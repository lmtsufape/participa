@component('mail::message')
# Olá {{ $user->name }}!



O trabalho ou atividade {{ $trabalho->titulo }}, submetido por {{ $autor->name }}, no evento {{ $nomeEvento }}, acaba de ser avaliado pelo revisor {{ $revisor->name }}.

Acesse o sistema para verificar a avaliação.

@component('mail::button', ['url' => 'http://sistemas.ufape.edu.br/easy'])
Acessar o sistema
@endcomponent

Atenciosamente,<br>
{{ config('app.name') }}<br>
Laboratório Multidisciplinar de Tecnologias Sociais<br>
Universidade Federal do Agreste de Pernambuco
@endcomponent
