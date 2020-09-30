<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;

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

    	return redirect()->route('cientifica.usuarios')->with('success', 'Permissão alterada!');

    }
}
