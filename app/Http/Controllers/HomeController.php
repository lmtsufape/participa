<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {        
        $eventos = \App\Evento::all();        
        
          if(Auth::user()->administradors != null){            
            return view('administrador.index');

          }else if(Auth::user()->coordComissaoCientifica != null){            
            return view('coordComissaoCientifica.index');

          }else if(Auth::user()->coordComissaoOrganizadora != null){            
            return view('coordComissaoOrganizadora.index');

          }else if(Auth::user()->membroComissao != null){            
            return view('membroComissao.index');

          }else if(Auth::user()->revisor != null){            
            return redirect( route('revisor.index') );

          }else if(Auth::user()->coautor != null){            
            return view('coautor.index');
            
          }else if(Auth::user()->coordEvento != null){  
            return view('coordenador.index');
            
          }
          else {
            return view('home');
          } 
              
    }

    public function downloadArquivo(Request $request){
        if (Storage::disk()->exists('app/'.$request->file)) {
            return response()->download(storage_path('app/'.$request->file));
        }
        return abort(404);
  	}
}
