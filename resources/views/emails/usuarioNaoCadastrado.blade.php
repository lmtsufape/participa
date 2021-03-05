@component('mail::message',['url' => 'http://sistemas.ufape.edu.br/easy'])
# Seja Bem-Vindo

    
Você foi convidado a se cadastrar no evento {{$evento}} como {{$funcao}} pelo o usuário {{$user}}, 
para ativar é preciso fazer login na sua conta, seus dados de login.

E-mail: {{$email}}

senha temporaria: {{$senha}}
 

@component('mail::button', ['url' => 'http://sistemas.ufape.edu.br/easy/login'])
Fazer login
@endcomponent

Obrigado,<br>
{{ config('app.name') }}
@endcomponent