@component('mail::message')
# Aviso

    
Você foi  atribuído ao trabalho: {{$info}} como revisor pelo o Easy - Sistema de Gestão de Eventos.
    
 

@component('mail::button', ['url' => 'http://sistemas.ufape.edu.br/easy/login'])
Acessar site
@endcomponent

Obrigado,<br>
{{ config('app.name') }}
@endcomponent