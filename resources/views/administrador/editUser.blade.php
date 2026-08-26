@extends('layouts.app')

@section('content')

<div class="container position-relative">


    @if($user->usuarioTemp == null)
        <div class="row justify-content-center">
            <div class="col-auto">
                <h2 >
                    {{ __('Editar usuário') }}: {{ $user->name }}
                </h2>
            </div>
        </div>
        <br>
    @else
        <div class="row" style="margin-top: 20px; margin-bottom: 20px; font-weight: 2000;">
            <div class="col-sm-12">
                <h1>{{ __('Completar Cadastro') }}</h1>
            </div>
        </div>
    @endif


    <form method="POST" action="{{ route('admin.updateUser', ['id' => $user->id]) }}">
        @csrf

        @include('user._form', ['user' => $user])

        <div class="row justify-content-center" style="margin: 20px 0 20px 0">
            <div class="col-md-6" style="padding-left:0">
                {{-- <a class="btn btn-secondary botao-form" href="{{route('home')}}" style="width:100%">Voltar</a> --}}
            </div>
            <div class="col-md-6" style="padding-right:0">
                <button type="submit" class="btn btn-success btn-lg botao-form" style="width:100%; font-weight: bold; font-size: 16px; padding: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                    </i> {{ __('Concluir') }}
                </button>
            </div>
        </div>
    </form>
    <form method="POST" action="{{ route('admin.users.password.reset', $user) }}">
        @csrf
        @method('PATCH')

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-header bg-white border-bottom py-4 px-4 rounded-top-4">
                <div>
                    <h4 class="fw-bold mb-1">
                        Redefinir senha
                    </h4>

                    <p class="text-muted mb-0 small">
                        Defina uma nova senha de acesso para este usuário.
                    </p>
                </div>
            </div>

            <div class="card-body p-4">

                <div class="row g-4 align-items-end">

                    <div class="col-md-6">
                        <label
                            for="password"
                            class="form-label fw-semibold"
                        >
                            Nova senha
                        </label>

                        <div class="input-group">

                            <span class="input-group-text bg-white rounded-start-3">
                                <i class="bi bi-lock text-muted"></i>
                            </span>

                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Digite a nova senha"
                                autocomplete="new-password"
                            >

                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>
                    </div>

                    <div class="col-md-6">
                        <label
                            for="password_confirmation"
                            class="form-label fw-semibold"
                        >
                            Confirmar nova senha
                        </label>

                        <div class="input-group">

                            <span class="input-group-text bg-white rounded-start-3">
                                <i class="bi bi-lock text-muted"></i>
                            </span>

                            <input
                                type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                class="form-control"
                                placeholder="Confirme a nova senha"
                                autocomplete="new-password"
                            >

                        </div>
                    </div>

                    <div class="col-lg-9">
                        <div
                            class="alert alert-primary bg-primary-subtle border-primary-subtle
                                text-primary-emphasis rounded-3 py-2 mb-0 small
                                d-flex align-items-center gap-2"
                        >
                            <i class="bi bi-shield-check"></i>

                            <span>
                                A senha deve ter no mínimo 8 caracteres.
                            </span>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <button
                            type="submit"
                            class="btn btn-primary rounded-3 px-4 w-100"
                        >
                            <i class="bi bi-key me-2"></i>
                            Redefinir senha
                        </button>
                    </div>

                </div>

            </div>
        </div>
    </form>
</div>


@endsection

