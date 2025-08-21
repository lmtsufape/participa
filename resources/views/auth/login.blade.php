@extends('layouts.app')

@section('css')
<link href="{{ asset('css/login/login.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container my-5">
    <div class="row">
        <!-- Seção esquerda - Logo e descrição -->
        <section class="col-lg-6 col-md-12 d-flex flex-column justify-content-center mobile-center">
            <div class="logo-container">
                <a class="navbar-brand" href="{{route('index')}}">
                    <img src="{{ asset('/img/logo-novo2.png') }}" alt="logo" width="50%">
                </a>
            </div>

            <p class="mt-3 description-text">
                {{ __('É um sistema de Gestão de Eventos Científicos que busca contribuir com instituições acadêmicas públicas ou privadas que necessitem de uma ferramenta para gerenciar eventos científicos') }}.
            </p>
        </section>

        <!-- Seção direita - Formulário -->
        <div class="col-lg-6 col-md-12 d-flex align-items-center justify-content-center">
            <form class="shadow form-home" method="POST" action="{{ route('login') }}">
                @csrf
                <!-- Cabeçalho do formulário -->
                <h4 class="text-center mb-3">
                    <strong>{{ __('Entrar') }}</strong>
                </h4>
                <hr class="border-secondary mb-4">

                <!-- Campo Email -->
                <div class="form-group py-4">
                    <label for="email" class="form-label">{{ __('E-mail') }}</label>
                    <input id="email"
                           type="email"
                           class="form-control @error('email') is-invalid @enderror"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autocomplete="email"
                           autofocus>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Campo Senha -->
                <div class="form-group py-1">
                    <label for="password" class="form-label">{{ __('Senha') }}</label>
                    <div class="input-group">
                        <input id="password"
                               type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               name="password"
                               required
                               autocomplete="current-password">
                        <button type="button"
                                class="btn btn-outline-secondary"
                                id="togglePassword"
                                tabindex="-1"
                                aria-label="Mostrar/ocultar senha">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Lembrar-me e Esqueceu a senha -->
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center pb-5">
                    <div class="mb-2 mb-sm-0">
                        <input class="form-check-input border-dark"
                               type="checkbox"
                               name="remember"
                               id="remember"
                               {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            {{ __('Lembre-se de mim') }}
                        </label>
                    </div>

                    @if (Route::has('password.request'))
                        <a class="btn btn-link link-secondary p-0" href="{{ route('password.request') }}">
                            {{ __('Esqueceu a senha?') }}
                        </a>
                    @endif
                </div>

                <!-- Botão Entrar -->
                <div class="text-center mb-4">
                    <button type="submit" class="btn btn-my-primary w-100">
                        {{ __('Entrar') }}
                    </button>
                </div>

                <!-- Link para cadastro -->
                <div class="text-center pt-4">
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

                // Alterna entre os ícones de olho
                eyeIcon.classList.toggle("bi-eye");
                eyeIcon.classList.toggle("bi-eye-slash");
            });
        });
    }
</script>
@endsection
