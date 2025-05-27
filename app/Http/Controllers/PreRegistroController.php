<?php

namespace App\Http\Controllers;

use App\Models\PreRegistro;
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
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'cpf' => 'required|string|unique:users,cpf',
        ]);

        // Verifica se já existe um código ainda válido para esse e-mail ou CPF
        $registroExistente = PreRegistro::where(function ($query) use ($request) {
            $query->where('email', $request->email)
                ->orWhere('cpf', $request->cpf);
            })->where('expiracao', '>', Carbon::now())->first();

        if ($registroExistente) {
            // Já existe um código válido. Redireciona para a etapa de confirmação.
            return redirect()->route('inserirCodigo', ['email' => $request->email])->with(['sucesso' => "Você já solicitou um código! Verifique seu e-mail e insira-o abaixo."]);
        }

        do {
            $codigo = strtoupper(Str::random(8));
        } while (PreRegistro::where('codigo', $codigo)->exists());

        $expiracao = Carbon::now()->addMinutes(15);

        PreRegistro::create([
            'nome' => $request->nome,
            'cpf' => $request->cpf,
            'email' => $request->email,
            'codigo' => $codigo,
            'expiracao' => $expiracao,
        ]);

        Mail::to($request->email)->send(new \App\Mail\EmailCodigoPreRegistro($codigo));

        $email = $request->email;

        return redirect()->route('inserirCodigo', compact('email'))->with(['sucesso' => "Código de validação enviado para o e-mail informado!"]);
    }

    public function inserirCodigo($email)
    {
        return view('auth.confirmarCodigoPreRegistro', ['email' => $email]);
    }

    public function verificarCodigo(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'codigo' => 'required|string'
        ]);

        $entrar = PreRegistro::where('email', $request->email)
            ->where('codigo', $request->codigo)
            ->where('expiracao', '>', now())
            ->first();

        if(!$entrar) {
            return redirect()->route('inserirCodigo', ['email' => $request->email])->with('erro', "O código informado expirou ou está incorreto!");
        }

        // Aqui você pode armazenar na sessão ou gerar token temporário
        session(['verified_pre_registration' => $entrar->id]);

        return redirect()->route('register', ['locale' => app()->getLocale()])->with(['nome' => $entrar->nome, 'cpf' => $entrar->cpf, 'email' => $entrar->email, 'sucesso' => 'Código verificado! Prossiga com o cadastro.']);
    }
}