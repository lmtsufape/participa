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
<div id="carouselComponentCaptions{{$id}}" class="carousel slide carousel-dark" data-bs-ride="false">
    <div class="carousel-inner">
        @foreach ($palestrantes->chunk(5) as $palestrantesChunk)
            <div class="carousel-item bg-my-shadow {{ $loop->first ? 'active' : '' }}">
                <div class="row px-5 ">
                    @foreach ($palestrantesChunk as $palestrante)
                        <div class="col-md-3">
                            @if ($palestrante->fotoPalestrante)
                                <img src="{{ asset('storage/' . $palestrante->fotoPalestrante) }}" class="img-thumbnail" alt="Foto de {{ $palestrante->nome }}">
                            @else
                                <img src="{{ asset('/img/capa-evento.png') }}" class="img-thumbnail" alt="Foto do palestrante">
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
    @if(count($palestrantes) > 5)
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
            @foreach ($palestrantes->chunk(5) as $index => $palestrante)
                <button type="button" data-bs-target="#carouselComponentCaptions{{$id}}"
                    data-bs-slide-to="{{ $index }}" class="rounded-circle {{ $index == 0 ? 'active' : 'bg-secondary' }}"
                    style="width: 10px; height: 10px; margin: 0 4px; border: none;"
                    aria-current="{{ $index == 0 ? 'true' : '' }}" aria-label="Slide {{ $index + 1 }}">
                </button>
            @endforeach
        </div>
    @endif
</div>
