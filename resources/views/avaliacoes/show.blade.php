@extends('layouts.app')

@section('content')

<div class="container mb-4 position-relative">

    <x-admin.content-header
        title="Visualizar avaliação"
        description="Consulte as respostas que você enviou nesta avaliação."
    />

    {{-- Informações do trabalho --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3 g-md-0">

                <p class="col-md-3 mb-0 text-muted text-break border-end pe-md-3">
                    Evento
                    <strong class="d-block text-my-primary">
                        {{ $evento->nome }}
                    </strong>
                </p>

                <p class="col-md-3 mb-0 text-muted text-break border-end px-md-3">
                    Modalidade
                    <strong class="d-block text-my-primary">
                        {{ $modalidade->nome }}
                    </strong>
                </p>

                <p class="col-md-3 mb-0 text-muted text-break border-end px-md-3">
                    {{ $evento->formSubTrab->etiquetaareatrabalho }}

                    <strong class="d-block text-my-primary">
                        {{ $trabalho->area->nome }}
                    </strong>
                </p>

                <p class="col-md-3 mb-0 text-muted text-break ps-md-3">
                    {{ $evento->formSubTrab->etiquetatitulotrabalho }}

                    <strong class="d-block text-my-primary">
                        {{ $trabalho->titulo }}
                    </strong>
                </p>

            </div>
        </div>
    </div>


    {{-- Avaliação --}}
    <div class="card shadow-lg">

        <div class="card-header bg-white py-4 px-4">

            <h3 class="card-title h4 text-my-primary mb-0">
                <strong>{{ $form->titulo }}</strong>
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

            @forelse ($form->perguntas->sortBy('ordem') as $pergunta)

                @continue(!$pergunta->visibilidade)

                @php
                    $respostaPadrao = $pergunta->respostasPadrao->first();
                    $respostaRevisor = $pergunta->respostasRevisores->first();
                @endphp

                @continue(!$respostaPadrao)

                <div class="card mb-3">
                    <div class="card-body">

                        {{-- Número e pergunta --}}
                        <div class="mb-3">

                            <span class="small fw-semibold text-my-primary d-block mb-1">
                                Pergunta {{ $loop->iteration }}
                            </span>

                            <div class="fw-bold">
                                {!! $pergunta->pergunta !!}
                            </div>

                        </div>

                        {{-- Pergunta com opções --}}
                        @if ($respostaPadrao->opcoes->isNotEmpty())

                            @foreach ($respostaPadrao->opcoes->sortBy('ordem') as $opcao)

                                @php
                                    $selecionada = $respostaRevisor
                                        ?->opcoes
                                        ?->contains('titulo', $opcao->titulo) ?? false;
                                @endphp

                                <div class="form-check mb-2">

                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="pergunta_{{ $pergunta->id }}"
                                        id="opcao_{{ $opcao->id }}"
                                        @checked($selecionada)
                                        disabled
                                    >

                                    <label
                                        class="form-check-label {{ $selecionada ? 'fw-semibold text-dark' : '' }}"
                                        for="opcao_{{ $opcao->id }}"
                                    >
                                        {!! $opcao->titulo !!}
                                    </label>

                                </div>

                            @endforeach

                        {{-- Pergunta de texto --}}
                        @elseif ($respostaPadrao->paragrafo)

                            @if (
                                $respostaRevisor &&
                                $respostaRevisor->paragrafo &&
                                $respostaRevisor->paragrafo->visibilidade
                            )

                                <div
                                    class="form-control bg-light text-dark"
                                    style="min-height: 90px;"
                                >
                                    {{ $respostaRevisor->paragrafo->resposta }}
                                </div>

                            @else

                                <div
                                    class="form-control bg-light text-muted fst-italic"
                                    style="min-height: 70px;"
                                >
                                    Pergunta não respondida.
                                </div>

                            @endif

                        @endif

                    </div>
                </div>

            @empty

                <div class="alert alert-light border mb-0">
                    Não há respostas disponíveis para esta avaliação.
                </div>

            @endforelse


            {{-- Arquivo corrigido --}}
            @if ($arquivoAvaliacao)

                <div class="mt-4">

                    <label class="form-label">
                        <strong>
                            Trabalho corrigido e/ou com comentários
                        </strong>
                    </label>

                    <div class="border rounded p-3">

                        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">

                            <div class="text-muted">
                                Arquivo enviado por você durante o preenchimento desta avaliação.
                            </div>

                            <a
                                class="btn btn-my-primary"
                                href="{{ route('downloadAvaliacao', [
                                    'trabalhoId' => $trabalho->id,
                                    'revisorUserId' => $revisorUser->id
                                ]) }}"
                            >
                                <i class="fa-solid fa-download me-2"></i>
                                Baixar arquivo
                            </a>

                        </div>

                    </div>

                </div>

            @endif


            {{-- Voltar --}}
            <div class="row justify-content-center mt-4">

                <div class="col-md-6">

                    <button
                        type="button"
                        class="btn btn-secondary w-100"
                        onclick="history.back()"
                    >
                        Voltar
                    </button>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
