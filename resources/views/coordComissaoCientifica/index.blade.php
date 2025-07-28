@extends('layouts.app')

@section('content')

<div class="container position-relative">

    {{-- titulo da página --}}
    <div class="row justify-content-center titulo-detalhes">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-10">
                    <h1>{{ __('Meus eventos - Coordenação comissão científica') }}</h1>
                </div>
                <div class="col-sm-2">
                    <a href="{{route('evento.criar')}}" class="btn btn-primary">{{ __('Novo evento') }}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row cards-eventos-index">
        @if($eventos->count() != 0)
        @foreach ($eventos as $evento)
            @if ($evento->deletado == false)
                @can('isPublishOrIsCoordenadorOrCoordenadorDasComissoes', $evento)
                <div class="card" style="width: 16rem;">
                    @if(isset($evento->fotoEvento))
                        <img class="img-card" src="{{asset('storage/'.$evento->fotoEvento)}}" class="card-img-top" alt="...">
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
                                        <i class="far fa-eye" style="color: black"></i>&nbsp;&nbsp;{{ __('Visualizar evento') }}
                                    </a>
                                </div>
                                <div>
                                    <a href="{{ route('coord.detalhesEvento', ['eventoId' => $evento->id]) }}">
                                        <i class="fas fa-cog" style="color: black"></i>&nbsp;&nbsp;{{ __('Configurar evento') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                @endcan
            @endif
        @endforeach
        @else
        <div class="d-flex justify-content-center align-items-center flex-column py-5">
            <div class="d-flex justify-content-center align-items-center flex-column card text-center shadow-sm border-0 p-4" style="max-width: 600px;">
                <img src="{{asset('img/iconeCalendario.png')}}" style="width: 100px; margin-bottom: 10px;"/>
                <h2 class="fw-semibold text-dark mb-3">{{ __('Você ainda não participou ou criou nenhum evento.') }}</h2>
                <p class="text-muted mb-0">{{ __('Explore os eventos disponíveis na plataforma, inscreva-se ou crie seu próprio evento para começar a interagir.') }}</p>
            </div>
        </div>
        @endif
    </div>

</div>

@endsection
