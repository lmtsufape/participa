@extends('layouts.app')

@section('content')
    <div class="container content mb-5 position-relative">
        {{-- CSS de bandeiras, Select2 e intl-tel-input --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/6.6.6/css/flag-icons.min.css"
            crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"
            crossorigin="anonymous" referrerpolicy="no-referrer" />

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

            .required-field::after {
                content: "*";
                color: #D44100;
                margin-left: 2px;
            }
        </style>

        <br><br>

        @if (session('sucesso'))
            <div class="alert alert-success">{{ session('sucesso') }}</div>
        @endif
        @if (session('erro'))
            <div class="alert alert-danger">{{ session('erro') }}</div>
        @endif

        <div class="row titulo text-center" style="color: #034652;">
            <h2 style="font-weight: bold;">{{ __('Cadastro') }}</h2>
        </div>

        @php
            $selecionado = old('pais', 'brasil');
            $paises = config('paises');
        @endphp

        @if (Auth::check())
            <form method="POST" action="{{ route('administrador.criarUsuario', app()->getLocale()) }}">
            @else
                <form method="POST" action="{{ route('enviarCodigo') }}">
        @endif
        @csrf

        {{-- País --}}
        <div class="form-group row my-3">
            <div class="col-md-12">
                <label for="pais" class="col-form-label required-field">{{ __('País') }}</label>
                <select id="pais" name="pais" class="form-control @error('pais') is-invalid @enderror">
                    @foreach ($paises as $slug => $pais)
                        <option value="{{ $slug }}" data-iso="{{ $pais['iso'] }}"
                            @if ($slug === $selecionado) selected @endif>
                            {{ $pais['nome'] }}
                        </option>
                    @endforeach
                </select>
                @error('pais')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>

        {{-- Etapas --}}
        <div class="etapas" style="font-weight: 500;">
            <div class="etapa ativa">
                <p>1. {{ __('Validação de cadastro') }}</p>
            </div>
            <div class="etapa">
                <p>2. {{ __('Informações de cadastro') }}</p>
            </div>
        </div>

        {{-- Formulário principal --}}
        <div class="container card">
            <br>
            {{-- Nome --}}
            <div class="form-group row">
                <div class="col-md-12">
                    <label for="nome" class="col-form-label required-field">{{ __('Nome completo') }}</label>
                    <input id="nome" type="text"
                        class="form-control apenasLetras @error('nome') is-invalid @enderror" name="nome"
                        value="{{ old('nome') }}" autocomplete="nome" autofocus required>
                    @error('nome')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            {{-- CPF | CNPJ | Passaporte --}}
            <div class="form-group row mb-3">
                <div class="col-md-6">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="customRadioInline1" name="documento_tipo" class="custom-control-input"
                            value="cpf" checked>
                        <label class="custom-control-label me-2" for="customRadioInline1">CPF</label>

                        <input type="radio" id="customRadioInline2" name="documento_tipo" class="custom-control-input"
                            value="cnpj" @error('cnpj') checked @enderror>
                        <label class="custom-control-label me-2" for="customRadioInline2">{{ __('CNPJ') }}</label>

                        <input type="radio" id="customRadioInline3" name="documento_tipo" class="custom-control-input"
                            value="passaporte" @error('passaporte') checked @enderror>
                        <label class="custom-control-label" for="customRadioInline3">{{ __('Passaporte') }}</label>
                    </div>

                    {{-- Campo CPF --}}
                    <div id="fieldCPF" class="mt-2">
                        <input id="cpf" type="text" class="form-control @error('cpf') is-invalid @enderror"
                            name="cpf" value="{{ old('cpf') }}" placeholder="CPF">
                        @error('cpf')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    {{-- Campo CNPJ --}}
                    <div id="fieldCNPJ" class="mt-2" style="display: none;">
                        <input id="cnpj" type="text" class="form-control @error('cnpj') is-invalid @enderror"
                            name="cnpj" value="{{ old('cnpj') }}" placeholder="{{ __('CNPJ') }}">
                        @error('cnpj')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    {{-- Campo Passaporte --}}
                    <div id="fieldPassaporte" class="mt-2" style="display: none;">
                        <input id="passaporte" type="text" class="form-control @error('passaporte') is-invalid @enderror"
                            name="passaporte" value="{{ old('passaporte') }}" placeholder="{{ __('Passaporte') }}">
                        @error('passaporte')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>

                {{-- E-mail --}}
                <div class="col-md-6">
                    <label for="email" class="col-form-label required-field">{{ __('E-mail') }}</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            {{-- Alert info --}}
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                {{ __('Enviaremos um código de validação do seu cadastro para este e-mail.') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            {{-- Botão continuar --}}
            <div class="row form-group mb-3">
                <div class="col-md-10"></div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"
                        style="background-color: #034652; color: white; border-color: #034652;">
                        {{ __('Continuar') }}
                    </button>
                </div>
            </div>
        </div>
        </form>
    </div>
@endsection

@section('javascript')
    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    {{-- jquery.mask --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" crossorigin="anonymous">
    </script>
    {{-- Select2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" defer></script>
    {{-- intl-tel-input --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"
        crossorigin="anonymous" defer></script>

    <script type="text/javascript">
        $(document).ready(function() {
            // Máscaras
            $('#cpf').mask('000.000.000-00');
            $('#cnpj').mask('00.000.000/0000-00');
            $(".apenasLetras").mask("#", {
                maxlength: false,
                translation: {
                    '#': {
                        pattern: /[A-zÀ-ÿ ]/,
                        recursive: true
                    }
                }
            });

            // Alternar campos CPF/CNPJ/Passaporte
            $('input[name="documento_tipo"]').on('change', function() {
                var tipo = $(this).val();
                $('#fieldCPF, #fieldCNPJ, #fieldPassaporte').hide();
                if (tipo === 'cpf') $('#fieldCPF').show();
                if (tipo === 'cnpj') $('#fieldCNPJ').show();
                if (tipo === 'passaporte') $('#fieldPassaporte').show();
            }).filter(':checked').trigger('change');

            // Select2 com bandeirinhas
            function formatCountry(option) {
                if (!option.id) return option.text;
                var iso = $(option.element).data('iso');
                return '<span class="flag-icon flag-icon-' + iso + '"></span> ' + option.text;
            }
            $('#pais').select2({
                templateResult: formatCountry,
                templateSelection: formatCountry,
                escapeMarkup: function(m) {
                    return m;
                }
            });

            // CEP via ViaCEP
            function limpa_formulario_cep() {
                $('#rua, #bairro, #cidade, #uf').val('');
            }
            window.meu_callback = function(conteudo) {
                if (!("erro" in conteudo)) {
                    $('#rua').val(conteudo.logradouro);
                    $('#bairro').val(conteudo.bairro);
                    $('#cidade').val(conteudo.localidade);
                    $('#uf').val(conteudo.uf);
                } else {
                    limpa_formulario_cep();
                    alert("CEP não encontrado.");
                }
            };
            window.pesquisacep = function(valor) {
                var cep = valor.replace(/\D/g, '');
                if (cep !== "" && /^[0-9]{8}$/.test(cep)) {
                    $('#rua, #bairro, #cidade, #uf').val('...');
                    var script = document.createElement('script');
                    script.src = 'https://viacep.com.br/ws/' + cep + '/json/?callback=meu_callback';
                    document.body.appendChild(script);
                } else {
                    limpa_formulario_cep();
                    if (valor !== "") alert("Formato de CEP inválido.");
                }
            };
            $('#cep').mask('00000-000').on('blur', function() {
                pesquisacep(this.value);
            });
        });
    </script>

    {{-- Seu script de celular.js, se precisar --}}
    <script src="{{ asset('js/celular.js') }}" defer></script>
@endsection
