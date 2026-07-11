@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fab fa-paypal"></i> Pagamento via PayPal
                    </h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <strong>Evento:</strong> {{ $evento->nome }}<br>
                        <strong>Valor:</strong> {{ config('paypal.currency', 'BRL') }} {{ number_format($categoria->valorComDescontoDeAssociado(), 2, ',', '.') }}
                    </div>

                    <div class="text-center mb-4">
                        <p class="lead">Você será redirecionado para o PayPal para concluir o pagamento.</p>
                        <p class="text-muted">Após a confirmação, você retornará automaticamente para esta página.</p>
                    </div>

                    <div class="text-center">
                        <a href="{{ $approveLink }}" class="btn btn-primary btn-lg" id="paypal-button">
                            <i class="fab fa-paypal"></i> Pagar com PayPal
                        </a>
                    </div>

                    <div class="mt-4 text-center">
                        <a href="{{ route('checkout.telaPagamento', ['evento' => $evento->id]) }}" class="btn btn-link">
                            <i class="fas fa-arrow-left"></i> Voltar e escolher outro método
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-redirect após 3 segundos (opcional)
    // setTimeout(function() {
    //     document.getElementById('paypal-button').click();
    // }, 3000);
</script>
@endsection

