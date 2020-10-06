<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\CoordComissaoCientifica;
use App\CoordComissaoOrganizadora;
use App\MembroComissao;
use App\Revisor;
use App\Coautor;
use App\CoordenadorEvento;
use App\Participante;

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

        if (isset($permissoes['coordComissaoCientifica'])) {
            if ( !isset($usuario->coordComissaoCientifica) ) {
                $usuario->coordComissaoCientifica()->save(new CoordComissaoCientifica());
            }              
        }elseif(isset($usuario->coordComissaoCientifica) && !isset($permissoes['coordComissaoCientifica'])){
            $usuario->coordComissaoCientifica()->delete();
        }

        if (isset($permissoes['coordComissaoOrganizadora'])) {
            if ( !isset($usuario->coordComissaoOrganizadora) ) {
                $usuario->coordComissaoOrganizadora()->save(new CoordComissaoOrganizadora());
            }
        }elseif(isset($usuario->coordComissaoOrganizadora) && !isset($permissoes['coordComissaoOrganizadora'])){
            $usuario->coordComissaoOrganizadora()->delete();
        }

        if (isset($permissoes['membroComissao'])) {
            if ( !isset($usuario->membroComissao) ) {
                $usuario->membroComissao()->save(new MembroComissao());
            }  
            
        }elseif(isset($usuario->membroComissao) && !isset($permissoes['membroComissao'])){
            $usuario->membroComissao()->delete();
        }

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

        if (isset($permissoes['coautor'])) {
            if ( !isset($usuario->coautor) ) {
                $usuario->coautor()->save(new Coautor());
            }              
        }elseif(isset($usuario->coautor) && !isset($permissoes['coautor'])){
            $usuario->coautor()->delete();
        }

        if (isset($permissoes['participante'])) {
            if ( !isset($usuario->participante) ) {
                $usuario->participante()->save(new Participante()); 
            }                          
        }elseif(isset($usuario->participante) && !isset($permissoes['participante'])){
            $usuario->participante()->delete();
        }

        if (isset($permissoes['coordEvento'])) {
            if ( !isset($usuario->coordEvento) ) {
                $usuario->coordEvento()->save(new CoordenadorEvento()); 
            }
        }elseif(isset($usuario->coordEvento) && !isset($permissoes['coordEvento'])){
            $usuario->coordEvento()->delete();
        }


    	return redirect()->route('cientifica.usuarios')->with('success', 'Permissão alterada!');

    }
}
