@php
    $modalidade = $modalidade ?? null;

    /*
     * Normaliza os documentos extras para funcionar:
     * - na criação;
     * - na atualização;
     * - após erro de validação.
     */
    if (session()->hasOldInput()) {
        $documentosOld = array_values(
            old('documentosExtra', [])
        );

        $idsOld = array_values(
            old('docsID', [])
        );

        $documentosExtras = collect($documentosOld)
            ->map(function ($documento, $indice) use ($idsOld) {
                $valores = array_values($documento);

                return [
                    'id' => $idsOld[$indice] ?? null,
                    'nome' => array_shift($valores) ?? '',
                    'tipos' => $valores,
                ];
            });
    } elseif ($modalidade) {
        $documentosExtras = $modalidade
            ->midiasExtra
            ->map(function ($documento) {
                $tipos = collect(
                    \App\Enums\TipoArquivo::cases()
                )
                    ->filter(
                        fn ($tipo) =>
                            (bool) $documento->{$tipo->value}
                    )
                    ->map(
                        fn ($tipo) => $tipo->value
                    )
                    ->values()
                    ->all();

                return [
                    'id' => $documento->id,
                    'nome' => $documento->nome,
                    'tipos' => $tipos,
                ];
            })
            ->values();
    } else {
        $documentosExtras = collect();
    }
@endphp


<x-forms.section
    title="Documentos e materiais"
    description="Disponibilize arquivos de orientação ou modelos para os participantes."
    icon="bi-folder2-open"
