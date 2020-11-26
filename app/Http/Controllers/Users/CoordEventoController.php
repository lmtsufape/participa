<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Models\Submissao\Evento;
use App\Http\Controllers\Controller;

class CoordEventoController extends Controller
{
	public function index()
	{	
		$eventos = Evento::all();
		return view('coordenador.index', ['eventos'=>$eventos]);
	}

    public function listaEventos()
    {
    	$eventos = Evento::all();
        
        return view('coordenador.lista_eventos',['eventos'=>$eventos]);
    }
}
