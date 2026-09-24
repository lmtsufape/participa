@php
    /*
     * Na criação: $modalidade = null
     * Na edição:  $modalidade = Modalidade
     */
    $modalidade = $modalidade ?? null;

    /*
     * Avaliação durante submissão
     */
    $avaliacaoDuranteSubmissao = session()->hasOldInput()
        ? (bool) old('avaliacaoDuranteSubmissao', false)
        : (bool) ($modalidade?->avaliacaoDuranteSubmissao ?? false);

    /*
     * Resumo por texto
     */
    $textoAtivo = session()->hasOldInput()
        ? (bool) old('texto', false)
        : (bool) ($modalidade?->texto ?? false);

    $limiteSelecionado = session()->hasOldInput()
        ? old('limit')
        : (
            ($modalidade?->caracteres ?? false)
                ? 'caracteres'
                : (
                    ($modalidade?->palavras ?? false)
                        ? 'palavras'
                        : null
                )
        );

    /*
     * Submissão por arquivo
     */
    $arquivoAtivo = session()->hasOldInput()
        ? (bool) old('arquivo', false)
        : (bool) ($modalidade?->arquivo ?? false);

    /*
     * Apresentação
     */
    $apresentacaoAtiva = session()->hasOldInput()
        ? (bool) old('apresentacao', false)
        : (bool) ($modalidade?->apresentacao ?? false);

    $tiposSelecionados = $modalidade
        ? $modalidade->tiposApresentacao->pluck('tipo')
        : collect();

    $tiposApresentacao = [
        'remoto' => 'Remoto',
        'presencial' => 'Presencial',
        'a_distancia' => 'À distância',
        'semipresencial' => 'Semipresencial',
    ];

    /*
     * Submissão única
     */
    $submissaoUnica = session()->hasOldInput()
        ? (bool) old('submissaoUnica', false)
        : (bool) ($modalidade?->submissaoUnica ?? false);
@endphp


<x-forms.section
    title="Configurações da submissão"
    description="Defina as regras e permissões para o envio de trabalhos nesta modalidade."
    icon="bi-sliders"
