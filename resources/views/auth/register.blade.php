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

            .required-field::after {
                content: "*";
                color: #D44100;
                margin-left: 2px;
            }
        </style>

        @if(session('sucesso'))
            <div class="alert alert-success">
                {{ session('sucesso') }}
            </div>
        @endif

        @if(session('erro'))
            <div class="alert alert-danger">
                {{ session('erro') }}
            </div>
        @endif

        <div class="row titulo text-center mt-3" style="color: #034652;">
            <h2 style="font-weight: bold;">{{__('Cadastro')}}</h2>
        </div>

        @if(Auth::check())
            <form method="POST" action="{{ route('administrador.criarUsuario', app()->getLocale()) }}">
        @else
                <form method="POST" action="{{ route('register', app()->getLocale())}}">
            @endif
                <div id="etapa-1">
                    <div class="etapas mt-3" style="font-weight: 500;">
                        <div class="etapa">
                            <p>1. {{ __('Validação de cadastro') }}</p>
                        </div>
                        <div class="etapa ativa">
                            <p>2. {{__('Informações de cadastro')}}</p>
                        </div>
                    </div>

                    <input type="hidden" name="name" class="form-control" value="{{ session('nome') ?? old('nome') }}">
                    <input type="hidden" name="email" class="form-control" value="{{ session('email') ?? old('email') }}">
                    <input type="hidden" name="cpf" class="form-control" value="{{ session('cpf') ?? old('cpf') }}">
                    <input type="hidden" name="cnpj" class="form-control" value="{{ session('cnpj') ?? old('cnpj') }}">
                    <input type="hidden" name="passaporte" class="form-control"
                        value="{{ session('passaporte') ?? old('passaporte') }}">
                    <input type="hidden" name="pais" class="form-control" value="{{ session('pais') }}">

                    @csrf
                    {{-- Nome | CPF --}}
                    <div class="container card my-3">
                        <div class="row mt-3">
                            <div class="col-md-8">
                                <div>
                                    <span class="h5" style="color: #034652; font-weight: bold;">Dados pessoais</span>
                                </div>
                            </div>
                        </div>

                        <hr style="border-top: 1px solid#034652">

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="name"
                                    class="col-form-label required-field"><strong>{{ __('Nome completo') }}</strong></label>
                                <input id="name" type="text"
                                    class="form-control apenasLetras @error('name') is-invalid @enderror" name="name"
                                    value="{{ session('nome') ?? old('name') }}" autocomplete="name" autofocus disabled>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ __($message) }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="nomeSocial"
                                    class="col-form-label"><strong>{{ __('Nome social') }}</strong></label>
                                <input id="nomeSocial" type="text"
                                    class="form-control apenasLetras @error('nomeSocial') is-invalid @enderror"
                                    name="nomeSocial" value="{{ old('nomeSocial') }}" autocomplete="nomeSocial" autofocus>

                                @error('nomeSocial')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ __($message) }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                @if(session('cpf') || old('cpf'))
                                    <div class="custom-control custom-radio custom-control-inline col-form-label">
                                        <input type="radio" id="customRadioInline1" name="customRadioInline"
                                            class="custom-control-input" checked>
                                        <label class="custom-control-label me-2"
                                            for="customRadioInline1"><strong>CPF</strong></label>

                                        <input type="radio" @error('passaporte') checked @enderror id="customRadioInline2"
                                            name="customRadioInline" class="custom-control-input">
                                        <label class="custom-control-label me-2"
                                            for="customRadioInline2"><strong>{{__('CNPJ')}}</strong></label>

                                        <input type="radio" @error('passaporte') checked @enderror id="customRadioInline3"
                                            name="customRadioInline" class="custom-control-input">
                                        <label class="custom-control-label "
                                            for="customRadioInline3"><strong>{{__('Passaporte')}}</strong></label>
                                    </div>

                                    <div id="fieldCPF" @error('cpf') style="display: none" @enderror>
                                        <input id="cpf" type="text" class="form-control @error('cpf') is-invalid @enderror"
                                            name="cpf" value="{{ session('cpf') ?? old('cpf') }}" autocomplete="cpf"
                                            placeholder="CPF" autofocus disabled>

                                        @error('cpf')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ __($message) }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                @elseif(session('cnpj') || old('cnpj'))
                                    <div class="custom-control custom-radio custom-control-inline col-form-label">
                                        <input type="radio" id="customRadioInline1" name="customRadioInline"
                                            class="custom-control-input">
                                        <label class="custom-control-label me-2"
                                            for="customRadioInline1"><strong>CPF</strong></label>

                                        <input type="radio" @error('passaporte') checked @enderror id="customRadioInline2"
                                            name="customRadioInline" class="custom-control-input" checked>
                                        <label class="custom-control-label me-2"
                                            for="customRadioInline2"><strong>{{__('CNPJ')}}</strong></label>

                                        <input type="radio" @error('passaporte') checked @enderror id="customRadioInline3"
                                            name="customRadioInline" class="custom-control-input">
                                        <label class="custom-control-label "
                                            for="customRadioInline3"><strong>{{__('Passaporte')}}</strong></label>
                                    </div>

                                    <div id="fieldCNPJ" @error('cnpj') style="display: block" @enderror style="display: block">
                                        <input id="cnpj" type="text" class="form-control @error('cnpj') is-invalid @enderror"
                                            name="cnpj" placeholder="{{__('CNPJ')}}"
                                            value="{{ session('cnpj') ?? old('cnpj') }}" autocomplete="cnpj" autofocus disabled>

                                        @error('cnpj')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ __($message) }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                @elseif(session('passaporte') || old('passaporte'))
                                    <div class="custom-control custom-radio custom-control-inline col-form-label">
                                        <input type="radio" id="customRadioInline1" name="customRadioInline"
                                            class="custom-control-input">
                                        <label class="custom-control-label me-2"
                                            for="customRadioInline1"><strong>CPF</strong></label>

                                        <input type="radio" @error('passaporte') checked @enderror id="customRadioInline2"
                                            name="customRadioInline" class="custom-control-input">
                                        <label class="custom-control-label me-2"
                                            for="customRadioInline2"><strong>{{__('CNPJ')}}</strong></label>

                                        <input type="radio" @error('passaporte') checked @enderror id="customRadioInline3"
                                            name="customRadioInline" class="custom-control-input" checked>
                                        <label class="custom-control-label "
                                            for="customRadioInline3"><strong>{{__('Passaporte')}}</strong></label>
                                    </div>

                                    <div id="fieldPassaporte" @error('passaporte') style="display: block" @enderror
                                        style="display: block">
                                        <input id="passaporte" type="text"
                                            class="form-control @error('passaporte') is-invalid @enderror" name="passaporte"
                                            placeholder="{{__('Passaporte')}}"
                                            value="{{ session('passaporte') ?? old('passaporte') }}" autocomplete="passaporte"
                                            autofocus disabled>

                                        @error('passaporte')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ __($message) }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label for="instituicao"
                                    class="col-form-label required-field"><strong>{{ __('Instituição') }}</strong></label>
                                <input id="instituicao" type="text"
                                    class="form-control apenasLetras @error('instituicao') is-invalid @enderror"
                                    name="instituicao" value="{{ old('instituicao') }}" autocomplete="instituicao"
                                    autofocus>

                                @error('instituicao')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ __($message) }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Instituição de Ensino e Celular --}}
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="celular"
                                    class="col-form-label required-field"><strong>{{ __('Celular') }}</strong></label><br>
                                <input id="phone" class="form-control celular @error('celular') is-invalid @enderror"
                                    type="tel" name="celular" value="{{old('celular')}}" style="width: 100% !important;"
                                    required autocomplete="celular" onkeyup="process(event)">
                                <div class="alert alert-info mt-1" style="display: none"></div>
                                <div id="celular-invalido" class="alert alert-danger mt-1" role="alert"
                                    style="display: none"></div>

                                @error('celular')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ __($message) }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="dataNascimento"
                                    class="col-form-label required-field"><strong>{{ __('Data de nascimento') }}</strong></label>
                                <input id="dataNascimento" type="date"
                                    class="form-control @error('dataNascimento') is-invalid @enderror" name="dataNascimento"
                                    value="{{ old('dataNascimento')}}" autocomplete="dataNascimento" required>

                                @error('dataNascimento')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ __($message) }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="email"
                                    class="col-form-label required-field"><strong>{{ __('E-mail') }}</strong></label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ session('email') ?? old('email')}}" autocomplete="email"
                                    disabled>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ __($message) }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Email | Senha | Confirmar Senha --}}
                        <div class="form-group row mb-3">
                            <div class="col-md-6">
                                <label for="password"
                                    class="col-form-label required-field"><strong>{{ __('Senha') }}</strong></label>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    autocomplete="new-password">
                                <small>{{__('OBS: A senha deve ter no mínimo 8 caracteres (letras ou números)')}}.</small>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ __($message) }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password-confirm"
                                    class="col-form-label required-field"><strong>{{ __('Confirmar senha') }}</strong></label>
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" autocomplete="new-password">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container card my-3" style="font-weight: 500;">
                    <div class="row mt-3">
                        <div class="col-md-8">
                            <div>
                                <span class="h5" style="color: #034652; font-weight: bold;">Endereço</span>
                            </div>
                        </div>
                    </div>

                    <hr style="border-top: 1px solid#034652">
                    {{-- Rua | Número | Bairro --}}

                    @if(session('pais') == 'brasil' || session('pais') == null)
                        <div class="form-group row mt-3">
                            <div class="col-md-12">
                                <label for="cep"
                                    class="col-form-label required-field"><strong>{{ __('CEP') }}@if($pais != 'outro')
                                    @endif</strong></label>
                                <input value="{{old('cep')}}" id="cep" type="text" autocomplete="cep" name="cep" autofocus
                                    class="form-control field__input a-field__input" placeholder="{{__('CEP')}}" size="10"
                                    maxlength="9" @if($pais != 'outro') required @endif>
                                @error('cep')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ __($message) }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    @else
                        <div class="form-group row mt-3">
                            <div class="col-md-12">
                                <label for="cep"
                                    class="col-form-label required-field"><strong>{{ __('CEP/Código Postal') }}</strong></label>
                                <input value="{{old('cep')}}" id="cepOutroPais" type="text"
                                    oninput="this.value = this.value.replace(/[^a-zA-Z0-9\- ]/g, '')" autocomplete="cep"
                                    name="cep" autofocus class="form-control field__input a-field__input"
                                    placeholder="{{__('CEP')}}" size="10" maxlength="10">
                            </div>
                        </div>
                    @endif
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="rua" class="col-form-label required-field"><strong>{{ __('Rua') }}</strong></label>
                            <input value="{{old('rua')}}" id="rua" type="text"
                                class="form-control @error('rua') is-invalid @enderror" name="rua"
                                autocomplete="new-password" required>

                            @error('rua')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ __($message) }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="numero"
                                class="col-form-label required-field"><strong>{{ __('Número') }}@if($pais != 'outro')
                                @endif</strong></label>
                            <input value="{{old('numero')}}" id="numero" type="text"
                                class="form-control @error('numero') is-invalid @enderror" name="numero"
                                autocomplete="numero" maxlength="10" @if($pais != 'outro') required @endif>

                            @error('numero')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ __($message) }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="bairro"
                                class="col-form-label required-field"><strong>{{ __('Bairro') }}</strong></label>
                            <input value="{{old('bairro')}}" id="bairro" type="text"
                                class="form-control @error('bairro') is-invalid @enderror" name="bairro"
                                autocomplete="bairro" required>

                            @error('bairro')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ __($message) }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="complemento" class="col-form-label"><strong>{{ __('Complemento') }}</strong></label>
                            <input type="text" value="{{old('complemento')}}" id="complemento"
                                class="form-control  @error('complemento') is-invalid @enderror" name="complemento">

                            @error('complemento')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ __($message) }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <div class="col-md-6">
                            <label for="cidade"
                                class="col-form-label required-field"><strong>{{ __('Cidade') }}</strong></label>
                            <input value="{{old('cidade')}}" id="cidade" type="text"
                                class="form-control apenasLetras @error('cidade') is-invalid @enderror" name="cidade"
                                autocomplete="cidade" required>

                            @error('cidade')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ __($message) }}</strong>
                                </span>
                            @enderror
                        </div>

                        @if(session('pais') == 'brasil' || session('pais') == null)
                            {{-- <div class="col-sm-6" id="groupformufinput">
                                <label for="ufInput" class="col-form-label"><strong>{{ __('UF') }}</strong>*</label>
                                <input type="text" value="{{old('uf')}}" id="ufInput"
                                    class="form-control  @error('uf') is-invalid @enderror" name="uf" required>

                                @error('uf')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ __($message) }}</strong>
                                </span>
                                @enderror
                            </div> --}}

                            <div class="col-md-6" id="groupformuf">
                                <label for="uf"
                                    class="col-form-label required-field"><strong>{{ __('Estado') }}</strong></label>
                                {{-- <input id="uf" type="text" class="form-control @error('uf') is-invalid @enderror" name="uf"
                                    value="{{ old('uf') }}" autocomplete="uf" autofocus> --}}
                                <select class="form-control @error('uf') is-invalid @enderror required-field" id="uf" name="uf"
                                    required>
                                    <option value="" disabled selected hidden>{{__()}}</option>
                                    <option @if(old('uf') == 'AC') selected @endif value="AC">Acre</option>
                                    <option @if(old('uf') == 'AL') selected @endif value="AL">Alagoas</option>
                                    <option @if(old('uf') == 'AP') selected @endif value="AP">Amapá</option>
                                    <option @if(old('uf') == 'AM') selected @endif value="AM">Amazonas</option>
                                    <option @if(old('uf') == 'BA') selected @endif value="BA">Bahia</option>
                                    <option @if(old('uf') == 'CE') selected @endif value="CE">Ceará</option>
                                    <option @if(old('uf') == 'DF') selected @endif value="DF">Distrito Federal</option>
                                    <option @if(old('uf') == 'ES') selected @endif value="ES">Espírito Santo</option>
                                    <option @if(old('uf') == 'GO') selected @endif value="GO">Goiás</option>
                                    <option @if(old('uf') == 'MA') selected @endif value="MA">Maranhão</option>
                                    <option @if(old('uf') == 'MT') selected @endif value="MT">Mato Grosso</option>
                                    <option @if(old('uf') == 'MS') selected @endif value="MS">Mato Grosso do Sul</option>
                                    <option @if(old('uf') == 'MG') selected @endif value="MG">Minas Gerais</option>
                                    <option @if(old('uf') == 'PA') selected @endif value="PA">Pará</option>
                                    <option @if(old('uf') == 'PB') selected @endif value="PB">Paraíba</option>
                                    <option @if(old('uf') == 'PR') selected @endif value="PR">Paraná</option>
                                    <option @if(old('uf') == 'PE') selected @endif value="PE">Pernambuco</option>
                                    <option @if(old('uf') == 'PI') selected @endif value="PI">Piauí</option>
                                    <option @if(old('uf') == 'RJ') selected @endif value="RJ">Rio de Janeiro</option>
                                    <option @if(old('uf') == 'RN') selected @endif value="RN">Rio Grande do Norte</option>
                                    <option @if(old('uf') == 'RS') selected @endif value="RS">Rio Grande do Sul</option>
                                    <option @if(old('uf') == 'RO') selected @endif value="RO">Rondônia</option>
                                    <option @if(old('uf') == 'RR') selected @endif value="RR">Roraima</option>
                                    <option @if(old('uf') == 'SC') selected @endif value="SC">Santa Catarina</option>
                                    <option @if(old('uf') == 'SP') selected @endif value="SP">São Paulo</option>
                                    <option @if(old('uf') == 'SE') selected @endif value="SE">Sergipe</option>
                                    <option @if(old('uf') == 'TO') selected @endif value="TO">Tocantins</option>
                                </select>

                                @error('uf')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ __($message) }}</strong>
                                    </span>
                                @enderror
                            </div>
                        @else
                            <div class="col-md-6" id="">
                                <label for="uf"
                                    class="col-form-label required-field"><strong>{{ __('Estado/Província/Região') }}</strong></label>
                                <input type="text" value="{{old('uf')}}" id="uf"
                                    class="form-control  @error('uf') is-invalid @enderror" name="uf">

                                @error('uf')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ __($message) }}</strong>
                                    </span>
                                @enderror
                            </div>
                        @endif
                    </div>


                </div>

                <div class="container card my-3" style="font-weight: 500;">
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <span class="h5" style="color: #034652; font-weight: bold;">Perfil social e identitário</span>
                        </div>
                    </div>

                    <hr style="border-top: 1px solid #034652">

                    <div>
                        {{-- Gênero --}}
                        <div>
                            <div class="form-group mt-3">
                                <label class="col-form-label required-field"><strong>Gênero</strong></label>
                                <div>
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
                                    @foreach($generos as $key => $label)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="genero" id="genero_{{ $key }}"
                                                value="{{ $key }}" @if ($loop->first) required @endif>
                                            <label class="form-check-label" for="genero_{{ $key }}">{{ $label }}</label>
                                        </div>
                                    @endforeach
                                    <input type="text" name="outroGenero" id="outroGenero" class="form-control mt-2"
                                        placeholder="Se marcou 'Outro', especifique" style="max-width: 300px;"
                                        maxlength="200">
                                </div>
                            </div>
                        </div>

                        {{-- Raça (auto-declaração) --}}
                        <div>
                            <div class="form-group mt-3">
                                <label class="col-form-label required-field"><strong>Raça (auto-declaração)</strong></label>
                                <div>
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
                                    @foreach($racas as $key => $label)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="raca[]" id="raca_{{ $key }}"
                                                value="{{ $key }}">
                                            <label class="form-check-label" for="raca_{{ $key }}">{{ $label }}</label>
                                        </div>
                                    @endforeach
                                    <input type="text" name="outraRaca" id="outraRaca" class="form-control mt-4"
                                        placeholder="Se marcou 'Outra', especifique" style="max-width: 300px;"
                                        maxlength="200">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        {{-- Comunidade ou povo tradicional --}}
                        <div>
                            <div class="form-group mt-3">
                                <label class="col-form-label required-field"><strong>Você pertence ou atua em alguma
                                        comunidade ou povo tradicional?</strong></label>
                                <div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="comunidadeTradicional"
                                            id="comunidade_sim" value="1" required>
                                        <label class="form-check-label" for="comunidade_sim">Sim</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="comunidadeTradicional"
                                            id="comunidade_nao" value="0">
                                        <label class="form-check-label" for="comunidade_nao">Não</label>
                                    </div>
                                    <input type="text" name="nomeComunidadeTradicional" id="nomeComunidadeTradicional"
                                        class="form-control mt-2" placeholder="Se sim, qual?" style="max-width: 400px;"
                                        maxlength="200">
                                </div>
                            </div>
                        </div>

                        {{-- Pessoa LGBTQIA+ --}}
                        <div>
                            <div class="form-group mt-3">
                                <label class="col-form-label required-field"><strong>Você se identifica como Pessoa
                                        LGBTQIA+?</strong></label>
                                <div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="lgbtqia" id="lgbtqia_sim"
                                            value="1" required>
                                        <label class="form-check-label" for="lgbtqia_sim">Sim</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="lgbtqia" id="lgbtqia_nao"
                                            value="0">
                                        <label class="form-check-label" for="lgbtqia_nao">Não</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        {{-- Informações sobre necessidades especiais --}}
                        <div>
                            <div class="form-group mt-3">
                                <label class="col-form-label required-field"><strong>Informações sobre
                                        necessidades</strong></label>
                                <div>
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
                                    @foreach($necessidades as $key => $label)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="necessidadesEspeciais[]"
                                                id="necessidade_{{ $key }}" value="{{ $key }}">
                                            <label class="form-check-label" for="necessidade_{{ $key }}">{{ $label }}</label>
                                        </div>
                                    @endforeach
                                    <input type="text" name="outraNecessidadeEspecial" id="outraNecessidadeEspecial"
                                        class="form-control mt-2" placeholder="Se marcou 'Outra', especifique"
                                        style="max-width: 300px;" maxlength="200">
                                </div>
                            </div>
                        </div>

                        {{-- Pessoa com deficiência ou idosos --}}
                        <div>
                            <div class="form-group mt-3">
                                <label class="col-form-label required-field"><strong>Você é uma pessoa idosa ou com
                                        deficiência?</strong></label>
                                <div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="deficienciaIdoso"
                                            id="deficiencia_sim" value="1" required>
                                        <label class="form-check-label" for="deficiencia_sim">Sim</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="deficienciaIdoso"
                                            id="deficiencia_nao" value="0">
                                        <label class="form-check-label" for="deficiencia_nao">Não</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div>
                            <div class="form-group mt-3">
                                <label class="col-form-label required-field"><strong>Você é uma pessoa associada ao Centro
                                        Paulo Freire-Estudos e Pesquisas?</strong></label>
                                <div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="associadoCPFreire"
                                            id="associadoCPFreire_sim" value="1" required>
                                        <label class="form-check-label" for="associadoCPFreire_sim">Sim</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="associadoCPFreire"
                                            id="associadoCPFreire_nao" value="0">
                                        <label class="form-check-label" for="associadoCPFreire_nao">Não</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="form-group mt-3">
                                <label class="col-form-label required-field"><strong>Se não, gostaria de receber mais
                                        informações sobre a associação do CPFreire?</strong></label>
                                <div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="receberInfoCPFreire"
                                            id="receberInfoCPFreire_sim" value="1" required>
                                        <label class="form-check-label" for="receberInfoCPFreire_sim">Sim</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="receberInfoCPFreire"
                                            id="receberInfoCPFreire_nao" value="0">
                                        <label class="form-check-label" for="receberInfoCPFreire_nao">Não</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        {{-- Participa de organização, rede ou movimento --}}
                        <div>
                            <div class="form-group mt-3">
                                <label class="col-form-label required-field"><strong>Você participa de alguma organização,
                                        rede ou movimento?</strong></label>
                                <div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="participacaoOrganizacao"
                                            id="participa_sim" value="1" required>
                                        <label class="form-check-label" for="participa_sim">Sim</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="participacaoOrganizacao"
                                            id="participa_nao" value="0">
                                        <label class="form-check-label" for="participa_nao">Não</label>
                                    </div>
                                    <input type="text" name="nomeOrganizacao" id="nomeOrganizacao" class="form-control mt-2"
                                        placeholder="Se sim, qual?" style="max-width: 400px;" maxlength="200">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div>
                            <div class="form-group mt-3">
                                <label class="col-form-label required-field"><strong>Você já participou de algum estudo
                                        sobre a Pedagogia Freireana?</strong></label>
                                <div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="estudoPedagogiaFreiriana"
                                            id="estudoPedagogiaFreiriana_sim" value="1" required>
                                        <label class="form-check-label" for="estudoPedagogiaFreiriana_sim">Sim</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="estudoPedagogiaFreiriana"
                                            id="estudoPedagogiaFreiriana_nao" value="0">
                                        <label class="form-check-label" for="estudoPedagogiaFreiriana_nao">Não</label>
                                    </div>

                                    <input type="text" name="pedagogiasFreirianas" id="pedagogiasFreirianas"
                                        class="form-control mt-2" placeholder="Se sim, quais?" style="max-width: 400px;"
                                        maxlength="200">
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="form-group mt-3">
                                <label class="col-form-label required-field"><strong>Se não, gostaria de
                                        participar?</strong></label>
                                <div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                            name="participarEstudoPedagogiaFreiriana"
                                            id="participarEstudoPedagogiaFreiriana_sim" value="1" required>
                                        <label class="form-check-label"
                                            for="participarEstudoPedagogiaFreiriana_sim">Sim</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                            name="participarEstudoPedagogiaFreiriana"
                                            id="participarEstudoPedagogiaFreiriana_nao" value="0">
                                        <label class="form-check-label"
                                            for="participarEstudoPedagogiaFreiriana_nao">Não</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="my-3">
                            <input name="termos" class="form-check-input " type="checkbox" value="1" id="termos"
                                required>
                            <label class="form-check-label required-field" for="termos">
                                {{ __('Concordo e respeitarei os') }}
                                <a href="#modal-termo-de-uso" data-bs-toggle="modal"
                                    data-bs-target="#modal-termo-de-uso">Termos de uso da plataforma</a>.
                            </label>
                        </div>
                    </div>



                    <div class="row form-group my-3">
                        <div class="col-md-10"></div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100"
                                style="background-color: #034652; color: white; border-color: #034652;">
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
        $(document).ready(function ($) {

            $('#cpf').mask('000.000.000-00');
            $('#cnpj').mask('00.000.000/0000-00');
            if ($('html').attr('lang') == 'en') {
            } else if ($('html').attr('lang') == 'pt-BR') {
                $('#cep').blur(function () {
                    pesquisacep(this.value);
                });
                var SPMaskBehavior = function (val) {
                    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
                },
                    spOptions = {
                        onKeyPress: function (val, e, field, options) {
                            field.mask(SPMaskBehavior.apply({}, arguments), options);
                        }
                    };
                //$('#celular').mask(SPMaskBehavior, spOptions);
                $('#cep').mask('00000-000');
            }
            $(".apenasLetras").mask("#", {
                maxlength: false,
                translation: {
                    '#': { pattern: /[A-zÀ-ÿ ]/, recursive: true }
                }
            });
            //$('#numero').mask('0000000000000');

        });
        function limpa_formulário_cep() {
            //Limpa valores do formulário de cep.
            document.getElementById('rua').value = ("");
            document.getElementById('bairro').value = ("");
            document.getElementById('cidade').value = ("");
            document.getElementById('uf').value = ("");
        }

        function meu_callback(conteudo) {
            if (!("erro" in conteudo)) {
                //Atualiza os campos com os valores.
                document.getElementById('rua').value = (conteudo.logradouro);
                document.getElementById('bairro').value = (conteudo.bairro);
                document.getElementById('cidade').value = (conteudo.localidade);
                document.getElementById('uf').value = (conteudo.uf);

            } //end if.
            else {
                //CEP não Encontrado.
                limpa_formulário_cep();
                alert("CEP não encontrado.");
            }
        }

        function pesquisacep(valor) {

            //Nova variável "cep" somente com dígitos.
            var cep = valor.replace(/\D/g, '');

            //Verifica se campo cep possui valor informado.
            if (cep != "") {

                //Expressão regular para validar o CEP.
                var validacep = /^[0-9]{8}$/;

                //Valida o formato do CEP.
                if (validacep.test(cep)) {

                    //Preenche os campos com "..." enquanto consulta webservice.
                    document.getElementById('rua').value = "...";
                    document.getElementById('bairro').value = "...";
                    document.getElementById('cidade').value = "...";
                    document.getElementById('uf').value = "...";


                    //Cria um elemento javascript.
                    var script = document.createElement('script');

                    //Sincroniza com o callback.
                    script.src = 'https://viacep.com.br/ws/' + cep + '/json/?callback=meu_callback';

                    //Insere script no documento e carrega o conteúdo.
                    document.body.appendChild(script);

                } //end if.
                else {
                    //cep é inválido.
                    limpa_formulário_cep();
                    alert("Formato de CEP inválido.");
                }
            } //end if.
            else {
                //cep sem valor, limpa formulário.
                limpa_formulário_cep();
            }
        };
    </script>
    <script src="{{ asset('js/celular.js') }}" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />

    <script type="text/javascript">
        $(document).ready(function () {
            // $("#fieldPassaporte").hide();
            $("#customRadioInline1").click(function () {
                $("#fieldPassaporte").hide();
                $("#fieldCNPJ").hide();
                $("#fieldCPF").show();
            });

            $("#customRadioInline2").click(function () {
                $("#fieldPassaporte").hide();
                $("#fieldCNPJ").show();
                $("#fieldCPF").hide();
            });

            $("#customRadioInline3").click(function () {
                $("#fieldPassaporte").show();
                $("#fieldCNPJ").hide();
                $("#fieldCPF").hide();
            });

        });

        function proximaEtapa() {
            document.getElementById('etapa-1').style.display = 'none';
            document.getElementById('etapa-2').style.display = 'block';
        }

        function etapaAnterior() {
            document.getElementById('etapa-1').style.display = 'block';
            document.getElementById('etapa-2').style.display = 'none';
        }

        $(document).ready(function () {
            setTimeout(function () {
                $('.iti').css('width', '100%');
                $('.iti input').css('width', '100%');
            }, 100); // pequeno atraso para garantir que o plugin já aplicou os elementos
        });
    </script>

@endsection