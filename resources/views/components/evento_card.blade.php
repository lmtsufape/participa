<div class="card border-0 shadow-sm text-center h-100">
    @if ($icone)
        <img src="{{ asset('storage/' . $icone) }}" alt="Imagem evento" width="100%" height="150px">
    @elseif ($fotoEvento)
        <img src="{{ asset('storage/' . $fotoEvento) }}" alt="Imagem evento" width="100%" height="150px">
    @else
        <img src="{{ asset('img/colorscheme.png') }}" alt="Imagem padrão" width="100%" height="150px">
    @endif

    {{-- Corpo do card --}}
    <div class="p-3">
        <h6 class="fw-bold text-uppercase mb-2"><a href="{{ $link }}" class="text-decoration-none text-dark">{{ $nome }}</a></h6>

        {{-- Data e botão --}}
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="small text-orange d-flex align-items-center gap-1">
                <img src="{{ asset('img/icons/calendar.png') }}" width="16px" alt="Data">
                <span class="text-my-secondary">{{ $dataInicioFormatada }}</span>
            </div>
            <a href="{{ $link }}" class="btn btn-my-outline-primary rounded-pill btn-sm px-3">{{ __('Saiba mais') }}</a>

        </div>

        {{-- Descrição --}}
        <p class="small text-muted mb-0">
            {!! $descricao !!}
        </p>

    </div>
</div>
