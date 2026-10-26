@php
    $restaurarProponentes = isset($atividadeProponentes)
        ? (string) old('idAtividade') === (string) $atividadeProponentes->id
        : old('idNovaAtividade') !== null;
    $nomesProponentes = $restaurarProponentes ? old('proponentes', []) : ($atividadeProponentes->proponentes ?? []);
@endphp
<div class="my-3" data-proponentes>
    <h5>Proponentes / Ministrantes</h5>
    <p class="text-muted">Opcional. Informe apenas os nomes das pessoas que irão ministrar a atividade. Serão exibidos na programação.</p>
    <input type="hidden" name="proponentes[]" value="">
    <div data-proponentes-lista>
        @foreach($nomesProponentes as $nomeProponente)
            @if($nomeProponente !== null && $nomeProponente !== '')
                <div class="input-group mb-2" data-proponente>
                    <input type="text" class="form-control" name="proponentes[]" value="{{ $nomeProponente }}" maxlength="255" aria-label="Nome do proponente ou ministrante">
                    <button type="button" class="btn btn-outline-secondary" data-remover-proponente>Remover</button>
                </div>
            @endif
        @endforeach
    </div>
    <button type="button" class="btn btn-outline-primary" data-adicionar-proponente>Adicionar proponente</button>
</div>
@once
    @push('scripts')
        <script src="{{ asset('js/atividade-proponentes.js') }}" defer></script>
    @endpush
@endonce
