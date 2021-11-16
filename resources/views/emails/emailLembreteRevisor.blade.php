@component('mail::message')
# Olá {{ $user->name }}!

Este e-mail é um lembrete de que você recebeu o(s) trabalho(s) ou atividade(s) {{$trabalhos}}para avaliação por parte da coordenação da comissão científica do "{{ $evento->nome }}" ({{ $coord->email }}).

A data-limite para a realização desta avaliação ou emissão de parecer é o dia {{ date('d/m/Y H:i:s', strtotime($dataLimite)) }}.

Agradecemos de antemão pela sua disponibilidade para colaborar com a realização deste evento.




@component('mail::button', ['url' => 'http://sistemas.ufape.edu.br/easy/login'])
Acessar site
@endcomponent

@include('emails.footer')
@endcomponent
