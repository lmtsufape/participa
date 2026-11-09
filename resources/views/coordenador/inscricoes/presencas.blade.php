@extends('coordenador.detalhesEvento')
@section('menu')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h1 class="titulo-detalhes mb-0">Confirmação de presença</h1>
    <a href="{{ route('inscricao.inscritos', $evento) }}" class="btn btn-outline-primary">Voltar aos inscritos</a>
</div>
<p>Marque quem compareceu ao evento. Cada alteração é salva automaticamente e permanece ao voltar a esta tela. Para corrigir uma marcação, desmarque a pessoa.</p>
<div class="card mb-3"><div class="card-body">
    <form method="GET" action="{{ route('inscricao.presencas', $evento) }}" class="row g-3 align-items-end">
        <div class="col-md-7">
            <label for="busca-presenca" class="form-label">Nome, e-mail ou CPF</label>
            <input id="busca-presenca" class="form-control" name="busca" value="{{ request('busca') }}" maxlength="255" placeholder="Digite para buscar um participante">
        </div>
        <div class="col-md-3">
            <label for="filtro-presenca" class="form-label">Presença</label>
            <select id="filtro-presenca" class="form-select" name="presenca">
                <option value="">Todas</option>
                <option value="presente" @selected(request('presenca') === 'presente')>Confirmada</option>
                <option value="pendente" @selected(request('presenca') === 'pendente')>Não registrada</option>
            </select>
        </div>
        <div class="col-md-2"><button class="btn btn-primary" type="submit">Buscar</button></div>
        <div class="col-12"><a href="{{ route('inscricao.presencas', $evento) }}" class="btn btn-outline-success btn-sm">Limpar filtros</a></div>
    </form>
</div></div>
<div id="presenca-erro" class="alert alert-danger d-none" role="alert"></div>
<p>{{ $inscricoes->total() }} inscrição(ões) encontrada(s). Apenas inscrições confirmadas permitem registrar presença.</p>
<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead><tr><th>Participante</th><th>E-mail</th><th>CPF</th><th>Inscrição</th><th>Presença</th></tr></thead>
        <tbody>
        @forelse ($inscricoes as $inscricao)
            <tr>
                <td>{{ $inscricao->user->name }}</td>
                <td>{{ $inscricao->user->email }}</td>
                <td>{{ $inscricao->user->cpf }}</td>
                <td><span class="badge {{ $inscricao->finalizada ? 'bg-success' : 'bg-secondary' }}">{{ $inscricao->finalizada ? 'Confirmada' : 'Não confirmada' }}</span></td>
                <td>
                    <div class="form-check form-switch">
                        <input type="checkbox" class="form-check-input js-presenca" role="switch" id="presenca-{{ $inscricao->id }}"
                            data-url="{{ route('inscricao.presencas.update', [$evento, $inscricao]) }}"
                            @checked($inscricao->is_presente) @disabled(!$inscricao->finalizada)>
                        <label class="form-check-label" for="presenca-{{ $inscricao->id }}">Presente<span class="visually-hidden">: {{ $inscricao->user->name }}</span></label>
                    </div>
                    <small class="js-presenca-status text-muted" role="status">{{ $inscricao->is_presente ? 'Presença confirmada' : 'Não registrada' }}</small>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center py-4">Nenhuma inscrição encontrada.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $inscricoes->links() }}
<script src="{{ asset('js/presencas.js') }}" defer></script>
@endsection
