@extends('layouts.app')

@section('content')

<div class="container">
    
    {{-- titulo da página --}}
    <div class="row titulo">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-10">
                    <h1>Eventos Atuais</h1>
                </div>
                <div class="col-sm-2">
                    <a href="{{route('evento.criar')}}" class="btn btn-primary">Novo Evento</a>
                </div>
            </div>
        </div>
    </div>

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
                      <div class="row justify-content-center">
                          <div class="col-sm-4">
                              <div class="row justify-content-center">
                                  <a href="{{ route('coord.detalhesEvento', ['eventoId' => $evento->id]) }}" class="btn btn-primary">Detalhes</a>
                              </div>
                          </div>
                          <div class="col-sm-4">
                              <div class="row justify-content-center">
                                  <a href="{{route('evento.editar',$evento->id)}}" class="btn btn-secondary">Editar</a>

                              </div>
                          </div>
                          <div class="col-sm-4" style="padding:0">
                              <form method="POST" action="{{route('evento.deletar',$evento->id)}}">
                                  {{ csrf_field() }}
                                  {{ method_field('DELETE') }}
                                  <div class="row justify-content-center">
                                      <button type="submit" class="btn btn-danger">Deletar</button>

                                  </div>
                              </form>
                          </div>
                      </div>
                    @endcan
                    
                </div>
                
            </div>
        @endforeach
    </div>

</div>

@endsection
