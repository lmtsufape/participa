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
    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-12">
            <table class="table table-hover table-responsive-lg table-sm" style="position: relative; top: 25px; border-top: none">
                <thead>
                <th>Nome</th>
                <th>Email</th>
                <th>Celular</th>
                <th>CPF</th>
                <th>Instituição</th>
                <th width="15%">Inscrição</th>
                </thead>

                @foreach($inscritos as $inscrito)
                    <tbody>
                    <td >{{$inscrito->name}}</td>
                    <td >{{$inscrito->email}}</td>
                    <td >{{$inscrito->celular}}</td>
                    <td >{{$inscrito->cpf}}</td>
                    <td>{{$inscrito->instituicao}}</td>
                    <td><a type="button" class="btn btn-primary"
                           href="{{route('atividades.cancelarInscricao', ['id'=>$atividade->id, 'user'=>$inscrito->id])}}"
                           onclick="return confirm('Tem certeza que deseja cancelar a inscrição de {{$inscrito->name}}?')"
                        >Cancelar Inscrição</a></td>
                    </tbody>
                @endforeach
            </table>
        </div>
    </div>
</div>
@endsection
