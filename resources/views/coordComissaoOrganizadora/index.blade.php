@extends('layouts.app')

@section('content')

<div class="container position-relative">


    <div class="row justify-content-center titulo-detalhes">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-10">
                    <h1>{{ __('Meus eventos - Coordenação comissão organizadora') }}</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        @if($eventos->count() != 0)
        @foreach ($eventos as $evento)
        <div class="card" style="width: 18rem;">
            @if($evento->is_multilingual && Session::get('idiomaAtual') === 'en' && isset($evento->fotoEvento_en))
                <img src="{{asset('storage/'.$evento->fotoEvento_en)}}" class="card-img-top" alt="...">
            @elseif($evento->is_multilingual && Session::get('idiomaAtual') === 'es' && isset($evento->fotoEvento_es))
                <img src="{{asset('storage/'.$evento->fotoEvento_es)}}" class="card-img-top" alt="...">
            @elseif(isset($evento->fotoEvento))
                <img src="{{asset('storage/'.$evento->fotoEvento)}}" class="card-img-top" alt="...">
            @else
                <img src="{{asset('img/colorscheme.png')}}" class="card-img-top" alt="..." style="height: 150px;">
            @endif
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="card-title">
                            <div class="row justify-content-center">
                                <div class="col-sm-12">
                                    {{$evento->nome}}
                                </div>

                            </div>

                        </h4>

                    </div>
                </div>
                <p class="card-text">
                    <strong>{{ __('Realização:') }}</strong> {{date('d/m/Y',strtotime($evento->dataInicio))}} - {{date('d/m/Y',strtotime($evento->dataFim))}}<br>
                </p>
                <div class="row col-md-12">
                    <a href="{{route('evento.visualizar',['id'=>$evento->id])}}">
                        <i class="far fa-eye" style="color: black"></i>&nbsp;&nbsp;{{__('Visualizar evento')}}
                    </a>
                    <a href="{{ route('coord.detalhesEvento', ['eventoId' => $evento->id]) }}">
                        <i class="fas fa-cog" style="color: black"></i>&nbsp;&nbsp;{{__('Configurar evento')}}
                    </a>
                </div>
            </div>

        </div>
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
