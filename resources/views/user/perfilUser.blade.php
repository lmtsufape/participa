@extends('layouts.app')

@section('content')

    <div class="container content mb-5 position-relative">
        <style>
            .etapas {
                display: flex;
                justify-content: space-between;
                border-bottom: 2px solid #ccc;
                margin-bottom: 20px;
                font-family: sans-serif;
            }

            .etapa {
                flex: 1;
                text-align: left;
                padding: 10px 0;
                color: #aaa;
                font-weight: normal;
                border-bottom: 2px solid transparent;
            }

            .etapa.ativa {
                color: #004d51;
                font-weight: bold;
                border-bottom: 2px solid #004d51;
            }
        </style>

        <div class="row titulo text-center mt-3" style="color: #034652;">
            <h2 style="font-weight: bold;">{{__('Meu Perfil')}}</h2>
        </div>

        <form method="POST" action="{{ route('perfil.update') }}">
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

        <form action="{{ route('perfil.password.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom py-4 px-4 rounded-top-4">
                    <div class="d-flex align-items-center gap-3">
                        <div>
                            <h4 class="fw-bold mb-1">Segurança</h4>
                            <p class="text-muted mb-0 small">Atualize sua senha e mantenha sua conta segura.</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4 align-items-end">

                        <div class="col-md-4">
                            <label for="current_password" class="form-label fw-semibold">Senha atual</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white rounded-start-3">
                                    <i class="bi bi-lock text-muted"></i>
                                </span>
                                <input
                                    type="password"
                                    name="current_password"
                                    id="current_password"
                                    class="form-control @error('current_password') is-invalid @enderror"
                                    placeholder="Digite sua senha atual"
                                    autocomplete="current-password"
                                >
                                <button class="btn btn-outline-secondary rounded-end-3" type="button">
                                    <i class="bi bi-eye"></i>
                                </button>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="password" class="form-label fw-semibold">Nova senha</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white rounded-start-3">
                                    <i class="bi bi-lock text-muted"></i>
                                </span>
                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Digite sua nova senha"
                                    autocomplete="new-password"
                                >
                                <button class="btn btn-outline-secondary rounded-end-3" type="button">
                                    <i class="bi bi-eye"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="password_confirmation" class="form-label fw-semibold">Confirmar nova senha</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white rounded-start-3">
                                    <i class="bi bi-lock text-muted"></i>
                                </span>
                                <input
                                    type="password"
                                    name="password_confirmation"
                                    id="password_confirmation"
                                    class="form-control"
                                    placeholder="Confirme sua nova senha"
                                    autocomplete="new-password"
                                >
                                <button class="btn btn-outline-secondary rounded-end-3" type="button">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-lg-9">
                            <div class="alert alert-primary bg-primary-subtle border-primary-subtle text-primary-emphasis rounded-3 py-2 mb-0 small d-flex align-items-center gap-2">
                                <i class="bi bi-shield-check"></i>
                                <span>A senha deve ter no mínimo 8 caracteres, podendo conter letras e números.</span>
                            </div>
                        </div>

                        <div class="col-lg-3 d-flex justify-content-lg-end">
                            <button type="submit" class="btn btn-primary rounded-3 px-4 w-100 w-lg-auto">
                                Alterar senha
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </div>



@endsection
