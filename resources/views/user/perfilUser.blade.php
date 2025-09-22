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

        <div class="row titulo text-center mt-3" style="color: #034652;">
            <h2 style="font-weight: bold;">{{__('Meu Perfil')}}</h2>
        </div>

        @if(Auth::check())
            <form method="POST" action="{{ route('perfil.update') }}">
                @if ($pais == null && $end != null)
                    @php
                        $pais = $end?->pais;
                    @endphp
                @else
                    @php
                        $pais = 'brasil';
                    @endphp
                @endif

                        @endif
                        <div id="etapa-1">

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
                                    <input hidden name="id" value="{{$user->id}}">
                                    <div class="col-md-6">
                                        <label for="name" class="col-form-label"><strong>{{ __('Nome completo') }}</strong></label>
                                        <input id="name" type="text" class="form-control apenasLetras @error('name') is-invalid @enderror" name="name" @if(old('name') != null) value="{{old('name') }}"  @else value="{{$user->name}}" @endif   autocomplete="name" autofocus>

                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                <strong>{{ __($message) }}</strong>
                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="nomeSocial" class="col-form-label"><strong>{{ __('Nome social') }}</strong></label>
                                        <input id="nomeSocial" type="text" class="form-control apenasLetras @error('nomeSocial') is-invalid @enderror" name="nomeSocial" @if(old('nomeSocial') != null) value="{{ old('nomeSocial') }}" @else value="{{$perfilIdentitario?->nomeSocial}}" @endif autocomplete="nomeSocial" autofocus>

                                        @error('nomeSocial')
                                        <span class="invalid-feedback" role="alert">
                                <strong>{{ __($message) }}</strong>
                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6">
                                        @if($user->cpf != null)
                                            <div class="custom-control custom-radio custom-control-inline col-form-label">
                                                <input type="radio" id="customRadioInline1" name="customRadioInline" class="custom-control-input" @if($errors->has('cpf') || (!$errors->has('cnpj') && !$errors->has('passaporte'))) checked @endif>
                                                <label class="custom-control-label me-2" for="customRadioInline1"><strong>CPF</strong></label>

                                                <input type="radio" id="customRadioInline2" name="customRadioInline" class="custom-control-input" @if($errors->has('cnpj')) checked @endif>
                                                <label class="custom-control-label me-2" for="customRadioInline2"><strong>{{__('CNPJ')}}</strong></label>

                                                <input type="radio" id="customRadioInline3" name="customRadioInline" class="custom-control-input" @if($errors->has('passaporte')) checked @endif>
                                                <label class="custom-control-label " for="customRadioInline3"><strong>{{__('Passaporte')}}</strong></label>
                                            </div>

                                            <div id="fieldCPF" style="display: {{ $errors->has('cpf') || (!$errors->has('cnpj') && !$errors->has('passaporte')) ? 'block' : 'none' }}">
                                                <input id="cpf" type="text" class="form-control @error('cpf') is-invalid @enderror" name="cpf" @if(old('cpf') != null) value="{{old('cpf')}}" @else value="{{$user->cpf}}" @endif autocomplete="cpf" placeholder="CPF" autofocus >

                                                @error('cpf')
                                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ __($message) }}</strong>
                                    </span>
                                                @enderror
                                            </div>
                                        @elseif($user->cnpj != null)
                                            <div class="custom-control custom-radio custom-control-inline col-form-label">
                                                <input type="radio" id="customRadioInline1" name="customRadioInline" class="custom-control-input">
                                                <label class="custom-control-label me-2" for="customRadioInline1"><strong>CPF</strong></label>

                                                <input type="radio" @error('passaporte') checked @enderror id="customRadioInline2" name="customRadioInline" class="custom-control-input" checked>
                                                <label class="custom-control-label me-2" for="customRadioInline2"><strong>{{__('CNPJ')}}</strong></label>

                                                <input type="radio" @error('passaporte') checked @enderror id="customRadioInline3" name="customRadioInline" class="custom-control-input">
                                                <label class="custom-control-label " for="customRadioInline3"><strong>{{__('Passaporte')}}</strong></label>
                                            </div>

                                            <div id="fieldCNPJ" @error('cnpj') style="display: block" @enderror style="display: block">
                                                <input id="cnpj" type="text" class="form-control @error('cnpj') is-invalid @enderror" name="cnpj" @if(old('cnpj') != null) @else value="{{$user->cnpj}}" @endif autocomplete="cnpj" placeholder="CPNJ" autofocus >

                                                @error('cnpj')
                                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ __($message) }}</strong>
                                    </span>
                                                @enderror
                                            </div>
                                        @elseif($user->passaporte != null)
                                            <div class="custom-control custom-radio custom-control-inline col-form-label">
                                                <input type="radio" id="customRadioInline1" name="customRadioInline" class="custom-control-input">
                                                <label class="custom-control-label me-2" for="customRadioInline1"><strong>CPF</strong></label>

                                                <input type="radio" @error('passaporte') checked @enderror id="customRadioInline2" name="customRadioInline" class="custom-control-input">
                                                <label class="custom-control-label me-2" for="customRadioInline2"><strong>{{__('CNPJ')}}</strong></label>

                                                <input type="radio" @error('passaporte') checked @enderror id="customRadioInline3" name="customRadioInline" class="custom-control-input" checked>
                                                <label class="custom-control-label " for="customRadioInline3"><strong>{{__('Passaporte')}}</strong></label>
                                            </div>

                                            <div id="fieldPassaporte" @error('passaporte') style="display: block" @enderror style="display: block" >
                                                <input id="passaporte" type="text" class="form-control @error('passaporte') is-invalid @enderror" name="passaporte" @if(old('passaporte') != null) @else value="{{$user->passaporte}}" @endif autocomplete="passaporte" placeholder="PASSAPORTE" autofocus >

                                                @error('passaporte')
                                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ __($message) }}</strong>
                                    </span>
                                                @enderror
                                            </div>
                                        @else
                                            <div class="custom-control custom-radio custom-control-inline col-form-label">
                                                <input type="radio" id="customRadioInline1" name="customRadioInline" class="custom-control-input" checked>
                                                <label class="custom-control-label me-2" for="customRadioInline1"><strong>CPF</strong></label>

                                                <input type="radio" @error('passaporte') checked @enderror id="customRadioInline2" name="customRadioInline" class="custom-control-input">
                                                <label class="custom-control-label me-2" for="customRadioInline2"><strong>{{__('CNPJ')}}</strong></label>

                                                <input type="radio" @error('passaporte') checked @enderror id="customRadioInline3" name="customRadioInline" class="custom-control-input">
                                                <label class="custom-control-label " for="customRadioInline3"><strong>{{__('Passaporte')}}</strong></label>
                                            </div>

                                            <div id="fieldCPF" @error('cpf') style="display: none" @enderror>
                                                <input id="cpf" type="text" class="form-control @error('cpf') is-invalid @enderror" name="cpf" @if(old('cpf') != null) @else value="{{$user->cpf}}" @endif autocomplete="cpf" placeholder="CPF" autofocus >

                                                @error('cpf')
                                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ __($message) }}</strong>
                                    </span>
                                                @enderror
                                            </div>
                                        @endif
                                    </div>

                                    <div class="col-md-6">
                                        <label for="instituicao" class="col-form-label"><strong>{{ __('Instituição') }}</strong></label>
                                        <input id="instituicao" type="text" class="form-control apenasLetras @error('instituicao') is-invalid @enderror" name="instituicao" @if(old('instituicao') != null) value="{{ old('instituicao') }}" @else value="{{$user->instituicao}}" @endif autocomplete="instituicao" autofocus>

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
                                        <label for="celular" class="col-form-label "><strong>{{ __('Celular') }}</strong></label><br>
                                        <input id="phone" class="form-control celular @error('celular') is-invalid @enderror" type="text" name="celular" value="{{ old('full_number', $user->celular) }}"  autocomplete="celular" onkeyup="process(event)">
                                        <div class="alert alert-info mt-1" style="display: none"></div>
                                        <div id="celular-invalido" class="alert alert-danger mt-1" role="alert"   style="display: none"></div>

                                        @error('celular')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ __($message) }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="dataNascimento" class="col-form-label"><strong>{{ __('Data de nascimento') }}</strong></label>
                                        <input id="dataNascimento" type="date" class="form-control @error('dataNascimento') is-invalid @enderror" name="dataNascimento" @if(old('dataNascimento') != null)  value="{{ old('dataNascimento')}}" @else value="{{$perfilIdentitario?->dataNascimento}}" @endif autocomplete="dataNascimento" >

                                        @error('dataNascimento')
                                        <span class="invalid-feedback" role="alert">
                                <strong>{{ __($message) }}</strong>
                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="email" class="col-form-label"><strong>{{ __('E-mail') }}</strong></label>
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" @if(old('email') != null) value="{{old('email')}}" @else value="{{$user->email}}" @endif  autocomplete="email" >

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
                                        <label for="senha_atual" class="col-form-label"><strong>{{ __('Senha atual') }}</strong></label>
                                        <input id="senha_atual" type="password" class="form-control @error('senha_atual') is-invalid @enderror" name="senha_atual">
                                        <small>{{__('Para alterar a senha digite a atual e a nova')}}.</small>
                                        @error('senha_atual')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ __($message) }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="password" class="col-form-label"><strong>{{ __('Nova senha') }}</strong></label>
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password">
                                        <small>{{__('OBS: A senha deve ter no mínimo 8 caracteres (letras ou números)')}}.</small>
                                        @error('senha_atual')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ __($message) }}</strong>
                                        </span>
                                        @enderror
                                    </div>


                                    <div class="col-md-6">
                                        <label for="password-confirm" class="col-form-label"><strong>{{ __('Confirme a nova senha') }}</strong></label>
                                        <input id="password-confirm" type="password" class="form-control @error('password-confirm') is-invalid @enderror" name="password-confirm">
                                        @error('password-confirm')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
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


                            <div class="col-md-4">
                                <label for="pais" class="col-form-label">{{ __('País') }}*</label>
                                <select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" class="form-control @error('pais') is-invalid @enderror" id="pais">
                                    <option value="" disabled selected hidden>-- {{__('País')}} --</option>
                                    <option @if($pais == 'brasil') selected @endif value="/perfil/brasil">{{__('Brasil')}}</option>
                                    <option @if($pais == 'usa') selected @endif value="/perfil/usa">{{__('Estados Unidos da América')}}</option>
                                    <option @if($pais == 'outro') selected @endif value="/perfil/outro">{{__('Outro')}}</option>
                                </select>
                                <input type="hidden" name="pais" value="{{$pais}}">
                                <small>{{__('O formulário seguirá os padrões desse país')}}.</small>

                                @error('pais')
                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ __($message) }}</strong>
                                            </span>
                                @enderror
                            </div>


                            <hr style="border-top: 1px solid#034652">
                            {{-- Rua | Número | Bairro --}}

                            @if(session('pais') == 'brasil' || session('pais') == null)
                                <div class="form-group row mt-3">
                                    <div class="col-md-12">
                                        <label for="cep" class="col-form-label"><strong>{{ __('CEP') }}@if($pais != 'outro') @endif</strong></label>
                                        <input @if(old('cep') != null) value="{{old('cep')}}" @else value="{{$end?->cep}}" @endif id="cep" type="text"  autocomplete="cep" name="cep" autofocus class="form-control field__input a-field__input"  size="10" maxlength="9"  >
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
                                        <label for="cep" class="col-form-label"><strong>{{ __('CEP/Código Postal') }}</strong></label>
                                        <input @if(old('cep') != null) value="{{old('cep')}}" @else value="{{$end?->cep}}" @endif id="cepOutroPais" type="text"  oninput="this.value = this.value.replace(/[^a-zA-Z0-9\- ]/g, '')" autocomplete="cep" name="cep" autofocus class="form-control field__input a-field__input" placeholder="{{__('CEP')}}" size="10" maxlength="10">
                                    </div>
                                </div>
                            @endif

                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="rua" class="col-form-label "><strong>{{ __('Rua') }}</strong></label>
                                    <input id="rua" type="rua" class="form-control @error('rua') is-invalid @enderror" name="rua" @if(old('rua') != null) value="{{old('rua')}}" @else value="{{$end?->rua}}" @endif  autocomplete="rua" >

                                    @error('rua')
                                    <span class="invalid-feedback" role="alert">
                            <strong>{{ __($message) }}</strong>
                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="numero" class="col-form-label "><strong>{{ __('Número') }}@if($pais != 'outro') @endif</strong></label>
                                    <input @if(old('numero') != null) value="{{old('numero')}}" @else value="{{$end?->numero}}" @endif id="numero" type="number" class="form-control @error('numero') is-invalid @enderror" name="numero" autocomplete="numero" maxlength="10">

                                    @error('numero')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ __($message) }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="bairro" class="col-form-label"><strong>{{ __('Bairro') }}</strong></label>
                                    <input id="bairro" type="text" class="form-control @error('bairro') is-invalid @enderror" name="bairro" value="{{ old('bairro', $end?->bairro) }}"  autocomplete="bairro">

                                    @error('bairro')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ __($message) }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="complemento" class="col-form-label"><strong>{{ __('Complemento') }}</strong></label>
                                    <input type="text"  @if(old('complemento') != null)value="{{old('complemento')}}" @else value="{{$end?->complemento}}" @endif id="complemento" class="form-control  @error('complemento') is-invalid @enderror" name="complemento" >

                                    @error('complemento')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ __($message) }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <div class="col-md-6">
                                    <label for="cidade" class="col-form-label">{{ __('Cidade') }}</label>
                                    <input id="cidade" type="text" class="form-control @error('cidade') is-invalid @enderror" name="cidade" @if(old('cidade') != null) value="{{ old('cidade') }}" @else value="{{$end?->cidade}}" @endif autocomplete="cidade">

                                    @error('cidade')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ __($message) }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                @if(session('pais') == 'brasil' || session('pais') == null)
                                    {{-- <div class="col-sm-6" id="groupformufinput">
                                        <label for="ufInput" class="col-form-label"><strong>{{ __('UF') }}</strong>*</label>
                                        <input type="text" value="{{old('uf')}}" id="ufInput" class="form-control  @error('uf') is-invalid @enderror" name="uf" required>

                                        @error('uf')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ __($message) }}</strong>
                                            </span>
                                        @enderror
                                    </div> --}}

                                    <div class="col-md-6" id="groupformuf">
                                        <label for="uf" class="col-form-label"><strong>{{ __('Estado') }}</strong></label>
                                        {{-- <input id="uf" type="text" class="form-control @error('uf') is-invalid @enderror" name="uf" value="{{ old('uf') }}"  autocomplete="uf" autofocus> --}}
                                        <select class="form-control @error('uf') is-invalid @enderror required-field" id="uf" name="uf">
                                            <option value="" disabled selected hidden>{{ __() }}</option>
                                            <option @if(old('uf', $end?->uf) == 'AC') selected @endif value="AC">Acre</option>
                                            <option @if(old('uf', $end?->uf) == 'AL') selected @endif value="AL">Alagoas</option>
                                            <option @if(old('uf', $end?->uf) == 'AP') selected @endif value="AP">Amapá</option>
                                            <option @if(old('uf', $end?->uf) == 'AM') selected @endif value="AM">Amazonas</option>
                                            <option @if(old('uf', $end?->uf) == 'BA') selected @endif value="BA">Bahia</option>
                                            <option @if(old('uf', $end?->uf) == 'CE') selected @endif value="CE">Ceará</option>
                                            <option @if(old('uf', $end?->uf) == 'DF') selected @endif value="DF">Distrito Federal</option>
                                            <option @if(old('uf', $end?->uf) == 'ES') selected @endif value="ES">Espírito Santo</option>
                                            <option @if(old('uf', $end?->uf) == 'GO') selected @endif value="GO">Goiás</option>
                                            <option @if(old('uf', $end?->uf) == 'MA') selected @endif value="MA">Maranhão</option>
                                            <option @if(old('uf', $end?->uf) == 'MT') selected @endif value="MT">Mato Grosso</option>
                                            <option @if(old('uf', $end?->uf) == 'MS') selected @endif value="MS">Mato Grosso do Sul</option>
                                            <option @if(old('uf', $end?->uf) == 'MG') selected @endif value="MG">Minas Gerais</option>
                                            <option @if(old('uf', $end?->uf) == 'PA') selected @endif value="PA">Pará</option>
                                            <option @if(old('uf', $end?->uf) == 'PB') selected @endif value="PB">Paraíba</option>
                                            <option @if(old('uf', $end?->uf) == 'PR') selected @endif value="PR">Paraná</option>
                                            <option @if(old('uf', $end?->uf) == 'PE') selected @endif value="PE">Pernambuco</option>
                                            <option @if(old('uf', $end?->uf) == 'PI') selected @endif value="PI">Piauí</option>
                                            <option @if(old('uf', $end?->uf) == 'RJ') selected @endif value="RJ">Rio de Janeiro</option>
                                            <option @if(old('uf', $end?->uf) == 'RN') selected @endif value="RN">Rio Grande do Norte</option>
                                            <option @if(old('uf', $end?->uf) == 'RS') selected @endif value="RS">Rio Grande do Sul</option>
                                            <option @if(old('uf', $end?->uf) == 'RO') selected @endif value="RO">Rondônia</option>
                                            <option @if(old('uf', $end?->uf) == 'RR') selected @endif value="RR">Roraima</option>
                                            <option @if(old('uf', $end?->uf) == 'SC') selected @endif value="SC">Santa Catarina</option>
                                            <option @if(old('uf', $end?->uf) == 'SP') selected @endif value="SP">São Paulo</option>
                                            <option @if(old('uf', $end?->uf) == 'SE') selected @endif value="SE">Sergipe</option>
                                            <option @if(old('uf', $end?->uf) == 'TO') selected @endif value="TO">Tocantins</option>
                                        </select>

                                        @error('uf')
                                        <span class="invalid-feedback" role="alert">
                            <strong>{{ __($message) }}</strong>
                        </span>
                                        @enderror
                                    </div>
                                @else
                                    <div class="col-md-6" id="">
                                        <label for="uf" class="col-form-label required-field"><strong>{{ __('Estado/Província/Região') }}</strong></label>
                                        <input type="text" value="{{old('uf')}}" id="uf" class="form-control  @error('uf') is-invalid @enderror" name="uf" >

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


                            <div class="row">
                                {{-- Gênero --}}
                                <div class="col-md-6">
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
                                                <input class="form-check-input"
                                                       type="radio"
                                                       name="genero"
                                                       id="genero_{{ $key }}"
                                                       value="{{ $key }}"

                                                       @if (old('genero', $perfilIdentitario?->genero) == $key) checked @endif>
                                                <label class="form-check-label" for="genero_{{ $key }}">{{ $label }}</label>
                                            </div>
                                        @endforeach

                                        <input type="text" name="outroGenero" id="outroGenero" class="form-control mt-2" placeholder="Se marcou 'Outro', especifique" style="max-width: 300px;"
                                               value="{{ old('outroGenero', $perfilIdentitario?->outroGenero) }}">
                                    </div>
                                </div>

                                {{-- Raça (auto-declaração) --}}
                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label class="col-form-label">Raça (auto-declaração)</label>
                                        <div>
                                            @php
                                                $racas = [
                                                    'negra' => 'Negra',
                                                    'parda' => 'Parda',
                                                    'indigena' => 'Indígena',
                                                    'branca' => 'Branca',
                                                    'outra_raca' => 'Outra',
                                                    'prefiro_nao_responder_raca' => 'Prefiro não responder',
                                                ];
                                            @endphp
                                            @foreach($racas as $key => $label)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="raca" id="raca_{{ $key }}" value="{{ $key }}"
                                                           @if (old('raca', $perfilIdentitario?->raca) == $key) checked @endif>
                                                    <label class="form-check-label" for="raca_{{ $key }}">{{ $label }}</label>
                                                </div>
                                            @endforeach
                                            <input type="text" name="outraRaca" id="outraRaca" class="form-control mt-4" placeholder="Se marcou 'Outra', especifique" style="max-width: 300px;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                {{-- Comunidade ou povo tradicional --}}
                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label class="col-form-label">Você pertence ou atua em alguma comunidade ou povo tradicional?</label>
                                        <div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="comunidadeTradicional" id="comunidade_sim" value="true"
                                                       @if ((old('comunidadeTradicional', $perfilIdentitario?->comunidadeTradicional) === 'true') || ($perfilIdentitario?->comunidadeTradicional === true)) checked @endif>
                                                <label class="form-check-label" for="comunidade_sim">Sim</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="comunidadeTradicional" id="comunidade_nao" value="false"
                                                       @if ((old('comunidadeTradicional', $perfilIdentitario?->comunidadeTradicional) === 'false') || ($perfilIdentitario?->comunidadeTradicional === false)) checked @endif>
                                                <label class="form-check-label" for="comunidade_nao">Não</label>
                                            </div>
                                            <input type="text" name="nomeComunidadeTradicional" id="nomeComunidadeTradicional" class="form-control mt-2" placeholder="Se sim, qual?" style="max-width: 400px;"
                                                   value="{{ old('nomeComunidadeTradicional', $perfilIdentitario?->nomeComunidadeTradicional) }}">
                                        </div>
                                    </div>
                                </div>

                                {{-- Pessoa LGBTQIA+ --}}
                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label class="col-form-label">Você se identifica como Pessoa LGBTQIA+?</label>
                                        <div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="lgbtqia" id="lgbtqia_sim" value="true"
                                                       @if ((old('lgbtqia', $perfilIdentitario?->lgbtqia) === 'true') || ($perfilIdentitario?->lgbtqia === true)) checked @endif>
                                                <label class="form-check-label" for="lgbtqia_sim">Sim</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="lgbtqia" id="lgbtqia_nao" value="false"
                                                       @if ((old('lgbtqia', $perfilIdentitario?->lgbtqia) === 'false') || ($perfilIdentitario?->lgbtqia === false)) checked @endif>
                                                <label class="form-check-label" for="lgbtqia_nao">Não</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                {{-- Informações sobre necessidades especiais --}}
                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label class="col-form-label">Informações sobre necessidades especiais</label>
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
                                                    <input class="form-check-input"
                                                           type="checkbox"
                                                           name="necessidadesEspeciais[]"
                                                           id="necessidade_{{ $key }}"
                                                           value="{{ $key }}"
                                                           @if(in_array($key, old('necessidadesEspeciais', $perfilIdentitario?->necessidadesEspeciais ?? []))) checked @endif>
                                                    <label class="form-check-label" for="necessidade_{{ $key }}">{{ $label }}</label>
                                                </div>
                                            @endforeach

                                            <input type="text" name="outraNecessidadeEspecial" id="outraNecessidadeEspecial" class="form-control mt-2" placeholder="Se marcou 'Outra', especifique" style="max-width: 300px;"
                                                   value="{{ old('outraNecessidadeEspecial', $perfilIdentitario?->outraNecessidadeEspecial) }}"
                                                   @if(!in_array('outra_necessidade', old('necessidadesEspeciais', $perfilIdentitario?->necessidadesEspeciais ?? []))) style="display:none;" @endif>
                                        </div>
                                    </div>
                                </div>

                                {{-- Pessoa com deficiência ou idosos --}}
                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label class="col-form-label">Você é uma pessoa idosa ou com deficiência?</label>
                                        <div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="deficienciaIdoso" id="deficiencia_sim" value="true"
                                                       @if ((old('deficienciaIdoso', $perfilIdentitario?->deficienciaIdoso) === 'true') || ($perfilIdentitario?->deficienciaIdoso === true)) checked @endif>
                                                <label class="form-check-label" for="deficiencia_sim">Sim</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="deficienciaIdoso" id="deficiencia_nao" value="false"
                                                       @if ((old('deficienciaIdoso', $perfilIdentitario?->deficienciaIdoso) === 'false') || ($perfilIdentitario?->deficienciaIdoso === false)) checked @endif>
                                                <label class="form-check-label" for="deficiencia_nao">Não</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                {{-- Associado da ABA Agroecologia --}}
                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label class="col-form-label">Você é uma pessoa associada à Associação Brasileira de Agroecologia (ABA-Agroecologia)?</label>
                                        <div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="associadoAba" id="associado_sim" value="true"
                                                @if ((old('associadoAba', $perfilIdentitario?->associadoAba) === 'true') || ($perfilIdentitario?->associadoAba === true)) checked @endif>
                                                <label class="form-check-label" for="associado_sim">Sim</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="associadoAba" id="associado_nao" value="false"
                                                @if ((old('associadoAba', $perfilIdentitario?->associadoAba) === 'false') || ($perfilIdentitario?->associadoAba === false)) checked @endif>
                                                <label class="form-check-label" for="associado_nao">Não</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Gostaria de receber mais informações sobre ABA --}}
                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label class="col-form-label ">Se não, gostaria de receber mais informações sobre a ABA-Agroecologia?</label>
                                        <div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="receberInfoAba" id="receber_info_sim" value="true"
                                                @if ((old('receberInfoAba', $perfilIdentitario?->receberInfoAba) === 'true') || ($perfilIdentitario?->receberInfoAba === true)) checked @endif>
                                                <label class="form-check-label" for="receber_info_sim">Sim</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="receberInfoAba" id="receber_info_nao" value="false"
                                                @if ((old('receberInfoAba', $perfilIdentitario?->receberInfoAba) === 'false') || ($perfilIdentitario?->receberInfoAba === false)) checked @endif>
                                                <label class="form-check-label" for="receber_info_nao">Não</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                {{-- Participa de organização, rede ou movimento --}}
                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label class="col-form-label">Você participa de alguma organização, rede ou movimento?</label>
                                        <div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="participacaoOrganizacao" id="participa_sim" value="true"
                                                @if ((old('participacaoOrganizacao', $perfilIdentitario?->participacaoOrganizacao) === 'true') || ($perfilIdentitario?->participacaoOrganizacao === true)) checked @endif>
                                                <label class="form-check-label" for="participa_sim">Sim</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="participacaoOrganizacao" id="participa_nao" value="false"
                                                @if ((old('participacaoOrganizacao', $perfilIdentitario?->participacaoOrganizacao) === 'false') || ($perfilIdentitario?->participacaoOrganizacao === false)) checked @endif>
                                                <label class="form-check-label" for="participa_nao">Não</label>
                                            </div>
                                            <input type="text" name="nomeOrganizacao" id="nomeOrganizacao" class="form-control mt-2" placeholder="Se sim, qual?" style="max-width: 400px;">
                                        </div>
                                    </div>
                                </div>

                                {{-- Informações institucionais e de atuação --}}
                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <label for="vinculoInstitucional" class="col-form-label">Informações Institucionais e de Atuação (preenchimento opcional)</label>
                                        <input type="text"
                                               name="vinculoInstitucional"
                                               id="vinculoInstitucional"
                                               class="form-control"
                                               placeholder="Vínculo institucional ou coletivo (se houver)"
                                               value="{{ old('vinculoInstitucional', $perfilIdentitario?->vinculoInstitucional ?? '') }}">
                                    </div>
                                </div>
                            </div>



                            <div class="row form-group my-3">
                                <div class="col-md-10"></div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100" style="background-color: #034652; color: white; border-color: #034652;">
                                        {{ __('Atualizar cadastro') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
    </div>



@endsection

@section('javascript')
    <script type="text/javascript" >
        $(document).ready(function($){

            $('#cpf').mask('000.000.000-00');
            $('#cnpj').mask('00.000.000/0000-00');
            if($('html').attr('lang') == 'en') {
            } else if ($('html').attr('lang') == 'pt-BR') {
                $('#cep').blur(function () {
                    pesquisacep(this.value);
                });
                var SPMaskBehavior = function (val) {
                        return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
                    },
                    spOptions = {
                        onKeyPress: function(val, e, field, options) {
                            field.mask(SPMaskBehavior.apply({}, arguments), options);
                        }
                    };
                //$('#celular').mask(SPMaskBehavior, spOptions);
                $('#cep').mask('00000-000');
            }
            $(".apenasLetras").mask("#", {
                maxlength: false,
                translation: {
                    '#': {pattern: /[A-zÀ-ÿ0-9\s\-\.\(\)\[\]\{\}\/\\,;&@#$%*+=|<>!?~`'"]/, recursive: true}
                }
            });
            //$('#numero').mask('0000000000000');

        });
        function limpa_formulário_cep() {
            //Limpa valores do formulário de cep.
            document.getElementById('rua').value=("");
            document.getElementById('bairro').value=("");
            document.getElementById('cidade').value=("");
            document.getElementById('uf').value=("");
        }

        function meu_callback(conteudo) {
            if (!("erro" in conteudo)) {
                //Atualiza os campos com os valores.
                document.getElementById('rua').value=(conteudo.logradouro);
                document.getElementById('bairro').value=(conteudo.bairro);
                document.getElementById('cidade').value=(conteudo.localidade);
                document.getElementById('uf').value=(conteudo.uf);

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
                if(validacep.test(cep)) {

                    //Preenche os campos com "..." enquanto consulta webservice.
                    document.getElementById('rua').value="...";
                    document.getElementById('bairro').value="...";
                    document.getElementById('cidade').value="...";
                    document.getElementById('uf').value="...";


                    //Cria um elemento javascript.
                    var script = document.createElement('script');

                    //Sincroniza com o callback.
                    script.src = 'https://viacep.com.br/ws/'+ cep + '/json/?callback=meu_callback';

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"/>

    <script type="text/javascript">
        $(document).ready(function(){
            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);

            // $("#fieldPassaporte").hide();
            $("#customRadioInline1").click(function(){
                $("#fieldPassaporte").hide();
                $("#fieldCNPJ").hide();
                $("#fieldCPF").show();
            });

            $("#customRadioInline2").click(function(){
                $("#fieldPassaporte").hide();
                $("#fieldCNPJ").show();
                $("#fieldCPF").hide();
            });

            $("#customRadioInline3").click(function(){
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

        $(document).ready(function() {
            setTimeout(function() {
                $('.iti').css('width', '100%');
                $('.iti input').css('width', '100%');
            }, 100); // pequeno atraso para garantir que o plugin já aplicou os elementos
        });
    </script>

@endsection
