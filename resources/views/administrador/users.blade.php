@extends('layouts.app')

@section('content')

<div class="container">
    <x-ui.content-header
        title="{{ __('Usuários') }}"
        description="Visualize e gerencie os usuários do sistema."
        :href="route('admin.cadastrarUsuario', app()->getLocale())"
        button-text="{{ __('Cadastrar usuário') }}"
    />

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3 p-lg-4">
            <form method="GET" action="{{ route('admin.users') }}">
                <div class="row g-3 align-items-end">

                    <div class="col-12 col-lg-6">
                        <label for="search" class="form-label fw-semibold">
                            Buscar usuário
                        </label>

                        <input
                            type="search"
                            id="search"
                            name="filter[search]"
                            class="form-control"
                            placeholder="Nome, e-mail, CPF, CNPJ ou passaporte"
                            value="{{ request('filter.search') }}"
                        >
                    </div>

                    <div class="col-12 col-md-6 col-lg-3">
                        <label for="trashed" class="form-label fw-semibold">
                            Situação
                        </label>

                        <select
                            id="trashed"
                            name="filter[trashed]"
                            class="form-select"
                        >
                            <option value="">
                                Não excluídos
                            </option>

                            <option
                                value="with"
                                @selected(request('filter.trashed') === 'with')
                            >
                                Todos os usuários
                            </option>

                            <option
                                value="only"
                                @selected(request('filter.trashed') === 'only')
                            >
                                Apenas excluídos
                            </option>
                        </select>
                    </div>

                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="d-flex gap-2">
                            <button
                                type="submit"
                                class="btn btn-primary flex-grow-1"
                            >
                                <i class="bi bi-search me-1"></i>
                                Buscar
                            </button>

                            <a
                                href="{{ route('admin.users') }}"
                                class="btn btn-outline-secondary"
                                title="Limpar filtros"
                                aria-label="Limpar filtros"
                            >
                                <i class="bi bi-x-lg"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <div class="d-flex justify-content-end mb-3">
        <a
            href="{{ route('admin.cadastro-automatica.index') }}"
            class="btn btn-outline-primary"
        >
            <i class="bi bi-file-earmark-spreadsheet me-1"></i>
            Cadastro por planilha
        </a>
    </div>

    <x-ui.table>
        <x-ui.table.head>
            <x-ui.table.row>
                <x-ui.table.th>#</x-ui.table.th>
                <x-ui.table.th>Nome</x-ui.table.th>
                <x-ui.table.th>E-mail</x-ui.table.th>
                <x-ui.table.th>Documento</x-ui.table.th>
                <x-ui.table.th class="text-center">Ações</x-ui.table.th>
            </x-ui.table.row>
        </x-ui.table.head>

        <x-ui.table.body id="users-tbody">

            @forelse ($users as $user)

                <x-ui.table.row :data-id="$user->id">
                    <x-ui.table.td>
                        {{ $loop->iteration }}
                    </x-ui.table.td>
                    <x-ui.table.td>
                        {{ $user->name }}
                    </x-ui.table.td>

                    <x-ui.table.td>
                        {{ $user->email }}
                    </x-ui.table.td>

                    <x-ui.table.td>
                        @php
                            $documentos = array_filter([
                                'CPF' => $user->cpf,
                                'CNPJ' => $user->cnpj,
                                'Passaporte' => $user->passaporte,
                            ], fn ($valor) => filled($valor));
                        @endphp

                        @forelse ($documentos as $tipo => $numero)
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-muted small" style="min-width: 40px;">
                                    {{ $tipo }}
                                </span>

                                <span>{{ $numero }}</span>
                            </div>
                        @empty
                            <span class="text-muted">—</span>
                        @endforelse
                    </x-ui.table.td>

                    <x-ui.table.td class="text-center">
                        <x-ui.table.actions>
                            <a href="{{ route('admin.editUser', ['id' => $user->id]) }}" title="Editar usuário" class="btn btn-sm btn-light border table-action">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @include('components.delete_modal', ['route' => 'admin.user.destroy', 'param' => 'user_id', 'entity_id' => $user->id])
                            <button type="button" class="btn btn-sm btn-light border table-action text-danger" title="Deletar usuário" data-bs-toggle="modal"
                                    aria-label="Deletar {{ $user->name }}" data-bs-target="#deleteModal{{$user->id}}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </x-ui.table.actions>
                    </x-ui.table.td>
                </x-ui.table.row>

            @empty

                <x-ui.table.row>
                    <x-ui.table.td colspan="7" class="text-center text-muted py-5">
                        Nenhum usuário cadastrado.
                    </x-ui.table.td>
                </x-ui.table.row>

            @endforelse

        </x-ui.table.body>

    </x-ui.table>

    <div class="d-flex justify-content-center">
        {{ $users->onEachSide(2)->links('vendor.pagination.participa') }}
    </div>

</div>

@endsection
