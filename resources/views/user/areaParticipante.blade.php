@extends('layouts.app')

@section('content')

<div class="container position-relative">

    {{-- titulo da página --}}
    <div class="row justify-content-center titulo-detalhes">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-10">
                    <h1>Meus Eventos - Participante</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        @if($eventos->count() != 0)
        @foreach ($eventos as $evento)
        <div class="card" style="width: 18rem;">
            @if(isset($evento->fotoEvento))

            @php
                $bannerPath = $evento->is_multilingual && Session::get('idiomaAtual') === 'en' && $evento->fotoEvento_en ? $evento->fotoEvento_en : $evento->fotoEvento;
            @endphp

            <img src="{{ asset('storage/' . $bannerPath) }}" class="card-img-top" alt="...">
            @else
            <img src="{{asset('img/colorscheme.png')}}" class="card-img-top" alt="...">
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
                    <strong>Realização:</strong> {{date('d/m/Y',strtotime($evento->dataInicio))}} - {{date('d/m/Y',strtotime($evento->dataFim))}}<br>

                </p>
                <p>
                    <a href="{{  route('evento.visualizar',['id'=>$evento->id])  }}" class="visualizarEvento">Visualizar Evento</a>
                </p>
            </div>

        </div>
        @endforeach
        @else
        <div class="card">
            <div class="card-body">
                <p class="card-text" >Você ainda não participou de nenhum evento.</p>
            </div>
        </div>
        @endif
    </div>

</div>

@endsection
