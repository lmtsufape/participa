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

        .upload-area:hover {
            border-color: #0d6efd !important;
            background-color: #f8f9ff;
        }

        .upload-area.dragover {
            border-color: #0d6efd !important;
            background-color: #f8f9ff;
            transform: scale(1.01);
        }

        .upload-content {
            pointer-events: none;
        }

        .required-field::after {
            content: " *";
            color: red;
        }

        .upload-area {
            position: relative;
            z-index: 1;
        }

        label {
            position: relative;
            z-index: 3;
            display: block;
        }

        .row.form-group.pb-4.pt-4 {
            clear: both;
            margin-top: 2rem;
            position: relative;
            z-index: 5;
        }
    </style>

    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="text-start" style="color: #034652; font-weight: bold;">
                    @if ($eventoPai ?? '')
                        {{ __('Novo Subevento') }}
                    @else
                        {{ __('Novo Evento') }}
                    @endif
                </h2>
            </div>
        </div>

        <form action="{{ route('evento.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if ($eventoPai ?? '')
                <input type="hidden" name="eventoPai" value="{{ $eventoPai->id }}">
            @endif

            <div id="etapa-1">
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="etapas" style="font-weight: 500;">
                            <div class="etapa ativa">
                                <p>1. {{ __('Informações gerais') }}</p>
                            </div>
                            <div class="etapa">
                                <p>2. {{ __('Endereço e data') }}</p>
                            </div>
                            @error('eventoPai')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="container card shadow">
                    <br>
                    {{-- nome | Participantes | Tipo --}}

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="nome" class="col-form-label text-start d-block fw-bold required-field">{{ __('Nome do evento') }}</label>
                            <input id="nome" type="text" class="form-control @error('nome') is-invalid @enderror"
                                name="nome" value="{{ old('nome') }}" required autocomplete="nome" autofocus>

                            @error('nome')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="col-form-label text-start d-block fw-bold required-field">{{ __('E-mail de contato') }}</label>
                            <input class="form-control @error('email') is-invalid @enderror" type="email"
                                value="{{ old('email') }}" name="email" id="email" required autofocus
                                autocomplete="email">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <br>

                    <div class="form-group row">
                        <div class="col-md-6 multilingual_fields" style="display: none;">
                            <label for="nome_en" class="col-form-label text-start d-block fw-bold required-field">{{ __('Nome em inglês') }}</label>
                            <input id="nome_en" type="text" class="form-control @error('nome_en') is-invalid @enderror"
                                name="nome_en" value="{{ old('nome_en') }}" autocomplete="nome_en" autofocus>

                            @error('nome_en')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-6 multilingual_fields" style="display: none;">
                            <label for="nome_es" class="col-form-label text-start d-block fw-bold required-field">{{ __('Nome em espanhol') }}</label>
                            <input id="nome_es" type="text" class="form-control @error('nome_es') is-invalid @enderror"
                                name="nome_es" value="{{ old('nome_es') }}" autocomplete="nome_es" autofocus>
                            @error('nome_es')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                            <br>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="@if ($eventoPai ?? '') col-md-3 @else col-md-4 @endif">
                            <label for="tipo" class="col-form-label text-start d-block fw-bold required-field">{{ __('Tipo') }}</label>
                            <select id="tipo" type="text" class="form-control @error('tipo') is-invalid @enderror"
                                name="tipo" required>
                                <option disabled selected hidden value="">-- {{ __('Tipo') }} --</option>
                                <option @if (old('tipo') == 'Congresso') selected @endif value="Congresso">Congresso</option>
                                <option @if (old('tipo') == 'Encontro') selected @endif value="Encontro">Encontro</option>
                                <option @if (old('tipo') == 'Seminário') selected @endif value="Seminário">Seminário</option>
                                <option @if (old('tipo') == 'Mesa redonda') selected @endif value="Mesa redonda">Mesa redonda</option>
                                <option @if (old('tipo') == 'Simpósio') selected @endif value="Simpósio">Simpósio</option>
                                <option @if (old('tipo') == 'Painel') selected @endif value="Painel">Painel</option>
                                <option @if (old('tipo') == 'Fórum') selected @endif value="Fórum">Fórum</option>
                                <option @if (old('tipo') == 'Conferência') selected @endif value="Conferência">Conferência</option>
                                <option @if (old('tipo') == 'Jornada') selected @endif value="Jornada">Jornada</option>
                                <option @if (old('tipo') == 'Cursos') selected @endif value="Cursos">Cursos</option>
                                <option @if (old('tipo') == 'Colóquio') selected @endif value="Colóquio">Colóquio</option>
                                <option @if (old('tipo') == 'Semana') selected @endif value="Semana">Semana</option>
                                <option @if (old('tipo') == 'Workshop') selected @endif value="Workshop">Workshop</option>
                                <option @if (old('tipo') == 'outro') selected @endif value="outro">Outro</option>
                            </select>

                            @error('tipo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="@if ($eventoPai ?? '') col-md-3 @else col-md-4 @endif">
                            <label for="recolhimento" class="col-form-label text-start d-block fw-bold required-field">{{ __('Recolhimento') }}</label>
                            <select name="recolhimento" id="recolhimento"
                                class="form-control @error('recolhimento') is-invalid @enderror">
                                @if (old('recolhimento') != null)
                                    <option @if (old('recolhimento') == '') selected @endif value="">-- Recolhimento --</option>
                                    <option @if (old('recolhimento') == 'apoiado') selected @endif value="apoiado">Apoiado</option>
                                    <option @if (old('recolhimento') == 'gratuito') selected @endif value="gratuito">Gratuito</option>
                                    <option @if (old('recolhimento') == 'pago') selected @endif value="pago">Pago</option>
                                @else
                                    <option value="">-- Recolhimento --</option>
                                    <option value="apoiado">Apoiado</option>
                                    <option value="gratuito">Gratuito</option>
                                    <option value="pago">Pago</option>
                                @endif
                            </select>

                            @error('recolhimento')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="instagram" class="col-form-label text-start d-block fw-bold">{{ __('ID do Instagram') }}</label>
                            <input class="form-control @error('instagram') is-invalid @enderror" type="text"
                                value="{{ old('instagram') }}" name="instagram" id="instagram" autofocus
                                autocomplete="instagram">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <br>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <!-- link do contato de suporte -->
                            <label for="contato_suporte" class="col-form-label text-start d-block fw-bold">{{ __('Link do contato de suporte') }}</label>
                            <input class="form-control @error('contato_suporte') is-invalid @enderror" type="text"
                                value="{{ old('contato_suporte') }}" name="contato_suporte" id="contato_suporte"
                                autocomplete="contato_suporte">
                            @error('contato_suporte')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <br>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="col-form-label">
                                <span class="fw-bold mb-3 d-block">{{ __('O seu evento será:') }}</span>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tipoEvento" id="online" value="online" checked>
                                        <label class="form-check-label" for="online">{{ __('Online') }}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tipoEvento" id="presencial" value="presencial">
                                        <label class="form-check-label" for="presencial">{{ __('Presencial') }}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tipoEvento" id="hibrido" value="hibrido">
                                        <label class="form-check-label" for="hibrido">{{ __('Híbrido') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_multilingual" name="is_multilingual">
                                <label class="form-check-label fw-bold" for="is_multilingual">{{ __('Evento Multilingue') }}</label>
                            </div>
                        </div>
                    </div>
                    <br>

                    <div class="form-group row">


                        @if ($eventoPai ?? '')
                            <div class="col-md-6">
                                <label for="email_coordenador" class="col-form-label text-start d-block fw-bold mb-3">{{ __('E-mail do coordenador') }}</label>
                                <input class="form-control @error('email_coordenador') is-invalid @enderror" type="email"
                                    value="{{ old('email_coordenador') }}" name="email_coordenador" id="email_coordenador">

                                @error('email_coordenador')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        @endif
                    </div>



                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="descricao" class="fw-bold required-field">{{ __('Descrição:') }}</label>
                                <textarea class="form-control mb-3 ckeditor-texto @error('descricao') is-invalid @enderror" required
                                    autocomplete="descricao" autofocus id="descricao" name="descricao" rows="8">{{ old('descricao') }}</textarea>
                            @error('descricao')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <br>

                    <div class="form-group row multilingual_fields" style="display: none;">
                        <div class="col-md-12">
                            <label for="descricao_en" class="fw-bold required-field">{{ __('Descrição em inglês') }}</label>
                                <textarea class="form-control ckeditor-texto @error('descricao_en') is-invalid @enderror" autocomplete="descricao_en" autofocus
                                    id="descricao_en" name="descricao_en" rows="8">{{ old('descricao_en') }}</textarea>
                            @error('descricao_en')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <br>

                    <div class="form-group row multilingual_fields" style="display: none;">
                        <div class="col-md-12">
                            <label for="exampleFormControlTextarea1" class="fw-bold required-field">{{__('Descrição em espanhol')}}</label>
                                <textarea class="form-control ckeditor-texto @error('descricao_es') is-invalid @enderror" autocomplete="descricao_es" autofocus
                                    id="descricao_es" name="descricao_es" rows="8">{{ old('descricao_es') }}</textarea>
                            @error('descricao_es')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <br>

                    <div class="form-group row">
                        <!-- BANNER -->
                        <div class="col-md-6">
                            <label for="fotoEvento" class="fw-bold mb-1 required-field">{{ __('Banner (tamanho: 1024 x 425, formato: JPEG, JPG e PNG):') }}</label>

                            <!-- Área de upload drag & drop -->
                            <div class="upload-area border border-2 border-dashed rounded-3 p-3 text-center position-relative"
                                style="min-height: 200px; border-color: #dee2e6 !important; cursor: pointer; transition: all 0.3s ease;"
                                onclick="document.getElementById('logo-input').click()"
                                ondrop="handleDrop(event, 'logo')"
                                ondragover="handleDragOver(event)"
                                ondragenter="handleDragEnter(event)"
                                ondragleave="handleDragLeave(event)">

                                <!-- Estado inicial - placeholder -->
                                <div id="logo-placeholder" class="upload-content d-flex flex-column align-items-center justify-content-center h-100">
                                    <i class="bi bi-cloud-upload fs-1 text-primary mb-2"></i>
                                    <h6 class="text-primary mb-2">Arraste sua imagem aqui</h6>
                                    <p class="text-muted small mb-2">ou clique para selecionar</p>
                                    <button type="button" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-upload me-1"></i>Escolher Banner
                                    </button>
                                    <small class="text-muted mt-2">1024 x 425px - JPEG, JPG, PNG</small>
                                </div>

                                <!-- Preview da imagem (substitui o imagem-loader) -->
                                <div id="logo-preview-container" class="d-none">
                                    <img id="logo-preview" class="img-fluid rounded" style="max-height: 180px;" src="" alt="Preview Banner">
                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2"
                                            onclick="removeImage('logo')" style="opacity: 0.8;">
                                        <i class="bi bi-x"></i>
                                    </button>
                                    <div class="mt-2">
                                        <small class="text-success"><i class="bi bi-check-circle me-1"></i>Banner carregado</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Input mantido exatamente como original -->
                            <div style="display: none;">
                                <input type="file" id="logo-input" class="form-control @error('fotoEvento') is-invalid @enderror"
                                    name="fotoEvento" value="{{ old('fotoEvento') }}" required
                                    accept=".jpeg,.jpg,.png" onchange="previewImage(this, 'logo')">
                            </div>

                            @error('fotoEvento')
                                <br>
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- ÍCONE -->
                        <div class="col-md-6">
                            <label for="icone" class="fw-bold mb-1 required-field">{{ __('Ícone (tamanho: 600 x 600, formato: JPEG, JPG e PNG):') }}</label>

                            <!-- Área de upload drag & drop -->
                            <div class="upload-area border border-2 border-dashed rounded-3 p-3 text-center position-relative"
                                style="min-height: 200px; border-color: #dee2e6 !important; cursor: pointer; transition: all 0.3s ease;"
                                onclick="document.getElementById('icone-input').click()"
                                ondrop="handleDrop(event, 'icone')"
                                ondragover="handleDragOver(event)"
                                ondragenter="handleDragEnter(event)"
                                ondragleave="handleDragLeave(event)">

                                <!-- Estado inicial - placeholder -->
                                <div id="icone-placeholder" class="upload-content d-flex flex-column align-items-center justify-content-center h-100">
                                    <i class="bi bi-cloud-upload fs-1 text-success mb-2"></i>
                                    <h6 class="text-success mb-2">Arraste seu ícone aqui</h6>
                                    <p class="text-muted small mb-2">ou clique para selecionar</p>
                                    <button type="button" class="btn btn-outline-success btn-sm">
                                        <i class="bi bi-upload me-1"></i>Escolher Ícone
                                    </button>
                                    <small class="text-muted mt-2">600 x 600px - JPEG, JPG, PNG</small>
                                </div>

                                <!-- Preview da imagem (substitui o imagem-loader-icone) -->
                                <div id="icone-preview-container" class="d-none">
                                    <img id="icone-preview" class="img-fluid rounded" style="max-height: 180px;" src="" alt="Preview Ícone">
                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2"
                                            onclick="removeImage('icone')" style="opacity: 0.8;">
                                        <i class="bi bi-x"></i>
                                    </button>
                                    <div class="mt-2">
                                        <small class="text-success"><i class="bi bi-check-circle me-1"></i>Ícone carregado</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Input mantido exatamente como original -->
                            <div style="display: none;">
                                <input type="file" id="icone-input" class="form-control @error('icone') is-invalid @enderror"
                                    name="icone" value="{{ old('icone') }}" required
                                    accept=".jpeg,.jpg,.png" onchange="previewImage(this, 'icone')">
                            </div>

                            @error('icone')
                                <br>
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <br>

                    <div class="form-group row multilingual_fields" style="display: none;">
                        <!-- BANNER INGLÊS -->
                        <div class="col-md-6">
                            <label for="fotoEvento_en" class="fw-bold mb-1 required-field">{{ __('Banner Inglês (tamanho: 1024 x 425, formato: JPEG, JPG e PNG):') }}</label>

                            <!-- Área de upload drag & drop -->
                            <div class="upload-area border border-2 border-dashed rounded-3 p-3 text-center position-relative"
                                style="min-height: 200px; border-color: #dee2e6 !important; cursor: pointer; transition: all 0.3s ease;"
                                onclick="document.getElementById('logo-input-en').click()"
                                ondrop="handleDrop(event, 'logo-en')"
                                ondragover="handleDragOver(event)"
                                ondragenter="handleDragEnter(event)"
                                ondragleave="handleDragLeave(event)">

                                <!-- Estado inicial - placeholder -->
                                <div id="logo-en-placeholder" class="upload-content d-flex flex-column align-items-center justify-content-center h-100">
                                    <i class="bi bi-cloud-upload fs-1 text-primary mb-2"></i>
                                    <h6 class="text-primary mb-2">Banner em Inglês</h6>
                                    <p class="text-muted small mb-2">Arraste ou clique para selecionar</p>
                                    <button type="button" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-upload me-1"></i>Escolher Banner EN
                                    </button>
                                    <small class="text-muted mt-2">1024 x 425px - JPEG, JPG, PNG</small>
                                </div>

                                <!-- Preview da imagem -->
                                <div id="logo-en-preview-container" class="d-none">
                                    <img id="logo-en-preview" class="img-fluid rounded" style="max-height: 180px;" src="" alt="Preview Banner EN">
                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2"
                                            onclick="removeImage('logo-en')" style="opacity: 0.8;">
                                        <i class="bi bi-x"></i>
                                    </button>
                                    <div class="mt-2">
                                        <small class="text-success"><i class="bi bi-check-circle me-1"></i>Banner EN carregado</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Input mantido exatamente como original -->
                            <div style="display: none;">
                                <input type="file" id="logo-input-en" class="form-control @error('fotoEvento_en') is-invalid @enderror"
                                    name="fotoEvento_en" value="{{ old('fotoEvento_en') }}"
                                    accept=".jpeg,.jpg,.png" onchange="previewImageDragDrop(this, 'logo-en')">
                            </div>

                            @error('fotoEvento_en')
                                <br>
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- ÍCONE INGLÊS -->
                        <div class="col-md-6">
                            <label for="icone_en" class="fw-bold mb-1 required-field">{{ __('Ícone Inglês (tamanho: 600 x 600, formato: JPEG, JPG e PNG):') }}</label>

                            <!-- Área de upload drag & drop -->
                            <div class="upload-area border border-2 border-dashed rounded-3 p-3 text-center position-relative"
                                style="min-height: 200px; border-color: #dee2e6 !important; cursor: pointer; transition: all 0.3s ease;"
                                onclick="document.getElementById('icone-input-en').click()"
                                ondrop="handleDrop(event, 'icone-en')"
                                ondragover="handleDragOver(event)"
                                ondragenter="handleDragEnter(event)"
                                ondragleave="handleDragLeave(event)">

                                <!-- Estado inicial - placeholder -->
                                <div id="icone-en-placeholder" class="upload-content d-flex flex-column align-items-center justify-content-center h-100">
                                    <i class="bi bi-cloud-upload fs-1 text-success mb-2"></i>
                                    <h6 class="text-success mb-2">Ícone em Inglês</h6>
                                    <p class="text-muted small mb-2">Arraste ou clique para selecionar</p>
                                    <button type="button" class="btn btn-outline-success btn-sm">
                                        <i class="bi bi-upload me-1"></i>Escolher Ícone EN
                                    </button>
                                    <small class="text-muted mt-2">600 x 600px - JPEG, JPG, PNG</small>
                                </div>

                                <!-- Preview da imagem -->
                                <div id="icone-en-preview-container" class="d-none">
                                    <img id="icone-en-preview" class="img-fluid rounded" style="max-height: 180px;" src="" alt="Preview Ícone EN">
                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2"
                                            onclick="removeImage('icone-en')" style="opacity: 0.8;">
                                        <i class="bi bi-x"></i>
                                    </button>
                                    <div class="mt-2">
                                        <small class="text-success"><i class="bi bi-check-circle me-1"></i>Ícone EN carregado</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Input mantido exatamente como original -->
                            <div style="display: none;">
                                <input type="file" id="icone-input-en" class="form-control @error('icone_en') is-invalid @enderror"
                                    name="icone_en" value="{{ old('icone_en') }}"
                                    accept=".jpeg,.jpg,.png" onchange="previewImageDragDrop(this, 'icone-en')">
                            </div>

                            @error('icone_en')
                                <br>
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <br>

                    <div class="form-group row multilingual_fields" style="display: none;">

                    <div class="col-md-6">
                            <label for="fotoEvento_es" class="fw-bold mb-1 required-field">{{ __('Banner Espanhol (tamanho: 1024 x 425, formato: JPEG, JPG e PNG):') }}</label>

                            <!-- Área de upload drag & drop -->
                            <div class="upload-area border border-2 border-dashed rounded-3 p-3 text-center position-relative"
                                style="min-height: 200px; border-color: #dee2e6 !important; cursor: pointer; transition: all 0.3s ease;"
                                onclick="document.getElementById('logo-input-es').click()"
                                ondrop="handleDrop(event, 'logo-es')"
                                ondragover="handleDragOver(event)"
                                ondragenter="handleDragEnter(event)"
                                ondragleave="handleDragLeave(event)">

                                <!-- Estado inicial - placeholder -->
                                <div id="logo-es-placeholder" class="upload-content d-flex flex-column align-items-center justify-content-center h-100">
                                    <i class="bi bi-cloud-upload fs-1 text-primary mb-2"></i>
                                    <h6 class="text-primary mb-2">Banner en Español</h6>
                                    <p class="text-muted small mb-2">Arrastra o haz clic para seleccionar</p>
                                    <button type="button" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-upload me-1"></i>Elegir Banner ES
                                    </button>
                                    <small class="text-muted mt-2">1024 x 425px - JPEG, JPG, PNG</small>
                                </div>

                                <!-- Preview da imagem -->
                                <div id="logo-es-preview-container" class="d-none">
                                    <img id="logo-es-preview" class="img-fluid rounded" style="max-height: 180px;" src="" alt="Preview Banner ES">
                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2"
                                            onclick="removeImage('logo-es')" style="opacity: 0.8;">
                                        <i class="bi bi-x"></i>
                                    </button>
                                    <div class="mt-2">
                                        <small class="text-success"><i class="bi bi-check-circle me-1"></i>Banner ES cargado</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Input mantido exatamente como original -->
                            <div style="display: none;">
                                <input type="file" id="logo-input-es" class="form-control @error('fotoEvento_es') is-invalid @enderror"
                                    name="fotoEvento_es" value="{{ old('fotoEvento_es') }}"
                                    accept=".jpeg,.jpg,.png" onchange="previewImageDragDrop(this, 'logo-es')">
                            </div>

                            @error('fotoEvento_es')
                                <br>
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- ÍCONE ESPANHOL -->
                        <div class="col-md-6">
                            <label for="icone_es" class="fw-bold mb-1 required-field">{{ __('Ícone Espanhol (tamanho: 600 x 600, formato: JPEG, JPG e PNG):') }}</label>

                            <!-- Área de upload drag & drop -->
                            <div class="upload-area border border-2 border-dashed rounded-3 p-3 text-center position-relative"
                                style="min-height: 200px; border-color: #dee2e6 !important; cursor: pointer; transition: all 0.3s ease;"
                                onclick="document.getElementById('icone-input-es').click()"
                                ondrop="handleDrop(event, 'icone-es')"
                                ondragover="handleDragOver(event)"
                                ondragenter="handleDragEnter(event)"
                                ondragleave="handleDragLeave(event)">

                                <!-- Estado inicial - placeholder -->
                                <div id="icone-es-placeholder" class="upload-content d-flex flex-column align-items-center justify-content-center h-100">
                                    <i class="bi bi-cloud-upload fs-1 text-success mb-2"></i>
                                    <h6 class="text-success mb-2">Ícono en Español</h6>
                                    <p class="text-muted small mb-2">Arrastra o haz clic para seleccionar</p>
                                    <button type="button" class="btn btn-outline-success btn-sm">
                                        <i class="bi bi-upload me-1"></i>Elegir Ícono ES
                                    </button>
                                    <small class="text-muted mt-2">600 x 600px - JPEG, JPG, PNG</small>
                                </div>

                                <!-- Preview da imagem -->
                                <div id="icone-es-preview-container" class="d-none">
                                    <img id="icone-es-preview" class="img-fluid rounded" style="max-height: 180px;" src="" alt="Preview Ícone ES">
                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2"
                                            onclick="removeImage('icone-es')" style="opacity: 0.8;">
                                        <i class="bi bi-x"></i>
                                    </button>
                                    <div class="mt-2">
                                        <small class="text-success"><i class="bi bi-check-circle me-1"></i>Ícono ES cargado</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Input mantido exatamente como original -->
                            <div style="display: none;">
                                <input type="file" id="icone-input-es" class="form-control @error('icone_es') is-invalid @enderror"
                                    name="icone_es" value="{{ old('icone_es') }}"
                                    accept=".jpeg,.jpg,.png" onchange="previewImageDragDrop(this, 'icone-es')">
                            </div>

                            <small class="text-muted mt-2">
                                {{ __('O arquivo será redimensionado para') }} 600 x 600<br>
                                {{ __('Formato') }}: JPEG, JPG, PNG
                            </small>

                            @error('icone_es')
                                <br>
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row form-group pb-4 pt-4">
                        <div class="col-md-10"></div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-primary w-100" style="background-color: #034652; color: white; border-color: #034652;" onclick="proximaEtapa()">
                                {{ __('Continuar') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Endereço e data -->
             <div id="etapa-2" style="display: none;">
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="etapas">
                            <div class="etapa">
                                <p>1. {{ __('Informações gerais') }}</p>
                            </div>
                            <div class="etapa ativa">
                                <p>2.{{ __('Endereço e data') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container card shadow">
                    <br>
                    {{-- Endereço --}}
                    <div class="form-group row">
                        <div class="col-md-6 form-group">
                           <label for="cep" class="col-form-label fw-bold required-field">{{ __('CEP') }}</label>
                           <input value="{{ old('cep') }}" onblur="pesquisacep(this.value);" id="cep"
                               name="cep" type="text" class="form-control @error('cep') is-invalid @enderror"
                               required autocomplete="cep">

                           @error('cep')
                               <span class="invalid-feedback" role="alert">
                                   <strong>{{ $message }}</strong>
                               </span>
                           @enderror
                       </div>

                        <div class="col-md-6">
                            <label for="rua" class="col-form-label text-start d-block fw-bold required-field">{{ __('Rua') }}</label>
                            <input value="{{old('rua')}}" id="rua" type="text" class="form-control @error('rua') is-invalid @enderror"
                            name="rua" autocomplete="new-password" required>

                            @error('rua')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ __($message) }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <br>

                    <div class="form-group row">
                        <div class="col-md-6">
                        <label for="numero" class="col-form-label text-start d-block fw-bold">{{ __('Número') }}</label>
                            <input id="numero" type="text" class="form-control @error('numero') is-invalid @enderror"
                                name="numero" value="{{ old('numero') }}" required autocomplete="numero" autofocus
                                maxlength="10">

                            @error('numero')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="bairro" class="col-form-label text-start d-block fw-bold required-field">{{ __('Bairro') }}</label>
                            <input value="{{old('bairro')}}" id="bairro" type="text" class="form-control @error('bairro') is-invalid @enderror"
                                name="bairro" autocomplete="bairro" required>

                            @error('bairro')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ __($message) }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <br>

                    <div class="col-md-12">
                        <label for="complemento" class="col-form-label text-start d-block fw-bold">{{ __('Complemento') }}</label>
                        <input type="text" value="{{old('complemento')}}" id="complemento"
                            class="form-control @error('complemento') is-invalid @enderror" name="complemento">

                        @error('complemento')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ __($message) }}</strong>
                            </span>
                        @enderror
                    </div>

                    <br>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="cidade" class="col-form-label text-start d-block fw-bold required-field">{{ __('Cidade') }}</label>
                            <input value="{{old('cidade')}}" id="cidade" type="text"
                                class="form-control apenasLetras @error('cidade') is-invalid @enderror"
                                name="cidade" autocomplete="cidade" required>

                            @error('cidade')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ __($message) }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group">
                        <label for="uf" class="col-form-label text-start d-block fw-bold required-field">{{ __('Estado') }}</label>
                        {{-- <input id="uf" type="text" class="form-control @error('uf') is-invalid @enderror" name="uf" value="{{ old('uf') }}" required autocomplete="uf" autofocus> --}}
                        <select class="form-control @error('uf') is-invalid @enderror" id="uf" name="uf">
                            <option value="" disabled selected hidden>-- UF --</option>
                            <option @if (old('uf') == 'AC') selected @endif value="AC">Acre
                            </option>
                            <option @if (old('uf') == 'AL') selected @endif value="AL">Alagoas
                            </option>
                            <option @if (old('uf') == 'AP') selected @endif value="AP">Amapá
                            </option>
                            <option @if (old('uf') == 'AM') selected @endif value="AM">Amazonas
                            </option>
                            <option @if (old('uf') == 'BA') selected @endif value="BA">Bahia
                            </option>
                            <option @if (old('uf') == 'CE') selected @endif value="CE">Ceará
                            </option>
                            <option @if (old('uf') == 'DF') selected @endif value="DF">Distrito
                                Federal</option>
                            <option @if (old('uf') == 'ES') selected @endif value="ES">Espírito
                                Santo</option>
                            <option @if (old('uf') == 'GO') selected @endif value="GO">Goiás
                            </option>
                            <option @if (old('uf') == 'MA') selected @endif value="MA">Maranhão
                            </option>
                            <option @if (old('uf') == 'MT') selected @endif value="MT">Mato Grosso
                            </option>
                            <option @if (old('uf') == 'MS') selected @endif value="MS">Mato Grosso
                                do Sul</option>
                            <option @if (old('uf') == 'MG') selected @endif value="MG">Minas Gerais
                            </option>
                            <option @if (old('uf') == 'PA') selected @endif value="PA">Pará
                            </option>
                            <option @if (old('uf') == 'PB') selected @endif value="PB">Paraíba
                            </option>
                            <option @if (old('uf') == 'PR') selected @endif value="PR">Paraná
                            </option>
                            <option @if (old('uf') == 'PE') selected @endif value="PE">Pernambuco
                            </option>
                            <option @if (old('uf') == 'PI') selected @endif value="PI">Piauí
                            </option>
                            <option @if (old('uf') == 'RJ') selected @endif value="RJ">Rio de
                                Janeiro</option>
                            <option @if (old('uf') == 'RN') selected @endif value="RN">Rio Grande
                                do Norte</option>
                            <option @if (old('uf') == 'RS') selected @endif value="RS">Rio Grande
                                do Sul</option>
                            <option @if (old('uf') == 'RO') selected @endif value="RO">Rondônia
                            </option>
                            <option @if (old('uf') == 'RR') selected @endif value="RR">Roraima
                            </option>
                            <option @if (old('uf') == 'SC') selected @endif value="SC">Santa
                                Catarina</option>
                            <option @if (old('uf') == 'SP') selected @endif value="SP">São Paulo
                            </option>
                            <option @if (old('uf') == 'SE') selected @endif value="SE">Sergipe
                            </option>
                            <option @if (old('uf') == 'TO') selected @endif value="TO">Tocantins
                            </option>
                        </select>

                        @error('uf')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    </div>

                    <br>

                    {{-- Datas do Evento --}}
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="dataInicio" class="col-form-label text-start d-block fw-bold required-field">{{ __('Data de início') }}</label>
                            <input id="dataInicio" type="date" class="form-control @error('dataInicio') is-invalid @enderror"
                                name="dataInicio" value="{{ old('dataInicio') }}" required autocomplete="dataInicio" autofocus>

                            @error('dataInicio')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="dataFim" class="col-form-label text-start d-block fw-bold required-field">{{ __('Data de término') }}</label>
                            <input id="dataFim" type="date" class="form-control @error('dataFim') is-invalid @enderror"
                                name="dataFim" value="{{ old('dataFim') }}" required autocomplete="dataFim" autofocus>

                            @error('dataFim')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <br>

                    <div class="form-group row align-items-center">
                        <div class="col-md-6">
                            <label for="dataLimiteInscricao" class="col-form-label text-start d-block fw-bold required-field">
                                {{ __('Data de encerramento de inscrições') }}
                            </label>
                            <input id="dataLimiteInscricao" type="datetime-local"
                                class="form-control @error('dataLimiteInscricao') is-invalid @enderror"
                                name="dataLimiteInscricao" value="{{ old('dataLimiteInscricao') }}"
                                autocomplete="dataLimiteInscricao" autofocus>
                            @error('dataLimiteInscricao')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-6 d-flex align-items-center">
                            <small class="mt-4">
                                <span style="color: red">{{ __('Atenção:') }}</span>
                                {{ __('Será no dia anterior a data do inicio do evento.') }}.
                            </small>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-check  pt-4 pb-3">
                            <input name="termos" class="form-check-input @error('termos') is-invalid @enderror"
                                type="checkbox" value="true" id="termos">
                            <label class="form-check-label " for="termos">
                                {{ __('Concordo e respeitarei os') }} <a href="{{ route('termos.de.uso') }}">{{ __('termos de uso') }}</a>
                                {{ __('da plataforma') }} {{ config('app.name') }}
                            </label>
                            @error('termos')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <br>

                    <div class="row form-group pb-4">
                        <div class="col-md-2">
                            <button type="button" class="btn btn-primary w-100" style="background-color: #034652; color: white; border-color: #034652;" onclick="etapaAnterior()">
                                {{ __('Voltar') }}
                            </button>
                        </div>
                        <div class="col-md-8"></div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100" style="background-color: #034652; color: white; border-color: #034652;">
                                @if ($eventoPai ?? '')
                                    {{ __('Criar Subevento') }}
                                @else
                                    {{ __('Criar Evento') }}
                                @endif
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection


@section('javascript')
    <script type="text/javascript">
        CKEDITOR.replaceAll('ckeditor-texto');
        $.fn.modal.Constructor.prototype._enforceFocus = function() {};
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // === FUNCIONALIDADE DRAG & DROP ===
            // Funções para drag & drop
            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            function handleDragOver(e) {
                preventDefaults(e);
                e.dataTransfer.dropEffect = 'copy';
            }

            function handleDragEnter(e) {
                preventDefaults(e);
                e.currentTarget.classList.add('dragover');
            }

            function handleDragLeave(e) {
                preventDefaults(e);
                e.currentTarget.classList.remove('dragover');
            }

            function handleDrop(e, type) {
                preventDefaults(e);
                e.currentTarget.classList.remove('dragover');

                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    const input = document.getElementById(type + '-input');
                    if (input) {
                        input.files = files;
                        // Trigger o evento change para manter compatibilidade com validação existente
                        const event = new Event('change', { bubbles: true });
                        input.dispatchEvent(event);
                    }
                }
            }

            function previewImageDragDrop(input, type) {
                if (input.files && input.files[0]) {
                    const file = input.files[0];

                    // Validar tipo de arquivo
                    if (!file.type.match(/^image\/(jpeg|jpg|png)$/)) {
                        alert('Por favor, selecione apenas arquivos JPEG, JPG ou PNG');
                        input.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    const preview = document.getElementById(type + '-preview');
                    const placeholder = document.getElementById(type + '-placeholder');
                    const previewContainer = document.getElementById(type + '-preview-container');

                    if (preview && placeholder && previewContainer) {
                        reader.onload = function(e) {
                            preview.src = e.target.result;
                            placeholder.classList.add('d-none');
                            previewContainer.classList.remove('d-none');
                        }
                        reader.readAsDataURL(file);
                    }
                }
            }

            function removeImage(type) {
                const input = document.getElementById(type + '-input');
                const placeholder = document.getElementById(type + '-placeholder');
                const previewContainer = document.getElementById(type + '-preview-container');

                if (input) input.value = '';
                if (placeholder) placeholder.classList.remove('d-none');
                if (previewContainer) previewContainer.classList.add('d-none');
            }

            // Expor funções globalmente para uso no HTML
            window.handleDragOver = handleDragOver;
            window.handleDragEnter = handleDragEnter;
            window.handleDragLeave = handleDragLeave;
            window.handleDrop = handleDrop;
            window.previewImageDragDrop = previewImageDragDrop;
            window.removeImage = removeImage;

            // === CONFIGURAÇÕES ORIGINAIS (MANTIDAS) ===
            // configurações para cada campo: nome do input, id do preview, dimensões máximas
            const fields = [
                { name: 'fotoEvento',      previewId: 'logo-preview',      loaderId: 'imagem-loader',          maxW: 1024, maxH: 425, message: 'O banner deve ter o tamanho de 1024×425px.' },
                { name: 'icone',           previewId: 'icone-preview',     loaderId: 'imagem-loader-icone',   maxW: 600,  maxH: 600, message: 'O ícone deve ter o tamanho de 600×600px.' },
                { name: 'fotoEvento_en',   previewId: 'logo-preview-en',   loaderId: 'imagem-loader-en',      maxW: 1024, maxH: 425, message: 'O banner (en) deve ter o tamanho de 1024×425px.' },
                { name: 'icone_en',        previewId: 'icone-preview-en',  loaderId: 'imagem-loader-icone-en', maxW: 600,  maxH: 600, message: 'O ícone (en) deve ter o tamanho de 600×600px.' },
                { name: 'fotoEvento_es',   previewId: 'logo-preview-es',   loaderId: 'imagem-loader-es',      maxW: 1024, maxH: 425, message: 'O banner (es) deve ter o tamanho de 1024×425px.' },
                { name: 'icone_es',        previewId: 'icone-preview-es',  loaderId: 'imagem-loader-icone-es', maxW: 600,  maxH: 600, message: 'O ícone (es) deve ter o tamanho de 600×600px.' },
            ];

            fields.forEach(field => {
                const input  = document.querySelector(`input[name="${field.name}"]`);
                const preview= document.getElementById(field.previewId);
                const loader = document.getElementById(field.loaderId);

                // Se não existir os elementos, skip (compatibilidade com diferentes páginas)
                if (!input || !preview) return;

                // guardar src padrão para restaurar em caso de erro
                const defaultSrc = preview.src;

                // clicar no loader abre o filepicker (MANTIDO)
                if (loader) {
                    loader.addEventListener('click', () => input.click());
                }

                // === INTEGRAÇÃO: Compatibilidade drag & drop com validação existente ===
                input.addEventListener('change', function() {
                    clearError();  // limpa mensagens antigas
                    const file = this.files[0];
                    if (!file) return;

                    // checa tipo
                    if (!['image/jpeg','image/png','image/jpg'].includes(file.type)) {
                        showError('Formato inválido. Só JPEG, JPG ou PNG.');
                        reset();
                        return;
                    }

                    // lê como DataURL
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = new Image();
                        img.onload = function() {
                            // checa dimensões
                            if (img.width > field.maxW || img.height > field.maxH) {
                                showError(field.message);
                                reset();
                            } else {
                                preview.src = e.target.result;  // mostra o preview

                                // === NOVA FUNCIONALIDADE: Atualizar interface drag & drop ===
                                const placeholder = document.getElementById(field.name.replace('fotoEvento', 'logo').replace('icone', 'icone') + '-placeholder');
                                const previewContainer = document.getElementById(field.name.replace('fotoEvento', 'logo').replace('icone', 'icone') + '-preview-container');

                                if (placeholder && previewContainer) {
                                    placeholder.classList.add('d-none');
                                    previewContainer.classList.remove('d-none');
                                    const dragDropPreview = document.getElementById(field.name.replace('fotoEvento', 'logo').replace('icone', 'icone') + '-preview');
                                    if (dragDropPreview) {
                                        dragDropPreview.src = e.target.result;
                                    }
                                }
                            }
                        };
                        img.src = e.target.result;
                    };
                    reader.readAsDataURL(file);

                    function reset() {
                        input.value = '';
                        preview.src = defaultSrc;

                        // === NOVA FUNCIONALIDADE: Reset interface drag & drop ===
                        const placeholder = document.getElementById(field.name.replace('fotoEvento', 'logo').replace('icone', 'icone') + '-placeholder');
                        const previewContainer = document.getElementById(field.name.replace('fotoEvento', 'logo').replace('icone', 'icone') + '-preview-container');

                        if (placeholder && previewContainer) {
                            placeholder.classList.remove('d-none');
                            previewContainer.classList.add('d-none');
                        }
                    }

                    function showError(msg) {
                        input.classList.add('is-invalid');
                        const parent = input.closest('.col-md-6');
                        let err = parent.querySelector('.js-error');
                        if (!err) {
                            err = document.createElement('div');
                            err.className = 'invalid-feedback d-block js-error';
                            parent.appendChild(err);
                        }
                        err.textContent = msg;
                    }

                    function clearError() {
                        input.classList.remove('is-invalid');
                        const parent = input.closest('.col-md-6');
                        const err = parent.querySelector('.js-error');
                        if (err) err.remove();
                    }
                });
            });
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function($) {

            // $('#summernote').summernote(
            //     {
            //     toolbar: [
            //         // [groupName, [list of button]]
            //         ['style', ['bold', 'italic', 'underline', 'clear']],
            //         ['font', ['superscript', 'subscript']],//'strikethrough',
            //         // ['fontsize', ['fontsize']],
            //         ['color', ['color']],
            //         // ['para', ['ul', 'ol', 'paragraph']],
            //         // ['height', ['height']]
            //     ]

            // }
            // );
            //CKEDITOR.replace( 'descricao' );
            $('#cep').mask('00000-000');
            $(".apenasLetras").mask("#", {
                maxlength: false,
                translation: {
                    '#': {
                        pattern: /[A-zÀ-ÿ ]/,
                        recursive: true
                    }
                }
            });
            /*$('#numero').mask('#', {
                maxlength: false,
                translation: {
                    '#': {pattern: /[0-9\\s/n]/, recursive: true}
                }
            });*/

            $('#is_multilingual').change(function() {
                const isChecked = $(this).is(':checked');
                $('.multilingual_fields').toggle(isChecked); // Show/hide the whole section

                // For all inputs and textareas within multilingual_fields
                $('.multilingual_fields').find('input[type="text"], textarea, input[type="file"]').each(function() {
                    const campoMulti = $(this);
                    campoMulti.prop('required', isChecked); // Set required based on checkbox

                    if (!isChecked) { // If checkbox is unchecked (fields are hidden and not required)
                        campoMulti.removeClass('is-invalid'); // Remove validation class from input/textarea
                        if (campoMulti.is('input[type="file"]')) {
                            const loaderDiv = campoMulti.parent().prev('.imagem-loader');
                            if (loaderDiv.length) {
                                loaderDiv.removeClass('border border-danger'); // Remove error style from loader
                            }
                        }
                        // Opcional: $(this).val(''); // Limpar valor ao desmarcar (cuidado com file inputs)
                    }
                });
            }).trigger('change'); // Apply on page load

            // === CÓDIGO ORIGINAL MANTIDO (compatibilidade com código legado) ===
            $('#imagem-loader').click(function() {
                $('#logo-input').change(function() {
                    if (this.files && this.files[0]) {
                        var file = new FileReader();
                        file.onload = function(e) {
                            document.getElementById("logo-preview").src = e.target.result;
                        };
                        file.readAsDataURL(this.files[0]);
                    }
                })
            });

            $('#imagem-loader-icone').click(function() {
                $('#icone-input').change(function() {
                    if (this.files && this.files[0]) {
                        var file = new FileReader();
                        file.onload = function(e) {
                            document.getElementById("icone-preview").src = e.target.result;
                        };
                        file.readAsDataURL(this.files[0]);
                    }
                })
            });

            $('#imagem-loader-en').click(function() {
                $('#logo-input-en').change(function() {
                    if (this.files && this.files[0]) {
                        var file = new FileReader();
                        file.onload = function(e) {
                            document.getElementById("logo-preview-en").src = e.target.result;
                        };
                        file.readAsDataURL(this.files[0]);
                    }
                })
            });

            $('#imagem-loader-icone-en').click(function() {
                $('#icone-input-en').change(function() {
                    if (this.files && this.files[0]) {
                        var file = new FileReader();
                        file.onload = function(e) {
                            document.getElementById("icone-preview-en").src = e.target.result;
                        };
                        file.readAsDataURL(this.files[0]);
                    }
                })
            });

            $('#imagem-loader-es').click(function() {
                $('#logo-input-es').change(function() {
                    if (this.files && this.files[0]) {
                        var file = new FileReader();
                        file.onload = function(e) {
                            document.getElementById("logo-preview-es").src = e.target.result;
                        };
                        file.readAsDataURL(this.files[0]);
                    }
                })
            });

            $('#imagem-loader-icone-es').click(function() {
                $('#icone-input-es').change(function() {
                    if (this.files && this.files[0]) {
                        var file = new FileReader();
                        file.onload = function(e) {
                            document.getElementById("icone-preview-es").src = e.target.result;
                        };
                        file.readAsDataURL(this.files[0]);
                    }
                })
            });
        });

        // === FUNÇÕES ORIGINAIS MANTIDAS ===
        function limpa_formulário_cep() {
            //Limpa valores do formulário de cep.
            document.getElementById('rua').value = ("");
            document.getElementById('bairro').value = ("");
            document.getElementById('cidade').value = ("");
            document.getElementById('uf').value = ("");
        }

        function meu_callback(conteudo) {
            if (!("erro" in conteudo)) {
                //Atualiza os campos com os valores.
                document.getElementById('rua').value = (conteudo.logradouro);
                document.getElementById('bairro').value = (conteudo.bairro);
                document.getElementById('cidade').value = (conteudo.localidade);
                document.getElementById('uf').value = (conteudo.uf);
            } //end if.
            else {
                //CEP não Encontrado.
                limpa_formulário_cep();
                alert("CEP não encontrado.");
            }
        }

        function pesquisacep(valor) {
            //Nova variável "cep" somente com dígitos.
            var cep = valor.replace(/\D/g, '');
            //Verifica se campo cep possui valor informado.
            if (cep != "") {
                //Expressão regular para validar o CEP.
                var validacep = /^[0-9]{8}$/;
                //Valida o formato do CEP.
                if (validacep.test(cep)) {
                    //Preenche os campos com "..." enquanto consulta webservice.
                    document.getElementById('rua').value = "...";
                    document.getElementById('bairro').value = "...";
                    document.getElementById('cidade').value = "...";
                    document.getElementById('uf').value = "...";
                    //Cria um elemento javascript.
                    var script = document.createElement('script');
                    //Sincroniza com o callback.
                    script.src = 'https://viacep.com.br/ws/' + cep + '/json/?callback=meu_callback';
                    //Insere script no documento e carrega o conteúdo.
                    document.body.appendChild(script);
                } //end if.
                else {
                    //cep é inválido.
                    limpa_formulário_cep();
                    alert("Formato de CEP inválido.");
                }
            } //end if.
            else {
                //cep sem valor, limpa formulário.
                limpa_formulário_cep();
            }
        };

        function proximaEtapa() {
            let primeiroCampoInvalido = null;
            let formEtapa1Valido = true;

            // Seleciona todos os campos que podem ser validados na etapa 1
            const camposEtapa1 = $('#etapa-1').find('input, select, textarea');

            camposEtapa1.each(function() {
                const campo = $(this);
                // Limpa a validação anterior do Bootstrap (se houver)
                campo.removeClass('is-invalid');
                if (campo.is('input[type="file"]')) {
                    const loaderDiv = campo.parent().prev('.imagem-loader');
                    if (loaderDiv.length) {
                        loaderDiv.removeClass('border border-danger'); // Clear previous error style
                    }
                }

                if (campo.prop('required')) { // Verifica se o campo está marcado como 'required'
                    let isFieldValid = true;
                    let fieldIsEffectivelyVisible = campo.is(':visible');

                    // Para campos de arquivo, a visibilidade efetiva depende se sua seção (multilíngue ou não) está visível.
                    if (campo.is('input[type="file"]')) {
                        const multilingualParent = campo.closest('.multilingual_fields');
                        if (multilingualParent.length > 0) { // É um campo de arquivo multilíngue
                            fieldIsEffectivelyVisible = multilingualParent.is(':visible');
                        } else { // É um campo de arquivo principal (não multilíngue)
                            fieldIsEffectivelyVisible = true; // Considerado sempre visível para validação se 'required'
                        }
                    }

                    if (fieldIsEffectivelyVisible) { // Apenas valida se o campo (ou seu controlador) está visível
                        if (campo.is('input[type="file"]')) {
                            if (this.files.length === 0) {
                                isFieldValid = false;
                                campo.addClass('is-invalid'); // Adiciona classe ao próprio input
                                const loaderDiv = campo.parent().prev('.imagem-loader');
                                if (loaderDiv.length) {
                                    loaderDiv.addClass('border border-danger'); // Adiciona borda ao visualizador
                                    if (!primeiroCampoInvalido) {
                                        primeiroCampoInvalido = loaderDiv; // Prioriza o visualizador para foco/scroll
                                    }
                                } else if (!primeiroCampoInvalido) {
                                    primeiroCampoInvalido = campo; // Fallback para o input se o visualizador não for encontrado
                                }
                            }
                        } else { // Para text, select, textarea
                            if (!this.checkValidity()) { // Usa a validação nativa do navegador
                                isFieldValid = false;
                                campo.addClass('is-invalid');
                                if (!primeiroCampoInvalido) {
                                    primeiroCampoInvalido = campo;
                                }
                            }
                        }
                    }
                    // Se o campo não for válido E era para ser validado (visível e obrigatório), marca o formulário como inválido.
                    if (!isFieldValid && fieldIsEffectivelyVisible) {
                        formEtapa1Valido = false;
                    }
                }
            });

            if (formEtapa1Valido) {
                document.getElementById('etapa-1').style.display = 'none';
                document.getElementById('etapa-2').style.display = 'block';
                // Atualiza a interface de etapas
                $('.etapas .etapa').removeClass('ativa');
                $('.etapas .etapa').eq(1).addClass('ativa');
                window.scrollTo(0, 0); // Rola para o topo para o usuário ver a nova etapa
            } else {
                if (primeiroCampoInvalido) {
                    // Rola para o elemento e tenta focar
                    if (primeiroCampoInvalido.is('div.imagem-loader')) { // Se for o nosso visualizador de imagem
                        $('html, body').animate({
                            scrollTop: primeiroCampoInvalido.offset().top - 100 // Ajuste o offset conforme necessário
                        }, 500);
                        // Adicionar tabindex para tornar o div focável pode ser uma opção para leitores de tela
                        // primeiroCampoInvalido.attr('tabindex', -1).focus();
                    } else {
                        primeiroCampoInvalido.focus(); // Foca em inputs de texto, select, etc.
                    }
                }
            }
        }

        function etapaAnterior() {
            document.getElementById('etapa-1').style.display = 'block';
            document.getElementById('etapa-2').style.display = 'none';
            // Atualiza a interface de etapas
            $('.etapas .etapa').removeClass('ativa'); // Remove de todas
            $('.etapas .etapa').eq(0).addClass('ativa'); // Ativa a primeira etapa
            window.scrollTo(0, 0);
        }

    </script>
@endsection
