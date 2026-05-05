@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Selecione o método de pagamento</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <strong>Evento:</strong> {{ $evento->nome }}<br>
                        <strong>Valor:</strong> R$ {{ number_format($categoria->valor_total, 2, ',', '.') }}
                    </div>

                    @if($isEstrangeiro)
                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle"></i> 
                            Detectamos que você é um participante estrangeiro. Recomendamos o uso do PayPal para facilitar o pagamento.
                        </div>
                    @endif

                    <div class="row">
                        <!-- Mercado Pago -->
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 border">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Mercado Pago</h5>
                                    <p class="card-text text-muted">
                                        Aceita cartões de crédito, PIX, boleto bancário e outros métodos brasileiros.
                                    </p>
                                    <form method="GET" action="{{ route('checkout.telaPagamento', ['evento' => $evento->id]) }}">
                                        <input type="hidden" name="gateway" value="mercadopago">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fas fa-credit-card"></i> Pagar com Mercado Pago
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- PayPal -->
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 border-primary">
                                <div class="card-body text-center">
                                    <h5 class="card-title">
                                        PayPal
                                        @if($isEstrangeiro)
                                            <span class="badge bg-success">Recomendado</span>
                                        @endif
                                    </h5>
                                    <p class="card-text text-muted">
                                        Ideal para participantes internacionais. Aceita cartões de crédito e conta PayPal.
                                    </p>
                                    <form method="GET" action="{{ route('checkout.telaPagamento', ['evento' => $evento->id]) }}">
                                        <input type="hidden" name="gateway" value="paypal">
                                        <button type="submit" class="btn btn-outline-primary btn-block">
                                            <i class="fab fa-paypal"></i> Pagar com PayPal
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 text-center">
                        <a href="{{ route('evento.visualizar', ['id' => $evento->id]) }}" class="btn btn-link">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

