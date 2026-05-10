@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h1 class="display-4 mb-4 fw-bold" style="color: #3d93a9; border-bottom: 3px solid #f2a440; padding-bottom: 10px;">
                Premiações
            </h1>

            <div class="row">
                <!-- Card Prêmio Darrell Posey -->
                <div class="col-md-8">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body p-4 border-start border-4" style="border-color: #f2a440 !important;">
                            <h2 class="h3 mb-3" style="color: #3d93a9;">Prêmio Darrell Posey</h2>
                            <p class="text-muted">
                                Confira abaixo as informações detalhadas sobre o cronograma e o regulamento oficial para a edição de 2026.
                            </p>
                            
                            <hr class="my-4">

                            <div class="d-grid d-md-flex gap-3">
                                <!-- Link Cronograma -->
                                <a href="{{ asset('documentos/premios/Cronograma_Darrell_Posey_2026.pdf') }}" 
                                   class="btn btn-outline-info d-flex align-items-center" 
                                   target="_blank">
                                   <i class="bi bi-file-earmark-pdf me-2"></i> Baixar Cronograma
                                </a>

                                <!-- Link Regulamento -->
                                <a href="{{ asset('documentos/premios/RegulamentoPrêmioDarrellPosey.pdf') }}" 
                                   class="btn btn-outline-info d-flex align-items-center" 
                                   target="_blank">
                                   <i class="bi bi-file-earmark-text me-2"></i> Baixar Regulamento
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Outras premiações (Exemplo) -->
                <div class="col-md-4">
                    <div class="alert alert-light border shadow-sm">
                        <h5 class="fw-bold" style="color: #3d93a9;">Aviso</h5>
                        <p class="small mb-0">Novas premiações serão anunciadas conforme o cronograma do evento CBBE.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection