<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Carta de Aceite - {{ $trabalho->titulo }}</title>
    <style>
        @page {
            margin: 25mm 20mm 20mm 20mm;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #111111;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            font-size: 14px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
        }
        .header img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
        }
        h1 {
            font-size: 22px;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 10px;
            margin-bottom: 25px;
            color: #114048;
        }
        .texto-aceite {
            text-align: justify;
            font-size: 14px;
            line-height: 1.8;
            margin-bottom: 25px;
        }
        .data-emissao {
            text-align: right;
            margin-bottom: 35px;
            font-size: 14px;
        }
        .assinatura-section {
            text-align: center;
            margin-top: 15px;
            margin-bottom: 30px;
        }
        .assinatura-img {
            max-width: 180px;
            height: auto;
            margin: 0 auto 5px auto;
            display: block;
        }
        .assinatura-linha {
            width: 250px;
            border-top: 1px solid #333333;
            margin: 0 auto 6px auto;
        }
        .assinatura-nome {
            font-weight: bold;
            font-size: 13px;
        }
        .assinatura-cargo {
            font-size: 12px;
            color: #555555;
        }
        .autenticacao-box {
            border: 1px solid #d1d5db;
            background-color: #f9fafb;
            border-radius: 8px;
            padding: 12px;
            text-align: center;
            margin-top: 20px;
        }
        .autenticacao-titulo {
            font-size: 11px;
            color: #4b5563;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        .autenticacao-codigo {
            font-family: 'Courier New', Courier, monospace;
            font-size: 16px;
            font-weight: bold;
            color: #111827;
            letter-spacing: 2px;
        }
        .autenticacao-link {
            font-size: 10px;
            color: #6b7280;
            margin-top: 5px;
        }
        .footer-logo {
            text-align: center;
            margin-top: 25px;
        }
        .footer-logo img {
            max-width: 120px;
            height: auto;
        }
    </style>
</head>
<body>

    @if ($bannerBase64)
        <div class="header">
            <img src="{{ $bannerBase64 }}" alt="Header CBEE">
        </div>
    @endif

    <h1>Carta de Aceite</h1>

    <div class="texto-aceite">
        Temos a satisfação de comunicar que, após análise da Comissão Científica, o trabalho intitulado 
        "<strong>{{ $trabalho->titulo }}</strong>", 
        de autoria de <strong>{{ $trabalho->autor->name }}</strong>@if ($trabalho->coautors->count()), com os coautores/as <strong>{{ $trabalho->coautors->pluck('user.name')->join(', ', ' e ') }}</strong>@endif, 
        foi <strong>aprovado</strong> na modalidade <strong>{{ $trabalho->modalidade->nome }}</strong> para apresentação no evento 
        <strong>XV CONGRESSO BRASILEIRO DE ETNOBIOLOGIA E ETNOECOLOGIA (CBEE)</strong>, 
        Convivência com os Territórios Brasileiros e Justiça Climática. O evento ocorrerá entre os dias 16 a 19 de novembro de 2026 na Universidade Federal de Minas Gerais (UFMG), cidade de Belo Horizonte, estado de Minas Gerais, Brasil.
    </div>

    <div class="data-emissao">
        Belo Horizonte, {{ \Carbon\Carbon::parse($trabalho->aprovacao_emitida_em ?? now())->locale('pt_BR')->isoFormat('D [de] MMMM [de] YYYY') }}.
    </div>

    <div class="assinatura-section">
        @if ($assinaturaBase64)
            <img src="{{ $assinaturaBase64 }}" alt="Assinatura" class="assinatura-img">
        @endif
        <div class="assinatura-linha"></div>
        <div class="assinatura-nome">Emmanuel Duarte Almada</div>
        <div class="assinatura-cargo">Presidente da comissão organizadora</div>
    </div>

    <div class="autenticacao-box">
        <div class="autenticacao-titulo">Código oficial de aprovação e autenticidade</div>
        <div class="autenticacao-codigo">{{ $codigo }}</div>
        <div class="autenticacao-link">
            Documento autenticado digitalmente. Para validar este aceite, acesse: {{ route('validarCertificado') }}
        </div>
    </div>

    @if ($logoBase64)
        <div class="footer-logo">
            <img src="{{ $logoBase64 }}" alt="Logo SBEE">
        </div>
    @endif

</body>
</html>