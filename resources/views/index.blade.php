@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="/css/home/home.css">
@endsection

@section('content')
    <section class="home-section d-flex align-items-center justify-content-start flex-column">

        <div class="container-fluid">
            <h1 class="text-white mt-5 mb-5 text-center">
                Proximos Eventos
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
            <h1 class="text-white mt-5 mb-5 text-center">
                Eventos Passados
            </h1>

            <div id="carouselEventosPassados"
                class="carousel mb-3 slide box-carrousel-primary d-flex align-items-center justify-content-center "
                data-ride="carousel">

                <div class="carousel-inner  carrousel-container-cards d-flex align-items-center justify-content-start">


                    @if (count($eventosPassados) > 0)
                        @forelse ($eventosPassados as $i => $eventoPassado)

                            <div class="carousel-item  @if ($i == 0) active @endif shadow-lg w-100">

                                <div class="carrousel-item-box-image">

                                    @if ($eventoPassado->fotoEvento != null)
                                        <img src="{{ asset('storage/' . $eventoPassado->fotoEvento) }}" alt="imagem evento"
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

                                        <a href="{{ route('evento.visualizar', ['id' => $eventoPassado->id]) }}">
                                            {{ $eventoPassado->nome }}
                                        </a>
                                    </div>

                                    <div class="carrousel-item-box-descricao">
                                        <div class="box-descricao">
                                            @if (strlen($eventoPassado->descricao) > 621)
                                                <div class="text-limit">
                                                    {!! $eventoPassado->descricao !!}
                                                </div>

                                                <br>

                                                <a class="link-modal" data-toggle="modal"
                                                    data-target="#modal{{ $eventoPassado->id }}">
                                                    Saiba mais
                                                </a>
                                            @else
                                                {!! $eventoPassado->descricao !!}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @endforeach
                    @endif


                    <!--botoes do slide eventos passados -->
                    @if (count($eventosPassados) > 1)
                        <a class="carousel-control-prev" href="#carouselEventosPassados" role="button" data-slide="prev">

                            <span class="button-carrousel d-flex align-items-center justify-content-center">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </span>

                        </a>

                        <a class="carousel-control-next" href="#carouselEventosPassados" role="button" data-slide="next">
                            <span class="button-carrousel d-flex align-items-center justify-content-center">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </span>
                        </a>
                    @endif

                </div>
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



@endsection
