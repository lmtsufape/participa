<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Submissao\Area;
use App\Models\Users\User;
use App\Models\Submissao\Endereco;
use App\Models\Submissao\Trabalho;
use App\Models\Submissao\Evento;
use App\Models\Submissao\Modalidade;
use App\Models\Users\Coautor;
use App\Models\Users\Revisor;
use App\Models\Users\ComissaoEvento;
use App\Http\Controllers\Controller;
use App\Models\Submissao\Certificado;
use App\Models\Submissao\Palestra;
use App\Models\Submissao\TipoComissao;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    //
    function perfil($pais = null){
        $user = User::find(Auth::user()->id);
        $end = $user->endereco;
        if($pais) {
            if ($pais != 'brasil') {
                app()->setLocale('en');
            } else {
                app()->setLocale('pt-BR');
            }
        } elseif ($end && $end->pais != 'brasil') {
            app()->setLocale('en');
        } else {
            app()->setLocale('pt-BR');
        }
        $areas = Area::orderBy('nome')->get();
        return view('user.perfilUser', compact('user', 'end', 'areas', 'pais'));
    }
    function editarPerfil(Request $request){
        if ($request->passaporte != null &&  $request->cpf != null) {
            $request->merge(['passaporte' => null]);
        }
        // dd($request->all());

        if(Auth()->user()->usuarioTemp == true){
            $user = User::find($request->id);



            $validator = $request->validate([
                'name' => 'bail|required|string|max:255',
                'cpf'           => ($request->passaporte ==null ? ['bail','required','cpf'] : 'nullable'),
                'passaporte'    => ($request->cpf ==null ? 'bail|required|max:10' : 'nullable'),
                'celular' => 'required|string|max:20',
                'instituicao' => 'required|string| max:255',
                'especialidade' => 'nullable|string',
                'rua' => 'required|string|max:255',
                'numero' => 'required|string',
                'bairro' => 'required|string|max:255',
                'cidade' => 'required|string|max:255',
                'complemento' => 'nullable|string|max:255',
                'uf' => 'required|string',
                'cep' => 'required|string',
                'password' => 'required|string|min:8|confirmed',
                'pais' => 'required',
                // 'primeiraArea' => 'required|string',
            ]);

            // criar endereço
            $end = new Endereco($validator);
            $end->save();

            // Atualizar dados não preenchidos de User

            $user->name = $request->input('name');
            $user->cpf = $request->input('cpf');
            $user->passaporte = $request->input('passaporte');
            $user->celular = $request->input('full_number');
            $user->instituicao = $request->input('instituicao');
            $user->password = bcrypt($request->password);
            if ($request->input('especialidade') != null) {
                $user->especProfissional = $request->input('especialidade');
            }
            $user->usuarioTemp = null;
            $user->enderecoId = $end->id;
            $user->save();

            // if ($user->revisor != null) {
            //     $revisor = $user->revisor;
            //     $revisor->areaId = $request->primeiraArea;
            //     if ($request->segundaArea != null) {
            //         $revisor->area_alternativa_id = $request->segundaArea;
            //     }
            //     $revisor->save();
            // }

            return back()->with(['message' => "Atualizado com sucesso!"]);

        }

        else {
            if ($request->passaporte != null &&  $request->cpf != null) {
                $request->merge(['passaporte' => null]);
            }
            // dd($request);
            $user = User::find($request->id);
            $validator = $request->validate([
                'name' => 'required|string|max:255',
                'cpf'           => ($request->passaporte  ==null ? ['bail','required','cpf'] : 'nullable'),
                'passaporte'    => ($request->cpf == null && $request->cpf ==null? ['bail','required','max:10'] : ['nullable']),
                'celular' => 'required|string|max:20',
                'instituicao' => 'required|string| max:255',
                // 'especProfissional' => 'nullable|string',
                'rua' => 'required|string|max:255',
                'numero' => 'required|string',
                'bairro' => 'required|string|max:255',
                'complemento' => 'nullable|string|max:255',
                'cidade' => 'required|string|max:255',
                'uf' => 'required|string',
                'cep' => 'required|string',
                'pais' => 'required',
                'email'             => 'required|string|email|max:255',
                'senha_atual'       => 'nullable|string|min:8',
                'password'          => 'nullable|string|min:8',
                'password-confirm'  => 'nullable|string|min:8',
            ]);

            // User

            if ($request->senha_atual != null) {
                if (!(Hash::check($request->senha_atual, $user->password))) {
                    return redirect()->back()->withErrors(['senha_atual' => "A senha digitada não correspondente a senha cadastrada."])->withInput($validator);
                }

                if (!($request->password != null)) {
                    return redirect()->back()->withErrors(['password' => "Digite a nova senha."])->withInput($validator);
                }

                if (!($request->input("password-confirm") != null)) {
                    return redirect()->back()->withErrors(['password-confirm' => "Digite a confirmação da senha."])->withInput($validator);
                }

                if (!($request->password == $request->input("password-confirm"))) {
                    return redirect()->back()->withErrors(['password' => "A confirmação não confere com a nova senha."])->withInput($validator);
                }

                $password = Hash::make($request->password);

                $user->password = $password;
            }

            if ($user->email != $request->email) {
                $check_user_email = User::where('email', $request->email)->first();
                if ($check_user_email == null) {
                    $user->email = $request->email;
                    $user->email_verified_at = null;
                } else {
                    return redirect()->back()->withErrors(['email' => "Já existe uma conta registrada com esse e-mail."])->withInput($validator);
                }
            }

            $user->name = $request->input('name');
            $user->cpf = $request->input('cpf');
            $user->passaporte = $request->input('passaporte');
            $user->celular = $request->input('full_number');
            $user->instituicao = $request->input('instituicao');
            // $user->especProfissional = $request->input('especProfissional');
            $user->usuarioTemp = null;
            $user->update();

            // endereço
            if($user->enderecoId == null){
                $end = new Endereco($request->all());
                $end->save();
                $user->enderecoId = $end->id;
                $user->update();
            }else{
                $end = Endereco::find($user->enderecoId);
                $end->fill($validator);
                $end->update();
            }
            // dd([$user,$end]);
            app()->setLocale('pt-BR');
            return back()->with(['message' => "Atualizado com sucesso!"]);

        }
    }

    public function meusCertificados()
    {
        $usuario = auth()->user();
        $tiposView = ['Apresentador','Comissão científica','Comissão organizadora','Revisor','Participante','Palestrante','Coordenador da comissao científica','Outras comissoes'];
        $certificadosPorTipo = $usuario->certificados->groupBy('tipo');
        $tipos = array_flip(Certificado::TIPO_ENUM);
        $comissoes = TipoComissao::find($usuario->certificados->pluck('pivot.comissao_id'));
        $palestras = Palestra::find($usuario->certificados->pluck('pivot.palestra_id'));
        $trabalhos = Trabalho::find($usuario->certificados->pluck('pivot.trabalho_id'));
        return view('user.meusCertificados', compact('tiposView', 'usuario', 'certificadosPorTipo', 'tipos', 'comissoes', 'palestras', 'trabalhos',));
    }

    public function meusTrabalhos(){
        $agora = Carbon::now();
        $user = Auth::user();
        $trabalhos = Trabalho::where('autorId', $user->id)->where('status', '!=', 'arquivado')->get();
        $comoCoautor = Coautor::where('autorId', $user->id)->first();

        $trabalhosCoautor = collect();

        if ($comoCoautor != null) {

            $trabalhosC = $comoCoautor->trabalhos;
            foreach ($trabalhosC as $trab) {
                if ($trab->autorId != auth()->user()->id) {
                    $trabalhosCoautor->push($trab);
                }
            }

        }

        return view('user.meusTrabalhos',[
                                            'trabalhos'           => $trabalhos,
                                            'trabalhosCoautor'    => $trabalhosCoautor,
                                            'agora'               => $agora,
                                        ]);
    }

    public function visualizarParecer(Request $request)
    {

        $trabalho = Trabalho::find($request->trabalhoId);
        $revisor = Revisor::find($request->revisorId);
        if(!$trabalho->getParecerAtribuicao($revisor->user) == 'encaminhado'){
            $this->authorize('permissaoVisualizarParecer', $trabalho);
        }
        $evento = Evento::find($request->eventoId);
        $modalidade = Modalidade::find($request->modalidadeId);
        $revisorUser = User::find($revisor->user_id);
        $respostas = collect();
        foreach ($modalidade->forms as $form) {
          foreach ($form->perguntas as $pergunta) {
            $respostas->push($pergunta->respostas->where('trabalho_id', $trabalho->id)->where('revisor_id', $revisor->id)->first());
          }
        }
        return view('user.visualizarParecer', compact('evento', 'modalidade', 'trabalho', 'revisorUser', 'respostas', 'revisor'));

    }

    public function searchUser(Request $request)
    {

        $user = User::where('email', $request->email)->first('name');

        return response()->json([
            'user' => [
                $user
            ]
        ]);
    }
}
