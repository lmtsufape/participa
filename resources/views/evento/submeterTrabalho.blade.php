@extends('layouts.app')

@section('content')
<div class="container content">

    <div class="row justify-content-center">
        <div class="col-sm-8">
            <div class="card" style="margin-top:50px">
                <div class="card-body">
                  <h2 class="card-title">{{$evento->nome}}</h2>
                  <h4 class="card-title">{{$nomeModalidade}}</h4>
                  <div class="titulo-detalhes"></div>
                  <br>
                  <h4 class="card-title">Enviar Trabalho</h4>
                  <p class="card-text">
                    <form method="POST" action="{{route('trabalho.store')}}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="eventoId" value="{{$evento->id}}">
                        <div>
                          @error('numeroMax')
                            @include('componentes.mensagens')
                          @enderror
                        </div>

                        <div class="row justify-content-center">
                            {{-- Nome Trabalho  --}}
                          <div class="col-sm-12">
                                <label for="nomeTrabalho" class="col-form-label">{{ $formSubTraba->etiquetatitulotrabalho }}</label>
                                <input id="nomeTrabalho" type="text" class="form-control @error('nomeTrabalho') is-invalid @enderror" name="nomeTrabalho" value="{{ old('nomeTrabalho') }}" required autocomplete="nomeTrabalho" autofocus>

                                @error('nomeTrabalho')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            {{-- Nome Trabalho  --}}
                          <div class="col-sm-12">
                                <label for="nomeTrabalho" class="col-form-label">{{$formSubTraba->etiquetaautortrabalho}}</label>
                                <input class="form-control" type="text" disabled value="{{Auth::user()->name}}">
                            </div>
                        </div>

                        <div class="row" style="margin-top:20px">
                          <div class="col-sm-12">
                            <div id="coautores">

                            </div>
                            <a href="#" class="btn btn-primary" id="addCoautor" style="width:100%;margin-top:10px">{{$formSubTraba->etiquetacoautortrabalho}}</a>
                          </div>
                        </div>

                        @if($regrasubarq->texto == true)
                          @if ($regrasubarq->caracteres == true)
                            <div class="row justify-content-center">
                              <div class="col-sm-12">
                                  <label for="resumo" class="col-form-label">{{$formSubTraba->etiquetaresumotrabalho}}</label>
                                  <textarea id="resumo" class="char-count form-control @error('resumo') is-invalid @enderror" data-ls-module="charCounter" minlength="{{$regrasubarq->mincaracteres}}" maxlength="{{$regrasubarq->maxcaracteres}}" name="resumo" value="{{ old('resumo') }}"  autocomplete="resumo" autofocusrows="5"></textarea>
                                  <p class="text-muted"><small><span name="resumo">0</span></small> - Min Caracteres: {{$regrasubarq->mincaracteres}} - Max Caracteres: {{$regrasubarq->maxcaracteres}}</p>
                                  @error('resumo')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                                  @enderror

                              </div>
                            </div>                              
                          @else
                            <div class="row justify-content-center">
                              <div class="col-sm-12">
                                  <label for="resumo" class="col-form-label">{{$formSubTraba->etiquetaresumotrabalho}}</label>
                                  <textarea id="palavra" class="form-control palavra @error('resumo') is-invalid @enderror" name="resumo" value="{{ old('resumo') }}"  autocomplete="resumo" autofocusrows="5"></textarea>
                                  <p class="text-muted"><small><span id="numpalavra">0</span></small> - Min Palavras: {{$regrasubarq->minpalavras}} - Max Palavras: {{$regrasubarq->maxpalavras}}</p>
                                  @error('resumo')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                                  @enderror

                              </div>
                            </div>
                          @endif
                        @endif
                        <!-- Areas -->
                        <div class="row justify-content-center">
                            <div class="col-sm-12">
                                <label for="area" class="col-form-label">{{$formSubTraba->etiquetaareatrabalho}}</label>
                                <select class="form-control @error('area') is-invalid @enderror" id="area" name="areaId">
                                    <option value="" disabled selected hidden>-- Área --</option>
                                    {{-- @foreach($areasEnomes as $area)
                                      <option value="{{$area->id}}">{{$area->nome}}</option>
                                    @endforeach --}}
                                    {{-- Apenas um teste abaixo --}}
                                    @foreach($areasEspecificas as $area)
                                      <option value="{{$area->id}}">{{$area->nome}}</option>
                                    @endforeach
                                </select>
                                @error('areaId')
                                <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                                  <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Modalidades -->
                        <input type="hidden" name="modalidadeId" value="{{$modalidadeEspecifica}}">
                        {{-- MODALIDADE SERIA PASSADA COMO HIDDEN --}}
                        {{-- <div class="row justify-content-center">
                            <div class="col-sm-12">
                                <label for="areaModalidadeId" class="col-form-label">{{ __('Modalidade:') }}</label>
                                <select class="form-control @error('modalidade') is-invalid @enderror" id="modalidade" name="modalidadeId">
                                  <option value="" disabled selected hidden>-- Modalidade --</option>
                                </select>

                                @error('modalidadeId')
                                <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                                  <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div> --}}
                        <div class="row justify-content-center">
                          {{-- Submeter trabalho --}}
                          {{-- *Obs: As condições abaixo servem para manter o layout do botão para upload
                            e dos botões para baixar a regra e o template. --}}
                          @if ($regrasubarq->arquivo == true && isset($regras) && isset($templates))
                            <div class="col-sm-6" style="margin-top: 20px;">
                              <label for="nomeTrabalho" class="col-form-label">{{$formSubTraba->etiquetauploadtrabalho}}</label>

                              <div class="custom-file">
                                <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="arquivo">
                              </div>
                              <small>Arquivos aceitos nos formatos 
                                @if($regrasubarq->pdf == true)pdf - @endif
                                @if($regrasubarq->jpg == true)jpg - @endif
                                @if($regrasubarq->jpeg == true)jpeg - @endif
                                @if($regrasubarq->png == true)png - @endif
                                @if($regrasubarq->docx == true)docx - @endif
                                @if($regrasubarq->odt == true)odt @endif.</small>
                              @error('arquivo')
                              <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                                <strong>{{ $message }}</strong>
                              </span>
                              @enderror
                            </div>

                            <div class="col-sm-3" style="margin-top: 20px;">
                              <label for="nomeTrabalho" class="col-form-label">{{$formSubTraba->etiquetabaixarregra}}:</label>
                              <a href="{{route('download.regra', ['file' => $regras->nome])}}" target="_new" style="font-size: 20px; color: #114048ff;" >
                                <img class="" src="{{asset('img/icons/file-download-solid.svg')}}" style="width:20px">
                              </a>
                            </div>
                            
                            <div class="col-sm-3" style="margin-top: 20px;">
                              <label for="nomeTrabalho" class="col-form-label">{{$formSubTraba->etiquetabaixartemplate}}:</label>
                              <a href="{{route('download.template', ['file' => $templates->nome])}}" target="_new" style="font-size: 20px; color: #114048ff;" >
                                <img class="" src="{{asset('img/icons/file-download-solid.svg')}}" style="width:20px">
                              </a>
                            </div>

                          @elseif ($regrasubarq->arquivo == true && isset($regras) && $templates == null)
                            <div class="col-sm-6" style="margin-top: 20px;">
                              <label for="nomeTrabalho" class="col-form-label">{{$formSubTraba->etiquetauploadtrabalho}}</label>

                              <div class="custom-file">
                                <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="arquivo">
                              </div>
                              <small>Arquivos aceitos nos formatos 
                                @if($regrasubarq->pdf == true)pdf - @endif
                                @if($regrasubarq->jpg == true)jpg - @endif
                                @if($regrasubarq->jpeg == true)jpeg - @endif
                                @if($regrasubarq->png == true)png - @endif
                                @if($regrasubarq->docx == true)docx - @endif
                                @if($regrasubarq->odt == true)odt @endif.</small>
                              @error('arquivo')
                              <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                                <strong>{{ $message }}</strong>
                              </span>
                              @enderror
                            </div>

                            <div class="col-sm-6" style="margin-top: 20px;">
                              <label for="nomeTrabalho" class="col-form-label">{{$formSubTraba->etiquetabaixarregra}}:</label>
                              <a href="{{route('download.regra', ['file' => $regras->nome])}}" target="_new" style="font-size: 20px; color: #114048ff;" >
                                <img class="" src="{{asset('img/icons/file-download-solid.svg')}}" style="width:20px">
                              </a>
                            </div>
                          @elseif ($regrasubarq->arquivo == true && isset($templates) && $regras == null)
                            <div class="col-sm-6" style="margin-top: 20px;">
                              <label for="nomeTrabalho" class="col-form-label">{{$formSubTraba->etiquetauploadtrabalho}}:</label>

                              <div class="custom-file">
                                <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="arquivo">
                              </div>
                              <small>Arquivos aceitos nos formatos 
                                @if($regrasubarq->pdf == true)pdf - @endif
                                @if($regrasubarq->jpg == true)jpg - @endif
                                @if($regrasubarq->jpeg == true)jpeg - @endif
                                @if($regrasubarq->png == true)png - @endif
                                @if($regrasubarq->docx == true)docx - @endif
                                @if($regrasubarq->odt == true)odt @endif.</small>
                              @error('arquivo')
                              <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                                <strong>{{ $message }}</strong>
                              </span>
                              @enderror
                            </div>
                            
                            <div class="col-sm-6" style="margin-top: 20px;">
                              <label for="nomeTrabalho" class="col-form-label">{{$formSubTraba->etiquetabaixartemplate}}:</label>
                              <a href="{{route('download.template', ['file' => $templates->nome])}}" target="_new" style="font-size: 20px; color: #114048ff;" >
                                <img class="" src="{{asset('img/icons/file-download-solid.svg')}}" style="width:20px">
                              </a>
                            </div>
                          @else

                            <div class="col-sm-12" style="margin-top: 20px;">
                              <label for="nomeTrabalho" class="col-form-label">{{$formSubTraba->etiquetauploadtrabalho}}</label>

                              <div class="custom-file">
                                <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="arquivo">
                              </div>
                              <small>Arquivos aceitos nos formatos 
                                @if($regrasubarq->pdf == true)pdf - @endif
                                @if($regrasubarq->jpg == true)jpg - @endif
                                @if($regrasubarq->jpeg == true)jpeg - @endif
                                @if($regrasubarq->png == true)png - @endif
                                @if($regrasubarq->docx == true)docx - @endif
                                @if($regrasubarq->odt == true)odt @endif.</small>
                              @error('arquivo')
                              <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                                <strong>{{ $message }}</strong>
                              </span>
                              @enderror
                            </div>
                          @endif
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

  $(document).ready(function(){
    $('.char-count').keyup(function() {
        var maxLength = parseInt($(this).attr('maxlength')); 
        var length = $(this).val().length;
        // var newLength = maxLength-length;
        
        var name = $(this).attr('name');
        
        $('span[name="'+name+'"]').text(length);
    });
  });

  $(document).ready(function(){
    $('.palavra').keyup(function() {
        var maxLength = parseInt($(this).attr('maxlength')); 
        var texto = $(this).val().length;
        console.log(texto);
        if ($(this).val()[length - 1] == " ") {
          var cont = $(this).val().length;
          // console.log("Contador:");
          // console.log(cont);
        }

        // console.log("Texto:");
        // console.log(texto);

        var name = $(this).attr('name');
        
        $('span[name="'+name+'"]').text(length);
    });
  });

  // function getLength() {
  //       getWord = document.getElementById( 'palavra' ).value,
  //       num = document.getElementById( 'numpalavra' );
        
  //       if ( getWord == '' ){
  //         console.log("IF");
  //         num.textContent = '0';
  //       }
  //       else if ( getWord.search( /\s[a-z0-9]+$/gi ) > -1 ) num.textContent = getWord.split( ' ' ).length;
  //       else if ( getWord.search( /[^\s]$/ ) > -1 ) num.textContent = '1';
  // }

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
  // Remover Coautor
  $(document).on('click','.delete',function(){
    $(this).closest('.row').remove();
          return false;
  });

  function addModalidade(areaId){
    console.log(modalidades)
    $("#modalidade").empty();
    for(let i = 0; i < modalidades.length; i++){
      if(modalidades[i].areaId == areaId){
        console.log(modalidades[i]);
        $("#modalidade").append("<option value="+modalidades[i].modalidadeId+">"+modalidades[i].modalidadeNome+"</option>")
      }
    }
  }
  function montarLinhaInput(){

    return  "<div class="+"row"+">"+
                "<div class="+"col-sm-6"+">"+
                    "<label>Nome Completo</label>"+
                    "<input"+" type="+'text'+" style="+"margin-bottom:10px"+" class="+'form-control emailCoautor'+" name="+'nomeCoautor[]'+" placeholder="+"Nome"+" required>"+
                "</div>"+
                "<div class="+"col-sm-5"+">"+
                    "<label>E-mail</label>"+
                    "<input"+" type="+'email'+" style="+"margin-bottom:10px"+" class="+'form-control emailCoautor'+" name="+'emailCoautor[]'+" placeholder="+"E-mail"+" required>"+
                "</div>"+
                "<div class="+"col-sm-1"+">"+
                    "<a href="+"#"+" class="+"delete"+">"+
                      "<img src="+"/img/icons/user-times-solid.svg"+" style="+"width:25px;margin-top:35px"+">"+
                    "</a>"+
                "</div>"+
            "</div>";
  }
</script>
@endsection
