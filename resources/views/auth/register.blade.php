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

        @php
            /*
             * No cadastro o documento já foi definido/validado
             * na etapa anterior.
             */
            $documentoTipo = old(
                'documento_tipo',
                session('cpf') ? 'cpf' : (session('cnpj') ? 'cnpj' : (session('passaporte') ? 'passaporte' : null)),
            );

            $paisAtual = old('pais', session('pais', 'brasil'));
        @endphp

        <div class="row titulo text-center mt-3" style="color: #034652;">
            <h2 style="font-weight: bold;">
                {{ __('Cadastro') }}
            </h2>
        </div>

        @if (Auth::check())
            <form method="POST" action="{{ route('administrador.criarUsuario', app()->getLocale()) }}">
            @else
                <form method="POST" action="{{ route('register', app()->getLocale()) }}">
        @endif

        @csrf

        {{-- =========================================================
            DADOS DA ETAPA ANTERIOR
        ========================================================== --}}

        <input type="hidden" name="name" value="{{ old('name', session('nome')) }}">

        <input type="hidden" name="email" value="{{ old('email', session('email')) }}">

        <input type="hidden" name="cpf" value="{{ old('cpf', session('cpf')) }}">

        <input type="hidden" name="cnpj" value="{{ old('cnpj', session('cnpj')) }}">

        <input type="hidden" name="passaporte" value="{{ old('passaporte', session('passaporte')) }}">

        <input type="hidden" name="documento_tipo" value="{{ $documentoTipo }}">

        <input type="hidden" name="pais" value="{{ $paisAtual }}">

        <div id="etapa-1">

            {{-- Etapas --}}
            <div class="etapas mt-3" style="font-weight: 500;">

                <div class="etapa">
                    <p>1. {{ __('Validação de cadastro') }}</p>
                </div>

                <div class="etapa ativa">
                    <p>2. {{ __('Informações de cadastro') }}</p>
                </div>

            </div>

            {{-- =========================================================
                DADOS PESSOAIS
            ========================================================== --}}

            <div class="container card my-3">

                <div class="row mt-3">

                    <div class="col-md-8">
                        <span class="h5" style="color: #034652; font-weight: bold;">
                            Dados pessoais
                        </span>
                    </div>

                </div>

                <hr style="border-top: 1px solid #034652">

                <div class="card-body">

                    <div class="row">

                        {{-- Nome --}}
                        <div class="form-group col-md-6">

                            <label for="name_display" class="col-form-label">
                                <strong>{{ __('Nome completo') }}</strong>
                            </label>

                            <input id="name_display" type="text"
                                class="form-control apenasLetras @error('name') is-invalid @enderror"
                                value="{{ old('name', session('nome')) }}" autocomplete="name" disabled>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ __($message) }}</strong>
                                </span>
                            @enderror

                        </div>

                        {{-- Nome social --}}
                        <div class="form-group col-md-6">

                            <label for="nomeSocial" class="col-form-label">
                                <strong>{{ __('Nome social') }}</strong>
                            </label>

                            <input id="nomeSocial" type="text"
                                class="form-control apenasLetras @error('nomeSocial') is-invalid @enderror"
                                name="nomeSocial" value="{{ old('nomeSocial') }}" autocomplete="nomeSocial">

                            @error('nomeSocial')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ __($message) }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>

                    {{-- Documento e instituição --}}
                    <div class="row">

                        <div class="col-md-6">

                            <div class="pt-2">

                                <div class="form-check form-check-inline">

                                    <input type="radio" class="form-check-input" id="documento_tipo_cpf"
                                        @checked($documentoTipo === 'cpf') disabled>

                                    <label class="form-check-label me-2" for="documento_tipo_cpf">
                                        CPF
                                    </label>

                                </div>

                                <div class="form-check form-check-inline">

                                    <input type="radio" class="form-check-input" id="documento_tipo_cnpj"
                                        @checked($documentoTipo === 'cnpj') disabled>

                                    <label class="form-check-label me-2" for="documento_tipo_cnpj">
                                        {{ __('CNPJ') }}
                                    </label>

                                </div>

                                <div class="form-check form-check-inline">

                                    <input type="radio" class="form-check-input" id="documento_tipo_passaporte"
                                        @checked($documentoTipo === 'passaporte') disabled>

                                    <label class="form-check-label" for="documento_tipo_passaporte">
                                        {{ __('Passaporte') }}
                                    </label>

                                </div>

                            </div>

                            {{-- CPF --}}
                            @if ($documentoTipo === 'cpf')
                                <div class="mt-2">

                                    <input id="cpf_display" type="text"
                                        class="form-control @error('cpf') is-invalid @enderror"
                                        value="{{ old('cpf', session('cpf')) }}" placeholder="CPF" disabled>

                                    @error('cpf')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                </div>
                            @endif

                            {{-- CNPJ --}}
                            @if ($documentoTipo === 'cnpj')
                                <div class="mt-2">

                                    <input id="cnpj_display" type="text"
                                        class="form-control @error('cnpj') is-invalid @enderror"
                                        value="{{ old('cnpj', session('cnpj')) }}" placeholder="{{ __('CNPJ') }}"
                                        disabled>

                                    @error('cnpj')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                </div>
                            @endif

                            {{-- Passaporte --}}
                            @if ($documentoTipo === 'passaporte')
                                <div class="mt-2">

                                    <input id="passaporte_display" type="text"
                                        class="form-control @error('passaporte') is-invalid @enderror"
                                        value="{{ old('passaporte', session('passaporte')) }}"
                                        placeholder="{{ __('Passaporte') }}" disabled>

                                    @error('passaporte')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                </div>
                            @endif

                        </div>

                        {{-- Instituição --}}
                        <div class="col-md-6">

                            <label for="instituicao" class="col-form-label">
                                <strong>{{ __('Instituição') }}</strong>
                            </label>

                            <input id="instituicao" type="text"
                                class="form-control apenasLetras @error('instituicao') is-invalid @enderror"
                                name="instituicao" value="{{ old('instituicao') }}" autocomplete="instituicao">

                            @error('instituicao')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ __($message) }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>

                    {{-- Celular | nascimento | email --}}
                    <div class="row">

                        <div class="col-md-4 form-group">

                            <label for="celular" class="col-form-label">
                                <strong>{{ __('Celular') }}</strong>
                            </label>

                            <br>

                            <input id="celular" class="form-control celular @error('celular') is-invalid @enderror"
                                type="tel" name="celular" value="{{ old('celular') }}"
                                style="width: 100% !important;" autocomplete="celular" required onkeyup="process(event)">

                            <div class="alert alert-info mt-1" style="display: none"></div>

                            <div id="celular-invalido" class="alert alert-danger mt-1" role="alert"
                                style="display: none"></div>

                            @error('celular')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ __($message) }}</strong>
                                </span>
                            @enderror

                        </div>

                        <div class="col-md-4 form-group">

                            <label for="data_nascimento" class="col-form-label">
                                <strong>{{ __('Data de nascimento') }}</strong>
                            </label>

                            <input id="data_nascimento" type="date"
                                class="form-control @error('data_nascimento') is-invalid @enderror" name="data_nascimento"
                                value="{{ old('data_nascimento') }}" autocomplete="data_nascimento" required>

                            @error('data_nascimento')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ __($message) }}</strong>
                                </span>
                            @enderror

                        </div>

                        <div class="col-md-4 form-group">

                            <label for="email_display" class="col-form-label">
                                <strong>{{ __('E-mail') }}</strong>
                            </label>

                            <input id="email_display" type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', session('email')) }}" autocomplete="email" disabled>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ __($message) }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>

                    {{-- Senha --}}
                    <div class="row mb-3">

                        <div class="col-md-6 form-group">

                            <label for="password" class="col-form-label">
                                <strong>{{ __('Senha') }}</strong>
                            </label>

                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password"
                                autocomplete="new-password" required>

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ __($message) }}</strong>
                                </span>
                            @enderror

                        </div>

                        <div class="col-md-6 form-group">

                            <label for="password-confirm" class="col-form-label">
                                <strong>{{ __('Confirmar senha') }}</strong>
                            </label>

                            <input id="password-confirm" type="password" class="form-control"
                                name="password_confirmation" autocomplete="new-password" required>

                        </div>

                        <div class="col-md-12">
                            <small class="text-muted">
                                A senha deve ter no mínimo 8 caracteres.
                                Digite a mesma senha nos dois campos.
                            </small>
                        </div>

                    </div>

                </div>

            </div>

            {{-- =========================================================
                ENDEREÇO
            ========================================================== --}}

            <div class="container card my-3" style="font-weight: 500;">

                <div class="row mt-3">

                    <div class="col-md-8">

                        <span class="h5" style="color: #034652; font-weight: bold;">
                            Endereço
                        </span>

                    </div>

                </div>

                <hr style="border-top: 1px solid #034652">

                <div class="card-body">

                    {{-- CEP --}}
                    <div class="row">

                        <div class="col-md-12 form-group">

                            @if ($paisAtual === 'brasil' || !$paisAtual)
                                <label for="cep" class="col-form-label">
                                    <strong>{{ __('CEP') }}</strong>
                                </label>

                                <input value="{{ old('cep') }}" id="cep" type="text" autocomplete="cep"
                                    name="cep" class="form-control @error('cep') is-invalid @enderror"
                                    placeholder="{{ __('CEP') }}" size="10" maxlength="9" required>
                            @else
                                <label for="cepOutroPais" class="col-form-label">
                                    <strong>{{ __('CEP/Código Postal') }}</strong>
                                </label>

                                <input value="{{ old('cep') }}" id="cepOutroPais" type="text" autocomplete="cep"
                                    name="cep" class="form-control @error('cep') is-invalid @enderror"
                                    placeholder="{{ __('CEP/Código Postal') }}" maxlength="10"
                                    oninput="this.value = this.value.replace(/[^a-zA-Z0-9\- ]/g, '')">
                            @endif

                            @error('cep')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ __($message) }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>

                    {{-- Rua | Número | Bairro --}}
                    <div class="row">

                        <div class="col-md-6 form-group">

                            <label for="rua" class="col-form-label">
                                <strong>{{ __('Rua') }}</strong>
                            </label>

                            <input value="{{ old('rua') }}" id="rua" type="text"
                                class="form-control @error('rua') is-invalid @enderror" name="rua"
                                autocomplete="rua" required>

                            @error('rua')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ __($message) }}</strong>
                                </span>
                            @enderror

                        </div>

                        <div class="col-md-3 form-group">

                            <label for="numero" class="col-form-label">
                                <strong>{{ __('Número') }}</strong>
                            </label>

                            <input value="{{ old('numero') }}" id="numero" type="text"
                                class="form-control @error('numero') is-invalid @enderror" name="numero"
                                autocomplete="numero" maxlength="10" @if ($paisAtual === 'brasil' || !$paisAtual) required @endif>

                            @error('numero')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ __($message) }}</strong>
                                </span>
                            @enderror

                        </div>

                        <div class="col-md-3 form-group">

                            <label for="bairro" class="col-form-label">
                                <strong>{{ __('Bairro') }}</strong>
                            </label>

                            <input value="{{ old('bairro') }}" id="bairro" type="text"
                                class="form-control @error('bairro') is-invalid @enderror" name="bairro"
                                autocomplete="bairro" required>

                            @error('bairro')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ __($message) }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>

                    {{-- Complemento | Cidade | Estado --}}
                    <div class="row pb-4">

                        <div class="col-md-6 form-group">

                            <label for="complemento" class="col-form-label">
                                <strong>{{ __('Complemento') }}</strong>
                            </label>

                            <input type="text" value="{{ old('complemento') }}" id="complemento"
                                class="form-control @error('complemento') is-invalid @enderror" name="complemento">

                            @error('complemento')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ __($message) }}</strong>
                                </span>
                            @enderror

                        </div>

                        <div class="col-md-3 form-group">

                            <label for="cidade" class="col-form-label">
                                <strong>{{ __('Cidade') }}</strong>
                            </label>

                            <input value="{{ old('cidade') }}" id="cidade" type="text"
                                class="form-control apenasLetras @error('cidade') is-invalid @enderror" name="cidade"
                                autocomplete="cidade" required>

                            @error('cidade')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ __($message) }}</strong>
                                </span>
                            @enderror

                        </div>

                        <div class="col-md-3 form-group">

                            @if ($paisAtual === 'brasil' || !$paisAtual)
                                <label for="uf" class="col-form-label">
                                    <strong>{{ __('Estado') }}</strong>
                                </label>

                                <select class="form-control @error('uf') is-invalid @enderror" id="uf"
                                    name="uf" required>

                                    <option value="" disabled @selected(!old('uf')) hidden>
                                        {{ __('Selecione o estado') }}
                                    </option>

                                    @foreach ($estados as $sigla => $nome)
                                        <option value="{{ $sigla }}" @selected(old('uf') == $sigla)>
                                            {{ $nome }}
                                        </option>
                                    @endforeach

                                </select>
                            @else
                                <label for="uf" class="col-form-label">
                                    <strong>{{ __('Estado/Província/Região') }}</strong>
                                </label>

                                <input type="text" value="{{ old('uf') }}" id="uf"
                                    class="form-control @error('uf') is-invalid @enderror" name="uf">
                            @endif

                            @error('uf')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ __($message) }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>

                </div>

            </div>

            {{-- =========================================================
                PERFIL SOCIAL E IDENTITÁRIO
            ========================================================== --}}

            <div class="container card my-3" style="font-weight: 500;">

                <div class="row mt-3">

                    <div class="col-md-12">

                        <span class="h5" style="color: #034652; font-weight: bold;">
                            Perfil social e identitário
                        </span>

                    </div>

                </div>

                <hr style="border-top: 1px solid #034652">

                <div class="card-body">

                    {{-- Gênero e raça --}}
                    <div class="row">

                        {{-- Gênero --}}
                        <div class="col-md-6 form-group">

                            <label class="col-form-label">
                                <strong>Gênero</strong>
                            </label>

                            @php
                                $generos = [
                                    'feminino' => 'Feminino',
                                    'masculino' => 'Masculino',
                                    'agênero' => 'Agênero',
                                    'nao_binario' => 'Não-Binário',
                                    'nao_conforme_ao_genero' => 'Não-conforme ao Gênero',
                                    'outro' => 'Outro',
                                    'prefiro_nao_responder' => 'Prefiro não responder',
                                ];
                            @endphp

                            @foreach ($generos as $key => $label)
                                <div class="form-check">

                                    <input class="form-check-input" type="radio" name="genero"
                                        id="genero_{{ $key }}" value="{{ $key }}"
                                        @checked(old('genero') === $key)>

                                    <label class="form-check-label" for="genero_{{ $key }}">
                                        {{ $label }}
                                    </label>

                                </div>
                            @endforeach

                            <input type="text" name="outroGenero" value="{{ old('outroGenero') }}" id="outroGenero"
                                class="form-control mt-2 @error('outroGenero') is-invalid @enderror"
                                placeholder="Se marcou 'Outro', especifique" style="max-width: 300px;" maxlength="200">

                            @error('outroGenero')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                        {{-- Raça --}}
                        <div class="col-md-6 form-group">

                            <label class="col-form-label">
                                <strong>Raça (auto-declaração)</strong>
                            </label>

                            @php
                                $racas = [
                                    'preta' => 'Preta',
                                    'parda' => 'Parda',
                                    'indigena' => 'Indígena',
                                    'amarela' => 'Amarela',
                                    'branca' => 'Branca',
                                    'outra_raca' => 'Outra (especificar)',
                                    'prefiro_nao_responder_raca' => 'Prefiro não responder',
                                ];
                            @endphp

                            @foreach ($racas as $key => $label)
                                <div class="form-check">

                                    <input class="form-check-input" type="radio" name="raca"
                                        id="raca_{{ $key }}" value="{{ $key }}"
                                        @checked(old('raca') === $key)>

                                    <label class="form-check-label" for="raca_{{ $key }}">
                                        {{ $label }}
                                    </label>

                                </div>
                            @endforeach

                            <input type="text" name="outraRaca" value="{{ old('outraRaca') }}" id="outraRaca"
                                class="form-control mt-4 @error('outraRaca') is-invalid @enderror"
                                placeholder="Se marcou 'Outra', especifique" style="max-width: 300px;" maxlength="200">

                            @error('outraRaca')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>

                    {{-- LGBTQIA --}}
                    <div class="row">

                        <div class="col-md-6 form-group mt-3">

                            <label class="col-form-label">
                                <strong>
                                    Você se identifica como Pessoa LGBTQIA+?
                                </strong>
                            </label>

                            <div class="form-check">

                                <input class="form-check-input" type="radio" name="lgbtqia" id="lgbtqia_sim"
                                    value="true" @checked(old('lgbtqia') === 'true')>

                                <label class="form-check-label" for="lgbtqia_sim">
                                    Sim
                                </label>

                            </div>

                            <div class="form-check">

                                <input class="form-check-input" type="radio" name="lgbtqia" id="lgbtqia_nao"
                                    value="false" @checked(old('lgbtqia') === 'false')>

                                <label class="form-check-label" for="lgbtqia_nao">
                                    Não
                                </label>

                            </div>

                        </div>

                    </div>

                    <div class="row">

                        {{-- Necessidades --}}
                        <div class="col-md-6 form-group mt-3">

                            <label class="col-form-label">
                                <strong>Informações sobre necessidades</strong>
                            </label>

                            @php
                                $necessidades = [
                                    'libras' => 'Libras',
                                    'audiodescricao' => 'Audiodescrição',
                                    'espaco_acessivel' => 'Espaço acessível',
                                    'acompanhante' => 'Acompanhante',
                                    'outra_necessidade' => 'Outra',
                                    'nenhuma' => 'Nenhuma',
                                ];
                            @endphp

                            @foreach ($necessidades as $key => $label)
                                <div class="form-check">

                                    <input class="form-check-input" type="checkbox" name="necessidadesEspeciais[]"
                                        id="necessidade_{{ $key }}" value="{{ $key }}"
                                        @checked(in_array($key, old('necessidadesEspeciais', [])))>

                                    <label class="form-check-label" for="necessidade_{{ $key }}">
                                        {{ $label }}
                                    </label>

                                </div>
                            @endforeach

                            <input type="text" name="outraNecessidadeEspecial"
                                value="{{ old('outraNecessidadeEspecial') }}" id="outraNecessidadeEspecial"
                                class="form-control mt-2" placeholder="Se marcou 'Outra', especifique"
                                style="max-width: 300px;" maxlength="200">

                        </div>

                        {{-- Pessoa idosa ou com deficiência --}}
                        <div class="col-md-6 form-group mt-3">

                            <label class="col-form-label">
                                <strong>
                                    Você é uma pessoa idosa ou com deficiência?
                                </strong>
                            </label>

                            <div class="form-check">

                                <input class="form-check-input" type="radio" name="deficienciaIdoso"
                                    id="deficiencia_sim" value="true" @checked(old('deficienciaIdoso') === 'true')>

                                <label class="form-check-label" for="deficiencia_sim">
                                    Sim
                                </label>

                            </div>

                            <div class="form-check">

                                <input class="form-check-input" type="radio" name="deficienciaIdoso"
                                    id="deficiencia_nao" value="false" @checked(old('deficienciaIdoso') === 'false')>

                                <label class="form-check-label" for="deficiencia_nao">
                                    Não
                                </label>

                            </div>

                        </div>

                    </div>

                    <div class="row">

                        {{-- Comunidade tradicional --}}
                        <div class="col-md-6 form-group mt-3">

                            <label class="col-form-label">
                                <strong>
                                    Você pertence ou atua em alguma comunidade ou povo tradicional?
                                </strong>
                            </label>

                            <div class="form-check">

                                <input class="form-check-input" type="radio" name="comunidadeTradicional"
                                    id="comunidade_sim" value="true" @checked(old('comunidadeTradicional') === 'true')>

                                <label class="form-check-label" for="comunidade_sim">
                                    Sim
                                </label>

                            </div>

                            <div class="form-check">

                                <input class="form-check-input" type="radio" name="comunidadeTradicional"
                                    id="comunidade_nao" value="false" @checked(old('comunidadeTradicional') === 'false')>

                                <label class="form-check-label" for="comunidade_nao">
                                    Não
                                </label>

                            </div>

                            <input type="text" name="nomeComunidadeTradicional"
                                value="{{ old('nomeComunidadeTradicional') }}" id="nomeComunidadeTradicional"
                                class="form-control mt-2" placeholder="Se sim, qual?" style="max-width: 400px;"
                                maxlength="200">

                        </div>

                        {{-- Organização --}}
                        <div class="col-md-6 form-group mt-3">

                            <label class="col-form-label">
                                <strong>
                                    Você participa de alguma organização, rede ou movimento?
                                </strong>
                            </label>

                            <div class="form-check">

                                <input class="form-check-input" type="radio" name="participacaoOrganizacao"
                                    id="participa_sim" value="true" @checked(old('participacaoOrganizacao') === 'true')>

                                <label class="form-check-label" for="participa_sim">
                                    Sim
                                </label>

                            </div>

                            <div class="form-check">

                                <input class="form-check-input" type="radio" name="participacaoOrganizacao"
                                    id="participa_nao" value="false" @checked(old('participacaoOrganizacao') === 'false')>

                                <label class="form-check-label" for="participa_nao">
                                    Não
                                </label>

                            </div>

                            <input type="text" name="nomeOrganizacao" value="{{ old('nomeOrganizacao') }}"
                                id="nomeOrganizacao" class="form-control mt-2" placeholder="Se sim, qual?"
                                style="max-width: 400px;" maxlength="200">

                        </div>

                    </div>

                    {{-- Vínculo --}}
                    <div class="row">

                        <div class="col-md-12 form-group mt-3">

                            <label for="vinculoInstitucional" class="col-form-label">
                                <strong>
                                    Informações Institucionais e de Atuação
                                    (preenchimento opcional)
                                </strong>
                            </label>

                            <textarea name="vinculoInstitucional" id="vinculoInstitucional" class="form-control"
                                placeholder="Vínculo institucional ou coletivo (se houver)" maxlength="1000" rows="5"
                                style="height: 120px; resize: none;">{{ old('vinculoInstitucional') }}</textarea>

                        </div>

                    </div>

                </div>

            </div>

            {{-- =========================================================
                TERMOS
            ========================================================== --}}

            <div class="container">

                {{-- Autorização de imagem --}}
                <div class="mb-3 mt-3">

                    <input name="termosImagem" class="form-check-input" type="checkbox" value="true"
                        id="termosImagem" @checked(old('termosImagem')) required>

                    <label class="form-check-label" for="termosImagem">
                        {{ __('Concordo com o') }}

                        <a href="#modal-termo-de-cessao" data-bs-toggle="modal" data-bs-target="#modal-termo-de-cessao">
                            Termo de autorização de imagem, voz e performance
                        </a>.
                    </label>

                </div>

                {{-- Termos da plataforma --}}
                <div class="mb-3">

                    <input name="termos" class="form-check-input" type="checkbox" value="true" id="termos"
                        @checked(old('termos')) required>

                    <label class="form-check-label" for="termos">
                        {{ __('Concordo e respeitarei os') }}

                        <a href="#modal-termo-de-uso" data-bs-toggle="modal" data-bs-target="#modal-termo-de-uso">
                            Termos de uso da plataforma
                        </a>.
                    </label>

                </div>

            </div>

            {{-- Submit --}}
            <div class="row form-group my-3">

                <div class="col-md-10"></div>

                <div class="col-md-2">

                    <button type="submit" class="btn btn-primary w-100"
                        style="
                            background-color: #034652;
                            color: white;
                            border-color: #034652;
                        ">
                        {{ __('Confirmar Cadastro') }}
                    </button>

                </div>

            </div>

        </div>

        </form>

    </div>

    @include('auth.modal-termo-de-uso')

