@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="/css/home/home.css">
@endsection

@section('content')
    <section class="home-section d-flex align-items-center justify-content-start flex-column">

        <div class="container-fluid">

            <h1 class="text-white mt-5 mb-4 text-center">
                Próximos Eventos
            </h1>

            <div id="carousel-primary"
                class="carousel slide box-carrousel-primary d-flex align-items-center justify-content-center "
                data-ride="carousel">

                <div class="carousel-inner carrousel-container-cards d-flex align-items-center justify-content-start">


                    @if (count($proximosEventos) > 0)
                        @forelse ($proximosEventos as $i => $ProximoEvento)

                            <div class="carousel-item  @if ($i == 0) active @endif shadow-lg w-100">

                                <div class="carrousel-item-box-image">

                                    @if ($ProximoEvento->fotoEvento != null)
                                        <img src="{{ asset('storage/' . $ProximoEvento->fotoEvento) }}" alt="imagem evento"
                                            width="100%" height="100%">
                                    @else
                                        <img src="{{ asset('img/colorscheme.png') }}" alt="" width="100%"
                                            height="100%">
                                    @endif

                                </div>

                                <div
                                    class="carrousel-item-box-content d-flex align-items-center justify-content-center flex-column">

                                    <div
                                        class="titulo-evento carrousel-item-box-titulo d-flex align-items-center justify-content-center">

                                        <a href="{{ route('evento.visualizar', ['id' => $ProximoEvento->id]) }}">
                                            {{ $ProximoEvento->nome }}
                                        </a>
                                    </div>

                                    <div class="carrousel-item-box-descricao">
                                        <div class="box-descricao">
                                            @if (strlen($ProximoEvento->descricao) > 621)
                                                <div class="text-limit">
                                                    {!! $ProximoEvento->descricao !!}
                                                </div>

                                                <br>

                                                <a class="link-modal" data-toggle="modal"
                                                    data-target="#modal{{ $ProximoEvento->id }}">
                                                    Saiba mais
                                                </a>
                                            @else
                                                {!! $ProximoEvento->descricao !!}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @endforeach


                        <!--botoes do primeiro slide -->
                        <a class="carousel-control-prev" href="#carousel-primary" role="button" data-slide="prev">

                            <span class="button-carrousel d-flex align-items-center justify-content-center">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </span>

                        </a>

                        <a class="carousel-control-next" href="#carousel-primary" role="button" data-slide="next">
                            <span class="button-carrousel d-flex align-items-center justify-content-center">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </span>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="container-fluid">


            <!--carrousel eventos passados -->
            <a href="{{ Route('eventos.passados') }}">
                <h1 class="text-white mt-5 mb-5 text-center">
                    Últimos eventos realizados
                </h1>
            </a>


            <div class="container-geral-slider-cards">
                <input id="quantEventos" type="text" hidden value="{{ count($eventosPassados) + 1 }}">
                <!--input hidden informando quantidade de slides -->

                @if (count($eventosPassados) > 3)
                    <div class="button-slidecards">
                        <span id="prevSlideCards" class="button-carrousel d-flex align-items-center justify-content-center">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        </span>
                    </div>
                @endif

                <div class="container-slider-cards">
                    <div id="listCardSlides" class="list-card-slides">
                        @foreach ($eventosPassados as $eventoPassado)
                            <div class="card-slide shadow-lg">
                                <div class="icone-box">
                                    @if ($eventoPassado->icone != null)
                                        <img src="{{ asset('storage/' . $eventoPassado->icone) }}" alt="imagem evento"
                                            width="100%" height="100%">
                                    @elseif($eventoPassado->fotoEvento != null)
                                        <img src="{{ asset('storage/' . $eventoPassado->fotoEvento) }}" alt="imagem evento"
                                            width="100%" height="100%">
                                    @else
                                        <img src="{{ asset('img/colorscheme.png') }}" alt="" width="100%"
                                            height="100%">
                                    @endif

                                </div>
                                <div class="card-content">
                                    <div class="tittle-card titulo-evento d-flex align-items-center justify-content-center">
                                        <a href="{{ route('evento.visualizar', ['id' => $eventoPassado->id]) }}">
                                            {{ $eventoPassado->nome }}
                                        </a>
                                    </div>
                                    <div class="card-content-desc">
                                        <div class="text-limit-card-slide">
                                            {!! $eventoPassado->descricao !!}
                                        </div>

                                        <a class="link-modal" data-toggle="modal"
                                            data-target="#modal{{ $eventoPassado->id }}">
                                            Saiba mais
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <!--Card link para a view de eventos passados -->

                        <div class="card-slide shadow-lg card-link">
                            <div class="header-card-link w-100 border ">
                                <img class="img-card-link" src="/img/more_icon.svg" alt="">
                            </div>
                            <div class="content-card-link w-100">
                                <a href="{{ Route('eventos.passados') }}">
                                    <button class="button-card-link">
                                        Visualizar eventos passados
                                    </button>
                                </a>
                            </div>

                        </div>

                    </div>
                </div>

                @if (count($eventosPassados) > 3)
                    <div class="button-slidecards">
                        <span id="nextSlideCards" class="button-carrousel d-flex align-items-center justify-content-center">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        </span>
                    </div>
                @endif
            </div>

        </div>
    </section>


    <!--Modal primeiro slide-->
    @foreach ($proximosEventos as $ProximoEvento)
        <div class="modal fade" id="modal{{ $ProximoEvento->id }}" role="dialog" aria-label="true">
            <div class="modal-dialog modal-lg ">

                <div class="modal-content">

                    <div class="modal-header titulo-evento d-flex align-items-center justify-content-between">
                        {{ $ProximoEvento->nome }}

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="close" aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        {!! $ProximoEvento->descricao !!}
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!--modal eventos passados-->
    @foreach ($eventosPassados as $eventoPassado)
        <div class="modal fade" id="modal{{ $eventoPassado->id }}" role="dialog" aria-label="true">
            <div class="modal-dialog modal-lg ">

                <div class="modal-content">

                    <div class="modal-header titulo-evento d-flex align-items-center justify-content-between">
                        {{ $eventoPassado->nome }}

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="close" aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        {!! $eventoPassado->descricao !!}
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <script src="/js/home.js"></script>

@endsection
