@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0 text-center">
                        <img src="{{ asset('img/icons/user-times-solid.svg') }}" alt="Recusar" style="width: 20px; height: 20px; margin-right: 8px;">
                        Justificativa de indisponibilidade
                    </h4>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info">
                        <img src="{{ asset('img/icons/info-circle-solid.svg') }}" alt="Informação" style="width: 16px; height: 16px; margin-right: 8px;">
                        <strong>Informação:</strong> Por favor, informe o motivo da recusa do convite para avaliação (opcional).
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('avaliador.recusar', ['token' => $token]) }}">
                        @csrf
                        <div class="mb-3">
                            <label for="justificativa" class="form-label text" style="margin-left: 0; padding-left: 0; text-align: left; display: flex;">
                                <img src="{{ asset('img/icons/file-alt-regular.svg') }}" alt="Comentário" style="width: 16px; height: 16px; margin-right: 4px;">
                                <strong>Justificativa</strong>:
                            </label>
                            <textarea
                                name="justificativa"
                                id="justificativa"
                                class="form-control @error('justificativa') is-invalid @enderror"
                                rows="6"
                                placeholder="Descreva o motivo da indisponibilidade..."
                            >{{ old('justificativa') }}</textarea>
                            @error('justificativa')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-danger btn-lg">
                                Recusar convite
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
