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
    <pre id="resultado" class="bg-light p-3 rounded small" style="max-height: 500px; overflow:auto;"></pre>
</div>

<script>
document.getElementById('form-relatorio').addEventListener('submit', async function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    const resultado = document.getElementById('resultado');
    resultado.textContent = 'Processando...';

    try {
        const response = await fetch('{{ route('admin.relatorio.processar') }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: formData
        });

        const data = await response.json();
        resultado.textContent = JSON.stringify(data, null, 2);
    } catch (error) {
        resultado.textContent = 'Erro: ' + error;
    }
});
</script>
@endsection
