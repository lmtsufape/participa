@extends('layouts.app')

@section('content')

<div class="container">

    {{-- titulo da página --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h1>{{__('Meus Eventos')}}</h1>
                </div>
                <div>
                    <a href="{{route('evento.criar')}}" class="btn btn-primary">{{__('Novo Evento')}}</a>
                </div>

    </div>
    <div class="row">
        @foreach ($eventos as $evento)
            @if ($evento->deletado == false)
                @can('isCoordenadorOrCoordenadorDasComissoes', $evento)
                    <div class="col-md-4">
                        <div class="card">
                            @if($evento->is_multilingual && Session::get('idiomaAtual') === 'en' && isset($evento->fotoEvento_en))
                                <img src="{{asset('storage/'.$evento->fotoEvento_en)}}" class="card-img-top" alt="...">
                            @elseif(isset($evento->fotoEvento))
                                <img src="{{asset('storage/'.$evento->fotoEvento)}}" class="card-img-top" alt="...">
                            @else
                                <img src="{{asset('img/colorscheme.png')}}" class="card-img-top" alt="..." style="height: 150px;">
                            @endif
                            <div class="card-body">
                                <div class="card-title text-justify">
                                    <h3>
                                        @if($evento->is_multilingual && Session::get('idiomaAtual') === 'en')
                                            {{$evento->nome_en}}
                                        @else
                                            {{$evento->nome}}
                                        @endif
                                    </h3>
                                    <p>
                                        @if($evento->is_multilingual && Session::get('idiomaAtual') === 'en')
                                            {{$evento->descricao_en}}
                                        @else
                                            {{$evento->descricao}}
                                        @endif
                                    </p>

                                </div>
                                <div class="card-text">
                                    <p class="text-danger"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-event" viewBox="0 0 16 16">
                                        <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z"/>
                                        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/>
                                      </svg> {{ \Carbon\Carbon::parse($evento->dataFim)->format('l, d F') }}</p>
                                    <p>
                                        <div class="row justify-content-center">
                                            <div class="col-sm-12">
                                                <img src="{{ asset('/img/icons/location_pointer.png') }}" alt="" width="18px" height="auto">
                                                {{$evento->endereco->rua}}, {{$evento->endereco->numero}} - {{$evento->endereco->cidade}} / {{$evento->endereco->uf}}.
                                            </div>
                                        </div>
                                    </p>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <a class="d-flex flex-column align-items-center text-decoration-none" href="{{route('evento.visualizar',['id'=>$evento->id])}}">
                                                <i class="far fa-eye" style="color: #008E3B"></i>
                                                <span style="font-size: 10px"><strong>{{__('Visualizar evento')}}</strong></span>
                                            </a>
                                        </div>
                                        <div class="col-md-4">
                                            <a class="d-flex flex-column align-items-center text-decoration-none" href="{{ route('coord.detalhesEvento', ['eventoId' => $evento->id]) }}">
                                                <i class="fas fa-cog" style="color: #727272"></i>
                                                <span style="font-size: 10px"><strong>{{__('Configurar evento')}}</strong></span>
                                            </a>
                                        </div>
                                        @can('isCriador', $evento)
                                        <div class="col-md-4">
                                            <a class="d-flex flex-column align-items-center text-decoration-none" href="#" data-toggle="modal" data-target="#modalExcluirEvento{{$evento->id}}">
                                                <i class="far fa-trash-alt" style="color: #FF0000"></i>
                                                <span style="font-size: 10px"><strong>{{__('Deletar evento')}}</strong></span>
                                            </a>
                                        </div>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan
            @endif
        @endforeach
    </div>
    @foreach ($eventos as $evento)
        @if ($evento->deletado == false)
            <!-- Modal de exclusão do evento -->
            <x-modal-excluir-evento :evento="$evento"/>
            <!-- fim do modal -->
        @endif
    @endforeach

</div>

@endsection
