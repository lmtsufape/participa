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
                    
                    
                    <a href="" class="btn btn-primary">Ver Detalhes</a>
                </div>
            </div>
        @endforeach
    </div>

</div>

@endsection