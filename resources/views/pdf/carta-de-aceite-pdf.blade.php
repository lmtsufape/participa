<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Carta de Aceite - {{ $trabalho->titulo }}</title>
    <style>
        @page {
            margin: 25mm 20mm 20mm 20mm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #222;
            font-size: 13pt;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            max-width: 220px;
            height: auto;
            margin-bottom: 15px;
        }
        h1 {
            font-size: 20pt;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 10px 0 25px 0;
            color: #111827;
        }
        .prezados {
            font-weight: bold;
            margin-bottom: 15px;
            text-transform: uppercase;
            font-size: 11pt;
            color: #374151;
        }
        .autores {
            font-size: 12pt;
            margin-bottom: 25px;
            color: #1f2937;
        }
        .texto {
            text-align: justify;
            text-indent: 40px;
            margin-bottom: 30px;
        }
        .codigo-box {
            margin: 30px auto;
            padding: 15px;
            background-color: #f3f4f6;
            border: 1px dashed #9ca3af;
            border-radius: 6px;
            text-align: center;
            width: 80%;
        }
        .codigo-label {
            font-size: 10pt;
            text-transform: uppercase;
            color: #6b7280;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        .codigo-valor {
            font-family: 'Courier New', Courier, monospace;
            font-size: 16pt;
            font-weight: bold;
            letter-spacing: 3px;
            color: #111;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9pt;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
        .data-emissao {
            text-align: right;
            margin-top: 30px;
            font-size: 11pt;
        }
    </style>
</head>
<body>

    <div class="header">
        @if(!empty($logoBase64))
            <img src="{{ $logoBase64 }}" class="logo" alt="Logo">
        @endif
        <h1>Carta de Aceite</h1>
    </div>

    <div class="prezados">Prezados(as) Autores(as):</div>
    
    <div class="autores">
        <strong>{{ $trabalho->autor->name }}</strong>@if ($trabalho->coautors->count()), <strong>{{ $trabalho->coautors->pluck('user.name')->join(', ', ' e ') }}</strong>@endif
    </div>

    <div class="texto">
        A Comissão Científica do <strong>{{ $trabalho->evento->nome }}</strong> tem a satisfação de comunicar o <strong>ACEITE</strong> do seu trabalho "<strong>{{ $trabalho->titulo }}</strong>", na modalidade <strong>{{ $trabalho->modalidade->nome }}</strong> para ser apresentado no Círculo de Cultura do Eixo: <strong>{{ $trabalho->area->nome }}</strong>, no <strong>{{ $trabalho->evento->nome }}</strong>. Reafirmamos que o trabalho para ser publicado precisa ser apresentado.
    </div>

    <div class="codigo-box">
        <div class="codigo-label">Código Oficial de Autenticação</div>
        <div class="codigo-valor">{{ $codigo }}</div>
    </div>

    <div class="data-emissao">
        Emitido em: {{ \Carbon\Carbon::parse($trabalho->aprovacao_emitida_em ?? now())->format('d/m/Y \à\s H:i') }}
    </div>

    <div class="footer">
        Documento autenticável através do endereço: {{ route('validarCertificado') }}<br>
        © {{ date('Y') }} {{ $trabalho->evento->nome }} - Todos os direitos reservados.
    </div>

</body>
</html>