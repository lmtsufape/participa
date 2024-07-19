@component('mail::message')
# Olá {{ $user->name }}!



Agradecemos a sua contribuição como {{ $cargo }} no evento {{ $nomeEvento }}.

Acesse o <a href="{{$link}}">link</a> para visualizar e baixar o seu certificado.




@include('emails.footer')
@endcomponent
