@extends('coordenador.detalhesEvento')

@section('css')
<style>
    .modules-page {
        max-width: 980px;
    }

    .modules-header {
        margin-bottom: 1.25rem;
    }

    .modules-title {
        font-size: clamp(1.75rem, 3vw, 2.5rem);
        font-weight: 700;
        color: #111827;
        margin-bottom: .25rem;
    }

    .modules-subtitle {
        color: #6b7280;
        margin-bottom: 0;
    }

    .module-card {
        border: 1px solid #d9e2e7;
        border-radius: 8px;
        box-shadow: 0 1px 2px rgba(15, 23, 42, .04);
        overflow: hidden;
    }

    .module-card + .module-card {
        margin-top: .875rem;
    }

    .module-card__body {
        padding: 1rem 1.25rem;
    }

    .module-card__title {
        font-size: 1rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: .2rem;
    }

    .module-card__description {
        color: #6b7280;
        font-size: .92rem;
        margin-bottom: 0;
    }

    .module-options {
        border-top: 1px solid #edf2f4;
        padding: .85rem 1.25rem 1rem;
        background: #f8fafb;
    }

    .module-options.is-disabled {
        opacity: .6;
    }

    .module-option-help {
        color: #6b7280;
        cursor: help;
        margin-left: .25rem;
    }

    .modules-actions {
        position: sticky;
        bottom: 0;
        z-index: 2;
        margin-top: 1rem;
        padding: .85rem 0;
        background: linear-gradient(180deg, rgba(245, 245, 245, 0), #f5f5f5 28%);
    }
</style>
@endsection

@section('menu')
@php
    $fieldValue = function (array $item) use ($evento, $modulos): bool {
        $column = $item['column'] ?? $item['field'];

        return (bool) ($item['storage'] === 'evento'
            ? $evento->{$column}
            : $modulos->{$column});
    };
@endphp

<div id="divEditarEtiquetas" class="eventos" style="display: block">
    <div class="modules-page">
        <div class="modules-header">
            <h1 class="modules-title">Módulos do evento</h1>
            <p class="modules-subtitle">Ative os recursos que devem aparecer para participantes e ajuste suas opções.</p>
        </div>

        <form method="POST" action="{{ route('exibir.modulo', $evento->id) }}">
            @csrf

            @foreach ($moduleGroups as $module)
                @php
                    $enabled = $fieldValue($module['enabled']);
                    $enabledField = $module['enabled']['field'];
                @endphp

                <section class="module-card" data-module-card>
                    <div class="module-card__body">
                        <div class="row align-items-center g-3">
                            <div class="col-md">
                                <h2 class="module-card__title">{{ $module['title'] }}</h2>
                                <p class="module-card__description">{{ $module['description'] }}</p>
                            </div>
                            <div class="col-md-auto">
                                <input type="hidden" name="{{ $enabledField }}" value="0">
                                <div class="form-check form-switch mb-0">
                                    <input
                                        class="form-check-input module-toggle"
                                        type="checkbox"
                                        role="switch"
                                        id="{{ $enabledField }}"
                                        name="{{ $enabledField }}"
                                        value="1"
                                        @checked($enabled)
                                    >
                                    <label class="form-check-label fw-semibold" for="{{ $enabledField }}">
                                        {{ $module['enabled']['label'] }}
                                    </label>
                                </div>
                                @error($enabledField)
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    @if (count($module['options']) > 0)
                        <div class="module-options @unless($enabled) is-disabled @endunless" data-module-options>
                            <div class="row g-3">
                                @foreach ($module['options'] as $option)
                                    @php
                                        $optionValue = $enabled && $fieldValue($option);
                                    @endphp

                                    <div class="col-md-6">
                                        <input type="hidden" name="{{ $option['field'] }}" value="0">
                                        <div class="form-check">
                                            <input
                                                class="form-check-input module-option"
                                                type="checkbox"
                                                id="{{ $option['field'] }}"
                                                name="{{ $option['field'] }}"
                                                value="1"
                                                @checked($optionValue)
                                                @disabled(! $enabled)
                                            >
                                            <label class="form-check-label" for="{{ $option['field'] }}">
                                                {{ $option['label'] }}
                                            </label>
                                            @if (! empty($option['help']))
                                                <i
                                                    class="bi bi-info-circle module-option-help"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    title="{{ $option['help'] }}"
                                                ></i>
                                            @endif
                                        </div>
                                        @error($option['field'])
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </section>
            @endforeach

            <div class="modules-actions">
                <button type="submit" class="btn btn-primary w-100">
                    Salvar módulos
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('javascript')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (tooltipTriggerEl) {
        bootstrap.Tooltip.getOrCreateInstance(tooltipTriggerEl);
    });

    document.querySelectorAll('[data-module-card]').forEach(function (card) {
        var toggle = card.querySelector('.module-toggle');
        var optionsWrapper = card.querySelector('[data-module-options]');
        var options = card.querySelectorAll('.module-option');

        if (!toggle || !optionsWrapper) {
            return;
        }

        toggle.addEventListener('change', function () {
            optionsWrapper.classList.toggle('is-disabled', !toggle.checked);
            options.forEach(function (option) {
                option.disabled = !toggle.checked;

                if (!toggle.checked) {
                    option.checked = false;
                }
            });
        });
    });
});
</script>
@endsection
