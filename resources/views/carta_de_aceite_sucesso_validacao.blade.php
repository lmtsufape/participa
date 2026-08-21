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

                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="small text-secondary mb-1">Código oficial</div>

                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div class="fs-5 fw-bold font-monospace">{{ $codigo }}</div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="btnCopiar">
                                    Copiar
                                </button>
                                <a href="{{ route('trabalho.cartaAceite.downloadPdf', ['codigo' => $codigo]) }}" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-pdf" viewBox="0 0 16 16">
                                      <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                                      <path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.142.23-.401.62-.733 1.135-1.026.47-.267 1.042-.472 1.684-.606.336-.07.697-.12 1.07-.152.42-.036.837-.054 1.237-.054.414 0 .8.018 1.144.053.385.04.725.105 1.01.19.467.142.846.36 1.117.635.27.274.37.608.293.978a.86.86 0 0 1-.497.618c-.287.145-.644.187-1.05.122-.44-.07-.946-.226-1.493-.46-.53-.227-1.094-.52-1.666-.867-.532.31-1.074.576-1.603.784-.505.2-1.002.327-1.47.375a2.53 2.53 0 0 1-.822-.04z"/>
                                    </svg>
                                    Baixar PDF
                                </a>
                            </div>
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
                                    <span class="text-muted">—</span>
                                @endforelse
                            </dd>

                            <dt class="col-sm-4">Modalidade</dt>
                            <dd class="col-sm-8">{{ $trabalho->modalidade->nome ?? '—' }}</dd>

                            <dt class="col-sm-4">Emitido em</dt>
                            <dd class="col-sm-8">{{ \Carbon\Carbon::parse($trabalho->aprovacao_emitida_em)->format('d/m/Y H:i') }}</dd>
                        </dl>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('validarCertificado') }}" class="btn btn-outline-secondary">
                        Validar outro código
                    </a>
                </div>

            </div>
        </div>
    </div>
@endsection

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