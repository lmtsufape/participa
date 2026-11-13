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
                            <h2 class="h3 mb-3" style="color: #3d93a9;">Guia do Participante</h2>
                            <p class="text-muted">
                                Não conhece o campus da UFMG, precisa de orientação quanto a acessibilidade do evento ou sobre as salas e auditórios da programação? Não se preocupe! Fizemos este guia detalhado para você, congressista!
                            </p>
                            <p class="text-muted">Baixe o PDF abaixo e fique por dentro de tudo sobre o evento!</p>

                            <hr class="my-4">

                            <div class="d-grid d-md-flex gap-3">
                                <a href="{{ asset('documentos/CBEE_GuiaDeParticipantes_2026.pdf') }}"
                                   class="btn btn-outline-info d-flex align-items-center"
                                   download="CBEE_GuiaDeParticipantes_2026.pdf">
                                    <i class="bi bi-file-earmark-text me-2" aria-hidden="true"></i> Baixar Guia do Participante (.pdf)
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-4 border-start border-4" style="border-color: #f2a440 !important;">
                            <h2 class="h3 mb-3" style="color: #3d93a9;">Recursos de acessibilidade e inclusão disponíveis XV CBEE</h2>
                            <p class="text-muted">
                                Confira o mapeamento dos recursos de acessibilidade, inclusão e parentalidade disponíveis no evento. Neste documento, você encontra informações sobre os espaços e as estruturas de apoio para uma participação mais acessível e acolhedora para todas as pessoas!
                            </p>

                            <hr class="my-4">

                            <div class="d-grid d-md-flex gap-3">
                                <a href="{{ asset('documentos/Mapa_Recursos_Detalhamento-3.pdf') }}"
                                   class="btn btn-outline-info d-flex align-items-center"
                                   download="Mapa_Recursos_Detalhamento-3.pdf">
                                    <i class="bi bi-file-earmark-text me-2" aria-hidden="true"></i> Baixar Mapa dos Recursos de Acessibilidade e Inclusão (.pdf)
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-4 border-start border-4" style="border-color: #f2a440 !important;">
                            <h2 class="h3 mb-3" style="color: #3d93a9;">CARDÁPIO DO RESTAURANTE UNIVERSITÁRIO (RU)</h2>

                            <hr class="my-4">

                            <div class="d-grid d-md-flex gap-3">
                                <a href="https://fump.ufmg.br/cardapio-do-dia"
                                   class="btn btn-outline-info d-flex align-items-center"
                                   target="_blank"
                                   rel="noopener noreferrer">
                                    <i class="bi bi-box-arrow-up-right me-2" aria-hidden="true"></i> Acessar cardápio do RU
                                </a>
                            </div>
                        </div>
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
