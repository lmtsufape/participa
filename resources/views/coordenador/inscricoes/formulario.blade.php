@extends('coordenador.detalhesEvento')

@section('css')
<style>
    .registration-form-builder {
        max-width: 1120px;
    }

    .builder-header {
        margin-bottom: 1.25rem;
    }

    .builder-title {
        font-size: clamp(1.75rem, 3vw, 2.5rem);
        font-weight: 700;
        color: #111827;
        margin-bottom: .25rem;
    }

    .builder-subtitle {
        color: #6b7280;
        margin-bottom: 0;
    }

    .builder-panel,
    .field-card {
        border: 1px solid #d9e2e7;
        border-radius: 8px;
        box-shadow: 0 1px 2px rgba(15, 23, 42, .04);
    }

    .builder-panel {
        background: #fff;
        padding: 1.15rem 1.25rem;
    }

    .field-card {
        background: #fff;
        padding: 1rem;
        height: 100%;
    }

    .field-card__title {
        font-size: 1rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: .2rem;
    }

    .field-card__meta,
    .field-card__categories {
        color: #6b7280;
        font-size: .9rem;
        margin-bottom: .25rem;
    }

    .field-type-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(155px, 1fr));
        gap: .75rem;
    }

    .field-type-option {
        border: 1px solid #d9e2e7;
        border-radius: 8px;
        padding: .8rem;
        cursor: pointer;
        background: #fff;
    }

    .field-type-option:has(input:checked) {
        border-color: #0d6efd;
        box-shadow: 0 0 0 .2rem rgba(13, 110, 253, .14);
    }

    .field-type-option input {
        margin-right: .35rem;
    }

    .field-type-option.is-disabled {
        cursor: not-allowed;
        opacity: .65;
    }

    .field-type-description {
        display: block;
        color: #6b7280;
        font-size: .82rem;
        margin-top: .25rem;
    }

    .option-row + .option-row {
        margin-top: .5rem;
    }

    .empty-state {
        border: 1px dashed #b8c4cc;
        border-radius: 8px;
        padding: 2rem;
        text-align: center;
        color: #6b7280;
        background: #fbfcfd;
    }
</style>
@endsection

@section('menu')
@php
    $typeLabel = fn (string $type) => $fieldTypes[$type]['label'] ?? ucfirst($type);
@endphp

<div class="registration-form-builder">
    <div class="builder-header d-flex flex-column flex-md-row justify-content-between gap-3">
        <div>
            <h1 class="builder-title">Formulário de inscrição</h1>
            <p class="builder-subtitle">Crie campos extras para coletar informações de acordo com a categoria do participante.</p>
        </div>

        <div class="align-self-md-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCriarCampo">
                <i class="bi bi-plus-lg"></i> Novo campo
            </button>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="builder-panel">
        <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-3">
            <div>
                <h2 class="h5 mb-1">Campos cadastrados</h2>
                <p class="builder-subtitle">{{ $campos->count() }} campo(s) extra(s) neste evento.</p>
            </div>
        </div>

        @if ($campos->isEmpty())
            <div class="empty-state">
                Nenhum campo extra salvo.
            </div>
        @else
            <div class="row g-3">
                @foreach ($campos as $campo)
                    <div class="col-md-6 col-xl-4">
                        <article class="field-card">
                            <div class="d-flex justify-content-between gap-2">
                                <div>
                                    <h3 class="field-card__title">{{ $campo->titulo }}</h3>
                                    <p class="field-card__meta">
                                        <i class="bi {{ $fieldTypes[$campo->tipo]['icon'] ?? 'bi-ui-checks' }}"></i>
                                        {{ $typeLabel($campo->tipo) }}
                                        <span class="mx-1">•</span>
                                        {{ $campo->obrigatorio ? 'Obrigatório' : 'Opcional' }}
                                    </p>
                                </div>
                            </div>

                            <p class="field-card__categories">
                                Categorias:
                                @if ($categorias->count() > 0 && $campo->categorias->count() === $categorias->count())
                                    todas
                                @else
                                    {{ $campo->categorias->pluck('nome')->join(', ') ?: 'nenhuma' }}
                                @endif
                            </p>

                            @if ($campo->tipo === 'select')
                                <p class="field-card__categories">
                                    Opções: {{ $campo->opcoes->pluck('nome')->join(', ') ?: 'sem opções' }}
                                </p>
                            @endif

                            @if ($campo->inscricoes_feitas_count > 0)
                                <p class="field-card__categories mb-3">
                                    {{ $campo->inscricoes_feitas_count }} resposta(s). O tipo não pode ser alterado.
                                </p>
                            @endif

                            <div class="d-flex gap-2 mt-3">
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalCampoEdit{{ $campo->id }}">
                                    <i class="bi bi-pencil"></i> Editar
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalCampoDelete{{ $campo->id }}">
                                    <i class="bi bi-trash"></i> Excluir
                                </button>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<div class="modal fade" id="modalCriarCampo" tabindex="-1" aria-labelledby="modalCriarCampoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <form class="field-form" action="{{ route('campo.formulario.store') }}" method="POST">
                @csrf
                <input type="hidden" name="evento_id" value="{{ $evento->id }}">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalCriarCampoLabel">Novo campo do formulário</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>

                <div class="modal-body">
                    @include('coordenador.inscricoes.partials.campo-form', [
                        'campo' => null,
                        'categorias' => $categorias,
                        'fieldTypes' => $fieldTypes,
                        'selectedType' => old('tipo_campo', 'text'),
                        'selectedCategories' => collect(old('categoria', []))->map(fn ($id) => (int) $id),
                        'allCategories' => old('para_todas', '1') === '1',
                        'options' => collect(old('select_text', ['', ''])),
                        'hasResponses' => false,
                    ])
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar campo</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach ($campos as $campo)
    <div class="modal fade" id="modalCampoEdit{{ $campo->id }}" tabindex="-1" aria-labelledby="modalCampoEdit{{ $campo->id }}Label" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <form class="field-form" action="{{ route('campo.edit', ['id' => $campo->id]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="evento_id" value="{{ $evento->id }}">
                    <input type="hidden" name="campo_id" value="{{ $campo->id }}">

                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCampoEdit{{ $campo->id }}Label">Editar {{ $campo->titulo }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>

                    <div class="modal-body">
                        @include('coordenador.inscricoes.partials.campo-form', [
                            'campo' => $campo,
                            'categorias' => $categorias,
                            'fieldTypes' => $fieldTypes,
                            'selectedType' => old('campo_id') == $campo->id ? old('tipo_campo', $campo->tipo) : $campo->tipo,
                            'selectedCategories' => old('campo_id') == $campo->id
                                ? collect(old('categoria', []))->map(fn ($id) => (int) $id)
                                : $campo->categorias->pluck('id'),
                            'allCategories' => old('campo_id') == $campo->id
                                ? old('para_todas') === '1'
                                : $categorias->count() > 0 && $campo->categorias->count() === $categorias->count(),
                            'options' => old('campo_id') == $campo->id
                                ? collect(old('select_text', ['', '']))
                                : ($campo->opcoes->isNotEmpty() ? $campo->opcoes->pluck('nome') : collect(['', ''])),
                            'hasResponses' => $campo->inscricoes_feitas_count > 0,
                        ])
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalCampoDelete{{ $campo->id }}" tabindex="-1" aria-labelledby="modalCampoDelete{{ $campo->id }}Label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('campo.destroy', ['id' => $campo->id]) }}" method="POST">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCampoDelete{{ $campo->id }}Label">Excluir campo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>

                    <div class="modal-body">
                        @if($campo->inscricoes_feitas_count > 0)
                            <div class="alert alert-danger">
                                Este campo já possui respostas. Ao excluir, as respostas preenchidas também serão removidas.
                            </div>
                        @endif

                        Tem certeza que deseja excluir <strong>{{ $campo->titulo }}</strong>?
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Excluir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection

@section('javascript')
<script>
document.addEventListener('DOMContentLoaded', function () {
    function syncForm(form) {
        var selectedType = form.querySelector('input[name="tipo_campo"]:checked');
        var selectOptions = form.querySelector('[data-select-options]');
        var allCategories = form.querySelector('[data-all-categories]');
        var categories = form.querySelector('[data-category-list]');

        if (selectOptions) {
            selectOptions.hidden = !selectedType || selectedType.value !== 'select';
        }

        if (categories && allCategories) {
            categories.hidden = allCategories.checked;
        }
    }

    document.querySelectorAll('.field-form').forEach(function (form) {
        syncForm(form);

        form.addEventListener('change', function (event) {
            if (event.target.name === 'tipo_campo' || event.target.matches('[data-all-categories]')) {
                syncForm(form);
            }
        });

        form.addEventListener('click', function (event) {
            var addButton = event.target.closest('[data-add-option]');
            var removeButton = event.target.closest('[data-remove-option]');

            if (addButton) {
                var list = form.querySelector('[data-options-list]');
                var template = form.querySelector('[data-option-template]');
                list.insertAdjacentHTML('beforeend', template.innerHTML);
            }

            if (removeButton) {
                var rows = form.querySelectorAll('.option-row');

                if (rows.length > 2) {
                    removeButton.closest('.option-row').remove();
                }
            }
        });
    });

    @if ($errors->any() && old('campo_id') === null)
        bootstrap.Modal.getOrCreateInstance(document.getElementById('modalCriarCampo')).show();
    @endif
});
</script>
@endsection
