@extends('layouts.app')

@section('content')
<div class="container content">

    <div class="row justify-content-center">
        <div class="col-sm-10">
            <div class="card" style="margin-top:50px">
                <div class="card-body">
                  <h2 class="card-title">{{$evento->nome}}</h2>
                  <h4 class="card-title">{{$modalidade->nome}}</h4>
                  <div class="titulo-detalhes"></div>
                  <br>
                  <h4 class="card-title">Enviar Trabalho</h4>
                  <p class="card-text">
                    <form method="POST" action="{{route('trabalho.store', $modalidade->id)}}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="eventoId" value="{{$evento->id}}">
                        <input type="hidden" name="modalidadeId" value="{{$modalidade->id}}">
                        <div>
                          @error('tipoExtensao')
                            @include('componentes.mensagens')
                          @enderror
                        </div>
                        <div>
                          @error('numeroMax')
                            @include('componentes.mensagens')
                          @enderror
                        </div>
                        @foreach ($ordemCampos as $indice)
                          @if ($indice == "etiquetatitulotrabalho")
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
                          @endif
                          @if ($indice == "etiquetaautortrabalho")
                            {{-- <div class="row justify-content-center">
                               Autor  
                              <div class="col-sm-12">
                                  <label for="nomeTrabalho" class="col-form-label">{{$formSubTraba->etiquetaautortrabalho}}</label>
                                  <input class="form-control" type="text" disabled value="{{Auth::user()->name}}">
                              </div>
                            </div> --}}
                          @endif
                          @if ($indice == "etiquetacoautortrabalho")
                            <div class="flexContainer" style="margin-top:20px">
                              <div class="col-sm-12">
                                <h4>Autores</h4>                                 
                                  <div id="coautores" class="flexContainer " >
                                    <div class="item card" id="1" style="order:1">
                                      <div class="row card-body">
                                          <div class="col-sm-4">
                                              <label>E-mail</label>
                                              <input type="email" style="margin-bottom:10px" id="email${order}" value="{{Auth::user()->email}}" onclick="digitarEmail(email${order})" class="form-control emailCoautor" name="emailCoautor[]" placeholder="E-mail" required>
                                          </div>
                                          <div class="col-sm-5">
                                              <label>Nome Completo</label>
                                              <input type="text" style="margin-bottom:10px" value="{{Auth::user()->name}}" class="form-control emailCoautor" name="nomeCoautor[]" placeholder="Nome" required>
                                          </div>
                                          <div class="col-sm-3">
                                              <a href="#" class="delete pr-2">
                                                <img src="{{asset('/img/icons/user-times-solid.svg')}}" style="margin-bottom:15px;width:25px;">
                                              </a>
                                              <a href="#" onclick="myFunction(event)">
                                                <i class="fas fa-arrow-up fa-2x" id="arrow-up" style=""></i>
                                              </a>
                                              <a href="#" onclick="myFunction(event)">
                                                <i class="fas fa-arrow-down fa-2x" id="arrow-down" style="margin-top:35px"></i>
                                              </a>
                                              
                                          </div>
                                      </div>
                                    </div>
                                  </div>
                                <a href="#" onclick="addLinha(event)" class="btn btn-primary" id="addCoautor" style="width:100%;margin-top:10px">{{$formSubTraba->etiquetacoautortrabalho}}</a>
                              </div>
                            </div>
                          @endif
                          @if ($indice == "etiquetaresumotrabalho")
                            @if ($modalidade->caracteres == true)
                              <div class="row justify-content-center">
                                <div class="col-sm-12">
                                    <label for="resumo" class="col-form-label">{{$formSubTraba->etiquetaresumotrabalho}}</label>
                                    <textarea id="resumo" class="char-count form-control @error('resumo') is-invalid @enderror" data-ls-module="charCounter" minlength="{{$modalidade->mincaracteres}}" maxlength="{{$modalidade->maxcaracteres}}" name="resumo" value="{{ old('resumo') }}"  autocomplete="resumo" autofocusrows="5"></textarea>
                                    <p class="text-muted"><small><span name="resumo">0</span></small> - Min Caracteres: {{$modalidade->mincaracteres}} - Max Caracteres: {{$modalidade->maxcaracteres}}</p>
                                    @error('resumo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                              </div>                              
                            @elseif ($modalidade->palavras == true)
                              <div class="row justify-content-center">
                                <div class="col-sm-12">
                                    <label for="resumo" class="col-form-label">{{$formSubTraba->etiquetaresumotrabalho}}</label>
                                    <textarea id="palavra" class="form-control palavra @error('resumo') is-invalid @enderror" name="resumo" value="{{ old('resumo') }}"  autocomplete="resumo" autofocusrows="5"></textarea>
                                    <p class="text-muted"><small><span id="numpalavra">0</span></small> - Min Palavras: {{$modalidade->minpalavras}} - Max Palavras: {{$modalidade->maxpalavras}}</p>
                                    @error('resumo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                              </div>
                            @endif
                          @endif
                          @if ($indice == "etiquetaareatrabalho")
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
                                        @foreach($areas as $area)
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
                          @endif
                          @if ($indice == "etiquetauploadtrabalho")
                            <div class="row justify-content-center">
                              {{-- Submeter trabalho --}}

                              @if ($modalidade->arquivo == true)
                                <div class="col-sm-12" style="margin-top: 20px;">
                                  <label for="nomeTrabalho" class="col-form-label">{{$formSubTraba->etiquetauploadtrabalho}}</label>
    
                                  <div class="custom-file">
                                    <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="arquivo" required>
                                  </div>
                                  <small>Arquivos aceitos nos formatos 
                                    @if($modalidade->pdf == true)<span> - pdf</span>@endif
                                    @if($modalidade->jpg == true)<span> - jpg</span>@endif
                                    @if($modalidade->jpeg == true)<span> - jpeg</span>@endif
                                    @if($modalidade->png == true)<span> - png</span>@endif
                                    @if($modalidade->docx == true)<span> - docx</span>@endif
                                    @if($modalidade->odt == true)<span> - odt</span>@endif 
                                    @if($modalidade->zip == true)<span> - zip</span>@endif
                                    @if($modalidade->svg == true)<span> - svg</span>@endif.
                                  </small>
                                  @error('arquivo')
                                  <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                                    <strong>{{ $message }}</strong>
                                  </span>
                                  @enderror
                                </div>
                              @endif
                            </div>
                          @endif
                          @if ($indice == "etiquetacampoextra1")
                            @if ($formSubTraba->checkcampoextra1 == true)
                              @if ($formSubTraba->tipocampoextra1 == "textosimples")  
                                {{-- Texto Simples --}}
                                <div class="row justify-content-center">
                                  {{-- Nome Trabalho  --}}
                                  <div class="col-sm-12">
                                        <label for="campoextra1simples" class="col-form-label">{{ $formSubTraba->etiquetacampoextra1}}:</label>
                                        <input id="campoextra1simples" type="text" class="form-control @error('campoextra1simples') is-invalid @enderror" name="campoextra1simples" value="{{ old('campoextra1simples') }}" required autocomplete="campoextra1simples" autofocus>
      
                                        @error('campoextra1simples')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                  </div>
                                </div>
                              @elseif ($formSubTraba->tipocampoextra1 == "textogrande")
                                {{-- Texto Grande --}}
                                <div class="row justify-content-center">
                                <div class="col-sm-12">
                                      <label for="campoextra1grande" class="col-form-label">{{ $formSubTraba->etiquetacampoextra1}}:</label>
                                      <textarea id="campoextra1grande" type="text" class="form-control @error('campoextra1grande') is-invalid @enderror" name="campoextra1grande" value="{{ old('campoextra1grande') }}" required autocomplete="campoextra1grande" autofocus></textarea>
      
                                      @error('campoextra1grande')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                      @enderror
                                  </div>
                                </div>
                              @elseif ($formSubTraba->tipocampoextra1 == "upload")
                                <div class="col-sm-12" style="margin-top: 20px;">
                                  <label for="campoextra1arquivo" class="col-form-label">{{ $formSubTraba->etiquetacampoextra1}}:</label>
            
                                  <div class="custom-file">
                                    <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="campoextra1arquivo" required>
                                  </div>
                                  <small>Algum texto aqui?</small>
                                  @error('campoextra1arquivo')
                                  <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                                    <strong>{{ $message }}</strong>
                                  </span>
                                  @enderror
                                </div>
                              @endif
                            @endif
                          @endif
                          @if ($indice == "etiquetacampoextra2")
                            @if ($formSubTraba->checkcampoextra2 == true)
                              @if ($formSubTraba->tipocampoextra2 == "textosimples")
                                <div class="row justify-content-center">
                                  {{-- Nome Trabalho  --}}
                                <div class="col-sm-12">
                                      <label for="campoextra2simples" class="col-form-label">{{ $formSubTraba->etiquetacampoextra2}}:</label>
                                      <input id="campoextra2simples" type="text" class="form-control @error('campoextra2simples') is-invalid @enderror" name="campoextra2simples" value="{{ old('campoextra2simples') }}" required autocomplete="campoextra2simples" autofocus>
          
                                      @error('campoextra2simples')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                      @enderror
                                  </div>
                                </div>
                              @elseif ($formSubTraba->tipocampoextra2 == "textogrande")
                                <div class="row justify-content-center">
                                  {{-- Nome Trabalho  --}}
                                <div class="col-sm-12">
                                      <label for="campoextra2grande" class="col-form-label">{{ $formSubTraba->etiquetacampoextra2}}:</label>
                                      <textarea id="campoextra2grande" type="text" class="form-control @error('campoextra2grande') is-invalid @enderror" name="campoextra2grande" value="{{ old('campoextra2grande') }}" required autocomplete="campoextra2grande" autofocus></textarea>
          
                                      @error('campoextra2grande')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                      @enderror
                                  </div>
                                </div>
                              @elseif ($formSubTraba->tipocampoextra2 == "upload")
                                <div class="col-sm-12" style="margin-top: 20px;">
                                  <label for="campoextra2arquivo" class="col-form-label">{{ $formSubTraba->etiquetacampoextra2}}:</label>
            
                                  <div class="custom-file">
                                    <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="campoextra2arquivo" required>
                                  </div>
                                  <small>Algum texto aqui?</small>
                                  @error('campoextra2arquivo')
                                  <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                                    <strong>{{ $message }}</strong>
                                  </span>
                                  @enderror
                                </div>
                              @endif
                            @endif
                          @endif
                          @if ($indice == "etiquetacampoextra3")
                            @if ($formSubTraba->checkcampoextra3 == true)
                              @if ($formSubTraba->tipocampoextra3 == "textosimples")
                                <div class="row justify-content-center">
                                  {{-- Nome Trabalho  --}}
                                <div class="col-sm-12">
                                      <label for="campoextra3simples" class="col-form-label">{{ $formSubTraba->etiquetacampoextra3}}:</label>
                                      <input id="campoextra3simples" type="text" class="form-control @error('campoextra3simples') is-invalid @enderror" name="campoextra3simples" value="{{ old('campoextra3simples') }}" required autocomplete="campoextra3simples" autofocus>
      
                                      @error('campoextra3simples')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                      @enderror
                                  </div>
                                </div>
                              @elseif ($formSubTraba->tipocampoextra3 == "textogrande")
                                <div class="row justify-content-center">
                                  {{-- Nome Trabalho  --}}
                                <div class="col-sm-12">
                                      <label for="campoextra3grande" class="col-form-label">{{ $formSubTraba->etiquetacampoextra3}}:</label>
                                      <textarea id="campoextra3grande" type="text" class="form-control @error('campoextra3grande') is-invalid @enderror" name="campoextra3grande" value="{{ old('campoextra3grande') }}" required autocomplete="campoextra3grande" autofocus></textarea>
      
                                      @error('campoextra3grande')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                      @enderror
                                  </div>
                                </div>
                              @elseif ($formSubTraba->tipocampoextra3 == "upload")
                                {{-- Arquivo de Regras  --}}
                                <div class="col-sm-12" style="margin-top: 20px;">
                                  <label for="campoextra3arquivo" class="col-form-label">{{ $formSubTraba->etiquetacampoextra3}}:</label>
            
                                  <div class="custom-file">
                                    <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="campoextra3arquivo" required>
                                  </div>
                                  <small>Algum texto aqui?</small>
                                  @error('campoextra3arquivo')
                                  <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                                    <strong>{{ $message }}</strong>
                                  </span>
                                  @enderror
                                </div>
                              @endif
                            @endif
                          @endif
                          @if ($indice == "etiquetacampoextra4")
                            @if ($formSubTraba->checkcampoextra4 == true)
                              @if ($formSubTraba->tipocampoextra4 == "textosimples")
                                <div class="row justify-content-center">
                                  {{-- Nome Trabalho  --}}
                                <div class="col-sm-12">
                                      <label for="campoextra4simples" class="col-form-label">{{ $formSubTraba->etiquetacampoextra4}}:</label>
                                      <input id="campoextra4simples" type="text" class="form-control @error('campoextra4simples') is-invalid @enderror" name="campoextra4simples" value="{{ old('campoextra4simples') }}" required autocomplete="campoextra4simples" autofocus>
      
                                      @error('campoextra4simples')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                      @enderror
                                  </div>
                                </div>
                              @elseif ($formSubTraba->tipocampoextra4 == "textogrande")
                                <div class="row justify-content-center">
                                  {{-- Nome Trabalho  --}}
                                <div class="col-sm-12">
                                      <label for="campoextra4grande" class="col-form-label">{{ $formSubTraba->etiquetacampoextra4}}:</label>
                                      <textarea id="campoextra4grande" type="text" class="form-control @error('campoextra4grande') is-invalid @enderror" name="campoextra4grande" value="{{ old('campoextra4grande') }}" required autocomplete="campoextra4grande" autofocus></textarea>
      
                                      @error('campoextra4grande')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                      @enderror
                                  </div>
                                </div>
                              @elseif ($formSubTraba->tipocampoextra4 == "upload")
                                {{-- Arquivo de Regras  --}}
                                <div class="col-sm-12" style="margin-top: 20px;">
                                  <label for="campoextra4arquivo" class="col-form-label">{{$formSubTraba->etiquetacampoextra4}}:</label>
            
                                  <div class="custom-file">
                                    <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="campoextra4arquivo" required>
                                  </div>
                                  <small>Algum texto aqui?</small>
                                  @error('campoextra4arquivo')
                                  <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                                    <strong>{{ $message }}</strong>
                                  </span>
                                  @enderror
                                </div>
                              @endif
                            @endif
                          @endif
                          @if ($indice == "etiquetacampoextra5")
                            @if ($formSubTraba->checkcampoextra5 == true)
                              @if ($formSubTraba->tipocampoextra5 == "textosimples")
                                <div class="row justify-content-center">
                                  {{-- Nome Trabalho  --}}
                                <div class="col-sm-12">
                                      <label for="campoextra5simples" class="col-form-label">{{ $formSubTraba->etiquetacampoextra5}}:</label>
                                      <input id="campoextra5simples" type="text" class="form-control @error('campoextra5simples') is-invalid @enderror" name="campoextra5simples" value="{{ old('campoextra5simples') }}" required autocomplete="campoextra5simples" autofocus>
      
                                      @error('campoextra5simples')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                      @enderror
                                  </div>
                                </div>
                              @elseif ($formSubTraba->tipocampoextra5 == "textogrande")
                                <div class="row justify-content-center">
                                  {{-- Nome Trabalho  --}}
                                <div class="col-sm-12">
                                      <label for="campoextra5" class="col-form-label">{{ $formSubTraba->etiquetacampoextra5}}:</label>
                                      <textarea id="campoextra5grande" type="text" class="form-control @error('campoextra5grande') is-invalid @enderror" name="campoextra5grande" value="{{ old('campoextra5grande') }}" required autocomplete="campoextra5grande" autofocus></textarea>
      
                                      @error('campoextra5grande')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                      @enderror
                                  </div>
                                </div>
                              @elseif ($formSubTraba->tipocampoextra5 == "upload")
                                {{-- Arquivo de Regras  --}}
                                <div class="col-sm-12" style="margin-top: 20px;">
                                  <label for="campoextra5arquivo" class="col-form-label">{{ $formSubTraba->etiquetacampoextra5}}:</label>
            
                                  <div class="custom-file">
                                    <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="campoextra5arquivo" required>
                                  </div>
                                  <small>Algum texto aqui?</small>
                                  @error('campoextra5arquivo')
                                  <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                                    <strong>{{ $message }}</strong>
                                  </span>
                                  @enderror
                                </div>
                              @endif
                            @endif
                          @endif
                        @endforeach
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

  // 

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
        // console.log(texto);
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

  $(document).ready(function(){
    function ordenar(event){
      event.preventDefault();
      // console.log(event);
    }
  });

  let order = 1;

  function myFunction(event) {
    event.preventDefault();
    el = event.srcElement.id
    // console.log( event.path['5'].childNodes)
    arr = event.path['5'].childNodes;    
    
    if(el == "arrow-up"){      
      number = event.path['4'].style.order;
      if(number == 1) return

      for (var i = 0; i < arr.length; i++) {
        if(event.path['5'].childNodes[i].style['order'] == parseInt(event.path['4'].style.order, 10) - 1 ){
          event.path['5'].childNodes[i].style['order'] = parseInt(event.path['5'].childNodes[i].style['order'], 10) + parseInt(1, 10);
          
          event.path['4'].style.order =  parseInt(event.path['4'].style.order, 10) - parseInt(1, 10);
          
          break;
        }
      }

      
        
    }else if(el == "arrow-down"){
      number = event.path['4'].style.order;
      if(number == order) return
      
      for (var i = 0; i < arr.length; i++) {
        if(event.path['5'].childNodes[i].style['order'] == parseInt(event.path['4'].style.order, 10) + 1 ){
          event.path['5'].childNodes[i].style['order'] = parseInt(event.path['5'].childNodes[i].style['order'], 10) - parseInt(1, 10);
          
          event.path['4'].style.order =  parseInt(event.path['4'].style.order, 10) + parseInt(1, 10);
          
          break;
        }
      }

    }
  }


  function addLinha(event){
    event.preventDefault();
    order += 1;
      linha = montarLinhaInput(order);
      $('#coautores').append(linha);
  }

  $(function(){
    // Coautores
    
    

    // Exibir modalidade de acordo com a área
    $("#area").change(function(){
      // console.log($(this).val());
      addModalidade($(this).val());
    });


  });
  // Remover Coautor
  $(document).on('click','.delete',function(){
    $(this).closest('.item').remove();
          return false;
  });

  function addModalidade(areaId){
    // console.log(modalidades)
    $("#modalidade").empty();
    for(let i = 0; i < modalidades.length; i++){
      if(modalidades[i].areaId == areaId){
        // console.log(modalidades[i]);
        $("#modalidade").append("<option value="+modalidades[i].modalidadeId+">"+modalidades[i].modalidadeNome+"</option>")
      }
    }
  }
  function montarLinhaInput(order){

    return `<div class="item card" id="${order}" style="order:${order}">
              <div class="row card-body">
                  <div class="col-sm-4">
                      <label>E-mail</label>
                      <input type="email" style="margin-bottom:10px" id="email${order}" onclick="digitarEmail(email${order})" class="form-control emailCoautor" name="emailCoautor[]" placeholder="E-mail" required>
                  </div>
                  <div class="col-sm-5">
                      <label>Nome Completo</label>
                      <input type="text" style="margin-bottom:10px" value="" class="form-control emailCoautor" name="nomeCoautor[]" placeholder="Nome" required>
                  </div>
                  <div class="col-sm-3">
                      <a href="#" class="delete pr-2"><img src="{{asset('/img/icons/user-times-solid.svg')}}" style="margin-bottom:15px;width:25px;"></a>
                      <a href="#" onclick="myFunction(event)"><i class="fas fa-arrow-up fa-2x" id="arrow-up" style=""></i></a>
                      <a href="#" onclick="myFunction(event)"><i class="fas fa-arrow-down fa-2x" id="arrow-down" style="margin-top:35px"></i></a>
                  </div>
              </div>
            </div>`;
  }
</script>

<script>

  let digitarEmail = card => {
    
    let email = document.querySelector('#'+card.id);

    email.addEventListener('keyup', function(event){
      // console.log(email)
      
        let data = {
        email: email.value,
        
        _token: '{{csrf_token()}}'
      };
      // console.log(data.email.indexOf('@'));
      if (!(data.email=="" || data.email.indexOf('@')==-1 || data.email.indexOf('.')==-1)) {
        $.ajax({
          type: 'POST',
          url: '{{ route("search.user") }}',
          data: data,
          dataType: 'json',
          success: function(res){
            if(res.user[0] != null){
              // console.log('pega')
              event.path[2].children[1].children[1].value = res.user[0]['name'];
            }
              
          },
          error: function(err){
              // console.log('err')
              // console.log(err)
          }
        });
      }
    });
  }

  
  

  

</script>
@endsection
