<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\ComissaoEvento;

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
            ]);
            
        $user = User::where('email',$request->input('emailMembroComissao'))->first();
        
        // dd($user->id);
        $comissaoEventos = new ComissaoEvento();
        
        $comissaoEventos->eventosId = $request->input('eventoId');
        $comissaoEventos->userId = $user->id;
        $comissaoEventos->save();

        return view('coordenador.detalhesEvento')->with('tela','comissao');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
