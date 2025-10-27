@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h3 class="mb-0">{{ __('Certificados Disponíveis') }}</h3>
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ __('Evento:') }} {{ $evento->nome }}</h5>
                    <p class="card-text">{{ __('Abaixo estão os certificados emitidos para o CPF de') }} {{ $user->name }} {{ __('neste evento.') }}</p>
                    <hr>

                    <ul class="list-group list-group-flush">
                        @foreach ($listaCertificados as $certificado)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $certificado['nome'] }}
                                <a href="{{ $certificado['download_url'] }}" target="_blank" class="btn btn-sm btn-outline-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                                        <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
                                        <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z"/>
                                    </svg>
                                    {{ __('Baixar Certificado') }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('validarCertificado') }}" class="btn btn-secondary">
                        {{ __('Nova Validação') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection