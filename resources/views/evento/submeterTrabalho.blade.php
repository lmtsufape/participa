@extends('layouts.app')

@section('content')
    <style>
        .etapas {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #ccc;
            margin-bottom: 20px;
            font-family: sans-serif;
        }

        .etapa {
            flex: 1;
            text-align: left;
            padding: 10px 0;
            color: #aaa;
            font-weight: normal;
            border-bottom: 2px solid transparent;
        }

        .etapa.ativa {
            color: #004d51;
            font-weight: bold;
            border-bottom: 2px solid #004d51;
        }

        .required-field::after {
            content: "*";
            color: #D44100;
            margin-left: 2px;
        }
        .custom-select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            text-align: left;
            padding-right: 2.5rem;
            background-color: #fff;
            background-image:
                url("data:image/svg+xml;charset=US-ASCII,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'><path fill='%23666' d='M2 5L0 3L4 3Z'/></svg>");
            background-repeat: no-repeat;
            background-position: right .75rem top 10px;
            background-size: .65em .65em;
        }

        .upload-icon {
            /* primeiro deixamos tudo preto (brightness(0)), depois invertemos (invert(1)) */
            filter: brightness(0) invert(1);
        }


        .custom-select:required:invalid {
            color: #6c757d;
        }

        /* quando o usuário escolhe uma opção válida, mostra o texto em cor normal */
        .custom-select:required:valid {
            color: #212529;
        }
    </style>
    <div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-danger">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Erro de Validação</h5>
                    <!-- botão × removido -->
                </div>
                <div class="modal-body">
                    <ul id="errorModalMessages" class="mb-0">
                        {{-- mensagens serão injetadas via JS --}}
                    </ul>
                </div>
                <div class="modal-footer">
                    <button
                        id="btnFecharErrorModal"
                        type="button"
                        class="btn btn-danger"
                        data-bs-dismiss="modal"
                    >
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container content">
        <div class="row justify-content-center" x-data="handler()">
            <div class="col-sm-10" style="padding-right: 0px;">

                <br>

                <div class="row titulo text-center" style="color: #034652;">
                    <h2 style="font-weight: bold;">{{__('Submissão de trabalho')}}</h2>
                </div>

                <br>

                <div style="margin-top:25px;">
                    <div class="">


                        {{-- @if($evento->is_multilingual && Session::get('idiomaAtual') === 'en')
                            <h2 class="card-title">{{$evento->nome_en}}</h2>
                            <h4 class="card-title">{{__('Modalidade')}}: {{$modalidade->nome_en}}</h4>
                        @elseif($evento->is_multilingual && Session::get('idiomaAtual') === 'es')
                            <h2 class="card-title">{{$evento->nome_es}}</h2>
                            <h4 class="card-title">{{__('Modalidade')}}: {{$modalidade->nome_es}}</h4>
                        @else
                            <h2 class="card-title">{{$evento->nome}}</h2>
                            <h4 class="card-title">{{__('Modalidade')}}: {{$modalidade->nome}}</h4>
                        @endif

                        <div class="titulo-detalhes"></div>
                        <br>
                        <h4 class="card-title">{{__('Enviar Trabalho')}}</h4>
                        <p class="card-text"> --}}

                            <form method="POST" action="{{route('trabalho.store')}}"
                                enctype="multipart/form-data" class="form-prevent-multiple-submits">
                                @csrf
                                <input type="hidden" name="eventoId" value="{{$evento->id}}">
                                <div id="etapa-1">
                                    <!-- <div class="etapas" style="font-weight: 500;">
                                        <div class="etapa ativa">
                                            <p>1. Informações do trabalho</p>
                                        </div>
                                        <div class="etapa">
                                            <p>2. Autoria</p>
                                        </div>
                                    </div> -->

                                    <div class="card card-body">
                                        @foreach ($ordemCampos as $indice)
                                            @if ($indice == "etiquetaareatrabalho")
                                                <!-- Areas -->
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <label for="area" class="col-form-label required-field"><strong>Área temática</strong></label>
                                                        <select class="form-control custom-select @error('areaId') is-invalid @enderror" id="area"
                                                                name="areaId"
                                                                required>
                                                            <option value="" disabled selected hidden>
                                                                Selecione a área temática
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

                                                    <div class="col-sm-6">
                                                        <label for="modalidade" class="col-form-label required-field"><strong>Modalidade</strong></label>

                                                        {{-- campo escondido para enviar o valor mesmo com o select disabled --}}
                                                        <input type="hidden" name="modalidadeId" value="{{ $modalidade->id }}">

                                                        <select
                                                            id="modalidade"
                                                            class="form-control  @error('modalidadeId') is-invalid @enderror"
                                                            disabled
                                                        >
                                                            @foreach($modalidades as $m)
                                                                @php
                                                                    $label = $m->nome;
                                                                    if ($evento->is_multilingual) {
                                                                        if (Session::get('idiomaAtual') === 'en' && $m->nome_en) {
                                                                            $label = $m->nome_en;
                                                                        } elseif (Session::get('idiomaAtual') === 'es' && $m->nome_es) {
                                                                            $label = $m->nome_es;
                                                                        }
                                                                    }
                                                                @endphp
                                                                <option
                                                                    value="{{ $m->id }}"
                                                                    {{ $m->id === $modalidade->id ? 'selected' : '' }}
                                                                >
                                                                    {{ $label }}
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                        @error('modalidadeId')
                                                        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                        <br>
                                        @foreach ($ordemCampos as $indice)
                                            @if ($indice == "etiquetatitulotrabalho")
                                                @if($evento->is_multilingual && Session::get('idiomaAtual') === 'en')
                                                    <div class="row justify-content-center">
                                                        {{-- Nome Trabalho Ingles  --}}
                                                        <div class="col-sm-12">
                                                            <label for="nomeTrabalho_en"
                                                                class="col-form-label required-field"><strong>{{ $formSubTraba->etiquetatitulotrabalho_en }}</strong>
                                                            </label>
                                                            <input id="nomeTrabalho_en" type="text" required
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
                                                            <label for="nomeTrabalho" class="col-form-label required-field"><strong>{{ $formSubTraba->etiquetatitulotrabalho }}</strong></label>
                                                            <input id="nomeTrabalho" type="text" required
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

                                            {{--@if ($indice == "etiquetaautortrabalho")--}}
                                            {{-- <div class="row justify-content-center">
                                            Autor
                                            <div class="col-sm-12">
                                                <label for="nomeTrabalho" class="col-form-label">{{$formSubTraba->etiquetaautortrabalho}}</label>
                                                <input class="form-control" type="text" disabled value="{{Auth::user()->name}}">
                                            </div>
                                            </div> --}}
                                            {{--@endif--}}

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
                                            {{-- @if ($indice == "etiquetaareatrabalho")
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
                                            @endif --}}
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

                                                            @if($modalidade->submissaoUnica == true)
                                                            <div>
                                                            <strong style="color: red;">ATENÇÃO: Nesta modalidade só é possível submeter o trabalho apenas uma vez.</strong>
                                                            </div>
                                                            @endif
                                                            <div class="custom-file">
                                                                <label for="arquivo"
                                                                       class="btn btn-primary btn-padding border"
                                                                       style="text-decoration:none; border-radius:10px; background-color:#D44100;"
                                                                       title="Clique aqui para selecionar um arquivo">
                                                                    <img src="{{ asset('img/icons/upload.svg') }}" class="upload-icon" alt="upload"
                                                                         alt="ícone de upload" width="24px"
                                                                         style="margin-right:8px; vertical-align:middle;">
                                                                    Selecionar arquivo
                                                                </label>
                                                                <input type="file"
                                                                       id="arquivo"
                                                                       name="arquivo"
                                                                       required
                                                                       style="display:none;">
                                                                <span id="nome-arquivo" style="margin-left:10px; vertical-align:middle;">Nenhum arquivo selecionado</span>
                                                            </div>

                                                            <small>
                                                                <strong>Extensão de arquivos aceitas:</strong>
                                                                <span id="extensoes-aceitas"></span>
                                                                <br>
                                                                <span>O tamanho máximo para arquivos de vídeo é de 50 MB. Os demais tipos possuem tamanho máximo de 5 MB.</span>
                                                            </small>


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
                                                            <label for="{{ $midia->hyphenizeNome() }}" class="col-form-label">
                                                                <strong>{{ $midia->nome }}</strong>
                                                            </label>

                                                            <div class="custom-file">
                                                                {{-- label estilizado como botão --}}
                                                                <label
                                                                    for="{{ $midia->hyphenizeNome() }}"
                                                                    class="btn btn-primary btn-padding border"
                                                                    style="text-decoration:none; border-radius:10px; background-color:#D44100;"
                                                                    title="Clique aqui para selecionar um arquivo"
                                                                >
                                                                    <img
                                                                        src="{{ asset('img/icons/upload.svg') }}"
                                                                        class="upload-icon"
                                                                        alt="upload"
                                                                        width="24"
                                                                        style="margin-right:8px; vertical-align:middle;"
                                                                    >
                                                                    Selecionar arquivo
                                                                </label>

                                                                {{-- input escondido --}}
                                                                <input
                                                                    type="file"
                                                                    id="{{ $midia->hyphenizeNome() }}"
                                                                    name="{{ $midia->hyphenizeNome() }}"
                                                                    required
                                                                    style="display:none;"
                                                                    onchange="document.getElementById('nome-{{ $midia->hyphenizeNome() }}').textContent = this.files[0]?.name || 'Nenhum arquivo selecionado'"
                                                                >

                                                                {{-- span pra mostrar o nome do arquivo --}}
                                                                <span
                                                                    id="nome-{{ $midia->hyphenizeNome() }}"
                                                                    style="margin-left:10px; vertical-align:middle;"
                                                                >Nenhum arquivo selecionado</span>
                                                            </div>

                                                            <small>
                                                                <strong>Extensão de arquivos aceitas:</strong>
                                                                @foreach ($midia->tiposAceitos() as $item)
                                                                    @if ($loop->first)
                                                                        <span> .{{ $item }}</span>
                                                                    @elseif ($loop->last)
                                                                        <span> .{{ $item }}.</span>
                                                                    @else
                                                                        <span> .{{ $item }},</span>
                                                                    @endif
                                                                @endforeach
                                                            </small>

                                                            {{-- corrija para usar o fieldName, não o nome da mídia --}}
                                                            @error($midia->hyphenizeNome())
                                                            <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
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




                                        @foreach ($ordemCampos as $indice)
                                            @if($indice == "etiquetacoautortrabalho")
                                                <div style="margin-top:20px">
                                                    <div class="row align-items-center justify-content-between"
                                                        style="flex-wrap: unset">
                                                        <div class="col-md-12">
                                                            <label class="mt-3 mb-0"> <b>{{$evento->formSubTrab->etiquetaautortrabalho}}</b> </label>
                                                            @if(in_array('etiquetacoautortrabalho', $ordemCampos))
                                                                    <div class="float-end mb-2">
                                                                        <button @click.prevent="adicionaAutor" id="addCoautor" class="btn btn-primary btn-padding border me-2"
                                                                                style="text-decoration: none; border-radius: 10px; background-color: #D44100"
                                                                                title="Clique aqui para adicionar {{$evento->formSubTrab->etiquetacoautortrabalho}} já cadastrado">
                                                                            <img id="icone-add-coautor" src="{{asset('img/icons/user-plus-solid.svg')}}"
                                                                                 alt="ícone de adicionar {{$evento->formSubTrab->etiquetacoautortrabalho}}" width="30px">
                                                                            Adicione um coautor(a)
                                                                        </button>
                                                                        <button @click.prevent="cadastrarAutor" id="cadastrarCoautor" class="btn btn-success btn-padding border"
                                                                                style="text-decoration: none; border-radius: 10px; background-color: #D44100"
                                                                                title="Clique aqui para cadastrar um novo {{$evento->formSubTrab->etiquetacoautortrabalho}}">
                                                                            <img id="icone-cadastrar-coautor" src="{{asset('img/icons/user-plus-solid.svg')}}"
                                                                                 alt="ícone de cadastrar {{$evento->formSubTrab->etiquetacoautortrabalho}}" width="30px">
                                                                            Inserir coautor(a) sem cadastro
                                                                        </button>
                                                                    </div>

                                                            @endif
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
                                                                <div class="item card w-100 mb-2">
                                                                    <div class="row card-body">
                                                                        <div :class="index == 0 ? 'col-md-6' : (autor.cadastrado === 'nao' ? 'col-md-4 col-lg-4' : 'col-md-4 col-lg-4')">
                                                                            <label :for="'email' + index">E-mail <small class="text-muted" x-show="index > 0 && autor.cadastrado === 'nao'">(opcional)</small></label>
                                                                        <input type="email" style="margin-bottom:10px"
                                                                            class="form-control emailCoautor"
                                                                            :class="index === 0 ? 'bg-light text-muted' : ''"
                                                                            name="emailCoautor[]" placeholder="E-mail"
                                                                            :id="'email' + index"
                                                                            x-init="$nextTick(() => centralizarTela(index))"
                                                                                x-on:focusout="checarNome(index)"
                                                                            x-model="autor.email"
                                                                                :readonly="@can('isCoordenadorOrComissaoCientifica', $evento) undefined @else index == 0 @endcan"
                                                                                :required="index == 0 || autor.cadastrado === 'sim'">
                                                                    </div>
                                                                    <div :class="index == 0 ? 'col-md-6' : (autor.cadastrado === 'nao' ? 'col-md-4 col-lg-5' : 'col-md-4 col-lg-5')">
                                                                        <label :for="'nome' + index">Nome Completo</label>
                                                                        <input type="text" style="margin-bottom:10px"
                                                                            class="form-control emailCoautor"
                                                                            :class="index === 0 ? 'bg-light text-muted' : ''"
                                                                            name="nomeCoautor[]" placeholder="Nome"
                                                                            :id="'nome' + index"
                                                                            x-model="autor.nome"
                                                                                :readonly="@can('isCoordenadorOrComissaoCientifica', $evento) undefined @else index == 0 @endcan"
                                                                                :required="index == 0 || autor.cadastrado === 'nao'">
                                                                    </div>
                                                                        <template x-if="index > 0 && autor.cadastrado === 'nao'">
                                                                            <div class="col-md-4 col-lg-3">
                                                                                <label :for="'vinculo' + index">Organização</label>
                                                                                <input type="text" class="form-control" :id="'vinculo' + index" name="vinculoCoautor[]" x-model="autor.vinculo" placeholder="Organização" required>
                                                                            </div>
                                                                        </template>
                                                                        <input type="hidden" :name="'coautorCadastrado['+index+']'" x-model="autor.cadastrado">
                                                                        <template x-if="index > 0">
                                                                            <div class="col-md-4 col-lg-3 justify-content-center d-flex align-items-end btn-group pb-1">
                                                                                <button type="button" @click="removeAutor(index)" style="color: #d30909;" class="btn"><img src="{{asset('img/icons/trash-alt-regular.svg')}}" class="icon-card" width="24" alt="Remover"></button>
                                                                                <button type="button" @click="sobeAutor(index)" class="btn btn-link"><img src="{{asset('img/icons/sobe.png')}}" class="icon-card" width="24" alt="Subir"></button>
                                                                                <button type="button" @click="desceAutor(index)" class="btn btn-link"><img src="{{asset('img/icons/desce.png')}}" class="icon-card" width="24" alt="Descer"></button>
                                                                            </div>
                                                                        </template>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach

                                        <br>


                                        <br>

                                        <div class="row form-group">
                                            <div class="col-md-3">
                                                <a href="{{route('evento.visualizar',['id'=>$evento->id])}}"
                                                    class="btn btn-secondary" style="width:100%">{{__('Cancelar')}}
                                                </a>
                                            </div>

                                            <div class="col-md-6"></div>

                                            <div class="col-md-3">
                                                <button type="submit" class="btn btn-primary w-100" style="background-color: #034652; color: white; border-color: #034652;">
                                                    {{ __('Enviar') }}
                                                </button>
                                                </div>
                                        </div>
                                    </div>
                                </div>


                            </form>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @php
        $extensoesPorModalidade = [];
        foreach($modalidades as $m) {
            $extensoesPorModalidade[$m->id] = $m->tiposAceitos();
        }
    @endphp
@endsection

@section('javascript')
    <script>
        const modalidadesData = @json(
        $modalidades
            ->map(fn($m) => [
                'id'           => $m->id,
                'maxCoautores' => $m->numMaxCoautores,
            ])
            ->values()
            ->all()
    );

        const modalidadesExtensoes = @json($extensoesPorModalidade);
        document.addEventListener('DOMContentLoaded', function() {
        const selectModalidade = document.getElementById('modalidade');
        const spanExtensoes    = document.getElementById('extensoes-aceitas');
        function atualizarExtensoes() {
            // id da modalidade:
            const modalidadeId = selectModalidade.value;
            // array de extensões
            const extensoes = modalidadesExtensoes[modalidadeId] || [];
            let html = '';
            extensoes.forEach((extensao, idx) => {
                html += `<span>.${extensao}${idx === extensoes.length - 1 ? '.' : ','}</span>`;
            });
            spanExtensoes.innerHTML = html;
        }
        atualizarExtensoes();
        selectModalidade.addEventListener('change', atualizarExtensoes);
        });
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
                modalidades: modalidadesData,
                adicionaAutor() {
                    const select = document.getElementById('modalidade');
                    const selectedId = select ? select.value : null;
                    if (!selectedId) {
                        showErrorModal('Selecione primeiro uma modalidade antes de adicionar coautor.');
                        return;
                    }
                    const modalidadeEscolhida = this.modalidades.find(
                        m => m.id === Number(selectedId)
                    );
                    const maxCo = modalidadeEscolhida.maxCoautores;
                    if (maxCo != null){
                        const coautoresAtuais = this.autores.length - 1; // Para não contar o autor principal
                        if (coautoresAtuais >= maxCo) {
                            showErrorModal(`Você já atingiu o número máximo de coautores (${maxCo}).`);
                            return;
                        }
                    }
                    this.autores.push({
                        nome: '',
                        email: '',
                        cadastrado: 'sim',
                        vinculo: ''
                    });
                },
                cadastrarAutor() {
                    const select = document.getElementById('modalidade');
                    const selectedId = select ? select.value : null;
                    if (!selectedId) {
                        showErrorModal('Selecione primeiro uma modalidade antes de cadastrar coautor.');
                        return;
                    }
                    const modalidadeEscolhida = this.modalidades.find(
                        m => m.id === Number(selectedId)
                    );
                    const maxCo = modalidadeEscolhida.maxCoautores;
                    if (maxCo != null){
                        const coautoresAtuais = this.autores.length - 1;
                        if (coautoresAtuais >= maxCo) {
                            showErrorModal(`Você já atingiu o número máximo de coautores (${maxCo}).`);
                            return;
                        }
                    }
                    this.autores.push({
                        nome: '',
                        email: '',
                        cadastrado: 'nao',
                        vinculo: ''
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

        document.getElementById('arquivo').addEventListener('change', function() {
            const arquivo = this.files[0];
            const nomeArquivoSpan = document.getElementById('nome-arquivo');
            if (arquivo) {
                nomeArquivoSpan.textContent = arquivo.name;
            } else {
                nomeArquivoSpan.textContent = '';
            }
        });


        function proximaEtapa() {
            document.getElementById('etapa-1').style.display = 'none';
            document.getElementById('etapa-2').style.display = 'block';
        }

        function etapaAnterior() {
            document.getElementById('etapa-1').style.display = 'block';
            document.getElementById('etapa-2').style.display = 'none';
        }

        function showErrorModal(message) {
            // coloca a mensagem dentro de uma <li>
            $('#errorModal .modal-body ul').html('<li>' + message + '</li>');
            // abre o modal
            $('#errorModal').modal('show');
        }
    </script>
@endsection
