<?php

namespace App\Http\Controllers\Submissao;

use App\Exports\AtividadeInscritosExport;
use App\Models\Submissao\Atividade;
use App\Models\Submissao\Evento;
use App\Models\Submissao\TipoAtividade;
use App\Models\Submissao\DatasAtividade;
use App\Models\Users\Convidado;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Mail\ConvidadoAtividade\EmailConvidadoAtividade;
use App\Mail\ConvidadoAtividade\EmailDesconvidandoAtividade;
use App\Mail\ConvidadoAtividade\EmailAtualizandoConvidadoAtividade;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);
        $atividades = Atividade::where('eventoId', $id)->orderBy('titulo')->get();

        foreach ($atividades as $atv) {
            $datasAtividades = DatasAtividade::where('atividade_id', $atv->id)->get();
            array_push($ids, $atv->id);
            array_push($duracaoAtividades, count($datasAtividades));
        }

        $tipoDeAtividades = TipoAtividade::orderBy('descricao')->get();
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
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);

        $validated = $request->validate([
            'idNovaAtividade'       => ['required', 'integer'],
            'título'                => ['required', 'max:150'],
            'tipo'                  => ['required', 'string'],
            'descrição'             => ['required', 'max:500'],
            'carga_horaria'         => ['nullable', 'string'],
            'vagas'                 => ['nullable', 'string'],
            'valor'                 => ['nullable', 'string'],
            'local'                 => ['required', 'string'],
            'duraçãoDaAtividade'    => ['required', 'string'],
        ]);

        $validateDuracaoAtividade = $request->validate([
            // Validação das datas
            'primeiroDia'   => ($request->input("duraçãoDaAtividade") >= 1) ? ['required', 'date'] : [''],
            'segundoDia'    => ($request->input("duraçãoDaAtividade") >= 2) ? ['required', 'date', 'after:primeiroDia'] : [''],
            'terceiroDia'   => ($request->input("duraçãoDaAtividade") >= 3) ? ['required', 'date', 'after:segundoDia'] : [''],
            'quartoDia'     => ($request->input("duraçãoDaAtividade") >= 4) ? ['required', 'date', 'after:terceiroDia'] : [''],
            'quintoDia'     => ($request->input("duraçãoDaAtividade") >= 5) ? ['required', 'date', 'after:quartoDia'] : [''],
            'sextoDia'      => ($request->input("duraçãoDaAtividade") >= 6) ? ['required', 'date', 'after:quintoDia'] : [''],
            'setimoDia'     => ($request->input("duraçãoDaAtividade") == 7) ? ['required', 'date', 'after:sextoDia'] : [''],

            // Validação das horas
            'inicio'        => ($request->input("duraçãoDaAtividade") >= 1) ? ['required','time'] : [''],
            'segundoInicio' => ($request->input("duraçãoDaAtividade") >= 2) ? ['required','time'] : [''],
            'terceiroInicio'=> ($request->input("duraçãoDaAtividade") >= 3) ? ['required','time'] : [''],
            'quartoInicio'  => ($request->input("duraçãoDaAtividade") >= 4) ? ['required','time'] : [''],
            'quintoInicio'  => ($request->input("duraçãoDaAtividade") >= 5) ? ['required','time'] : [''],
            'sextoInicio'   => ($request->input("duraçãoDaAtividade") >= 6) ? ['required','time'] : [''],
            'setimoInicio'  => ($request->input("duraçãoDaAtividade") == 7) ? ['required','time'] : [''],
            'fim'           => ($request->input("duraçãoDaAtividade") >= 1) ? ['required','time'] : [''],
            'segundoFim'    => ($request->input("duraçãoDaAtividade") >= 2) ? ['required','time'] : [''],
            'terceiroFim'   => ($request->input("duraçãoDaAtividade") >= 3) ? ['required','time'] : [''],
            'quartoFim'     => ($request->input("duraçãoDaAtividade") >= 4) ? ['required','time'] : [''],
            'quintoFim'     => ($request->input("duraçãoDaAtividade") >= 5) ? ['required','time'] : [''],
            'sextoFim'      => ($request->input("duraçãoDaAtividade") >= 6) ? ['required','time'] : [''],
            'setimoFim'     => ($request->input("duraçãoDaAtividade") == 7) ? ['required','time'] : [''],
        ]);

        if ($request->inicio != null && strtotime($request->inicio) > strtotime($request->fim)) {
            return redirect()->back()->withErrors(['idNovaAtividade' => 2, 'fim' => 'Fim deve ser um horário após ' . $request->inicio])->withInput();
        }

        if ($request->segundoInicio != null && strtotime($request->segundoInicio) > strtotime($request->segundoFim)) {
            return redirect()->back()->withErrors(['idNovaAtividade' => 2, 'segundoFim' => 'Segundo fim deve ser um horário após ' . $request->segundoInicio])->withInput();
        }

        if ($request->terceiroInicio != null && strtotime($request->terceiroInicio) > strtotime($request->terceiroFim)) {
            return redirect()->back()->withErrors(['idNovaAtividade' => 2, 'terceiroFim' => 'Fim deve ser um horário após ' . $request->terceiroInicio])->withInput();
        }

        if ($request->quartoInicio != null && strtotime($request->quartoInicio) > strtotime($request->quartoFim)) {
            return redirect()->back()->withErrors(['idNovaAtividade' => 2, 'quartoFim' => 'Fim deve ser um horário após ' . $request->quartoInicio])->withInput();
        }

        if ($request->quintoInicio != null && strtotime($request->quintoInicio) > strtotime($request->quintoFim)) {
            return redirect()->back()->withErrors(['idNovaAtividade' => 2, 'quintoFim' => 'Fim deve ser um horário após ' . $request->quintoInicio])->withInput();
        }

        if ($request->sextoInicio != null && strtotime($request->sextoInicio) > strtotime($request->sextoFim)) {
            return redirect()->back()->withErrors(['idNovaAtividade' => 2, 'sextoFim' => 'Fim deve ser um horário após ' . $request->sextoInicio])->withInput();
        }

        if ($request->setimoInicio != null && strtotime($request->setimoInicio) > strtotime($request->setimoFim)) {
            return redirect()->back()->withErrors(['idNovaAtividade' => 2, 'setimoFim' => 'Fim deve ser um horário após ' . $request->setimoInicio])->withInput();
        }

        $validatedConvidados = $request->validate([
            'nomeDoConvidado.*'     => 'nullable',
            'emailDoConvidado.*'    => ($request->nomeDoConvidado[0] != null) ? 'required' : 'nullable',
            'funçãoDoConvidado.*'   => ($request->nomeDoConvidado[0] != null) ? 'required' : 'nullable',
            // 'outra.*'               => ($request->funçãoDoConvidado[0] == "Outra") ? 'required' : 'nullable',
        ]);

        // dd($request);
        $atividade = new Atividade();
        $atividade->eventoId                    = $request->eventoId;
        $atividade->tipo_id                     = $request->tipo;
        $atividade->titulo                      = $request->input("título");
        $atividade->vagas                       = $request->vagas;
        $atividade->valor                       = $request->valor;
        $atividade->descricao                   = $request->input("descrição");
        $atividade->local                       = $request->local;
        $atividade->palavras_chave              = $request->palavrasChaves;
        $atividade->carga_horaria               = $request->carga_horaria;
        $atividade->visibilidade_participante   = true;
        $atividade->save();

        if ($request->input("duraçãoDaAtividade") >= 1) {
            $dataAtividade = new DatasAtividade();
            $dataAtividade->data            = $request->primeiroDia;
            $dataAtividade->hora_inicio     = $request->inicio;
            $dataAtividade->hora_fim        = $request->fim;
            $dataAtividade->atividade_id    = $atividade->id;
            $dataAtividade->save();
        }
        if ($request->input("duraçãoDaAtividade") >= 2) {
            $dataAtividade = new DatasAtividade();
            $dataAtividade->data            = $request->segundoDia;
            $dataAtividade->hora_inicio     = $request->segundoInicio;
            $dataAtividade->hora_fim        = $request->segundoFim;
            $dataAtividade->atividade_id    = $atividade->id;
            $dataAtividade->save();
        }
        if ($request->input("duraçãoDaAtividade") >= 3) {
            $dataAtividade = new DatasAtividade();
            $dataAtividade->data            = $request->terceiroDia;
            $dataAtividade->hora_inicio     = $request->terceiroInicio;
            $dataAtividade->hora_fim        = $request->terceiroFim;
            $dataAtividade->atividade_id    = $atividade->id;
            $dataAtividade->save();
        }
        if ($request->input("duraçãoDaAtividade") >= 4) {
            $dataAtividade = new DatasAtividade();
            $dataAtividade->data            = $request->quartoDia;
            $dataAtividade->hora_inicio     = $request->quartoInicio;
            $dataAtividade->hora_fim        = $request->quartoFim;
            $dataAtividade->atividade_id    = $atividade->id;
            $dataAtividade->save();
        }
        if ($request->input("duraçãoDaAtividade") >= 5) {
            $dataAtividade = new DatasAtividade();
            $dataAtividade->data            = $request->quintoDia;
            $dataAtividade->hora_inicio     = $request->quintoInicio;
            $dataAtividade->hora_fim        = $request->quintoFim;
            $dataAtividade->atividade_id    = $atividade->id;
            $dataAtividade->save();
        }
        if ($request->input("duraçãoDaAtividade") >= 6) {
            $dataAtividade = new DatasAtividade();
            $dataAtividade->data            = $request->sextoDia;
            $dataAtividade->hora_inicio     = $request->sextoInicio;
            $dataAtividade->hora_fim        = $request->sextoFim;
            $dataAtividade->atividade_id    = $atividade->id;
            $dataAtividade->save();
        }
        if ($request->input("duraçãoDaAtividade") == 7) {
            $dataAtividade = new DatasAtividade();
            $dataAtividade->data            = $request->setimoDia;
            $dataAtividade->hora_inicio     = $request->setimoInicio;
            $dataAtividade->hora_fim        = $request->setimoFim;
            $dataAtividade->atividade_id    = $atividade->id;
            $dataAtividade->save();
        }

        if ($request->nomeDoConvidado[0] != null) {
            for ($i = 0; $i < count($request->nomeDoConvidado); $i++) {
                $convidado = new Convidado();
                $convidado->nome            = $request->nomeDoConvidado[$i];
                $convidado->email           = $request->emailDoConvidado[$i];
                if ($request->funçãoDoConvidado[$i] == "Outra") {
                    $convidado->funcao      = $request->outra[$i];
                } else {
                    $convidado->funcao      = $request->funçãoDoConvidado[$i];
                }
                $convidado->atividade_id    = $atividade->id;
                $convidado->save();

                $subject = "Convite para atividade";
                Mail::to($convidado->email)->send(new EmailConvidadoAtividade($convidado, $subject));
            }
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
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $evento);

        $validated = $request->validate([
            'idAtividade'           => ['required', 'integer'],
            'titulo'                => ['required', 'max:150'],
            'tipo'                  => ['required', 'string'],
            'descricao'             => ['required', 'max:500'],
            'carga_horaria'         => ['nullable', 'string'],
            'vagas'                 => ['nullable', 'string'],
            'valor'                 => ['nullable', 'string'],
            'local'                 => ['required', 'string'],
            'duracaoDaAtividade'    => ['required', 'string'],
        ]);

        $validateDuracaoAtividade = $request->validate([
            // Validação das datas
            'primeiroDia'   => ($request->duracaoAtividade >= 1) ? ['required', 'date'] : [''],
            'segundoDia'    => ($request->duracaoAtividade >= 2) ? ['required', 'date', 'after:primeiroDia'] : [''],
            'terceiroDia'   => ($request->duracaoAtividade >= 3) ? ['required', 'date', 'after:segundoDia'] : [''],
            'quartoDia'     => ($request->duracaoAtividade >= 4) ? ['required', 'date', 'after:terceiroDia'] : [''],
            'quintoDia'     => ($request->duracaoAtividade >= 5) ? ['required', 'date', 'after:quartoDia'] : [''],
            'sextoDia'      => ($request->duracaoAtividade >= 6) ? ['required', 'date', 'after:quintoDia'] : [''],
            'setimoDia'     => ($request->duracaoAtividade == 7) ? ['required', 'date', 'after:sextoDia'] : [''],

            // Validação das horas
            'inicio'        => ($request->duracaoAtividade >= 1) ? ['required','time'] : [''],
            'segundoInicio' => ($request->duracaoAtividade >= 2) ? ['required','time'] : [''],
            'terceiroInicio'=> ($request->duracaoAtividade >= 3) ? ['required','time'] : [''],
            'quartoInicio'  => ($request->duracaoAtividade >= 4) ? ['required','time'] : [''],
            'quintoInicio'  => ($request->duracaoAtividade >= 5) ? ['required','time'] : [''],
            'sextoInicio'   => ($request->duracaoAtividade >= 6) ? ['required','time'] : [''],
            'setimoInicio'  => ($request->duracaoAtividade == 7) ? ['required','time'] : [''],
            'fim'           => ($request->duracaoAtividade >= 1) ? ['required','time'] : [''],
            'segundoFim'    => ($request->duracaoAtividade >= 2) ? ['required','time'] : [''],
            'terceiroFim'   => ($request->duracaoAtividade >= 3) ? ['required','time'] : [''],
            'quartoFim'     => ($request->duracaoAtividade >= 4) ? ['required','time'] : [''],
            'quintoFim'     => ($request->duracaoAtividade >= 5) ? ['required','time'] : [''],
            'sextoFim'      => ($request->duracaoAtividade >= 6) ? ['required','time'] : [''],
            'setimoFim'     => ($request->duracaoAtividade == 7) ? ['required','time'] : [''],
        ]);

        if ($request->inicio != null && strtotime($request->inicio) > strtotime($request->fim)) {
            return redirect()->back()->withErrors(['idNovaAtividade' => 2, 'fim' => 'Fim deve ser um horário após ' . $request->inicio])->withInput();
        }

        if ($request->segundoInicio != null && strtotime($request->segundoInicio) > strtotime($request->segundoFim)) {
            return redirect()->back()->withErrors(['idNovaAtividade' => 2, 'segundoFim' => 'Segundo fim deve ser um horário após ' . $request->segundoInicio])->withInput();
        }

        if ($request->terceiroInicio != null && strtotime($request->terceiroInicio) > strtotime($request->terceiroFim)) {
            return redirect()->back()->withErrors(['idNovaAtividade' => 2, 'terceiroFim' => 'Fim deve ser um horário após ' . $request->terceiroInicio])->withInput();
        }

        if ($request->quartoInicio != null && strtotime($request->quartoInicio) > strtotime($request->quartoFim)) {
            return redirect()->back()->withErrors(['idNovaAtividade' => 2, 'quartoFim' => 'Fim deve ser um horário após ' . $request->quartoInicio])->withInput();
        }

        if ($request->quintoInicio != null && strtotime($request->quintoInicio) > strtotime($request->quintoFim)) {
            return redirect()->back()->withErrors(['idNovaAtividade' => 2, 'quintoFim' => 'Fim deve ser um horário após ' . $request->quintoInicio])->withInput();
        }

        if ($request->sextoInicio != null && strtotime($request->sextoInicio) > strtotime($request->sextoFim)) {
            return redirect()->back()->withErrors(['idNovaAtividade' => 2, 'sextoFim' => 'Fim deve ser um horário após ' . $request->sextoInicio])->withInput();
        }

        if ($request->setimoInicio != null && strtotime($request->setimoInicio) > strtotime($request->setimoFim)) {
            return redirect()->back()->withErrors(['idNovaAtividade' => 2, 'setimoFim' => 'Fim deve ser um horário após ' . $request->setimoInicio])->withInput();
        }

        $validatedConvidados = $request->validate([
            'nomeDoConvidado.*'     => 'nullable',
            'emailDoConvidado.*'    => $request->has('nomeDoConvidado') ? 'required|email' : 'nullable',
            'funçãoDoConvidado.*'   => $request->has('nomeDoConvidado') ? 'required' : 'nullable',
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

        $idsConvidados = Convidado::where('atividade_id', $id)->get('id');

        $ids = [];
        foreach ($idsConvidados as $idsConv) {
            array_push($ids, $idsConv->id);
        }

        if ($request->idConvidado != null && count($request->idConvidado) > 0) {
            for ($i = 0; $i < count($ids); $i++) {
                if (in_array($ids[$i], $request->idConvidado)) {
                    $key = array_search($ids[$i], $request->idConvidado);
                    $convidado = Convidado::find($ids[$i]);
                    $convidado->nome            = $request->nomeDoConvidado[$key];
                    $convidado->email           = $request->emailDoConvidado[$key];
                    if ($request->funçãoDoConvidado[$key] == "Outra") {
                        $convidado->funcao      = $request->outra[$key];
                    } else {
                        $convidado->funcao      = $request->funçãoDoConvidado[$key];
                    }
                    $convidado->atividade_id    = $atividade->id;
                    $convidado->update();

                    if ($this->valida_email($convidado->email)) {
                        $subject = "Convite atualizado para atividade";
                        Mail::to($convidado->email)->send(new EmailAtualizandoConvidadoAtividade($convidado, $subject));
                    }
                } else {
                    $convidado = Convidado::find($ids[$i]);

                    if ($this->valida_email($convidado->email)) {
                        $subject = "Você foi removido da atividade";
                        Mail::to($convidado->email)->send(new EmailDesconvidandoAtividade($convidado, $subject));
                    }
                    $convidado->delete();
                }
            }
            for ($i = 0; $i < count($request->idConvidado); $i++) {
                if ($request->idConvidado[$i] == 0) {
                    $convidado = new Convidado();
                    $convidado->nome            = $request->nomeDoConvidado[$i];
                    $convidado->email           = $request->emailDoConvidado[$i];
                    if ($request->funçãoDoConvidado[$i] == "Outra") {
                        $convidado->funcao      = $request->outra[$i];
                    } else {
                        $convidado->funcao      = $request->funçãoDoConvidado[$i];
                    }
                    $convidado->atividade_id    = $atividade->id;
                    $convidado->save();

                    if ($this->valida_email($convidado->email)) {
                        $subject = "Convite na edição da atividade";
                        Mail::to($convidado->email)->send(new EmailConvidadoAtividade($convidado, $subject));
                    }
                }
            }
        } else {
            for ($i = 0; $i < count($atividade->convidados); $i++) {
                $convidado = Convidado::find($atividade->convidados[$i]->id);

                if ($this->valida_email($convidado->email)) {
                    $subject = "Você foi removido da atividade";
                    Mail::to($convidado->email)->send(new EmailDesconvidandoAtividade($convidado, $subject));
                }
                $convidado->delete();
            }
            // dd($request);
            if ($request->nomeDoConvidado != null) {
                for ($i = 0; $i < count($request->nomeDoConvidado); $i++) {
                    $convidado = new Convidado();
                    $convidado->nome            = $request->nomeDoConvidado[$i];
                    $convidado->email           = $request->emailDoConvidado[$i];
                    if ($request->funçãoDoConvidado[$i] == "Outra") {
                        $convidado->funcao      = $request->outra[$i];
                    } else {
                        $convidado->funcao      = $request->funçãoDoConvidado[$i];
                    }
                    $convidado->atividade_id    = $atividade->id;
                    $convidado->save();

                    if ($this->valida_email($convidado->email)) {
                        $subject = "Convite na edição da atividade";
                        Mail::to($convidado->email)->send(new EmailConvidadoAtividade($convidado, $subject));
                    }
                }
            }
        }

        return redirect()->back()->with(['mensagem' => 'Atividade editada com sucesso!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Atividade  $atividade
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $atividade = Atividade::find($id);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $atividade->evento);

        foreach ($atividade->datasAtividade as $da) {
            $da->delete();
        }

        $atividade->delete();

        return redirect()->back()->with(['mensagem' => 'Atividade excluida com sucesso!']);
    }

    // Salva a visibilidade da atividade para participantes
    public function setVisibilidadeAjax($id) {
        $atividade = Atividade::find($id);
        $this->authorize('isCoordenadorOrCoordenadorDasComissoes', $atividade->evento);

        if ($atividade->visibilidade_participante) {
            $atividade->visibilidade_participante = false;
        } else {
            $atividade->visibilidade_participante = true;
        }
        $atividade->save();
    }

    public function atividadesJson($id) {

        $evento = Evento::find($id);
        $atividades = DB::table('atividades')->join('datas_atividades', 'atividades.id', 'datas_atividades.atividade_id')->orderBy('atividade_id')->orderBy('data')->where('eventoId', '=', $id)->get();

        $eventsFC = collect();
        $idAtividade = 0;

        if (auth()->user() != null && auth()->user()->id === $evento->coordenadorId) {
            foreach ($atividades as $atv) {
                if ($idAtividade != $atv->atividade_id) {
                    $idAtividade = $atv->atividade_id;
                    $color = $this->random_color();
                }
                $atividade = [
                    'id' => $atv->atividade_id,
                    'title' => $atv->titulo,
                    'start' => $atv->data . " " . $atv->hora_inicio,
                    'end' => $atv->data . " " . $atv->hora_fim,
                    'color' => $color,
                ];
                $eventsFC->push($atividade);
            }
        } else {
            foreach ($atividades as $atv) {
                if ($idAtividade != $atv->atividade_id) {
                    $idAtividade = $atv->atividade_id;
                    $color = $this->random_color();
                }
                if ($atv->visibilidade_participante) {
                    $atividade = [
                        'id' => $atv->atividade_id,
                        'title' => $atv->titulo,
                        'start' => $atv->data . " " . $atv->hora_inicio,
                        'end' => $atv->data . " " . $atv->hora_fim,
                        'color' => $color,
                    ];
                    $eventsFC->push($atividade);
                }
            }
        }

        // dd(response()->json($eventsFC));

        return response()->json($eventsFC);
    }

    public function random_color() {
        $letters = '0123456789ABCDEF';
        $color = '#';
        for($i = 0; $i < 6; $i++) {
            $index = rand(0,15);
            $color .= $letters[$index];
        }
        return $color;
    }

    public function valida_email($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public function listarInscritos($id)
    {
        $atividade = Atividade::find($id);
        $inscritos = $atividade->users()->get();
        return view('coordenador.atividade.inscritos', ['inscritos' => $inscritos, 'atividade' => $atividade,'evento' => $atividade->evento]);

    }

    public function inscrever($id){
        $atividade = Atividade::find($id);
        if($atividade->atividadeInscricoesEncerradas()){
            return redirect()->back()->with(['error' => ''.$atividade->titulo.' já iniciada. Não aceitamos mais inscritos.']);
        }
        if($atividade->vagas >0){
            $user = Auth::user();
            $atividade->vagas -= 1;
            $atividade->users()->attach($user->id);
            $atividade->update();
            return redirect()->back()->with(['message' => 'Inscrito em '.$atividade->titulo.' com sucesso!']);
        }
        return redirect()->back()->with(['error' => ''.$atividade->titulo.' não possui mais vagas!']);
    }

    public function cancelarInscricao($id){
        $atividade = Atividade::find($id);
        $user = Auth::user();
        $atividade->vagas += 1;
        $atividade->users()->detach($user->id);
        $atividade->update();

        return redirect()->back()->with(['message' => 'Inscrição em '.$atividade->titulo.' cancelada sucesso!']);
    }

    public function exportInscritos($id)
    {
        $atividade = Atividade::find($id);
        return (new AtividadeInscritosExport($atividade))->download($atividade->titulo . '.csv', \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
