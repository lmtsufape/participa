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

        <div class="row titulo text-center mt-3" style="color: #034652;">
            <h2 style="font-weight: bold;">{{__('Meu Perfil')}}</h2>
        </div>

        <form method="POST" action="{{ route('perfil.update') }}">


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
                                <fieldset class="custom-control custom-radio custom-control-inline col-form-label">
                                    <input type="radio" id="documento_cpf" name="documentos" class="custom-control-input" value="cpf" @checked(old('documentos') === 'cpf' || old('cpf', $user->cpf))>
                                    <label class="custom-control-label me-2" for="documento_cpf"><strong>CPF</strong></label>

                                    <input type="radio" id="documento_cnpj" name="documentos" class="custom-control-input" value="cnpj" @checked(old('documentos') === 'cnpj' || old('cnpj', $user->cnpj))>
                                    <label class="custom-control-label me-2" for="documento_cnpj"><strong>{{__('CNPJ')}}</strong></label>

                                    <input type="radio" id="documento_passaporte" name="documentos" class="custom-control-input" value="passaporte" @checked(old('documentos') === 'passaporte' || old('passaporte', $user->passaporte))>
                                    <label class="custom-control-label " for="documento_passaporte"><strong>{{__('Passaporte')}}</strong></label>
                                </fieldset>

                                <div id="div_cpf" class="d-none">
                                    <input id="cpf" type="text" class="form-control @error('cpf') is-invalid @enderror" name="cpf" value="{{old('cpf', $user->cpf)}}" autocomplete="cpf" placeholder="CPF" autofocus >

                                    @error('cpf')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ __($message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div id="div_cnpj" class="d-none">
                                    <input id="cnpj" type="text" class="form-control @error('cnpj') is-invalid @enderror" name="cnpj" value="{{old('cnpj', $user->cnpj)}}" autocomplete="cnpj" placeholder="CNPJ" autofocus >
                                    @error('cnpj')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ __($message) }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div id="div_passaporte" class="d-none">
                                    <input id="passaporte" type="text" class="form-control @error('passaporte') is-invalid @enderror" name="passaporte" value="{{old('passaporte', $user->passaporte)}}" autocomplete="passaporte" placeholder="Passaporte" autofocus >
                                    @error('passporte')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ __($message) }}</strong>
                                        </span>
                                    @enderror
                                </div>

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
                    <div class="row pb-4">
                        <div class="col-md-4 form-group">
                            <label for="celular" class="col-form-label "><strong>{{ __('Celular') }}</strong></label><br>
                            <input id="celular" class="form-control celular @error('celular') is-invalid @enderror" type="text" name="celular" value="{{ old('full_number', $user->celular) }}"  autocomplete="celular" onkeyup="process(event)">
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
                        <div class="col-md-6" id="groupformuf">
                            <label for="uf" class="col-form-label"><strong>{{ __('Estado') }}</strong></label>
                            <select class="form-control @error('uf') is-invalid @enderror" id="uf" name="uf">
                                <option value="" disabled selected hidden>{{__('Selecione o estado')}}</option>
                                @foreach ($estados as $sigla => $nome)
                                    <option @selected(old('uf') == $sigla) value="{{ $sigla }}">{{ $nome }}</option>
                                @endforeach
                            </select>

                            @error('uf')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ __($message) }}</strong>
                                </span>
                            @enderror
                        </div>
                    @else
                        <div class="col-md-6" id="">
                            <label for="uf" class="col-form-label"><strong>{{ __('Estado/Província/Região') }}</strong></label>
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

            {{-- Perfil Social e Identitário --}}
            <div class="container card my-3" style="font-weight: 500;">
                <div class="row mt-3">
                    <div class="col-md-12">
                        <span class="h5" style="color: #034652; font-weight: bold;">Perfil social e identitário</span>
                    </div>
                </div>

                <hr style="border-top: 1px solid #034652">

                <div class="card-body">
                    <div class="row">
                        {{-- Gênero --}}
                        <div class="col-md-6 form-group">
                            <label class="col-form-label"><strong>Gênero</strong></label>
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
                                            @checked(old('genero', $user->perfilIdentitario?->genero) == $key)
                                            >
                                        <label class="form-check-label" for="genero_{{ $key }}">{{ $label }}</label>
                                    </div>
                                @endforeach
                                <input type="text" name="outroGenero" value="{{ old('outroGenero', $user->perfilIdentitario?->outroGenero) }}" id="outroGenero" class="form-control mt-2" placeholder="Se marcou 'Outro', especifique" style="max-width: 300px;" maxlength="200">
                            </div>
                        </div>
                        {{-- Raça (auto-declaração) --}}
                        <div class="col-md-6 form-group mt-3">
                            <label class="col-form-label"><strong>Raça (auto-declaração)</strong></label>
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
                                        <input class="form-check-input"
                                            type="radio"
                                            name="raca"
                                            id="raca_{{ $key }}"
                                            value="{{ $key }}"
                                            @checked(old('raca', $user->perfilIdentitario?->raca) == $key)>
                                        <label class="form-check-label" for="raca_{{ $key }}">{{ $label }}</label>
                                    </div>
                                @endforeach
                                <input type="text" name="outraRaca" value="{{ old('outraRaca', $user->perfilIdentitario?->outraRaca) }}" id="outraRaca" class="form-control mt-4" placeholder="Se marcou 'Outra', especifique" style="max-width: 300px;" maxlength="200">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        {{-- Pessoa LGBTQIA+ --}}
                        <div class="col-md-6 form-group mt-3">
                            <label class="col-form-label"><strong>Você se identifica como Pessoa LGBTQIA+?</strong></label>
                            <div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="lgbtqia" @checked(old('lgbtqia', $user->perfilIdentitario?->lgbtqia) == true) id="lgbtqia_sim" value="true">
                                    <label class="form-check-label" for="lgbtqia_sim">Sim</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="lgbtqia" @checked(old('lgbtqia', $user->perfilIdentitario?->lgbtqia) == false) id="lgbtqia_nao" value="false">
                                    <label class="form-check-label" for="lgbtqia_nao">Não</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        {{-- Informações sobre necessidades especiais --}}
                        <div class="col-md-6 form-group mt-3">
                            <label class="col-form-label"><strong>Informações sobre necessidades</strong></label>
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
                                            @checked(in_array($key, old('necessidadesEspeciais', $user->perfilIdentitario?->necessidadesEspeciais ?? [])))
                                            id="necessidade_{{ $key }}"
                                            value="{{ $key }}">
                                        <label class="form-check-label" for="necessidade_{{ $key }}">{{ $label }}</label>
                                    </div>
                                @endforeach
                                <input type="text" name="outraNecessidadeEspecial" value="{{ old('outraNecessidadeEspecial', $user->perfilIdentitario?->outraNecessidadeEspecial) }}" id="outraNecessidadeEspecial" class="form-control mt-2" placeholder="Se marcou 'Outra', especifique" style="max-width: 300px;" maxlength="200">
                            </div>
                        </div>
                        {{-- Pessoa com deficiência ou idosos --}}
                        <div class="col-md-6 form-group mt-3">
                            <label class="col-form-label"><strong>Você é uma pessoa idosa ou com deficiência?</strong></label>
                            <div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" @checked(old('deficienciaIdoso', $user->perfilIdentitario?->deficienciaIdoso) == true) name="deficienciaIdoso" id="deficiencia_sim" value="true">
                                    <label class="form-check-label" for="deficiencia_sim">Sim</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" @checked(old('deficienciaIdoso', $user->perfilIdentitario?->deficienciaIdoso) == false) name="deficienciaIdoso" id="deficiencia_nao" value="false">
                                    <label class="form-check-label" for="deficiencia_nao">Não</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        {{-- Comunidade ou povo tradicional --}}
                        <div class="col-md-6 form-group mt-3">
                            <label class="col-form-label"><strong>Você pertence ou atua em alguma comunidade ou povo tradicional?</strong></label>
                            <div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" @checked(old('comunidadeTradicional', $user->perfilIdentitario?->comunidadeTradicional) == true) name="comunidadeTradicional" id="comunidade_sim" value="true">
                                    <label class="form-check-label" for="comunidade_sim">Sim</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" @checked(old('comunidadeTradicional', $user->perfilIdentitario?->deficiencomunidadeTradicionalciaIdoso) == false) name="comunidadeTradicional" id="comunidade_nao" value="false">
                                    <label class="form-check-label" for="comunidade_nao">Não</label>
                                </div>
                                <input type="text" name="nomeComunidadeTradicional" value="{{ old('nomeComunidadeTradicional', $user->perfilIdentitario?->nomeComunidadeTradicional) }}" id="nomeComunidadeTradicional" class="form-control mt-2" placeholder="Se sim, qual?" style="max-width: 400px;" maxlength="200">
                            </div>
                        </div>
                        {{-- Participa de organização, rede ou movimento --}}
                        <div class="col-md-6 form-group mt-3">
                            <label class="col-form-label"><strong>Você participa de alguma organização, rede ou movimento?</strong></label>
                            <div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="participacaoOrganizacao" @checked(old('participacaoOrganizacao', $user->perfilIdentitario?->participacaoOrganizacao) == true) id="participa_sim" value="true">
                                    <label class="form-check-label" for="participa_sim">Sim</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="participacaoOrganizacao" @checked(old('participacaoOrganizacao', $user->perfilIdentitario?->participacaoOrganizacao) == false) id="participa_nao" value="false">
                                    <label class="form-check-label" for="participa_nao">Não</label>
                                </div>
                                <input type="text" name="nomeOrganizacao" value="{{ old('nomeOrganizacao', $user->perfilIdentitario?->nomeOrganizacao) }}" id="nomeOrganizacao" class="form-control mt-2" placeholder="Se sim, qual?" style="max-width: 400px;" maxlength="200">
                            </div>
                        </div>
                        {{-- Informações institucionais e de atuação --}}
                        <div>
                            <div class="form-group mt-3">
                                <label for="vinculoInstitucional" class="col-form-label"><strong>Informações Institucionais e de Atuação (preenchimento opcional)</strong></label>
                                <textarea name="vinculoInstitucional" id="vinculoInstitucional" class="form-control" placeholder="Vínculo institucional ou coletivo (se houver)" maxlength="1000" rows="5" style="height: 120px; resize: none; overflow: hidden;">{{ old('vinculoInstitucional', $user->perfilIdentitario?->vinculoInstitucional) }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @if ($user->membroComissaoEvento != null && count($user->membroComissaoEvento) > 0)
                            <div class="col-md-4">
                                <label for="especialidade" class="col-form-label">{{ __('Especialidade profissional') }}</label>
                                <input id="especialidade" type="text" class="form-control apenasLetras @error('especialidade') is-invalid @enderror" name="especialidade" autocomplete="new-password">
                                @error('especialidade')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        @endif
                    </div>
                    <div class="row justify-content-center" style="margin: 20px 0 20px 0">
                        <div class="col-md-6" style="padding-left:0">
                            {{-- <a class="btn btn-secondary botao-form" href="{{route('home')}}" style="width:100%">Voltar</a> --}}
                        </div>
                        <div class="col-md-6" style="padding-right:0">
                            <button type="submit" class="btn btn-success btn-lg botao-form" style="width:100%; font-weight: bold; font-size: 16px; padding: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                                </i> {{ __('Concluir') }}
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </form>

        <form action="{{ route('perfil.password.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom py-4 px-4 rounded-top-4">
                    <div class="d-flex align-items-center gap-3">
                        <div>
                            <h4 class="fw-bold mb-1">Segurança</h4>
                            <p class="text-muted mb-0 small">Atualize sua senha e mantenha sua conta segura.</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4 align-items-end">

                        <div class="col-md-4">
                            <label for="current_password" class="form-label fw-semibold">Senha atual</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white rounded-start-3">
                                    <i class="bi bi-lock text-muted"></i>
                                </span>
                                <input
                                    type="password"
                                    name="current_password"
                                    id="current_password"
                                    class="form-control @error('current_password') is-invalid @enderror"
                                    placeholder="Digite sua senha atual"
                                    autocomplete="current-password"
                                >
                                <button class="btn btn-outline-secondary rounded-end-3" type="button">
                                    <i class="bi bi-eye"></i>
                                </button>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="password" class="form-label fw-semibold">Nova senha</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white rounded-start-3">
                                    <i class="bi bi-lock text-muted"></i>
                                </span>
                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Digite sua nova senha"
                                    autocomplete="new-password"
                                >
                                <button class="btn btn-outline-secondary rounded-end-3" type="button">
                                    <i class="bi bi-eye"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="password_confirmation" class="form-label fw-semibold">Confirmar nova senha</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white rounded-start-3">
                                    <i class="bi bi-lock text-muted"></i>
                                </span>
                                <input
                                    type="password"
                                    name="password_confirmation"
                                    id="password_confirmation"
                                    class="form-control"
                                    placeholder="Confirme sua nova senha"
                                    autocomplete="new-password"
                                >
                                <button class="btn btn-outline-secondary rounded-end-3" type="button">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-lg-9">
                            <div class="alert alert-primary bg-primary-subtle border-primary-subtle text-primary-emphasis rounded-3 py-2 mb-0 small d-flex align-items-center gap-2">
                                <i class="bi bi-shield-check"></i>
                                <span>A senha deve ter no mínimo 8 caracteres, podendo conter letras e números.</span>
                            </div>
                        </div>

                        <div class="col-lg-3 d-flex justify-content-lg-end">
                            <button type="submit" class="btn btn-primary rounded-3 px-4 w-100 w-lg-auto">
                                Alterar senha
                            </button>
                        </div>

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



            $("#documento_cpf").change(function(){
                $("#div_passaporte, #div_cnpj").addClass('d-none').find("input").val('');
                $("#div_cpf").removeClass('d-none');
            });

            $("#documento_cnpj").change(function(){
                $("#div_passaporte, #div_cpf").addClass('d-none').find("input").val('');
                $("#div_cnpj").removeClass('d-none');
            });

            $("#documento_passaporte").change(function(){
                $("#div_passaporte").removeClass('d-none');
                $("#div_cnpj, #div_cpf").addClass('d-none').find("input").val('');
            });

            $("input[name='documentos']:checked").trigger('change')
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
