@extends('layouts.app')

@section('content')
<div class="container content">

    <div class="row justify-content-center">
        <div class="col-sm-6">
            <div class="card" style="margin-top:50px">
                <div class="card-body">
                  <h5 class="card-title">Enviar Trabalho</h5>
                  <p class="card-text">
                    <form method="POST" action="{{route('trabalho.store')}}">
                        @csrf
                        <input type="hidden" name="eventoId" value="{{$evento->id}}">
                        <div class="row justify-content-center">
                            {{-- Nome Trabalho  --}}
                          <div class="col-sm-12">
                                <label for="nomeTrabalho" class="col-form-label">{{ __('Título do Trabalho') }}</label>
                                <input id="nomeTrabalho" type="text" class="form-control @error('nomeTrabalho') is-invalid @enderror" name="nomeTrabalho" value="{{ old('nomeTrabalho') }}" required autocomplete="nomeTrabalho" autofocus>
                    
                                @error('nomeTrabalho')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-sm-12">
                                <label for="emailCoautor" class="col-form-label">{{ __('E-mail dos coautores') }}
                                <img src="{{asset('img/icons/question-circle-solid.svg')}}" style="width:17px" data-toggle="tooltip" data-placement="top" title="O e-mail dos coautores deve ser separado por vírgula."> 
                                </label>
                                <input id="emailCoautor" type="text" class="form-control @error('emailCoautor') is-invalid @enderror" name="emailCoautor" value="{{ old('emailCoautor') }}" required autocomplete="emailCoautor" autofocus>
                                <small id="emailHelp" class="form-text text-muted">O e-mail dos coautores deve ser separado por vírgula.</small>
                    
                                @error('emailCoautor')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                    
                                @error('emailNaoEncontrado')
                                {{$message}}
                                @enderror
                    
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-sm-12">
                                <label for="areaModalidadeId" class="col-form-label">{{ __('Área - Modalidade') }}</label>
                                <select class="form-control @error('areaModalidadeId') is-invalid @enderror" id="areaModalidadeId" name="areaModalidadeId">
                                    <option value="" disabled selected hidden>-- Área - Modalidade --</option>
                                    @foreach($areaModalidades as $areaModalidade)
                                      <option value="{{$areaModalidade->id}}">{{$areaModalidade->area->nome}} - {{$areaModalidade->modalidade->nome}}</option>
                                    @endforeach
                                </select>
                    
                                @error('areaModalidadeId')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                            
                            
                    
                        
                    </p>
                    <div class="row justify-content-center">
                
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary" style="width:100%">
                                {{ __('Finalizar') }}
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