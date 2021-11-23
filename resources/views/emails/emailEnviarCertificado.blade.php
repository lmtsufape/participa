@component('mail::message')
# Olá {{ $user->name }}!



Agradecemos a sua contribuição como {{ $cargo }} no evento {{ $nomeEvento }}.

Segue anexo da sua certificação.


@include('emails.footer')
@endcomponent
