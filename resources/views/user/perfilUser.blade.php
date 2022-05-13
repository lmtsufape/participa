@extends('layouts.app')

@section('content')

<div class="banner-perfil"  style="position: relative; top: 65px;">
    <div class="row justify-content-center curved" style="margin-bottom:-5px">

    </div>

    <div class="row justify-content-center">
        <div class="col-sm-12">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#114048ff"
            fill-opacity="1" d="M0,288L80,261.3C160,235,320,181,480,176C640,171,800,213,960,
            218.7C1120,224,1280,192,1360,176L1440,160L1440,0L1360,0C1280,0,1120,0,960,0C800,
            0,640,0,480,0C320,0,160,0,80,0L0,0Z"></path>
            </svg>
        </div>
    </div>
</div>

<div class="card-perfil justify-content-center">
    <div class="card card-change-mode" style="width: 80%;">
        <div class="card-body">

            @if(Auth()->user()->usuarioTemp == null)
                <div class="container">
                    @if(session('message') && Auth()->user()->usuarioTemp == null)
                        <div class="row">
                            <div class="col-md-12" style="margin-top: 5px;">
                                <div class="alert alert-success">
                                    <p>{{session('message')}}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="row justify-content-center titulo">
                        <div class="col-sm-12">
                            {{__('Perfil')}}
                        </div>
                    </div>

                    <div class="row subtitulo">
                        <div class="col-sm-12">
                            <p>{{__('Informações Pessoais')}}</p>
                        </div>
                    </div>


                    <form method="POST" action="{{ route('perfil.update') }}">
                        @csrf
                        <div class="row justify-content-center">
                            <input hidden name="id" value="{{$user->id}}">
                            <div class="col-md-6">
                                <label for="name" class="col-form-label">{{ __('Nome Completo') }}</label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" @if(old('name') != null) value="{{ old('name') }}" @else value="{{$user->name}}" @endif required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <div class="custom-control custom-radio custom-control-inline col-form-label">
                                  <input type="radio" id="customRadioInline1" name="check_cpf" class="custom-control-input" @if($user->cpf == null) disabled @else  @endif @error('cpf') checked @enderror >
                                  <label class="custom-control-label" for="customRadioInline1">CPF</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                  <input type="radio" id="customRadioInline2" name="check_passaporte" class="custom-control-input" @if($user->passaporte == null) disabled @else  @endif  >
                                  <label class="custom-control-label " for="customRadioInline2">Passaporte</label>
                                </div>

                                <div id="fieldCPF" @error('passaporte') style="display: none" @enderror>
                                  <input id="cpf" type="text" class="form-control @error('cpf') is-invalid @enderror" name="cpf" @if(old('cpf') != null) value="{{ old('cpf') }}" @else value="{{$user->cpf}}" @endif autocomplete="cpf" placeholder="CPF" autofocus>

                                  @error('cpf')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                  @enderror
                                </div>
                                <div id="fieldPassaporte" @error('passaporte') style="display: block" @enderror style="display: none" >
                                  <input id="passaporte" type="text" class="form-control @error('passaporte') is-invalid @enderror" name="passaporte" placeholder="{{__('Passaporte')}}" @if(old('passaporte') != null) value="{{ old('passaporte') }}" @else value="{{$user->passaporte}}" @endif autocomplete="passaporte" autofocus>

                                  @error('passaporte')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                  @enderror
                                </div>

                            </div>
                        </div>

                        <div class="row justify-content-center">

                            <div class="col-md-7">
                                <label for="instituicao" class="col-form-label">{{ __('Instituição de Ensino') }}</label>
                                <input id="instituicao" type="text" class="form-control @error('instituicao') is-invalid @enderror" name="instituicao" @if(old('instituicao') != null) value="{{ old('instituicao') }}" @else value="{{$user->instituicao}}" @endif required autocomplete="instituicao" autofocus>

                                @error('instituicao')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-5">
                                <label for="celular" class="col-form-label">{{ __('Celular') }}</label>
                                <input id="celular" type="text" class="form-control @error('celular') is-invalid @enderror" name="celular" @if(old('celular') != null) value="{{ old('celular') }}" @else value="{{$user->celular}}" @endif required autocomplete="celular" autofocus>

                                @error('celular')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <label for="email" class="col-form-label">{{ __('E-Mail') }}</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{old('email',$user->email)}}"  autocomplete="email" required>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <label for="senha_atual" class="col-form-label">{{__('Senha atual')}}</label>
                                <input type="password" class="form-control @error('senha_atual') is-invalid @enderror" name="senha_atual" id="senha_atual">

                                @error('senha_atual')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <small>Para alterar a senha digite a atual e a nova</small>
                            </div>
                            <div class="col-sm-4">
                                <label for="password" class="col-form-label">{{__('Nova senha')}}</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-sm-4">
                                <label for="password-confirm" class="col-form-label">{{__('Confirme a nova senha')}}</label>
                                <input type="password" class="form-control @error('password-confirm') is-invalid @enderror" name="password-confirm" id="password-confirm">

                                @error('password-confirm')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        @if($end != null)
                            <div class="row subtitulo" style="margin-top:20px">
                                <div class="col-sm-12">
                                    <p>{{__('Endereço')}}</p>
                                </div>
                            </div>

                            {{-- Endereço --}}

                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="pais" class="col-form-label">{{ __('País') }}*</label>
                                    <select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" class="form-control @error('pais') is-invalid @enderror" id="pais">
                                        <option value="" disabled selected hidden>-- {{__('País')}} --</option>
                                        @if ($pais)
                                            <option @if($pais == 'brasil') selected @endif value="/perfil/brasil">{{__('Brasil')}}</option>
                                            <option @if($pais == 'usa') selected @endif value="/perfil/usa">{{__('Estados Unidos da América')}}</option>
                                            <option @if($pais == 'outro') selected @endif value="/perfil/outro">{{__('Outro')}}</option>
                                        @else
                                            <option @if($end->pais == 'brasil') selected @endif value="/perfil/brasil">{{__('Brasil')}}</option>
                                            <option @if($end->pais == 'usa') selected @endif value="/perfil/usa">{{__('Estados Unidos da América')}}</option>
                                            <option @if($end->pais == 'outro') selected @endif value="/perfil/outro">{{__('Outro')}}</option>
                                        @endif
                                    </select>
                                    <input type="hidden" name="pais" value="{{$end->pais}}">
                                    <small>{{__('O formulário seguirá os padrões desse país')}}.</small>

                                    @error('pais')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ __($message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row justify-content-center">
                                <div class="col-md-12">
                                    <label for="cep" class="col-form-label">{{ __('CEP') }}</label>
                                    <input id="cep" type="text" class="form-control @error('cep') is-invalid @enderror" name="cep" @if(old('cep') != null ) value="{{ old('cep') }}" @else value="{{$end->cep}}" @endif required autocomplete="cep">

                                    @error('cep')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row justify-content-center">
                                <div class="col-md-6">
                                    <label for="rua" class="col-form-label">{{ __('Rua') }}</label>
                                    <input id="rua" type="text" class="form-control @error('rua') is-invalid @enderror" name="rua" @if(old('rua') != null) value="{{ old('rua') }}" @else value="{{$end->rua}}" @endif required autocomplete="new-password">

                                    @error('rua')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-2">
                                    <label for="numero" class="col-form-label">{{ __('Número') }}</label>
                                    <input id="numero" type="number" class="form-control @error('numero') is-invalid @enderror" name="numero" @if(old('numero') != null) value="{{ old('numero') }}" @else value="{{$end->numero}}" @endif required autocomplete="numero">

                                    @error('numero')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="bairro" class="col-form-label">{{ __('Bairro') }}</label>
                                    <input id="bairro" type="text" class="form-control @error('bairro') is-invalid @enderror" name="bairro" @if(old('bairro') != null) value="{{ old('bairro') }}" @else value="{{$end->bairro}}" @endif required autocomplete="bairro">

                                    @error('bairro')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="form-group row justify-content-center">
                                <div class="col-md-4">
                                    <label for="cidade" class="col-form-label">{{ __('Cidade') }}</label>
                                    <input id="cidade" type="text" class="form-control @error('cidade') is-invalid @enderror" name="cidade" @if(old('cidade') != null) value="{{ old('cidade') }}" @else value="{{$end->cidade}}" @endif required autocomplete="cidade">

                                    @error('cidade')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-sm-4">
                                    <label for="complemento" class="col-form-label">{{ __('Complemento') }}</label>
                                    <input id="complemento" type="text" class="form-control apenasLetras @error('complemento') is-invalid @enderror" name="complemento" @if(old('complemento') != null) value="{{ old('complemento') }}"@else value="{{$end->complemento}} "@endif required autocomplete="complemento">

                                    @error('complemento')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="col-sm-4" id="groupformufinput">
                                    <label for="ufInput" class="col-form-label">{{ __('UF') }}</label>
                                    <input type="text" value="{{old('uf', $end->uf)}}" id="ufInput" class="form-control  @error('uf') is-invalid @enderror" name="uf" >

                                    @error('uf')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ __($message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-sm-4" id="groupformuf">
                                    <label for="uf" class="col-form-label">{{ __('UF') }}</label>
                                    {{-- <input id="uf" type="text" class="form-control @error('uf') is-invalid @enderror" name="uf" value="{{ old('uf') }}" required autocomplete="uf" autofocus> --}}
                                    <select class="form-control @error('uf') is-invalid @enderror" id="uf" name="uf">
                                        <option value="" disabled selected hidden>-- UF --</option>
                                        <option @if(old('uf', $end->uf) == 'AC') selected @endif value="AC">Acre</option>
                                        <option @if(old('uf', $end->uf) == 'AL') selected @endif value="AL">Alagoas</option>
                                        <option @if(old('uf', $end->uf) == 'AP') selected @endif value="AP">Amapá</option>
                                        <option @if(old('uf', $end->uf) == 'AM') selected @endif value="AM">Amazonas</option>
                                        <option @if(old('uf', $end->uf) == 'BA') selected @endif value="BA">Bahia</option>
                                        <option @if(old('uf', $end->uf) == 'CE') selected @endif value="CE">Ceará</option>
                                        <option @if(old('uf', $end->uf) == 'DF') selected @endif value="DF">Distrito Federal</option>
                                        <option @if(old('uf', $end->uf) == 'ES') selected @endif value="ES">Espírito Santo</option>
                                        <option @if(old('uf', $end->uf) == 'GO') selected @endif value="GO">Goiás</option>
                                        <option @if(old('uf', $end->uf) == 'MA') selected @endif value="MA">Maranhão</option>
                                        <option @if(old('uf', $end->uf) == 'MT') selected @endif value="MT">Mato Grosso</option>
                                        <option @if(old('uf', $end->uf) == 'MS') selected @endif value="MS">Mato Grosso do Sul</option>
                                        <option @if(old('uf', $end->uf) == 'MG') selected @endif value="MG">Minas Gerais</option>
                                        <option @if(old('uf', $end->uf) == 'PA') selected @endif value="PA">Pará</option>
                                        <option @if(old('uf', $end->uf) == 'PB') selected @endif value="PB">Paraíba</option>
                                        <option @if(old('uf', $end->uf) == 'PR') selected @endif value="PR">Paraná</option>
                                        <option @if(old('uf', $end->uf) == 'PE') selected @endif value="PE">Pernambuco</option>
                                        <option @if(old('uf', $end->uf) == 'PI') selected @endif value="PI">Piauí</option>
                                        <option @if(old('uf', $end->uf) == 'RJ') selected @endif value="RJ">Rio de Janeiro</option>
                                        <option @if(old('uf', $end->uf) == 'RN') selected @endif value="RN">Rio Grande do Norte</option>
                                        <option @if(old('uf', $end->uf) == 'RS') selected @endif value="RS">Rio Grande do Sul</option>
                                        <option @if(old('uf', $end->uf) == 'RO') selected @endif value="RO">Rondônia</option>
                                        <option @if(old('uf', $end->uf) == 'RR') selected @endif value="RR">Roraima</option>
                                        <option @if(old('uf', $end->uf) == 'SC') selected @endif value="SC">Santa Catarina</option>
                                        <option @if(old('uf', $end->uf) == 'SP') selected @endif value="SP">São Paulo</option>
                                        <option @if(old('uf', $end->uf) == 'SE') selected @endif value="SE">Sergipe</option>
                                        <option @if(old('uf', $end->uf) == 'TO') selected @endif value="TO">Tocantins</option>
                                    </select>

                                    @error('uf')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                            </div>
                        @endif
                        <div class="row justify-content-center" style="margin: 20px 0 20px 0">

                            <div class="col-md-7" style="padding-left:0">
                                {{-- <a class="btn btn-secondary botao-form" href="{{route('home')}}" style="width:100%">Voltar</a> --}}
                            </div>
                            <div class="col-md-5" style="padding-right:0">
                                <button type="submit" class="btn btn-atualizar-perfil botao-form" style="width:100%">
                                    {{ __('Atualizar') }}
                                </button>
                            </div>
                        </div>

                        </form>
                    </div>
                </div>
            @else
                <div class="container">
                    @if(session('message') )
                    <div class="row">
                            <div class="col-md-12" style="margin-top: 5px;">
                                <div class="alert alert-success">
                                    <p>{{session('message')}}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="row" style="margin-top: 20px; margin-bottom: 20px; font-weight: 2000;">
                        <div class="col-sm-12">
                            <h1>Complete seu cadastro</h1>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('perfil.update') }}">
                        @csrf

                        <div class="row subtitulo">
                            <div class="col-sm-12">
                                <p>{{__('País')}}</p>
                            </div>
                        </div>

                        <div class="form-group row">
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
                        </div>
                        <div class="row subtitulo">
                            <div class="col-sm-12">
                                <p>{{__('Informações Pessoais')}}</p>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <input hidden name="id" value="{{$user->id}}">
                            <div class="col-md-6">
                                <label for="name" class="col-form-label">{{ __('Name') }}</label>
                                <input id="name" type="text" class="form-control apenasLetras @error('name') is-invalid @enderror" name="name" @if(old('name') != null) value="{{ old('name') }}" @else value="{{$user->name}}" @endif required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <div class="custom-control custom-radio custom-control-inline col-form-label">
                                  <input type="radio" id="customRadioInline3" name="check_cpf" class="custom-control-input"  >
                                  <label class="custom-control-label" for="customRadioInline3">{{__('CPF')}}</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                  <input type="radio" id="customRadioInline4" name="check_cpf" class="custom-control-input"  >
                                  <label class="custom-control-label " for="customRadioInline4">{{__('Passaporte')}}</label>
                                </div>

                                <div id="fieldCPF" @error('passaporte') style="display: none" @enderror>
                                  <input id="cpf" type="text" class="form-control @error('cpf') is-invalid @enderror" name="cpf" @if(old('cpf') != null) value="{{ old('cpf') }}" @else value="{{$user->cpf}}" @endif autocomplete="cpf" placeholder="CPF" autofocus>

                                  @error('cpf')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                  @enderror
                                </div>
                                <div id="fieldPassaporte" @error('passaporte') style="display: block" @enderror style="display: none" >
                                  <input id="passaporte" type="text" class="form-control @error('passaporte') is-invalid @enderror" name="passaporte" placeholder="{{__('Passaporte')}}" @if(old('passaporte') != null) value="{{ old('passaporte') }}" @else value="{{$user->passaporte}}" @endif autocomplete="passaporte" autofocus>

                                  @error('passaporte')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                  @enderror
                                </div>

                            </div>

                        </div>
                        <div class="row justify-content-center">

                            <div class="col-md-8">
                            <label for="instituicao" class="col-form-label">{{ __('Instituição de Ensino') }}</label>
                            <input id="instituicao" type="text" class="form-control apenasLetras @error('instituicao') is-invalid @enderror" name="instituicao" @if(old('instituicao') != null) value="{{ old('instituicao') }}" @else value="{{$user->instituicao}}" @endif required autocomplete="instituicao" autofocus>

                            @error('instituicao')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="celular" class="col-form-label">{{ __('Celular') }}</label>
                                <input id="celular" type="text" class="form-control @error('celular') is-invalid @enderror" name="celular" @if(old('celular') != null) value="{{ old('celular') }}" @else value="{{$user->celular}}" @endif required autocomplete="celular" autofocus>

                                @error('celular')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                        </div>



                        <div class="row">
                            @if ($user->membroComissaoEvento != null && count($user->membroComissaoEvento) > 0)
                                <div class="col-md-4">
                                    <label for="especialidade" class="col-form-label">{{ __('Especialidade profissional') }}</label>
                                    <input id="especialidade" type="text" class="form-control apenasLetras @error('especialidade') is-invalid @enderror" name="especialidade" required autocomplete="new-password">

                                    @error('especialidade')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            @endif

                            <div class="col-md-4">
                                <label for="password" class="col-form-label">{{ __('Senha') }}</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="password-confirm" class="col-form-label">{{ __('Confirme a Senha') }}</label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>

                        </div>

                        {{-- @if(isset(Auth::user()->revisor))
                        <div class="row subtitulo" style="margin-top:20px">
                            <div class="col-sm-12">
                                <p>Informações do perfil de revisor</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="area" class="col-form-label">{{ __('Primeira área do conhecimento') }}</label>
                                <select class="form-control @error('primeiraArea') is-invalid @enderror" name="primeiraArea" id="area">
                                    @foreach ($areas as $area)
                                        @if (Auth::user()->revisor->areaId == $area->id)
                                            <option value="{{$area->id}}" selected>{{$area->nome}}</option>
                                        @else
                                            <option value="{{$area->id}}">{{$area->nome}}</option>
                                        @endif
                                    @endforeach

                                    @error('primeiraArea')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </select>
                            </div>

                            <div class="col-sm-4">
                                <label for="area" class="col-form-label">{{ __('Segunda área do conhecimento') }}</label>
                                <select class="form-control @error('segundaArea') is-invalid @enderror" name="segundaArea" id="area">
                                    <option value="" selected>-- Alternativa de área --</option>
                                    @foreach ($areas as $area)
                                        <option value="{{$area->id}}">{{$area->nome}}</option>
                                    @endforeach

                                    @error('segundaArea')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </select>
                            </div>
                        </div>
                        @endif --}}

                        <div class="row subtitulo" style="margin-top:20px">
                            <div class="col-sm-12">
                                <p>{{__('Endereço')}}</p>
                            </div>
                        </div>

                        {{-- Endereço --}}
                        <div class="form-group row justify-content-center">
                            <div class="col-md-12">
                                <label for="cep" class="col-form-label">{{ __('CEP') }}</label>
                                <input value="{{old('cep')}}" id="cep" type="text" class="form-control @error('cep') is-invalid @enderror" name="cep" required autocomplete="cep">

                                @error('cep')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row justify-content-center">
                            <div class="col-md-6">
                                <label for="rua" class="col-form-label">{{ __('Rua') }}</label>
                                <input value="{{old('rua')}}" id="rua" type="text" class="form-control @error('rua') is-invalid @enderror" name="rua" required autocomplete="new-password">

                                @error('rua')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label for="numero" class="col-form-label">{{ __('Número') }}</label>
                                <input value="{{old('numero')}}" id="numero" min="0" type="text" class="form-control @error('numero') is-invalid @enderror" name="numero" required autocomplete="numero">

                                @error('numero')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="bairro" class="col-form-label">{{ __('Bairro') }}</label>
                                <input value="{{old('bairro')}}" id="bairro" type="text" class="form-control @error('bairro') is-invalid @enderror" name="bairro" required autocomplete="bairro">

                                @error('bairro')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row justify-content-center">
                            <div class="col-md-4">
                                <label for="cidade" class="col-form-label">{{ __('Cidade') }}</label>
                                <input value="{{old('cidade')}}" id="cidade" type="text" class="form-control apenasLetras @error('cidade') is-invalid @enderror" name="cidade" required autocomplete="cidade">

                                @error('cidade')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-sm-4">
                                <label for="complemento" class="col-form-label">{{ __('Complemento') }}</label>
                                <input id="complemento" type="text" class="form-control apenasLetras @error('complemento') is-invalid @enderror" name="complemento" value="{{ old('complemento') }}" autocomplete="complemento" autofocus>

                                @error('complemento')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-sm-4" id="groupformufinput">
                                <label for="ufInput" class="col-form-label">{{ __('UF') }}</label>
                                <input type="text" value="{{old('uf')}}" id="ufInput" class="form-control  @error('uf') is-invalid @enderror" name="uf" >

                                @error('uf')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ __($message) }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-sm-4" id="groupformuf">
                                <label for="uf" class="col-form-label">{{ __('UF') }}</label>
                                {{-- <input id="uf" type="text" class="form-control @error('uf') is-invalid @enderror" name="uf" value="{{ old('uf') }}" required autocomplete="uf" autofocus> --}}
                                <select class="form-control @error('uf') is-invalid @enderror" id="uf" name="uf">
                                    <option value="" disabled selected hidden>-- {{__('UF')}} --</option>
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
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row justify-content-center" style="margin: 20px 0 20px 0">
                            <div class="col-md-6" style="padding-left:0">
                                {{-- <a class="btn btn-secondary botao-form" href="{{route('home')}}" style="width:100%">Voltar</a> --}}
                            </div>
                            <div class="col-md-6" style="padding-right:0">
                                <button type="submit" class="btn btn-atualizar-perfil botao-form" style="width:100%">
                                    {{ __('Concluir') }}
                                </button>
                            </div>
                        </div>

                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@include('componentes.footer')
