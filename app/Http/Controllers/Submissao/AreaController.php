<?php

namespace App\Http\Controllers\Submissao;

use App\Http\Controllers\Controller;
use App\Models\Submissao\Area;
use App\Models\Submissao\Evento;
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);

        $validatedData = $request->validate([
            'nome' => 'required|string',
            'resumo' => 'nullable|string',
            'nome_en' => 'nullable|string',
            'resumo_en' => 'nullable|string',
            'nome_es' => 'nullable|string',
            'resumo_es' => 'nullable|string',
        ]);

        Area::create([
            'nome' => $request->nome,
            'resumo' => $request->resumo,
            'nome_en' => $request->nome_en,
            'resumo_en' => $request->resumo_en,
            'nome_es' => $request->nome_es,
            'resumo_es' => $request->resumo_es,
            'eventoId' => $request->eventoId,
        ]);

        return redirect()->back()->with(['success' => 'Área cadastrada com sucesso!']);
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
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nome_da_área' => 'required',
            'resumo' => 'nullable|string',
            'nome_en' => 'nullable|string',
            'resumo_en' => 'nullable|string',
            'nome_es' => 'nullable|string',
            'resumo_es' => 'nullable|string',
        ]);

        $area = Area::find($id);
        $evento = $area->evento;
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);

        $area->nome = $request->input('nome_da_área');
        $area->resumo = $request->input('resumo', null);
        $area->nome_en = $request->input('nome_da_área_en', null);
        $area->resumo_en = $request->input('resumo_en', null);
        $area->nome_es = $request->input('nome_da_área_es', null);
        $area->resumo_es = $request->input('resumo_es', null);
        $area->update();

        return redirect()->back()->with(['success' => 'Área atualizada com sucesso!']);
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
        $evento = $area->evento;
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);

        if (count($area->revisor) > 0) {
            return redirect()->back()->withErrors(['excluirAtividade' => 'Não é possível excluir, existem revisores ligados a essa área.']);
        }
        if (count($area->trabalho) > 0) {
            return redirect()->back()->withErrors(['excluirAtividade' => 'Não é possível excluir, existem trabalhos ligados a essa área.']);
        }

        $area->delete();

        return redirect()->back()->with(['success' => 'Área excluida com sucesso!']);
    }

    public function reorder(Request $request)
    {
        $order = $request->input('order', []);
        foreach ($order as $item) {
            Area::where('id', $item['id'])
                ->update(['ordem' => $item['position']]);
        }
        return response()->json(['status' => 'ok']);
    }
}
