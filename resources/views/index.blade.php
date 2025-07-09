@php use Illuminate\Support\Facades\Storage; @endphp
@extends('layouts.app')

@section('css')
    <!-- Flickity CSS -->
    <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css" />

    <style>
        /* container do carrossel */
        .main-carousel {
            overflow: visible;    /* para que as setas fiquem para fora */
        }


        .carousel-cell-images {
            width: 60%;
            margin-right: 2%;
            display: flex;
            flex-direction: column;
        }


        .carousel-cell-images {
            width: 60%;
            margin-right: 2%;
            display: flex;
            flex-direction: column;
        }
        @media (max-width: 768px) {
            .carousel-cell-images {
                width: 100%; /* Em telas menores, o card ocupa a largura total */
                margin-right: 0;
            }

        }

        .carousel-cell-images .img-wrapper {
            width: 100%; /* Garante que o wrapper ocupe toda a largura do card */
            height: 320px; /* Altura fixa do card */
            overflow: hidden; /* Corta qualquer excesso */
            border-radius: 8px;
            background: #f0f0f0; /* Cor de fundo enquanto carrega */
            position: relative;
        }

        .carousel-cell-images .img-wrapper .img-fixed {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover; /* Preenche o espaço, cortando o excesso */
            /* Ou use object-fit: contain; para mostrar a imagem inteira */
            object-position: center; /* Centraliza a imagem */
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
        }

        .carousel-cell-images .card-text {
            margin-top: 0.75rem;
        }
        /* título e legenda */
        .tit-carrossel-home {
            font-size: 1.25rem;
            font-weight: 600;
            color: #000;          /* título sempre visível */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .sub-carrossel-home {
            font-size: 0.9rem;
            color: #555;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        /* customização das setas */
        .flickity-prev-next-button {
            width: 3rem;
            height: 3rem;
            background: #fff;
            opacity: 0.9;
            border-radius: 50%;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        .flickity-prev-next-button:hover {
            background: #fff;
            opacity: 1;
        }
        .flickity-prev-next-button.previous { left: -1.5rem; }
        .flickity-prev-next-button.next     { right: -1.5rem; }
        .flickity-button-icon { fill: #333; }

        /* paginação (bolinhas) */
        .flickity-page-dots {
            bottom: -2rem;
        }
        .flickity-page-dots .dot {
            width: 0.75rem;
            height: 0.75rem;
            margin: 0 0.25rem;
            opacity: 0.5;
            background: #888;
        }
        .flickity-page-dots .dot.is-selected {
            background: #337ab7;
            opacity: 1;
        }
    </style>
@endsection

@section('content')
    @if($eventos_destaques->isNotEmpty())
        <div class="container d-flex flex-column pb-5">
            <div class="d-flex align-items-center mb-3 position-relative">
                <h2 class="text-my-primary position-absolute start-50 translate-middle-x" style="white-space: nowrap;">
                    {{ __('Eventos com inscrições abertas ou em realização') }}
                </h2>
                <a href="{{ route('eventos.proximos') }}"
                   class="btn btn-my-outline-primary rounded-5 ms-auto">
                    {{ __('Ver todos') }}
                </a>
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
@endsection
