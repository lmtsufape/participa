@extends('layouts.app')

@section('content')

<div class="container position-relative">

    {{-- titulo da página --}}
    <div class="row justify-content-center titulo">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-10">
                    <h1>Editar Trabalho</h1>
                </div>

            </div>
        </div>

    </div>
    @if(session('mensagem'))
        <div class="row">
            <div class="col-md-12" style="margin-top: 5px;">
                <div class="alert alert-success">
                    <p>{{session('mensagem')}}</p>
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="modal-body">
            <form id="formEditarTrab{{$trabalho->id}}" action="{{route('editar.trabalho', ['id' => $trabalho->id])}}" method="POST" enctype="multipart/form-data">
              @csrf

              @php
                $formSubTraba = $trabalho->evento->formSubTrab;
                $ordem = explode(",", $formSubTraba->ordemCampos);
                array_splice($ordem, 6, 0, "midiaExtra");
                array_splice($ordem, 5, 0, "apresentacao");
                $modalidade = $trabalho->modalidade;
                $areas = $trabalho->evento->areas;
              @endphp
              <input type="hidden" name="trabalhoEditId" value="{{$trabalho->id}}">
              @error('numeroMax'.$trabalho->id)
                <div class="row">
                  <div class="col-md-12">
                    <div class="alert alert-danger" role="alert">
                      {{ $message }}
                    </div>
                  </div>
                </div>
              @enderror
              @foreach ($ordem as $indice)
                @if ($indice == "etiquetatitulotrabalho")
                  <div class="row justify-content-center">
                    {{-- Nome Trabalho  --}}
                    <div class="col-sm-12">
                        <label for="nomeTrabalho_{{$trabalho->id}}" class="col-form-label">{{ $formSubTraba->etiquetatitulotrabalho }}</label>
                        <input id="nomeTrabalho_{{$trabalho->id}}" type="text" class="form-control @error('nomeTrabalho'.$trabalho->id) is-invalid @enderror" name="nomeTrabalho{{$trabalho->id}}" value="{{old('nomeTrabalho'.$trabalho->id, $trabalho->titulo)}}" autocomplete="nomeTrabalho" autofocus>

                        @error('nomeTrabalho'.$trabalho->id)
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                  </div>
                @endif
                @if ($indice == "etiquetacoautortrabalho")
                  <div class="flexContainer" style="margin-top:20px">
                    <label><b>{{$evento->formSubTrab->etiquetaautortrabalho}}</b></label>

                    {{-- 1. AUTOR PRINCIPAL (Posição 0 fixa) --}}
                    <div class="item card mt-1">
                      <div class="row card-body p-2 align-items-center">
                        <div class="col-sm-5">
                          <label class="small mb-1">E-mail do Autor</label>
                          <input type="email" value="{{$trabalho->autor->email}}" class="form-control form-control-sm emailCoautor" name="emailCoautor_{{$trabalho->id}}[]" placeholder="E-mail" required>
                        </div>
                        <div class="col-sm-5">
                          <label class="small mb-1">Nome do Autor</label>
                          <input type="text" value="{{$trabalho->autor->name}}" class="form-control form-control-sm" name="nomeCoautor_{{$trabalho->id}}[]" placeholder="Nome" required>
                        </div>
                        <div class="col-sm-2 text-center mt-3">
                          <span class="badge bg-primary">Autor(a)</span>
                        </div>
                      </div>
                    </div>

                    {{-- CABEÇALHO DA SEÇÃO DE COAUTORES --}}
                    <div class="d-flex justify-content-between align-items-center mt-3 mb-1">
                      <label class="mb-0"><b>{{$evento->formSubTrab->etiquetacoautortrabalho}}</b></label>
                      <a href="#" style="color: #196572ff; text-decoration: none;" title="Adicionar coautor" onclick="montarLinhaInput(this, {{$trabalho->id}}, event)" id="addCoautor_{{$trabalho->id}}">
                        <i class="bi bi-plus-circle fs-4 align-middle"></i> <span class="align-middle">Adicionar</span>
                      </a>
                    </div>

                    {{-- 2. LISTA EXCLUSIVA PARA OS COAUTORES (Apenas cards filhos aqui dentro) --}}
                    <div id="lista-coautores-{{$trabalho->id}}" class="flexContainer">
                      @if (old('nomeCoautor_'.$trabalho->id) != null)
                        @foreach (old('nomeCoautor_'.$trabalho->id) as $i => $nomeCoautor)
                          @if ($i > 0)
                            <div class="item card mt-2">
                              <div class="row card-body p-2 align-items-center">
                                <div class="col-sm-5">
                                  <label class="small mb-1">E-mail (Opcional)</label>
                                  <input type="email" value="{{old('emailCoautor_'.$trabalho->id)[$i]}}" class="form-control form-control-sm emailCoautor" name="emailCoautor_{{$trabalho->id}}[]" placeholder="E-mail (opcional)" oninput="buscarEmail(this)">
                                </div>
                                <div class="col-sm-5">
                                  <label class="small mb-1">Nome Completo</label>
                                  <input type="text" value="{{$nomeCoautor}}" class="form-control form-control-sm" name="nomeCoautor_{{$trabalho->id}}[]" required placeholder="Nome">
                                </div>
                                <div class="col-sm-2 d-flex align-items-center justify-content-around mt-3">
                                  <a style="color: #d30909;" href="#" onclick="deletarCoautor(this, event)" class="delete text-decoration-none" title="Remover">
                                    <i class="bi bi-trash3 fs-5 icon-card"></i>
                                  </a>
                                  <a href="#" class="text-decoration-none text-success" onclick="mover(this, 1, event)" title="Subir">
                                    <i class="bi bi-arrow-up-circle fs-5"></i>
                                  </a>
                                  <a href="#" class="text-decoration-none text-success" onclick="mover(this, 0, event)" title="Descer">
                                    <i class="bi bi-arrow-down-circle fs-5"></i>
                                  </a>
                                </div>
                              </div>
                            </div>
                          @endif
                        @endforeach
                      @else
                        @foreach ($trabalho->coautors->sortBy('ordem') as $coautor)
                          <div class="item card mt-2">
                            <div class="row card-body p-2 align-items-center">
                              <div class="col-sm-5">
                                <label class="small mb-1">E-mail (Opcional)</label>
                                <input type="email" class="form-control form-control-sm emailCoautor" 
                                      name="emailCoautor_{{$trabalho->id}}[]" 
                                      value="{{ str_contains($coautor->user->email, '@participa.local') ? '' : $coautor->user->email }}" 
                                      placeholder="E-mail (opcional)" oninput="buscarEmail(this)">
                              </div>
                              <div class="col-sm-5">
                                <label class="small mb-1">Nome Completo</label>
                                <input type="text" class="form-control form-control-sm" 
                                      name="nomeCoautor_{{$trabalho->id}}[]" 
                                      value="{{$coautor->user->name}}" placeholder="Nome completo" required>
                              </div>
                              <div class="col-sm-2 d-flex align-items-center justify-content-around mt-3">
                                <a style="color: #d30909;" href="#" onclick="deletarCoautor(this, event)" class="delete text-decoration-none" title="Remover">
                                  <i class="bi bi-trash3 fs-5 icon-card"></i>
                                </a>
                                <a href="#" class="text-decoration-none text-success" onclick="mover(this, 1, event)" title="Subir">
                                  <i class="bi bi-arrow-up-circle fs-5"></i>
                                </a>
                                <a href="#" class="text-decoration-none text-success" onclick="mover(this, 0, event)" title="Descer">
                                  <i class="bi bi-arrow-down-circle fs-5"></i>
                                </a>
                              </div>
                            </div>
                          </div>
                        @endforeach
                      @endif
                    </div>
                  </div>
                @endif
                @if ($modalidade->texto && $indice == "etiquetaresumotrabalho")
                  @if ($modalidade->caracteres == true)
                    <div class="row justify-content-center">
                      <div class="col-sm-12">
                          <label for="resumo_{{$trabalho->id}}" class="col-form-label">{{$formSubTraba->etiquetaresumotrabalho}}</label>
                          <textarea id="resumo_{{$trabalho->id}}" class="char-count form-control @error('resumo'.$trabalho->id) is-invalid @enderror" data-ls-module="charCounter" minlength="{{$modalidade->mincaracteres}}" maxlength="{{$modalidade->maxcaracteres}}" name="resumo{{$trabalho->id}}"  autocomplete="resumo" autofocusrows="5">{{old('resumo'.$trabalho->id, $trabalho->resumo)}}</textarea>
                          <p class="text-muted"><small><span id="resumo{{$trabalho->id}}">{{strlen($trabalho->resumo)}}</span></small> - Min Caracteres: {{$modalidade->mincaracteres}} - Max Caracteres: {{$modalidade->maxcaracteres}}</p>
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
                          <textarea id="resumo_{{$trabalho->id}}" class="form-control palavra @error('resumo'.$trabalho->id) is-invalid @enderror" name="resumo{{$trabalho->id}}" required autocomplete="resumo" autofocusrows="5">{{old('resumo'.$trabalho->id, $trabalho->resumo)}}</textarea>
                          <p class="text-muted"><small><span id="resumo{{$trabalho->id}}">{{count(explode(" ", $trabalho->resumo))}}</span></small> - Min Palavras: <span id="minpalavras">{{$modalidade->minpalavras}}</span> - Max Palavras: <span id="maxpalavras">{{$modalidade->maxpalavras}}</span></p>
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
                          <label for="area_{{$trabalho->id}}" class="col-form-label"><b>{{$formSubTraba->etiquetaareatrabalho}}</b></label>
                          <select id="area_{{$trabalho->id}}" class="form-control @error('area'.$trabalho->id) is-invalid @enderror" name="area{{$trabalho->id}}" required>
                              <option value="" disabled selected hidden>-- Área --</option>
                              {{-- Apenas um teste abaixo --}}
                              @if (old('area'.$trabalho->id) != null)
                                @foreach($areas as $area)
                                  <option value="{{$area->id}}" @if(old('area'.$trabalho->id) == $area->id) selected @endif>{{$area->nome}}</option>
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
                  <!-- Modalidades -->
                <div class="row justify-content-center">
                    <div class="col-sm-12">
                        <label for="modalidade_{{$trabalho->id}}" class="col-form-label"><b>Modalidade</b></label>
                        <select id="modalidade_{{$trabalho->id}}" class="form-control @error('modalidadeError'.$trabalho->id) is-invalid @enderror" name="modalidade{{$trabalho->id}}" required>
                            <option value="" disabled selected hidden>-- Modalidade --</option>
                            @if (old('modalidade'.$trabalho->id) != null)
                              @foreach($modalidades as $modalidade_for)
                                <option value="{{$modalidade_for->id}}" @if(old('modalidade'.$trabalho->id) == $modalidade_for->id) selected @endif>{{$modalidade_for->nome}}</option>
                              @endforeach
                            @else
                              @foreach($modalidades as $modalidade_for)
                                <option value="{{$modalidade_for->id}}" @if($trabalho->modalidadeId == $modalidade_for->id) selected @endif>{{$modalidade_for->nome}}</option>
                              @endforeach
                            @endif

                        </select>
                        @error('modalidadeError'.$trabalho->id)
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
                        <label for="nomeTrabalho" class="col-form-label"><b>{{$formSubTraba->etiquetauploadtrabalho}}:</b></label>
                          <a href="{{route('downloadTrabalho', ['id' => $trabalho->id])}}">Arquivo atual</a>
                        <br>
                        <small>Para trocar o arquivo envie um novo.</small>
                        <div class="custom-file">
                          <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="arquivo{{$trabalho->id}}">
                        </div>
                        <small>Extensão de arquivos aceitas:
                          @if($trabalho->modalidade->pdf == true)<span> - pdf</span>@endif
                          @if($trabalho->modalidade->jpg == true)<span> - jpg</span>@endif
                          @if($trabalho->modalidade->jpeg == true)<span> - jpeg</span>@endif
                          @if($trabalho->modalidade->png == true)<span> - png</span>@endif
                          @if($trabalho->modalidade->docx == true)<span> - docx</span>@endif
                          @if($trabalho->modalidade->odt == true)<span> - odt</span>@endif
                          @if($trabalho->modalidade->zip == true)<span> - zip</span>@endif
                          @if($trabalho->modalidade->svg == true)<span> - svg</span>@endif
                          @if($trabalho->modalidade->mp4 == true)<span> - mp4</span>@endif
                          @if($trabalho->modalidade->mp3 == true)<span> - mp3</span>@endif.
                        </small>
                        @error('arquivo'.$trabalho->id)
                          <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                      </div>
                    @endif
                  </div>
                @endif
                @if ($indice == "apresentacao")
                    @if ($trabalho->modalidade->apresentacao)
                        <div class="row justify-content-center mt-4">
                            <div class="col-sm-12">
                                <label for="area"
                                    class="col-form-label"><strong>{{ __('Forma de apresentação do trabalho') }}</strong>
                                </label>
                                <select name="tipo_apresentacao" id="tipo_apresentacao" class="form-control @error('tipo_apresentacao') is-invalid @enderror" required>
                                    <option value="" selected disabled>{{__('-- Selecione a forma de apresentação do trabalho --')}}</option>
                                    @foreach ($trabalho->modalidade->tiposApresentacao as $tipo)
                                    <option @if(old('tipo_apresentacao') == $tipo->tipo || $trabalho->tipo_apresentacao == $tipo->tipo) selected @endif value="{{$tipo->tipo}}">{{__($tipo->tipo)}}</option>
                                    @endforeach
                                </select>

                                @error('tipo_apresentacao')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    @endif
                @endif
                @if ($indice == "midiaExtra")
                    <div class="row justify-content-center">
                        @foreach ($modalidade->midiasExtra as $midia)
                            <div class="col-sm-12" style="margin-top: 20px;">
                                <label for="{{$midia->hyphenizeNome()}}"
                                    class="col-form-label"><strong>{{$midia->nome}}</strong>
                                </label>
                                <a href="{{route('downloadMidiaExtra', ['id' => $trabalho->id, 'id_midia' => $midia->id])}}">Arquivo atual</a>
                                <small>Para trocar o arquivo envie um novo.</small>
                                <div class="custom-file">
                                    <input type="file" class="filestyle"
                                        data-placeholder="Nenhum arquivo" data-text="Selecionar"
                                        data-btnClass="btn-primary-lmts" name="{{$midia->hyphenizeNome()}}">
                                </div>
                                <small><strong>Extensão de arquivos aceitas:</strong>
                                    @if($midia->pdf == true)
                                        <span> / ".pdf"</span>
                                    @endif
                                    @if($midia->jpg == true)
                                        <span> / ".jpg"</span>
                                    @endif
                                    @if($midia->jpeg == true)
                                        <span> / ".jpeg"</span>
                                    @endif
                                    @if($midia->png == true)
                                        <span> / ".png"</span>
                                    @endif
                                    @if($midia->docx == true)
                                        <span> / ".docx"</span>
                                    @endif
                                    @if($midia->odt == true)
                                        <span> / ".odt"</span>
                                    @endif
                                    @if($midia->zip == true)
                                        <span> / ".zip"</span>
                                    @endif
                                    @if($midia->svg == true)
                                        <span> / ".svg"</span>
                                    @endif
                                    @if($midia->mp4 == true)
                                        <span> / ".mp4"</span>
                                    @endif
                                    @if($midia->mp3 == true)
                                        <span> / ".mp3"</span>
                                    @endif. </small>
                                @error($midia->nome)
                                <span class="invalid-feedback" role="alert"
                                    style="overflow: visible; display:block">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                @endif
                @if ($indice == "etiquetacampoextra1")
                  @if ($formSubTraba->checkcampoextra1 == true)
                    @if ($formSubTraba->tipocampoextra1 == "textosimples")
                      {{-- Texto Simples --}}
                      <div class="row justify-content-center">
                        {{-- Nome Trabalho  --}}
                        <div class="col-sm-12">
                              <label for="campoextra1simples_{{$trabalho->id}}" class="col-form-label">{{ $formSubTraba->etiquetacampoextra1}}:</label>
                              <input id="campoextra1simples_{{$trabalho->id}}" type="text" class="form-control @error('campoextra1simples') is-invalid @enderror" name="campoextra1simples" value="{{ old('campoextra1simples') }}" required autocomplete="campoextra1simples" autofocus>

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
            {{-- <a href="{{route('downloadTrabalho', ['id' => $trabalho->id])}}" target="_new" class="m-2" style="font-size: 20px; color: #114048ff;" >
                <img class="" src="{{asset('img/icons/file-download-solid.svg')}}" style="width:20px">
            </a> --}}
                <br>
                <button type="submit" class="btn btn-primary mr-4" form="formEditarTrab{{$trabalho->id}}">Salvar</button>
                <button type="button" class="btn btn-secondary" onclick="window.location='{{ route('coord.listarTrabalhos', ['eventoId' => $evento->id]) }}'">Cancelar</button>
            </form>
          </div>
    </div>

