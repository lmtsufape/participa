@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Importar Associados via Planilha (.CSV)</h5>
                    <a href="{{ route('admin.home') }}" class="btn btn-sm btn-light">Voltar ao Painel</a>
                </div>

                <div class="card-body">
                    @if (session('success_import'))
                        <div class="alert alert-success">
                            {{ session('success_import') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.associados.importar') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="planilha" class="form-label">Selecione o arquivo CSV obtido da planilha de Sócios:</label>
                            <input type="file" name="planilha" id="planilha" class="form-control @error('planilha') is-invalid @enderror" accept=".csv" required>
                            @error('planilha')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <small class="text-muted d-block mt-1">A planilha deve conter o CPF na 3ª coluna, pulando os cabeçalhos iniciais do documento.</small>
                        </div>

                        <button type="submit" class="btn btn-success">Processar Planilha e Associar</button>
                    </form>
                </div>
            </div>

            {{-- Relatório da Execução Atual --}}
            @if(session('associados_adicionados') || session('associados_nao_encontrados'))
                <div class="row">
                    {{-- Usuários vinculados com sucesso --}}
                    <div class="col-md-6 mb-3">
                        <div class="card border-success">
                            <div class="card-header bg-success text-white">
                                Tornados Associados no Sistema ({{ count(session('associados_adicionados', [])) }})
                            </div>
                            <div class="card-body" style="max-height: 350px; overflow-y: auto;">
                                @if(count(session('associados_adicionados', [])) > 0)
                                    <ul class="list-group list-group-flush">
                                        @foreach(session('associados_adicionados') as $u)
                                            <li class="list-group-item d-flex justify-content-between align-items-center py-1 px-2">
                                                <small>{{ $u['name'] }}</small>
                                                <span class="badge bg-secondary text-white"><small>{{ $u['cpf'] }}</small></span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted mb-0">Nenhum usuário foi modificado para associado nessa ação.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Usuários não encontrados --}}
                    <div class="col-md-6 mb-3">
                        <div class="card border-danger">
                            <div class="card-header bg-danger text-white">
                                Não Encontrados / Sem Cadastro ({{ count(session('associados_nao_encontrados', [])) }})
                            </div>
                            <div class="card-body" style="max-height: 350px; overflow-y: auto;">
                                @if(count(session('associados_nao_encontrados', [])) > 0)
                                    <div class="alert alert-warning py-1 px-2 mb-2"><small>⚠️ Estes CPFs precisam se cadastrar antes na plataforma.</small></div>
                                    <ul class="list-group list-group-flush">
                                        @foreach(session('associados_nao_encontrados') as $u)
                                            <li class="list-group-item d-flex justify-content-between align-items-center py-1 px-2">
                                                <small class="text-danger">{{ $u['name'] }}</small>
                                                <span class="badge bg-danger text-white"><small>{{ $u['cpf'] }}</small></span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted mb-0">Excelente! Todos os CPFs da planilha constam no banco de dados.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection