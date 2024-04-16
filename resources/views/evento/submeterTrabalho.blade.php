@extends('layouts.app')

@section('content')
    <div class="container content">
        <div class="row justify-content-center" x-data="handler()">
            <div class="col-sm-10" style="padding-right: 0px;">
                <div class="card" style="margin-top:50px;">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @if($evento->is_multilingual && Session::get('idiomaAtual') === 'en')
                            <h2 class="card-title">{{$evento->nome_en}}</h2>
                        @else
                            <h2 class="card-title">{{$evento->nome}}</h2>
                        @endif
                        <h4 class="card-title">{{__('Modalidade')}}: {{$modalidade->nome}}</h4>
                        <div class="titulo-detalhes"></div>
                        <br>
                        <h4 class="card-title">{{__('Enviar Trabalho')}}</h4>
                        <p class="card-text">
                            <form method="POST" action="{{route('trabalho.store', $modalidade->id)}}"
                                enctype="multipart/form-data" class="form-prevent-multiple-submits">
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
                                <div>
                                    @error('emailCoautor.*')
                                        @include('componentes.mensagens')
                                    @enderror
                                </div>
                                @foreach ($ordemCampos as $indice)
                                    @if ($indice == "etiquetatitulotrabalho")
                                        @if($evento->is_multilingual && Session::get('idiomaAtual') === 'en')


                                        <div class="row justify-content-center">
                                            {{-- Nome Trabalho Ingles  --}}
                                            <div class="col-sm-12">
                                                <label for="nomeTrabalho_en"
                                                       class="col-form-label"><strong>{{ $formSubTraba->etiquetatitulotrabalho_en }}</strong>
                                                </label>
                                                <input id="nomeTrabalho_en" type="text"
                                                       class="form-control @error('nomeTrabalho_en') is-invalid @enderror"
                                                       name="nomeTrabalho_en" value="{{ old('nomeTrabalho_en') }}"
                                                       autocomplete="nomeTrabalho_en" autofocus>
                                                @error('nomeTrabalho_en')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                        @else

                                        <div class="row justify-content-center">
                                            {{-- Nome Trabalho  --}}
                                            <div class="col-sm-12">
                                                <label for="nomeTrabalho"
                                                    class="col-form-label"><strong>{{ $formSubTraba->etiquetatitulotrabalho }}</strong>
                                                </label>
                                                <input id="nomeTrabalho" type="text"
                                                    class="form-control @error('nomeTrabalho') is-invalid @enderror"
                                                    name="nomeTrabalho" value="{{ old('nomeTrabalho') }}"
                                                    autocomplete="nomeTrabalho" autofocus>
                                                @error('nomeTrabalho')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                         @endif
                                    @endif
{{--                                    @if ($indice == "etiquetaautortrabalho")--}}
                                        {{-- <div class="row justify-content-center">
                                        Autor
                                        <div class="col-sm-12">
                                            <label for="nomeTrabalho" class="col-form-label">{{$formSubTraba->etiquetaautortrabalho}}</label>
                                            <input class="form-control" type="text" disabled value="{{Auth::user()->name}}">
                                        </div>
                                        </div> --}}
{{--                                    @endif--}}
                                    @if ($indice == "etiquetacoautortrabalho")
                                        <div style="margin-top:20px">
                                            <div class="row align-items-center justify-content-between"
                                                style="flex-wrap: unset">
                                                <div class="col-md-12">
                                                    <label><b>{{$evento->formSubTrab->etiquetaautortrabalho}}</b></label>
                                                </div>
                                            </div>
                                            <div id="coautores" class="mb-2">
                                                <template x-for="(autor, index) in autores" :key="index">
                                                    <div class="row">
                                                        <template x-if="index == 1">
                                                            <label class="col-sm-12" id="title-coautores"
                                                                style="margin-top:20px">
                                                                <b>{{$evento->formSubTrab->etiquetacoautortrabalho}}</b>
                                                            </label>
                                                        </template>
                                                        <div class="item card w-100 mt-0">
                                                            <div class="row card-body">
                                                                <div :class="index == 0 ? 'col-md-6' : 'col-md-4 col-lg-4'">
                                                                    <label :for="'email' + index">E-mail</label>
                                                                    <input type="email" style="margin-bottom:10px"
                                                                        class="form-control emailCoautor"
                                                                        name="emailCoautor[]" placeholder="E-mail"
                                                                        :id="'email' + index"
                                                                        x-init="$nextTick(() => centralizarTela(index))"
                                                                        x-on:focusout="checarNome(index)"
                                                                        x-model="autor.email"
                                                                        :readonly="@can('isCoordenadorOrComissaoCientifica', $evento) undefined @else index == 0 @endcan">
                                                                </div>
                                                                <div :class="index == 0 ? 'col-md-6' : 'col-md-4 col-lg-5'">
                                                                    <label :for="'nome' + index">Nome Completo</label>
                                                                    <input type="text" style="margin-bottom:10px"
                                                                        class="form-control emailCoautor"
                                                                        name="nomeCoautor[]" placeholder="Nome"
                                                                        :id="'nome' + index"
                                                                        x-model="autor.nome"
                                                                        :readonly="@can('isCoordenadorOrComissaoCientifica', $evento) undefined @else index == 0 @endcan">
                                                                </div>
                                                                <template x-if="index > 0">
                                                                    <div class="col-md-4 col-lg-3 justify-content-center d-flex align-items-end btn-group pb-1">
                                                                        <button type="button" @click="removeAutor(index)" style="color: #d30909;" class="btn"><i class="fas fa-user-times fa-2x"></i></button>
                                                                        <button type="button" @click="sobeAutor(index)" class="btn btn-link"><i class="fas fa-arrow-up fa-2x"></i></button>
                                                                        <button type="button" @click="desceAutor(index)" class="btn btn-link"><i class="fas fa-arrow-down fa-2x"></i></button>
                                                                    </div>
                                                                </template>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($modalidade->texto && $indice == "etiquetaresumotrabalho")

                                        @if ($modalidade->caracteres == true)
                                                @if($evento->is_multilingual && Session::get('idiomaAtual') === 'en')
                                                    <div class="row justify-content-center">
                                                        <div class="col-sm-12">
                                                            <label for="resumo_en"
                                                                   class="col-form-label">{{$formSubTraba->etiquetaresumotrabalho_en}}</label>
                                                            <textarea id="resumo_en"
                                                                      class="char-count form-control @error('resumo_en') is-invalid @enderror"
                                                                      data-ls-module="charCounter"
                                                                      minlength="{{$modalidade->mincaracteres}}"
                                                                      maxlength="{{$modalidade->maxcaracteres}}" name="resumo_en"
                                                                      autocomplete="resumo"
                                                                      autofocusrows="5">{{old('resumo_en')}}</textarea>
                                                            <p class="text-muted"><small><span name="resumo">0</span></small> - Min
                                                                Caracteres: {{$modalidade->mincaracteres}} - Max
                                                                Caracteres: {{$modalidade->maxcaracteres}}</p>
                                                            @error('resumo_en')
                                                            <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                @else
                                                    <div class="row justify-content-center">
                                                    <div class="col-sm-12">
                                                        <label for="resumo"
                                                               class="col-form-label">{{$formSubTraba->etiquetaresumotrabalho}}</label>
                                                        <textarea id="resumo"
                                                                  class="char-count form-control @error('resumo') is-invalid @enderror"
                                                                  data-ls-module="charCounter"
                                                                  minlength="{{$modalidade->mincaracteres}}"
                                                                  maxlength="{{$modalidade->maxcaracteres}}" name="resumo"
                                                                  autocomplete="resumo"
                                                                  autofocusrows="5">{{old('resumo')}}</textarea>
                                                        <p class="text-muted"><small><span name="resumo">0</span></small> - Min
                                                            Caracteres: {{$modalidade->mincaracteres}} - Max
                                                            Caracteres: {{$modalidade->maxcaracteres}}</p>
                                                        @error('resumo')
                                                        <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                @endif


                                        @elseif ($modalidade->palavras == true)
                                                @if($evento->is_multilingual && Session::get('idiomaAtual') === 'en')
                                                    <div class="row justify-content-center">
                                                        <div class="col-sm-12">
                                                            <label for="resumo_en"
                                                                   class="col-form-label">{{$formSubTraba->etiquetaresumotrabalho_en}}</label>
                                                            <textarea id="palavra"
                                                                      class="form-control palavra @error('resumo_en') is-invalid @enderror"
                                                                      name="resumo_en" value="{{ old('resumo_en') }}"
                                                                      autocomplete="resumo_en" autofocusrows="5"
                                                                      required>{{old('resumo_en')}}</textarea>
                                                            <p class="text-muted"><small><span id="numpalavra">0</span></small> -
                                                                Min Palavras: <span
                                                                    id="minpalavras">{{$modalidade->minpalavras}}</span> - Max
                                                                Palavras: <span id="maxpalavras">{{$modalidade->maxpalavras}}</span>
                                                            </p>
                                                            @error('resumo_en')
                                                            <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="row justify-content-center">
                                                        <div class="col-sm-12">
                                                            <label for="resumo"
                                                                   class="col-form-label">{{$formSubTraba->etiquetaresumotrabalho}}</label>
                                                            <textarea id="palavra"
                                                                      class="form-control palavra @error('resumo') is-invalid @enderror"
                                                                      name="resumo" value="{{ old('resumo') }}"
                                                                      autocomplete="resumo" autofocusrows="5"
                                                                      required>{{old('resumo')}}</textarea>
                                                            <p class="text-muted"><small><span id="numpalavra">0</span></small> -
                                                                Min Palavras: <span
                                                                    id="minpalavras">{{$modalidade->minpalavras}}</span> - Max
                                                                Palavras: <span id="maxpalavras">{{$modalidade->maxpalavras}}</span>
                                                            </p>
                                                            @error('resumo')
                                                            <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                @endif


                                        @endif
                                    @endif
                                    @if ($indice == "etiquetaareatrabalho")
                                        <!-- Areas -->
                                        <div class="row justify-content-center">
                                            <div class="col-sm-12">
                                                <label for="area"
                                                    class="col-form-label"><strong>{{$formSubTraba->etiquetaareatrabalho}}</strong>
                                                </label>
                                                <select class="form-control @error('areaId') is-invalid @enderror" id="area"
                                                        name="areaId">
                                                    <option value="" disabled selected hidden>
                                                        -- {{ $formSubTraba->etiquetaareatrabalho }} --
                                                    </option>
                                                    {{-- Apenas um teste abaixo --}}
                                                    @foreach($areas as $area)
                                                        <option value="{{$area->id}}"
                                                                @if(old('areaId') == $area->id) selected @endif>{{$area->nome}}</option>
                                                    @endforeach
                                                </select>
                                                @error('areaId')
                                                <span class="invalid-feedback" role="alert"
                                                    style="overflow: visible; display:block">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    @endif
                                    @if ($indice == "apresentacao")
                                        @if ($modalidade->apresentacao)
                                            <div class="row justify-content-center mt-4">
                                                <div class="col-sm-12">
                                                    <label for="area"
                                                        class="col-form-label"><strong>{{ __('Forma de apresentação do trabalho') }}</strong>
                                                    </label>
                                                    <select name="tipo_apresentacao" id="tipo_apresentacao" class="form-control @error('tipo_apresentacao') is-invalid @enderror" required>
                                                        <option value="" selected disabled>{{__('-- Selecione a forma de apresentação do trabalho --')}}</option>
                                                        @foreach ($modalidade->tiposApresentacao as $tipo)
                                                        <option @if(old('tipo_apresentacao') == $tipo->tipo) selected @endif value="{{$tipo->tipo}}">{{__($tipo->tipo)}}</option>
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
                                    @if ($indice == "etiquetauploadtrabalho")
                                        <div class="row justify-content-center">
                                            {{-- Submeter trabalho --}}

                                            @if ($modalidade->arquivo == true)
                                                <div class="col-sm-12" style="margin-top: 20px;">
                                                    <label for="nomeTrabalho"
                                                        class="col-form-label"><strong>{{$formSubTraba->etiquetauploadtrabalho}}</strong>
                                                    </label>
                                                    <div class="custom-file">
                                                        <input type="file" class="filestyle"
                                                            data-placeholder="Nenhum arquivo" data-text="Selecionar"
                                                            data-btnClass="btn-primary-lmts" name="arquivo" required>
                                                    </div>
                                                    <small><strong>Extensão de arquivos aceitas:</strong>
                                                        @foreach ($modalidade->tiposAceitos() as $item)
                                                            @if ($loop->last)
                                                                <span> .{{$item}}.</span>
                                                            @else
                                                                <span> .{{$item}},</span>
                                                            @endif
                                                        @endforeach</small>
                                                    @error('arquivo')
                                                    <span class="invalid-feedback" role="alert"
                                                        style="overflow: visible; display:block">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                    @if ($indice == "midiaExtra")
                                        <div class="row justify-content-center">
                                            @foreach ($modalidade->midiasExtra as $midia)
                                                <div class="col-sm-12" style="margin-top: 20px;">
                                                    <label for="{{$midia->hyphenizeNome()}}"
                                                        class="col-form-label"><strong>{{$midia->nome}}</strong>
                                                    </label>
                                                    <div class="custom-file">
                                                        <input type="file" class="filestyle"
                                                            data-placeholder="Nenhum arquivo" data-text="Selecionar"
                                                            data-btnClass="btn-primary-lmts" name="{{$midia->hyphenizeNome()}}" required>
                                                    </div>
                                                    <small><strong>Extensão de arquivos aceitas:</strong>
                                                        @foreach ($midia->tiposAceitos() as $item)
                                                            @if ($loop->first)
                                                                <span> .{{$item}}</span>
                                                            @elseif ($loop->last)
                                                                <span> .{{$item}}.</span>
                                                            @else
                                                                <span> .{{$item}},</span>
                                                            @endif
                                                        @endforeach</small>
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
                                                    @if($evento->is_multilingual && Session::get('idiomaAtual') === 'en')
                                                        <div class="row justify-content-center">
                                                            {{-- Nome Trabalho  --}}
                                                            <div class="col-sm-12">
                                                                <label for="campoextra1simples_en"
                                                                       class="col-form-label">{{ $formSubTraba->etiquetacampoextra1_en}}
                                                                    :</label>
                                                                <input id="campoextra1simples_en" type="text"
                                                                       class="form-control @error('campoextra1simples_en') is-invalid @enderror"
                                                                       name="campoextra1simples_en"
                                                                       value="{{ old('campoextra1simples_en') }}" required
                                                                       autocomplete="campoextra1simples_en" autofocus>
                                                                @error('campoextra1simples_en')
                                                                <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    @else
                                                    <div class="row justify-content-center">
                                                        {{-- Nome Trabalho  --}}
                                                        <div class="col-sm-12">
                                                            <label for="campoextra1simples"
                                                                class="col-form-label">{{ $formSubTraba->etiquetacampoextra1}}
                                                                :</label>
                                                            <input id="campoextra1simples" type="text"
                                                                class="form-control @error('campoextra1simples') is-invalid @enderror"
                                                                name="campoextra1simples"
                                                                value="{{ old('campoextra1simples') }}" required
                                                                autocomplete="campoextra1simples" autofocus>
                                                            @error('campoextra1simples')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    @endif
                                            @elseif ($formSubTraba->tipocampoextra1 == "textogrande")
                                                {{-- Texto Grande --}}
                                                @if($evento->is_multilingual && Session::get('idiomaAtual') === 'en')
                                                    <div class="row justify-content-center">
                                                        <div class="col-sm-12">
                                                            <label for="campoextra1grande_en"
                                                                   class="col-form-label">{{ $formSubTraba->etiquetacampoextra1_en}}
                                                                :</label>
                                                            <textarea id="campoextra1grande_en" type="text"
                                                                      class="form-control @error('campoextra1grande_en') is-invalid @enderror"
                                                                      name="campoextra1grande_en"
                                                                      value="{{ old('campoextra1grande_en') }}" required
                                                                      autocomplete="campoextra1grande_en" autofocus></textarea>
                                                            @error('campoextra1grande_en')
                                                            <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="row justify-content-center">
                                                        <div class="col-sm-12">
                                                            <label for="campoextra1grande"
                                                                class="col-form-label">{{ $formSubTraba->etiquetacampoextra1}}
                                                                :</label>
                                                            <textarea id="campoextra1grande" type="text"
                                                                    class="form-control @error('campoextra1grande') is-invalid @enderror"
                                                                    name="campoextra1grande"
                                                                    value="{{ old('campoextra1grande') }}" required
                                                                    autocomplete="campoextra1grande" autofocus></textarea>
                                                            @error('campoextra1grande')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                @endif
                                            @elseif ($formSubTraba->tipocampoextra1 == "upload")
                                                <div class="col-sm-12" style="margin-top: 20px;">
                                                    <label for="campoextra1arquivo"
                                                        class="col-form-label">{{ $formSubTraba->etiquetacampoextra1}}
                                                        :</label>
                                                    <div class="custom-file">
                                                        <input type="file" class="filestyle"
                                                            data-placeholder="Nenhum arquivo" data-text="Selecionar"
                                                            data-btnClass="btn-primary-lmts" name="campoextra1arquivo"
                                                            required>
                                                    </div>
                                                    <small>Algum texto aqui?</small>
                                                    @error('campoextra1arquivo')
                                                    <span class="invalid-feedback" role="alert"
                                                        style="overflow: visible; display:block">
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
                                                    @if($evento->is_multilingual && Session::get('idiomaAtual') === 'en')
                                                        <div class="row justify-content-center">
                                                            {{-- Nome Trabalho  --}}
                                                            <div class="col-sm-12">
                                                                <label for="campoextra2simples_en"
                                                                       class="col-form-label">{{ $formSubTraba->etiquetacampoextra2_en}}
                                                                    :</label>
                                                                <input id="campoextra2simples_en" type="text"
                                                                       class="form-control @error('campoextra2simples_en') is-invalid @enderror"
                                                                       name="campoextra2simples_en"
                                                                       value="{{ old('campoextra2simples_en') }}" required
                                                                       autocomplete="campoextra2simples_en" autofocus>
                                                                @error('campoextra2simples_en')
                                                                <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="row justify-content-center">
                                                            {{-- Nome Trabalho  --}}
                                                            <div class="col-sm-12">
                                                                <label for="campoextra2simples"
                                                                       class="col-form-label">{{ $formSubTraba->etiquetacampoextra2}}
                                                                    :</label>
                                                                <input id="campoextra2simples" type="text"
                                                                       class="form-control @error('campoextra2simples') is-invalid @enderror"
                                                                       name="campoextra2simples"
                                                                       value="{{ old('campoextra2simples') }}" required
                                                                       autocomplete="campoextra2simples" autofocus>
                                                                @error('campoextra2simples')
                                                                <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    @endif

                                            @elseif ($formSubTraba->tipocampoextra2 == "textogrande")
                                                    @if($evento->is_multilingual && Session::get('idiomaAtual') === 'en')
                                                        <div class="row justify-content-center">
                                                            {{-- Nome Trabalho  --}}

                                                            <div class="col-sm-12">
                                                                <label for="campoextra2grande_en"
                                                                       class="col-form-label">{{ $formSubTraba->etiquetacampoextra2_en}}
                                                                    :</label>
                                                                <textarea id="campoextra2grande_en" type="text"
                                                                          class="form-control @error('campoextra2grande_en') is-invalid @enderror"
                                                                          name="campoextra2grande_en"
                                                                          value="{{ old('campoextra2grande_en') }}" required
                                                                          autocomplete="campoextra2grande_en" autofocus></textarea>
                                                                @error('campoextra2grande_en')
                                                                <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    @else
                                                <div class="row justify-content-center">
                                                    {{-- Nome Trabalho  --}}

                                                    <div class="col-sm-12">
                                                        <label for="campoextra2grande"
                                                            class="col-form-label">{{ $formSubTraba->etiquetacampoextra2}}
                                                            :</label>
                                                        <textarea id="campoextra2grande" type="text"
                                                                class="form-control @error('campoextra2grande') is-invalid @enderror"
                                                                name="campoextra2grande"
                                                                value="{{ old('campoextra2grande') }}" required
                                                                autocomplete="campoextra2grande" autofocus></textarea>
                                                        @error('campoextra2grande')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                    @endif
                                            @elseif ($formSubTraba->tipocampoextra2 == "upload")
                                                <div class="col-sm-12" style="margin-top: 20px;">
                                                    <label for="campoextra2arquivo"
                                                        class="col-form-label">{{ $formSubTraba->etiquetacampoextra2}}
                                                        :</label>
                                                    <div class="custom-file">
                                                        <input type="file" class="filestyle"
                                                            data-placeholder="Nenhum arquivo" data-text="Selecionar"
                                                            data-btnClass="btn-primary-lmts" name="campoextra2arquivo"
                                                            required>
                                                    </div>
                                                    <small>Algum texto aqui?</small>
                                                    @error('campoextra2arquivo')
                                                    <span class="invalid-feedback" role="alert"
                                                        style="overflow: visible; display:block">
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
                                                    @if($evento->is_multilingual && Session::get('idiomaAtual') === 'en')
                                                        <div class="row justify-content-center">
                                                            <div class="col-sm-12">
                                                                <label for="campoextra3simples_en" class="col-form-label">{{ $formSubTraba->etiquetacampoextra3_en }}:</label>
                                                                <input id="campoextra3simples_en" type="text" class="form-control @error('campoextra3simples_en') is-invalid @enderror" name="campoextra3simples_en" value="{{ old('campoextra3simples_en') }}" required autocomplete="campoextra3simples_en" autofocus>
                                                                @error('campoextra3simples_en')
                                                                <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="row justify-content-center">
                                                            <div class="col-sm-12">
                                                                <label for="campoextra3simples" class="col-form-label">{{ $formSubTraba->etiquetacampoextra3 }}:</label>
                                                                <input id="campoextra3simples" type="text" class="form-control @error('campoextra3simples') is-invalid @enderror" name="campoextra3simples" value="{{ old('campoextra3simples') }}" required autocomplete="campoextra3simples" autofocus>
                                                                @error('campoextra3simples')
                                                                <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    @endif
                                                @elseif ($formSubTraba->tipocampoextra3 == "textogrande")
                                                    {{-- Texto Grande --}}
                                                    @if($evento->is_multilingual && Session::get('idiomaAtual') === 'en')
                                                        <div class="row justify-content-center">
                                                            <div class="col-sm-12">
                                                                <label for="campoextra3grande_en" class="col-form-label">{{ $formSubTraba->etiquetacampoextra3_en }}:</label>
                                                                <textarea id="campoextra3grande_en" class="form-control @error('campoextra3grande_en') is-invalid @enderror" name="campoextra3grande_en" required autocomplete="campoextra3grande_en" autofocus>{{ old('campoextra3grande_en') }}</textarea>
                                                                @error('campoextra3grande_en')
                                                                <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="row justify-content-center">
                                                            <div class="col-sm-12">
                                                                <label for="campoextra3grande" class="col-form-label">{{ $formSubTraba->etiquetacampoextra3 }}:</label>
                                                                <textarea id="campoextra3grande" class="form-control @error('campoextra3grande') is-invalid @enderror" name="campoextra3grande" required autocomplete="campoextra3grande" autofocus>{{ old('campoextra3grande') }}</textarea>
                                                                @error('campoextra3grande')
                                                                <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                              </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    @endif
                                            @elseif ($formSubTraba->tipocampoextra3 == "upload")
                                                {{-- Arquivo de Regras  --}}
                                                <div class="col-sm-12" style="margin-top: 20px;">
                                                    <label for="campoextra3arquivo"
                                                        class="col-form-label">{{ $formSubTraba->etiquetacampoextra3}}
                                                        :</label>
                                                    <div class="custom-file">
                                                        <input type="file" class="filestyle"
                                                            data-placeholder="Nenhum arquivo" data-text="Selecionar"
                                                            data-btnClass="btn-primary-lmts" name="campoextra3arquivo"
                                                            required>
                                                    </div>
                                                    <small>Algum texto aqui?</small>
                                                    @error('campoextra3arquivo')
                                                    <span class="invalid-feedback" role="alert"
                                                        style="overflow: visible; display:block">
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
                                                    @if($evento->is_multilingual && Session::get('idiomaAtual') === 'en')
                                                        <div class="row justify-content-center">
                                                            <div class="col-sm-12">
                                                                <label for="campoextra4simples_en" class="col-form-label">{{ $formSubTraba->etiquetacampoextra4_en }}:</label>
                                                                <input id="campoextra4simples_en" type="text" class="form-control @error('campoextra4simples_en') is-invalid @enderror" name="campoextra4simples_en" value="{{ old('campoextra4simples_en') }}" required autocomplete="campoextra4simples_en" autofocus>
                                                                @error('campoextra4simples_en')
                                                                <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                                </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="row justify-content-center">
                                                            <div class="col-sm">
                                                                <label for="campoextra4simples" class="col-form-label">{{ $formSubTraba->etiquetacampoextra4 }}:</label>
                                                                <input id="campoextra4simples" type="text" class="form-control @error('campoextra4simples') is-invalid @enderror" name="campoextra4simples" value="{{ old('campoextra4simples') }}" required autocomplete="campoextra4simples" autofocus>
                                                                @error('campoextra4simples')
                                                                <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                                </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    @endif
                                                @elseif ($formSubTraba->tipocampoextra4 == "textogrande")
                                                    {{-- Texto Grande --}}
                                                    @if($evento->is_multilingual && Session::get('idiomaAtual') === 'en')
                                                        <div class="row justify-content-center">
                                                            <div class="col-sm-12">
                                                                <label for="campoextra4grande_en" class="col-form-label">{{ $formSubTraba->etiquetacampoextra4_en }}:</label>
                                                                <textarea id="campoextra4grande_en" class="form-control @error('campoextra4grande_en') is-invalid @enderror" name="campoextra4grande_en" required autocomplete="campoextra4grande_en" autofocus>{{ old('campoextra4grande_en') }}</textarea>
                                                                @error('campoextra4grande_en')
                                                                <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                                </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="row justify-content-center">
                                                            <div class="col-sm-12">
                                                                <label for="campoextra4grande" class="col-form-label">{{ $formSubTraba->etiquetacampoextra4 }}:</label>
                                                                <textarea id="campoextra4grande" class="form-control @error('campoextra4grande') is-invalid @enderror" name="campoextra4grande" required autocomplete="campoextra4grande" autofocus>{{ old('campoextra4grande') }}</textarea>
                                                                @error('campoextra4grande')
                                                                <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                                </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    @endif
                                            @elseif ($formSubTraba->tipocampoextra4 == "upload")
                                                {{-- Arquivo de Regras  --}}
                                                <div class="col-sm-12" style="margin-top: 20px;">
                                                    <label for="campoextra4arquivo"
                                                        class="col-form-label">{{$formSubTraba->etiquetacampoextra4}}
                                                        :</label>
                                                    <div class="custom-file">
                                                        <input type="file" class="filestyle"
                                                            data-placeholder="Nenhum arquivo" data-text="Selecionar"
                                                            data-btnClass="btn-primary-lmts" name="campoextra4arquivo"
                                                            required>
                                                    </div>
                                                    <small>Algum texto aqui?</small>
                                                    @error('campoextra4arquivo')
                                                    <span class="invalid-feedback" role="alert"
                                                        style="overflow: visible; display:block">
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
                                                    {{-- Texto Simples --}}
                                                    @if($evento->is_multilingual && Session::get('idiomaAtual') === 'en')
                                                        <div class="row justify-content-center">
                                                            <div class="col-sm-12">
                                                                <label for="campoextra5simples_en" class="col-form-label">{{ $formSubTraba->etiquetacampoextra5_en }}:</label>
                                                                <input id="campoextra5simples_en" type="text" class="form-control @error('campoextra5simples_en') is-invalid @enderror" name="campoextra5simples_en" value="{{ old('campoextra5simples_en') }}" required autocomplete="campoextra5simples_en" autofocus>
                                                                @error('campoextra5simples_en')
                                                                <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                                </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="row justify-content-center">
                                                            <div class="col-sm-12">
                                                                <label for="campoextra5simples" class="col-form-label">{{ $formSubTraba->etiquetacampoextra5 }}:</label>
                                                                <input id="campoextra5simples" type="text" class="form-control @error('campoextra5simples') is-invalid @enderror" name="campoextra5simples" value="{{ old('campoextra5simples') }}" required autocomplete="campoextra5simples" autofocus>
                                                                @error('campoextra5simples')
                                                                <span class="invalid-feedback" role="alert">
