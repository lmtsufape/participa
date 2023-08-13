@component('mail::message')
# Olá {{ $user->name }}!



O evento intitulado '{{$evento->titulo}}' foi criado com sucesso!

@component('mail::button', ['url' => route('home')])
Acessar o sistema
@endcomponent

@include('emails.footer')
@endcomponent


