@extends('emails.layouts.app')

@section('title', 'Carta de Aceite')

@section('preview')
    Carta de Aceite do {{ $trabalho->evento->nome }} — confirmação de aprovação do trabalho.
@endsection

@section('content')
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px;">
        <tr>
            <td style="vertical-align:middle;padding-right:16px;">

                <p
                    style="
                margin:0 0 6px;
                color:#007e85;
                font-size:11px;
                font-weight:bold;
                letter-spacing:1px;
            ">
                    COMUNICAÇÃO OFICIAL
                </p>

                <h1
                    style="
                margin:0 0 8px;
                font-size:26px;
                line-height:1.3;
                color:#12252d;
            ">
                    Carta de Aceite
                </h1>

                <p
                    style="
                margin:0;
                font-size:13px;
                color:#6b7280;
            ">
                    Confirmação de aprovação de trabalho
                </p>

            </td>

            @if ($imgPath)
                <td width="120" align="right" style="vertical-align:middle;">
                    <img src="{{ $message->embed($imgPath) }}" alt="Imagem do evento" width="120"
                        style="
                        display:block;
                        width:120px;
                        max-width:120px;
                        height:auto;
                        border:0;
                        border-radius:6px;
                    ">
                </td>
            @endif
        </tr>
    </table>

    {{-- Comunicado de aprovação --}}
    <p
        style="
        margin: 0 0 14px;
        font-size: 16px;
        text-align: justify;
        line-height: 1.7;
    ">

        Temos a satisfação de comunicar que, após análise da
        Comissão Científica, o trabalho intitulado

        "<strong>{{ $trabalho->titulo }}</strong>",

        de autoria de
        <strong>{{ $trabalho->autor->name }}</strong>

        @if ($trabalho->coautors->count())
            , com os coautores/as
            <strong>
                {{ $trabalho->coautors->pluck('user.name')->join(', ', ' e ') }}
            </strong>
        @endif

        foi <strong>aprovado</strong> na modalidade

        <strong>{{ $trabalho->modalidade->nome }}</strong>

        para apresentação no evento

        <strong>{{ $trabalho->evento->nome }}</strong>.

        A ser realizado de

        <strong>
            {{ $trabalho->evento->dataInicio?->format('d/m/Y') }}

            @if ($trabalho->evento->dataFim)
                a {{ $trabalho->evento->dataFim->format('d/m/Y') }}.
            @endif
        </strong>

    </p>

    {{-- Código oficial de aprovação --}}
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin: 24px 0;">
        <tr>
            <td align="center">

                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
                    style="
                        background-color: #f8fafc;
                        border: 1px solid #e5e7eb;
                        border-radius: 8px;
                    ">
                    <tr>
                        <td align="center" style="padding: 20px 16px;">

                            <p
                                style="
                                margin: 0 0 10px;
                                font-size: 13px;
                                color: #374151;
                            ">
                                Código oficial de aprovação
                            </p>

                            <p
                                style="
                                margin: 0;
                                font-family: Consolas, monospace;
                                font-size: 22px;
                                line-height: 1.4;
                                letter-spacing: 2px;
                                color: #111;
                                overflow-wrap: anywhere;
                            ">
                                {{ $codigo }}
                            </p>

                            <p
                                style="
                                margin: 12px 0 0;
                                font-size: 12px;
                                color: #6b7280;
                                overflow-wrap: anywhere;
                            ">
                                Acesse o link:

                                <a href="{{ route('validarCertificado') }}"
                                    style="
                                        color: #007e85;
                                        text-decoration: underline;
                                    ">
                                    {{ route('validarCertificado') }}
                                </a>
                            </p>

                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>

    {{-- Assinatura --}}
    <p
        style="
        margin: 24px 0 4px;
        font-size: 16px;
        text-align: center;
        color: #172b35;
    ">
        Comissão Científica do {{ $trabalho->evento->nome }}
    </p>

    @if ($trabalho->evento->email)
        <p style="
            margin: 0 0 4px;
            font-size: 14px;
            text-align: center;
        ">
            <a href="mailto:{{ $trabalho->evento->email }}"
                style="
                    color: #007e85;
                    text-decoration: underline;
                ">
                {{ $trabalho->evento->email }}
            </a>
        </p>
    @endif

@endsection

@section('footer')
    <p style="
        margin: 0 0 8px;
        font-size: 13px;
        color: #526675;
    ">
        Este é um e-mail automático da plataforma Participa.
    </p>

    <p style="
        margin: 0;
        font-size: 12px;
        color: #6b7d89;
    ">
        &copy; {{ now()->year }} {{ $trabalho->evento->nome }}
        • Não responda a este e-mail.
    </p>
@endsection
