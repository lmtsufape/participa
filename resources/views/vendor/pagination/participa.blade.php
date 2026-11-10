@if ($paginator->hasPages())
    <div class="participa-pagination">

        {{-- Informações dos registros --}}
        <div class="participa-pagination-info">
            <div class="participa-pagination-icon">
                <i class="bi bi-layers"></i>
            </div>

            <span>
                Exibindo
                <strong>{{ $paginator->firstItem() ?? 0 }} a {{ $paginator->lastItem() ?? 0 }}</strong>
                de
                <strong>{{ number_format($paginator->total(), 0, ',', '.') }}</strong>
                registros
            </span>
        </div>

        {{-- Navegação --}}
        @if ($paginator->hasPages())
            <nav class="participa-pagination-nav" aria-label="Navegação entre páginas">
                <ul class="pagination mb-0">

                    {{-- Primeira página --}}
                    <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                        @if ($paginator->onFirstPage())
                            <span class="page-link" aria-disabled="true">
                                <i class="bi bi-chevron-double-left"></i>
                            </span>
                        @else
                            <a class="page-link"
                               href="{{ $paginator->url(1) }}"
                               aria-label="Primeira página">
                                <i class="bi bi-chevron-double-left"></i>
                            </a>
                        @endif
                    </li>

                    {{-- Página anterior --}}
                    <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                        @if ($paginator->onFirstPage())
                            <span class="page-link" aria-disabled="true">
                                <i class="bi bi-chevron-left"></i>
                            </span>
                        @else
                            <a class="page-link"
                               href="{{ $paginator->previousPageUrl() }}"
                               rel="prev"
                               aria-label="Página anterior">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        @endif
                    </li>

                    {{-- Números das páginas --}}
                    @foreach ($elements as $element)

                        {{-- Separador --}}
                        @if (is_string($element))
                            <li class="page-item disabled">
                                <span class="page-link participa-pagination-ellipsis">
                                    {{ $element }}
                                </span>
                            </li>
                        @endif

                        {{-- Páginas --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li class="page-item active" aria-current="page">
                                        <span class="page-link">
                                            {{ $page }}
                                        </span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link"
                                           href="{{ $url }}"
                                           aria-label="Ir para página {{ $page }}">
                                            {{ $page }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        @endif

                    @endforeach

                    {{-- Próxima página --}}
                    <li class="page-item {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
                        @if ($paginator->hasMorePages())
                            <a class="page-link"
                               href="{{ $paginator->nextPageUrl() }}"
                               rel="next"
                               aria-label="Próxima página">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        @else
                            <span class="page-link" aria-disabled="true">
                                <i class="bi bi-chevron-right"></i>
                            </span>
                        @endif
                    </li>

                    {{-- Última página --}}
                    <li class="page-item {{ $paginator->onLastPage() ? 'disabled' : '' }}">
                        @if ($paginator->onLastPage())
                            <span class="page-link" aria-disabled="true">
                                <i class="bi bi-chevron-double-right"></i>
                            </span>
                        @else
                            <a class="page-link"
                               href="{{ $paginator->url($paginator->lastPage()) }}"
                               aria-label="Última página">
                                <i class="bi bi-chevron-double-right"></i>
                            </a>
                        @endif
                    </li>

                </ul>
            </nav>
        @endif

    </div>
@endif

<style>

    /* Container principal */
    .participa-pagination {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;

        padding: 20px 24px;
        margin: 24px 0;

        background-color: #fff;
        border: 1px solid #dce5e8;
        border-radius: 14px;

        box-shadow: 0 4px 16px rgba(20, 68, 84, 0.05);
    }

    /* Informações */
    .participa-pagination-info {
        display: flex;
        align-items: center;
        gap: 16px;

        padding-right: 28px;
        border-right: 1px solid #dce5e8;

        color: #344054;
        font-size: 14px;
        white-space: nowrap;
    }

    .participa-pagination-info strong {
        color: #101828;
        font-weight: 600;
    }

    /* Ícone */
    .participa-pagination-icon {
        display: flex;
        align-items: center;
        justify-content: center;

        width: 42px;
        height: 42px;
        flex-shrink: 0;

        background-color: #e1f3f5;
        color: #144454;
        border-radius: 10px;
        font-size: 20px;
    }

    /* Navegação */
    .participa-pagination-nav {
        min-width: 0;
    }

    .participa-pagination .pagination {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
    }

    /* Botões */
    .participa-pagination .page-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        min-width: 44px;
        height: 44px;
        padding: 0 12px;

        background-color: #fff;
        border: 1px solid #d6e0e4;
        border-radius: 10px !important;

        color: #144454;
        font-size: 15px;
        font-weight: 500;
        text-decoration: none;

        box-shadow: none;
        transition: all 0.2s ease;
    }

    /* Hover */
    .participa-pagination .page-item:not(.active):not(.disabled)
    .page-link:hover {
        color: #144454;
        background-color: #eef6f7;
        border-color: #9bb9c2;
    }

    /* Página ativa */
    .participa-pagination .page-item.active .page-link {
        background-color: #144454;
        border-color: #144454;
        color: #fff;
        font-weight: 600;
    }

    /* Botões desabilitados */
    .participa-pagination .page-item.disabled .page-link {
        background-color: #f8fafb;
        border-color: #e4e9ec;
        color: #a5b4bd;
        cursor: default;
    }

    /* Reticências */
    .participa-pagination .page-item.disabled
    .participa-pagination-ellipsis {
        background: transparent;
        border-color: transparent;
        color: #667085;
    }

    /* Foco acessível */
    .participa-pagination .page-link:focus-visible {
        outline: 2px solid #144454;
        outline-offset: 2px;
    }

    /* Responsividade */
    @media (max-width: 1199.98px) {
        .participa-pagination {
            flex-direction: column;
            justify-content: center;
            gap: 18px;
        }

        .participa-pagination-info {
            padding-right: 0;
            border-right: 0;
        }

        .participa-pagination .pagination {
            justify-content: center;
            flex-wrap: wrap;
        }
    }

    @media (max-width: 575.98px) {
        .participa-pagination {
            padding: 16px 12px;
        }

        .participa-pagination-info {
            gap: 10px;
            font-size: 12px;
            white-space: normal;
        }

        .participa-pagination-icon {
            width: 36px;
            height: 36px;
            font-size: 16px;
        }

        .participa-pagination .pagination {
            gap: 4px;
        }

        .participa-pagination .page-link {
            min-width: 34px;
            height: 36px;
            padding: 0 8px;
            font-size: 13px;
        }
    }

</style>
