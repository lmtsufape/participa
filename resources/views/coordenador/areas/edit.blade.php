@extends('layouts.app')

@section('sidebar')
@endsection

@section('content')

<div class="container">
    <x-ui.content-header
        title="{{ __('Editar Área') }}"
        description="{{ __('Atualize as informações da área do seu evento.') }}"
    />
    <form method="POST" action="{{route('coord.areas.update', ['area_id' => $area->id])}}">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body">
                <div class="row justify-content-center g-3">
                    <div class="form-group">
                        <label for="nome" class="form-label fw-bold">{{ __('Nome da Área') }}</label>
                        <input id="nome" type="text" class="form-control @error('nome') is-invalid @enderror" name="nome" value="{{ old('nome', $area->nome ?? '') }}" required autocomplete="nome" autofocus>

                        @error('nome')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="resumo" class="form-label fw-bold">{{__('Resumo')}}</label>
                        <textarea id="resumo" class="form-control @error('resumo') is-invalid @enderror" name="resumo" rows="3">{{ old('resumo', $area->resumo ?? '') }}</textarea>
                        @error('resumo')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    @if ($evento->is_multilingual)
                        <div class="form-group">
                            <label for="nome_en" class="form-label fw-bold">{{ __('Nome da Área (Inglês)') }}</label>
                            <input id="nome_en" type="text" class="form-control @error('nome_en') is-invalid @enderror" name="nome_en" value="{{ old('nome_en', $area->nome_en ?? '') }}" autocomplete="nome_en" required>
                            @error('nome_en')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>
                        <div class="form-group">
                            <label for="resumo_en" class="form-label fw-bold">{{__('Resumo (Inglês)')}}</label>
                            <textarea id="resumo_en" class="form-control @error('resumo_en') is-invalid @enderror" name="resumo_en" rows="3">{{ old('resumo_en', $area->resumo_en ?? '') }}</textarea>
                            @error('resumo_en')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="nome_es" class="form-label fw-bold">{{ __('Nome da Área (Espanhol)') }}</label>
                            <input id="nome_es" type="text" class="form-control @error('nome_es') is-invalid @enderror" name="nome_es" value="{{ old('nome_es', $area->nome_es ?? '') }}" autocomplete="nome_es" required>

                            @error('nome_es')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="resumo_es" class="form-label fw-bold">{{__('Resumo (Espanhol)')}}</label>
                            <textarea id="resumo_es" class="form-control @error('resumo_es') is-invalid @enderror" name="resumo_es" rows="3">{{ old('resumo_es', $area->resumo_es ?? '') }}</textarea>

                            @error('resumo_es')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    @endif
            </div>
            <div class="d-flex justify-content-end pt-4">
                <button type="submit" class="btn btn-my-primary w-25" >
                    {{ __('Salvar') }}
                </button>
            </div>
        </div>

    </form>
</div>

@endsection
