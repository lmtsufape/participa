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
                    [
                        'titulo' => '1. Cronograma das Sessões de Pôsters',
                        'arquivo' => $arquivoPosters,
                        'botao' => 'Baixar Cronograma das Sessões de Pôsters (.pdf)',
                        'orientacoes_titulo' => 'Apresentação de Poster - Orientações',
                        'orientacoes' => [
                            'Você pode baixar o modelo do banner na aba normas deste site;',
                            'É permitido que outro autor ou autora (que não seja o principal) apresente o trabalho;',
                            'Serão dois dias de exposição, porém cada trabalho será apresentado somente em um dia (quarta ou quinta-feira). Fique atento(a) no cronograma para saber sua data de exposição;',
                            'Entre em contato com a organização do evento se necessitar indicações de gráficas para impressão do seu banner em Belo Horizonte.',
                        ],
                    ],
                    [
                        'titulo' => '2. Cronograma de Comunicações Orais',
                        'arquivo' => $arquivoComunicacoesOrais,
                        'botao' => 'Baixar Cronograma de Comunicações Orais (.pdf)',
                        'orientacoes_titulo' => 'Comunicação Oral - Orientações',
                        'orientacoes' => [
                            'Você pode baixar o modelo de apresentação em PowerPoint na aba normas deste site;',
                            'As apresentações serão de até 15 minutos. Após um período de tempo, abriremos para discussão dos trabalhos apresentados. Portanto, permaneça na sala antes e após a sua apresentação para participar das discussões.',
                            'Salve seu trabalho na nuvem e em um PenDrive para evitar possíveis problemas com internet;',
                            'Se possível, leve seu computador para a projeção dos slides;',
                            'É permitido que outro autor ou autora (que não seja o principal) apresente o trabalho, basta comunicar o avaliador no momento da apresentação.',
                        ],
                    ],
                ];
            @endphp

            @foreach ($cronogramas as $cronograma)
                <div class="shadow-sm p-4 mb-5 bg-white rounded border-start border-4" style="border-color: #f2a440 !important;">
                    <h2 class="h4 mb-4 fw-bold" style="color: #3d93a9;">{{ $cronograma['titulo'] }}</h2>
                    <p class="fw-bold mb-2">{{ $cronograma['orientacoes_titulo'] }}</p>
                    <ul class="mb-4" style="line-height: 1.6;">
                        @foreach ($cronograma['orientacoes'] as $orientacao)
                            <li class="mb-2">{{ $orientacao }}</li>
                        @endforeach
                    </ul>
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