>

    {{-- Avaliação durante submissão --}}
    <div x-data="{ mostrarAviso: false }">

        <div class="form-check form-switch">
            <input
                class="form-check-input"
                id="avaliacaoDuranteSubmissaocheck"
                type="checkbox"
                name="avaliacaoDuranteSubmissao"
                value="1"
                @checked($avaliacaoDuranteSubmissao)
                @change="mostrarAviso = $event.target.checked"
            >

            <label
                class="form-check-label fw-semibold"
                for="avaliacaoDuranteSubmissaocheck"
            >
                Permitir avaliação durante o período de submissão
            </label>
        </div>

        <div
            x-show="mostrarAviso"
            x-transition
            class="alert alert-warning mt-3 mb-0"
        >
            <i class="bi bi-exclamation-triangle me-1"></i>

            <strong>Atenção:</strong>
            permitir que a avaliação inicie durante o período de submissão
            evita que um participante envie o mesmo trabalho várias vezes.
        </div>

    </div>

    @error('avaliacaoDuranteSubmissao')
        <div class="text-danger small mt-1">
            {{ $message }}
        </div>
    @enderror


    {{-- Resumo por texto --}}
    <div class="form-check form-switch mt-3">
        <input
            class="form-check-input"
            type="checkbox"
            name="texto"
            id="texto"
            value="1"
            @checked($textoAtivo)
        >

        <label
            class="form-check-label fw-semibold"
            for="texto"
        >
            Adicionar campo de resumo por texto
        </label>
    </div>

    @error('texto')
        <div class="text-danger small mt-1">
            {{ $message }}
        </div>
    @enderror


    <div
        id="restricoes-resumo-texto"
        class="mt-3 ms-4 {{ $textoAtivo ? '' : 'd-none' }}"
    >
        <div class="mb-2">
            <span class="fw-semibold">
                Limite do resumo
            </span>

            <small class="text-muted d-block">
                Defina se o limite será calculado por caracteres ou palavras.
            </small>
        </div>

        <div class="d-flex flex-wrap gap-3">
            <div class="form-check">
                <input
                    class="form-check-input"
                    type="radio"
                    name="limit"
                    id="limite-caracteres"
                    value="caracteres"
                    @checked($limiteSelecionado === 'caracteres')
                >

                <label
                    class="form-check-label"
                    for="limite-caracteres"
                >
                    Quantidade de caracteres
                </label>
            </div>

            <div class="form-check">
                <input
                    class="form-check-input"
                    type="radio"
                    name="limit"
                    id="limite-palavras"
                    value="palavras"
                    @checked($limiteSelecionado === 'palavras')
                >

                <label
                    class="form-check-label"
                    for="limite-palavras"
                >
                    Quantidade de palavras
                </label>
            </div>
        </div>

        @error('limit')
            <div class="text-danger small mt-2">
                {{ $message }}
            </div>
        @enderror


        {{-- Limites por caracteres --}}
        <div
            id="min-max-caracteres"
            class="row g-3 mt-1 {{ $limiteSelecionado === 'caracteres' ? '' : 'd-none' }}"
        >
            <div class="col-sm-6 col-md-4">
                <label
                    class="form-label"
                    for="mincaracteres"
                >
                    Mínimo
                </label>

                <input
                    class="form-control @error('mincaracteres') is-invalid @enderror"
                    type="number"
                    id="mincaracteres"
                    name="mincaracteres"
                    min="0"
                    value="{{ old('mincaracteres', $modalidade?->mincaracteres) }}"
                >

                @error('mincaracteres')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-sm-6 col-md-4">
                <label
                    class="form-label"
                    for="maxcaracteres"
                >
                    Máximo
                </label>

                <input
                    class="form-control @error('maxcaracteres') is-invalid @enderror"
                    type="number"
                    id="maxcaracteres"
                    name="maxcaracteres"
                    min="0"
                    value="{{ old('maxcaracteres', $modalidade?->maxcaracteres) }}"
                >

                @error('maxcaracteres')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>


        {{-- Limites por palavras --}}
        <div
            id="min-max-palavras"
            class="row g-3 mt-1 {{ $limiteSelecionado === 'palavras' ? '' : 'd-none' }}"
        >
            <div class="col-sm-6 col-md-4">
                <label
                    class="form-label"
                    for="minpalavras"
                >
                    Mínimo
                </label>

                <input
                    class="form-control @error('minpalavras') is-invalid @enderror"
                    type="number"
                    id="minpalavras"
                    name="minpalavras"
                    min="0"
                    value="{{ old('minpalavras', $modalidade?->minpalavras) }}"
                >

                @error('minpalavras')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-sm-6 col-md-4">
                <label
                    class="form-label"
                    for="maxpalavras"
                >
                    Máximo
                </label>

                <input
                    class="form-control @error('maxpalavras') is-invalid @enderror"
                    type="number"
                    id="maxpalavras"
                    name="maxpalavras"
                    min="0"
                    value="{{ old('maxpalavras', $modalidade?->maxpalavras) }}"
                >

                @error('maxpalavras')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
    </div>


    {{-- Submissão por arquivo --}}
    <div class="form-check form-switch mt-3">
        <input
            class="form-check-input"
            type="checkbox"
            role="switch"
            name="arquivo"
            id="arquivo"
            value="1"
            @checked($arquivoAtivo)
        >

        <label
            class="form-check-label fw-semibold"
            for="arquivo"
        >
            Incluir submissão por arquivo
        </label>
    </div>

    @error('arquivo')
        <div class="text-danger small mt-1">
            {{ $message }}
        </div>
    @enderror


    <div
        id="tipos-arquivos"
        class="mt-3 ms-4 {{ $arquivoAtivo ? '' : 'd-none' }}"
    >
        <div class="mb-3">
            <span class="fw-semibold">
                Extensões permitidas
            </span>

            <small class="text-muted d-block">
                Selecione os tipos de arquivo que poderão ser enviados.
            </small>
        </div>

        <div class="row g-3">
            @foreach (\App\Enums\TipoArquivo::agrupadosPorCategoria() as $categoria => $tipos)
                <div class="col-6 col-md-4 col-lg">
                    <div class="small fw-semibold text-muted mb-2">
                        {{ $categoria }}
                    </div>

                    @foreach ($tipos as $tipo)
                        @php
                            $tipoSelecionado = session()->hasOldInput()
                                ? (bool) old($tipo->value, false)
                                : (bool) data_get(
                                    $modalidade,
                                    $tipo->value,
                                    false
                                );
                        @endphp

                        <div class="form-check mb-1">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="{{ $tipo->value }}"
                                id="{{ $tipo->value }}"
                                value="1"
                                @checked($tipoSelecionado)
                            >

                            <label
                                class="form-check-label"
                                for="{{ $tipo->value }}"
                            >
                                {{ $tipo->extensao() }}
                            </label>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>

        @error('tipos_arquivo')
            <div class="text-danger small mt-2">
                {{ $message }}
            </div>
        @enderror
    </div>


    {{-- Apresentação --}}
    <div class="mt-3">
        <div class="form-check form-switch">
            <input
                class="form-check-input"
                type="checkbox"
                name="apresentacao"
                id="apresentacao"
                value="1"
                @checked($apresentacaoAtiva)
            >

            <label
                class="form-check-label fw-semibold"
                for="apresentacao"
            >
                Habilitar escolha da forma de apresentação do trabalho
            </label>
        </div>

        @error('apresentacao')
            <div class="text-danger small mt-1">
                {{ $message }}
            </div>
        @enderror


        <div
            id="tipo-apresentacao"
            class="mt-3 ms-4 {{ $apresentacaoAtiva ? '' : 'd-none' }}"
        >
            <div class="mb-2">
                <span class="fw-semibold">
                    Formas de apresentação
                </span>

                <small class="text-muted d-block">
                    Selecione pelo menos uma forma de apresentação disponível para o autor.
                </small>
            </div>

            <div class="d-flex flex-wrap gap-3">
                @foreach ($tiposApresentacao as $campo => $tipo)
                    @php
                        $tipoApresentacaoSelecionado =
                            session()->hasOldInput()
                                ? (bool) old($campo, false)
                                : $tiposSelecionados->contains($tipo);
                    @endphp

                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="{{ $campo }}"
                            id="{{ $campo }}"
                            value="1"
                            @checked($tipoApresentacaoSelecionado)
                        >

                        <label
                            class="form-check-label"
                            for="{{ $campo }}"
                        >
                            {{ $tipo }}
                        </label>
                    </div>
                @endforeach
            </div>

            @error('tipos_apresentacao')
                <div class="text-danger small mt-2">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>


    {{-- Submissão única --}}
    <div class="form-check form-switch mt-3">
        <input
            class="form-check-input"
            type="checkbox"
            value="1"
            name="submissaoUnica"
            id="submissaoUnicacheck"
            @checked($submissaoUnica)
        >

        <label
            class="form-check-label fw-semibold"
            for="submissaoUnicacheck"
        >
            {{ __('Habilitar submissão única para avaliação') }}
        </label>
    </div>

    @error('submissaoUnica')
        <div class="text-danger small mt-1">
            {{ $message }}
        </div>
    @enderror

