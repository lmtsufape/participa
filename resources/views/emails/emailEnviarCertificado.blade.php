@component('mail::message')
# Olá {{ $user->name }}!



Agradecemos a sua contribuição como {{ $cargo }} no evento {{ $nomeEvento }}.

Segue anexo da sua certificação.

@component('mail::button', ['url' => 'http://sistemas.ufape.edu.br/easy'])
Acessar o sistema
@endcomponent

@include('emails.footer')
@endcomponent