</div>

@endsection
@section('javascript')
@if(old('trabalhoEditId'))
  <script>
    $(document).ready(function() {
      $('#modalEditarTrabalho_{{old('trabalhoEditId')}}').modal('show');
    })
  </script>
@endif

<script>
  function montarLinhaInput(btn, id, event) {
    if (event) event.preventDefault();
    var coautores = document.getElementById("lista-coautores-" + id);
    if (!coautores) return;

    var html = `
    <div class="item card mt-2">
        <div class="row card-body p-2 align-items-center">
            <div class="col-sm-5">
                <label class="small mb-1">E-mail (Opcional)</label>
                <input type="email" class="form-control form-control-sm emailCoautor" name="emailCoautor_${id}[]" placeholder="E-mail (opcional)" oninput="buscarEmail(this)">
            </div>
            <div class="col-sm-5">
                <label class="small mb-1">Nome Completo</label>
                <input type="text" class="form-control form-control-sm" name="nomeCoautor_${id}[]" required placeholder="Nome completo">
            </div>
            <div class="col-sm-2 d-flex align-items-center justify-content-around mt-3">
                <a style="color: #d30909;" href="#" onclick="deletarCoautor(this, event)" class="delete text-decoration-none" title="Remover">
                    <i class="bi bi-trash3 fs-5 icon-card"></i>
                </a>
                <a href="#" class="text-decoration-none text-success" onclick="mover(this, 1, event)" title="Subir">
                    <i class="bi bi-arrow-up-circle fs-5"></i>
                </a>
                <a href="#" class="text-decoration-none text-success" onclick="mover(this, 0, event)" title="Descer">
                    <i class="bi bi-arrow-down-circle fs-5"></i>
                </a>
            </div>
        </div>
    </div>
    `;
    coautores.insertAdjacentHTML('beforeend', html);
  }

  function deletarCoautor(btn, event) {
    if (event) event.preventDefault();
    var card = btn.closest('.item');
    if (card) card.remove();
  }

  $(document).ready(function(){
    $('.char-count').keyup(function() {

        var maxLength = parseInt($(this).attr('maxlength'));
        var length = $(this).val().length;
        // var newLength = maxLength-length;

        var name = $(this).attr("name");
        $('#'+name).text(length);
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
        var name = $(this).attr("name");
        $('#'+name).text(words);
    });
  });


  function mover(btn, direcao, event) {
    if (event) event.preventDefault();
    var card = btn.closest('.item');
    if (!card) return;
    var container = card.parentElement;

    if (direcao === 1) { // Subir
      var anterior = card.previousElementSibling;
      if (anterior) {
        container.insertBefore(card, anterior);
      }
    } else if (direcao === 0) { // Descer
      var proximo = card.nextElementSibling;
      if (proximo) {
        container.insertBefore(proximo, card);
      }
    }
  }

  function buscarEmail(input) {
    var emailBuscado = input.value;
    var inputName = input.parentElement.parentElement.children[1].children[1];

    let data = {email: emailBuscado,};

    if (!(emailBuscado=="" || emailBuscado.indexOf('@')==-1 || emailBuscado.indexOf('.')==-1)) {
      $.ajax({
        type: 'GET',
        url: '{{ route("search.user") }}',
        data: data,
        dataType: 'json',
        success: function(res) {
          if(res.user[0] != null) {
            inputName.value = res.user[0]['name'];
          }
        },
        error: function(err){
            // console.log('err')
            // console.log(err)
        }
      });
    }
  }

</script>

@endsection
