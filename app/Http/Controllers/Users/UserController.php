<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Submissao\Area;
use App\Models\Users\User;
use App\Models\Submissao\Endereco;
use App\Models\Submissao\Trabalho;
use App\Models\Users\Coautor;
use App\Models\Users\ComissaoEvento;
use App\Http\Controllers\Controller;

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
        // dd($request->name);

        if(Auth()->user()->usuarioTemp == true){
            $validator = $request->validate([
                'name' => 'required|string|max:255',
                'cpf' => 'required|cpf|unique:users',
                'celular' => 'required|string|telefone',
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
            $user = User::find($request->id);
            $user->name = $request->input('name');
            $user->cpf = $request->input('cpf');
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

            return redirect(route('home'));
            
        }

        else {
            $validator = $request->validate([
                'name' => 'required|string|max:255',
                'cpf' => 'required|cpf',
                'celular' => 'required|string|telefone',
                'instituicao' => 'required|string| max:255',
                // 'especProfissional' => 'nullable|string',
                'rua' => 'required|string|max:255',
                'numero' => 'required|string',
                'bairro' => 'required|string|max:255',
                'complemento' => 'nullable|string|max:255',
                'cidade' => 'required|string|max:255',
                'uf' => 'required|string',
                'cep' => 'required|string',
            ]);

            // User
            $user = User::find($request->id);
            $user->name = $request->input('name');
            $user->cpf = $request->input('cpf');
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
            return redirect(route('home'));

        }
    }

    public function meusTrabalhos(){

        $user = Auth::user();
        $trabalhos = Trabalho::where('autorId', $user->id)->get();
        $comoCoautor = Coautor::where('autorId', $user->id)->first();
        
        return view('user.meusTrabalhos',[
                                            'trabalhos'           => $trabalhos,
                                            'trabalhosCoautor'    => $comoCoautor->trabalhos,
                                        ]);
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
