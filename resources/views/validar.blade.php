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
                    <h1 class="h3 fw-semibold mb-0">{{ __('Validar documento') }}</h1>
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
                        {{-- Opção 1: Validação por Hash (Original) --}}
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tipo" id="tipo_cert" value="certificado" @if(old('tipo', 'certificado') == 'certificado') checked @endif>
                            <label class="form-check-label" for="tipo_cert">{{ __('Hash Certificado') }}</label>
                        </div>

                        {{-- Opção 2: Carta de Aceite (Original) --}}
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tipo" id="tipo_aceite" value="aceite" @if(old('tipo') == 'aceite') checked @endif>
                            <label class="form-check-label" for="tipo_aceite">{{ __('Carta de aceite') }}</label>
                        </div>

                        {{-- NOVO: Opção 3: Certificado por CPF/Evento --}}
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tipo" id="tipo_cpf_evento" value="cpf_evento" @if(old('tipo') == 'cpf_evento') checked @endif>
                            <label class="form-check-label" for="tipo_cpf_evento">{{ __('Certificado por CPF') }}</label>
                        </div>
                    </fieldset>

                    {{-- Campos de Busca por Hash (Certificado/Aceite) --}}
                    <div id="campos_hash" @if(old('tipo') == 'cpf_evento') style="display:none;" @endif>
                        <label for="hash" class="form-label">{{ __('Hash de validação') }}</label>
                        <input id="hash" type="text" name="hash" class="form-control @error('hash') is-invalid @enderror" value="{{ old('hash') }}" @if(old('tipo') != 'cpf_evento') required @else disabled @endif autofocus>
                        
                        @error('hash')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    
                    {{-- NOVO: Campos de Busca por CPF e Evento --}}
                    <div id="campos_cpf_evento" @if(old('tipo', 'certificado') != 'cpf_evento') style="display:none;" @endif>
                        
                        <div class="mb-3">
                            <label for="evento_id" class="form-label">{{ __('Selecione o Evento') }}</label>
                            <select id="evento_id" name="evento_id" class="form-control @error('evento_id') is-invalid @enderror @error('cpf_evento') is-invalid @enderror" @if(old('tipo') == 'cpf_evento') required @else disabled @endif>
                                <option value="">{{ __('Selecione...') }}</option>
                                @foreach($eventos as $evento)
                                    <option value="{{ $evento->id }}" @if(old('evento_id') == $evento->id) selected @endif>{{ $evento->nome }}</option>
                                @endforeach
                            </select>
                            
                            @error('evento_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div>
                            <label for="cpf" class="form-label">{{ __('Seu CPF') }}</label>
                            <input id="cpf" type="text" name="cpf" class="form-control @error('cpf') is-invalid @enderror @error('cpf_evento') is-invalid @enderror" value="{{ old('cpf') }}" @if(old('tipo') == 'cpf_evento') required @else disabled @endif placeholder="Ex: 000.000.000-00" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)">
                            
                            @error('cpf')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            {{-- Erro unificado do controlador para CPF/Evento --}}
                            @error('cpf_evento')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

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
        
        const camposHash = document.getElementById('campos_hash');
        const campoHashInput = document.getElementById('hash');

        const camposCpfEvento = document.getElementById('campos_cpf_evento');
        const campoCpfInput = document.getElementById('cpf');
        const campoEventoSelect = document.getElementById('evento_id');
        
        // Função para formatar CPF (opcional, mas melhora a UX)
        // function formatarCPF(v) {
        //     v = v.replace(/\D/g, "")
        //     if (v.length > 9) v = v.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
        //     else if (v.length > 6) v = v.replace(/(\d{3})(\d{3})(\d{3})/, "$1.$2.$3");
        //     else if (v.length > 3) v = v.replace(/(\d{3})(\d{3})/, "$1.$2");
        //     return v;
        // }
        
        // campoCpfInput.addEventListener('keyup', (e) => {
        //     e.target.value = formatarCPF(e.target.value);
        // });


        function toggleFields() {
            if (cpfEventoRadio.checked) {
                // Habilita CPF/Evento, Desabilita Hash
                camposCpfEvento.style.display = 'block';
                campoCpfInput.required = true;
                campoCpfInput.disabled = false;
                campoEventoSelect.required = true;
                campoEventoSelect.disabled = false;

                camposHash.style.display = 'none';
                campoHashInput.required = false;
                campoHashInput.disabled = true;

            } else {
                 // Habilita Hash, Desabilita CPF/Evento
                camposHash.style.display = 'block';
                campoHashInput.required = true;
                campoHashInput.disabled = false;
                
                camposCpfEvento.style.display = 'none';
                campoCpfInput.required = false;
                campoCpfInput.disabled = true;
                campoEventoSelect.required = false;
                campoEventoSelect.disabled = true;
            }
        }

        // Adiciona listeners
        hashRadio.addEventListener('change', toggleFields);
        aceiteRadio.addEventListener('change', toggleFields);
        cpfEventoRadio.addEventListener('change', toggleFields);
        
        // Estado inicial
        toggleFields();
    });
</script>

@endsection