<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Evento;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {   
        $user = Auth::user();
        if($user->administradors != null){    
            $eventos = Evento::all();        
            return view('administrador.index', ['eventos' => $eventos]);

          }else if($user->coordComissaoCientifica != null){ 
            $eventos = $user->coordComissaoCientifica->eventos;           
            return view('coordenador.index', ['eventos' => $eventos]);

          }else if($user->coordComissaoOrganizadora != null){ 
            $eventos = $user->coordComissaoOrganizadora->eventos;   
            return view('coordenador.index', ['eventos' => $eventos]);

          }else if($user->membroComissao != null){    
            $eventos = $user->membroComissao->eventos;        
            return view('coordenador.index', ['eventos' => $eventos]);

          }else if($user->revisor != null){   
            $eventos = $user->revisor->evento;        
            return view('coordenador.index', ['eventos' => $eventos]);

          }else if($user->coautor != null){
            $eventos = $user->coautor->eventos;            
            return view('coordenador.index', ['eventos' => $eventos]);
             
          }else {
            return view('home');
          } 
    }
}
