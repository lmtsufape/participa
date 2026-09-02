@component('mail::message')
# Olá {{ $trabalho->autor->name }}!



O parecer do seu trabalho ou atividade "{{ $trabalho->titulo }}", submetido no evento "{{ $evento->nome }}", está disponível.

Você tem 05 dias de prazo para revisar e enviar corrigido pelo sistema. Não se esqueça de adicionar nomes, vínculos e e-mails nessa segunda submissão, conforme Edital.

Acesse o sistema para verificar o parecer.

@component('mail::button', ['url' => route('home')])
Acessar o sistema
@endcomponent

@include('emails.footer')
@endcomponent
