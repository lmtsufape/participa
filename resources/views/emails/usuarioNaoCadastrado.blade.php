@component('mail::message',['url' => 'http://sistemas.ufape.edu.br/easy'])
# Seja Bem-Vindo!

Você foi convidado a se cadastrar no evento {{$evento}} como {{$funcao}} pelo o usuário {{$user}},
para ativar é preciso fazer login na sua conta.


Seus dados de login para que possa completar o cadastro:

E-mail: {{$email}}

Senha temporária: {{$senha}}


@component('mail::button', ['url' => 'http://sistemas.ufape.edu.br/easy/login'])
Fazer login
@endcomponent

Obrigado,<br>
{{ config('app.name') }}
@endcomponent
