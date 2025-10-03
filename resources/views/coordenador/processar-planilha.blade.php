@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Processar Planilha de Alimentação</h4>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('processar-planilha.processar') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="arquivo" class="form-label">Selecionar arquivo XLSX</label>
                            <input type="file" class="form-control" id="arquivo" name="arquivo" accept=".xlsx,.xls" required>
                            <div class="form-text">
                                <strong>Formato esperado:</strong><br>
                                • Coluna A: Nome<br>
                                • Coluna B: CPF<br>
                                • Coluna C: Email<br>
                                • Coluna D: (ignorada)
                            </div>
                        </div>

                        <div class="mb-3">
                            <h5>O que será processado:</h5>
                            <ul>
                                <li><strong>Normalização de CPF:</strong> Remove espaços, vírgulas e pontuação, depois formata corretamente</li>
                                <li><strong>Verificação de usuário:</strong> Busca usuário pelo CPF no sistema</li>
                                <li><strong>Status da inscrição:</strong> Verifica se a inscrição está finalizada</li>
                                <li><strong>Alimentação:</strong> Marca campo "Alimentação" como true para inscrições finalizadas</li>
                            </ul>
                        </div>

                        <div class="mb-3">
                            <h5>Resultado:</h5>
                            <p>Será gerada uma planilha com os seguintes status:</p>
                            <ul>
                                <li><strong>Usuário não cadastrado:</strong> CPF não encontrado no sistema</li>
                                <li><strong>Inscrição finalizada - Alimentação liberada:</strong> Usuário encontrado e inscrição finalizada</li>
                                <li><strong>Inscrição pendente:</strong> Usuário encontrado mas inscrição não finalizada</li>
                            </ul>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload"></i> Processar Planilha
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

