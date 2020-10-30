<?php

namespace App\Http\Controllers;

use App\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
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
        $validatedData = $request->validate([
          'nome'  =>  'required|string',
        ]);

        Area::create([
          'nome'      => $request->nome,
          'eventoId'  => $request->eventoId,
        ]);

        return redirect()->back()->with(['mensagem' => 'Área cadastrada com sucesso!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function show(Area $area)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function edit(Area $area)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nome_da_área' => 'required',
        ]);

        $area = Area::find($id);

        $area->nome = $request->input('nome_da_área');
        $area->update();

        return redirect()->back()->with(['mensagem' => 'Área atualizada com sucesso!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $area = Area::find($id);
        
        if (count($area->revisor) > 0) {
            return redirect()->back()->withErrors(['excluirAtividade' => 'Não é possível excluir, existem revisores ligados a essa área.']);
        }
        if (count($area->trabalho) > 0) {
            return redirect()->back()->withErrors(['excluirAtividade' => 'Não é possível excluir, existem trabalhos ligados a essa área.']);
        }

        $area->delete();

        return redirect()->back()->with(['mensagem' => 'Área excluida com sucesso!']);
    }
}
