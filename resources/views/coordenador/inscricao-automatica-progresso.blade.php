@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Processamento de Inscrições Automáticas</h4>
                </div>
                <div class="card-body">
                    <div id="progress-container">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span id="status-text">Iniciando processamento...</span>
                                <span id="progress-text">0%</span>
                            </div>
                            <div class="progress" style="height: 25px;">
                                <div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-animated" 
                                     role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h5 class="card-title" id="processados">0</h5>
                                            <p class="card-text">Processados</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h5 class="card-title" id="total">0</h5>
                                            <p class="card-text">Total</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="resultado-container" style="display: none;">
                            <div class="alert alert-success">
                                <h5>Processamento Concluído!</h5>
                                <p>O processamento foi finalizado com sucesso. Clique no botão abaixo para baixar o relatório.</p>
                            </div>
                            
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('inscricao-automatica.download', ['job_id' => $jobId]) }}" 
                                   class="btn btn-success btn-lg">
                                    <i class="fas fa-download"></i> Baixar Relatório
                                </a>
                            </div>
                        </div>

                        <div id="loading-container">
                            <div class="text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Processando...</span>
                                </div>
                                <p class="mt-2">Processando inscrições em lotes de 10 com intervalo de 15 segundos...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const jobId = '{{ $jobId }}';
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-text');
    const statusText = document.getElementById('status-text');
    const processadosEl = document.getElementById('processados');
    const totalEl = document.getElementById('total');
    const resultadoContainer = document.getElementById('resultado-container');
    const loadingContainer = document.getElementById('loading-container');

    function atualizarProgresso() {
        fetch(`{{ route('inscricao-automatica.status') }}?job_id=${jobId}`)
            .then(response => response.json())
            .then(data => {
                if (data.completado) {
                    // Processamento concluído
                    progressBar.style.width = '100%';
                    progressBar.setAttribute('aria-valuenow', 100);
                    progressText.textContent = '100%';
                    statusText.textContent = 'Processamento concluído!';
                    processadosEl.textContent = data.dados.processados || 0;
                    totalEl.textContent = data.dados.total || 0;
                    
                    // Mostrar resultado
                    loadingContainer.style.display = 'none';
                    resultadoContainer.style.display = 'block';
                    
                    // Parar polling
                    return;
                } else {
                    // Atualizar progresso
                    const progresso = Math.round(data.progresso || 0);
                    progressBar.style.width = progresso + '%';
                    progressBar.setAttribute('aria-valuenow', progresso);
                    progressText.textContent = progresso + '%';
                    statusText.textContent = `Processando... ${data.processados || 0} de ${data.total || 0}`;
                    processadosEl.textContent = data.processados || 0;
                    totalEl.textContent = data.total || 0;
                }
            })
            .catch(error => {
                console.error('Erro ao atualizar progresso:', error);
                statusText.textContent = 'Erro ao atualizar progresso';
            });
    }

    // Atualizar progresso a cada 2 segundos
    const interval = setInterval(atualizarProgresso, 2000);
    
    // Primeira atualização
    atualizarProgresso();
});
</script>
@endsection
