<?php

namespace App\Http\Controllers;

use App\Atividade;
use App\Evento;
use App\TipoAtividade;
use Illuminate\Http\Request;

class AtividadeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $evento = Evento::find($id);
        $atividades = Atividade::where('eventoId', $id)->get();
        $tipoDeAtividades = TipoAtividade::all();
        return view('coordenador.programacao.atividades')->with(['evento' => $evento,
                                                                 'atividades' => $atividades,
                                                                 'tipos' => $tipoDeAtividades]);
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
        // dd($request);
        $validated = $request->validate([
            'idNovaAtividade'       => ['required', 'integer'],
            'titulo'                => ['required', 'max:20'],
            'tipo'                  => ['required', 'string'],
            'descricao'             => ['required', 'max:500'],
            'carga_horaria'         => ['nullable', 'string'],
            'vagas'                 => ['nullable', 'string'],
            'valor'                 => ['nullable', 'string'],
            'local'                 => ['required', 'string'],
            'duracaoDaAtividade'    => ['required', 'string'],
            'nomeDoConvidado'       => ['nullable', 'string'],
            'emailDoConvidado'      => ['nullable', 'string'],
            'funçãoDoConvidado'     => ['nullable', 'string'],

            // Validação das datas
            'primeiroDia'   => ['required_if:duracaoAtividade,1', 'date'],
            'segundoDia'    => ['required_if:duracaoAtividade,1,2', 'date'],
            'terceiroDia'   => ['required_if:duracaoAtividade,1,2,3', 'date'],
            'quartoDia'     => ['required_if:duracaoAtividade,1,2,3,4', 'date'],
            'quintoDia'     => ['required_if:duracaoAtividade,1,2,3,4,5', 'date'],
            'sextoDia'      => ['required_if:duracaoAtividade,1,2,3,4,5,6', 'date'],
            'setimoDia'     => ['required_if:duracaoAtividade,1,2,3,4,5,6,7', 'date'],
            
            // Validação das horas
            'inicio'        => ['required_if:duracaoAtividade,1', 'time'],
            'segundoInicio' => ['required_if:duracaoAtividade,1,2', 'time'],
            'terceiroInicio'=> ['required_if:duracaoAtividade,1,2,3', 'time'],
            'quartoInicio'  => ['required_if:duracaoAtividade,1,2,3,4', 'time'],
            'quintoInicio'  => ['required_if:duracaoAtividade,1,2,3,4,5', 'time'],
            'sextoInicio'   => ['required_if:duracaoAtividade,1,2,3,4,5,6', 'time'],
            'setimoInicio'  => ['required_if:duracaoAtividade,1,2,3,4,5,6,7', 'time'],
            'fim'           => ['required_if:duracaoAtividade,1', 'time', 'after:inicio'],
            'segundoFim'    => ['required_if:duracaoAtividade,1,2', 'time', 'after:segundoInicio'],
            'terceiroFim'   => ['required_if:duracaoAtividade,1,2,3', 'time', 'after:terceiroInicio'],
            'quartoFim'     => ['required_if:duracaoAtividade,1,2,3,4', 'time', 'after:quartoInicio'],
            'quintoFim'     => ['required_if:duracaoAtividade,1,2,3,4,5', 'time', 'after:quintoInicio'],
            'sextoFim'      => ['required_if:duracaoAtividade,1,2,3,4,5,6', 'time', 'after:sextoInicio'],
            'setimoFim'     => ['required_if:duracaoAtividade,1,2,3,4,5,6,7', 'time', 'after:setimoInicio'],
        ]);

        dd('tudo ok');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Atividade  $atividade
     * @return \Illuminate\Http\Response
     */
    public function show(Atividade $atividade)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Atividade  $atividade
     * @return \Illuminate\Http\Response
     */
    public function edit(Atividade $atividade)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Atividade  $atividade
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // ver um modo de retornar o erro
        $validated = $request->validate([
            'idAtividade' => 'required',
            'titulo' => 'required',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Atividade  $atividade
     * @return \Illuminate\Http\Response
     */
    public function destroy(Atividade $atividade)
    {
        //
    }
}
