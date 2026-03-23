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
                <img src="{{ asset('/img/logoatualizada.png') }}" alt="logo" width="60%">
            </a>
        </section>

        <div class="col-md-6 d-flex align-items-center justify-content-end">
            <form class="d-flex flex-column justify-content-center shadow form-home" method="POST" action="{{route('validarCertificadoPost')}}">
                @csrf

                <div class="text-center">
                    <h1 class="h3 fw-semibold mb-0">{{ __('Acessar / validar documentos ou certificados') }}</h1>
                    <p class="text-center mb-0" id="subtitulo-documento">{{ __('Certificado ou carta de aceite') }}</p>
                </div>
                <hr class="border-secondary">

                {{-- Se houver mensagem de sucesso, mostra aqui (usado para redirecionamento) --}}
                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                
                <div class="form-group">
                    <fieldset class="mb-3">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                {{-- Opção 1: Validação por Hash (Original) --}}
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipo" id="tipo_cert" value="certificado" @if(old('tipo', 'certificado') == 'certificado') checked @endif>
                                    <label class="form-check-label" for="tipo_cert">{{ __('Hash Certificado') }}</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                {{-- Opção 2: Carta de Aceite (Original) --}}
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipo" id="tipo_aceite" value="aceite" @if(old('tipo') == 'aceite') checked @endif>
                                    <label class="form-check-label" for="tipo_aceite">{{ __('Carta de aceite') }}</label>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    {{-- Campos de Busca por Hash (Certificado/Aceite) --}}
                    <div id="campos_hash" @if(old('tipo') == 'cpf_evento' || old('tipo') == 'nome') style="display:none;" @endif>
                        <label for="hash" class="form-label">{{ __('Hash de validação') }}</label>
                        <input id="hash" type="text" name="hash" class="form-control @error('hash') is-invalid @enderror" value="{{ old('hash') }}" @if(old('tipo') != 'cpf_evento' && old('tipo') != 'nome') required @else disabled @endif autofocus>
                        
                        @error('hash')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    
                    
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const hashRadio = document.getElementById('tipo_cert');
        const aceiteRadio = document.getElementById('tipo_aceite');
        const cpfEventoRadio = document.getElementById('tipo_cpf_evento');
        const nomeRadio = document.getElementById('tipo_nome');
        
        const camposHash = document.getElementById('campos_hash');
        const campoHashInput = document.getElementById('hash');

        const camposCpfEvento = document.getElementById('campos_cpf_evento');
        const campoCpfInput = document.getElementById('cpf');
        const campoEventoSelect = document.getElementById('evento_id');

        const camposNome = document.getElementById('campos_nome');
        const campoNomeInput = document.getElementById('nome');

        function toggleFields() {
            if (cpfEventoRadio.checked) {
                // Habilita CPF/Evento, Desabilita Hash e Nome
                camposCpfEvento.style.display = 'block';
                campoCpfInput.required = true;
                campoCpfInput.disabled = false;
                campoEventoSelect.required = true;
                campoEventoSelect.disabled = false;

                camposHash.style.display = 'none';
                campoHashInput.required = false;
                campoHashInput.disabled = true;

                camposNome.style.display = 'none';
                campoNomeInput.required = false;
                campoNomeInput.disabled = true;

            } else if (nomeRadio.checked) {
                // Habilita Nome, Desabilita Hash e CPF/Evento
                camposNome.style.display = 'block';
                campoNomeInput.required = true;
                campoNomeInput.disabled = false;

                camposHash.style.display = 'none';
                campoHashInput.required = false;
                campoHashInput.disabled = true;

                camposCpfEvento.style.display = 'none';
                campoCpfInput.required = false;
                campoCpfInput.disabled = true;
                campoEventoSelect.required = false;
                campoEventoSelect.disabled = true;

            } else {
                camposHash.style.display = 'block';
                campoHashInput.required = true;
                campoHashInput.disabled = false;
                
                camposCpfEvento.style.display = 'none';
                campoCpfInput.required = false;
                campoCpfInput.disabled = true;
                campoEventoSelect.required = false;
                campoEventoSelect.disabled = true;

                camposNome.style.display = 'none';
                campoNomeInput.required = false;
                campoNomeInput.disabled = true;
            }
        }

        // Adiciona listeners
        hashRadio.addEventListener('change', toggleFields);
        aceiteRadio.addEventListener('change', toggleFields);
        cpfEventoRadio.addEventListener('change', toggleFields);
        nomeRadio.addEventListener('change', toggleFields);
        
        // Estado inicial
        toggleFields();
    });
</script>

@endsection