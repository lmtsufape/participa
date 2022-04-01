@component('mail::message')
# Olá {{ $trabalho->autor->name }}!



O parecer do seu trabalho ou atividade "{{ $trabalho->titulo }}", submetido no evento "{{ $evento->nome }}", está disponível.

Acesse o sistema para verificar o parecer.

@component('mail::button', ['url' => 'http://participa.ufape.edu.br'])
Acessar o sistema
@endcomponent

@include('emails.footer')
@endcomponent
