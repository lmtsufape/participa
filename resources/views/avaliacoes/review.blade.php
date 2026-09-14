@extends('layouts.app')

@section('sidebar')

@endsection

@section('content')

<div class="container">

    {{-- Cabeçalho --}}
    <div class="row mb-4">

        <div class="col-md-12">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">

                <div>

                    <h3 class="mb-1 fw-bold">
                        Revisar Avaliação
                    </h3>

                    <p class="mb-0 text-muted">
                        Trabalho:
                        <strong class="text-my-primary">
                            {{ $trabalho->titulo }}
                        </strong>
                    </p>

                    <p class="mb-0 text-muted">
                        Modalidade:
                        <strong class="text-my-primary">
                            {{ $modalidade->nome }}
                        </strong>
                    </p>

                    <p class="mb-0 text-muted">
                        Avaliador:
                        <strong class="text-my-primary">
                            {{ $revisorUser->name }}
                        </strong>
                    </p>

                </div>

            </div>

        </div>

    </div>


    {{-- Formulário de edição --}}
    <form
        id="editarRespostas"
        action="{{ route('revisor.editar.respostas') }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf

        <input
            type="hidden"
            name="trabalho_id"
            value="{{ $trabalho->id }}"
        >

        <input
            type="hidden"
            name="revisor_id"
            value="{{ $revisor->id }}"
        >

        <input
            type="hidden"
            name="form_id"
            value="{{ $form->id }}"
        >


        <div class="row">

            {{-- Avaliação --}}
            <div class="col-md-8">

                <div class="card">

                    <div class="card-header bg-white py-4 px-4">

                        <h3 class="card-title h4 text-my-primary mb-0">
                            <strong>
                                {{ $form->titulo }}
                            </strong>
                        </h3>

                        @if ($form->instrucoes)

                            <div class="alert alert-success mt-3 mb-0">

                                <div class="fw-semibold text-success mb-1">
                                    Orientações aos(as) avaliadores(as):
                                </div>

                                <div class="text-muted text-break">
                                    {!! $form->instrucoes !!}
                                </div>

                            </div>

                        @endif

                    </div>


                    <div class="card-body">

                        @foreach ($form->perguntas->sortBy('ordem') as $pergunta)

                            @php
                                /*
                                 * Estrutura original da pergunta.
                                 */
                                $respostaPadrao = $pergunta
                                    ->respostasPadrao
                                    ->first();

                                /*
                                 * Como o controller já filtrou por:
                                 *
                                 * trabalho_id
                                 * revisor_id
                                 *
                                 * esta é a resposta específica que estamos
                                 * revisando.
                                 */
                                $respostaRevisor = $pergunta
                                    ->respostasRevisores
                                    ->first();
                            @endphp

                            @continue(!$respostaPadrao)


                            <div class="card mb-3">

                                <div class="card-body">

                                    {{-- Pergunta --}}
                                    <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-3">

                                        <div class="flex-grow-1">

                                            <span class="small fw-semibold text-my-primary d-block mb-1">
                                                Pergunta {{ $pergunta->ordem }}
                                            </span>

                                            <div class="fw-bold">
                                                {!! $pergunta->pergunta !!}
                                            </div>

                                        </div>


                                        {{-- Visibilidade da própria pergunta --}}
                                        <div class="flex-shrink-0">

                                            <div class="form-check">

                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    id="pergunta-visivel-{{ $pergunta->id }}"
                                                    @checked($pergunta->visibilidade)
                                                    disabled
                                                >

                                                <label
                                                    class="form-check-label small text-muted"
                                                    for="pergunta-visivel-{{ $pergunta->id }}"
                                                >
                                                    Visível para o autor
                                                </label>

                                            </div>

                                        </div>

                                    </div>


                                    {{-- ===================================================
                                         PERGUNTA DE OPÇÕES
                                    ==================================================== --}}
                                    @if ($respostaPadrao->opcoes->isNotEmpty())

                                        @php
                                            /*
                                             * A opção respondida possui parent_id
                                             * apontando para a opção padrão.
                                             */
                                            $opcaoSelecionadaId = $respostaRevisor
                                                ?->opcoes
                                                ?->first()
                                                ?->parent_id;
                                        @endphp


                                        @foreach ($respostaPadrao->opcoes->sortBy('ordem') as $opcao)

                                            <div class="form-check mb-2">

                                                <input
                                                    class="form-check-input
                                                        @error('respostas.' . $pergunta->id)
                                                            is-invalid
                                                        @enderror
                                                    "
                                                    type="radio"

                                                    name="respostas[{{ $pergunta->id }}]"

                                                    value="{{ $opcao->id }}"

                                                    id="opcao-{{ $pergunta->id }}-{{ $opcao->id }}"

                                                    @checked(
                                                        old(
                                                            'respostas.' . $pergunta->id,
                                                            $opcaoSelecionadaId
                                                        ) == $opcao->id
                                                    )

                                                    required
                                                >

                                                <label
                                                    class="form-check-label"
                                                    for="opcao-{{ $pergunta->id }}-{{ $opcao->id }}"
                                                >
                                                    {!! $opcao->titulo !!}
                                                </label>

                                            </div>

                                        @endforeach


                                        @error('respostas.' . $pergunta->id)

                                            <div class="text-danger small mt-2">
                                                {{ $message }}
                                            </div>

                                        @enderror


                                        @if (!$respostaRevisor)

                                            <div class="text-muted small fst-italic mt-2">
                                                Esta pergunta não possui resposta registrada.
                                            </div>

                                        @endif


                                    {{-- ===================================================
                                         PERGUNTA DE PARÁGRAFO
                                    ==================================================== --}}
                                    @elseif ($respostaPadrao->paragrafo)

                                        <textarea
                                            class="form-control
                                                @error('respostas.' . $pergunta->id)
                                                    is-invalid
                                                @enderror
                                            "
                                            name="respostas[{{ $pergunta->id }}]"
                                            rows="4"
                                            required
                                        >{{ old(
                                            'respostas.' . $pergunta->id,
                                            $respostaRevisor?->paragrafo?->resposta
                                        ) }}</textarea>


                                        @error('respostas.' . $pergunta->id)

                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>

                                        @enderror


                                        {{-- Visibilidade da resposta --}}
                                        <div class="form-check mt-3">

                                            <input
                                                class="form-check-input resposta-visibilidade"
                                                type="checkbox"

                                                name="visibilidade[{{ $pergunta->id }}]"

                                                value="1"

                                                id="visibilidade-{{ $pergunta->id }}"

                                                @checked(
                                                    old(
                                                        'visibilidade.' . $pergunta->id,
                                                        $respostaRevisor?->paragrafo?->visibilidade
                                                    )
                                                )
                                            >

                                            <label
                                                class="form-check-label small"
                                                for="visibilidade-{{ $pergunta->id }}"
                                            >
                                                Resposta visível para o autor
                                            </label>

                                        </div>


                                        @if (!$respostaRevisor?->paragrafo)

                                            <div class="text-muted small fst-italic mt-2">
                                                Esta pergunta não possui resposta registrada.
                                            </div>

                                        @endif

                                    @endif

                                </div>

                            </div>

                        @endforeach


                        {{-- Selecionar todas as respostas textuais --}}
                        <div class="border-top pt-3 mt-4">

                            <div class="form-check">

                                <input
                                    id="selecionarTodas"
                                    class="form-check-input"
                                    type="checkbox"
                                >

                                <label
                                    class="form-check-label small"
                                    for="selecionarTodas"
                                >
                                    Tornar todas as respostas textuais visíveis para o autor
                                </label>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Sidebar --}}
            <div class="col-md-4">

                {{-- Arquivo da avaliação --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body">

                        <h6 class="fw-bold mb-3">
                            Arquivo da avaliação
                        </h6>


                        {{-- Arquivo já enviado --}}
                        @if ($arquivoAvaliacao)

                            <div class="border rounded-3 p-3 mb-3">

                                <div class="d-flex align-items-center gap-3">

                                    {{-- Ícone do arquivo --}}
                                    <div
                                        class="d-flex align-items-center justify-content-center flex-shrink-0 text-my-primary"
                                        style="width: 36px; height: 36px;"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="22"
                                            height="22"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            aria-hidden="true"
                                        >
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                            <polyline points="14 2 14 8 20 8"/>
                                        </svg>
                                    </div>


                                    {{-- Informações do arquivo --}}
                                    <div
                                        class="flex-grow-1"
                                        style="min-width: 0;"
                                    >

                                        <div
                                            class="small fw-semibold text-dark text-truncate"
                                            title="{{ basename($arquivoAvaliacao->nome) }}"
                                        >
                                            {{ basename($arquivoAvaliacao->nome) }}
                                        </div>

                                        <div class="small text-muted">
                                            Arquivo enviado pelo(a) avaliador(a)
                                        </div>

                                    </div>


                                    {{-- Download --}}
                                    <a
                                        href="{{ route('downloadAvaliacao', [
                                            'trabalhoId' => $trabalho->id,
                                            'revisorUserId' => $revisorUser->id
                                        ]) }}"
                                        class="btn btn-outline-secondary d-flex align-items-center justify-content-center flex-shrink-0"
                                        style="width: 36px; height: 36px; padding: 0;"
                                        title="Baixar arquivo"
                                        aria-label="Baixar arquivo"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="17"
                                            height="17"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            aria-hidden="true"
                                        >
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                            <polyline points="7 10 12 15 17 10"/>
                                            <line x1="12" y1="15" x2="12" y2="3"/>
                                        </svg>
                                    </a>

                                </div>

                            </div>


                            <p class="small text-muted mb-2">
                                Para substituir o arquivo atual, selecione um novo arquivo abaixo.
                            </p>

                        @else

                            <p class="small text-muted mb-3">
                                Nenhum arquivo foi enviado com esta avaliação.
                            </p>

                        @endif


                        {{-- Upload de novo arquivo --}}
                        <input
                            type="file"
                            class="form-control @error('arquivoAvaliacao') is-invalid @enderror"
                            name="arquivoAvaliacao"
                            id="arquivoAvaliacao"
                            accept="
                                @if ($modalidade->pdf).pdf,@endif
                                @if ($modalidade->jpg).jpg,@endif
                                @if ($modalidade->jpeg).jpeg,@endif
                                @if ($modalidade->png).png,@endif
                                @if ($modalidade->docx).docx,@endif
                                @if ($modalidade->odt).odt,@endif
                                @if ($modalidade->zip).zip,@endif
                                @if ($modalidade->svg).svg,@endif
                            "
                        >


                        {{-- Formatos aceitos --}}
                        <div class="form-text">

                            Arquivos aceitos:

                            @php
                                $formatos = [];

                                if ($modalidade->pdf) {
                                    $formatos[] = 'PDF';
                                }

                                if ($modalidade->jpg) {
                                    $formatos[] = 'JPG';
                                }

                                if ($modalidade->jpeg) {
                                    $formatos[] = 'JPEG';
                                }

                                if ($modalidade->png) {
                                    $formatos[] = 'PNG';
                                }

                                if ($modalidade->docx) {
                                    $formatos[] = 'DOCX';
                                }

                                if ($modalidade->odt) {
                                    $formatos[] = 'ODT';
                                }

                                if ($modalidade->zip) {
                                    $formatos[] = 'ZIP';
                                }

                                if ($modalidade->svg) {
                                    $formatos[] = 'SVG';
                                }
                            @endphp

                            {{ implode(', ', $formatos) }}.

                        </div>


                        @error('arquivoAvaliacao')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>
                </div>


                {{-- Ações --}}
                <div class="card shadow-sm">
                    <div class="card-body">

                        <h6 class="fw-bold mb-3">
                            Ações
                        </h6>

                        <div class="d-flex flex-column gap-2">

                            {{-- Salvar alterações --}}
                            <button
                                type="submit"
                                class="btn btn-my-primary w-100"
                                id="submeterFormBotao"
                            >
                                Salvar alterações
                            </button>


                            {{-- Encaminhamento --}}
                            @if ($trabalho->avaliado($revisor->user))

                                @if (
                                    $trabalho->getParecerAtribuicao($revisor->user)
                                    != 'encaminhado'
                                )

                                    <a
                                        href="{{ route(
                                            'trabalho.encaminhar',
                                            [
                                                $trabalho->id,
                                                $revisor
                                            ]
                                        ) }}"
                                        class="btn btn-outline-secondary w-100"
                                    >
                                        Encaminhar para autor(a)
                                    </a>

                                @else

                                    {{-- Lembrete de correção --}}
                                    @if ($trabalho->aprovado === null)

                                        <button
                                            type="submit"
                                            class="btn btn-outline-secondary w-100"
                                            form="avisoCorrecao"
                                        >
                                            Lembrar envio da correção
                                        </button>

                                    @endif


                                    {{-- Desfazer encaminhamento --}}
                                    <a
                                        href="{{ route(
                                            'trabalho.encaminhar',
                                            [
                                                $trabalho->id,
                                                $revisor
                                            ]
                                        ) }}"
                                        class="btn btn-outline-secondary w-100"
                                    >
                                        Desfazer encaminhamento
                                    </a>

                                @endif

                            @endif


                            {{-- Liberar / bloquear correção --}}
                            <button
                                type="button"
                                class="btn btn-outline-secondary w-100"
                                data-bs-toggle="modal"
                                data-bs-target="#avaliacao-corrigir-{{ $trabalho->id }}"
                            >
                                @if (!$trabalho->permite_correcao)
                                    Liberar para correção
                                @else
                                    Bloquear para correção
                                @endif
                            </button>


                            {{-- Separação visual para ação destrutiva --}}
                            <div class="border-top my-1"></div>


                            {{-- Excluir avaliação --}}
                            <button
                                type="button"
                                class="btn btn-outline-danger w-100"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteModal{{ $trabalho->id }}"
                            >
                                Excluir avaliação
                            </button>

                        </div>

                    </div>
                </div>

            </div>

        </div>

    </form>


    @push('modais')

        @include('coordenador.trabalhos.avaliacao-modal', [
            'trabalho' => $trabalho,
            'valor' => 'null',
            'descricao' => 'corrigir',
        ])

    @endpush

</div>


@include('components.delete_modal', [
    'route' => 'coord.avaliacao.destroy',
    'param' => 'trabalho_id',
    'entity_id' => $trabalho->id,
    'element' => $revisor->id,
    'param_element' => 'revisor_id',
])


@stack('modais')

@endsection


@section('javascript')

@parent

<script>

    document.addEventListener('DOMContentLoaded', function () {

        const selecionarTodas = document.getElementById('selecionarTodas');

        if (!selecionarTodas) {
            return;
        }

        selecionarTodas.addEventListener('change', function () {

            document
                .querySelectorAll('.resposta-visibilidade')
                .forEach(function (checkbox) {

                    if (!checkbox.disabled) {
                        checkbox.checked = selecionarTodas.checked;
                    }

                });

        });

    });

</script>

@endsection
