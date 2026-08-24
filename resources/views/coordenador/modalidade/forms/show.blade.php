@extends('layouts.app')
@section('sidebar')
@endsection
@section('content')

    <style>
        select[readonly] {
            background: #eee;
            /*Simular campo inativo - Sugestão @GabrielRodrigues*/
            pointer-events: none;
            touch-action: none;
        }
    </style>
    @php
        $evento = $modalidade->evento;
    @endphp

    <div class="container">
        <div class="row mb-4">
            <div class="col-md-5">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                    <div>
                        <h3 class="mb-1 fw-bold">
                            Visualizar Formulário
                        </h3>
                        <p class="mb-0 text-muted">
                            Modalidade:
                            <strong class="text-my-primary">
                                {{ $modalidade->nome }}
                            </strong>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                @include('coordenador.modalidade.forms.mode-switch', ['form' => $form, 'active' => "visualizacao"])
            </div>
        </div>
        <div class="alert alert-info d-flex align-items-center gap-3 mb-3" role="alert">
            <div class="alert-icon">
                <i class="bi bi-info-circle"></i>
            </div>
            <div>
                <strong>Você está no modo de visualização.</strong>
                Para alterar perguntas, troque para o modo de edição.
            </div>
        </div>

 <div class="col-12">
    <div class="d-flex flex-column gap-3">
        @forelse ($form->perguntas->sortBy('ordem') as $pergunta)

            @php
                $respostaPadrao = $pergunta->respostasPadrao->first();
            @endphp

            <div class="card border-0 shadow-sm">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                        <div class="d-flex gap-3">

                            <div
                                class="rounded-circle bg-body-tertiary border
                                       d-flex align-items-center justify-content-center
                                       fw-bold text-my-primary"
                                style="width: 36px; height: 36px; min-width: 36px;"
                            >
                                {{ $loop->iteration }}
                            </div>

                            <div>
                                <h6 class="fw-bold mb-1">
                                    {{ $pergunta->pergunta }}
                                </h6>

                                <small class="text-muted">
                                    @if ($respostaPadrao?->paragrafo)
                                        Resposta em texto livre
                                    @elseif ($respostaPadrao?->opcoes?->isNotEmpty())
                                        Múltipla escolha
                                    @else
                                        Tipo de resposta não definido
                                    @endif
                                </small>
                            </div>
                        </div>

                        @if ($pergunta->visibilidade)
                            <span
                                class="badge rounded-pill
                                       text-bg-success-subtle text-success
                                       border border-success-subtle"
                            >
                                Visível ao autor
                            </span>
                        @else
                            <span class="badge rounded-pill text-bg-secondary">
                                Interna
                            </span>
                        @endif
                    </div>


                    {{-- Resposta em parágrafo --}}
                    @if ($respostaPadrao?->paragrafo)

                        <textarea
                            class="form-control bg-body-tertiary"
                            rows="3"
                            placeholder="Resposta em texto livre"
                            disabled
                        ></textarea>


                    {{-- Resposta por opções --}}
                    @elseif ($respostaPadrao?->opcoes?->isNotEmpty())

                        <div class="d-flex flex-column gap-2">
                            @foreach ($respostaPadrao->opcoes->sortBy('ordem') as $opcao)

                                <label
                                    class="border rounded-3 px-3 py-2
                                           bg-body-tertiary
                                           d-flex align-items-center gap-2 mb-0"
                                >
                                    <input
                                        class="form-check-input mt-0"
                                        type="radio"
                                        disabled
                                    >

                                    <span>
                                        {{ $opcao->titulo }}
                                    </span>
                                </label>

                            @endforeach
                        </div>


                    {{-- Resposta não configurada --}}
                    @else

                        <div class="alert alert-light border mb-0 text-muted">
                            Nenhum tipo de resposta configurado para esta pergunta.
                        </div>

                    @endif

                </div>
            </div>

        @empty

            <div class="alert alert-light border">
                Nenhuma pergunta cadastrada neste formulário.
            </div>

        @endforelse
    </div>
</div>
    </div>

@endsection
