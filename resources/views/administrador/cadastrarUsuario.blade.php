@extends('layouts.app')

@section('content')
<div class="container content mb-5 position-relative">
    <style>
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
        <h2 style="font-weight: bold;">{{__('Cadastrar Usuário')}}</h2>
    </div>

    <form method="POST" action="{{ route('administrador.criarUsuario', app()->getLocale()) }}">
        @csrf
        
        {{-- Dados Pessoais --}}
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
                    <label for="name" class="col-form-label required-field"><strong>{{ __('Nome completo') }}</strong></label>
                    <input id="name" type="text" class="form-control apenasLetras @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" autocomplete="name" autofocus required>

                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ __($message) }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="nomeSocial" class="col-form-label"><strong>{{ __('Nome social') }}</strong></label>
                    <input id="nomeSocial" type="text" class="form-control apenasLetras @error('nomeSocial') is-invalid @enderror" name="nomeSocial" value="{{ old('nomeSocial') }}" autocomplete="nomeSocial">

                    @error('nomeSocial')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ __($message) }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-6">
                    <div class="custom-control custom-radio custom-control-inline col-form-label">
                        <input type="radio" id="customRadioInline1" name="customRadioInline" class="custom-control-input" checked>
                        <label class="custom-control-label me-2" for="customRadioInline1"><strong>CPF</strong></label>

                        <input type="radio" id="customRadioInline2" name="customRadioInline" class="custom-control-input">
                        <label class="custom-control-label me-2" for="customRadioInline2"><strong>{{__('CNPJ')}}</strong></label>

                        <input type="radio" id="customRadioInline3" name="customRadioInline" class="custom-control-input">
                        <label class="custom-control-label" for="customRadioInline3"><strong>{{__('Passaporte')}}</strong></label>
                    </div>

                    <div id="fieldCPF">
                        <input id="cpf" type="text" class="form-control @error('cpf') is-invalid @enderror" name="cpf" value="{{ old('cpf') }}" autocomplete="cpf" placeholder="CPF" autofocus>

                        @error('cpf')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ __($message) }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div id="fieldCNPJ" style="display: none">
                        <input id="cnpj" type="text" class="form-control @error('cnpj') is-invalid @enderror" name="cnpj" placeholder="{{__('CNPJ')}}" value="{{ old('cnpj') }}" autocomplete="cnpj">

                        @error('cnpj')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ __($message) }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div id="fieldPassaporte" style="display: none">
                        <input id="passaporte" type="text" class="form-control @error('passaporte') is-invalid @enderror" name="passaporte" placeholder="{{__('Passaporte')}}" value="{{ old('passaporte') }}" autocomplete="passaporte">

                        @error('passaporte')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ __($message) }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="instituicao" class="col-form-label required-field"><strong>{{ __('Instituição') }}</strong></label>
                    <input id="instituicao" type="text" class="form-control apenasLetras @error('instituicao') is-invalid @enderror" name="instituicao" value="{{ old('instituicao') }}" autocomplete="instituicao" required>

                    @error('instituicao')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ __($message) }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-4">
                    <label for="celular" class="col-form-label required-field"><strong>{{ __('Celular') }}</strong></label><br>
                    <input id="phone" class="form-control celular @error('celular') is-invalid @enderror" type="tel" name="celular" value="{{old('celular')}}" style="width: 100% !important;" required autocomplete="celular" onkeyup="process(event)">
                    <div class="alert alert-info mt-1" style="display: none"></div>
                    <div id="celular-invalido" class="alert alert-danger mt-1" role="alert" style="display: none"></div>

                    @error('celular')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ __($message) }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="dataNascimento" class="col-form-label required-field"><strong>{{ __('Data de nascimento') }}</strong></label>
                    <input id="dataNascimento" type="date" class="form-control @error('dataNascimento') is-invalid @enderror" name="dataNascimento" value="{{ old('dataNascimento')}}" autocomplete="dataNascimento" required>

                    @error('dataNascimento')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ __($message) }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="email" class="col-form-label required-field"><strong>{{ __('E-mail') }}</strong></label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email')}}" autocomplete="email" required>

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ __($message) }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row mb-3">
                <div class="col-md-6">
                    <label for="password" class="col-form-label required-field"><strong>{{ __('Senha') }}</strong></label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password" required>
                    <small>{{__('OBS: A senha deve ter no mínimo 8 caracteres (letras ou números)')}}.</small>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ __($message) }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="password-confirm" class="col-form-label required-field"><strong>{{ __('Confirmar senha') }}</strong></label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password" required>
                </div>
            </div>
        </div>

        {{-- Endereço --}}
        <div class="container card my-3" style="font-weight: 500;">
            <div class="row mt-3">
                <div class="col-md-8">
                    <div>
                        <span class="h5" style="color: #034652; font-weight: bold;">Endereço</span>
                    </div>
                </div>
            </div>

            <hr style="border-top: 1px solid#034652">

            <div class="form-group row mt-3">
                <div class="col-md-12">
                    <label for="cep" class="col-form-label required-field"><strong>{{ __('CEP') }}</strong></label>
                    <input value="{{old('cep')}}" id="cep" type="text" autocomplete="cep" name="cep" autofocus class="form-control field__input a-field__input" placeholder="{{__('CEP')}}" size="10" maxlength="9" required>
                    @error('cep')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ __($message) }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-6">
                    <label for="rua" class="col-form-label required-field"><strong>{{ __('Rua') }}</strong></label>
                    <input value="{{old('rua')}}" id="rua" type="text" class="form-control @error('rua') is-invalid @enderror" name="rua" autocomplete="new-password" required>

                    @error('rua')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ __($message) }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="numero" class="col-form-label required-field"><strong>{{ __('Número') }}</strong></label>
                    <input value="{{old('numero')}}" id="numero" type="text" class="form-control @error('numero') is-invalid @enderror" name="numero" autocomplete="numero" maxlength="10" required>

                    @error('numero')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ __($message) }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-6">
                    <label for="bairro" class="col-form-label required-field"><strong>{{ __('Bairro') }}</strong></label>
                    <input value="{{old('bairro')}}" id="bairro" type="text" class="form-control @error('bairro') is-invalid @enderror" name="bairro" autocomplete="bairro" required>

                    @error('bairro')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ __($message) }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="complemento" class="col-form-label"><strong>{{ __('Complemento') }}</strong></label>
                    <input type="text" value="{{old('complemento')}}" id="complemento" class="form-control @error('complemento') is-invalid @enderror" name="complemento">

                    @error('complemento')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ __($message) }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row mb-3">
                <div class="col-md-6">
                    <label for="cidade" class="col-form-label required-field"><strong>{{ __('Cidade') }}</strong></label>
                    <input value="{{old('cidade')}}" id="cidade" type="text" class="form-control apenasLetras @error('cidade') is-invalid @enderror" name="cidade" autocomplete="cidade" required>

                    @error('cidade')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ __($message) }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-6" id="groupformuf">
                    <label for="uf" class="col-form-label required-field"><strong>{{ __('Estado') }}</strong></label>
                    <select class="form-control @error('uf') is-invalid @enderror required-field" id="uf" name="uf" required>
                        <option value="" disabled selected hidden>{{__('Selecione o estado')}}</option>
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
                                    <input class="form-check-input"
                                        type="radio"
                                        name="genero"
                                        id="genero_{{ $key }}"
                                        value="{{ $key }}"
                                        @if ($loop->first) required @endif>
                                    <label class="form-check-label" for="genero_{{ $key }}">{{ $label }}</label>
                                </div>
                            @endforeach
                            <input type="text" name="outroGenero" id="outroGenero" class="form-control mt-2" placeholder="Se marcou 'Outro', especifique" style="max-width: 300px;" maxlength="200">
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
                                    <input class="form-check-input"
                                        type="checkbox" 
                                        name="raca[]" 
                                        id="raca_{{ $key }}"
                                        value="{{ $key }}">
                                    <label class="form-check-label" for="raca_{{ $key }}">{{ $label }}</label>
                                </div>
                            @endforeach
                            <input type="text" name="outraRaca" id="outraRaca" class="form-control mt-4" placeholder="Se marcou 'Outra', especifique" style="max-width: 300px;" maxlength="200">
                        </div>
                    </div>
                </div>
            </div>

            <div>
                {{-- Comunidade ou povo tradicional --}}
                <div>
                    <div class="form-group mt-3">
                        <label class="col-form-label required-field"><strong>Você pertence ou atua em alguma comunidade ou povo tradicional?</strong></label>
                        <div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="comunidadeTradicional" id="comunidade_sim" value="true" required>
                                <label class="form-check-label" for="comunidade_sim">Sim</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="comunidadeTradicional" id="comunidade_nao" value="false">
                                <label class="form-check-label" for="comunidade_nao">Não</label>
                            </div>
                            <input type="text" name="nomeComunidadeTradicional" id="nomeComunidadeTradicional" class="form-control mt-2" placeholder="Se sim, qual?" style="max-width: 400px;" maxlength="200">
                        </div>
                    </div>
                </div>

                {{-- Pessoa LGBTQIA+ --}}
                <div>
                    <div class="form-group mt-3">
                        <label class="col-form-label required-field"><strong>Você se identifica como Pessoa LGBTQIA+?</strong></label>
                        <div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="lgbtqia" id="lgbtqia_sim" value="true" required>
                                <label class="form-check-label" for="lgbtqia_sim">Sim</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="lgbtqia" id="lgbtqia_nao" value="false">
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
                        <label class="col-form-label required-field"><strong>Informações sobre necessidades</strong></label>
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
                                        value="{{ $key }}">
                                    <label class="form-check-label" for="necessidade_{{ $key }}">{{ $label }}</label>
                                </div>
                            @endforeach
                            <input type="text" name="outraNecessidadeEspecial" id="outraNecessidadeEspecial" class="form-control mt-2" placeholder="Se marcou 'Outra', especifique" style="max-width: 300px;" maxlength="200">
                        </div>
                    </div>
                </div>

                {{-- Pessoa com deficiência ou idosos --}}
                <div>
                    <div class="form-group mt-3">
                        <label class="col-form-label required-field"><strong>Você é uma pessoa idosa ou com deficiência?</strong></label>
                        <div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="deficienciaIdoso" id="deficiencia_sim" value="true" required>
                                <label class="form-check-label" for="deficiencia_sim">Sim</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="deficienciaIdoso" id="deficiencia_nao" value="false">
                                <label class="form-check-label" for="deficiencia_nao">Não</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                {{-- Associado da ABA Agroecologia --}}
                <div>
                    <div class="form-group mt-3">
                        <label class="col-form-label required-field"><strong>Você é uma pessoa associada à Associação Brasileira de Agroecologia (ABA-Agroecologia)?</strong></label>
                        <div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="associadoAba" id="associado_sim" value="true" required>
                                <label class="form-check-label" for="associado_sim">Sim</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="associadoAba" id="associado_nao" value="false">
                                <label class="form-check-label" for="associado_nao">Não</label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Gostaria de receber mais informações sobre ABA --}}
                <div>
                    <div class="form-group mt-3">
                        <label class="col-form-label required-field"><strong>Se não, gostaria de receber mais informações sobre a ABA-Agroecologia?</strong></label>
                        <div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="receberInfoAba" id="receber_info_sim" value="true" required>
                                <label class="form-check-label" for="receber_info_sim">Sim</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="receberInfoAba" id="receber_info_nao" value="false">
                                <label class="form-check-label" for="receber_info_nao">Não</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                {{-- Participa de organização, rede ou movimento --}}
                <div>
                    <div class="form-group mt-3">
                        <label class="col-form-label required-field"><strong>Você participa de alguma organização, rede ou movimento?</strong></label>
                        <div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="participacaoOrganizacao" id="participa_sim" value="true" required>
                                <label class="form-check-label" for="participa_sim">Sim</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="participacaoOrganizacao" id="participa_nao" value="false">
                                <label class="form-check-label" for="participa_nao">Não</label>
                            </div>
                            <input type="text" name="nomeOrganizacao" id="nomeOrganizacao" class="form-control mt-2" placeholder="Se sim, qual?" style="max-width: 400px;" maxlength="200">
                        </div>
                    </div>
                </div>

                {{-- Informações institucionais e de atuação --}}
                <div>
                    <div class="form-group mt-3">
                        <label for="vinculoInstitucional" class="col-form-label"><strong>Informações Institucionais e de Atuação (preenchimento opcional)</strong></label>
                        <textarea name="vinculoInstitucional" id="vinculoInstitucional" class="form-control" placeholder="Vínculo institucional ou coletivo (se houver)" maxlength="1000" rows="5" style="height: 120px; resize: none; overflow: hidden;">{{ old('vinculoInstitucional') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="row form-group my-3">
                <div class="col-md-10"></div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100" style="background-color: #034652; color: white; border-color: #034652;">
                        {{ __('Cadastrar Usuário') }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection

@section('javascript')
<script type="text/javascript">
    $(document).ready(function($){
        $('#cpf').mask('000.000.000-00');
        $('#cnpj').mask('00.000.000/0000-00');
        
        if($('html').attr('lang') == 'pt-BR') {
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
            $('#cep').mask('00000-000');
        }
        
        $(".apenasLetras").mask("#", {
            maxlength: false,
            translation: {
                '#': {pattern: /[A-zÀ-ÿ0-9\s\-\.\(\)\[\]\{\}\/\\,;&@#$%*+=|<>!?~`'"]/, recursive: true}
            }
        });
    });

    function limpa_formulário_cep() {
        document.getElementById('rua').value=("");
        document.getElementById('bairro').value=("");
        document.getElementById('cidade').value=("");
        document.getElementById('uf').value=("");
    }

    function meu_callback(conteudo) {
        if (!("erro" in conteudo)) {
            document.getElementById('rua').value=(conteudo.logradouro);
            document.getElementById('bairro').value=(conteudo.bairro);
            document.getElementById('cidade').value=(conteudo.localidade);
            document.getElementById('uf').value=(conteudo.uf);
        } else {
            limpa_formulário_cep();
            alert("CEP não encontrado.");
        }
    }

    function pesquisacep(valor) {
        var cep = valor.replace(/\D/g, '');
        if (cep != "") {
            var validacep = /^[0-9]{8}$/;
            if(validacep.test(cep)) {
                document.getElementById('rua').value="...";
                document.getElementById('bairro').value="...";
                document.getElementById('cidade').value="...";
                document.getElementById('uf').value="...";

                var script = document.createElement('script');
                script.src = 'https://viacep.com.br/ws/'+ cep + '/json/?callback=meu_callback';
                document.body.appendChild(script);
            } else {
                limpa_formulário_cep();
                alert("Formato de CEP inválido.");
            }
        } else {
            limpa_formulário_cep();
        }
    }

    $(document).ready(function(){
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

    $(document).ready(function() {
        setTimeout(function() {
            $('.iti').css('width', '100%');
            $('.iti input').css('width', '100%');
        }, 100);
    });
</script>
<script src="{{ asset('js/celular.js') }}" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"/>
@endsection