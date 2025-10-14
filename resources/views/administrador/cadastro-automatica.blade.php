@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Cadastro Automático de Usuários via Planilha</h4>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.cadastro-automatica.processar') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="arquivo" class="form-label">Selecionar arquivo XLSX</label>
                            <input type="file" class="form-control" id="arquivo" name="arquivo" accept=".xlsx,.xls" required>
                            <div class="form-text mt-3">
                                <strong>Formato esperado da Planilha (verifique as colunas):</strong>
                                <table class="table table-sm table-bordered mt-2">
                                    <thead>
                                        <tr>
                                            <th>Coluna</th>
                                            <th>Campo Esperado</th>
                                            <th>Observações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr><td>A</td><td>Nome Completo *</td><td>Campo `name`</td></tr>
                                        <tr><td>B</td><td>CPF *</td><td>Campo `cpf` (formatado)</td></tr>
                                        <tr><td>C</td><td>E-mail *</td><td>Campo `email`</td></tr>
                                        <tr><td>E</td><td>Instituição *</td><td>Campo `instituicao`</td></tr>
                                        <tr><td>F</td><td>Celular *</td><td>Campo `celular`</td></tr>
                                        <tr><td>G</td><td>Data de Nascimento *</td><td>Campo `dataNascimento`</td></tr>
                                        <tr><td>H</td><td>Cidade *</td><td>Usado na busca de endereço</td></tr>
                                        <tr><td>I</td><td>Comunidade/Aldeia/Quilombo</td><td>Usado como `logradouro` para a busca de CEP</td></tr>
                                        <tr><td>J</td><td>Estado *</td><td>Usado na busca de endereço</td></tr>
                                    </tbody>
                                </table>
                                <p class="text-danger">**Busca de Endereço:** O sistema tentará buscar o CEP com base na Cidade, Estado e Logradouro/Comunidade (Coluna I). O **Número será preenchido como '1'** automaticamente.</p>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Processar Cadastro
                        </button>
                        <a href="{{ route('admin.users') }}" class="btn btn-secondary">Voltar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection