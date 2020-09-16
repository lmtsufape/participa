<?php

namespace App\Http\Controllers;

use App\Atividade;
use App\Evento;
use App\TipoAtividade;
use App\DatasAtividade;
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
        $duracaoAtividades = [];
        $ids = [];

        $evento = Evento::find($id);
        $atividades = Atividade::where('eventoId', $id)->get();
        
        foreach ($atividades as $atv) {
            $datasAtividades = DatasAtividade::where('atividade_id', $atv->id)->get();
            array_push($ids, $atv->id);
            array_push($duracaoAtividades, count($datasAtividades));
        }

        $tipoDeAtividades = TipoAtividade::all();
        return view('coordenador.programacao.atividades')->with(['evento' => $evento,
                                                                 'atividades' => $atividades,
                                                                 'tipos' => $tipoDeAtividades,
                                                                 'duracaoAtividades' => $duracaoAtividades,
                                                                 'ids' => $ids]);
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
            'titulo'                => ['required', 'max:50'],
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
        $atividade->eventoId                    = $request->eventoId;
        $atividade->tipo_id                     = $request->tipo; 
        $atividade->titulo                      = $request->titulo;
        $atividade->vagas                       = $request->vagas;
        $atividade->valor                       = $request->valor;
        $atividade->descricao                   = $request->descricao;
        $atividade->local                       = $request->local;
        $atividade->palavras_chave              = $request->palavrasChaves;
        $atividade->carga_horaria               = $request->carga_horaria;
        $atividade->visibilidade_participante   = true;
        $atividade->save();

        if ($request->duracaoDaAtividade >= 1) {
            $dataAtividade = new DatasAtividade();
            $dataAtividade->data            = $request->primeiroDia;
            $dataAtividade->hora_inicio     = $request->inicio;
            $dataAtividade->hora_fim        = $request->fim;
            $dataAtividade->atividade_id    = $atividade->id;
            $dataAtividade->save();
        }
        if ($request->duracaoDaAtividade >= 2) {
            $dataAtividade = new DatasAtividade();
            $dataAtividade->data            = $request->segundoDia;
            $dataAtividade->hora_inicio     = $request->segundoInicio;
            $dataAtividade->hora_fim        = $request->segundoFim;
            $dataAtividade->atividade_id    = $atividade->id;
            $dataAtividade->save();
        }
        if ($request->duracaoDaAtividade >= 3) {
            $dataAtividade = new DatasAtividade();
            $dataAtividade->data            = $request->terceiroDia;
            $dataAtividade->hora_inicio     = $request->terceiroInicio;
            $dataAtividade->hora_fim        = $request->terceiroFim;
            $dataAtividade->atividade_id    = $atividade->id;
            $dataAtividade->save();
        }
        if ($request->duracaoDaAtividade >= 4) {
            $dataAtividade = new DatasAtividade();
            $dataAtividade->data            = $request->quartoDia;
            $dataAtividade->hora_inicio     = $request->quartoInicio;
            $dataAtividade->hora_fim        = $request->quartoFim;
            $dataAtividade->atividade_id    = $atividade->id;
            $dataAtividade->save();
        }
        if ($request->duracaoDaAtividade >= 5) {
            $dataAtividade = new DatasAtividade();
            $dataAtividade->data            = $request->quintoDia;
            $dataAtividade->hora_inicio     = $request->quintoInicio;
            $dataAtividade->hora_fim        = $request->quintoFim;
            $dataAtividade->atividade_id    = $atividade->id;
            $dataAtividade->save();
        }
        if ($request->duracaoDaAtividade >= 6) {
            $dataAtividade = new DatasAtividade();
            $dataAtividade->data            = $request->sextoDia;
            $dataAtividade->hora_inicio     = $request->sextoInicio;
            $dataAtividade->hora_fim        = $request->sextoFim;
            $dataAtividade->atividade_id    = $atividade->id;
            $dataAtividade->save();
        }
        if ($request->duracaoDaAtividade == 7) {
            $dataAtividade = new DatasAtividade();
            $dataAtividade->data            = $request->setimoDia;
            $dataAtividade->hora_inicio     = $request->setimoInicio;
            $dataAtividade->hora_fim        = $request->setimoFim;
            $dataAtividade->atividade_id    = $atividade->id;
            $dataAtividade->save();
        }
        
        return redirect()->back()->with(['mensagem' => 'Atividade cadastrada com sucesso!']);
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
        $validated = $request->validate([
            'idAtividade'       => ['required', 'integer'],
            'titulo'                => ['required', 'max:50'],
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
        
        $atividade = Atividade::find($id);
        $atividade->tipo_id                     = $request->tipo; 
        $atividade->titulo                      = $request->titulo;
        $atividade->vagas                       = $request->vagas;
        $atividade->valor                       = $request->valor;
        $atividade->descricao                   = $request->descricao;
        $atividade->local                       = $request->local;
        $atividade->palavras_chave              = $request->palavrasChaves;
        $atividade->carga_horaria               = $request->carga_horaria;
        $atividade->update();

        foreach ($atividade->datasAtividade as $dataAtv) {
            $dataAtv->delete();
        }

        if ($request->duracaoDaAtividade >= 1) {
            $dataAtividade = new DatasAtividade();
            $dataAtividade->data            = $request->primeiroDia;
            $dataAtividade->hora_inicio     = $request->inicio;
            $dataAtividade->hora_fim        = $request->fim;
            $dataAtividade->atividade_id    = $atividade->id;
            $dataAtividade->save();
        }
        if ($request->duracaoDaAtividade >= 2) {
            $dataAtividade = new DatasAtividade();
            $dataAtividade->data            = $request->segundoDia;
            $dataAtividade->hora_inicio     = $request->segundoInicio;
            $dataAtividade->hora_fim        = $request->segundoFim;
            $dataAtividade->atividade_id    = $atividade->id;
            $dataAtividade->save();
        }
        if ($request->duracaoDaAtividade >= 3) {
            $dataAtividade = new DatasAtividade();
            $dataAtividade->data            = $request->terceiroDia;
            $dataAtividade->hora_inicio     = $request->terceiroInicio;
            $dataAtividade->hora_fim        = $request->terceiroFim;
            $dataAtividade->atividade_id    = $atividade->id;
            $dataAtividade->save();
        }
        if ($request->duracaoDaAtividade >= 4) {
            $dataAtividade = new DatasAtividade();
            $dataAtividade->data            = $request->quartoDia;
            $dataAtividade->hora_inicio     = $request->quartoInicio;
            $dataAtividade->hora_fim        = $request->quartoFim;
            $dataAtividade->atividade_id    = $atividade->id;
            $dataAtividade->save();
        }
        if ($request->duracaoDaAtividade >= 5) {
            $dataAtividade = new DatasAtividade();
            $dataAtividade->data            = $request->quintoDia;
            $dataAtividade->hora_inicio     = $request->quintoInicio;
            $dataAtividade->hora_fim        = $request->quintoFim;
            $dataAtividade->atividade_id    = $atividade->id;
            $dataAtividade->save();
        }
        if ($request->duracaoDaAtividade >= 6) {
            $dataAtividade = new DatasAtividade();
            $dataAtividade->data            = $request->sextoDia;
            $dataAtividade->hora_inicio     = $request->sextoInicio;
            $dataAtividade->hora_fim        = $request->sextoFim;
            $dataAtividade->atividade_id    = $atividade->id;
            $dataAtividade->save();
        }
        if ($request->duracaoDaAtividade == 7) {
            $dataAtividade = new DatasAtividade();
            $dataAtividade->data            = $request->setimoDia;
            $dataAtividade->hora_inicio     = $request->setimoInicio;
            $dataAtividade->hora_fim        = $request->setimoFim;
            $dataAtividade->atividade_id    = $atividade->id;
            $dataAtividade->save();
        }
        
        return redirect()->back()->with(['mensagem' => 'Atividade editada com sucesso!']);
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

    // Salva a visibilidade da atividade para participantes
    public function setVisibilidadeAjax($id) {
        $atividade = Atividade::find($id);

        if ($atividade->visibilidade_participante) {
            $atividade->visibilidade_participante = false;
        } else {
            $atividade->visibilidade_participante = true;
        }
        $atividade->save();
    }
}
