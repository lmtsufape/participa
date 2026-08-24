@extends('layouts.app')
@section('sidebar')
@endsection
@section('content')
<style>

.table-modern {
    --bs-table-bg: transparent;
}

.table-modern thead th {
    font-size: 0.875rem;
    border-bottom: 1px solid #e9ecef;
    background-color: #f8f9fa;
    padding: 1rem 1rem 1rem 1rem;
}

.table-modern tbody td {
    border-top: 1px solid #f1f3f5;
    padding-top: 1rem;
    padding-bottom: 1rem;
}



.table-modern .btn {
    min-width: 92px;
}
</style>
<div class="container">
    <x-admin.content-header
        title="Listar Formulários"
        description="Gerencie os formulários utilizados na avaliação dos trabalhos desta modalidade."
        :href="route('coord.atribuir.form', [
            'evento_id' => $evento->id,
            'modalidade_id' => $modalidade->id
        ])"
        button-text="Novo formulário"
    />
    <p class="pt-0 text-muted">
         Modalidade:
         <strong class="text-my-primary">
             {{ $modalidade->nome }}
         </strong>
    </p>
    <div class="card border-0 shadow rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle table-modern">
                    <thead class="thead">
                        <tr>
                            <th class="fw-semibold text-muted" scope="col">Título do Formulário</th>
                            <th class="fw-semibold text-muted" scope="col">Versão</th>
                            <th class="fw-semibold text-muted text-center" scope="col">Status</th>
                            <th class="fw-semibold text-muted text-center" scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($forms as $form)
                            <tr>
                                <td>{{ $form->titulo }}</td>
                                <td class="ps-4">v.{{ $form->versao }}</td>
                                <td class="text-center">
                                    @if ($form->status)
                                        <span
                                        class="badge rounded-pill px-3 py-2 {{ $form->status->badgeClass() }}"
                                        style="min-width: 90px;"
                                        >
                                            {{ $form->status->label() }}
                                        </span>
                                    @else
                                        <span
                                            class="badge rounded-pill bg-light text-secondary px-3 py-2"
                                            style="min-width: 90px;"
                                        >
                                            Indefinido
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex gap-2">
                                        <form action="{{ route('coord.visualizar.form') }}" method="get">
                                            <input type="hidden" name="form_id" value="{{ $form->id }}">
                                            <input type="hidden" name="modalidade_id" value="{{ $form->modalidade->id }}">
                                            <button type="submit" class="btn btn-sm btn-outline-secondary rounded-3">
                                                Visualizar
                                            </button>
                                        </form>
                                        <input type="hidden" name="modalidade_id" value="{{ $form->modalidade->id }}">
                                        <a class="btn btn-sm btn-secondary" href=" {{ route('coord.respostasToPdf', $form->modalidade) }} ">
                                            Ver respostas
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
