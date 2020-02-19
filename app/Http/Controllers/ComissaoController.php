<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\ComissaoEvento;
use App\Evento;
use App\Area;
use App\Revisor;

class ComissaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validationData = $this->validate($request,[
            'emailMembroComissao'=>'required|string|email',
            'especProfissional'=>'required|string',
            ]);
            
        $user = User::where('email',$request->input('emailMembroComissao'))->first();
        
        // dd($user->id);
        $comissaoEventos = new ComissaoEvento();
        
        $comissaoEventos->eventosId = $request->input('eventoId');
        $comissaoEventos->userId = $user->id;
        $comissaoEventos->especProfissional = $request->input('especProfissional');
        $comissaoEventos->save();


        $evento = Evento::find($request->input('eventoId'));
        $ComissaoEvento = ComissaoEvento::where('eventosId',$evento->id)->get();
        $areas = Area::where('eventoId', $evento->id)->get();
        $revisores = Revisor::where('eventoId', $evento->id)->get();
        // dd($ComissaoEventos);
        $ids = [];
        foreach($ComissaoEvento as $ce){
          array_push($ids,$ce->userId);
        }
        $users = User::find($ids);
        return view('coordenador.detalhesEvento', [
                                                        'evento'    => $evento,
                                                        'areas'     => $areas,
                                                        'revisores' => $revisores,
                                                        'users'     => $users,
                                                    ]);
    }

   
    public function coordenadorComissao(Request $request){

        

        $evento = Evento::find($request->input('eventoId'));
        $evento->coordComissaoId = $request->input('coordComissaoId');
        $evento->save();

        $ComissaoEvento = ComissaoEvento::where('eventosId',$evento->id)->get();
        $areas = Area::where('eventoId', $evento->id)->get();
        $revisores = Revisor::where('eventoId', $evento->id)->get();
        // dd($ComissaoEventos);
        $ids = [];
        foreach($ComissaoEvento as $ce){
          array_push($ids,$ce->userId);
        }
        $users = User::find($ids);
        return view('coordenador.detalhesEvento', [
                                                        'evento'    => $evento,
                                                        'areas'     => $areas,
                                                        'revisores' => $revisores,
                                                        'users'     => $users,
                                                    ]);
    }

    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