>

    {{-- Modelo de apresentação --}}
    <div class="mb-4">
        <label
            for="arquivoModelos"
            class="form-label fw-semibold"
        >
            Enviar {{ $evento->formEvento->etiquetabaixarapresentacao }}
        </label>

        @if ($modalidade?->modelo_apresentacao)
            <div class="mb-2">
                <a
                    href="{{ route('modalidade.modelos.download', ['id' => $modalidade->id]) }}"
                    class="text-decoration-none"
                >
                    <i class="bi bi-download me-1"></i>
                    Arquivo atual
                </a>
            </div>

            <div class="form-check mb-2">
                <input
                    class="form-check-input"
                    type="checkbox"
                    name="deleteapresentacao"
                    id="deleteapresentacao"
                    value="1"
                    @checked(old('deleteapresentacao', false))
                >

                <label
                    class="form-check-label"
                    for="deleteapresentacao"
                >
                    Excluir arquivo enviado
                </label>
            </div>

            @error('deleteapresentacao')
                <div class="text-danger small mb-2">
                    {{ $message }}
                </div>
            @enderror
        @endif

        <input
            type="file"
            class="form-control @error('arquivoModelos') is-invalid @enderror"
            id="arquivoModelos"
            name="arquivoModelos"
            accept=".odt,.ott,.docx,.doc,.rtf,.txt,.pdf,.odp,.ppt,.pptx"
        >

        @error('arquivoModelos')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

        <div class="form-text">
            O arquivo deve estar no formato ODT, OTT, DOCX, DOC,
            RTF, TXT, PDF, ODP, PPT ou PPTX e possuir até 2 MB.
        </div>

        @if ($modalidade?->modelo_apresentacao)
            <div class="form-text">
                Para alterar o arquivo atual, envie uma nova versão.
            </div>
        @endif
    </div>


    {{-- Regras --}}
    <div class="mb-4">
        <label
            for="arquivoRegras"
            class="form-label fw-semibold"
        >
            Enviar {{ $evento->formEvento->etiquetabaixarregra }}
        </label>

        @if ($modalidade?->regra)
            <div class="mb-2">
                <a
                    href="{{ route('modalidade.regras.download', ['id' => $modalidade->id]) }}"
                    class="text-decoration-none"
                >
                    <i class="bi bi-download me-1"></i>
                    Arquivo atual
                </a>
            </div>

            <div class="form-check mb-2">
                <input
                    class="form-check-input"
                    type="checkbox"
                    name="deleteregra"
                    id="deleteregra"
                    value="1"
                    @checked(old('deleteregra', false))
                >

                <label
                    class="form-check-label"
                    for="deleteregra"
                >
                    Excluir arquivo enviado
                </label>
            </div>

            @error('deleteregra')
                <div class="text-danger small mb-2">
                    {{ $message }}
                </div>
            @enderror
        @endif

        <input
            type="file"
            class="form-control @error('arquivoRegras') is-invalid @enderror"
            id="arquivoRegras"
            name="arquivoRegras"
            accept=".pdf"
        >

        @error('arquivoRegras')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

        <div class="form-text">
            O arquivo deve estar no formato PDF e possuir até 10 MB.
        </div>

        @if ($modalidade?->regra)
            <div class="form-text">
                Para alterar o arquivo atual, envie uma nova versão.
            </div>
        @endif
    </div>


    {{-- Template --}}
    <div class="mb-4">
        <label
            for="arquivoTemplates"
            class="form-label fw-semibold"
        >
            Enviar {{ $evento->formEvento->etiquetabaixartemplate }}
        </label>

        @if ($modalidade?->template)
            <div class="mb-2">
                <a
                    href="{{ route('modalidade.template.download', ['id' => $modalidade->id]) }}"
                    class="text-decoration-none"
                >
                    <i class="bi bi-download me-1"></i>
                    Arquivo atual
                </a>
            </div>

            <div class="form-check mb-2">
                <input
                    class="form-check-input"
                    type="checkbox"
                    name="deletetemplate"
                    id="deletetemplate"
                    value="1"
                    @checked(old('deletetemplate', false))
                >

                <label
                    class="form-check-label"
                    for="deletetemplate"
                >
                    Excluir arquivo enviado
                </label>
            </div>

            @error('deletetemplate')
                <div class="text-danger small mb-2">
                    {{ $message }}
                </div>
            @enderror
        @endif

        <input
            type="file"
            class="form-control @error('arquivoTemplates') is-invalid @enderror"
            id="arquivoTemplates"
            name="arquivoTemplates"
            accept=".odt,.ott,.docx,.doc,.rtf,.txt,.pdf,.pptx"
        >

        @error('arquivoTemplates')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

        <div class="form-text">
            O arquivo deve estar no formato ODT, OTT, DOCX, DOC,
            RTF, TXT, PDF ou PPTX e possuir até 2 MB.
        </div>

        @if ($modalidade?->template)
            <div class="form-text">
                Para alterar o arquivo atual, envie uma nova versão.
            </div>
        @endif
    </div>


    {{-- Instruções --}}
    <div class="mb-4">
        <label
            for="arquivoInstrucoes"
            class="form-label fw-semibold"
        >
            Enviar {{ $evento->formEvento->etiquetabaixarinstrucoes }}
        </label>

        @if ($modalidade?->instrucoes)
            <div class="mb-2">
                <a
                    href="{{ route('modalidade.instrucoes.download', ['modalidade' => $modalidade->id]) }}"
                    class="text-decoration-none"
                >
                    <i class="bi bi-download me-1"></i>
                    Arquivo atual
                </a>
            </div>

            <div class="form-check mb-2">
                <input
                    class="form-check-input"
                    type="checkbox"
                    name="deleteinstrucoes"
                    id="deleteinstrucoes"
                    value="1"
                    @checked(old('deleteinstrucoes', false))
                >

                <label
                    class="form-check-label"
                    for="deleteinstrucoes"
                >
                    Excluir arquivo enviado
                </label>
            </div>

            @error('deleteinstrucoes')
                <div class="text-danger small mb-2">
                    {{ $message }}
                </div>
            @enderror
        @endif

        <input
            type="file"
            class="form-control @error('arquivoInstrucoes') is-invalid @enderror"
            id="arquivoInstrucoes"
            name="arquivoInstrucoes"
            accept=".pdf"
        >

        @error('arquivoInstrucoes')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

        <div class="form-text">
            O arquivo deve estar no formato PDF e possuir até 2 MB.
        </div>

        @if ($modalidade?->instrucoes)
            <div class="form-text">
                Para alterar o arquivo atual, envie uma nova versão.
            </div>
        @endif
    </div>


    <hr class="my-4">


    {{-- Documentos extras --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="fw-semibold">
                Documentos solicitados
            </div>

            <small class="text-muted">
                Defina documentos adicionais que deverão ser enviados pelos participantes.
            </small>
        </div>

        <button
            type="button"
            id="btn-adicionar-documento"
            class="btn btn-primary"
        >
            <i class="bi bi-plus-lg me-1"></i>
            Requisitar novo documento
        </button>
    </div>


    <div id="documentos-extra">
        @foreach ($documentosExtras as $indice => $documento)
            <div
                class="mb-4"
                data-documento-extra
            >
                @if ($documento['id'])
                    <input
                        type="hidden"
                        name="docsID[]"
                        value="{{ $documento['id'] }}"
                    >
                @endif

                <label
                    for="documento-extra-{{ $indice }}"
                    class="form-label fw-semibold"
                >
                    Nome do documento
                    <span class="text-danger">*</span>
                </label>

                <div class="d-flex gap-2">
                    <input
                        type="text"
                        class="form-control @error("documentosExtra.$indice") is-invalid @enderror"
                        id="documento-extra-{{ $indice }}"
                        name="documentosExtra[{{ $indice }}][]"
                        placeholder="Digite o nome do documento"
                        value="{{ $documento['nome'] }}"
                        required
                    >

                    <button
                        type="button"
                        class="btn btn-outline-danger"
                        data-remover-documento
                        title="Remover documento"
                    >
                        <i class="bi bi-trash"></i>
                    </button>
                </div>

                @error("documentosExtra.$indice")
                    <div class="text-danger small mt-1">
                        {{ $message }}
                    </div>
                @enderror


                <div class="mt-3 ms-3">
                    <div class="small fw-semibold text-muted mb-2">
                        Tipos de extensão aceitas
                    </div>

                    <div class="row g-3">
                        @foreach (\App\Enums\TipoArquivo::agrupadosPorCategoria() as $categoria => $tipos)
                            <div class="col-6 col-md-4 col-lg">
                                <div class="small fw-semibold mb-2">
                                    {{ $categoria }}
                                </div>

                                @foreach ($tipos as $tipo)
                                    <div class="form-check mb-1">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            id="documento-{{ $indice }}-{{ $tipo->value }}"
                                            name="documentosExtra[{{ $indice }}][]"
                                            value="{{ $tipo->value }}"
                                            @checked(
                                                in_array(
                                                    $tipo->value,
                                                    $documento['tipos']
                                                )
                                            )
                                        >

                                        <label
                                            class="form-check-label"
                                            for="documento-{{ $indice }}-{{ $tipo->value }}"
                                        >
                                            {{ $tipo->extensao() }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>


    {{-- Template JS para adicionar novos documentos --}}
    <template id="documento-extra-template">
        <div
            class="mb-4"
            data-documento-extra
        >
            <label
                for="documento-extra-__INDEX__"
                class="form-label fw-semibold"
            >
                Nome do documento
                <span class="text-danger">*</span>
            </label>

            <div class="d-flex gap-2">
                <input
                    type="text"
                    class="form-control"
                    id="documento-extra-__INDEX__"
                    name="documentosExtra[__INDEX__][]"
                    placeholder="Digite o nome do documento"
                    required
                >

                <button
                    type="button"
                    class="btn btn-outline-danger"
                    data-remover-documento
                    title="Remover documento"
                >
                    <i class="bi bi-trash"></i>
                </button>
            </div>

            <div class="mt-3 ms-3">
                <div class="small fw-semibold text-muted mb-2">
                    Tipos de extensão aceitas
                </div>

                <div class="row g-3">
                    @foreach (\App\Enums\TipoArquivo::agrupadosPorCategoria() as $categoria => $tipos)
                        <div class="col-6 col-md-4 col-lg">
                            <div class="small fw-semibold mb-2">
                                {{ $categoria }}
                            </div>

                            @foreach ($tipos as $tipo)
                                <div class="form-check mb-1">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        id="documento-__INDEX__-{{ $tipo->value }}"
                                        name="documentosExtra[__INDEX__][]"
                                        value="{{ $tipo->value }}"
                                    >

                                    <label
                                        class="form-check-label"
                                        for="documento-__INDEX__-{{ $tipo->value }}"
                                    >
                                        {{ $tipo->extensao() }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </template>

</x-forms.section>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        const container = document.getElementById('documentos-extra');

        const template = document.getElementById(
            'documento-extra-template'
        );

        const botaoAdicionar = document.getElementById(
            'btn-adicionar-documento'
        );

        let proximoIndice = {{ $documentosExtras->count() }};


        botaoAdicionar?.addEventListener('click', () => {
            const html = template.innerHTML.replaceAll(
                '__INDEX__',
                proximoIndice
            );

            container.insertAdjacentHTML(
                'beforeend',
                html
            );

            proximoIndice++;
        });


        container?.addEventListener('click', (event) => {
            const botao = event.target.closest(
                '[data-remover-documento]'
            );

            if (!botao) {
                return;
            }

            botao
                .closest('[data-documento-extra]')
                ?.remove();
        });
    });
</script>
