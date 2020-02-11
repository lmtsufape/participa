@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row titulo">
        <h1>Eventos Atuais</h1>
    </div>
    <a href="{{route('evento.criar')}}" class="btn btn-primary">Novo Evento</a>

    <div class="row">


        @foreach ($eventos as $evento)
            <div class="card" style="width: 18rem;">
                <img src="{{asset('img/colorscheme.png')}}" class="card-img-top" alt="...">
                <div class="card-body">
                    <h4 class="card-title">{{$evento->nome}}</h4>
                    <p class="card-text">
                        <strong>Início:</strong> {{$evento->dataInicio}}<br>
                        <strong>Fim:</strong> {{$evento->dataFim}}<br>
                        <strong>Número de Vagas:</strong> {{$evento->numeroParticipantes}}
                    </p>

                    @can('isCoordenador', $evento)
                      <div class="row">
                          <div class="col-sm-7">
                              <a href="{{ route('coord.detalhesEvento', ['eventoId' => $evento->id]) }}" class="btn btn-primary">Ver Detalhes</a>
                          </div>
                      </div>
                    @endcan
                    <div class="row">
                        <div class="col-sm-4">
                            <a href="{{route('evento.editar',$evento->id)}}" class="btn btn-secondary">Editar</a>
                        </div>
                        <div class="col-sm-4">
                            <form method="POST" action="{{route('evento.deletar',$evento->id)}}">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button type="submit" class="btn btn-danger">Deletar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>

@endsection