</x-forms.section>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        const avaliacaoDuranteSubmissao = document.getElementById(
            'avaliacaoDuranteSubmissaocheck'
        );

        const texto = document.getElementById('texto');
        const restricoesTexto = document.getElementById(
            'restricoes-resumo-texto'
        );

        const limiteCaracteres = document.getElementById(
            'limite-caracteres'
        );

        const limitePalavras = document.getElementById(
            'limite-palavras'
        );

        const camposCaracteres = document.getElementById(
            'min-max-caracteres'
        );

        const camposPalavras = document.getElementById(
            'min-max-palavras'
        );

        const arquivo = document.getElementById('arquivo');
        const tiposArquivos = document.getElementById(
            'tipos-arquivos'
        );

        const apresentacao = document.getElementById(
            'apresentacao'
        );

        const tiposApresentacao = document.getElementById(
            'tipo-apresentacao'
        );


        /*
         * Resumo por texto
         */
        texto?.addEventListener('change', () => {
            restricoesTexto?.classList.toggle(
                'd-none',
                !texto.checked
            );
        });


        /*
         * Caracteres / palavras
         */
        document
            .querySelectorAll('input[name="limit"]')
            .forEach((radio) => {
                radio.addEventListener('change', () => {
                    camposCaracteres?.classList.toggle(
                        'd-none',
                        !limiteCaracteres.checked
                    );

                    camposPalavras?.classList.toggle(
                        'd-none',
                        !limitePalavras.checked
                    );
                });
            });


        /*
         * Submissão por arquivo
         */
        arquivo?.addEventListener('change', () => {
            tiposArquivos?.classList.toggle(
                'd-none',
                !arquivo.checked
            );
        });


        /*
         * Apresentação
         */
        apresentacao?.addEventListener('change', () => {
            tiposApresentacao?.classList.toggle(
                'd-none',
                !apresentacao.checked
            );
        });
    });
</script>
