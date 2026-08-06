@php
    $options = $options->count() >= 2 ? $options : collect(['', '']);
@endphp

<div class="mb-3">
    <label class="form-label" for="titulo_do_campo{{ $campo?->id }}">Título do campo *</label>
    <input
        type="text"
        id="titulo_do_campo{{ $campo?->id }}"
        name="titulo_do_campo"
        class="form-control @error('titulo_do_campo') is-invalid @enderror"
        required
        value="{{ old('campo_id') == $campo?->id || $campo === null ? old('titulo_do_campo', $campo?->titulo) : $campo?->titulo }}"
    >
    @error('titulo_do_campo')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Tipo do campo *</label>
    <div class="field-type-grid">
        @foreach ($fieldTypes as $type => $definition)
            @php
                $disabled = $hasResponses && $selectedType !== $type;
            @endphp
            <label class="field-type-option @if($disabled) is-disabled @endif">
                <input
                    type="radio"
                    name="tipo_campo"
                    value="{{ $type }}"
                    @checked($selectedType === $type)
                    @disabled($disabled)
                    required
                >
                <i class="bi {{ $definition['icon'] }}"></i>
                <strong>{{ $definition['label'] }}</strong>
                <span class="field-type-description">{{ $definition['description'] }}</span>
            </label>
        @endforeach
    </div>
    @if ($hasResponses)
        <div class="form-text">Este campo já possui respostas, então o tipo atual foi preservado.</div>
    @endif
    @error('tipo_campo')
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="form-check form-switch mb-3">
    <input
        type="hidden"
        name="campo_obrigatorio"
        value="0"
    >
    <input
        class="form-check-input"
        type="checkbox"
        role="switch"
        id="campo_obrigatorio{{ $campo?->id }}"
        name="campo_obrigatorio"
        value="1"
        @checked(old('campo_id') == $campo?->id || $campo === null ? old('campo_obrigatorio', $campo?->obrigatorio) : $campo?->obrigatorio)
    >
    <label class="form-check-label" for="campo_obrigatorio{{ $campo?->id }}">Campo obrigatório</label>
</div>

<div class="mb-3" data-select-options>
    <div class="d-flex justify-content-between align-items-center mb-2">
        <label class="form-label mb-0">Opções de seleção</label>
        <button type="button" class="btn btn-sm btn-outline-primary" data-add-option>
            <i class="bi bi-plus-lg"></i> Adicionar opção
        </button>
    </div>

    <div data-options-list>
        @foreach ($options as $option)
            <div class="option-row input-group">
                <input type="text" class="form-control" name="select_text[]" value="{{ $option }}" placeholder="Nome da opção">
                <button type="button" class="btn btn-outline-danger" data-remove-option aria-label="Remover opção">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        @endforeach
    </div>

    <template data-option-template>
        <div class="option-row input-group">
            <input type="text" class="form-control" name="select_text[]" placeholder="Nome da opção">
            <button type="button" class="btn btn-outline-danger" data-remove-option aria-label="Remover opção">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    </template>

    @error('select_text')
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="border rounded p-3">
    <div class="form-check form-switch mb-2">
        <input type="hidden" name="para_todas" value="0">
        <input
            class="form-check-input"
            type="checkbox"
            role="switch"
            id="para_todas{{ $campo?->id }}"
            name="para_todas"
            value="1"
            data-all-categories
            @checked($allCategories)
        >
        <label class="form-check-label" for="para_todas{{ $campo?->id }}">
            Exibir para todas as categorias
        </label>
    </div>

    <div data-category-list>
        @if ($categorias->isEmpty())
            <div class="alert alert-warning mb-0">Crie pelo menos uma categoria antes de cadastrar campos.</div>
        @else
            <div class="row g-2">
                @foreach ($categorias as $categoria)
                    <div class="col-md-6">
                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="categoria{{ $campo?->id }}{{ $categoria->id }}"
                                name="categoria[]"
                                value="{{ $categoria->id }}"
                                @checked($selectedCategories->contains($categoria->id))
                            >
                            <label class="form-check-label" for="categoria{{ $campo?->id }}{{ $categoria->id }}">
                                {{ $categoria->nome }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @error('erroCategoria')
        <div class="text-danger small mt-2">{{ $message }}</div>
    @enderror
    @if($campo)
        @error('erroCategoriaEdit'.$campo->id)
            <div class="text-danger small mt-2">{{ $message }}</div>
        @enderror
    @endif
</div>
