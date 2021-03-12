@extends('layouts.app')

@section('content')



@foreach ($trabalhos as $trabalho)
  <div class="modal fade" id="modalTrabalho_{{$trabalho->id}}" tabindex="-1" role="dialog" aria-labelledby="modalTrabalho" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #114048ff; color: white;">
          <h5 class="modal-title" id="exampleModalCenterTitle">Submeter nova versão</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="POST" action="{{route('trabalho.novaVersao')}}" enctype="multipart/form-data">
          @csrf
        <div class="modal-body">

          <div class="row justify-content-center">
            <div class="col-sm-12">
                @error('error')
                  <div class="alert alert-danger">
                    <p>{{$message}}</p>
                  </div>
                @enderror
                @error('tipoExtensao')
                <div class="alert alert-danger">
                  <p>{{$message}}</p>
                </div>
                @enderror
                <input type="hidden" name="trabalhoId" value="{{$trabalho->id}}" id="trabalhoNovaVersaoId">
                
                {{-- Arquivo  --}}
                <label for="nomeTrabalho" class="col-form-label">{{ __('Novo arquivo para ') }}{{$trabalho->titulo}}</label>

                <div class="custom-file">
                  <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="arquivo">
                </div>

                <small>Arquivos aceitos nos formatos 
                  @if($trabalho->modalidade->pdf == true)<span> - pdf</span>@endif
                  @if($trabalho->modalidade->jpg == true)<span> - jpg</span>@endif
                  @if($trabalho->modalidade->jpeg == true)<span> - jpeg</span>@endif
                  @if($trabalho->modalidade->png == true)<span> - png</span>@endif
                  @if($trabalho->modalidade->docx == true)<span> - docx</span>@endif
                  @if($trabalho->modalidade->odt == true)<span> - odt</span>@endif 
                  @if($trabalho->modalidade->zip == true)<span> - zip</span>@endif
                  @if($trabalho->modalidade->svg == true)<span> - svg</span>@endif.
                </small>
                @error('arquivo')
                <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
          <button type="submit" class="btn btn-primary">Salvar</button>
        </div>
      </form>
      </div>
    </div>
  </div>
@endforeach
  
<div class="container content" style="margin-top: 80px;">
    {{-- titulo da página --}}
    <div class="row justify-content-center titulo-detalhes">
      @if(session('mensagem'))
        <div class="col-md-12" style="margin-top: 5px;">
            <div class="alert alert-success">
              <p>{{session('mensagem')}}</p>
            </div>
        </div>
      @endif
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-12">
                    <h1>Meus Trabalhos</h1>
                </div>
               
            </div>
        </div>
    </div>
    <br>
    <div class="row margin">
        <div class="col-sm-12 info-evento">
            <h4>Como Autor</h4>
        </div>
    </div>

    <!-- Tabela de trabalhos -->

    <div class="row justify-content-center">
        <div class="col-sm-12">

        @if (count($trabalhos) > 0)
          <table class="table table-responsive-lg table-hover">
              <thead>
              <tr>
                  <th>Evento</th>
                  <th>ID</th>
                  <th>Título</th>
                  <th style="text-align:center">Baixar</th>
                  <th style="text-align:center">Editar</th>
                  <th style="text-align:center">Excluir</th>
              </tr>
              </thead>
              <tbody>
              @foreach($trabalhos as $trabalho)
                  <tr>
                  <td>{{$trabalho->evento->nome}}</td>
                  <td>{{$trabalho->id}}</td>
                  <td>{{$trabalho->titulo}}</td>
                  <td style="text-align:center">
                      <a href="{{route('downloadTrabalho', ['id' => $trabalho->id])}}" target="_new" style="font-size: 20px; color: #114048ff;" >
                          <img class="" src="{{asset('img/icons/file-download-solid.svg')}}" style="width:20px">
                      </a>
                  </td>
                  <td style="text-align:center">
                      {{-- <a href="#" data-toggle="modal" data-target="#modalEditarTrabalho_{{$trabalho->id}}" style="color:#114048ff">
                        <img class="" src="{{asset('img/icons/file-upload-solid.svg')}}" style="width:20px">
                      </a> --}}
                  </td>
                  <td style="text-align:center">
                    <a href="#" @if($agora <= $trabalho->modalidade->fimSubmissao) data-toggle="modal" data-target="#modalExcluirTrabalho_{{$trabalho->id}}" style="color:#114048ff" @else data-toggle="popover" data-placement="bottom" title="Não permitido" data-content="A exclusão do trabalho só é permitida durante o periodo de submissão." @endif>
                      <img class="" src="{{asset('img/icons/trash-alt-regular.svg')}}" style="width:20px">
                    </a>
                  </td>
                  </tr>
              @endforeach
              </tbody>
          </table>
        @else 
          Você não submeteu nenhum trabalho...
        @endif
        </div>
    </div>
    
    <br>

    <div class="row margin">
        <div class="col-sm-12 info-evento">
            <h4>Como Coautor</h4>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-sm-12">

        @if ($trabalhosCoautor != null && count($trabalhosCoautor) > 0)
          <table class="table table-responsive-lg table-hover">
              <thead>
              <tr>
                  <th>Evento</th>
                  <th>Título</th>
                  <th>Autor</th>
                  <th style="text-align:center">Baixar</th>
              </tr>
              </thead>
              <tbody>
              @foreach($trabalhosCoautor as $trabalho)
                  <tr>
                  <td>{{$trabalho->evento->nome}}</td>
                  <td>{{$trabalho->titulo}}</td>
                  <td>{{$trabalho->autor->name}}</td>
                  <td style="text-align:center">
                      <a href="{{route('downloadTrabalho', ['id' => $trabalho->id])}}" target="_new" style="font-size: 20px; color: #114048ff;" >
                          <img class="" src="{{asset('img/icons/file-download-solid.svg')}}" style="width:20px">
                      </a>
                  </td>                    
                  </tr>
              @endforeach
              </tbody>
          </table>
        @else
          Você não participa como coautor em nenhum trabalho...
        @endif
        </div>
    </div>

