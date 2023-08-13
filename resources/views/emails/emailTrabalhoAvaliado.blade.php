@component('mail::message')
# Olá {{ $user->name }}!



O trabalho ou atividade {{ $trabalho->titulo }}, submetido por {{ $autor->name }}, no evento {{ $nomeEvento }}, acaba de ser avaliado pelo revisor {{ $revisor->user->name }}.

Acesse o sistema para verificar a avaliação.

@component('mail::button', ['url' => route('home')])
Acessar o sistema
@endcomponent

@include('emails.footer')
@endcomponent
