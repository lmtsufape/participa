<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Lista de Trabalhos Aprovados</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        h1 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
        }
        h2 {
            font-size: 14px;
            margin-top: 20px;
            margin-bottom: 10px;
            color: #333;
        }
        .trabalho {
            margin-bottom: 5px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .trabalho h3 {
            font-size: 13px;
            margin: 0 0 5px 0;
            color: #000;
        }
        .trabalho p {
            margin: 3px 0;
        }
        .autor {
            font-weight: bold;
        }
        .coautores {
            font-style: italic;
        }
        .area {
            color: #666;
        }
    </style>
</head>
<body>
    <h1>Lista de Trabalhos Aprovados - {{ $evento->nome }}</h1>

    @foreach ($trabalhosPorModalidade as $trabalhos)
        <h2>Modalidade: {{ $trabalhos[0]->modalidade->nome }}</h2>
        
        @foreach ($trabalhos as $trabalho)
            <div class="trabalho">
                <h3>{{ $trabalho->titulo }}</h3>
                <p class="autor">Autor: {{ $trabalho->autor->name }}</p>
                
                @if ($trabalho->coautors->count() > 0)
                    <p class="coautores">Coautores: 
                        {{ $trabalho->coautors->map(function($coautor) {
                            return $coautor->user->name;
                        })->join('; ') }}
                    </p>
                @endif
                
                <p class="area">Área: {{ $trabalho->area->nome }}</p>
            </div>
        @endforeach
    @endforeach
</body>
</html> 