</div>


@foreach ($trabalhos as $trabalho)
  @if($agora <= $trabalho->modalidade->fimSubmissao)
    <!-- Modal  excluir trabalho -->
    <div class="modal fade" id="modalExcluirTrabalho_{{$trabalho->id}}" tabindex="-1" aria-labelledby="modalExcluirTrabalho_{{$trabalho->id}}Label" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background-color: #114048ff; color: white;">
            <h5 class="modal-title" id="modalExcluirTrabalho_{{$trabalho->id}}Label">Excluir {{$trabalho->id}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="formExcluirTrab{{$trabalho->id}}" action="{{route('excluir.trabalho', ['id' => $trabalho->id])}}" method="POST">
              @csrf
              <p>
                Tem certeza que deseja excluir esse trabalho?
              </p>
              <small>Ninguém poderá ver seu trabalho após a exclusão.</small>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
            <button type="submit" class="btn btn-primary" form="formExcluirTrab{{$trabalho->id}}">Sim</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Fim Modal excluir trabalho -->
    <!-- Modal  editar trabalho -->
    <div class="modal fade" id="modalEditarTrabalho_{{$trabalho->id}}" tabindex="-1" aria-labelledby="modalEditarTrabalho_{{$trabalho->id}}Label" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background-color: #114048ff; color: white;">
            <h5 class="modal-title" id="modalEditarTrabalho_{{$trabalho->id}}Label">Editar {{$trabalho->id}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="formEditarTrab{{$trabalho->id}}" action="{{route('editar.trabalho', ['id' => $trabalho->id])}}" method="POST">
              @csrf

              @php
                $formSubTraba = $trabalho->evento->formSubTrab;
                $ordem = explode(",", $formSubTraba->ordemCampos);
                $modalidade = $trabalho->modalidade;
                $areas = $trabalho->evento->areas;
              @endphp

              @foreach ($ordem as $indice)
                @if ($indice == "etiquetatitulotrabalho")
                  <div class="row justify-content-center">
                    {{-- Nome Trabalho  --}}
                    <div class="col-sm-12">
                        <label for="nomeTrabalho_{{$trabalho->id}}" class="col-form-label">{{ $formSubTraba->etiquetatitulotrabalho }}</label>
                        <input id="nomeTrabalho_{{$trabalho->id}}" type="text" class="form-control @error('nomeTrabalho'.$trabalho->id) is-invalid @enderror" name="nomeTrabalho{{$trabalho->id}}" value="@if(old('nomeTrabalho'.$trabalho->id)!=null){{old('nomeTrabalho'.$trabalho->id)}}@else{{$trabalho->titulo}}@endif" required autocomplete="nomeTrabalho" autofocus>

                        @error('nomeTrabalho'.$trabalho->id)
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
                    <div id="divCoautores_{{$trabalho->id}}" class="col-sm-12">
                      <h4>Autores</h4>                                 
                        <div id="coautores_{{$trabalho->id}}" class="flexContainer " >
                          @if (old('nomeCoautor') != null)
                            @foreach (old('nomeCoautor') as $i => $nomeCoautor)
                              <div class="item card" id="{{$i+1}}" style="order: {{$i+1}};">
                                <div class="row card-body">
                                    <div class="col-sm-4">
                                        <label>E-mail</label>
                                        <input type="email" style="margin-bottom:10px" id="email{{$i+1}}" value="{{old('emailCoautor.'.$i)}}" onclick="digitarEmail(email{{$i+1}})" class="form-control emailCoautor" name="emailCoautor[]" placeholder="E-mail" required>
                                    </div>
                                    <div class="col-sm-5">
                                        <label>Nome Completo</label>
                                        <input type="text" style="margin-bottom:10px" value="{{$nomeCoautor}}" class="form-control emailCoautor" name="nomeCoautor[]" placeholder="Nome" required>
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
                            @endforeach
                          @else 
                          
                            @foreach ($trabalho->coautors as $i => $coautor)
                              <div class="item card" id="{{$i+1}}" style="order:{{$i+1}}">
                                <div class="row card-body">
                                    <div class="col-sm-4">
                                        <label>E-mail</label>
                                        <input type="email" style="margin-bottom:10px" id="email${order}" value="{{$coautor->user->email}}" onclick="digitarEmail(email${order})" class="form-control emailCoautor" name="emailCoautor{{$trabalho->id}}[]" placeholder="E-mail" required>
                                    </div>
                                    <div class="col-sm-5">
                                        <label>Nome Completo</label>
                                        <input type="text" style="margin-bottom:10px" value="{{$coautor->user->name}}" class="form-control emailCoautor" name="nomeCoautor{{$trabalho->id}}[]" placeholder="Nome" required>
                                    </div>
                                    <div class="col-sm-3">
                                        <a href="#" onclick="deletarCoautor(this)" class="delete pr-2">
                                          <img src="{{asset('/img/icons/user-times-solid.svg')}}" style="margin-bottom:15px;width:25px;">
                                        </a>
                                        <a href="#" onclick="mover(this, 1, {{$trabalho->id}})">
                                          <i class="fas fa-arrow-up fa-2x" id="arrow-up" style=""></i>
                                        </a>
                                        <a href="#" onclick="mover(this, 0, {{$trabalho->id}})">
                                          <i class="fas fa-arrow-down fa-2x" id="arrow-down" style="margin-top:35px"></i>
                                        </a>
                                    </div>
                                </div>
                              </div>
                            @endforeach
                          @endif
                        </div>
                      
                    </div>
                    <div class="col-sm-12">
                      <a href="#" onclick="montarLinhaInput(this, {{$trabalho->id}})" class="btn btn-primary addCoautor" id="addCoautor_{{$trabalho->id}}" style="width:100%;margin-top:10px">{{$formSubTraba->etiquetacoautortrabalho}}</a>
                    </div>
                  </div>
                @endif
                @if ($modalidade->texto && $indice == "etiquetaresumotrabalho")
                  @if ($modalidade->caracteres == true)
                    <div class="row justify-content-center">
                      <div class="col-sm-12">
                          <label for="resumo_{{$trabalho->id}}" class="col-form-label">{{$formSubTraba->etiquetaresumotrabalho}}</label>
                          <textarea id="resumo_{{$trabalho->id}}" class="char-count form-control @error('resumo'.$trabalho->id) is-invalid @enderror" data-ls-module="charCounter" minlength="{{$modalidade->mincaracteres}}" maxlength="{{$modalidade->maxcaracteres}}" name="resumo{{$trabalho->id}}"  autocomplete="resumo" autofocusrows="5">@if(old('resumo'.$trabalho->id) != null){{old('resumo'.$trabalho->id)}}@else{{$trabalho->resumo}}@endif</textarea>
                          <p class="text-muted"><small><span name="resumo">{{strlen($trabalho->resumo)}}</span></small> - Min Caracteres: {{$modalidade->mincaracteres}} - Max Caracteres: {{$modalidade->maxcaracteres}}</p>
                          @error('resumo'.$trabalho->id)
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                          @enderror

                      </div>
                    </div>                              
                  @elseif ($modalidade->palavras == true)
                    <div class="row justify-content-center">
                      <div class="col-sm-12">
                          <label for="resumo_{{$trabalho->id}}" class="col-form-label">{{$formSubTraba->etiquetaresumotrabalho}}</label>
                          <textarea id="resumo_{{$trabalho->id}}" class="form-control palavra @error('resumo'.$trabalho->id) is-invalid @enderror" name="resumo{{$trabalho->id}}" autocomplete="resumo" autofocusrows="5">@if(old('resumo'.$trabalho->id) != null){{old('resumo'.$trabalho->id)}}@else{{$trabalho->resumo}}@endif</textarea>
                          <p class="text-muted"><small><span id="numpalavra">{{count(explode(" ", $trabalho->resumo))}}</span></small> - Min Palavras: {{$modalidade->minpalavras}} - Max Palavras: {{$modalidade->maxpalavras}}</p>
                          @error('resumo'.$trabalho->id)
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
                          <label for="area_{{$trabalho->id}}" class="col-form-label">{{$formSubTraba->etiquetaareatrabalho}}</label>
                          <select id="area_{{$trabalho->id}}" class="form-control @error('area'.$trabalho->id) is-invalid @enderror" name="area{{$trabalho->id}}">
                              <option value="" disabled selected hidden>-- Área --</option>
                              {{-- Apenas um teste abaixo --}}
                              @if (old('area'.$trabalho->id) != null) 
                                @foreach($areas as $area)
                                  <option value="{{$area->id}}" @if(old('area') == $area->id) selected @endif>{{$area->nome}}</option>
                                @endforeach
                              @else 
                                @foreach($areas as $area)
                                  <option value="{{$area->id}}" @if($trabalho->areaId == $area->id) selected @endif>{{$area->nome}}</option>
                                @endforeach
                              @endif
                              
                          </select>
                          @error('area'.$trabalho->id)
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
                        <label for="nomeTrabalho" class="col-form-label">{{$formSubTraba->etiquetauploadtrabalho}}:</label>
                        <a href="{{route('downloadTrabalho', ['id' => $trabalho->id])}}">Arquivo atual</a>
                        <br>
                        <small>Para trocar o arquivo envie um novo.</small>
                        <div class="custom-file">
                          <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="arquivo_{{$trabalho->id}}">
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
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary" form="formEditarTrab{{$trabalho->id}}">Salvar</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Fim Modal editar trabalho -->
  @endif
@endforeach



@endsection

@section('javascript')

<script>
  function montarLinhaInput(div, id){
    var tamanho = div.parentElement.parentElement.children[0].children[1].children.length;
    var order = null;

    try {
      order = parseInt(div.parentElement.parentElement.children[0].children[1].children[tamanho-1].id) + 1;
    } catch (TypeError) {
      order = 1;
    }

    var html = `<div class="item card" id="${order}" style="order:${order}">
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
                        <a href="#" onclick="deletarCoautor(this)" class="delete pr-2"><img src="{{asset('/img/icons/user-times-solid.svg')}}" style="margin-bottom:15px;width:25px;"></a>
                        <a href="#" onclick="mover(this, 1, ${id})"><i class="fas fa-arrow-up fa-2x" id="arrow-up" style=""></i></a>
                        <a href="#" onclick="mover(this, 0, ${id})"><i class="fas fa-arrow-down fa-2x" id="arrow-down" style="margin-top:35px"></i></a>
                    </div>
                  </div>
                </div>`;

    $('#coautores_'+id).append(html);
  }

  function deletarCoautor(div) {
    div.parentElement.parentElement.parentElement.remove();
  }

  @error('trabalhoId')
    $(document).ready(function() {
      $("#modalTrabalho_{{$message}}").modal('show');
    })
  @enderror

  // $(document).ready(function(){
  //   $('.char-count').keyup(function() {
  //       var maxLength = parseInt($(this).attr('maxlength')); 
  //       var length = $(this).val().length;
  //       // var newLength = maxLength-length;
        
  //       var name = $(this).attr('name');
        
  //       $('span[name="'+name+'"]').text(length);
  //   });
  // });

  // $(document).ready(function(){
  //   $('.palavra').keyup(function() {
  //       var maxLength = parseInt($(this).attr('maxlength')); 
  //       var texto = $(this).val().length;
  //       // console.log(texto);
  //       if ($(this).val()[length - 1] == " ") {
  //         var cont = $(this).val().length;
  //         // console.log("Contador:");
  //         // console.log(cont);
  //       }

  //       // console.log("Texto:");
  //       // console.log(texto);

  //       var name = $(this).attr('name');
        
  //       $('span[name="'+name+'"]').text(length);
  //   });
  // });

  // $(document).ready(function(){
  //   function ordenar(event){
  //     event.preventDefault();
  //     // console.log(event);
  //   }
  // });

  // let order = 1;

  function mover(div, direcao, id) {
    ordemClicado = div.parentElement.parentElement.parentElement.style.order;  
    // var salva = div.parentElement.parentElement.parentElement.parentElement;
    // html = '<div id="aaa">' + salva.innerHTML +'</div>';
    // console.log(div.parentElement.parentElement.parentElement.parentElement.remove());
    // $('#divCoautores_'+id).append(html);
    
    if(direcao == 0) {
      var divs = div.parentElement.parentElement.parentElement.parentElement.children;
      console.log(divs[1]);
      for (var i = 0; i < divs.length; i++) {
        if (ordemClicado == divs[i].style.order && divs[i+1] != null) {
          var idCima = divs[i].style.order;
          divs[i].style.order = parseInt(divs[i+1].style.order, 10);
          divs[i+1].style.order = parseInt(idCima, 10);
          var div = divs[i];
          divs[i] = divs[i+1];
          divs[i+1] = div;
          
        }
      }
      console.log(divs[1]);
    } else {
      var divs = div.parentElement.parentElement.parentElement.parentElement.children;
      for (var i = 0; i < divs.length; i++) {
        console.log(ordemClicado == divs[i].id);
        // if (idClicado == divs[i].id) {
        //   var cima = div[i-1];
        //   var baixo = div[i];
        //   div[i].style.order = baixo.style.order;
        //   if (cima != null) {
        //     div[i+1].style.order = cima.style.order;
        //   }
        // }
      }
    }
  }


  // function addLinha(event){
  //   event.preventDefault();
  //   order += 1;
  //     linha = montarLinhaInput(order);
  //     $('#coautores').append(linha);
  // }

  // $(function(){
  //   // Coautores
    
    

  //   // Exibir modalidade de acordo com a área
  //   $("#area").change(function(){
  //     // console.log($(this).val());
  //     addModalidade($(this).val());
  //   });


  // });
  // // Remover Coautor
  // $(document).on('click','.delete',function(){
  //   $(this).closest('.item').remove();
  //         return false;
  // });

  // function addModalidade(areaId){
  //   // console.log(modalidades)
  //   $("#modalidade").empty();
  //   for(let i = 0; i < modalidades.length; i++){
  //     if(modalidades[i].areaId == areaId){
  //       // console.log(modalidades[i]);
  //       $("#modalidade").append("<option value="+modalidades[i].modalidadeId+">"+modalidades[i].modalidadeNome+"</option>")
  //     }
  //   }
  // }

  // let digitarEmail = card => {
    
  //   let email = document.querySelector('#'+card.id);

  //   email.addEventListener('keyup', function(event){
  //     // console.log(email)
      
  //       let data = {
  //       email: email.value,
        
  //       _token: '{{csrf_token()}}'
  //     };
  //     // console.log(data.email.indexOf('@'));
  //     if (!(data.email=="" || data.email.indexOf('@')==-1 || data.email.indexOf('.')==-1)) {
  //       $.ajax({
  //         type: 'POST',
  //         url: '{{ route("search.user") }}',
  //         data: data,
  //         dataType: 'json',
  //         success: function(res){
  //           if(res.user[0] != null){
  //             // console.log('pega')
  //             event.path[2].children[1].children[1].value = res.user[0]['name'];
  //           }
              
  //         },
  //         error: function(err){
  //             // console.log('err')
  //             // console.log(err)
  //         }
  //       });
  //     }
  //   });
  // }

</script>

@endsection
