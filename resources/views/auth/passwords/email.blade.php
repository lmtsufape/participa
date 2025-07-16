@extends('layouts.app')

@section('content')
<div class="container content my-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-sm rounded-4 border-0">
        <div class="card-body p-4">
          {{-- Cabeçalho --}}
          <div class="text-center mb-4">
            <h2 class="text-my-primary">{{ __('Redefinir senha') }}</h2>
            <p class="text-muted">{{ __('Digite seu e-mail para receber o link de redefinição de senha.') }}</p>
          </div>

          @if (session('status'))
            <div class="alert alert-success" role="alert">
              {{ session('status') }}
            </div>
          @endif

          <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-3">
              <label for="email" class="form-label fw-bold">{{ __('Endereço de e-mail') }}</label>
              <input
                id="email"
                type="email"
                class="form-control @error('email') is-invalid @enderror"
                name="email"
                value="{{ old('email') }}"
                required
                autocomplete="email"
                autofocus
              >

              @error('email')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>

            <button type="submit" class="btn btn-my-primary w-100">
              {{ __('Enviar link de redefinição') }}
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
