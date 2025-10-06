@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Inscrição Automática via Planilha</h4>
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

                    <form action="{{ route('inscricao-automatica.processar') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <input type="hidden" name="evento_id" value="{{ $evento->id }}">

                        <div class="mb-3">
                            <label for="categoria_id" class="form-label">Categoria de Participante</label>
                            <select class="form-control" id="categoria_id" name="categoria_id" required>
                                <option value="">Selecione uma categoria</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}">{{ $categoria->nome }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="arquivo" class="form-label">Selecionar arquivo XLSX</label>
                            <input type="file" class="form-control" id="arquivo" name="arquivo" accept=".xlsx,.xls" required>
                            <div class="form-text">
                                <strong>Formato esperado:</strong><br>
                                • Coluna A: Nome<br>
                                • Coluna B: CPF<br>
                                • Coluna C: Email<br>
                            </div>
                        </div>

                        <div class="mb-3">
                            <h5>O que será processado:</h5>
                            <ul>
                                <li><strong>Normalização de CPF:</strong> Remove espaços, vírgulas e pontuação, depois formata corretamente</li>
                                <li><strong>Verificação de usuário:</strong> Busca usuário pelo CPF no sistema</li>
                                <li><strong>Verificação de inscrição:</strong> Verifica se já está inscrito no evento</li>
                                <li><strong>Cancelamento de pré-inscrições:</strong> Cancela pré-inscrições não finalizadas</li>
                                <li><strong>Inscrição automática:</strong> Cria inscrição finalizada para usuários válidos</li>
                            </ul>
                        </div>

                        <div class="mb-3">
                            <h5>Resultado:</h5>
                            <p>Será gerada uma planilha com os seguintes status:</p>
                            <ul>
                                <li><strong>Usuário não cadastrado:</strong> CPF não encontrado no sistema</li>
                                <li><strong>Já estava inscrito:</strong> Usuário já possui inscrição finalizada</li>
                                <li><strong>Inscrito com sucesso:</strong> Usuário inscrito automaticamente</li>
                                <li><strong>Erro:</strong> Problemas durante o processamento</li>
                            </ul>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Processar Inscrições
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
