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
        ]);

        $validateDuracaoAtividade = $request->validate([
            // Validação das datas
            'primeiroDia'   => ($request->duracaoAtividade == 1 || $request->duracaoAtividade == 2 || $request->duracaoAtividade == 3 || $request->duracaoAtividade == 4 || $request->duracaoAtividade == 5 || $request->duracaoAtividade == 6 || $request->duracaoAtividade == 7) ? ['required', 'date'] : [''],
            'segundoDia'    => ($request->duracaoAtividade == 2 || $request->duracaoAtividade == 3 || $request->duracaoAtividade == 4 || $request->duracaoAtividade == 5 || $request->duracaoAtividade == 6 || $request->duracaoAtividade == 7) ? ['required', 'date', 'after:primeiroDia'] : [''],
            'terceiroDia'   => ($request->duracaoAtividade == 3 || $request->duracaoAtividade == 4 || $request->duracaoAtividade == 5 || $request->duracaoAtividade == 6 || $request->duracaoAtividade == 7) ? ['required', 'date', 'after:segundoDia'] : [''],
            'quartoDia'     => ($request->duracaoAtividade == 4 || $request->duracaoAtividade == 5 || $request->duracaoAtividade == 6 || $request->duracaoAtividade == 7) ? ['required', 'date', 'after:terceiroDia'] : [''],
            'quintoDia'     => ($request->duracaoAtividade == 5 || $request->duracaoAtividade == 6 || $request->duracaoAtividade == 7) ? ['required', 'date', 'after:quartoDia'] : [''],
            'sextoDia'      => ($request->duracaoAtividade == 6 || $request->duracaoAtividade == 7) ? ['required', 'date', 'after:quintoDia'] : [''],
            'setimoDia'     => ($request->duracaoAtividade == 7) ? ['required', 'date', 'after:sextoDia'] : [''],
            
            // Validação das horas
            'inicio'        => ($request->duracaoAtividade == 1 || $request->duracaoAtividade == 2 || $request->duracaoAtividade == 3 || $request->duracaoAtividade == 4 || $request->duracaoAtividade == 5 || $request->duracaoAtividade == 6 || $request->duracaoAtividade == 7) ? ['time'] : [''],
            'segundoInicio' => ($request->duracaoAtividade == 2 || $request->duracaoAtividade == 3 || $request->duracaoAtividade == 4 || $request->duracaoAtividade == 5 || $request->duracaoAtividade == 6 || $request->duracaoAtividade == 7) ? ['time'] : [''],
            'terceiroInicio'=> ($request->duracaoAtividade == 3 || $request->duracaoAtividade == 4 || $request->duracaoAtividade == 5 || $request->duracaoAtividade == 6 || $request->duracaoAtividade == 7) ? ['time'] : [''],
            'quartoInicio'  => ($request->duracaoAtividade == 4 || $request->duracaoAtividade == 5 || $request->duracaoAtividade == 6 || $request->duracaoAtividade == 7) ? ['time'] : [''],
            'quintoInicio'  => ($request->duracaoAtividade == 5 || $request->duracaoAtividade == 6 || $request->duracaoAtividade == 7) ? ['time'] : [''],
            'sextoInicio'   => ($request->duracaoAtividade == 6 || $request->duracaoAtividade == 7) ? ['time'] : [''],
            'setimoInicio'  => ($request->duracaoAtividade == 7) ? ['time'] : [''],
            'fim'           => ($request->duracaoAtividade == 1 || $request->duracaoAtividade == 2 || $request->duracaoAtividade == 3 || $request->duracaoAtividade == 4 || $request->duracaoAtividade == 5 || $request->duracaoAtividade == 6 || $request->duracaoAtividade == 7) ? ['time', 'after_time:inicio'] : [''],
            'segundoFim'    => ($request->duracaoAtividade == 2 || $request->duracaoAtividade == 3 || $request->duracaoAtividade == 4 || $request->duracaoAtividade == 5 || $request->duracaoAtividade == 6 || $request->duracaoAtividade == 7) ? ['time', 'after_time:segundoInicio'] : [''],
            'terceiroFim'   => ($request->duracaoAtividade == 3 || $request->duracaoAtividade == 4 || $request->duracaoAtividade == 5 || $request->duracaoAtividade == 6 || $request->duracaoAtividade == 7) ? ['time', 'after_time:terceiroInicio'] : [''],
            'quartoFim'     => ($request->duracaoAtividade == 4 || $request->duracaoAtividade == 5 || $request->duracaoAtividade == 6 || $request->duracaoAtividade == 7) ? ['time', 'after_time:quartoInicio'] : [''],
            'quintoFim'     => ($request->duracaoAtividade == 5 || $request->duracaoAtividade == 6 || $request->duracaoAtividade == 7) ? ['time', 'after_time:quintoInicio'] : [''],
            'sextoFim'      => ($request->duracaoAtividade == 6 || $request->duracaoAtividade == 7) ? ['time', 'after_time:sextoInicio'] : [''],
            'setimoFim'     => ($request->duracaoAtividade == 7) ? ['time', 'after_time:setimoInicio'] : [''],
        ]);
        $atividade = new Atividade();
        dd($atividade);
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
