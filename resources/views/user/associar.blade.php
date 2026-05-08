@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background-color: #114048;">
                    <h2 class="mb-0 text-center" style="font-size: 24px;">Seja um Associado CPFreire</h2>
                </div>
                <div class="card-body bg-light">
                    <p class="lead text-center mb-5">Escolha o plano que melhor se adequa ao seu perfil e aproveite descontos exclusivos em nossos eventos.</p>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 border-0 shadow-sm text-center">
                                <div class="card-body d-flex flex-column">
                                    <h3 class="fw-bold mb-3" style="color: #114048;">Profissional</h3>
                                    <p class="text-muted flex-grow-1">Indicado para profissionais das diversas áreas do conhecimento ou aposentados(as).</p>
                                    <div class="py-3">
                                        <span class="display-6 fw-bold">R$ 160</span>
                                        <span class="text-muted">/ ano</span>
                                    </div>
                                    <a href="{{ route('associar.pagar', 'profissional') }}" class="btn btn-lg text-white w-100 mt-auto" style="background-color: #114048;">
                                        Assinar Plano
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="card h-100 border-0 shadow-sm text-center">
                                <div class="card-body d-flex flex-column">
                                    <h3 class="fw-bold mb-3" style="color: #114048;">Estudante</h3>
                                    <p class="text-muted flex-grow-1">Indicado para estudantes do Ensino Médio ou graduação (necessário comprovação posterior).</p>
                                    <div class="py-3">
                                        <span class="display-6 fw-bold">R$ 80</span>
                                        <span class="text-muted">/ ano</span>
                                    </div>
                                    <a href="{{ route('associar.pagar', 'estudante') }}" class="btn btn-lg text-white w-100 mt-auto" style="background-color: #114048;">
                                        Assinar Plano
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection