@component('mail::message')
# Olá, {{ $user->name }}!

Temos uma atualização sobre a sua solicitação de inscrição como Pessoa com Deficiência (PCD) para o evento **{{ $evento->nome }}**.

Infelizmente, sua solicitação foi rejeitada pela coordenação do evento.

Caso acredite que isso seja um erro ou queira mais informações, por favor, entre em contato com a organização do evento.

Atenciosamente,<br>
A equipe do {{ config('app.name') }}
@endcomponent
