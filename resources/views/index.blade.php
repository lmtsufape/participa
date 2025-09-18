@php use Illuminate\Support\Facades\Storage; @endphp
@extends('layouts.app')

@section('css')
    <!-- Flickity CSS -->
    <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css" />

    <!-- CSS Personalizado do Carrossel -->
    <link rel="stylesheet" href="{{ asset('css/home-carousel.css') }}" />
@endsection

@section('content')
    @if($eventos_destaques->isNotEmpty())
        <div class="container d-flex flex-column pb-5">
            <div class="d-flex align-items-center mb-3 position-relative">
                <h2 class="text-my-primary position-absolute start-50 translate-middle-x" style="white-space: nowrap;">
                    {{ __('Eventos com inscrições abertas ou em realização') }}
                </h2>
            </div>

            <div class="container d-flex flex-column pb-5">
                <div class="d-flex align-items-center mb-3 position-relative">
                    <a href="{{ route('eventos.proximos') }}"
                    class="btn btn-my-outline-primary rounded-5 ms-auto">
                        {{ __('Ver todos') }}
                    </a>
                </div>
            </div>

            <!-- carrossel Flickity -->
            <div
                class="main-carousel js-flickity"
                id="CarouselHome"
                data-flickity='{
                    "cellAlign": "center",
                    "contain": true,
                    "pageDots": true,
                    "prevNextButtons": true,
                    "groupCells": false,
                    "wrapAround": true
                }'
            >
                @foreach ($eventos_destaques->take(7) as $evento)
                    @php

                        $inicio = \Carbon\Carbon::parse($evento->dataInicio);
                        $fim    = \Carbon\Carbon::parse($evento->dataFim);
                        $idioma = Session::get('idiomaAtual', 'pt');
                        $mesIgual = $inicio->isSameMonth($fim);
                        switch ($idioma) {
                            case 'en':
                                $suf = 'to'; $fmt1 = 'd F'; $fmt2 = 'd F Y'; break;
                            case 'es':
                                $suf = 'hasta'; $fmt1 = 'd \\d\\e F'; $fmt2 = 'd \\d\\e F \\d\\e Y'; break;
                            default:
                                $suf = 'a';
                                if ($mesIgual) { $fmt1 = 'd'; $fmt2 = 'd \\d\\e F \\d\\e Y'; }
                                else          { $fmt1 = 'd \\d\\e F'; $fmt2 = 'd \\d\\e F \\d\\e Y'; }
                                break;
                        }
                    @endphp

                    <div class="carousel-cell-images">
                        @php

                            $imgUrl = $evento->fotoEvento
                                ? Storage::url($evento->fotoEvento)
                                : asset('img/fundo-vagalumes.png');
                        @endphp

                        <div class="img-wrapper">
                            <a href="{{ route('evento.visualizar', $evento->id) }}">
                                <img
                                    src="{{ $imgUrl }}"
                                    alt="{{ $evento->nome }}"
                                    class="img-fixed"
                                >
                            </a>
                        </div>

                        <div class="card-text mt-2">
                            <h5 class="tit-carrossel-home text-dark">
                                {{ $evento->nome }}
                            </h5>
                            <p class="sub-carrossel-home mb-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                     fill="currentColor" class="bi bi-calendar-event"
                                     viewBox="0 0 16 16">
                                    <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z"/>
                                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0
                            1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0
                            1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5
                            0 0 1 .5-.5M1 4v10a1 1 0 0
                            0 1 1h12a1 1 0 0 0 1-1V4z"/>
                                </svg>
                                {{ $inicio->translatedFormat($fmt1) }}
                                {{ $suf }}
                                {{ $fim->translatedFormat($fmt2) }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if($eventos_passados->isNotEmpty())
        <div class="container py-5">
            @include('components.carrossel', [
                'eventos'     => $eventos_passados,
                'titulo'      => __('Últimos eventos realizados'),
                'id'          => 'eventosRealizados',
                'urlVerTodos' => route('eventos.passados'),
            ])
        </div>
    @endif
@endsection

    <script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
    <script>

    const slideCount = {{ $eventos_destaques->count() }};
        new Swiper('.mySwiper', {
            loop: slideCount > 1,
            slidesPerView: 1,
            slidesPerGroup: 1,
            observer: true,
            observeParents: true,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
        });
    </script>
