@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Relatório de Inscrições</h1>

    <form id="form-relatorio" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Arquivo XLSX</label>
            <input type="file" name="arquivo" id="arquivo" class="form-control" accept=".xlsx,.xls,.csv" required>
        </div>

        <div class="mb-3 d-none">
            <label class="form-label">Evento (opcional)</label>
            <input type="number" hidden name="evento_id" class="form-control" value="2" placeholder="ID do evento">
        </div>

        <button type="submit" class="btn btn-primary">Enviar e Processar</button>
    </form>

    <hr class="my-4">

    <h3>Resultado</h3>
    <div id="resultado" class="mt-3">
    </div>
</div>

<script>
document.getElementById('form-relatorio').addEventListener('submit', async function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    const resultado = document.getElementById('resultado');
    resultado.innerHTML = '<div class="alert alert-info">Processando...</div>';

    try {
        const response = await fetch('{{ route('admin.relatorio.processar') }}', {
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

    //totais
    html += '<div class="col-md-4">';
    html += '<div class="card bg-success text-white">';
    html += '<div class="card-body text-center">';
    html += '<h5 class="card-title">Encontrados</h5>';
    html += '<h3>' + data.total.encontrados + '</h3>';
    html += '</div></div></div>';

    html += '<div class="col-md-4">';
    html += '<div class="card bg-danger text-white">';
    html += '<div class="card-body text-center">';
    html += '<h5 class="card-title">Não Encontrados</h5>';
    html += '<h3>' + data.total.nao_encontrados + '</h3>';
    html += '</div></div></div>';

    html += '<div class="col-md-4">';
    html += '<div class="card bg-info text-white">';
    html += '<div class="card-body text-center">';
    html += '<h5 class="card-title">Total</h5>';
    html += '<h3>' + (data.total.encontrados + data.total.nao_encontrados) + '</h3>';
    html += '</div></div></div>';

    html += '</div>';

    // usuarios encontrados
    if (data.encontrados.length > 0) {
        html += '<h4 class="text-success">Usuários Encontrados</h4>';
        html += '<div class="table-responsive mb-4">';
        html += '<table class="table table-striped table-hover">';
        html += '<thead class="table-success">';
        html += '<tr><th>Nome</th><th>CPF</th><th>Email</th><th>Inscrição</th><th>Alimentação</th></tr>';
        html += '</thead><tbody>';

        data.encontrados.forEach(function(usuario) {
            html += '<tr>';
            html += '<td>' + (usuario.nome || '-') + '</td>';
            html += '<td>' + (usuario.cpf || '-') + '</td>';
            html += '<td>' + (usuario.email || '-') + '</td>';
            html += '<td><span class="badge ' + (usuario.inscricao ? 'bg-success' : 'bg-warning') + '">';
            html += (usuario.inscricao ? 'Confirmada' : 'Pendente') + '</span></td>';
            html += '<td><span class="badge ' + (usuario.alimentacao ? 'bg-success' : 'bg-secondary') + '">';
            html += (usuario.alimentacao ? 'Sim' : 'Não') + '</span></td>';
            html += '</tr>';
        });

        html += '</tbody></table></div>';
    }

    // usuários não encontrados
    if (data.nao_encontrados.length > 0) {
        html += '<h4 class="text-danger">Usuários Não Encontrados</h4>';
        html += '<div class="table-responsive">';
        html += '<table class="table table-striped table-hover">';
        html += '<thead class="table-danger">';
        html += '<tr><th>Nome</th><th>CPF</th><th>Email</th><th>Status</th></tr>';
        html += '</thead><tbody>';

        data.nao_encontrados.forEach(function(usuario) {
            html += '<tr>';
            html += '<td>' + (usuario.nome || '-') + '</td>';
            html += '<td>' + (usuario.cpf || '-') + '</td>';
            html += '<td>' + (usuario.email || '-') + '</td>';
            html += '<td><span class="badge bg-danger">Não encontrado</span></td>';
            html += '</tr>';
        });

        html += '</tbody></table></div>';
    }

    return html;
}
</script>
@endsection
