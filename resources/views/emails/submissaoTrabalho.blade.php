@component('mail::message')
# Olá {{ $user->name }}!



O trabalho, no qual você está como coautor, intitulado de '{{$trabalho->titulo}}' foi recebido com sucesso!

@component('mail::button', ['url' => route('home')])
Acessar o sistema
@endcomponent

@include('emails.footer')
@endcomponent
