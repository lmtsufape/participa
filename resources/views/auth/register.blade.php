@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card card-login-cadastro">
                {{-- <div class="card-header">{{ __('Register') }}</div> --}}

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
          
                        <div class="row justify-content-center">
                            <div class="titulo-login-cadastro">Cadastro</div>
                        </div>
          
                        <div class="form-group row">
                            
                            <div class="col-md-12">
                                <label for="name" class="col-form-label">{{ __('Name') }}</label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
          
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
          
                        <div class="form-group row">
                            
                          <div class="col-md-12">
                              <label for="cpf" class="col-form-label">{{ __('CPF') }}</label>
                              <input id="cpf" type="text" class="form-control @error('cpf') is-invalid @enderror" name="cpf" value="{{ old('cpf') }}" required autocomplete="cpf" autofocus>
          
                              @error('cpf')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                              @enderror
                          </div>
                        </div>
          
                        <div class="form-group row">
                              
                          <div class="col-md-12">
                              <label for="celular" class="col-form-label">{{ __('Celular') }}</label>
                              <input id="celular" type="number" class="form-control @error('celular') is-invalid @enderror" name="celular" value="{{ old('celular') }}" required autocomplete="celular" autofocus>
          
                              @error('celular')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                              @enderror
                          </div>
                        </div>
          
                        <div class="form-group row">
                                
                          <div class="col-md-12">
                              <label for="instituicao" class="col-form-label">{{ __('Instituição de Ensino') }}</label>
                              <input id="instituicao" type="text" class="form-control @error('instituicao') is-invalid @enderror" name="instituicao" value="{{ old('instituicao') }}" required autocomplete="instituicao" autofocus>
          
                              @error('instituicao')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                              @enderror
                          </div>
                        </div>
                        <div class="form-group row">
                                
                          <div class="col-md-12">
                              <label for="especProfissional" class="col-form-label">{{ __('Esperiência Profissional') }}</label>
                              <input id="especProfissional" type="text" class="form-control @error('especProfissional') is-invalid @enderror" name="especProfissional" value="{{ old('especProfissional') }}" required autocomplete="especProfissional" autofocus>
          
                              @error('especProfissional')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                              @enderror
                          </div>
                        </div>
                        <div class="form-group row">
                            
                            <div class="col-md-12">
                                <label for="email" class="col-form-label">{{ __('E-Mail Address') }}</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
          
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
          
                        <div class="form-group row">
                            
                            <div class="col-md-12">
                                <label for="password" class="col-form-label">{{ __('Password') }}</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
          
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            
                          <div class="col-md-12">
                              <label for="password-confirm" class="col-form-label">{{ __('Confirm Password') }}</label>
                              <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                          </div>
                       </div>
          
                        {{-- Endereço --}}
                        <div class="form-group row">
                            
                          <div class="col-md-12">
                              <label for="rua" class="col-form-label">{{ __('Rua') }}</label>
                              <input id="rua" type="text" class="form-control @error('rua') is-invalid @enderror" name="rua" required autocomplete="new-password">
          
                              @error('rua')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                              @enderror
                          </div>
                        </div>
          
                        <div class="form-group row">
                            
                          <div class="col-md-12">
                              <label for="numero" class="col-form-label">{{ __('Número') }}</label>
                              <input id="numero" type="number" class="form-control @error('numero') is-invalid @enderror" name="numero" required autocomplete="numero">
          
                              @error('numero')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                              @enderror
                          </div>
                        </div>
                        <div class="form-group row">
                            
                          <div class="col-md-12">
                              <label for="bairro" class="col-form-label">{{ __('Bairro') }}</label>
                              <input id="bairro" type="text" class="form-control @error('bairro') is-invalid @enderror" name="bairro" required autocomplete="bairro">
          
                              @error('bairro')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                              @enderror
                          </div>
                        </div>
                        <div class="form-group row">
                            
                          <div class="col-md-12">
                              <label for="cidade" class="col-form-label">{{ __('Cidade') }}</label>
                              <input id="cidade" type="text" class="form-control @error('cidade') is-invalid @enderror" name="cidade" required autocomplete="cidade">
          
                              @error('cidade')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                              @enderror
                          </div>
                        </div>
                        <div class="form-group row">
                            
                          <div class="col-md-12">
                              <label for="uf" class="col-form-label">{{ __('UF') }}</label>
                              <input id="uf" type="text" class="form-control @error('uf') is-invalid @enderror" name="uf" required autocomplete="uf">
          
                              @error('uf')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                              @enderror
                          </div>
                        </div>
                        <div class="form-group row">
                            
                          <div class="col-md-12">
                              <label for="cep" class="col-form-label">{{ __('CEP') }}</label>
                              <input id="cep" type="text" class="form-control @error('cep') is-invalid @enderror" name="cep" required autocomplete="cep">
          
                              @error('cep')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                              @enderror
                          </div>
                        </div>
          
          
          
                        
          
                        <div class="form-group row mb-0">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" style="width:100%">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
