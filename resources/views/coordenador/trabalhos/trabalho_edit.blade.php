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
            @if($errors->any())
                <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p>{{$error}}</p>
                @endforeach
                </div>
            @endif
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
                    <div class="row">
                        <div class="col">
                            <label><b>{{$evento->formSubTrab->etiquetaautortrabalho}}</b></label>
                        </div>
                    </div>
                        <div id="coautores{{$trabalho->id}}" class="flexContainer " >
                          @if (old('nomeCoautor_'.$trabalho->id) != null)
                            @foreach (old('nomeCoautor_'.$trabalho->id) as $i => $nomeCoautor)
                                @if($i == 1)
                                    <label id="title-coautores{{$trabalho->id}}" style="margin-top:20px"><b>{{$evento->formSubTrab->etiquetacoautortrabalho}}</b></label>
                                @endif
                                <div class="item card mt-0">
                                    <div class="row card-body">
                                        <div class="col-sm-4">
                                            <label>E-mail</label>
                                            <input type="email" style="margin-bottom:10px" value="{{old('emailCoautor_'.$trabalho->id)[$i]}}" class="form-control emailCoautor" name="emailCoautor_{{$trabalho->id}}[]" required placeholder="E-mail">
                                        </div>
                                        <div class="col-sm-5">
                                            <label>Nome Completo</label>
                                            <input type="text" style="margin-bottom:10px" value="{{$nomeCoautor}}" class="form-control emailCoautor" name="nomeCoautor_{{$trabalho->id}}[]" required placeholder="Nome">
                                        </div>
                                        <div class="col-sm-3">
                                            <a style="color: #d30909;" href="#" onclick="deletarCoautor(this, {{$trabalho->id}}, event)" class="delete pr-2">
                                                <img src="{{asset('img/icons/trash-alt-regular.svg')}}" class="icon-card" width="24" alt="Remover">
                                            </a>
                                            <a href="#" onclick="mover(this.parentElement.parentElement.parentElement, 1, {{$trabalho->id}}, event)">
                                                <img src="{{asset('img/icons/sobe.png')}}" class="icon-card" width="24" alt="Subir">
                                            </a>
                                            <a href="#" onclick="mover(this.parentElement.parentElement.parentElement, 0, {{$trabalho->id}}, event)">
                                                <img src="{{asset('img/icons/desce.png')}}" class="icon-card" width="24" alt="Descer">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                          @else
                            <div class="item card mt-0">
                                <div class="row card-body">
                                    <div class="col-sm-4">
                                        <label>E-mail</label>
                                        <input type="email" style="margin-bottom:10px" value="{{$trabalho->autor->email}}" oninput="buscarEmail(this)" class="form-control emailCoautor" name="emailCoautor_{{$trabalho->id}}[]" placeholder="E-mail" required>
                                    </div>
                                    <div class="col-sm-5">
                                        <label>Nome Completo</label>
                                        <input type="text" style="margin-bottom:10px" value="{{$trabalho->autor->name}}" class="form-control emailCoautor" name="nomeCoautor_{{$trabalho->id}}[]" placeholder="Nome" required>
                                    </div>
                                    <div class="col-sm-3 d-flex align-items-center gap-3">
                                        <a style="color: #d30909;" href="#" onclick="deletarCoautor(this, {{$trabalho->id}}, event)" class="delete text-decoration-none pr-2">
                                            <i class="bi bi-trash3 fs-4 icon-card"></i>
                                        </a>
                                        <a href="#" class="text-decoration-none text-success" onclick="mover(this.parentElement.parentElement.parentElement, 1, {{$trabalho->id}}, event)">
                                            <i class="bi bi-arrow-up-circle fs-4"></i>
                                        </a>
                                        <a href="#" class="text-decoration-none text-success" onclick="mover(this.parentElement.parentElement.parentElement, 0, {{$trabalho->id}}, event)">
                                            <i class="bi bi-arrow-down-circle fs-4"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @if(! $trabalho->coautors->isEmpty())
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <label id="title-coautores{{$trabalho->id}}"><b>{{$evento->formSubTrab->etiquetacoautortrabalho}}</b></label>
                                    <div>
                                        <a href="#" style="color: #196572ff;text-decoration: none;" title="Clique aqui para adicionar {{$evento->formSubTrab->etiquetacoautortrabalho}}, se houver" onclick="montarLinhaInput(this, {{$trabalho->id}}, event)" id="addCoautor_{{$trabalho->id}}">
                                            <i class="bi bi-plus-circle fs-4"></i>
                                        </a>
                                    </div>
                                </div>
                            @endif
                            @foreach ($trabalho->coautors as $i => $coautor)
                                <div class="item card mt-0">
                                    <div class="row card-body">
                                        <div class="col-sm-4">
                                            <label>E-mail</label>
                                            <input type="email" style="margin-bottom:10px" value="{{$coautor->user->email}}" oninput="buscarEmail(this)" class="form-control emailCoautor" name="emailCoautor_{{$trabalho->id}}[]" placeholder="E-mail" required>
                                        </div>
                                        <div class="col-sm-5">
                                            <label>Nome Completo</label>
                                            <input type="text" style="margin-bottom:10px" value="{{$coautor->user->name}}" class="form-control emailCoautor" name="nomeCoautor_{{$trabalho->id}}[]" placeholder="Nome" required>
                                        </div>
                                        <div class="col-sm-3 d-flex align-items-center gap-3">
                                            <a h style="color: #d30909;" class="text-decoration-none" href="#" onclick="deletarCoautor(this, {{$trabalho->id}}, event)" class="delete pr-2">
                                                <i class="bi bi-trash3 fs-4 icon-card"></i>
                                            </a>
                                            <a href="#" class="text-decoration-none text-success" onclick="mover(this.parentElement.parentElement.parentElement, 1, {{$trabalho->id}}, event)">
                                                <i class="bi bi-arrow-up-circle fs-4"></i>
                                            </a>
                                            <a href="#" class="text-decoration-none text-success" onclick="mover(this.parentElement.parentElement.parentElement, 0, {{$trabalho->id}}, event)">
                                                <i class="bi bi-arrow-down-circle fs-4"></i>
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
  function montarLinhaInput(div, id, event){
    var coautores = document.getElementById("coautores"+id);
    var html = "";
    if (coautores.children.length==1){
        html = `<label id="title-coautores{{$trabalho->id}}" style="margin-top:20px"><b>${@json($evento->formSubTrab->etiquetacoautortrabalho)}</b></label>`;
    }
    event.preventDefault();
    html += `
    <div class="item card mt-0">
        <div class="row card-body">
            <div class="col-sm-4">
                <label>E-mail</label>
                <input type="email" style="margin-bottom:10px" class="form-control emailCoautor" name="emailCoautor_{{$trabalho->id}}[]" required placeholder="E-mail">
            </div>
            <div class="col-sm-5">
                <label>Nome Completo</label>
                <input type="text" style="margin-bottom:10px" class="form-control emailCoautor" name="nomeCoautor_{{$trabalho->id}}[]" required placeholder="Nome">
            </div>
            <div class="col-sm-3 d-flex align-items-center gap-3">
                <a style="color: #d30909;" href="#" onclick="deletarCoautor(this, {{$trabalho->id}}, event)" class="delete text-decoration-none pr-2">
                    <i class="bi bi-trash3 fs-4 icon-card"></i>
                </a>
                <a href="#" class="text-decoration-none text-success" onclick="mover(this.parentElement.parentElement.parentElement, 1, {{$trabalho->id}}, event)">
                    <i class="bi bi-arrow-up-circle fs-4"></i>
                </a>
                <a href="#" class="text-decoration-none text-success" onclick="mover(this.parentElement.parentElement.parentElement, 0, {{$trabalho->id}}, event)">
                    <i class="bi bi-arrow-down-circle fs-4"></i>
                </a>
            </div>
        </div>
    </div>
    `;

    $('#coautores'+id).append(html);
  }

  function deletarCoautor(div, id, event) {
    event.preventDefault();
    var titulo = document.getElementById("title-coautores"+id);
    var coautores = document.getElementById("coautores"+id);
    $('#title-coautores'+id).remove();
    div.parentElement.parentElement.parentElement.remove();
    if(coautores.children.length >= 2) {
        coautores.insertBefore(titulo, coautores.children[1]);
    }
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


  function mover(div, direcao, id, event) {
    event.preventDefault();
    var coautores = document.getElementById("coautores"+id);

    var hcoautores;
    if(coautores.children.length > 2) {
        hcoautores = coautores.children[1];
        coautores.children[1].remove();
    }
    if(direcao == 0) {
      for(var i = 0; i < coautores.children.length; i++) {
        if (coautores.children[i] == div && coautores.children[i+1] != null) {
          var baixo = coautores.children[i+1];
          var cima = coautores.children[i];
          coautores.children[i+1].remove();
          coautores.insertBefore(baixo, cima);
          break;
        }
      }
    } else if (direcao == 1) {
      for(var i = 0; i < coautores.children.length; i++) {
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
