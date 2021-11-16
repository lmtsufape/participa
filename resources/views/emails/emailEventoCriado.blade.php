@component('mail::message')
# Olá {{ $user->name }}!



O evento intitulado '{{$evento->titulo}}' foi criado com sucesso!

@component('mail::button', ['url' => 'http://sistemas.ufape.edu.br/easy'])
Acessar o sistema
@endcomponent

Atenciosamente,<br>
{{ config('app.name') }}<br>
Laboratório Multidisciplinar de Tecnologias Sociais<br>
Universidade Federal do Agreste de Pernambuco
@endcomponent


