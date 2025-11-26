@component('mail::message')
# Olá!

{!! nl2br(e($mensagemPersonalizada)) !!}

@component('mail::panel')
Evento: {{ $evento->nome }}
@endcomponent

Em caso de dúvidas, responda este e-mail para falar com a organização do evento.

@include('emails.footer')
@endcomponent
