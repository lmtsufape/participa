@component('mail::message')
# Olá {{ $trabalho->autor->name }}!



O parecer do seu trabalho ou atividade "{{ $trabalho->titulo }}", submetido no evento "{{ $evento->nome }}", está disponível.

Acesse o sistema para verificar o parecer.

@component('mail::button', ['url' => 'http://sistemas.ufape.edu.br/easy'])
Acessar o sistema
@endcomponent

Atenciosamente,<br>
{{ config('app.name') }}<br>
Laboratório Multidisciplinar de Tecnologias Sociais<br>
Universidade Federal do Agreste de Pernambuco
@endcomponent
