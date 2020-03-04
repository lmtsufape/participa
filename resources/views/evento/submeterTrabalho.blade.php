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
                                <label for="resumo" class="col-form-label">{{ __('Resumo do Trabalho') }}</label>
                                <textarea id="resumo" class="form-control @error('resumo') is-invalid @enderror" name="resumo" value="{{ old('resumo') }}" required autocomplete="resumo" autofocusrows="5"></textarea>

                                @error('resumo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror


                            </div>
                        </div>

                        <!-- Areas -->
                        <div class="row justify-content-center">
                            <div class="col-sm-12">
                                <label for="area" class="col-form-label">{{ __('Área') }}</label>
                                <select class="form-control @error('area') is-invalid @enderror" id="area" name="area">
                                    <option value="" disabled selected hidden>-- Área --</option>
                                    @foreach($areasEnomes as $area)
                                      <option value="{{$area->id}}">{{$area->nome}}</option>
                                    @endforeach
                                </select>

                                @error('area')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Modalidades -->
                        <div class="row justify-content-center">
                            <div class="col-sm-12">
                                <label for="areaModalidadeId" class="col-form-label">{{ __('Modalidade') }}</label>
                                <select class="form-control @error('modalidade') is-invalid @enderror" id="modalidade" name="modalidade">


                                </select>

                                @error('modalidade')
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
                                {{ __('Enviar') }}
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

  var modalidades = JSON.parse('<?php echo json_encode($modalidadesIDeNome) ?>');
  $(function(){
    // Coautores
    $('#addCoautor').click(function(){
      linha = montarLinhaInput();
      $('#coautores').append(linha);
    });

    // Exibir modalidade de acordo com a área
    $("#area").change(function(){
      console.log($(this).val());
      addModalidade($(this).val());
    });
  });

  function addModalidade(areaId){
    console.log(modalidades)
    $("#modalidade").empty();
    for(let i = 0; i < modalidades.length; i++){
      if(modalidades[i].areaId == areaId){
        console.log(modalidades[i]);
        $("#modalidade").append("<option value="+modalidades[i].modalidadeID+">"+modalidades[i].modalidadeNome+"</option>")
      }
    }
  }
  function montarLinhaInput(){
    // let numCoautores = console.log($("#coautores").children().length + 1);
    return  "<div class="+"row"+">"+
                "<div class="+"col-sm-12"+">"+
                    "<input"+" type="+'email'+" style="+"margin-bottom:10px"+" class="+'form-control emailCoautor'+" name="+'emailCoautor'+" placeholder="+"E-mail do Coautor"+" required>"+
                "</div>"+
            "</div>";
  }
</script>
@endsection
