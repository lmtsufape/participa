@extends('layouts.app')

@section('content')
<style>

.form-home {
    border-radius: 10px;
    background-color: white;
    width: 500px;

    min-height: 200px;
    padding: 1.5rem 2rem;
}


p{
    text-align: justify;
}

</style>

<div class="container my-5">
    <div class="row">
        <section class="col-md-6 d-flex flex-column justify-content-center">
            <a class="navbar-brand" href="{{route('index')}}">
                <img src="{{ asset('/img/logo.png') }}" alt="logo" width="60%">
            </a>
        </section>

        <div class="col-md-6 d-flex align-items-center justify-content-end">
            <form class="d-flex flex-column justify-content-center shadow form-home" method="POST" action="{{route('validarCertificadoPost')}}">
                @csrf

                <div class="d-flex justify-content-center">
                    <h3>Validar certificado</h3>
                </div>
                <hr class="border-secondary">

                <div class="form-group">
                    <label for="hash" class="form-label">{{ __('Hash de validação') }}</label>
                    <input id="hash" type="text" name="hash" class="form-control @error('hash') is-invalid @enderror" required autofocus>

                    @error('hash')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-my-primary w-100">
                        {{ __('Validar') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection