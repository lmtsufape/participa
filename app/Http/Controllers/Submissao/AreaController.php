<?php

namespace App\Http\Controllers\Submissao;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAreaRequest;
use App\Http\Requests\UpdateAreaRequest;
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
    public function index(Request $request)
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $areas = Area::where('eventoId', $evento->id)->orderBy('ordem')->get();

        return view('coordenador.areas.index', [
            'evento' => $evento,
            'areas' => $areas,
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($evento_id)
    {
        $evento = Evento::findOrFail($evento_id);

        return view('coordenador.areas.create', compact('evento'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAreaRequest $request, $evento_id)
    {
        $evento = Evento::findOrFail($evento_id);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);

        $evento->areas()->create($request->validated());

        return redirect()->route('coord.areas.index', $evento->id)->with(['success' => 'Área cadastrada com sucesso!']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function edit($area_id)
    {
        $area = Area::with('evento')->findOrFail($area_id);
        $evento = $area->evento;

        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);

        return view('coordenador.areas.edit', compact('area', 'evento'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAreaRequest $request, $area_id)
    {
        $area = Area::with('evento')->findOrFail($area_id);
        $evento = $area->evento;
        
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);

        $area->update($request->validated());

        return redirect()->route('coord.areas.index', $evento->id)->with(['success' => 'Área atualizada com sucesso!']);
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
