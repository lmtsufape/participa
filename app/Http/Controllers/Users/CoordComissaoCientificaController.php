<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Users\User;
use App\Models\Users\CoordComissaoCientifica;
use App\Models\Users\CoordComissaoOrganizadora;
use App\Models\Users\MembroComissao;
use App\Models\Users\Revisor;
use App\Models\Users\Coautor;
use App\Models\Users\CoordenadorEvento;
use App\Models\Users\Participante;
use App\Mail\EmailParaUsuarioNaoCadastrado;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class CoordComissaoCientificaController extends Controller
{
    public function index()
    {
    	return view('coordComissaoCientifica.index');
    }

    public function editais()
    {
    	return view('coordComissaoCientifica.index');
    }

    public function usuarios()
    {
    	$usuarios = User::doesntHave('administradors')->paginate(10);
    	//dd($usuarios);
    	return view('coordComissaoCientifica.listarUsuarios', compact('usuarios'));
    }

    public function areas()
    {
    	return view('coordComissaoCientifica.index');
    }

    public function permissoes(Request $request)
    {
        $usuario = User::find($request->user_id);

        $permissoes = $request->all();
        // dd($permissoes );        

        if (isset($permissoes['revisor'])) {
            if ( !isset($usuario->revisor) ) {
                $revisor = new Revisor();
                $revisor->trabalhosCorrigidos = 0;
                $revisor->correcoesEmAndamento = 0;
                $revisor->user_id = $usuario->id;
                $revisor->user_id = $usuario->id;
                $revisor->user_id = $usuario->id;
                $revisor->save();

                $usuario->revisor()->save($revisor);
            }  
                       
        }elseif(isset($usuario->revisor) && !isset($permissoes['revisor'])){
            $usuario->revisor()->delete();
        }



    	return redirect()->back()->with('success', 'Permissão alterada!');

    }

    public function novoUsuario(Request $request)
    {   
        $validationData = $this->validate($request,[
            'emailUsuario'=>'required|string|email',
            
            ]);

        $user = User::where('email',$request->input('emailUsuario'))->first();
        if($user == null){
            $passwordTemporario = Str::random(8);
            Mail::to($request->emailUsuario)->send(new EmailParaUsuarioNaoCadastrado(Auth()->user()->name, '  ', "Revisor", " ", $passwordTemporario));
            $user = User::create([
              'name' => $request->nomeUsuario,
              'email' => $request->emailUsuario,
              'password' => bcrypt($passwordTemporario),
              'usuarioTemp' => true,
            ]);

            $user->revisor()->create([ 'user_id' => $user->id ]);
            
        }

        return redirect()->back()->with('success', 'E-mail enviado!');

    }
}
