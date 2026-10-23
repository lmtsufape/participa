@extends('emails.layouts.app')

@section('title', 'Parecer disponível')

@section('preview')
    O parecer do seu trabalho está disponível para consulta no Participa.
@endsection

@section('content')

    <x-emails.title
        title="Parecer disponível"
        subtitle="Avaliação do trabalho liberada para consulta"
    />

    <p style="margin: 0 0 16px;">
        Olá, {{ $trabalho->autor->name }}!
    </p>

    <p style="margin: 0 0 16px;">
        Informamos que a Comissão Científica do evento
        <strong>{{ $trabalho->evento->nome }}</strong>
        disponibilizou o parecer referente ao trabalho
        <strong>“{{ $trabalho->titulo }}”</strong>.
    </p>

    <p style="margin: 0 0 20px;">
        Você já pode acessar a plataforma Participa para
        consultar a avaliação e os comentários dos avaliadores.
    </p>

    <x-emails.button :href="$url">
        Consultar parecer
    </x-emails.button>

    <p style="
        margin: 20px 0 0;
        font-size: 13px;
        color: #526675;
    ">
        Esta notificação informa apenas a disponibilização
        do parecer para consulta.
    </p>

@endsection
