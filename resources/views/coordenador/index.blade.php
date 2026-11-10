@extends('layouts.app')


@section('content')

    <style>
        .event-card-img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        /* Ícone do cabeçalho */
        .filter-icon {
            width: 44px;
            height: 44px;
            flex-shrink: 0;
            background-color: #e7f1f3;
            color: #124858;
        }

        /* Filtros aplicados */
        .filter-chip {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 6px 12px;
            border-radius: 50rem;
            background-color: #e7f1f3;
            color: #124858;
            font-size: 0.8rem;
            font-weight: 500;
            max-width: 100%;
            overflow-wrap: anywhere;
        }

        .filter-chip a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: inherit;
            text-decoration: none;
            flex-shrink: 0;
        }

        .filter-chip a:hover {
            opacity: 0.65;
        }

        /* Separador do cabeçalho */
        @media (min-width: 992px) {
            .border-start-lg {
                border-left: 1px solid var(--bs-border-color);
            }
        }

        /* Consistência dos campos */
        .card .form-control,
        .card .form-select,
        .card .input-group-text {
            min-height: 40px;
        }

    </style>

    <div class="container">

        {{-- titulo da página --}}
        <x-ui.content-header
            title="{{__('Meus Eventos')}}"
            description="Visualize e gerencie os eventos que você criou."
            :href="route('evento.criar')"
            button-text="{{ __('Novo Evento') }}"
        />



        @php
            $filtros = request('filter', []);

            $filtrosAtivos = collect($filtros)
                ->filter(fn ($valor) => filled($valor))
                ->count();
        @endphp

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-3 p-lg-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="filter-icon d-flex align-items-center justify-content-center rounded-3">
                        <i class="bi bi-funnel fs-5"></i>
                    </div>

                    <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-1 gap-lg-3">
                        <h5 class="fw-bold mb-0">
                            Filtros de busca
                        </h5>

                        <span class="text-muted small border-start-lg ps-lg-3">
                            Encontre eventos e refine os resultados.
                        </span>
                    </div>
                </div>

                <form method="GET" action="{{ route('admin.eventos') }}">
                    <div class="row g-3">

                        <div class="col-12 col-lg-4">
                            <label for="search" class="form-label fw-semibold">
                                Buscar evento
                            </label>

                            <div class="input-group">
                                <span class="input-group-text bg-body-tertiary border-end-0">
                                    <i class="bi bi-search text-muted"></i>
                                </span>

                                <input
                                    type="search"
                                    id="search"
                                    name="filter[search]"
                                    class="form-control border-start-0"
                                    placeholder="Nome, descrição, e-mail ou ID"
                                    value="{{ request('filter.search') }}"
                                >
                            </div>
                        </div>

                        <div class="col-12 col-md-4 col-lg-3">
                            <label for="tipo" class="form-label fw-semibold">
                                Tipo
                            </label>

                            <select
                                id="tipo"
                                name="filter[tipo]"
                                class="form-select"
                            >
                                <option value="">Todos os tipos</option>

                                @foreach (\App\Enums\TipoEvento::cases() as $tipo)
                                    <option
                                        value="{{ $tipo->value }}"
                                        @selected(request('filter.tipo') === $tipo->value)
                                    >
                                        {{ $tipo->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-4 col-lg">
                            <label for="publicado" class="form-label fw-semibold">
                                Publicação
                            </label>

                            <select
                                id="publicado"
                                name="filter[publicado]"
                                class="form-select"
                            >
                                <option value="">Todos</option>

                                <option
                                    value="1"
                                    @selected(request('filter.publicado') === '1')
                                >
                                    Publicados
                                </option>

                                <option
                                    value="0"
                                    @selected(request('filter.publicado') === '0')
                                >
                                    Não publicados
                                </option>
                            </select>
                        </div>

                        <div class="col-12 col-md-4 col-lg">
                            <label for="classificacao" class="form-label fw-semibold">
                                Classificação
                            </label>

                            <select
                                id="classificacao"
                                name="filter[classificacao]"
                                class="form-select"
                            >
                                <option value="">Todos os eventos</option>

                                <option
                                    value="principal"
                                    @selected(request('filter.classificacao') === 'principal')
                                >
                                    Eventos principais
                                </option>

                                <option
                                    value="subevento"
                                    @selected(request('filter.classificacao') === 'subevento')
                                >
                                    Subeventos
                                </option>
                            </select>
                        </div>

                    </div>

                    <div class="border-top mt-4 pt-3">
                        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">

                            <div class="d-flex flex-wrap align-items-center gap-2">

                                <span class="text-muted small fw-medium me-1">
                                    Filtros aplicados:
                                </span>

                                @if ($filtrosAtivos === 0)
                                    <span class="text-muted small">
                                        Nenhum filtro aplicado
                                    </span>
                                @endif

                                @if (filled(request('filter.search')))
                                    <span class="filter-chip">
                                        Buscar: {{ request('filter.search') }}
                                        <a
                                            href="{{ route('admin.eventos', ['filter' => array_diff_key($filtros, ['search' => true])]) }}"
                                            aria-label="Remover filtro de busca"
                                        >
                                            <i class="bi bi-x-lg"></i>
                                        </a>
                                    </span>
                                @endif

                                @if (filled(request('filter.tipo')))
                                    <span class="filter-chip">
                                        Tipo: {{ \App\Enums\TipoEvento::tryFrom(request('filter.tipo'))?->label() ?? request('filter.tipo') }}
                                        <a
                                            href="{{ route('admin.eventos', ['filter' => array_diff_key($filtros, ['tipo' => true])]) }}"
                                            aria-label="Remover filtro de tipo"
                                        >
                                            <i class="bi bi-x-lg"></i>
                                        </a>
                                    </span>
                                @endif

                                @if (filled(request('filter.publicado')))
                                    <span class="filter-chip">
                                        Publicação:
                                        {{ request('filter.publicado') === '1' ? 'Publicados' : 'Não publicados' }}
                                        <a
                                            href="{{ route('admin.eventos', ['filter' => array_diff_key($filtros, ['publicado' => true])]) }}"
                                            aria-label="Remover filtro de publicação"
                                        >
                                            <i class="bi bi-x-lg"></i>
                                        </a>
                                    </span>
                                @endif

                                @if (filled(request('filter.classificacao')))
                                    <span class="filter-chip">
                                        Classificação:
                                        {{ request('filter.classificacao') === 'principal' ? 'Eventos principais' : 'Subeventos' }}
                                        <a
                                            href="{{ route('admin.eventos', ['filter' => array_diff_key($filtros, ['classificacao' => true])]) }}"
                                            aria-label="Remover filtro de classificação"
                                        >
                                            <i class="bi bi-x-lg"></i>
                                        </a>
                                    </span>
                                @endif

                            </div>

                            <div class="d-flex gap-2 flex-shrink-0">

                                <button
                                    type="submit"
                                    class="btn btn-primary px-4"
                                >
                                    <i class="bi bi-search me-2"></i>
                                    Buscar
                                </button>

                                <a
                                    href="{{ route('admin.eventos') }}"
                                    class="btn btn-outline-secondary px-3"
                                >
                                    <i class="bi bi-arrow-counterclockwise me-1"></i>
                                    Limpar
                                </a>

                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>

        <div class="row g-4">
            @foreach ($eventos as $evento)
                @if (! $evento->deletado)
                    @can('isCoordenadorOrCoordenadorDasComissoes', $evento)
                        <div class="col-12 col-md-6 col-xl-4">
                            <x-eventos.card :evento="$evento"/>
                        </div>
                    @endcan
                @endif
            @endforeach
        </div>
        <div class="d-flex justify-content-center">
            {{ $eventos->onEachSide(2)->links('vendor.pagination.participa') }}
        </div>
        @foreach ($eventos as $evento)
            @if ($evento->deletado == false)
                <!-- Modal de exclusão do evento -->
                <x-modal-excluir-evento :evento="$evento"/>
                <!-- fim do modal -->
            @endif
        @endforeach

    </div>

@endsection
