@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="/css/home/home.css">
@endsection

@section('content')

    <div class="container d-flex flex-column pb-5">
        <div class="d-flex align-items-center">
            <h2 class="mx-auto">Em destaque neste momento</h2>
            <button class="btn border rounded-pill ">Ver todos</button>
        </div>
        <div id="carouselPrincipalCaptions" class="carousel carousel-dark slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                @foreach ($eventos_destaques as $index => $evento)
                    <button type="button" data-bs-target="#carouselPrincipalCaptions"
                        data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}"
                        aria-current="{{ $index == 0 ? 'true' : '' }}" aria-label="Slide {{ $index + 1 }}">
                    </button>
                @endforeach
            </div>

            <div class="carousel-inner">
                @foreach ($eventos_destaques as $index => $evento)
                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                        <img src="{{ Storage::url($evento->fotoEvento) }}" class="d-block w-100 rounded"
                            alt="Foto do evento">
                        <div class="carousel-caption d-none d-md-block">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h1>{{ $evento->nome }}</h1>

                                    <p class="text-danger"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-event" viewBox="0 0 16 16">
                                        <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z"/>
                                        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/>
                                        </svg> {{ \Carbon\Carbon::parse($evento->dataFim)->format('l, d F') }}</p>

                                </div>
                                <div>
                                    <a class="btn border rounded-3">Saiba mais</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#carouselPrincipalCaptions"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselPrincipalCaptions"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
    <div class="container py-5">
        @include('components.carrossel', ['eventos' => $eventos_destaques, 'titulo' => 'Vistos recentemente', 'id' => 'vistosRecentimente'])

    </div>
    <div class="container py-5">
        @include('components.carrossel', ['eventos' => $eventos_passados, 'titulo' => 'Últimos eventos realizados', 'id' => 'eventosRealizados'])

    </div>

    <script src="/js/home.js"></script>

@endsection
