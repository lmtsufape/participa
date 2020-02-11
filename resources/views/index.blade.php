@extends('layouts.app')

@section('content')

<div class="content">
   <div class="row justify-content-center curved" style="margin-bottom:-5px">
    <div class=" col-sm-8 text">
      <h1>Nome do Sistema</h1>
      <p>
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
        Quisque sit amet rutrum arcu, et rutrum arcu. Nam vitae suscipit leo. 
        Donec vestibulum laoreet lacinia. Aenean ultricies est odio, non venenatis lorem mattis a. 
        Maecenas aliquet neque felis, a malesuada nisi blandit lobortis. 
        Suspendisse a ultrices libero, eleifend dignissim neque.
      </p>
    </div>
    <div class="col-sm-4">
      <img src="{{asset('img/pc.png')}}" alt="">
    </div>
    <a class="btn btn-outline-light btn-lg"  id="modulo1" href="#" 
    style="margin-bottom:10px;" role="button" data-toggle="modal" data-target="#modalLogin">Criar Evento</a>
  </div>

  
  <div class="row justify-content-center" style="margin-bottom:5%">
    <div class="col-sm-12" style="padding:0">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#114048ff" 
        fill-opacity="1" d="M0,288L80,261.3C160,235,320,181,480,176C640,171,800,213,960,
        218.7C1120,224,1280,192,1360,176L1440,160L1440,0L1360,0C1280,0,1120,0,960,0C800,
        0,640,0,480,0C320,0,160,0,80,0L0,0Z"></path>
      </svg>
    </div>
  </div>
        

  <div class="row justify-content-center" style="padding:0 5% 0 5%">
    <div class="col-sm-4">
      <div class="info-modulo">
        <div class="info-modulo-head">
          <img src="{{asset('img/iphone.png')}}" alt="">
          <h1>Módulo 1</h1>
          
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#ccc" fill-opacity="1" d="M0,224L120,213.3C240,203,480,181,720,160C960,139,1200,117,1320,106.7L1440,96L1440,320L1320,320C1200,320,960,320,720,320C480,320,240,320,120,320L0,320Z"></path></svg>
        <div class="info-modulo-body">

          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
            Proin nec volutpat urna, et interdum turpis. Ut aliquam quis 
            tortor sit amet scelerisque. Etiam vehicula nulla a odio 
            imperdiet dictum. In id pretium nisi. In sed nisi sit amet 
            dolor suscipit mollis eu ut sapien. Nam id velit at libero varius cursus. </p>
          </div>
      </div>
    </div>

    <div class="col-sm-4">
      <div class="info-modulo">
        <div class="info-modulo-head">
          <img src="{{asset('img/iphone.png')}}" alt="">
          <h1>Módulo 2</h1>
          
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#ccc" fill-opacity="1" d="M0,224L120,213.3C240,203,480,181,720,160C960,139,1200,117,1320,106.7L1440,96L1440,320L1320,320C1200,320,960,320,720,320C480,320,240,320,120,320L0,320Z"></path></svg>
        <div class="info-modulo-body">

          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
            Proin nec volutpat urna, et interdum turpis. Ut aliquam quis 
            tortor sit amet scelerisque. Etiam vehicula nulla a odio 
            imperdiet dictum. In id pretium nisi. In sed nisi sit amet 
            dolor suscipit mollis eu ut sapien. Nam id velit at libero varius cursus. </p>
          </div>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="info-modulo">
        <div class="info-modulo-head">
          <img src="{{asset('img/iphone.png')}}" alt="">
          <h1>Módulo 3</h1>
          
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#ccc" fill-opacity="1" d="M0,224L120,213.3C240,203,480,181,720,160C960,139,1200,117,1320,106.7L1440,96L1440,320L1320,320C1200,320,960,320,720,320C480,320,240,320,120,320L0,320Z"></path></svg>
        <div class="info-modulo-body">

          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
            Proin nec volutpat urna, et interdum turpis. Ut aliquam quis 
            tortor sit amet scelerisque. Etiam vehicula nulla a odio 
            imperdiet dictum. In id pretium nisi. In sed nisi sit amet 
            dolor suscipit mollis eu ut sapien. Nam id velit at libero varius cursus. </p>
          </div>
        </div>
      </div>
    </div>
  </div>



  <!-- Modal Login-->
  <div class="modal fade" id="modalLogin" tabindex="-1" role="dialog" aria-labelledby="modalLogin" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="row justify-content-center">
                <div class="titulo-login-cadastro">Login</div>
            </div>

            <div class="form-group row">
                
                <div class="col-md-12">
                    <label for="email" class="col-form-label text-md-right">{{ __('E-Mail Address') }}</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                
                <div class="col-md-12">
                    <label for="password" class="col-form-label text-md-right">{{ __('Password') }}</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                        <label class="form-check-label" for="remember">
                            {{ __('Remember Me') }}
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group row mb-0">
                
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary" style="width:100%">
                        {{ __('Login') }}
                    </button>
                    <div class="row justify-content-center">
                    
                        @if (Route::has('password.request'))
                        <a class="btn btn-link" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                        @endif
                    </div>
                </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

    <!-- Modal Cadastro-->
    <div class="modal fade" id="modalCadastro" tabindex="-1" role="dialog" aria-labelledby="modalCadastro" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-body">
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
  
  @endsection
  
@section('javascript')
  

@endsection