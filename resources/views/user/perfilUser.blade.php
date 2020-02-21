@extends('layouts.app')

@section('content')

<div class="container content">
    <div class="row titulo">
        <h1>Perfil</h1>
    </div>

    <div class="row subtitulo">   
        <div class="col-sm-12">
            <p>Informações Pessoais</p>
        </div>     
    </div>

    <form method="POST" action="{{ route('perfil') }}">
        @csrf
        <div class="row justify-content-center">
            <input hidden name="id" value="{{$user->id}}">
            <div class="col-md-6">
                <label for="name" class="col-form-label">{{ __('Name') }}</label>
                <input value="{{$user->name}}" id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="col-md-3">
                <label for="cpf" class="col-form-label">{{ __('CPF') }}</label>
                <input value="{{$user->cpf}}" id="cpf" type="text" class="form-control @error('cpf') is-invalid @enderror" name="cpf" value="{{ old('cpf') }}" required autocomplete="cpf" autofocus>

                @error('cpf')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="col-md-3">
                <label for="celular" class="col-form-label">{{ __('Celular') }}</label>
                <input value="{{$user->celular}}" id="celular" type="number" class="form-control @error('celular') is-invalid @enderror" name="celular" value="{{ old('celular') }}" required autocomplete="celular" autofocus>

                @error('celular')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="row justify-content-center">
            
            <div class="col-md-6">
              <label for="instituicao" class="col-form-label">{{ __('Instituição de Ensino') }}</label>
              <input value="{{$user->instituicao}}" id="instituicao" type="text" class="form-control @error('instituicao') is-invalid @enderror" name="instituicao" value="{{ old('instituicao') }}" required autocomplete="instituicao" autofocus>

              @error('instituicao')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
            </div>

            <div class="col-md-6">
                <label for="especProfissional" class="col-form-label">{{ __('Esperiência Profissional') }}</label>
                <input value="{{$user->especProfissional}}" id="especProfissional" type="text" class="form-control @error('especProfissional') is-invalid @enderror" name="especProfissional" value="{{ old('especProfissional') }}" required autocomplete="especProfissional" autofocus>

                @error('especProfissional')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="row subtitulo" style="margin-top:20px">   
            <div class="col-sm-12">
                <p>Endereço</p>
            </div>     
        </div>

        {{-- Endereço --}}
        <div class="form-group row justify-content-center">
            <div class="col-md-2">
                <label for="cep" class="col-form-label">{{ __('CEP') }}</label>
                <input value="{{$end->cep}}" id="cep" type="text" class="form-control @error('cep') is-invalid @enderror" name="cep" required autocomplete="cep">

                @error('cep')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="rua" class="col-form-label">{{ __('Rua') }}</label>
                <input value="{{$end->rua}}" id="rua" type="text" class="form-control @error('rua') is-invalid @enderror" name="rua" required autocomplete="new-password">

                @error('rua')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="col-md-4">
              <label for="numero" class="col-form-label">{{ __('Número') }}</label>
              <input value="{{$end->numero}}" id="numero" type="number" class="form-control @error('numero') is-invalid @enderror" name="numero" required autocomplete="numero">

              @error('numero')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
          </div>
          </div>

          
          <div class="form-group row justify-content-center">              
            <div class="col-md-4">
                <label for="bairro" class="col-form-label">{{ __('Bairro') }}</label>
                <input value="{{$end->bairro}}" id="bairro" type="text" class="form-control @error('bairro') is-invalid @enderror" name="bairro" required autocomplete="bairro">

                @error('bairro')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="col-md-4">
                  <label for="cidade" class="col-form-label">{{ __('Cidade') }}</label>
                  <input value="{{$end->cidade}}" id="cidade" type="text" class="form-control @error('cidade') is-invalid @enderror" name="cidade" required autocomplete="cidade">

                  @error('cidade')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
            </div>
            <div class="col-md-4">
                <label for="uf" class="col-form-label">{{ __('UF') }}</label>
                <input value="{{$end->uf}}" id="uf" type="text" class="form-control @error('uf') is-invalid @enderror" name="uf" required autocomplete="uf">

                @error('uf')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
          </div>
          <div class="row justify-content-center" style="margin: 20px 0 20px 0">

            <div class="col-md-6" style="padding-left:0">
                <a class="btn btn-secondary botao-form" href="{{route('home')}}" style="width:100%">Voltar</a>
            </div>
            <div class="col-md-6" style="padding-right:0">
                <button type="submit" class="btn btn-primary botao-form" style="width:100%">
                    {{ __('Concluir') }}
                </button>
            </div>
        </div>

        </form>    
    </div>
</div>
    
@endsection