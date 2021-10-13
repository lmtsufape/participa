@extends('layouts.app')

@section('content')

<div class="container"  style="position: relative; top: 80px;">

    {{-- titulo da página --}}
    <div class="row justify-content-center titulo-detalhes">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-10">
                    <h1>Meus Eventos</h1>
                </div>
                <div class="col-sm-2">
                    <a href="{{route('evento.criar')}}" class="btn btn-primary">Novo Evento</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row cards-eventos-index">

        @foreach ($eventos as $evento)
            @if ($evento->deletado == false)
                @can('isPublishOrIsCoordenador', $evento)
                <div class="card" style="width: 16rem;">
                    @if(isset($evento->fotoEvento))
                        <img class="img-card" src="{{asset('storage/eventos/'.$evento->id.'/logo.png')}}" class="card-img-top" alt="...">
                    @else
                        <img class="img-card" src="{{asset('img/colorscheme.png')}}" class="card-img-top" alt="...">
                    @endif
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <h5 class="card-title">
                                    <div class="row">
                                        <div class="col-sm-10">
                                            <a href="{{route('evento.visualizar',['id'=>$evento->id])}}" style="text-decoration: inherit;">
                                                {{$evento->nome}}
                                            </a>
                                        </div>
                                    </div>

                                </h5>
                            </div>
                        </div>
                        <div>
                            <p class="card-text">
                                <img src="{{ asset('/img/icons/calendar.png') }}" alt="" width="20px;" style="position: relative; top: -2px;"> {{date('d/m/Y',strtotime($evento->dataInicio))}} - {{date('d/m/Y',strtotime($evento->dataFim))}}<br>
                                {{-- <strong>Submissão:</strong> {{date('d/m/Y',strtotime($evento->inicioSubmissao))}} - {{date('d/m/Y',strtotime($evento->fimSubmissao))}}<br>
                                <strong>Revisão:</strong> {{date('d/m/Y',strtotime($evento->inicioRevisao))}} - {{date('d/m/Y',strtotime($evento->fimRevisao))}}<br> --}}
                            </p>
                            <p>
                                <div class="row justify-content-center">
                                    <div class="col-sm-12">
                                        <img src="{{ asset('/img/icons/location_pointer.png') }}" alt="" width="18px" height="auto">
                                        {{$evento->endereco->rua}}, {{$evento->endereco->numero}} - {{$evento->endereco->cidade}} / {{$evento->endereco->uf}}.
                                    </div>
                                </div>
                            </p>
                            <div>
                                <div>
                                    <a href="{{route('evento.visualizar',['id'=>$evento->id])}}">
                                        <i class="far fa-eye" style="color: black"></i>&nbsp;&nbsp;Visualizar evento
                                    </a>
                                </div>
                                <div>
                                    <a href="{{ route('coord.detalhesEvento', ['eventoId' => $evento->id]) }}">
                                        <i class="fas fa-cog" style="color: black"></i>&nbsp;&nbsp;Configurar evento
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                @endcan
            @endif
        @endforeach
    </div>

</div>

@endsection