<strong>{{ $message }}</strong>
</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    @endif
                                                @elseif ($formSubTraba->tipocampoextra5 == "textogrande")
                                                    {{-- Texto Grande --}}
                                                    @if($evento->is_multilingual && Session::get('idiomaAtual') === 'en')
                                                        <div class="row justify-content-center">
                                                            <div class="col-sm-12">
                                                                <label for="campoextra5grande_en" class="col-form-label">{{ $formSubTraba->etiquetacampoextra5_en }}:</label>
                                                                <textarea id="campoextra5grande_en" class="form-control @error('campoextra5grande_en') is-invalid @enderror" name="campoextra5grande_en" required autocomplete="campoextra5grande_en" autofocus>{{ old('campoextra5grande_en') }}</textarea>
                                                                @error('campoextra5grande_en')
                                                                <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                                </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="row justify-content-center">
                                                            <div class="col-sm-12">
                                                                <label for="campoextra5grande" class="col-form-label">{{ $formSubTraba->etiquetacampoextra5 }}:</label>
                                                                <textarea id="campoextra5grande" class="form-control @error('campoextra5grande') is-invalid @enderror" name="campoextra5grande" required autocomplete="campoextra5grande" autofocus>{{ old('campoextra5grande') }}</textarea>
                                                                @error('campoextra5grande')
                                                                <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                                </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    @endif
                                            @elseif ($formSubTraba->tipocampoextra5 == "upload")
                                                {{-- Arquivo de Regras  --}}
                                                <div class="col-sm-12" style="margin-top: 20px;">
                                                    <label for="campoextra5arquivo"
                                                        class="col-form-label">{{ $formSubTraba->etiquetacampoextra5}}
                                                        :</label>
                                                    <div class="custom-file">
                                                        <input type="file" class="filestyle"
                                                            data-placeholder="Nenhum arquivo" data-text="Selecionar"
                                                            data-btnClass="btn-primary-lmts" name="campoextra5arquivo"
                                                            required>
                                                    </div>
                                                    <small>Algum texto aqui?</small>
                                                    @error('campoextra5arquivo')
                                                    <span class="invalid-feedback" role="alert"
                                                        style="overflow: visible; display:block">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            @endif
                                        @endif
                                    @endif
                                @endforeach
                                <div class="row justify-content-center mt-2">
                                    <div class="col-md-6">
                                        <a href="{{route('evento.visualizar',['id'=>$evento->id])}}"
                                            class="btn btn-secondary" style="width:100%">{{__('Cancelar')}}
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="submit"
                                                class="btn btn-primary button-prevent-multiple-submits"
                                                style="width:100%">
                                            <i class="spinner fa fa-spinner fa-spin"
                                                style="display: none;"></i> {{ __('Enviar') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </p>
                    </div>
                </div>
            </div>
            @if(in_array('etiquetacoautortrabalho', $ordemCampos))
                <div id="div-add-coautor" class="col-sm-2"
                     style="margin-top:50px; position: fixed; right: 2%; padding-left: 0px;">
                    <div class="float-right">
                        <button @click="adicionaAutor" id="addCoautor" class="btn btn-primary btn-padding border mb-2"
                           style="text-decoration: none; border-radius: 14px; background-color: #E5B300"
                           title="Clique aqui para adicionar {{$evento->formSubTrab->etiquetacoautortrabalho}}, se houver">
                            <img id="icone-add-coautor" class="mt-2" src="{{asset('img/icons/user-plus-solid.svg')}}"
                                 alt="ícone de adicionar {{$evento->formSubTrab->etiquetacoautortrabalho}}" width="30px">
                            <br> Adicionar {{$evento->formSubTrab->etiquetacoautortrabalho}}
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        function handler() {
            usuario = @json(auth()->user());
            oldEmail = @json(old('emailCoautor'));
            oldNome = @json(old('nomeCoautor'));
            inicial = [];
            if (oldEmail == null) {
                inicial.push({
                    nome: usuario.name,
                    email: usuario.email
                })
            } else {
                for (let i = 0; i < oldEmail.length; i++) {
                    inicial.push({
                        nome: oldNome[i],
                        email: oldEmail[i]
                    })
                }
            }
            return {
                autores: inicial,
                adicionaAutor() {
                    this.autores.push({
                        nome: '',
                        email: ''
                    });
                },
                removeAutor(index) {
                    this.autores.splice(index, 1);
                },
                sobeAutor(index) {
                    if (index > 1) {
                        temp = this.autores[index - 1]
                        this.autores[index - 1] = this.autores[index]
                        this.autores[index] = temp
                    }
                },
                desceAutor(index) {
                    if (index > 0 && (index + 1) < this.autores.length) {
                        temp = this.autores[index + 1]
                        this.autores[index + 1] = this.autores[index]
                        this.autores[index] = temp
                    }
                }
            }
        }

        function checarNome(index) {
            let data = {
                email: $('#email' + index).val(),
                _token: '{{csrf_token()}}'
            };
            if (!(data.email == "" || data.email.indexOf('@') == -1 || data.email.indexOf('.') == -1)) {
                $.ajax({
                    type: 'GET',
                    url: '{{ route("search.user") }}',
                    data: data,
                    dataType: 'json',
                    success: function (res) {
                        if (res.user[0] != null) {
                            $('#nome' + index).val(res.user[0]['name']);
                        }
                    },
                    error: function (err) {
                    }
                });
            }
        }

        $(document).ready(function () {
            $('.char-count').keyup(function () {
                var maxLength = parseInt($(this).attr('maxlength'));
                var length = $(this).val().length;
                // var newLength = maxLength-length;

                var name = $(this).attr('name');

                $('span[name="' + name + '"]').text(length);
            });
        });

        $(document).ready(function () {
            $('.palavra').keyup(function () {
                var myText = this.value.trim();
                var wordsArray = myText.split(/\s+/g);
                var words = wordsArray.length;
                var min = parseInt(($('#minpalavras').text()));
                var max = parseInt(($('#maxpalavras').text()));
                if (words < min || words > max) {
                    this.setCustomValidity('Número de palavras não permitido. Você possui atualmente ' + words + ' palavras.');
                } else {
                    this.setCustomValidity('');
                }

                $('#numpalavra').text(words);
            });
        });

        $(document).ready(function () {
            function ordenar(event) {
                event.preventDefault();
                // console.log(event);
            }
        });

        function centralizarTela(index) {
            if ($("#email" + index).length) {
                var el = $("#email" + index);
                el.focus();
                var center = $(window).height() / 2;
                var top = el.offset().top;
                if (top > center) {
                    $(window).scrollTop(top - center);
                }
            }
        }

        $(function () {
            // Exibir modalidade de acordo com a área
            $("#area").change(function () {
                // console.log($(this).val());
                addModalidade($(this).val());
            });


        });

        function addModalidade(areaId) {
            // console.log(modalidades)
            $("#modalidade").empty();
            for (let i = 0; i < modalidades.length; i++) {
                if (modalidades[i].areaId == areaId) {
                    // console.log(modalidades[i]);
                    $("#modalidade").append("<option value=" + modalidades[i].modalidadeId + ">" + modalidades[i].modalidadeNome + "</option>")
                }
            }
        }
    </script>
@endsection
