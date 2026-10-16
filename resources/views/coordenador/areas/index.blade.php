@extends('layouts.app')

@section('sidebar')
@endsection

@section('content')
    <div id="divListarAreas" class="container">

        <x-ui.content-header
            title="Listar Áreas"
            description="Áreas cadastradas no seu evento"
            :href="route('coord.areas.create', [
                'evento_id' => $evento->id,
            ])"
            button-text="Nova área"
        />

        <x-ui.table>

            <x-ui.table.head>
                <x-ui.table.row>
                    <x-ui.table.th>Nome</x-ui.table.th>
                    <x-ui.table.th>Resumo</x-ui.table.th>
                    <x-ui.table.th class="text-center">Ações</x-ui.table.th>
                </x-ui.table.row>
            </x-ui.table.head>

            <x-ui.table.body id="areas-tbody">

                @forelse ($areas as $area)

                    <x-ui.table.row :data-id="$area->id">

                        <x-ui.table.td>
                            {{ $area->nome }}
                        </x-ui.table.td>

                        <x-ui.table.td>
                            {{ $area->resumo }}
                        </x-ui.table.td>

                        <x-ui.table.td class="text-center">

                            <x-ui.table.actions>

                                {{-- Editar --}}
                                <a href="{{ route('coord.areas.edit', ['area_id' => $area->id]) }}" class="btn btn-sm btn-light border table-action"
                                    title="Editar área">
                                    <i class="bi bi-pencil"></i>

                                </a>

                                {{-- Excluir --}}
                                <button
                                    type="button"
                                    class="btn btn-sm btn-light border table-action text-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalExcluirArea{{ $area->id }}"
                                    title="Excluir área"
                                    aria-label="Excluir área {{ $area->nome }}"
                                >
                                    <i class="bi bi-trash"></i>
                                </button>

                            </x-ui.table.actions>

                        </x-ui.table.td>

                    </x-ui.table.row>

                @empty

                    <x-ui.table.row>
                        <x-ui.table.td colspan="2" class="text-center text-muted py-5">
                            Nenhuma área cadastrada.
                        </x-ui.table.td>
                    </x-ui.table.row>

                @endforelse

            </x-ui.table.body>

        </x-ui.table>
    </div>

    @push('modals')
        @foreach ($areas as $area)
            <div class="modal fade" id="modalExcluirArea{{ $area->id }}" tabindex="-1" aria-labelledby="modalExcluirAreaLabel{{ $area->id }}"
                    aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow rounded-3">

                        <form action="{{ route('coord.areas.destroy', ['id' => $area->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <div class="modal-header" style="background-color: #114048ff; color: white;">
                                <h5 class="modal-title fw-semibold" id="modalExcluirAreaLabel{{ $area->id }}">
                                    Excluir área
                                </h5>

                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                            </div>

                            <div class="modal-body py-4">
                                <p class="mb-2">
                                    Tem certeza que deseja excluir a área
                                    <strong>{{ $area->nome }}</strong>?
                                </p>

                                <p class="text-muted small mb-0">
                                    Esta ação não poderá ser desfeita.
                                </p>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                                    Cancelar
                                </button>

                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash me-1"></i>
                                    Excluir área
                                </button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        @endforeach
    @endpush

@endsection
