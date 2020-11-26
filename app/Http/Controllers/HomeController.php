<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Submissao\Evento;

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
            // dd('adm');       
            return view('administrador.index', ['eventos' => $eventos]);

          }else if($user->coordComissaoCientifica != null){ 
            $eventos = $user->coordComissaoCientifica->eventos; 
            // dd('CCC');          
            return view('coordenador.index', ['eventos' => $eventos]);

          }else if($user->coordComissaoOrganizadora != null){ 
            $eventos = $user->coordComissaoOrganizadora->eventos;
            // dd('CCO');   
            return view('coordenador.index', ['eventos' => $eventos]);

          }else if($user->membroComissao != null){    
            $eventos = $user->membroComissao->eventos;
            // dd('MC');        
            return view('coordenador.index', ['eventos' => $eventos]);

          }else if($user->revisor != null){   
            $eventos = Evento::all();  
            // dd('R');    
            return view('coordenador.index', ['eventos' => $eventos]);

          }else if($user->coautor != null){
            $eventos = $user->coautor->eventos;            
            // dd('CA');
            return view('coordenador.index', compact('eventos'));
            
          }else if($user->coordEvento != null){
            // $eventos = $user->coordEvento->evento;  
            $eventos = Evento::all(); 
            // dd('CE');     
            return view('coordenador.index', compact('eventos'));
            
          }else if($user->participante != null){

            $eventos = Evento::all();  
            // dd('P');          
            return view('coordenador.index', compact('eventos'));
            
          }else {
            return view('home');
          } 
    }
}
