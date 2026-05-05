@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fab fa-paypal"></i> Status do Pagamento
                    </h4>
                </div>
                <div class="card-body">
                    @if(isset($order))
                        @php
                            $status = $order['status'] ?? $pagamento->status;
                            $purchaseUnit = $order['purchase_units'][0] ?? null;
                            $capture = $purchaseUnit['payments']['captures'][0] ?? null;
                        @endphp

                        @if($status === 'COMPLETED' || $pagamento->status === 'approved')
                            <div class="alert alert-success">
                                <h5><i class="fas fa-check-circle"></i> Pagamento Aprovado!</h5>
                                <p class="mb-0">Sua inscrição foi confirmada com sucesso.</p>
                            </div>

                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <h6 class="card-title">Detalhes do Pagamento</h6>
                                    <hr>
                                    <p class="mb-1"><strong>ID da Ordem:</strong> {{ $pagamento->paypal_order_id }}</p>
                                    <p class="mb-1"><strong>Valor:</strong> {{ config('paypal.currency', 'USD') }} {{ number_format($pagamento->valor, 2, '.', ',') }}</p>
                                    <p class="mb-1"><strong>Status:</strong> 
                                        <span class="badge bg-success">Aprovado</span>
                                    </p>
                                    @if($capture)
                                        <p class="mb-0"><strong>Data:</strong> {{ \Carbon\Carbon::parse($capture['create_time'])->format('d/m/Y H:i') }}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <i class="fas fa-envelope"></i> 
                                Um email de confirmação foi enviado para <strong>{{ auth()->user()->email }}</strong>
                            </div>
                        @elseif($status === 'PENDING')
                            <div class="alert alert-warning">
                                <h5><i class="fas fa-clock"></i> Pagamento Pendente</h5>
                                <p class="mb-0">Seu pagamento está sendo processado. Você receberá um email quando for confirmado.</p>
                            </div>
                        @elseif($status === 'CANCELLED' || $status === 'VOIDED')
                            <div class="alert alert-danger">
                                <h5><i class="fas fa-times-circle"></i> Pagamento Cancelado</h5>
                                <p class="mb-0">O pagamento foi cancelado. Você pode tentar novamente.</p>
                            </div>
                        @else
                            <div class="alert alert-secondary">
                                <h5><i class="fas fa-info-circle"></i> Status: {{ $status }}</h5>
                                <p class="mb-0">Aguardando processamento do pagamento.</p>
                            </div>
                        @endif
                    @else
                        @if($pagamento->status === 'approved')
                            <div class="alert alert-success">
                                <h5><i class="fas fa-check-circle"></i> Pagamento Aprovado!</h5>
                                <p class="mb-0">Sua inscrição foi confirmada com sucesso.</p>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <h5><i class="fas fa-clock"></i> Processando Pagamento</h5>
                                <p class="mb-0">Aguardando confirmação do PayPal.</p>
                            </div>
                        @endif
                    @endif

                    <div class="mt-3 text-center">
                        <a href="{{ route('index') }}" class="btn btn-primary">
                            <i class="fas fa-home"></i> Voltar ao Início
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

