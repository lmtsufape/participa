@extends('layouts.app')
@section('sidebar')
@endsection
@section('content')
<div class="container">
    <h1>Trabalhos completos enviados</h1>
    <div class="card mb-3"><div class="card-body">
        <form method="GET" action="{{ route('coord.listarVersoesFinais', $evento) }}">
            <div class="row g-3">
                <div class="col-md-3"><label for="filtro-id" class="form-label">Buscar por ID</label><input id="filtro-id" name="id" type="number" min="1" class="form-control" value="{{ request('id') }}"></div>
                <div class="col-md-9"><label for="filtro-titulo" class="form-label">Buscar por título</label><input id="filtro-titulo" name="titulo" class="form-control" value="{{ request('titulo') }}"></div>
                <div class="col-md-6">
                    <label for="filtro-eixo" class="form-label">Eixo</label>
                    <select id="filtro-eixo" name="eixo_id" class="form-control">
                        <option value="">Todos os eixos disponíveis</option>
                        @foreach($areas as $area)<option value="{{ $area->id }}" @selected(request('eixo_id') == $area->id)>{{ $area->nome }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="filtro-modalidade" class="form-label">Modalidade</label>
                    <select id="filtro-modalidade" name="modalidade_id" class="form-control">
                        <option value="">Todas as modalidades</option>
                        @foreach($modalidades as $modalidade)<option value="{{ $modalidade->id }}" @selected(request('modalidade_id') == $modalidade->id)>{{ $modalidade->nome }}</option>@endforeach
                    </select>
                </div>
                <div class="col-12"><button class="btn btn-primary" type="submit">Buscar</button> <a class="btn btn-outline-success" href="{{ route('coord.listarVersoesFinais', $evento) }}">Limpar filtros</a></div>
            </div>
        </form>
    </div></div>
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <p class="mb-0">{{ $trabalhos->total() }} trabalho(s) com trabalho completo enviado.</p>
        <a class="btn btn-success d-inline-flex align-items-center gap-2"
           href="{{ route('coord.exportarAutoresVersoesFinais', array_merge(['evento' => $evento->id], request()->only(['id', 'titulo', 'eixo_id', 'modalidade_id']))) }}">
            <i class="fas fa-file-excel" aria-hidden="true"></i> Exportar autores (.xlsx)
        </a>
    </div>
    <div class="table-responsive"><table class="table table-hover">
        <thead><tr><th>ID</th><th>Título</th><th>Autor</th><th>Modalidade</th><th>Eixo</th><th>Enviado em</th><th>Arquivo</th></tr></thead>
        <tbody>
            @forelse($trabalhos as $trabalho)
                @php($versao = $trabalho->versoesFinais->first())
                <tr>
                    <td>{{ $trabalho->id }}</td><td>{{ $trabalho->titulo }}</td><td>{{ $trabalho->autor?->name ?? 'Autor não disponível' }}</td>
                    <td>{{ $trabalho->modalidade?->nome }}</td><td>{{ $trabalho->area?->nome }}</td><td>{{ $versao->created_at->format('d/m/Y H:i') }}</td>
                    <td><a class="btn btn-sm btn-outline-primary" href="{{ route('trabalho.versao-final.download', [$trabalho, $versao]) }}">Baixar trabalho completo</a>
                    <a href="{{ route('trabalho.versao-final.index', $trabalho) }}">Consultar envio</a></td>
                </tr>
            @empty
                <tr><td colspan="7">Nenhum trabalho completo enviado para os filtros selecionados.</td></tr>
            @endforelse
        </tbody>
    </table></div>
    {{ $trabalhos->links() }}
</div>
@endsection
