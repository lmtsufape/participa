@component('mail::message')
# Olá {{ $user->name }}!



Agradecemos a sua contribuição como {{ $cargo }} no evento {{ $nomeEvento }}.

Segue em anexo sua certificação.

@component('mail::button', ['url' => 'http://sistemas.ufape.edu.br/easy'])
Acessar o sistema
@endcomponent

Atenciosamente,<br>
{{ config('app.name') }}<br>
Laboratório Multidisciplinar de Tecnologias Sociais<br>
Universidade Federal do Agreste de Pernambuco
@endcomponent
