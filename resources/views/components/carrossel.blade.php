@php
    $urlVerTodos = $urlVerTodos ?? route('eventos.proximos');
@endphp


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

<div class="d-flex justify-content-sm-between align-items-center pb-5">
  <h2 class="text-my-primary">{{ $titulo }}</h2>
  <a href="{{ $urlVerTodos }}" class="btn btn-my-outline-primary rounded-5">{{ __('Ver todos') }}</a>
</div>

<div id="carouselComponentCaptions{{$id}}" class="carousel slide carousel-dark" data-bs-ride="false">
  <div class="carousel-inner">
    @foreach($eventos->chunk(3) as $chunk)
      <div class="carousel-item {{ $loop->first ? 'active' : '' }} bg-transparent">
        <div class="row px-5">
          @foreach($chunk as $evento)
            <div class="col-md-4 mb-4">
              <div class="card border-0 shadow-sm text-center h-100">
                {{-- imagem --}}
                @if($evento->icone)
                  <img src="{{ asset('storage/'.$evento->icone) }}"
                       alt="Imagem evento"
                       class="w-100" style="height:150px;object-fit:cover;">
                @elseif($evento->fotoEvento)
                  <img src="{{ asset('storage/'.$evento->fotoEvento) }}"
                       alt="Imagem evento"
                       class="w-100" style="height:150px;object-fit:cover;">
                @else
                  <img src="{{ asset('img/colorscheme.png') }}"
                       alt="Imagem padrão"
                       class="w-100" style="height:150px;object-fit:cover;">
                @endif

                {{-- corpo --}}
                <div class="p-3">
                  <h6 class="fw-bold text-uppercase mb-2">
                    <a href="{{ route('evento.visualizar', $evento->id) }}"
                       class="text-decoration-none text-dark">
                      {{ $evento->nome }}
                    </a>
                  </h6>

                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="small text-orange d-flex align-items-center gap-1">
                      <img src="{{ asset('img/icons/calendar.png') }}" width="16" alt="Data">
                        <span class="text-my-secondary">
                            {{\Carbon\Carbon::parse($evento->dataFim)->locale('pt_BR')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                        </span>
                    </div>
                    <a href="{{ route('evento.visualizar', $evento->id) }}"
                       class="btn btn-my-outline-primary rounded-pill btn-sm px-3">
                      {{ __('Saiba mais') }}
                    </a>
                  </div>

                  <p class="small text-muted mb-0 truncate-multiline">
                    {!! Str::limit(strip_tags($evento->descricao), 200, '...') !!}
                  </p>
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
