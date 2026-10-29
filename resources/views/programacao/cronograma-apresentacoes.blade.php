@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h1 class="display-4 mb-4 fw-bold" style="color: #3d93a9; border-bottom: 3px solid #f2a440; padding-bottom: 10px;">
                Cronograma de Apresentação Oral e Pôsters
            </h1>

            @php
                $arquivoPosters = 'documentos/CronogramaSessoesPosteres.pdf';
                $arquivoComunicacoesOrais = 'documentos/CronogramaComunicacoesOrais.pdf';
                $cronogramas = [
                    ['titulo' => '1. Cronograma das Sessões de Pôsters', 'arquivo' => $arquivoPosters, 'botao' => 'Baixar Cronograma das Sessões de Pôsters (.pdf)'],
                    ['titulo' => '2. Cronograma de Comunicações Orais', 'arquivo' => $arquivoComunicacoesOrais, 'botao' => 'Baixar Cronograma de Comunicações Orais (.pdf)'],
                ];
            @endphp

            @foreach ($cronogramas as $cronograma)
                <div class="shadow-sm p-4 mb-5 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                    <h2 class="h4 mb-4 fw-bold" style="color: #3d93a9;">{{ $cronograma['titulo'] }}</h2>
                    <div class="my-3">
                        <a href="{{ $cronograma['arquivo'] !== '' ? asset($cronograma['arquivo']) : '' }}"
                           download
                           class="btn btn-primary d-inline-flex align-items-center gap-2 {{ $cronograma['arquivo'] === '' ? 'disabled' : '' }}"
                           @if ($cronograma['arquivo'] === '') aria-disabled="true" tabindex="-1" @endif>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-file-earmark-slides" viewBox="0 0 16 16" aria-hidden="true">
                                <path d="M7 5.5a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1z"/>
                                <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5z"/>
                            </svg>
                            {{ $cronograma['botao'] }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
