
@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-9 col-xl-8">

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">

                    <div class="text-center mb-4">
                        <div class="verification-icon mx-auto mb-3">
                            <i class="bi bi-envelope-check"></i>
                        </div>

                        <h3 class="fw-bold mb-2">
                            Confirme seu e-mail
                        </h3>

                        <p class="text-muted mb-0">
                            Enviamos um link de verificação para seu e-mail.
                            Para acessar as funcionalidades do Participa,
                            confirme seu endereço.
                        </p>
                    </div>

                    @if (session('status') === 'verification-link-sent')
                        <div class="alert alert-success d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>
                                Um novo link de verificação foi enviado
                                para seu e-mail.
                            </span>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div class="email-box rounded-3 p-3 mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-envelope fs-4 text-success"></i>

                            <div class="flex-grow-1 overflow-hidden">
                                <small class="text-muted d-block">
                                    E-mail cadastrado
                                </small>

                                <span class="fw-semibold text-break">
                                    {{ auth()->user()->email }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info border-0 rounded-3 mb-4">
                        <div class="d-flex align-items-start gap-3">
                            <i class="bi bi-info-circle fs-5"></i>

                            <div>
                                <h6 class="fw-semibold mb-1">
                                    Não encontrou o e-mail?
                                </h6>

                                <p class="mb-0 small">
                                    Verifique sua caixa de spam ou lixo eletrônico.
                                    Caso não tenha recebido, solicite um novo
                                    link de verificação.
                                </p>
                            </div>
                        </div>
                    </div>

                    <form
                        method="POST"
                        action="{{ route('verification.resend') }}"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="btn btn-my-primary w-100 py-3 fw-semibold"
                        >
                            <i class="bi bi-send me-2"></i>
                            Reenviar e-mail de verificação
                        </button>
                    </form>


                    <hr class="my-4">

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button
                            type="submit"
                            class="btn btn-outline-secondary w-100 py-2"
                        >
                            <i class="bi bi-box-arrow-right me-2"></i>
                            Sair da conta
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .verification-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 76px;
        height: 76px;
        border-radius: 50%;
        background-color: #e8f5ee;
        color: #198754;
        font-size: 34px;
    }

    .email-box {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
    }
</style>
@endsection
