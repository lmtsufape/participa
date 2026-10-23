@extends('emails.layouts.app')

@section('title', 'Trabalho liberado para correção')

@section('preview')
    Seu trabalho foi liberado para correção. Confira o prazo para envio.
@endsection

@section('content')

    <x-emails.title
        title="Trabalho liberado para correção"
        subtitle="Período para envio da versão corrigida"
    />

    <p style="margin: 0 0 16px;">
        Olá, {{ $trabalho->autor->name }}!
    </p>

    <p style="margin: 0 0 16px;">
        Informamos que o trabalho
        <strong>“{{ $trabalho->titulo }}”</strong>,
        submetido ao evento
        <strong>{{ $trabalho->evento->nome }}</strong>,
        foi liberado para correção.
    </p>

    <p style="margin: 0 0 20px;">
        Você poderá realizar os ajustes necessários e enviar
        uma nova versão do trabalho dentro do período
        estabelecido abaixo.
    </p>

    {{-- Período de correção --}}
    <table
        role="presentation"
        width="100%"
        cellpadding="0"
        cellspacing="0"
        border="0"
        style="
            margin: 24px 0;
            background-color: #f8fafb;
            border: 1px solid #dce5e8;
            border-radius: 8px;
        "
    >
        <tr>
            <td style="padding: 20px 24px;">

                <p style="
                    margin: 0 0 16px;
                    font-size: 15px;
                    font-weight: bold;
                    color: #00454d;
                ">
                    Período para envio das correções
                </p>

                <p style="margin: 0 0 10px; font-size: 14px;">
                    <strong>Início:</strong>
                    {{ $trabalho->modalidade->inicioCorrecao->format('d/m/Y') }}
                </p>

                <p style="margin: 0; font-size: 14px;">
                    <strong>Término:</strong>
                    {{ $trabalho->modalidade->fimCorrecao->format('d/m/Y') }}
                </p>

            </td>
        </tr>
    </table>

    <p style="margin: 0 0 20px;">
        Acesse a plataforma para consultar as orientações
        disponíveis e submeter a versão corrigida do trabalho.
    </p>

    <x-emails.button :href="$url">
        Acessar trabalho
    </x-emails.button>

    <p style="
        margin: 20px 0 0;
        font-size: 13px;
        color: #526675;
    ">
        Atenção: a versão corrigida deverá ser submetida
        dentro do prazo estabelecido pela organização do evento.
    </p>

@endsection
