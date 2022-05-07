@extends('layouts.app')

@section('content')
<div class="container content" style="position: relative; top: 50px;">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card card-login-cadastro">
                {{-- <div class="card-header">{{ __('Login') }}</div> --}}

                <div class="card-body">
                    <form method="POST" action="{{route('validarCertificadoPost')}}">
                        @csrf

                        <div class="row justify-content-center">
                            <div class="titulo-login-cadastro">Validar certificado</div>
                        </div>

                        <div class="form-group row">

                            <div class="col-md-12">
                                <label for="hash" class="col-form-label text-md-right">{{ __('Hash de validação') }}</label>
                                <input id="hash" type="text" name="hash" class="form-control @error('hash') is-invalid @enderror" required autofocus>

                                @error('hash')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">

                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" style="width:100%">
                                    {{ __('Validar') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<br>
@include('componentes.footer')

@endsection
