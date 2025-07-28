<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
</head>
<body>
    <h4>Editado - {{$convidado->atividade->titulo}}</h4>
    <p>
        A atividade {{$convidado->atividade->titulo}} do evento {{$convidado->atividade->evento->nome}} a qual vossa senhoria é convidado como {{$convidado->funcao}} foi atualizada.
    </p>
    <p>
        <h6>Segue os novos dias e horários que a atividade irá ocorrer</h6>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Data</th>
                    <th scope="col">Hora de início</th>
                    <th scope="col">Hora de termino</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($convidado->atividade->datasAtividade as $dataAtv)
                    <tr>
                        <th scope="row">{{date('d/m/Y', strtotime($dataAtv->data))}}</th>
                        <th>{{$dataAtv->hora_inicio}}</th>
                        <th>{{$dataAtv->hora_fim}}</th>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </p>
    <p>
        @include('emails.footer')
    </p>

</body>
</html>
