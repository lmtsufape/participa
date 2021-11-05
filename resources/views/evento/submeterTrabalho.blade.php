@extends('layouts.app')

@section('content')
<div class="container content">

    <div class="row justify-content-center">
        <div class="col-sm-10">
            <div class="card" style="margin-top:50px">
                <div class="card-body">
                  <h2 class="card-title">{{$evento->nome}}</h2>
                  <h4 class="card-title">Modalidade: {{$modalidade->nome}}</h4>
                  <div class="titulo-detalhes"></div>
                  <br>
                  <h4 class="card-title">Enviar Trabalho</h4>
                  <p class="card-text">
                    <form method="POST" action="{{route('trabalho.store', $modalidade->id)}}" enctype="multipart/form-data" class="form-prevent-multiple-submits">
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
                                  <label for="nomeTrabalho" class="col-form-label"><strong>{{ $formSubTraba->etiquetatitulotrabalho }}</strong> </label>
                                  <input id="nomeTrabalho" type="text" class="form-control @error('nomeTrabalho') is-invalid @enderror" name="nomeTrabalho" value="{{ old('nomeTrabalho') }}" autocomplete="nomeTrabalho" autofocus>

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
                            <div class="row " style="margin-top:20px">
                              <div class="col-sm-12">
                                <div class="row">
                                    <div class="col">
                                        <h4>Autor(a)</h4>
                                    </div>
                                    <div class="col mr-5">
                                        <div class="float-right">
                                            <a href="#" onclick="addLinha(event)" id="addCoautor" style="color: #196572ff;text-decoration: none;" title="Clique aqui para adicionar coautor(es), se houver">
                                                <i class="fas fa-user-plus fa-2x"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div id="coautores" class="row " >
                                    @if (old('nomeCoautor') != null)
                                        <div class="item card" id="1" style="width:100%;">
                                            <div class="row card-body">
                                                <div class="col-sm-4">
                                                    <label>E-mail</label>
                                                    <input type="email" style="margin-bottom:10px" id="email${order}" value="{{Auth::user()->email}}" onclick="digitarEmail(email${order})" class="form-control emailCoautor" name="emailCoautor[]" placeholder="E-mail" disabled>
                                                </div>
                                                <div class="col-sm-5">
                                                    <label>Nome Completo</label>
                                                    <input type="text" style="margin-bottom:10px" value="{{Auth::user()->name}}" class="form-control emailCoautor" name="nomeCoautor[]" placeholder="Nome" disabled>
                                                </div>
                                                <div class="col-sm-3">
                                                    <a href="#" @if(!Auth::check()) class=" delete pr-2" @else onclick="return false;" class=" pr-2" @endif style="color: #d30909;">
                                                        <i class="fas fa-user-times fa-2x"></i>
                                                    </a>
                                                    <a href="#" onclick="mover(this.parentElement.parentElement.parentElement, 1, event)">
                                                        <i class="fas fa-arrow-up fa-2x" id="arrow-up" style=""></i>
                                                    </a>
                                                    <a href="#" onclick="mover(this.parentElement.parentElement.parentElement, 0, event)">
                                                        <i class="fas fa-arrow-down fa-2x" id="arrow-down" style="margin-top:35px"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <h4 class="col-sm-12" id="title-coautores" style="margin-top:20px">Coautor(es)</h4>
                                        @foreach (old('nomeCoautor') as $i => $nomeCoautor)
                                        <div class="item card " id="{{$i+1}}" style="width:100%;">
                                          <div class="row card-body">
                                              <div class="col-sm-4">
                                                  <label>E-mail</label>
                                                  <input type="email" style="margin-bottom:10px" id="email{{$i+1}}" value="{{old('emailCoautor')[$i]}}" onclick="digitarEmail(email{{$i+1}})" class="form-control emailCoautor" name="emailCoautor[]" placeholder="E-mail">
                                              </div>
                                              <div class="col-sm-5">
                                                  <label>Nome Completo</label>
                                                  <input type="text" style="margin-bottom:10px" value="{{$nomeCoautor}}" class="form-control emailCoautor" name="nomeCoautor[]" placeholder="Nome">
                                              </div>
                                              <div class="col-sm-3">
                                                  <a href="#" style="color: #d30909;" class="delete pr-2">
                                                    <i class="fas fa-user-times fa-2x"></i>
                                                  </a>
                                                  <a href="#" onclick="mover(this.parentElement.parentElement.parentElement, 1, event)">
                                                    <i class="fas fa-arrow-up fa-2x" id="arrow-up" style=""></i>
                                                  </a>
                                                  <a href="#" onclick="mover(this.parentElement.parentElement.parentElement, 0, event)">
                                                    <i class="fas fa-arrow-down fa-2x" id="arrow-down" style="margin-top:35px"></i>
                                                  </a>
                                              </div>
                                          </div>
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="item card" id="1" style="width:100%;">
                                            <div class="row card-body">
                                                <div class="col-sm-4">
                                                    <label>E-mail</label>
                                                    <input type="email" style="margin-bottom:10px" id="email${order}" value="{{Auth::user()->email}}" onclick="digitarEmail(email${order})" class="form-control emailCoautor" name="emailCoautor[]" placeholder="E-mail" disabled>
                                                </div>
                                                <div class="col-sm-5">
                                                    <label>Nome Completo</label>
                                                    <input type="text" style="margin-bottom:10px" value="{{Auth::user()->name}}" class="form-control emailCoautor" name="nomeCoautor[]" placeholder="Nome" disabled>
                                                </div>
                                                <div class="col-sm-3">
                                                    <a href="#" @if(!Auth::check()) class=" delete pr-2" @else onclick="return false;" class=" pr-2" @endif style="color: #d30909;">
                                                        <i class="fas fa-user-times fa-2x"></i>
                                                    </a>
                                                    <a href="#" onclick="return false;">
                                                        <i class="fas fa-arrow-up fa-2x" id="arrow-up" style=""></i>
                                                    </a>
                                                    <a href="#" onclick="return false;">
                                                        <i class="fas fa-arrow-down fa-2x" id="arrow-down" style="margin-top:35px"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                  </div>
                              </div>
                            </div>
                          @endif
                          @if ($modalidade->texto && $indice == "etiquetaresumotrabalho")
                            @if ($modalidade->caracteres == true)
                              <div class="row justify-content-center">
                                <div class="col-sm-12">
                                    <label for="resumo" class="col-form-label">{{$formSubTraba->etiquetaresumotrabalho}}</label>
                                    <textarea id="resumo" class="char-count form-control @error('resumo') is-invalid @enderror" data-ls-module="charCounter" minlength="{{$modalidade->mincaracteres}}" maxlength="{{$modalidade->maxcaracteres}}" name="resumo"  autocomplete="resumo" autofocusrows="5">{{old('resumo')}}</textarea>
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
                                    <textarea id="palavra" class="form-control palavra @error('resumo') is-invalid @enderror" name="resumo" value="{{ old('resumo') }}"  autocomplete="resumo" autofocusrows="5" required>{{old('resumo')}}</textarea>
                                    <p class="text-muted"><small><span id="numpalavra">0</span></small> - Min Palavras: <span id="minpalavras">{{$modalidade->minpalavras}}</span> - Max Palavras: <span id="maxpalavras">{{$modalidade->maxpalavras}}</span></p>
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
                                    <label for="area" class="col-form-label"><strong>{{$formSubTraba->etiquetaareatrabalho}}</strong> </label>
                                    <select class="form-control @error('areaId') is-invalid @enderror" id="area" name="areaId">
                                        <option value="" disabled selected hidden>-- {{ $formSubTraba->etiquetaareatrabalho }} --</option>
                                        {{-- Apenas um teste abaixo --}}
                                        @foreach($areas as $area)
                                          <option value="{{$area->id}}" @if(old('areaId') == $area->id) selected @endif>{{$area->nome}}</option>
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
                                  <label for="nomeTrabalho" class="col-form-label"><strong>{{$formSubTraba->etiquetauploadtrabalho}}</strong> </label>

                                  <div class="custom-file">
                                    <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="arquivo" required>
                                  </div>
                                  <small><strong>Extensão de arquivos aceitas:</strong>
                                    @if($modalidade->pdf == true)<span> / ".pdf"</span>@endif
                                    @if($modalidade->jpg == true)<span> / ".jpg"</span>@endif
                                    @if($modalidade->jpeg == true)<span> / ".jpeg"</span>@endif
                                    @if($modalidade->png == true)<span> / ".png"</span>@endif
                                    @if($modalidade->docx == true)<span> / ".docx"</span>@endif
                                    @if($modalidade->odt == true)<span> / ".odt"</span>@endif
                                    @if($modalidade->zip == true)<span> / ".zip"</span>@endif
                                    @if($modalidade->svg == true)<span> / ".svg"</span>@endif.
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
                            <button type="submit" class="btn btn-primary button-prevent-multiple-submits" style="width:100%">
                                <i class="spinner fa fa-spinner fa-spin" style="display: none;"></i> {{ __('Enviar') }}
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
        var myText = this.value.trim();
        var wordsArray = myText.split(/\s+/g);
        var words = wordsArray.length;
        var min = parseInt(($('#minpalavras').text()));
        var max = parseInt(($('#maxpalavras').text()));
        if(words < min || words > max) {
            this.setCustomValidity('Número de palavras não permitido. Você possui atualmente '+words+' palavras.');
        } else {
            this.setCustomValidity('');
        }

        $('#numpalavra').text(words);
    });
  });

  $(document).ready(function(){
    function ordenar(event){
      event.preventDefault();
      // console.log(event);
    }
  });

  let order = 1;

  function mover(div, direcao, event) {
    event.preventDefault();
    var coautores = document.getElementById("coautores");

    var hcoautores;
    if(coautores.children.length > 2) {
        hcoautores = coautores.children[1];
        coautores.children[1].remove();
    }
    if(direcao == 0) {
      for(var i = 1; i < coautores.children.length; i++) {
        if (coautores.children[i] == div && coautores.children[i+1] != null) {
          var baixo = coautores.children[i+1];
          var cima = coautores.children[i];
          coautores.children[i+1].remove();
          coautores.insertBefore(baixo, cima);
          break;
        }
      }
    } else if (direcao == 1) {
      for(var i = 2; i < coautores.children.length; i++) {
        if (coautores.children[i] == div && coautores.firstChild != div) {
          var baixo = coautores.children[i];
          var cima = coautores.children[i-1];
          coautores.children[i].remove();
          coautores.insertBefore(baixo, cima);
          break;
        }
      }
    }
    if(coautores.children.length >= 2){
        coautores.insertBefore(hcoautores, coautores.children[1]);
        console.log('');
    }
  }


  function addLinha(event){
    event.preventDefault();
    order += 1;
    if ($("#title-coautores").length == 0) {
        $('#coautores').append('<h4 class="col-sm-12" id="title-coautores" style="margin-top:20px">Coautor(es)</h4>');
    }
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
  $(document).on('click','.delete', function(e){
    $(this).closest('.item').remove();
    e.preventDefault();
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

    return `<div class="item card" id="${order}" style="width:100%;">
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
                      <a href="#" style="color: #d30909;" class="delete pr-2"><i class="fas fa-user-times fa-2x"></i></a>
                      <a href="#" onclick="mover(this.parentElement.parentElement.parentElement, 1, event)"><i class="fas fa-arrow-up fa-2x" id="arrow-up" style=""></i></a>
                      <a href="#" onclick="mover(this.parentElement.parentElement.parentElement, 0, event)"><i class="fas fa-arrow-down fa-2x" id="arrow-down" style="margin-top:35px"></i></a>
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
          type: 'GET',
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
