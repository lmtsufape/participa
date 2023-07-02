<?php

namespace App\Http\Controllers\Submissao;

use App\Http\Controllers\Controller;
use App\Models\Submissao\Evento;
use App\Models\Submissao\TipoAtividade;
use Illuminate\Http\Request;

class TipoAtividadeController extends Controller
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
        //
    }

    // Salvar uma nova tipo de atividade
    public function storeAjax(Request $request)
    {
        $evento = Evento::find($request->evento_id);
        if (auth()->user()->id == $evento->coordenadorId || $evento->usuariosDaComissao->contains(auth()->user()) || $evento->usuariosDaComissaoOrganizadora->contains(auth()->user())) {
            if ($request->name == null) {
                return response()->json('Nome não contido na requição.', 404);
            }

            $tipo = new TipoAtividade();
            $tipo->descricao = $request->name;
            $tipo->evento_id = $evento->id;
            $tipo->save();

            $tiposAtividades = TipoAtividade::where('evento_id', '=', $evento->id)->get();

            return response()->json($tiposAtividades);
        } else {
            return response()->json('Usuário não autorizado.', 403);
        }
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
