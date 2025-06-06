@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="/css/home/home.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <style>
        .swiper {
            width: 100% !important;
            max-width: 100%;
            max-height: 100%;
            overflow: hidden;
            position: relative;
        }
        .swiper-wrapper {
            display: flex;
            width: 100% !important;
        }
        .swiper-slide {
            flex: 0 0 100% !important;
            width: 100% !important;
            height: 100%;
            position: relative;
        }
        .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }
        .swiper-button-next,
        .swiper-button-prev {
            color: #000;
        }
        .carousel-caption {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 1rem 1.5rem;
            background: linear-gradient(to top, rgba(0,0,0,0.9), transparent);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
        }
        .carousel-caption h1 {
            font-size: 1.5rem;
            color: #fff;
            margin: 0;
        }
        .carousel-caption .caption-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: .5rem;
            width: 100%;
        }
        .carousel-caption .info {
            display: flex;
            align-items: center;
            gap: .5rem;
            font-size: .9rem;
            color: #ddd;
        }
        .carousel-caption .info svg {
            flex-shrink: 0;
        }
        .carousel-caption .btn {
            flex-shrink: 0;
        }
        /* ------------------------- */
        /* ajuste de altura para tablets */
        @media (max-width: 992px) {
            .swiper {
                height: 300px;
            }
        }

        /* ajuste de altura para telas menores */
        @media (max-width: 768px) {
            .swiper {
                height: 240px;
            }
        }

        /* ajuste de altura para mobile */
        @media (max-width: 576px) {
            .swiper {
                height: 180px;
            }
        }

    </style>
@endsection

@section('content')
    @if($eventos_destaques->isNotEmpty())
        <div class="container d-flex flex-column pb-5">
            <div class="container d-flex align-items-center mb-3 position-relative">
                <h2 class="text-my-primary position-absolute start-50 translate-middle-x">
                    {{ __('Em destaque neste momento') }}
                </h2>
                <a href="{{ route('eventos.proximos') }}"
                class="btn btn-my-outline-primary rounded-5 ms-auto">
                    {{ __('Ver todos') }}
                </a>
            </div>

            <!-- Swiper -->
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    @foreach ($eventos_destaques->take(5) as $evento)
                        <div class="swiper-slide">
                            <img src="{{ Storage::url($evento->fotoEvento) }}" alt="Foto do evento">
                            <div class="carousel-caption">
                                <a href="{{ route('evento.visualizar', ['id' => $evento->id]) }}">
                                    <h1 class="text-start mb-4">{{ __('13º Congresso Brasileiro de Agroecologia: inscrições e submissões de trabalhos') }}</h1>
                                </a>
                                <div class="caption-row">
                                    <p class="info mb-2">

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
                                         <?php


                                            $dataInicio = \Carbon\Carbon::parse($evento->dataInicio);
                                            $dataFim = \Carbon\Carbon::parse($evento->dataFim);

                                            $idioma = Session::get('idiomaAtual', 'pt');
                                            $mesIgual = $dataInicio->isSameMonth($dataFim);
                                            switch ($idioma) {
                                                case 'en':
                                                    $suffixo      = 'to';
                                                    $startFormat = 'd F';
                                                    $endFormat   = 'd F Y';
                                                    break;
                                                case 'es':
                                                    $suffixo     = 'hasta';
                                                    $startFormat = 'd \\d\\e F';
                                                    $endFormat   = 'd \\d\\e F \\d\\e Y';
                                                    break;
                                                default:
                                                    $suffixo = 'a';
                                                    if ($mesIgual) {
                                                        $startFormat = 'd';
                                                        $endFormat   = 'd \\d\\e F \\d\\e Y';
                                                    } else {
                                                        $startFormat = 'd \\d\\e F';
                                                        $endFormat   = 'd \\d\\e F \\d\\e Y';
                                                    }
                                                    break;
                                            }
                                        ?>
                                        {{ $dataInicio->translatedFormat($startFormat) }}
                                        {{ $suffixo }}
                                        {{ $dataFim->translatedFormat($endFormat) }}
                                    </p>
                                    <a href="{{ route('evento.visualizar', ['id' => $evento->id]) }}"
                                    class="btn btn-outline-light rounded-3">
                                        {{ __('Saiba mais') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <!-- Navegação do Swiper -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-pagination"></div>
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

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
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
