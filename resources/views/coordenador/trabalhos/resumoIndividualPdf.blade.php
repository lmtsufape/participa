<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>{{ $trabalho->titulo }}</title>
    <style>
        body {
            color: #212529;
            font-family: sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }
        h1 {
            color: #3d93a9;
            font-size: 20px;
            margin-bottom: 6px;
        }
        .contexto {
            color: #666;
            margin-bottom: 24px;
        }
        .campo {
            margin-bottom: 12px;
        }
        .rotulo {
            font-weight: bold;
        }
        .resumo {
            text-align: justify;
            white-space: pre-line;
        }
    </style>
</head>
<body>
    <h1>{{ $trabalho->titulo }}</h1>
    <div class="contexto">
        <div>{{ $evento->nome }}</div>
        <div>Eixo: {{ $area->nome }}</div>
        <div>Modalidade: {{ optional($trabalho->modalidade)->nome ?? 'Não informada' }}</div>
    </div>

    <div class="campo">
        <span class="rotulo">Autor:</span>
        {{ optional($trabalho->autor)->name ?? 'Não informado' }}
    </div>

    @php($coautores = $trabalho->coautors->pluck('user')->filter())
    @if ($coautores->isNotEmpty())
        <div class="campo">
            <span class="rotulo">Coautores:</span>
            {{ $coautores->pluck('name')->join('; ') }}
        </div>
    @endif

    <div class="campo">
        <div class="rotulo">Resumo:</div>
        <div class="resumo">{{ $trabalho->resumo ?: 'Não informado' }}</div>
    </div>
</body>
</html>
