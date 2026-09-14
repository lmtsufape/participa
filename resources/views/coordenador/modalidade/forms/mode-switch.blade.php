{{-- resources/views/coordenador/evento/modalidade/forms/mode-switch.blade.php --}}

@php
    $active = $active ?? 'visualizacao';

    $modalidade = $modalidade ?? $form->modalidade;
    $evento = $evento ?? $modalidade->evento;

    $showUrl = $showUrl ?? route('coord.visualizar.form', [
        'form_id' => $form->id,
        'modalidade_id' => $modalidade->id,
    ]);

    $editUrl = $editUrl ?? route('coord.modalidades.form.edit', [
        'evento' => $evento,
        'form' => $form,
    ]);

    $isVisualizacao = $active === 'visualizacao';
    $isEdicao = $active === 'edicao';
@endphp

<div
    class="d-inline-flex align-items-center gap-1 p-1 border rounded-3 bg-body-tertiary shadow-sm"
    role="group"
    aria-label="Modo do formulário"
>
    <a
        @unless($isVisualizacao)
            href="{{ $showUrl }}"
        @endunless

        class="btn btn-sm d-inline-flex align-items-center gap-2 px-3 py-2 rounded-2
            {{ $isVisualizacao ? 'btn-my-primary pe-none' : 'btn-light text-secondary border-0' }}"

        @if ($isVisualizacao)
            aria-current="page"
            aria-disabled="true"
            tabindex="-1"
        @endif
    >
        <i class="bi bi-eye"></i>
        <span>Visualização</span>
    </a>

    <a
        @unless($isEdicao)
            href="{{ $editUrl }}"
        @endunless

        class="btn btn-sm d-inline-flex align-items-center gap-2 px-3 py-2 rounded-2
            {{ $isEdicao ? 'btn-my-primary pe-none' : 'btn-light text-secondary border-0' }}"

        @if ($isEdicao)
            aria-current="page"
            aria-disabled="true"
            tabindex="-1"
        @endif
    >
        <i class="bi bi-pencil"></i>
        <span>Edição</span>
    </a>
</div>
