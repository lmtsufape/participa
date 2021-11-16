<!DOCTYPE html>
<html>
<head>

</head>
<body>
    <h4>Removido - {{$convidado->atividade->titulo}}</h4>
    <p>
        Informamos que vosse senhoria foi removido da atividade {{$convidado->atividade->titulo}} do evento {{$convidado->atividade->evento->nome}} como {{$convidado->funcao}}.
    </p>
    <p>
        @include('emails.footer')
    </p>
</body>
</html>
