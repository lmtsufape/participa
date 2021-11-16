@component('mail::message')
# Olá {{ $user->name }}!



O trabalho, no qual você está como coautor, intitulado de '{{$trabalho->titulo}}' foi recebido com sucesso!

@component('mail::button', ['url' => 'http://sistemas.ufape.edu.br/easy'])
Acessar o sistema
@endcomponent

Atenciosamente,<br>
{{ config('app.name') }}<br>
Laboratório Multidisciplinar de Tecnologias Sociais<br>
Universidade Federal do Agreste de Pernambuco
@endcomponent
