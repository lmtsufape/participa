@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card shadow p-4 border-0">
                <div class="card-body">
                    <i class="fab fa-paypal text-primary display-1 mb-4"></i>
                    <h3 class="fw-bold">Quase pronto!</h3>
                    <p class="text-muted">Você escolheu pagar a anuidade de <strong>{{ ucfirst($tipo) }}</strong> via PayPal.</p>
                    
                    <div class="my-4 p-3 bg-light rounded">
                        <span class="d-block text-muted small">Total a pagar</span>
                        <span class="display-6 fw-bold text-dark">R$ {{ number_format($valor, 2, ',', '.') }}</span>
                    </div>

                    <p class="small text-muted mb-4">Ao clicar no botão abaixo, você será redirecionado com segurança para o ambiente do PayPal para efetuar o pagamento.</p>

                    <a href="{{ $approveLink }}" class="btn btn-primary btn-lg w-100 shadow-sm fw-bold">
                        <i class="fas fa-external-link-alt"></i> Ir para o PayPal
                    </a>

                    <div class="mt-3">
                        <a href="{{ route('associar.pagar', ['tipo' => $tipo]) }}" class="btn btn-link text-muted btn-sm text-decoration-none">
                            Cancelar e mudar método
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection