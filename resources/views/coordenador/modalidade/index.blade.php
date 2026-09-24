@extends('layouts.app')
@section('sidebar')
@endsection
@section('content')
@error('excluirModalidade')
@include('componentes.mensagens')
@enderror

<style>
    /* Card */

    .modalidades-card {
        border-radius: 0.75rem;
    }


    /*
    |--------------------------------------------------------------------------
    | Tabela
    |--------------------------------------------------------------------------
    */

    .table-modalidades {
        --bs-table-bg: transparent;
        --bs-table-hover-bg: #f8fafc;
    }


    /* Cabeçalho */

    .table-modalidades thead th {
        padding: 0.9rem 1rem;

        background-color: #f8f9fa;

        border-top: 0;
        border-bottom: 1px solid #e5e7eb;

        color: #6c757d;

        font-size: 0.75rem;
        font-weight: 600;

        text-transform: uppercase;
        letter-spacing: 0.04em;

        white-space: nowrap;
    }


    /* Mantém os cantos arredondados sem overflow-hidden */

    .table-modalidades thead th:first-child {
        border-top-left-radius: 0.75rem;
    }

    .table-modalidades thead th:last-child {
        border-top-right-radius: 0.75rem;
    }


    /* Linhas */

    .table-modalidades tbody td {
        padding: 1.15rem 1rem;

        border-top: 0;
        border-bottom: 1px solid #edf0f2;

        vertical-align: middle;
    }

    .table-modalidades tbody tr:last-child td {
        border-bottom: 0;
    }


    /*
    |--------------------------------------------------------------------------
    | Modalidade
    |--------------------------------------------------------------------------
    */

    .modalidade-info {
        display: flex;
        flex-direction: column;

        gap: 0.2rem;
    }

    .modalidade-nome {
        color: #212529;

        font-size: 0.9375rem;
        font-weight: 600;

        line-height: 1.3;
    }


    /*
    |--------------------------------------------------------------------------
    | Cronograma
    |--------------------------------------------------------------------------
    */

    .cronograma-grid {
        display: grid;

        grid-template-columns: repeat(3, minmax(135px, 1fr));

        gap: 1rem;
    }

    .cronograma-etapa {
        min-width: 0;
    }

    .cronograma-titulo {
        margin-bottom: 0.3rem;

        color: #6c757d;

        font-size: 0.75rem;
        font-weight: 500;

        text-transform: uppercase;
    }

    .cronograma-data {
        display: flex;
        align-items: center;

        gap: 0.4rem;

        color: #343a40;

        font-size: 0.8125rem;
        font-weight: 500;

        white-space: nowrap;
    }

    .cronograma-data i {
        color: #adb5bd;

        font-size: 0.7rem;
    }


    /*
    |--------------------------------------------------------------------------
    | Resultado
    |--------------------------------------------------------------------------
    */

    .resultado-data {
        display: inline-flex;
        align-items: center;

        gap: 0.45rem;

        color: #495057;

        font-size: 0.8125rem;
        font-weight: 500;

        white-space: nowrap;
    }

    .resultado-data i {
        color: #6c757d;
    }


    /*
    |--------------------------------------------------------------------------
    | Drag
    |--------------------------------------------------------------------------
    */

    .drag-handle {
        display: inline-flex;

        align-items: center;
        justify-content: center;

        width: 32px;
        height: 34px;

        padding: 0;

        border: 0;
        border-radius: 0.4rem;

        background: transparent;

        color: #adb5bd;

        cursor: grab;
    }

    .drag-handle:hover {
        background-color: #f1f3f5;

        color: #495057;
    }

    .drag-handle:active {
        cursor: grabbing;
    }


    /*
    |--------------------------------------------------------------------------
    | Ações
    |--------------------------------------------------------------------------
    */

    .action-btn {
        display: inline-flex;

        align-items: center;
        justify-content: center;

        width: 34px;
        height: 34px;

        padding: 0;

        color: #495057;
    }

    .action-btn:hover {
        background-color: #f1f3f5;
    }


    /*
    |--------------------------------------------------------------------------
    | Dropdown
    |--------------------------------------------------------------------------
    */

    .table-modalidades .dropdown {
        position: relative;
    }

    .table-modalidades .dropdown-menu {
        min-width: 240px;

        padding: 0.4rem;

        border: 1px solid #e5e7eb;

        border-radius: 0.65rem;

        z-index: 1055;
    }

    .table-modalidades .dropdown-item {
        padding: 0.55rem 0.7rem;

        border-radius: 0.4rem;

        font-size: 0.875rem;
    }

    .table-modalidades .dropdown-header {
        padding: 0.5rem 0.7rem;

        color: #8a9199;

        font-size: 0.7rem;
        font-weight: 600;

        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    /* Indicador padrão */
    .etapa-indicador {
        display: inline-block;
        flex-shrink: 0;

        width: 7px;
        height: 7px;

        border-radius: 50%;
    }


    /* Finalizado */
    .etapa-finalizada {
        color: #198754;

        font-size: 0.8rem;

        flex-shrink: 0;
    }

    .cronograma-titulo-finalizado {
        color: #198754;
    }

    .cronograma-data-finalizada {
        color: #868e96;
    }


    /* Ainda não iniciado */
    .etapa-pendente {
        color: #adb5bd;

        font-size: 0.75rem;

        flex-shrink: 0;
    }


    /* Datas não configuradas */
    .etapa-indefinida {
        color: #ced4da;

        font-size: 0.75rem;

        flex-shrink: 0;
    }


    /* Status textual */
    .cronograma-status-finalizado,
    .cronograma-status-andamento {
        margin-left: 0.15rem;

        font-size: 0.625rem;
        font-weight: 600;

        text-transform: none;
        letter-spacing: 0;

        white-space: nowrap;
    }

    .cronograma-status-finalizado {
        color: #198754;
    }

    .cronograma-status-andamento {
        color: #6c757d;
    }


    /*
    |--------------------------------------------------------------------------
    | Overflow
    |--------------------------------------------------------------------------
    */

    /*
    * No desktop, permitimos que o dropdown ultrapasse os limites
    * da tabela/card.
    */
    @media (min-width: 992px) {

        .modalidades-table-wrapper {
            overflow: visible;
        }

    }


    /*
    * Em telas menores continuamos permitindo scroll horizontal.
    */
    @media (max-width: 991.98px) {

        .modalidades-table-wrapper {
            overflow-x: auto;
            overflow-y: auto;
        }

    }
    .cronograma-titulo {
        display: flex;
        align-items: center;
        gap: 0.4rem;

        margin-bottom: 0.3rem;

        color: #6c757d;

        font-size: 0.75rem;
        font-weight: 500;

        text-transform: uppercase;
    }

    .etapa-indicador {
        display: inline-block;
        flex-shrink: 0;

        width: 7px;
        height: 7px;

        border-radius: 50%;
    }

    /*
    |--------------------------------------------------------------------------
    | Responsividade do cronograma
    |--------------------------------------------------------------------------
    */

    @media (max-width: 1200px) {

        .cronograma-grid {
            grid-template-columns: 1fr;

            gap: 0.6rem;
        }

    }
</style>

<div class="modalidades container">

    <x-admin.content-header
        title="Listar Modalidades"
        description="Gerencie as modalidades do evento e acompanhe seus prazos e configurações."
        :href="route('coord.modalidade.create', [
            'eventoId' => $evento->id,
        ])"
        button-text="Nova modalidade"
    />
    <div class="card border-0 shadow-sm modalidades-card">

        <div class="table-responsive modalidades-table-wrapper">

            <table class="table table-modalidades align-middle mb-0">

                <thead>
                    <tr>
                        <th style="width: 48px;"></th>

                        <th style="min-width: 220px;">
                            Modalidade
                        </th>

                        <th style="min-width: 500px;">
                            Cronograma
                        </th>

                        <th style="width: 130px;">
                            Resultado
                        </th>

                        <th class="text-end" style="width: 110px;">
                            Ações
                        </th>
                    </tr>
                </thead>

                <tbody id="modalidades-tbody">

                    @foreach($modalidades as $modalidade)

                        @php
                            $agora = now();

                            $statusPeriodo = function ($inicio, $fim) use ($agora) {
                                if (!$inicio || !$fim) {
                                    return 'indefinido';
                                }

                                $inicioPeriodo = $inicio->copy()->startOfDay();
                                $fimPeriodo = $fim->copy()->endOfDay();

                                if ($agora->lt($inicioPeriodo)) {
                                    return 'pendente';
                                }

                                if ($agora->gt($fimPeriodo)) {
                                    return 'finalizado';
                                }

                                return 'andamento';
                            };

                            $statusSubmissao = $statusPeriodo(
                                $modalidade->inicioSubmissao,
                                $modalidade->fimSubmissao
                            );

                            $statusAvaliacao = $statusPeriodo(
                                $modalidade->inicioRevisao,
                                $modalidade->fimRevisao
                            );

                            $statusCorrecao = $statusPeriodo(
                                $modalidade->inicioCorrecao,
                                $modalidade->fimCorrecao
                            );
                        @endphp

                        <tr data-id="{{ $modalidade->id }}">

                            {{-- Ordenação --}}
                            <td class="text-center">

                                <button
                                    type="button"
                                    class="drag-handle handle"
                                    title="Arrastar para reordenar"
                                    aria-label="Arrastar para reordenar"
                                >
                                    <i class="bi bi-grip-vertical"></i>
                                </button>

                            </td>


                            {{-- Modalidade --}}
                            <td>

                                <div class="modalidade-info">

                                    <span class="modalidade-nome">
                                        {{ $modalidade->nome }}
                                    </span>

                                </div>

                            </td>


                            {{-- Cronograma --}}
                            <td>

                                <div class="cronograma-grid">

                                    {{-- Submissão --}}
                                    <div class="cronograma-etapa">

                                        <div @class([
                                            'cronograma-titulo',
                                            'cronograma-titulo-finalizado' => $statusSubmissao === 'finalizado',
                                            'cronograma-titulo-andamento' => $statusSubmissao === 'andamento',
                                        ])>

                                            @switch($statusSubmissao)

                                                @case('finalizado')
                                                    <i
                                                        class="bi bi-check-circle-fill etapa-finalizada"
                                                        title="Período finalizado"
                                                    ></i>
                                                    @break

                                                @case('andamento')
                                                    <span
                                                        class="etapa-indicador bg-primary"
                                                        title="Em andamento"
                                                    ></span>
                                                    @break

                                                @case('pendente')
                                                    <i
                                                        class="bi bi-circle etapa-pendente"
                                                        title="Ainda não iniciado"
                                                    ></i>
                                                    @break

                                                @default
                                                    <i
                                                        class="bi bi-dash-circle etapa-indefinida"
                                                        title="Período não definido"
                                                    ></i>

                                            @endswitch

                                            <span>
                                                Submissão
                                            </span>

                                            @if($statusSubmissao === 'finalizado')
                                                <span class="cronograma-status-finalizado">
                                                    Finalizado
                                                </span>
                                            @elseif($statusSubmissao === 'andamento')
                                                <span class="cronograma-status-andamento">
                                                    Em andamento
                                                </span>
                                            @endif

                                        </div>

                                        <div @class([
                                            'cronograma-data',
                                            'cronograma-data-finalizada' => $statusSubmissao === 'finalizado',
                                        ])>

                                            {{ $modalidade->inicioSubmissao?->format('d/m/Y') ?? '—' }}

                                            <i class="bi bi-arrow-right"></i>

                                            {{ $modalidade->fimSubmissao?->format('d/m/Y') ?? '—' }}

                                        </div>

                                    </div>


                                    {{-- Avaliação --}}
                                    <div class="cronograma-etapa">

                                        <div @class([
                                            'cronograma-titulo',
                                            'cronograma-titulo-finalizado' => $statusAvaliacao === 'finalizado',
                                            'cronograma-titulo-andamento' => $statusAvaliacao === 'andamento',
                                        ])>

                                            @switch($statusAvaliacao)

                                                @case('finalizado')
                                                    <i
                                                        class="bi bi-check-circle-fill etapa-finalizada"
                                                        title="Período finalizado"
                                                    ></i>
                                                    @break

                                                @case('andamento')
                                                    <span
                                                        class="etapa-indicador bg-info"
                                                        title="Em andamento"
                                                    ></span>
                                                    @break

                                                @case('pendente')
                                                    <i
                                                        class="bi bi-circle etapa-pendente"
                                                        title="Ainda não iniciado"
                                                    ></i>
                                                    @break

                                                @default
                                                    <i
                                                        class="bi bi-dash-circle etapa-indefinida"
                                                        title="Período não definido"
                                                    ></i>

                                            @endswitch

                                            <span>
                                                Avaliação
                                            </span>

                                            @if($statusAvaliacao === 'finalizado')
                                                <span class="cronograma-status-finalizado">
                                                    Finalizado
                                                </span>
                                            @elseif($statusAvaliacao === 'andamento')
                                                <span class="cronograma-status-andamento">
                                                    Em andamento
                                                </span>
                                            @endif

                                        </div>

                                        <div @class([
                                            'cronograma-data',
                                            'cronograma-data-finalizada' => $statusAvaliacao === 'finalizado',
                                        ])>

                                            {{ $modalidade->inicioRevisao?->format('d/m/Y') ?? '—' }}

                                            <i class="bi bi-arrow-right"></i>

                                            {{ $modalidade->fimRevisao?->format('d/m/Y') ?? '—' }}

                                        </div>

                                    </div>


                                    {{-- Correção --}}
                                    <div class="cronograma-etapa">

                                        <div @class([
                                            'cronograma-titulo',
                                            'cronograma-titulo-finalizado' => $statusCorrecao === 'finalizado',
                                            'cronograma-titulo-andamento' => $statusCorrecao === 'andamento',
                                        ])>

                                            @switch($statusCorrecao)

                                                @case('finalizado')
                                                    <i
                                                        class="bi bi-check-circle-fill etapa-finalizada"
                                                        title="Período finalizado"
                                                    ></i>
                                                    @break

                                                @case('andamento')
                                                    <span
                                                        class="etapa-indicador bg-warning"
                                                        title="Em andamento"
                                                    ></span>
                                                    @break

                                                @case('pendente')
                                                    <i
                                                        class="bi bi-circle etapa-pendente"
                                                        title="Ainda não iniciado"
                                                    ></i>
                                                    @break

                                                @default
                                                    <i
                                                        class="bi bi-dash-circle etapa-indefinida"
                                                        title="Período não definido"
                                                    ></i>

                                            @endswitch

                                            <span>
                                                Correção
                                            </span>

                                            @if($statusCorrecao === 'finalizado')
                                                <span class="cronograma-status-finalizado">
                                                    Finalizado
                                                </span>
                                            @elseif($statusCorrecao === 'andamento')
                                                <span class="cronograma-status-andamento">
                                                    Em andamento
                                                </span>
                                            @endif

                                        </div>

                                        <div @class([
                                            'cronograma-data',
                                            'cronograma-data-finalizada' => $statusCorrecao === 'finalizado',
                                        ])>

                                            {{ $modalidade->inicioCorrecao?->format('d/m/Y') ?? '—' }}

                                            <i class="bi bi-arrow-right"></i>

                                            {{ $modalidade->fimCorrecao?->format('d/m/Y') ?? '—' }}

                                        </div>

                                    </div>

                                </div>

                            </td>


                            {{-- Resultado --}}
                            <td>

                                @if($modalidade->inicioResultado)

                                    <div class="resultado-data">

                                        <i class="bi bi-calendar-check"></i>

                                        <span>
                                            {{ $modalidade->inicioResultado->format('d/m/Y') }}
                                        </span>

                                    </div>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- Ações --}}
                            <td class="text-end">

                                <div class="d-inline-flex align-items-center gap-1">
                                    <a class="btn btn-sm btn-light border action-btn" href="{{ route('coord.modalidade.edit', ['modalidade_id' => $modalidade->id]) }}">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <div class="dropdown">

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-light border action-btn"
                                            data-bs-toggle="dropdown"
                                            data-bs-boundary="viewport"
                                            aria-expanded="false"
                                            title="Mais ações"
                                        >
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>


                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">

                                            @can('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento)

                                                <li>
                                                    <h6 class="dropdown-header">
                                                        Avaliação
                                                    </h6>
                                                </li>

                                                <li>
                                                    <a
                                                        href="{{ route('coord.forms', [
                                                            'eventoId' => $evento->id,
                                                            'modalidade_id' => $modalidade->id
                                                        ]) }}"
                                                        class="dropdown-item"
                                                    >
                                                        <i class="bi bi-ui-checks me-2"></i>
                                                        Formulário de avaliação
                                                    </a>
                                                </li>

                                                <li>
                                                    <a
                                                        href="{{ route('coord.cadastrarCriterio', [
                                                            'eventoId' => $evento->id
                                                        ]) }}"
                                                        class="dropdown-item"
                                                    >
                                                        <i class="bi bi-plus-circle me-2"></i>
                                                        Cadastrar critérios
                                                    </a>
                                                </li>

                                                <li>
                                                    <a
                                                        href="{{ route('coord.listarCriterios', [
                                                            'eventoId' => $evento->id
                                                        ]) }}"
                                                        class="dropdown-item"
                                                    >
                                                        <i class="bi bi-list-check me-2"></i>
                                                        Listar critérios
                                                    </a>
                                                </li>

                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>

                                            @endcan

                                            <li>
                                                <button
                                                    type="button"
                                                    class="dropdown-item text-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalExcluirModalidade{{ $modalidade->id }}"
                                                >
                                                    <i class="bi bi-trash me-2"></i>
                                                    Excluir modalidade
                                                </button>
                                            </li>

                                        </ul>

                                    </div>

                                </div>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>


    @foreach ($modalidades as $index => $modalidade)
    <!-- Modal excluir modalida -->

    <!-- Modal de exclusão da área -->
    <div class="modal fade" id="modalExcluirModalidade{{$modalidade->id}}" tabindex="-1" role="dialog" aria-labelledby="#label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #114048ff; color: white;">
                    <h5 class="modal-title" id="#label">Confirmação</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('modalidade.destroy', ['id' => $modalidade->id])}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        Tem certeza que deseja excluir essa modalidade?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                        <button type="submit" class="btn btn-primary">Sim</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


</div>
@endforeach
{{-- Fim Modal --}}

@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
@parent
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const tbody = document.getElementById('modalidades-tbody');

        Sortable.create(tbody, {
            handle: '.handle',
            animation: 150,
            onEnd: () => {
                // monta [{id, position}, …]
                const order = Array.from(tbody.querySelectorAll('tr'))
                    .map((tr, idx) => ({
                        id: tr.dataset.id,
                        position: idx + 1
                    }));

                fetch("{{ route('modalidades.reorder') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ order })
                })
                    .then(r => r.json())
                    .then(json => {
                        if (json.status !== 'ok') {
                            alert('Erro ao salvar a nova ordem de modalidades.');
                        }
                    })
                    .catch(() => alert('Erro ao salvar a nova ordem de modalidades.'));
            }
        });
    });

</script>
@endsection
