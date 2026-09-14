<?php

namespace App\Http\Controllers\Submissao;

use App\Enums\StatusForm;
use App\Http\Controllers\Controller;
use App\Http\Requests\modalidades\forms\StoreFormRequest;
use App\Http\Requests\modalidades\forms\UpdateFormModalidadeRequest;
use App\Models\Submissao\Evento;
use App\Models\Submissao\Form;
use App\Models\Submissao\Modalidade;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function forms(Request $request, $modalidade_id)
    {
        $evento = Evento::find($request->eventoId);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento);

        $forms = Form::where('modalidadeId', $modalidade_id)->orderBy('titulo')->get();
        $modalidade = Modalidade::findOrFail($modalidade_id);
        return view('coordenador.modalidade.forms.index', compact(
            'evento',
            'forms',
            'modalidade'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $evento = Evento::find($request->evento_id);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento);

        $modalidade = Modalidade::find($request->modalidade_id);

        return view('coordenador.modalidade.forms.create', compact('evento', 'modalidade'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function salvarForm(StoreFormRequest $request)
    {
        $evento = Evento::find($request->evento_id);
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento);

        $modalidade = Modalidade::find($request->modalidade_id);
        $dados = $request->all();
        DB::transaction(function () use ($modalidade, $dados) {
            $form = $modalidade->forms()->create([
                'titulo' => $dados['titulo'],
                'instrucoes' => $dados['instrucoes'],
                'status' => StatusForm::Rascunho,
            ]);

            foreach ($dados['perguntas'] as $index => $perguntaData) {
                $pergunta = $form->perguntas()->create([
                    'pergunta' => $perguntaData['titulo'],
                    'visibilidade' => isset($perguntaData['visibilidade']),
                    'ordem' => $perguntaData['ordem']
                ]);

                $resposta = $pergunta->respostasPadrao()->create([]);
                match ($perguntaData['tipo']) {
                    'paragrafo' => $resposta->paragrafo()->create([]),
                    'radio' => collect($perguntaData['opcoes'])
                                    ->each(function ($opcao) use ($resposta){
                                        $resposta->opcoes()->create([
                                            'titulo' => $opcao['titulo'],
                                            // 'tipo' => $opcao['tipo'],
                                            'ordem' => $opcao['ordem'],

                                        ]);
                                    }),
                    default => throw new InvalidArgumentException('Tipo de pergunta inválido.'),
                };
            }

        });

        return redirect()->route('coord.forms', ['eventoId' => $evento->id, 'modalidade_id' => $modalidade->id])->with('success', 'Formulário cadastrado com sucesso');
    }

    /**
     * Display the specified resource.
     */
    public function visualizarForm(Request $request)
    {
        $modalidade = Modalidade::with('evento')->find($request->modalidade_id);

        $this->authorize('isCoordenadorOrCoordenadorDaComissaoCientifica', $modalidade->evento);

        $form = Form::with('perguntas.respostas')->find($request->form_id);
        $data = $request->all();

        return view('coordenador.modalidade.forms.show', compact('modalidade', 'form'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function modalidadeFormEdit(Evento $evento, Form $form)
    {
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento);
        $form = $form->load([
            'perguntas',
            'perguntas.respostasPadrao.opcoes',
            'perguntas.respostasPadrao.paragrafo',
        ]);

        $form->perguntas = $form->perguntas->map(function ($pergunta) {
            $resposta = $pergunta->respostasPadrao->first();

            return [
                'id' => $pergunta->id,
                'titulo' => $pergunta->pergunta,
                'tipo' => $resposta?->opcoes?->isNotEmpty() ? 'radio' : 'paragrafo',
                'ordem' => $pergunta->ordem,
                'visibilidade' => (bool) $pergunta->visibilidade,
                'opcoes' => $resposta
                    ? $resposta->opcoes->map(fn ($opcao) => [
                        'id' => $opcao->id,
                        'titulo' => $opcao->titulo,
                        'tipo' => $opcao->tipo,
                        'ordem' => $opcao->ordem,
                    ])->values()->toArray()
                    : [],
            ];
        })
        ->values()
        ->toArray();

        $modalidade = $form->modalidade;

        return view('coordenador.modalidade.forms.edit', compact('form', 'evento', 'modalidade'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function modalidadeFormUpdate(
        UpdateFormModalidadeRequest $request,
        $form_id
    ) {
        $form = Form::with([
            'modalidade.evento',
            'perguntas.respostasPadrao.paragrafo',
            'perguntas.respostasPadrao.opcoes',
        ])->findOrFail($form_id);

        $evento = $form->modalidade->evento;

        $this->authorize(
            'isCoordenadorOrCoordenadorDaComissaoCientifica',
            $evento
        );

        $dados = [
            ...$request->formData(),
            'perguntas' => $request->items(),
        ];

        DB::transaction(function () use ($form, $dados) {

            /*
            |--------------------------------------------------------------------------
            | Verifica se existem respostas de revisores
            |--------------------------------------------------------------------------
            */

            $possuiRespostas = $form->perguntas()
                ->whereHas('respostasRevisores')
                ->exists();

            /*
            |--------------------------------------------------------------------------
            | Detecta alteração estrutural
            |--------------------------------------------------------------------------
            |
            | Só precisamos fazer essa verificação se o formulário já tiver
            | respostas.
            |
            | É alteração estrutural:
            |
            | - adicionar pergunta;
            | - remover pergunta;
            | - mudar o tipo da pergunta;
            | - adicionar opção;
            | - remover opção.
            |
            | Alterar título, instrução, texto da pergunta ou texto da opção
            | NÃO é alteração estrutural.
            |
            */

            $alteracaoEstrutural = false;

            if ($possuiRespostas) {

                /*
                * Perguntas atuais do formulário indexadas pelo ID.
                */
                $perguntasAtuais = $form->perguntas->keyBy('id');

                /*
                * IDs das perguntas existentes que vieram da edição.
                */
                $idsPerguntasEnviadas = collect($dados['perguntas'])
                    ->pluck('pergunta_id')
                    ->filter()
                    ->map(fn ($id) => (int) $id);

                /*
                |--------------------------------------------------------------------------
                | Adição de pergunta
                |--------------------------------------------------------------------------
                |
                | Pergunta sem ID significa que ela ainda não existe no banco.
                |
                */

                $adicionouPergunta = collect($dados['perguntas'])
                    ->contains(
                        fn ($pergunta) => empty($pergunta['pergunta_id'])
                    );

                /*
                |--------------------------------------------------------------------------
                | Remoção de pergunta
                |--------------------------------------------------------------------------
                */

                $removeuPergunta = $perguntasAtuais
                    ->keys()
                    ->map(fn ($id) => (int) $id)
                    ->diff($idsPerguntasEnviadas)
                    ->isNotEmpty();

                if ($adicionouPergunta || $removeuPergunta) {
                    $alteracaoEstrutural = true;
                }

                /*
                |--------------------------------------------------------------------------
                | Verifica mudança de tipo e alterações nas opções
                |--------------------------------------------------------------------------
                */

                if (!$alteracaoEstrutural) {

                    foreach ($dados['perguntas'] as $perguntaData) {

                        $pergunta = $perguntasAtuais->get(
                            $perguntaData['pergunta_id']
                        );

                        /*
                        * Em situação normal isso não deve ocorrer.
                        */
                        if (!$pergunta) {
                            $alteracaoEstrutural = true;
                            break;
                        }

                        $respostaPadrao = $pergunta
                            ->respostasPadrao
                            ->first();

                        /*
                        |--------------------------------------------------------------------------
                        | Descobre o tipo atual da pergunta
                        |--------------------------------------------------------------------------
                        */

                        if ($respostaPadrao?->paragrafo) {

                            $tipoAtual = 'paragrafo';

                        } elseif (
                            $respostaPadrao?->opcoes
                            && $respostaPadrao->opcoes->isNotEmpty()
                        ) {

                            $tipoAtual = 'radio';

                        } else {

                            $tipoAtual = null;
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | Mudança de tipo
                        |--------------------------------------------------------------------------
                        |
                        | paragrafo -> radio
                        | radio     -> paragrafo
                        |
                        | Isso altera a estrutura da resposta.
                        |
                        */

                        if ($tipoAtual !== $perguntaData['tipo']) {
                            $alteracaoEstrutural = true;
                            break;
                        }

                        /*
                        * Perguntas de parágrafo não possuem opções.
                        */
                        if ($perguntaData['tipo'] !== 'radio') {
                            continue;
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | IDs das opções atuais
                        |--------------------------------------------------------------------------
                        */

                        $idsOpcoesAtuais = $respostaPadrao
                            ->opcoes
                            ->pluck('id')
                            ->map(fn ($id) => (int) $id);

                        /*
                        |--------------------------------------------------------------------------
                        | IDs das opções enviadas
                        |--------------------------------------------------------------------------
                        */

                        $idsOpcoesEnviadas = collect(
                            $perguntaData['opcoes']
                        )
                            ->pluck('id')
                            ->filter()
                            ->map(fn ($id) => (int) $id);

                        /*
                        |--------------------------------------------------------------------------
                        | Adição de opção
                        |--------------------------------------------------------------------------
                        */

                        $adicionouOpcao = collect(
                            $perguntaData['opcoes']
                        )->contains(
                            fn ($opcao) => empty($opcao['id'])
                        );

                        /*
                        |--------------------------------------------------------------------------
                        | Remoção de opção
                        |--------------------------------------------------------------------------
                        */

                        $removeuOpcao = $idsOpcoesAtuais
                            ->diff($idsOpcoesEnviadas)
                            ->isNotEmpty();

                        if ($adicionouOpcao || $removeuOpcao) {
                            $alteracaoEstrutural = true;
                            break;
                        }
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | CASO 3
            |--------------------------------------------------------------------------
            |
            | Existem respostas + alteração estrutural.
            |
            | O formulário atual NÃO será modificado estruturalmente.
            | É criada uma nova versão para os revisores que ainda não responderam.
            |
            */

            if ($possuiRespostas && $alteracaoEstrutural) {

                $formOriginalId = $form->form_original_id
                    ?? $form->id;

                $novaVersao = $form->modalidade
                    ->forms()
                    ->create([
                        'titulo' => $dados['titulo'],
                        'instrucoes' => $dados['instrucoes'],

                        'versao' => ($form->versao ?? 1) + 1,

                        'form_original_id' => $formOriginalId,

                        'form_anterior_id' => $form->id,

                        'status' => 'publicado',
                    ]);

                /*
                |--------------------------------------------------------------------------
                | Cria as perguntas da nova versão
                |--------------------------------------------------------------------------
                |
                | Os IDs das perguntas anteriores NÃO são reutilizados.
                |
                */

                foreach ($dados['perguntas'] as $perguntaData) {

                    $pergunta = $novaVersao
                        ->perguntas()
                        ->create([
                            'pergunta' => $perguntaData['titulo'],
                            'visibilidade' => $perguntaData['visivel'],
                            'ordem' => $perguntaData['ordem'],
                        ]);

                    /*
                    * Toda pergunta possui uma resposta padrão que define
                    * sua estrutura.
                    */
                    $resposta = $pergunta
                        ->respostasPadrao()
                        ->create([]);

                    match ($perguntaData['tipo']) {

                        'paragrafo' =>
                            $resposta
                                ->paragrafo()
                                ->create([]),

                        'radio' =>
                            collect($perguntaData['opcoes'])
                                ->each(function ($opcao) use ($resposta) {

                                    $resposta
                                        ->opcoes()
                                        ->create([
                                            'titulo' => $opcao['titulo'],
                                            'ordem' => $opcao['ordem'],
                                        ]);
                                }),

                        default =>
                            throw new InvalidArgumentException(
                                'Tipo de pergunta inválido.'
                            ),
                    };
                }

                /*
                * A versão antiga continua existindo para preservar
                * as respostas dos revisores que já responderam.
                */
                $form->update([
                    'status' => StatusForm::Substituido,
                ]);

                return;
            }

            /*
            |--------------------------------------------------------------------------
            | CASOS 1 E 2
            |--------------------------------------------------------------------------
            |
            | CASO 1:
            | Não existem respostas.
            | Qualquer alteração pode ocorrer no formulário atual.
            |
            | CASO 2:
            | Existem respostas, porém houve somente alteração textual.
            | O próprio formulário pode ser atualizado.
            |
            */

            $form->update([
                'titulo' => $dados['titulo'],
                'instrucoes' => $dados['instrucoes'],
            ]);

            /*
            * Guardaremos os IDs das perguntas que permaneceram.
            */
            $perguntasMantidas = [];

            foreach ($dados['perguntas'] as $perguntaData) {

                /*
                |--------------------------------------------------------------------------
                | Pergunta existente
                |--------------------------------------------------------------------------
                */

                if ($perguntaData['pergunta_id']) {

                    /*
                    * Busca pelo relacionamento para impedir que uma pergunta
                    * de outro formulário seja atualizada.
                    */
                    $pergunta = $form
                        ->perguntas()
                        ->findOrFail(
                            $perguntaData['pergunta_id']
                        );

                    $pergunta->update([
                        'pergunta' => $perguntaData['titulo'],
                        'visibilidade' => $perguntaData['visivel'],
                        'ordem' => $perguntaData['ordem'],
                    ]);

                /*
                |--------------------------------------------------------------------------
                | Nova pergunta
                |--------------------------------------------------------------------------
                |
                | Este caso só pode chegar aqui quando o formulário ainda
                | não possui respostas.
                |
                */

                } else {

                    $pergunta = $form
                        ->perguntas()
                        ->create([
                            'pergunta' => $perguntaData['titulo'],
                            'visibilidade' => $perguntaData['visivel'],
                            'ordem' => $perguntaData['ordem'],
                        ]);
                }

                $perguntasMantidas[] = $pergunta->id;

                /*
                |--------------------------------------------------------------------------
                | Resposta padrão
                |--------------------------------------------------------------------------
                */

                $resposta = $pergunta
                    ->respostasPadrao()
                    ->firstOrCreate([]);

                /*
                |--------------------------------------------------------------------------
                | Pergunta do tipo parágrafo
                |--------------------------------------------------------------------------
                */

                if ($perguntaData['tipo'] === 'paragrafo') {

                    /*
                    * Caso anteriormente fosse radio.
                    *
                    * Isso só poderá acontecer aqui se ainda não existirem
                    * respostas de revisores.
                    */
                    $resposta
                        ->opcoes()
                        ->delete();

                    $resposta
                        ->paragrafo()
                        ->firstOrCreate([]);

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Pergunta do tipo radio
                |--------------------------------------------------------------------------
                */

                if ($perguntaData['tipo'] === 'radio') {

                    /*
                    * Caso anteriormente fosse parágrafo.
                    */
                    $resposta
                        ->paragrafo()
                        ->delete();

                    $opcoesMantidas = [];

                    foreach ($perguntaData['opcoes'] as $opcaoData) {

                        /*
                        |--------------------------------------------------------------------------
                        | Opção existente
                        |--------------------------------------------------------------------------
                        */

                        if ($opcaoData['id']) {

                            /*
                            * Busca a opção dentro da resposta correta.
                            */
                            $opcao = $resposta
                                ->opcoes()
                                ->findOrFail(
                                    $opcaoData['id']
                                );

                            $opcao->update([
                                'titulo' => $opcaoData['titulo'],
                                'ordem' => $opcaoData['ordem'],
                            ]);

                        /*
                        |--------------------------------------------------------------------------
                        | Nova opção
                        |--------------------------------------------------------------------------
                        |
                        | Só pode ocorrer neste fluxo quando ainda não existem
                        | respostas de revisores.
                        |
                        */

                        } else {

                            $opcao = $resposta
                                ->opcoes()
                                ->create([
                                    'titulo' => $opcaoData['titulo'],
                                    'ordem' => $opcaoData['ordem'],
                                ]);
                        }

                        $opcoesMantidas[] = $opcao->id;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Remove opções excluídas
                    |--------------------------------------------------------------------------
                    |
                    | Se já houvesse respostas, a remoção teria sido detectada
                    | anteriormente e uma nova versão teria sido criada.
                    |
                    */

                    $resposta
                        ->opcoes()
                        ->whereNotIn(
                            'id',
                            $opcoesMantidas
                        )
                        ->delete();

                    continue;
                }

                throw new InvalidArgumentException(
                    'Tipo de pergunta inválido.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Remove perguntas excluídas
            |--------------------------------------------------------------------------
            |
            | Se houvesse respostas, essa remoção já teria ocasionado
            | a criação de uma nova versão.
            |
            */

            $form
                ->perguntas()
                ->whereNotIn(
                    'id',
                    $perguntasMantidas
                )
                ->delete();
        });

        return redirect()->back()->with([
            'success' => 'Formulário editado com sucesso!'
        ]);
    }

    public function publicar(Form $form)
    {

        $publicado = DB::transaction(function () use ($form) {

            $form = Form::query()
                ->whereKey($form->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($form->status !== StatusForm::Rascunho) {
                return false;
            }

            $formPublicado = $form->modalidade
                ->formAtual()
                ->lockForUpdate()
                ->first();

            $formPublicado?->update([
                'status' => StatusForm::Substituido,
            ]);

            $form->update([
                'status' => StatusForm::Publicado,
                'publicado_em' => now(),
            ]);

            return true;
        });

        if (! $publicado) {
            return back()->with(
                'error',
                'Esta versão já foi publicada ou não está mais disponível para publicação.'
            );
        }

        return back()->with(
            'success',
            "Versão {$form->versao} publicada com sucesso."
        );
    }


    public function respostasToPdf(Modalidade $modalidade)
    {
        $evento = $modalidade->evento;
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento);
        $pdf = Pdf::loadView('coordenador.modalidade.respostasPdf', ['modalidade' => $modalidade])->setOptions(['defaultFont' => 'sans-serif']);

        return $pdf->stream("respostas-{$modalidade->nome}.pdf");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyForm($id)
    {
        $form = Form::find($id);
        $evento = $form->modalidade->evento;
        $this->authorize('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento);

        $temRespostas = false;
        foreach ($form->perguntas as $pergunta) {
            $primeira = $pergunta->respostas->first();
            if ($primeira && $primeira->opcoes && $primeira->opcoes->count()) {
                //Resposta com Múltipla escolha:
            } elseif ($primeira && $primeira->paragrafo && $primeira->paragrafo->count()) {
                foreach ($pergunta->respostas as $resposta) {
                    if ($resposta->revisor != null || $resposta->trabalho != null) {
                        $temRespostas = true;
                        break;
                    }
                }
            } elseif ($temRespostas) {
                break;
            }
        }
        //dd($temRespostas);

        if (!$temRespostas) {
            $form->delete();

            return redirect()->back()->with(['success' => 'Formulário excluído com sucesso!']);
        } else {
            return redirect()->back()->withErrors(['excluirFormulario' => 'Não é possível excluir. Existem respostas submetidas ligadas a este formulário.']);
        }
    }
}
