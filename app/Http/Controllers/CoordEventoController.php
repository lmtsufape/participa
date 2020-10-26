<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Evento;

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
