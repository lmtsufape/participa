@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <img src="{{ asset('img/icons/check-solid.svg') }}" alt="Válido" style="width: 20px; height: 20px; margin-right: 8px; filter: brightness(0) invert(1);"> Recibo Válido
                    </h4>
                </div>

                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="alert alert-success">
                            <img src="{{ asset('img/icons/check-solid.svg') }}" alt="Autêntico" style="width: 16px; height: 16px; margin-right: 5px;">
                            <strong>Documento Autêntico</strong><br>
                            Este recibo foi validado e é autêntico.
                        </div>
                    </div>

                                         <div class="row">
                         <div class="col-md-6 mb-4">
                             <h5 class="mb-2"><img src="{{ asset('img/icons/user-solid-black.svg') }}" alt="Usuário" style="width: 16px; height: 16px; margin-right: 5px;"> Participante</h5>
                             <p class="border-bottom pb-3">{{ $nome }}</p>
                         </div>
                         <div class="col-md-6 mb-4">
                             <h5 class="mb-2"><img src="{{ asset('img/icons/calendar-black.svg') }}" alt="Data" style="width: 16px; height: 16px; margin-right: 5px;"> Data</h5>
                             <p class="border-bottom pb-3">{{ \Carbon\Carbon::parse($data)->locale('pt_BR')->isoFormat('D [de] MMMM [de] YYYY') }}</p>
                         </div>
                     </div>

                     <div class="row">
                         <div class="col-md-6 mb-4">
                             <h5 class="mb-2"><img src="{{ asset('img/icons/calendar-evento.svg') }}" alt="Evento" style="width: 16px; height: 16px; margin-right: 5px;"> Evento</h5>
                             <p class="border-bottom pb-3">{{ $evento }}</p>
                         </div>
                         <div class="col-md-6 mb-4">
                             <h5 class="mb-2"><img src="{{ asset('img/icons/category-solid-black.svg') }}" alt="Categoria" style="width: 16px; height: 16px; margin-right: 5px;"> Categoria</h5>
                             <p class="border-bottom pb-3">{{ $categoria }}</p>
                         </div>
                     </div>

                     <div class="row">
                         <div class="col-md-6 mb-4">
                             <h5 class="mb-2"><img src="{{ asset('img/icons/cash-payment-solid.svg') }}" alt="Valor" style="width: 16px; height: 16px; margin-right: 5px;"> Valor Pago</h5>
                             <p class="border-bottom pb-3">R$ {{ number_format($valor, 2, ',', '.') }}</p>
                         </div>
                         <div class="col-md-6 mb-4">
                             <h5 class="mb-2"><img src="{{ asset('img/icons/code.svg') }}" alt="Código" style="width: 16px; height: 16px; margin-right: 5px;"> Código de Validação</h5>
                             <p class="border-bottom pb-3">
                                 <code class="bg-light p-2 rounded">{{ $codigo_validacao }}</code>
                             </p>
                         </div>
                     </div>

                    <div class="text-center mt-4">
                        <div class="alert alert-info">
                            <img src="{{ asset('img/icons/info-circle-solid.svg') }}" alt="Informação" style="width: 16px; height: 16px; margin-right: 5px;">
                            <strong>Informação:</strong> Este documento foi emitido pela Associação Brasileira de Agroecologia (ABA) e é válido para fins de comprovação de pagamento.
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            <img src="{{ asset('img/icons/arrow.svg') }}" alt="Voltar" style="width: 16px; height: 16px; margin-right: 5px; transform: rotate(180deg);"> Voltar ao Início
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
