@extends('coordenador.detalhesEvento')

@section('menu')

<div id="" style="display: block">
    <div class="row">
        <div class="col-md-12">
            <h1 class="titulo-detalhes">Listar Inscritos</h1>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-body">
                  <div class="row justify-content-between">
                    <div class="col-md-6">
                      <h5 class="card-title">Inscrições</h5>
                      <h6 class="card-subtitle mb-2 text-muted">Inscritos no evento {{$evento->nome}}</h6>
                      <h6 class="card-subtitle mb-2 text-muted">Obs.: ao exportar o arquivo csv, usar o delimitador , (vírgula) para abrir o arquivo</h6>
                    </div>
                    <div class="col-md-6 d-flex justify-content-sm-start justify-content-md-end align-items-center">
                        <a href="{{route('evento.downloadInscritos', $evento)}}" class="btn btn-primary float-md-right">Exportar .csv</a>
                    </div>

                  </div>
                  <p class="card-text">
                    <table class="table table-hover table-responsive-lg table-sm" style="position: relative;">
                        <thead>
                            <th>
                                @if ($evento->subeventos->count() > 0)
                                    <th>Evento/Subevento</th>
                                @endif
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Cidade</th>
                                <th>Estado</th>
                            </th>
                        </thead>
                        @foreach ($inscricoes as $inscricao)
                            <tbody>
                                <th>
                                    @if ($evento->subeventos->count() > 0)
                                        <td>{{$inscricao->evento->nome}}</td>
                                    @endif
                                    <td>{{$inscricao->user->name}}</td>
                                    <td>{{$inscricao->user->email}}</td>
                                    <td>{{$inscricao->user->endereco->cidade}}</td>
                                    <td>{{$inscricao->user->endereco->uf}}</td>
                                </th>
                            </tbody>
                        @endforeach
                    </table>
                  </p>
                </div>
              </div>
        </div>
    </div>
</div>
@endsection
