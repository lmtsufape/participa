@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header text-white text-center" style="background-color: #114048;">
                    <h4 class="mb-0 py-1">Forma de Pagamento da Associação</h4>
                </div>
                <div class="card-body bg-light p-4">
                    <div class="alert alert-info border-0 shadow-sm">
                        <strong>Plano Selecionado:</strong> {{ ucfirst($tipo) }}<br>
                        <strong>Valor da Anuidade:</strong> R$ {{ number_format($valor, 2, ',', '.') }} / ano
                    </div>

                    @if($isEstrangeiro)
                        <div class="alert alert-warning border-0 shadow-sm">
                            <i class="fas fa-info-circle"></i> 
                            Recomendamos o uso do <strong>PayPal</strong> para cartões emitidos fora do Brasil.
                        </div>
                    @endif

                    @if($errors->has('msg'))
                        <div class="alert alert-danger">
                            {{ $errors->first('msg') }}
                        </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 shadow-sm border-0 text-center">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="fw-bold" style="color: #114048;">Mercado Pago</h5>
                                    <p class="text-muted small flex-grow-1">
                                        Pix, Cartões Nacionais, Boleto Bancário.
                                    </p>
                                    <form method="GET" action="{{ route('associar.pagar', ['tipo' => $tipo]) }}">
                                        <input type="hidden" name="gateway" value="mercadopago">
                                        <button type="submit" class="btn text-white w-100" style="background-color: #114048;">
                                            Usar Mercado Pago
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="card h-100 shadow-sm border-primary text-center">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="fw-bold text-primary">PayPal</h5>
                                    <p class="text-muted small flex-grow-1">
                                        Recomendado para cartões internacionais e saldo PayPal.
                                    </p>
                                    <form method="GET" action="{{ route('associar.pagar', ['tipo' => $tipo]) }}">
                                        <input type="hidden" name="gateway" value="paypal">
                                        <button type="submit" class="btn btn-primary w-100">
                                            Usar PayPal
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 text-center">
                        <a href="{{ route('associar.index') }}" class="btn btn-link text-decoration-none text-muted">
                            <i class="fas fa-arrow-left"></i> Voltar aos planos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection