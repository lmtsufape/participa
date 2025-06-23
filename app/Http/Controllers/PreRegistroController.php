<?php

namespace App\Http\Controllers;

use App\Models\PreRegistro;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

use function Laravel\Prompts\error;

class PreRegistroController extends Controller
{
    public function preRegistro()
    {
        return view('auth.preRegistro');
    }

    public function enviarCodigo(Request $request)
    {
        $rules = [
            'nome' => 'required|string|max:255',
            'email' => 'required|email',
            'pais' => 'required|string',
        ];

        $messages = [
            'email.unique' => 'Este email já está cadastrado no sistema.',
        ];

        if ($request->filled('cpf')) {
            $rules['cpf'] = 'required|string|unique:users,cpf';
            $messages['cpf.unique'] = 'Este CPF já está cadastrado no sistema.';
        } elseif ($request->filled('cnpj')) {
            $rules['cnpj'] = 'required|string|unique:users,cnpj';
            $messages['cnpj.unique'] = 'Este CNPJ já está cadastrado no sistema.';
        } elseif ($request->filled('passaporte')) {
            $rules['passaporte'] = 'required|string|min:6|max:9|regex:/^[A-Za-z0-9]{6,9}$/|unique:users,passaporte';
            $messages['passaporte.unique'] = 'Este passaporte já está cadastrado no sistema.';
            $messages['passaporte.regex'] = 'O passaporte deve conter apenas letras e números (sem símbolos) e ter entre 6 e 9 caracteres.';
        }

        $request->validate($rules, $messages);

        // Verifica se existe um usuário com soft delete
        $userDeletado = User::withTrashed()->where('email', $request->email)->first();
        
        if ($userDeletado && $userDeletado->deleted_at === null) {
            return back()->withErrors(['email' => 'Este email já está cadastrado no sistema.']);
        }

        // Verifica se já existe um código ainda válido para esse e-mail ou CPF
        $registroExistente = PreRegistro::where(function ($query) use ($request) {
            $query->where('email', $request->email);

            if($request->filled('cpf')) {
                $query->orWhere('cpf', $request->cpf);
            } elseif($request->filled('cnpj')) {
                $query->orWhere('cnpj', $request->cnpj);
            } elseif($request->filled('passaporte')) {
                $query->orWhere('passaporte', $request->passaporte);
            }
        })->where('expiracao', '>', Carbon::now())->first();

        if ($registroExistente) {
            // Já existe um código válido. Redireciona para a etapa de confirmação.
            return redirect()->route('inserirCodigo', ['id' => $registroExistente->id])->with(['sucesso' => "Você já solicitou um código! Verifique seu e-mail e insira-o abaixo."]);
        }

        do {
            $codigo = strtoupper(Str::random(8));
        } while (PreRegistro::where('codigo', $codigo)->exists());

        $expiracao = Carbon::now()->addMinutes(15);

        $preRegistro = PreRegistro::create([
                            'nome' => $request->nome,
                            'cpf' => $request->cpf,
                            'email' => $request->email,
                            'codigo' => $codigo,
                            'pais' => $request->pais,
                            'expiracao' => $expiracao,
                            'cnpj' => $request->cnpj,
                            'passaporte' => $request->passaporte,
                        ]);

        Mail::to($request->email)->send(new \App\Mail\EmailCodigoPreRegistro($preRegistro));

        $id = $preRegistro->id;

        return redirect()->route('inserirCodigo', compact('id'))->with(['sucesso' => "Código de validação enviado para o e-mail informado!"]);
    }

    public function inserirCodigo($id)
    {
        return view('auth.confirmarCodigoPreRegistro', ['id' => $id]);
    }

    public function verificarCodigo(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'codigo' => 'required|string'
        ]);

        $entrar = PreRegistro::where('id', $request->id)
            ->where('codigo', $request->codigo)
            ->where('expiracao', '>', now())
            ->first();

        if(!$entrar) {
            return redirect()->route('inserirCodigo', ['id' => $request->id])->with('erro', "O código informado expirou ou está incorreto!");
        }

        session(['verified_pre_registration' => $entrar->id]);

        return redirect()->route('register', ['locale' => app()->getLocale()])->with(['nome' => $entrar->nome, 'cpf' => $entrar->cpf, 'cnpj' => $entrar->cnpj, 'passaporte' => $entrar->passaporte, 'email' => $entrar->email, 'pais' => $entrar->pais, 'sucesso' => 'Código verificado! Prossiga com o cadastro.']);
    }
}