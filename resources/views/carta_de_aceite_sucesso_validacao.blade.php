@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">

            <header class="text-center mb-3">
            <div class="d-inline-flex align-items-center gap-2">
                <h1 class="h5 fw-semibold mb-0">Carta de aceite válida</h1>
                <span class="badge rounded-pill text-bg-success">Autenticada</span>
            </div>
            <p class="text-muted small mb-0">Autenticidade confirmada pelo sistema.</p>
            </header>

            <div class="border-top opacity-25 mb-3"></div>

            <div class="card mb-3">
            <div class="card-body">
                <div class="small text-secondary mb-1">Código oficial</div>

                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="fs-5 fw-bold font-monospace">{{ $codigo }}</div>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="btnCopiar">
                    Copiar
                </button>
                </div>

                <hr class="my-3">

                <dl class="row mb-0">
                <dt class="col-sm-4">Título</dt>
                <dd class="col-sm-8">{{ $trabalho->titulo }}</dd>

                <dt class="col-sm-4">Autor(a)</dt>
                <dd class="col-sm-8">{{ $trabalho->autor->name ?? '—' }}</dd>

                <dt class="col-sm-4">Coautores(as)</dt>
                <dd class="col-sm-8">
                    @forelse ($trabalho->coautors as $coautor)
                        <li class="list-unstyled">{{ Str::title($coautor->user->name) }}</li>
                    @empty
                        <span>—</span>
                    @endforelse
                </dd>

                <dt class="col-sm-4">Modalidade</dt>
                <dd class="col-sm-8">{{ $trabalho->modalidade->nome ?? '—' }}</dd>

                <dt class="col-sm-4">Emitido em</dt>
                <dd class="col-sm-8">{{ optional($trabalho->aprovacao_emitida_em)->format('d/m/Y H:i') ?? '—' }}</dd>
                </dl>
            </div>
            </div>

            {{-- Botão de Download do PDF --}}
            <div class="d-grid gap-2 mb-3">
                <a href="{{ route('cartaAceite.downloadPdf', ['codigo' => $codigo]) }}" class="btn btn-primary d-flex align-items-center justify-content-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-file-earmark-pdf" viewBox="0 0 16 16">
                        <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                        <path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.143.244-.427.721-.698 1.155-.933.457-.248.915-.494 1.38-.724a10.78 10.78 0 0 0 .932-1.257c.186-.31.33-.63.435-.956a7.878 7.878 0 0 0 .21-1.459c.002-.198.052-.406.148-.516.12-.137.304-.22.501-.22.18 0 .347.09.435.244.095.164.082.399.03.605a6.05 6.05 0 0 1-.563 1.348c-.28.535-.646 1.053-1.082 1.507.092.386.198.79.328 1.217.156.518.334 1.022.52 1.483.08.198.14.41.13.627-.01.216-.11.411-.26.544a.712.712 0 0 1-.531.147c-.26-.03-.49-.197-.67-.407a7.99 7.99 0 0 1-.82-1.209c-.524.28-1.066.52-1.62.716-.45.16-.9.284-1.35.311a1.09 1.09 0 0 1-.165.006z"/>
                    </svg>
                    Baixar Carta de Aceite (PDF)
                </a>
            </div>

            <div class="d-grid">
                <a href="{{ route('validarCertificado') }}" class="btn btn-outline-secondary">
                    Validar outro código
                </a>
            </div>

        </div>
        </div>
    </div>
@endsection

@section('javascript')
<script>
    document.getElementById('btnCopiar')?.addEventListener('click', async () => {
      try {
        await navigator.clipboard.writeText("{{ $codigo }}");
        const btn = document.getElementById('btnCopiar');
        const original = btn.textContent;
        btn.textContent = 'Copiado!';
        setTimeout(() => btn.textContent = original, 1600);
      } catch(e) {}
    });
</script>
@endsection