@extends('layouts.app')

@section('content')
<div class="container content">

    <div class="row justify-content-center">
        <div class="col-sm-8">
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
                        <div class="row" style="margin-top:20px">
                          <div class="col-sm-12">
                            <label for="">E-mail do Coautor</label>
                            <div id="coautores">

                            </div>
                            <a href="#" class="btn btn-primary" id="addCoautor" style="width:100%;margin-top:10px">Adicionar Coautor</a>
                          </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-sm-12">
                                <!-- <label for="emailCoautor" class="col-form-label">{{ __('E-mail dos coautores') }} -->
                                <!-- <input id="emailCoautor" type="text" class="form-control @error('emailCoautor') is-invalid @enderror" name="emailCoautor" value="{{ old('emailCoautor') }}" required autocomplete="emailCoautor" autofocus>
                                @error('emailCoautor')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                                @error('emailNaoEncontrado')
                                {{$message}}
                                @enderror -->

                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-sm-12">
                                <label for="resumo" class="col-form-label">{{ __('Resumo do Trabalho') }}</label>
                                <textarea id="resumo" class="form-control @error('resumo') is-invalid @enderror" name="resumo" value="{{ old('resumo') }}" required autocomplete="resumo" autofocusrows="5"></textarea>

                                @error('resumo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
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
                        <div class="col-md-6">
                            <a href="{{route('evento.visualizar',['id'=>$evento->id])}}" class="btn btn-secondary" style="width:100%">Cancelar</a>
                        </div>
                        <div class="col-md-6">
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

@section('javascript')
<script type="text/javascript">
  $(function(){
    $('#addCoautor').click(function(){
      console.log('Add Coautor');
      linha = montarLinhaInput();
      $('#coautores').append(linha);
    });
  });

  function montarLinhaInput(){
    return "<input"+" type="+'email'+" class="+'form-control emailCoautor'+" name="+'emailCoautor'+" placeholder="+"E-mail do Coautor"+" required>";
  }
</script>
@endsection