@endsection
@section('javascript')
  <script type="text/javascript" >
        $(document).ready(function($){
            $('#cpf').mask('000.000.000-00');
            if($('html').attr('lang') == 'en') {
                $('#celular').mask('(000) 000-0000');
                $('#groupformuf').addClass('d-none');
                $("#uf").prop('disabled', true);
            } else if ($('html').attr('lang') == 'pt-BR') {
                $('#cep').blur(function () {
                    pesquisacep(this.value);
                });
                $('#groupformufinput').addClass('d-none');
                $("#ufInput").prop('disabled', true);
                var SPMaskBehavior = function (val) {
                return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
                },
                spOptions = {
                    onKeyPress: function(val, e, field, options) {
                    field.mask(SPMaskBehavior.apply({}, arguments), options);
                    }
                };
                $('#celular').mask(SPMaskBehavior, spOptions);
                $('#cep').mask('00000-000');
            }
            $(".apenasLetras").mask("#", {
                maxlength: false,
                translation: {
                    '#': {pattern: /[A-zÀ-ÿ ]/, recursive: true}
                }
            });
            $('#numero').mask('0000000000000');
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript">

      $(document).ready(function(){
          // $("#fieldPassaporte").hide();
          $("#customRadioInline1").click(function(){
              $("#fieldPassaporte").hide();
              $("#fieldCPF").show();
          });

          $("#customRadioInline2").click(function(){
              $("#fieldPassaporte").show();
              $("#fieldCPF").hide();
          });
          $("#customRadioInline3").click(function(){
              $("#fieldPassaporte").hide();
              $("#fieldCPF").show();
          });

          $("#customRadioInline4").click(function(){
              $("#fieldPassaporte").show();
              $("#fieldCPF").hide();
          });

      });

    </script>
@endsection
