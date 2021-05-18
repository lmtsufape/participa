@component('mail::message')
# Seja Bem-Vindo

The body of your message.

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') .'- Eventos Acadêmicos
Laboratório Multidisciplinar de Tecnologias Sociais
Universidade Federal do Agreste de Pernambuco' }}
@endcomponent
