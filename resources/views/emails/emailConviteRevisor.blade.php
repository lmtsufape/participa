@component('mail::message')
# Seja Bem-Vindo

    Convidamos vossa senhoria, para ser revisor do evento {{$evento->nome}}.</h4>
    

@component('mail::button', ['url' => 'http://sistemas.ufape.edu.br/easy'])
Acessar site
@endcomponent

Obrigado,<br>
{{ config('app.name') }}
@endcomponent