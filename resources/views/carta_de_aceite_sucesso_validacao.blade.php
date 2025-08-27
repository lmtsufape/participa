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

                <dt class="col-sm-4">Modalidade</dt>
                <dd class="col-sm-8">{{ $trabalho->modalidade->nome ?? '—' }}</dd>

                <dt class="col-sm-4">Emitido em</dt>
                <dd class="col-sm-8">{{ $trabalho->aprovacao_emitida_em->format('d/m/Y H:i') }}</dd>
                </dl>
            </div>
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
  {{-- copiar para área de transferência --}}
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
