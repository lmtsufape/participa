@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Confirmação de presença</h4>
                </div>
                <div class="card-body">

                    <form action="{{ route('evento.processarListaPresenca') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="arquivo" class="form-label">Selecionar arquivo XLSX</label>
                            <input type="file" class="form-control" id="arquivo" name="arquivo" accept=".xlsx,.xls" required>

                        </div>

                        <hr class="my-4">

                        <h3>Resultado</h3>
                        <div id="resultado" class="mt-3">
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Processar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

<script>
    document.getElementById('form-relatorio').addEventListener('submit', async function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    const resultado = document.getElementById('resultado');
    resultado.innerHTML = '<div class="alert alert-info">Processando...</div>';

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
