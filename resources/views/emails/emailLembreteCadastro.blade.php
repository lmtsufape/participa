@component('mail::message')
# Olá {{$email}}!



Este e-mail é um lembrete de que você foi indicado(a) pela coordenação do evento "{{ $evento }}" ({{ $coord->email }}) para atuar como avaliador(a) ou parecerista de atividades e/ou trabalhos acadêmicos e que **necessita completar o seu cadastro para ter acesso aos trabalhos para avaliação**.

Agradecemos de antemão pela sua disponibilidade para colaborar com a realização deste evento.

Seus dados de login para que possa completar o cadastro:

E-mail: {{$email}}

Senha temporária: {{$senha}}

@component('mail::button', ['url' => 'http://sistemas.ufape.edu.br/easy/login'])
Fazer login
@endcomponent

@include('emails.footer')
@endcomponent
