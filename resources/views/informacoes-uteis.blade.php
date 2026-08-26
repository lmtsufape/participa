@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h1 class="display-4 mb-4 fw-bold" style="color: #3d93a9; border-bottom: 3px solid #f2a440; padding-bottom: 10px;">
                Informações Úteis
            </h1>
            <div class="row">
                <!-- Card Sugestão de Hospedagem -->
                <div class="col-md-8">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body p-4 border-start border-4" style="border-color: #f2a440 !important;">
                            <h2 class="h3 mb-3" style="color: #3d93a9;">Sugestão de Hospedagem</h2>
                            <p class="text-muted">
                                Confira abaixo as informações detalhadas sobre a sugestão de hospedagem para o evento.
                            </p>

                            <hr class="my-4">

                            <div class="d-grid d-md-flex gap-3">
                                <a href="{{ route('sugestao.hospedagem', ['locale' => app()->getLocale()]) }}" 
                                   class="btn btn-outline-info d-flex align-items-center" 
                                   target="_blank">
                                   <i class="bi bi-file-earmark-text me-2"></i> Visualizar informações
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-4 border-start border-4" style="border-color: #f2a440 !important;">
                            <h2 class="h3 mb-3" style="color: #3d93a9;">Hospedagem Solidária</h2>
                            <p class="text-muted">
                                Confira abaixo as informações detalhadas sobre a hospedagem solidária para o evento.
                            </p>
                            
                            <hr class="my-4">

                            <div class="d-grid d-md-flex gap-3">
                                <!-- Link Cronograma -->
                                <a href="{{ route('hospedagem.solidaria', ['locale' => app()->getLocale()]) }}" 
                                   class="btn btn-outline-info d-flex align-items-center" 
                                   target="_blank">
                                   <i class="bi bi-file-earmark-text me-2"></i> Visualizar informações
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection