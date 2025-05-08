<style>
    .carousel-indicators button.active {
        background-color: #000 !important;
    }
    .truncate-multiline {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }


</style>
<div class="d-flex justify-content-sm-between align-items-center px-5">
    <h2 class="text-my-primary">{{$titulo}}</h2>
    <a href="{{route('eventos.proximos')}}" class="btn border rounded-pill">Ver todos</a>
</div>
<div id="carouselComponentCaptions{{$id}}" class="carousel slide carousel-dark" data-bs-ride="false">
    <div class="carousel-inner">
        @foreach ($eventos->chunk(3) as $eventosChunk)
            <div class="carousel-item bg-my-shadow {{ $loop->first ? 'active' : '' }}">
                <div class="row px-5 ">
                    @foreach ($eventosChunk as $evento)
                        <div class="col-md-4">
                            <div class="card shadow" style="height: 40vh;">
                                <img src="{{ asset('/img/capa-evento.png') }}" class="card-img-top" alt="Foto do evento">
                                <div class="card-body">
                                    <h4>{{$evento->nome}}</h4>
                                    <div class="d-flex justify-content-between align-items-baseline">

                                        <span class="d-flex align-items-center gap-1 text-my-secondary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-event" viewBox="0 0 16 16">
                                                <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z"/>
                                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/>
                                            </svg>
                                            {{ \Carbon\Carbon::parse($evento->dataFim)->format('l, d F') }}
                                        </span>

                                        <a href="{{route('evento.visualizar', ['id' => $evento->id])}}" class="btn border text-end rounded-3">Saiba mais</a>
                                    </div>

                                    <p class="card-text m-1 pt-1 truncate-multiline">{!! Str::limit(strip_tags($evento->descricao), 200, '...') !!}</p>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
    @if(count($eventos) > 3)
        <button class="carousel-control-prev w-auto"  type="button" data-bs-target="#carouselComponentCaptions{{$id}}"
            data-bs-slide="prev">
            <span class="" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-chevron-compact-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M9.224 1.553a.5.5 0 0 1 .223.67L6.56 8l2.888 5.776a.5.5 0 1 1-.894.448l-3-6a.5.5 0 0 1 0-.448l3-6a.5.5 0 0 1 .67-.223"/>
                </svg>
            </span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next w-auto"  type="button" data-bs-target="#carouselComponentCaptions{{$id}}"
            data-bs-slide="next">
            <span class="" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-chevron-compact-right" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M6.776 1.553a.5.5 0 0 1 .671.223l3 6a.5.5 0 0 1 0 .448l-3 6a.5.5 0 1 1-.894-.448L9.44 8 6.553 2.224a.5.5 0 0 1 .223-.671"/>
                </svg>
            </span>
            <span class="visually-hidden">Next</span>
        </button>
        <div class="carousel-indicators">
            @foreach ($eventos->chunk(3) as $index => $evento)
                <button type="button" data-bs-target="#carouselComponentCaptions{{$id}}"
                    data-bs-slide-to="{{ $index }}" class="rounded-circle {{ $index == 0 ? 'active' : 'bg-secondary' }}"
                    style="width: 10px; height: 10px; margin: 0 4px; border: none;"
                    aria-current="{{ $index == 0 ? 'true' : '' }}" aria-label="Slide {{ $index + 1 }}">
                </button>
            @endforeach
        </div>
    @endif
</div>