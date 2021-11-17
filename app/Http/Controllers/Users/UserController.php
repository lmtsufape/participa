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
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    //
    function perfil(){
        $user = User::find(Auth::user()->id);
        $end = $user->endereco;
        $areas = Area::orderBy('nome')->get();
        return view('user.perfilUser',['user'=>$user,'end'=>$end,'areas'=>$areas]);
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
                'cpf'           => ($request->passaporte ==null ? ['bail','required','cpf','unique:users'] : 'nullable'),
                'passaporte'    => ($request->cpf ==null ? 'bail|required|max:10|unique:users' : 'nullable'),
                'celular' => 'required|string|max:16',
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
                // 'primeiraArea' => 'required|string',
            ]);

            // criar endereço
            $end = new Endereco();
            $end->rua = $request->input('rua');
            $end->numero = $request->input('numero');
            $end->bairro = $request->input('bairro');
            $end->cidade = $request->input('cidade');
            $end->complemento = $request->input('complemento');
            $end->uf = $request->input('uf');
            $end->cep = $request->input('cep');

            $end->save();

            // Atualizar dados não preenchidos de User

            $user->name = $request->input('name');
            $user->cpf = $request->input('cpf');
            $user->passaporte = $request->input('passaporte');
            $user->celular = $request->input('celular');
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
                'cpf'           => ($request->passaporte  ==null ? ['bail','required','cpf',Rule::unique('users')->ignore($user->id)] : 'nullable'),
                'passaporte'    => ($request->cpf == null && $request->cpf ==null? ['bail','required','max:10',Rule::unique('users')->ignore($user->id)] : ['nullable']),
                'celular' => 'required|string|max:16',
                'instituicao' => 'required|string| max:255',
                // 'especProfissional' => 'nullable|string',
                'rua' => 'required|string|max:255',
                'numero' => 'required|string',
                'bairro' => 'required|string|max:255',
                'complemento' => 'nullable|string|max:255',
                'cidade' => 'required|string|max:255',
                'uf' => 'required|string',
                'cep' => 'required|string',

                'senha_atual'       => 'nullable|string|min:8',
                'password'          => 'nullable|string|min:8',
                'password-confirm'  => 'nullable|string|min:8',
            ]);

            // User
            
            if ($request->senha_atual != null) {
                if (!(Hash::check($request->senha_atual, $user->password))) {
                    return redirect()->back()->withErrors(['senha_atual' => "A senha digitada não correspondente a senha cadastrada."])->withInput($validator);
                }

                if ($request->password != null && $request->input("password-confirm") != null) {
                    if (!($request->password == $request->input("password-confirm"))) {
                        return redirect()->back()->withErrors(['password' => "A confirmação não confere com a nova senha."])->withInput($validator);
                    }
                    
                    $password = Hash::make($request->password);

                    $user->password = $password;
                }
            }

            $user->name = $request->input('name');
            $user->cpf = $request->input('cpf');
            $user->passaporte = $request->input('passaporte');
            $user->celular = $request->input('celular');
            $user->instituicao = $request->input('instituicao');
            // $user->especProfissional = $request->input('especProfissional');
            $user->usuarioTemp = null;
            $user->update();

            // endereço
            $end = Endereco::find($user->enderecoId);
            $end->rua = $request->input('rua');
            $end->numero = $request->input('numero');
            $end->bairro = $request->input('bairro');
            $end->cidade = $request->input('cidade');
            $end->complemento = $request->input('complemento');
            $end->uf = $request->input('uf');
            $end->cep = $request->input('cep');

            $end->update();
            // dd([$user,$end]);
            return back()->with(['message' => "Atualizado com sucesso!"]);

        }
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

        $evento = Evento::find($request->eventoId);
        $modalidade = Modalidade::find($request->modalidadeId);
        $trabalho = Trabalho::find($request->trabalhoId);
        $revisor = Revisor::find($request->revisorId);
        $revisorUser = User::find($revisor->user_id);
        $this->authorize('permissaoVisualizarParecer', $trabalho);

        return view('user.visualizarParecer', compact('evento', 'modalidade', 'trabalho', 'revisorUser'));

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
