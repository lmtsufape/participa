<div class="d-flex align-items-center">
    <h2 class="mx-auto">{{$titulo}}</h2>
    <a href="{{route('eventos.proximos')}}" class="btn border rounded-pill">Ver todos</a>
</div>
<div id="carouselComponentCaptions" class="carousel carousel-dark" data-bs-ride="carousel">
    <div class="carousel-indicators">
        @foreach ($eventos_destaques as $index => $evento)
            <button type="button" data-bs-target="#carouselComponentCaptions"
                data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}"
                aria-current="{{ $index == 0 ? 'true' : '' }}" aria-label="Slide {{ $index + 1 }}">
            </button>
        @endforeach
    </div>

    <div class="carousel-inner">
        @foreach ($eventos_destaques->chunk(3) as $eventosChunk)
            <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                <div class="row px-5">
                    @foreach ($eventosChunk as $evento)
                        <div class="col-md-4">
                            <div class="card">
                                <img src="{{ Storage::url($evento->fotoEvento) }}" class="card-img-top" alt="Foto do evento">
                                <div class="card-body">
                                    <h4>{{$evento->nome}}</h4>
                                    <div class="d-flex {{isset($data) ? 'justify-content-between' : ''}} align-items-baseline">
                                        @isset ($data)
                                            <p class="text-danger"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-event" viewBox="0 0 16 16">
                                                <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z"/>
                                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/>
                                              </svg> {{ \Carbon\Carbon::parse($evento->dataFim)->format('l, d F') }}</p>
                                        @endisset
                                        <a href="{{route('evento.visualizar', ['id' => $evento->id])}}" class="btn border rounded-3">Saiba mais</a>
                                    </div>
                                    @isset($data)
                                        <p class="card-text">{{$evento->descricao}}</p>
                                    @endisset
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <button class="carousel-control-prev bg-dark" style="left: -10rem" type="button" data-bs-target="#carouselComponentCaptions"
        data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next bg-dark" style="right: -10rem;" type="button" data-bs-target="#carouselComponentCaptions"
        data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>