@endsection


@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {

            /*
             * Máscaras
             */
            $('#cpf_display').mask('000.000.000-00');
            $('#cnpj_display').mask('00.000.000/0000-00');

            @if ($paisAtual === 'brasil' || !$paisAtual)

                $('#cep').mask('00000-000');

                $('#cep').blur(function() {
                    pesquisacep(this.value);
                });
            @endif


            /*
             * Campos que aceitam letras/caracteres permitidos.
             */
            $(".apenasLetras").mask("#", {
                maxlength: false,
                translation: {
                    '#': {
                        pattern: /[A-zÀ-ÿ0-9\s\-\.\(\)\[\]\{\}\/\\,;&@#$%*+=|<>!?\~`'"]/,
                        recursive: true
                    }
                }
            });


            /*
             * Gênero - "Outro"
             */
            function atualizarOutroGenero() {

                const genero = $('input[name="genero"]:checked').val();

                $('#outroGenero')
                    .prop('disabled', genero !== 'outro');

                if (genero !== 'outro') {
                    $('#outroGenero').val('');
                }
            }

            $('input[name="genero"]').on('change', atualizarOutroGenero);

            atualizarOutroGenero();


            /*
             * Raça - "Outra"
             */
            function atualizarOutraRaca() {

                const raca = $('input[name="raca"]:checked').val();

                $('#outraRaca')
                    .prop('disabled', raca !== 'outra_raca');

                if (raca !== 'outra_raca') {
                    $('#outraRaca').val('');
                }
            }

            $('input[name="raca"]').on('change', atualizarOutraRaca);

            atualizarOutraRaca();


            /*
             * Comunidade tradicional
             */
            function atualizarComunidadeTradicional() {

                const valor =
                    $('input[name="comunidadeTradicional"]:checked').val();

                $('#nomeComunidadeTradicional')
                    .prop('disabled', valor !== 'true');

                if (valor !== 'true') {
                    $('#nomeComunidadeTradicional').val('');
                }
            }

            $('input[name="comunidadeTradicional"]')
                .on('change', atualizarComunidadeTradicional);

            atualizarComunidadeTradicional();


            /*
             * Organização
             */
            function atualizarOrganizacao() {

                const valor =
                    $('input[name="participacaoOrganizacao"]:checked').val();

                $('#nomeOrganizacao')
                    .prop('disabled', valor !== 'true');

                if (valor !== 'true') {
                    $('#nomeOrganizacao').val('');
                }
            }

            $('input[name="participacaoOrganizacao"]')
                .on('change', atualizarOrganizacao);

            atualizarOrganizacao();


            /*
             * Necessidade especial "Outra"
             */
            function atualizarOutraNecessidade() {

                const marcada =
                    $('#necessidade_outra_necessidade').is(':checked');

                $('#outraNecessidadeEspecial')
                    .prop('disabled', !marcada);

                if (!marcada) {
                    $('#outraNecessidadeEspecial').val('');
                }
            }

            $('#necessidade_outra_necessidade')
                .on('change', atualizarOutraNecessidade);

            atualizarOutraNecessidade();


            /*
             * Evita marcar "Nenhuma" juntamente com outras necessidades.
             */
            $('input[name="necessidadesEspeciais[]"]').on('change', function() {

                if ($(this).val() === 'nenhuma' && $(this).is(':checked')) {

                    $('input[name="necessidadesEspeciais[]"]')
                        .not(this)
                        .prop('checked', false);

                } else if ($(this).is(':checked')) {

                    $('#necessidade_nenhuma')
                        .prop('checked', false);

                }

                atualizarOutraNecessidade();
            });

        });


        /*
         |--------------------------------------------------------------------------
         | ViaCEP
         |--------------------------------------------------------------------------
         */

        function limpa_formulário_cep() {

            document.getElementById('rua').value = '';
            document.getElementById('bairro').value = '';
            document.getElementById('cidade').value = '';
            document.getElementById('uf').value = '';

        }


        function meu_callback(conteudo) {

            if (!('erro' in conteudo)) {

                document.getElementById('rua').value =
                    conteudo.logradouro;

                document.getElementById('bairro').value =
                    conteudo.bairro;

                document.getElementById('cidade').value =
                    conteudo.localidade;

                document.getElementById('uf').value =
                    conteudo.uf;

            } else {

                limpa_formulário_cep();

                alert('CEP não encontrado.');

            }

        }


        function pesquisacep(valor) {

            const cep = valor.replace(/\D/g, '');

            if (!cep) {
                limpa_formulário_cep();
                return;
            }

            const validacep = /^[0-9]{8}$/;

            if (!validacep.test(cep)) {

                limpa_formulário_cep();

                alert('Formato de CEP inválido.');

                return;
            }

            document.getElementById('rua').value = '...';
            document.getElementById('bairro').value = '...';
            document.getElementById('cidade').value = '...';
            document.getElementById('uf').value = '...';

            const script = document.createElement('script');

            script.src =
                'https://viacep.com.br/ws/' +
                cep +
                '/json/?callback=meu_callback';

            document.body.appendChild(script);

        }
    </script>

    <script src="{{ asset('js/celular.js') }}" defer></script>

    <script src="{{ asset('js/jquery-mask-plugin.js') }}" defer></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />
@endsection
