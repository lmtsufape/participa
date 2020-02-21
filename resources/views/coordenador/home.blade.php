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
                    <div class="row">
                        <div class="col-sm-12">
                            <h4 class="card-title">
                                <div class="row justify-content-center">
                                    <div class="col-sm-12">
                                        {{$evento->nome}}
                                        @can('isCoordenador', $evento)
                                            <div class="btn-group dropright dropdown-options">
                                                <a id="options" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    {{-- <img src="{{asset('img/icons/ellipsis-v-solid.svg')}}" style="width:8px"> --}}
                                                </a>
                                                <div class="dropdown-menu">
                                                    <a href="{{ route('coord.detalhesEvento', ['eventoId' => $evento->id]) }}" class="dropdown-item">
                                                        <img src="{{asset('img/icons/eye-regular.svg')}}" class="icon-card" alt="">
                                                        Detalhes
                                                    </a>
                                                    <a href="{{route('evento.editar',$evento->id)}}" class="dropdown-item">
                                                        <img src="{{asset('img/icons/edit-regular.svg')}}" class="icon-card" alt="">
                                                        Editar
                                                    </a>
                                                    <form method="POST" action="{{route('evento.deletar',$evento->id)}}">
                                                        {{ csrf_field() }}
                                                        {{ method_field('DELETE') }}
                                                        <button type="submit" class="dropdown-item">
                                                            <img src="{{asset('img/icons/trash-alt-regular.svg')}}" class="icon-card" alt="">
                                                            Deletar
                                                        </button>
                                                        
                                                    </form>
                                                </div>
                                            </div>
                                        @endcan
                                    </div>    
                                    
                                </div>
                            
                            </h4>
                            
                        </div>
                    </div>
                    <p class="card-text">
                        <strong>Início:</strong> {{$evento->dataInicio}}<br>
                        <strong>Fim:</strong> {{$evento->dataFim}}<br>
                        <strong>Número de Vagas:</strong> {{$evento->numeroParticipantes}}
                    </p>

                    
                      <div class="row justify-content-center">
                          <div class="col-sm-4">
                              <div class="row justify-content-center">
                                  
                              </div>
                          </div>
                          <div class="col-sm-4">
                              <div class="row justify-content-center">
                                  

                              </div>
                          </div>
                          <div class="col-sm-4" style="padding:0">
                              
                          </div>
                      </div>
                    
                    
                </div>
                
            </div>
        @endforeach
    </div>

</div>

@endsection
