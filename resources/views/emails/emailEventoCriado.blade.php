@component('mail::message')
# Olá {{ $user->name }}!



O evento intitulado '{{$evento->titulo}}' foi criado com sucesso!

@component('mail::button', ['url' => 'http://participa.ufape.edu.br'])
Acessar o sistema
@endcomponent

@include('emails.footer')
@endcomponent


