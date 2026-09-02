@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Confirmação de Presença - {{ $evento->nome }}</h4>
                </div>
                <div class="card-body">
                    <form id="form-lista-presenca" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="evento_id" value="{{ $evento->id }}">
                        <div class="mb-4">
                            <label for="arquivo" class="form-label"><strong>Selecionar arquivo de presença</strong></label>
                            <input type="file" class="form-control" id="arquivo" name="arquivo" accept=".xlsx,.xls,.csv" required>
                            <div class="form-text">Formatos aceitos: Excel (.xlsx, .xls) ou CSV (.csv)</div>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg">
                            Processar Lista de Presença
                        </button>
                    </form>

                    <hr class="my-4">

                    <h3>Resultado do Processamento</h3>
                    <div id="resultado" class="mt-3">
                        <div class="alert alert-info">
                            Selecione um arquivo e clique em "Processar" para ver os resultados.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('form-lista-presenca').addEventListener('submit', async function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    const resultado = document.getElementById('resultado');
    resultado.innerHTML = '<div class="alert alert-info">Processando arquivo...</div>';

    try {
        const response = await fetch('{{ route('evento.processarListaPresenca', $evento) }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: formData
        });

        const data = await response.json();
        resultado.innerHTML = formatarResultado(data);
    } catch (error) {
        resultado.innerHTML = '<div class="alert alert-danger">Erro: ' + error + '</div>';
    }
});

function formatarResultado(data) {
    let html = '<div class="row mb-4">';

    // Cards de totais
    html += '<div class="col-md-3">';
    html += '<div class="card bg-success text-white">';
    html += '<div class="card-body text-center">';
    html += '<h5 class="card-title">Marcados Presença</h5>';
    html += '<h3>' + (data.total.marcados_presenca || 0) + '</h3>';
    html += '</div></div></div>';

    html += '<div class="col-md-3">';
    html += '<div class="card bg-warning text-white">';
    html += '<div class="card-body text-center">';
    html += '<h5 class="card-title">Sem Inscrição</h5>';
    html += '<h3>' + (data.total.sem_inscricao || 0) + '</h3>';
    html += '</div></div></div>';

    html += '<div class="col-md-3">';
    html += '<div class="card bg-danger text-white">';
    html += '<div class="card-body text-center">';
    html += '<h5 class="card-title">Não Encontrados</h5>';
    html += '<h3>' + data.total.nao_encontrados + '</h3>';
    html += '</div></div></div>';

    html += '<div class="col-md-3">';
    html += '<div class="card bg-secondary text-white">';
    html += '<div class="card-body text-center">';
    html += '<h5 class="card-title">Total Processado</h5>';
    html += '<h3>' + ((data.total.marcados_presenca || 0) + (data.total.sem_inscricao || 0) + data.total.nao_encontrados) + '</h3>';
    html += '</div></div></div>';

    html += '</div>';

    // Mensagem de sucesso
    if (data.mensagem) {
        html += '<div class="alert alert-success">' + data.mensagem + '</div>';
    }

    // Usuários encontrados
    if (data.encontrados && data.encontrados.length > 0) {
        html += '<h4 class="text-success">Usuários Encontrados e Marcados</h4>';
        html += '<div class="table-responsive mb-4">';
        html += '<table class="table table-striped table-hover">';
        html += '<thead class="table-success">';
        html += '<tr><th>Nome</th><th>Email</th><th>Documento</th><th>Tipo</th><th>Inscrição</th><th>Status</th></tr>';
        html += '</thead><tbody>';

        data.encontrados.forEach(function(usuario) {
            html += '<tr>';
            html += '<td><strong>' + (usuario.user ? usuario.user.name : '-') + '</strong></td>';
            html += '<td>' + (usuario.user ? usuario.user.email : '-') + '</td>';
            html += '<td>' + (usuario.documento_raw || '-') + '</td>';
            html += '<td><span class="badge bg-primary">' + (usuario.tipo_documento || '-') + '</span></td>';
            html += '<td><span class="badge ' + (usuario.inscricao ? 'bg-success' : 'bg-warning') + '">';
            html += (usuario.inscricao ? 'Confirmada' : 'Pendente') + '</span></td>';
            html += '<td><span class="badge bg-success">Marcado</span></td>';
            html += '</tr>';
        });

        html += '</tbody></table></div>';
    }

    if (data.sem_inscricao && data.sem_inscricao.length > 0) {
        html += '<h4 class="text-warning">Usuários Sem Inscrição Confirmada</h4>';
        html += '<div class="table-responsive mb-4">';
        html += '<table class="table table-striped table-hover">';
        html += '<thead class="table-warning">';
        html += '<tr><th>Nome</th><th>Email</th><th>Documento</th><th>Tipo</th><th>Status</th><th>Motivo</th></tr>';
        html += '</thead><tbody>';

        data.sem_inscricao.forEach(function(usuario) {
            html += '<tr>';
            html += '<td><strong>' + (usuario.user ? usuario.user.name : '-') + '</strong></td>';
            html += '<td>' + (usuario.user ? usuario.user.email : '-') + '</td>';
            html += '<td>' + (usuario.documento_raw || '-') + '</td>';
            html += '<td><span class="badge bg-primary">' + (usuario.tipo_documento || '-') + '</span></td>';
            html += '<td><span class="badge bg-warning">Sem Inscrição</span></td>';
            html += '<td><span class="text-muted">Usuário não possui inscrição confirmada no evento</span></td>';
            html += '</tr>';
        });

        html += '</tbody></table></div>';
    }

    // Usuários não encontrados
    if (data.nao_encontrados && data.nao_encontrados.length > 0) {
        html += '<h4 class="text-danger">Usuários Não Encontrados</h4>';
        html += '<div class="table-responsive">';
        html += '<table class="table table-striped table-hover">';
        html += '<thead class="table-danger">';
        html += '<tr><th>Documento</th><th>Tipo</th><th>Status</th><th>Motivo</th></tr>';
        html += '</thead><tbody>';

        data.nao_encontrados.forEach(function(usuario) {
            html += '<tr>';
            html += '<td><strong>' + (usuario.documento_raw || '-') + '</strong></td>';
            html += '<td><span class="badge bg-secondary">' + (usuario.tipo_documento || '-') + '</span></td>';
            html += '<td><span class="badge bg-danger">Não encontrado</span></td>';
            html += '<td><span class="text-muted">Usuário não cadastrado no sistema</span></td>';
            html += '</tr>';
        });

        html += '</tbody></table></div>';
    }

    return html;
}
</script>

@endsection
