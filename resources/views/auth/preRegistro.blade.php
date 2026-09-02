@extends('layouts.app')

@section('content')
    <div class="container content mb-5 position-relative">
        {{-- CSS de bandeiras e intl-tel-input --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/6.6.6/css/flag-icons.min.css"
            crossorigin="anonymous" referrerpolicy="no-referrer" />
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
        </style>

        <br><br>

        <div class="row titulo text-center" style="color: #034652;">
            <h2 style="font-weight: bold;">{{ __('Cadastro') }}</h2>
        </div>

        @php
            $selecionado = old('pais', 'brasil');
            $paises = config('paises');
        @endphp

        @auth
            <form method="POST" action="{{ route('administrador.criarUsuario', app()->getLocale()) }}">
        @else
            <form method="POST" action="{{ route('enviarCodigo') }}">
        @endif
        @csrf

        {{-- País --}}
        <div class="form-group row my-3">
            <div class="col-md-12">
                <label for="pais" class="col-form-label">{{ __('País') }}</label>
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
                    <label for="nome" class="col-form-label">{{ __('Nome completo') }}</label>
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
                    <div class="pt-1">
                        <div class="form-check form-check-inline">
                            <input type="radio" name="documento_tipo" class="form-check-input"
                                value="cpf" @checked(old('documento_tipo', 'cpf') === 'cpf')>
                            <label class="form-check-label me-2" for="cpf">CPF</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" name="documento_tipo" class="form-check-input"
                                value="cnpj" @checked(old('documento_tipo', 'cpf') === 'cnpj')>
                            <label class="form-check-label me-2" for="cnpj">{{ __('CNPJ') }}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" name="documento_tipo" class="form-check-input"
                                value="passaporte" @checked(old('documento_tipo', 'cpf') === 'passaporte')>
                            <label class="form-check-label" for="passaporte">{{ __('Passaporte') }}</label>
                        </div>
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
                    <label for="email" class="col-form-label">{{ __('E-mail') }}</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            {{-- Alert info --}}
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
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

    {{-- jquery.mask --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" crossorigin="anonymous">
    </script>

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
                    '#': {pattern: /[A-zÀ-ÿ0-9\s\-\.\(\)\[\]\{\}\/\\,;&@#$%*+=|<>!?~`'"]/, recursive: true}
                }
            });

            // Alternar campos CPF/CNPJ/Passaporte
            function toggleDocumentoFields(clear = false) {
                const tipo = $('input[name="documento_tipo"]:checked').val();

                $('#fieldCPF, #fieldCNPJ, #fieldPassaporte').hide();

                if (clear) {
                    if (tipo !== 'cpf') $('#fieldCPF input').val('');
                    if (tipo !== 'cnpj') $('#fieldCNPJ input').val('');
                    if (tipo !== 'passaporte') $('#fieldPassaporte input').val('');
                }

                if (tipo === 'cpf') $('#fieldCPF').show();
                if (tipo === 'cnpj') $('#fieldCNPJ').show();
                if (tipo === 'passaporte') $('#fieldPassaporte').show();
            }

            $('input[name="documento_tipo"]').on('change', function () {
                toggleDocumentoFields(true);
            });

            toggleDocumentoFields(false);

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
