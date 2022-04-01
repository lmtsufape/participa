@component('mail::message')
# Olá {{ $user->name }}!



Você foi indicado(a) pela coordenação do evento "{{ $evento->nome }}" ({{ $coord->email }}) para atuar como avaliador(a) ou parecerista de atividades e/ou trabalhos acadêmicos.

Pedimos que acesse o site para completar o seu cadastro, caso não o possua, e ter acesso aos trabalhos para avaliação.

Agradecemos de antemão pela sua disponibilidade para colaborar com a realização deste evento.


@component('mail::button', ['url' => 'http://participa.ufape.edu.br'])
Acessar site
@endcomponent

@include('emails.footer')
@endcomponent
