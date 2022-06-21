@extends('coordenador.detalhesEvento')

@section('menu')

<div id="" style="display: block">
    <div class="row">
        <div class="col-md-10">
            <h1 class="titulo-detalhes">Lista de Inscritos</h1>
        </div>
        <div class="col-md-2 ">
            <a href="{{route('atividades.exportar', ['id'=>$atividade->id])}}" class="btn btn-primary float-md-right">Exportar .csv</a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <table class="table table-hover table-responsive-lg table-sm" style="position: relative; top: 25px; border-top: none">
                <thead>
                <th>Nome</th>
                <th>Email</th>
                <th>Celular</th>
                <th>CPF</th>
                <th>Instituição</th>
                </thead>

                @foreach($inscritos as $inscrito)
                    <tbody>
                    <td >{{$inscrito->name}}</td>
                    <td >{{$inscrito->email}}</td>
                    <td >{{$inscrito->celular}}</td>
                    <td >{{$inscrito->cpf}}</td>
                    <td >{{$inscrito->instituicao}}</td>
                    </tbody>
                @endforeach
            </table>
        </div>
    </div>
</div>
@endsection
