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
        Att, Coordenador do evento.
    </p>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>
</html>