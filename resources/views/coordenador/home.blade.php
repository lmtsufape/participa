@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row titulo">        
        <h1>Eventos Atuais</h1>
    </div>
    <a href="{{route('evento.criar')}}" class="btn btn-primary">Novo Evento</a>

    <div class="row justify-content-center">

        @for ($i = 0; $i < 10; $i++)
            
            <div class="card" style="width: 18rem;">
                <img src="{{asset('img/colorscheme.png')}}" class="card-img-top" alt="...">
                <div class="card-body">
                    <h4 class="card-title">Nome do Evento</h4>
                    <p class="card-text">Data: 02/02/2020</p>
                    <p class="card-text">Status: Revisão</p>
                    
                    <a href="#" class="btn btn-primary">Ver Detalhes</a>
                </div>
            </div>

        @endfor
    </div>

</div>

@endsection