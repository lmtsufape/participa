@extends('layouts.app')

@section('content')
<style>

.form-home{
    border-radius: 10px;
    background-color: white;
    width: 500px;
    height: 480px;
    padding: 20px 30px;

}

p{
    text-align: justify;
}

</style>

<div class="container my-5">
    <div class="row">
        <section class="col-md-6 d-flex flex-column justify-content-center">
            <a class="navbar-brand" href="{{route('index')}}">
                <img src="{{ asset('/img/logoatualizadanova.png') }}" alt="logo" width="50%">
            </a>

            <p class="mt-3 fs-4 text-justify">{{ __('É um sistema de Gestão de Eventos Científicos que busca contribuir com instituições acadêmicas públicas ou privadas que necessitem de uma ferramenta para gerenciar eventos científicos') }}.</p>
        </section>

        <div class="col-md-6 d-flex align-items-center justify-content-end">
            <form class="shadow form-home" method="POST" action="{{ route('login') }}">
                @csrf
                <h4 class="text-md-center"><strong>{{ __('Entrar') }}</strong></h4>
                <hr class="border-secondary">

                <div class="form-group py-4">
                    <label for="email" class="form-label">{{ __('E-mail') }}</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group py-1 ">
                    <label for="password" class="form-label">{{ __('Senha') }}</label>
                    <div class="input-group">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                        <button type="button" class="btn btn-outline-secondary" id="togglePassword" tabindex="-1">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center pb-5" >
                    <div>
                        <input class="form-check-input border-dark" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">{{ __('Lembre-se de mim') }}</label>
                    </div>
                    @if (Route::has('password.request'))
                        <a class="btn btn-link link-secondary" href="{{ route('password.request') }}">
                            {{ __('Esqueceu a senha?') }}
                        </a>
                    @endif
                </div>

                <div class="text-md-center row">
                    <button type="submit" class="btn btn-my-primary w-100">
                        {{ __('Entrar') }}
                    </button>
                </div>

                <div class="text-md-center pt-4">
                    <span>{{ __('Não possui conta?') }}</span>
                    <a class="text-my-secondary fw-bold" href="{{ route('preRegistro') }}">
                        {{ __('Crie agora') }}
                    </a>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection
@section('javascript')
<script>
    if (!window.__togglePasswordInitialized) {
        window.__togglePasswordInitialized = true;

        document.addEventListener("DOMContentLoaded", function () {
            const togglePassword = document.getElementById("togglePassword");
            const password = document.getElementById("password");
            const eyeIcon = document.getElementById("eyeIcon");

            if (!togglePassword || !password || !eyeIcon) return;

            togglePassword.addEventListener("click", function () {
                const type = password.getAttribute("type") === "password" ? "text" : "password";
                password.setAttribute("type", type);

                eyeIcon.classList.toggle("bi-eye");
                eyeIcon.classList.toggle("bi-eye-slash");
            });
        });
    }
</script>
@endsection
