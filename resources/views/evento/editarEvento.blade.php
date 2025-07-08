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
    </style>

    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="text-start" style="color: #034652; font-weight: bold;">
                    @if ($eventoPai ?? '')
                        {{ __('Editar Subevento') }}
                    @else
                        {{ __('Editar Evento') }}
                    @endif
                </h2>
            </div>
        </div>

        <form action="{{route('evento.update',['id' => $evento->id])}}" method="POST" enctype="multipart/form-data">
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
                                <p>2. {{__('Endereço e data')}}</p>
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
                    <div class="row">
                        <div class="form-check col-sm-12 form-group">
                            <div class="form-check col-sm-12 form-group">
                                <input class="form-check-input" type="checkbox" id="is_multilingual" name="is_multilingual"
                                 value="1" {{ old('is_multilingual', $evento->is_multilingual ? '1' : '') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label text-start d-block" for="is_multilingual">{{ __('Evento Multilingue') }}</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="nome" class="col-form-label text-start d-block fw-bold mb-3 required-field">{{ __('Nome do evento') }}</label>
                            <input id="nome" type="text" class="form-control @error('nome') is-invalid @enderror"
                                name="nome" value="{{ old('nome', $evento->nome) }}" required autocomplete="nome" autofocus>
                            <div id="erro-nome" class="text-danger mt-1" style="display: none;">
                                <small>{{ __('O nome do evento é obrigatório') }}</small>
                            </div>

                            @error('nome')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="col-form-label text-start d-block fw-bold mb-3 required-field">{{ __('E-mail de contato') }}</label>
                            <input class="form-control @error('email') is-invalid @enderror" type="email"
                                value="{{ old('email', $evento->email) }}" name="email" id="email" required autofocus
                                autocomplete="email">
                            <div id="erro-email" class="text-danger mt-1" style="display: none;">
                                <small>{{ __('O e-mail de contato é obrigatório') }}</small>
                            </div>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                         @if($evento->evento_pai_id != null)
                            <div class="col-sm-6 form-group">
                                <label for="email_coordenador" class="col-form-label">{{ __('E-mail do coordenador') }}</label>
                                @if($evento->coordenadoresEvento()->exists())
                                <input class="form-control @error('email_coordenador') is-invalid @enderror" type="email" value="{{old('email_coordenador', $evento->coordenadoresEvento()->first()->email)}}" name="email_coordenador" id="email_coordenador">
                                @else
                                <input class="form-control @error('email_coordenador') is-invalid @enderror" type="email" value="{{old('email_coordenador')}}" name="email_coordenador" id="email_coordenador">
                                @endif

                                @error('email_coordenador')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        @endif
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6 multilingual_fields" style="display: none;">
                            <label for="nome_en" class="col-form-label text-start d-block fw-bold mb-3 required-field">{{ __('Nome em inglês') }}</label>
                            <input id="nome_en" type="text" class="form-control @error('nome_en') is-invalid @enderror"
                                name="nome_en" value="{{ old('nome_en', $evento->nome_en) }}" autocomplete="nome_en" autofocus>

                            @error('nome_en')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-6 multilingual_fields" style="display: none;">
                            <label for="nome_es" class="col-form-label text-start d-block fw-bold mb-3 required-field">{{ __('Nome em espanhol') }}</label>
                            <input id="nome_es" type="text" class="form-control @error('nome_es') is-invalid @enderror"
                                name="nome_es" value="{{ old('nome_es', $evento->nome_es) }}" autocomplete="nome_es" autofocus>
                            @error('nome_es')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="@if ($eventoPai ?? '') col-md-3 @else col-md-4 @endif">
                            <label for="tipo" class="col-form-label text-start d-block fw-bold mb-3 required-field">{{ __('Tipo') }}</label>
                             <select id="tipo" type="text" class="form-control @error('tipo') is-invalid @enderror" name="tipo" required>
                            @if (old('tipo') != null)
                            <option @if(old('tipo')=="Congresso" ) selected @endif value="Congresso">Congresso</option>
                            <option @if(old('tipo')=="Encontro" ) selected @endif value="Encontro">Encontro</option>
                            <option @if(old('tipo')=="Seminário" ) selected @endif value="Seminário">Seminário</option>
                            <option @if(old('tipo')=="Mesa redonda" ) selected @endif value="Mesa redonda">Mesa redonda</option>
                            <option @if(old('tipo')=="Simpósio" ) selected @endif value="Simpósio">Simpósio</option>
                            <option @if(old('tipo')=="Painel" ) selected @endif value="Painel">Painel</option>
                            <option @if(old('tipo')=="Fórum" ) selected @endif value="Fórum">Fórum</option>
                            <option @if(old('tipo')=="Conferência" ) selected @endif value="Conferência">Conferência</option>
                            <option @if(old('tipo')=="Jornada" ) selected @endif value="Jornada">Jornada</option>
                            <option @if(old('tipo')=="Cursos" ) selected @endif value="Cursos">Cursos</option>
                            <option @if(old('tipo')=="Colóquio" ) selected @endif value="Colóquio">Colóquio</option>
                            <option @if(old('tipo')=="Semana" ) selected @endif value="Semana">Semana</option>
                            <option @if(old('tipo')=="Workshop" ) selected @endif value="Workshop">Workshop</option>
                            <option @if(old('tipo')=="outro" ) selected @endif value="outro">Outro</option>
                            @else
                            <option @if($evento->tipo == "Congresso") selected @endif value="Congresso">Congresso</option>
                            <option @if($evento->tipo == "Encontro") selected @endif value="Encontro">Encontro</option>
                            <option @if($evento->tipo == "Seminário") selected @endif value="Seminário">Seminário</option>
                            <option @if($evento->tipo == "Mesa redonda") selected @endif value="Mesa redonda">Mesa redonda</option>
                            <option @if($evento->tipo == "Simpósio") selected @endif value="Simpósio">Simpósio</option>
                            <option @if($evento->tipo == "Painel") selected @endif value="Painel">Painel</option>
                            <option @if($evento->tipo == "Fórum") selected @endif value="Fórum">Fórum</option>
                            <option @if($evento->tipo == "Conferência") selected @endif value="Conferência">Conferência</option>
                            <option @if($evento->tipo == "Jornada") selected @endif value="Jornada">Jornada</option>
                            <option @if($evento->tipo == "Cursos") selected @endif value="Cursos">Cursos</option>
                            <option @if($evento->tipo == "Colóquio") selected @endif value="Colóquio">Colóquio</option>
                            <option @if($evento->tipo == "Semana") selected @endif value="Semana">Semana</option>
                            <option @if($evento->tipo == "Workshop") selected @endif value="Workshop">Workshop</option>
                            <option @if($evento->tipo == "outro") selected @endif value="outro">Outro</option>
                            @endif
                        </select>

                            @error('tipo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="@if ($eventoPai ?? '') col-md-3 @else col-md-4 @endif">
                            <label for="recolhimento" class="col-form-label text-start d-block fw-bold mb-3 required-field">{{ __('Recolhimento') }}</label>
                             <select name="recolhimento" id="recolhimento" class="form-control @error('recolhimento') is-invalid @enderror">
                            @if (old('recolhimento') != null)
                            <option @if(old('recolhimento')=="apoiado" ) selected @endif value="apoiado">Apoiado</option>
                            <option @if(old('recolhimento')=="gratuito" ) selected @endif value="gratuito">Gratuito</option>
                            <option @if(old('recolhimento')=="pago" ) selected @endif value="pago">Pago</option>
                            @else
                            <option @if($evento->recolhimento == "apoiado") selected @endif value="apoiado">Apoiado</option>
                            <option @if($evento->recolhimento == "gratuito") selected @endif value="gratuito">Gratuito</option>
                            <option @if($evento->recolhimento == "pago") selected @endif value="pago">Pago</option>
                            @endif
                        </select>

                            @error('recolhimento')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="instagram" class="col-form-label text-start d-block fw-bold mb-3">{{ __('ID do Instagram') }}</label>
                            <input class="form-control @error('instagram') is-invalid @enderror" type="text"
                                value="{{ old('instagram', $evento->instagram) }}" name="instagram" id="instagram" autofocus
                                autocomplete="instagram">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <!-- link do contato de suporte -->
                            <label for="contato_suporte" class="col-form-label text-start d-block fw-bold mb-3">{{ __('Link do contato de suporte') }}</label>
                            <input class="form-control @error('contato_suporte') is-invalid @enderror" type="text"
                                value="{{ old('contato_suporte', $evento->contato_suporte) }}" name="contato_suporte" id="contato_suporte"
                                autocomplete="contato_suporte">
                            @error('contato_suporte')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="custom-control custom-radio custom-control-inline col-form-label">
                                <span class="fw-bold mb-3">{{ __('O seu evento será:') }}</span> <br>
                                <input type="radio" id="customRadioInline1" name="customRadioInline" class="custom-control-input" checked>
                                <label class="custom-control-label me-2" for="customRadioInline1">{{ __('Online') }}</label>

                                <input type="radio"  name="customRadioInline" class="custom-control-input">
                                <label class="custom-control-label me-2" for="customRadioInline2">{{__('Presencial')}}</label>

                                <input type="radio" name="customRadioInline" class="custom-control-input">
                                <label class="custom-control-label " for="customRadioInline3">{{__('Híbrido')}}</label>
                            </div>
                        </div>


                    </div>

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
                            <label for="descricao" class="fw-bold mb-3 ">{{ __('Descrição:') }}</label>
                            <textarea class="form-control mb-3 @error('descricao') is-invalid @enderror required-field" required
                                autocomplete="descricao" autofocus id="descricao" name="descricao" rows="8">
                                @if(old('descricao') != null) {{ old('descricao') }} @else {{$evento->descricao}} @endif</textarea>
                            <div id="erro-descricao" class="text-danger mt-1" style="display: none;">
                                <small>{{ __('A descrição é obrigatória') }}</small>
                            </div>
                            @error('descricao')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row multilingual_fields" style="display: none;">
                        <div class="col-md-12">
                            <label for="descricao_en" class="fw-bold mb-3 required-field">{{ __('Descrição em inglês') }}</label>
                            <textarea class="form-control @error('descricao_en') is-invalid @enderror" autocomplete="descricao_en" autofocus
                                id="descricao_en" name="descricao_en" rows="8">
                                @if(old('descricao_en') != null) {{ old('descricao_en') }} @else {{$evento->descricao_en}} @endif</textarea>
                            @error('descricao_en')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row multilingual_fields" style="display: none;">
                        <div class="col-md-12">
                            <label for="exampleFormControlTextarea1" class="fw-bold mb-3 required-field">{{__('Descrição em espanhol')}}</label>
                            <textarea class="form-control @error('descricao_es') is-invalid @enderror" autocomplete="descricao_es" autofocus
                                id="descricao_es" name="descricao_es" rows="8">
                                @if(old('descricao_es') != null){{ old('descricao_en') }} @else {{$evento->descricao_en}} @endif</textarea>
                            @error('descricao_es')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="fotoEvento" class="fw-bold mb-3">{{ __('Banner (tamanho: 1024 x 425, formato: JPEG, JPG e PNG):') }}</label>
                            <div id="imagem-loader" class="imagem-loader">
                                @if ($evento->fotoEvento != null)
                                        <img id="logo-preview" class="img-fluid" src="{{asset('storage/'.$evento->fotoEvento)}}" alt="">
                                    @else
                                        <img id="logo-preview" class="img-fluid" src="{{asset('/img/nova_imagem.PNG')}}" alt="">
                                    @endif
                            </div>
                            <div style="display: none;">
                                <input type="file" id="logo-input" class="form-control @error('fotoEvento') is-invalid @enderror"
                                    name="fotoEvento" value="{{ old('fotoEvento') }}" id="fotoEvento">
                            </div>
                            @error('fotoEvento')
                                <br>
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="icone" class="fw-bold mb-3">{{ __('Ícone (tamanho: 600 x 600, formato: JPEG, JPG e PNG):') }}</label>
                            <div id="imagem-loader-icone" class="imagem-loader">
                                @if ($evento->icone != null)
                                        <img id="icone-preview" class="img-fluid" src="{{asset('storage/'.$evento->icone)}}" alt="">
                                    @else
                                        <img id="icone-preview" class="img-fluid" src="{{asset('/img/nova_imagem.PNG')}}" alt="">
                                @endif
                            </div>
                            <div style="display: none;">
                                <input type="file" id="icone-input" class="form-control @error('icone') is-invalid @enderror"
                                    name="icone" value="{{ old('icone') }}" id="icone">
                            </div>
                            @error('icone')
                                <br>
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row multilingual_fields" style="display: none;">
                        <div class="col-md-6">
                            <label for="fotoEvento_en" class="fw-bold mb-3">{{ __('Banner Inglês') }}</label>
                            <div id="imagem-loader-en" class="imagem-loader">
                                @if ($evento->fotoEvento_en != null)
                                        <img id="logo-preview" class="img-fluid" src="{{asset('storage/'.$evento->fotoEvento_en)}}" alt="">
                                    @else
                                        <img id="logo-preview" class="img-fluid" src="{{asset('/img/nova_imagem.PNG')}}" alt="">
                                @endif
                            </div>
                            <div style="display: none;">
                                <input type="file" id="logo-input-en" class="form-control @error('fotoEvento_en') is-invalid @enderror"
                                    name="fotoEvento_en" value="{{ old('fotoEvento_en') }}" id="fotoEvento_en">
                            </div>
                            @error('fotoEvento_en')
                                <br>
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="icone_en" class="fw-bold mb-3">{{ __('Ícone inglês') }}</label>
                            <div id="imagem-loader-icone-en" class="imagem-loader">
                                @if ($evento->icone_en != null)
                                        <img id="icone-preview" class="img-fluid" src="{{asset('storage/'.$evento->icone_en)}}" alt="">
                                    @else
                                        <img id="icone-preview" class="img-fluid" src="{{asset('/img/nova_imagem.PNG')}}" alt="">
                                @endif
                            </div>
                            <div style="display: none;">
                                <input type="file" id="icone-input-en" class="form-control @error('icone_en') is-invalid @enderror"
                                    name="icone_en" value="{{ old('icone_en') }}" id="icone_en">
                            </div>
                            <small style="position: relative; top: 5px;">{{ __('O arquivo será redimensionado para') }} 600 x 600;<br>{{ __('Formato') }}: JPEG, JPG, PNG</small>
                            @error('icone_en')
                                <br>
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row multilingual_fields" style="display: none;">
                        <div class="col-md-6">
                            <label for="fotoEvento_es" class="fw-bold mb-3">{{ __('Banner Espanhol') }}</label>
                            <div id="imagem-loader-es" class="imagem-loader">
                                @if ($evento->fotoEvento_es != null)
                                        <img id="logo-preview" class="img-fluid" src="{{asset('storage/'.$evento->fotoEvento_es)}}" alt="">
                                    @else
                                        <img id="logo-preview" class="img-fluid" src="{{asset('/img/nova_imagem.PNG')}}" alt="">
                                @endif
                            </div>
                            <div style="display: none;">
                                <input type="file" id="logo-input-es" class="form-control @error('fotoEvento_es') is-invalid @enderror"
                                    name="fotoEvento_es" value="{{ old('fotoEvento_es') }}" id="fotoEvento_es">
                            </div>
                            <small style="position: relative; top: 5px;">{{ __('Tamanho minimo') }}: 1024 x 425;<br>{{ __('Formato') }}: JPEG, JPG, PNG</small>
                            @error('fotoEvento_es')
                                <br>
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="icone_es" class="fw-bold mb-3">{{__('Ícone espanhol')}}</label>
                            <div id="imagem-loader-icone-es" class="imagem-loader">
                                @if ($evento->icone_es != null)
                                        <img id="icone-preview" class="img-fluid" src="{{asset('storage/'.$evento->icone_es)}}" alt="">
                                    @else
                                        <img id="icone-preview" class="img-fluid" src="{{asset('/img/nova_imagem.PNG')}}" alt="">
                                @endif
                            </div>
                            <div style="display: none;">
                                <input type="file" id="icone-input-es" class="form-control @error('icone_es') is-invalid @enderror"
                                    name="icone_es" value="{{ old('icone_es') }}" id="icone_es">
                            </div>
                            <small style="position: relative; top: 5px;">{{ __('O arquivo será redimensionado para') }} 600 x 600;<br>{{ __('Formato') }}: JPEG, JPG, PNG</small>
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
                                <p>2. {{ __('Endereço e data') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container card shadow">
                    <br>
                    {{-- Endereço --}}
                    <div class="form-group row">
                        <div class="col-md-6 form-group">
                           <label for="cep" class="col-form-label">{{ __('CEP') }}*</label>
                           <input value="{{ old('cep', $endereco->cep) }}" onblur="pesquisacep(this.value);" id="cep"
                               name="cep" type="text" class="form-control @error('cep') is-invalid @enderror"
                               required autocomplete="cep">

                           @error('cep')
                               <span class="invalid-feedback" role="alert">
                                   <strong>{{ $message }}</strong>
                               </span>
                           @enderror
                       </div>

                        <div class="col-md-6">
                            <label for="rua" class="col-form-label text-start d-block fw-bold ">{{ __('Rua') }}</label>
                            <input value="{{ old('rua', $endereco->rua) }}" id="rua" type="text" class="form-control @error('rua') is-invalid @enderror"
                            name="rua" autocomplete="new-password" required>

                            @error('rua')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ __($message) }}</strong>
                            </span>
                            @enderror
                        </div>

                    </div>



                        <div class="form-group row">
                            <div class="col-md-6">
                            <label for="numero" class="col-form-label text-start d-block fw-bold mb-3">{{ __('Número') }}</label>
                                <input id="numero" type="text" class="form-control @error('numero') is-invalid @enderror"
                                    name="numero" value="{{ old('numero', $endereco->numero) }}" required autocomplete="numero" autofocus
                                    maxlength="10">

                                @error('numero')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="bairro" class="col-form-label text-start d-block fw-bold mb-3">{{ __('Bairro') }}</label>
                                <input value="{{ old('bairro', $endereco->bairro) }}" id="bairro" type="text" class="form-control @error('bairro') is-invalid @enderror"
                                    name="bairro" autocomplete="bairro" required>

                                @error('bairro')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ __($message) }}</strong>
                                    </span>
                                @enderror
                            </div>

                        </div>
                        <div class="col-md-12">
                            <label for="complemento" class="col-form-label text-start d-block fw-bold mb-3">{{ __('Complemento') }}</label>
                            <input type="text" value="@if(old('complemento') != null){{old('complemento')}}@else{{$evento->endereco->complemento}}@endif" id="complemento"
                                class="form-control @error('complemento') is-invalid @enderror" name="complemento">

                            @error('complemento')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ __($message) }}</strong>
                                </span>
                            @enderror
                        </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="cidade" class="col-form-label text-start d-block fw-bold mb-3">{{ __('Cidade') }}</label>
                            <input value="{{ old('cidade', $endereco->cidade) }}" id="cidade" type="text"
                                class="form-control apenasLetras @error('cidade') is-invalid @enderror"
                                name="cidade" autocomplete="cidade" required>

                            @error('cidade')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ __($message) }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group">
                        <label for="uf" class="col-form-label text-start d-block fw-bold mb-3">{{ __('Estado') }}</label>
                        {{-- <input id="uf" type="text" class="form-control @error('uf') is-invalid @enderror" name="uf" value="{{ old('uf') }}" required autocomplete="uf" autofocus> --}}
                         <select class="form-control @error('uf') is-invalid @enderror" id="uf" name="uf">
                        <option value="" disabled selected hidden>-- UF --</option>
                        <option @selected(old('uf', $endereco->uf) == 'AC') value="AC">Acre</option>
                        <option @selected(old('uf', $endereco->uf) == 'AL') value="AL">Alagoas</option>
                        <option @selected(old('uf', $endereco->uf) == 'AP') value="AP">Amapá</option>
                        <option @selected(old('uf', $endereco->uf) == 'AM') value="AM">Amazonas</option>
                        <option @selected(old('uf', $endereco->uf) == 'BA') value="BA">Bahia</option>
                        <option @selected(old('uf', $endereco->uf) == 'CE') value="CE">Ceará</option>
                        <option @selected(old('uf', $endereco->uf) == 'DF') value="DF">Distrito Federal</option>
                        <option @selected(old('uf', $endereco->uf) == 'ES') value="ES">Espírito Santo</option>
                        <option @selected(old('uf', $endereco->uf) == 'GO') value="GO">Goiás</option>
                        <option @selected(old('uf', $endereco->uf) == 'MA') value="MA">Maranhão</option>
                        <option @selected(old('uf', $endereco->uf) == 'MT') value="MT">Mato Grosso</option>
                        <option @selected(old('uf', $endereco->uf) == 'MS') value="MS">Mato Grosso do Sul</option>
                        <option @selected(old('uf', $endereco->uf) == 'MG') value="MG">Minas Gerais</option>
                        <option @selected(old('uf', $endereco->uf) == 'PA') value="PA">Pará</option>
                        <option @selected(old('uf', $endereco->uf) == 'PB') value="PB">Paraíba</option>
                        <option @selected(old('uf', $endereco->uf) == 'PR') value="PR">Paraná</option>
                        <option @selected(old('uf', $endereco->uf) == 'PE') value="PE">Pernambuco</option>
                        <option @selected(old('uf', $endereco->uf) == 'PI') value="PI">Piauí</option>
                        <option @selected(old('uf', $endereco->uf) == 'RJ') value="RJ">Rio de Janeiro</option>
                        <option @selected(old('uf', $endereco->uf) == 'RN') value="RN">Rio Grande do Norte</option>
                        <option @selected(old('uf', $endereco->uf) == 'RS') value="RS">Rio Grande do Sul</option>
                        <option @selected(old('uf', $endereco->uf) == 'RO') value="RO">Rondônia</option>
                        <option @selected(old('uf', $endereco->uf) == 'RR') value="RR">Roraima</option>
                        <option @selected(old('uf', $endereco->uf) == 'SC') value="SC">Santa Catarina</option>
                        <option @selected(old('uf', $endereco->uf) == 'SP') value="SP">São Paulo</option>
                        <option @selected(old('uf', $endereco->uf) == 'SE') value="SE">Sergipe</option>
                        <option @selected(old('uf', $endereco->uf) == 'TO') value="TO">Tocantins</option>
                    </select>

                        @error('uf')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    </div>

                    {{-- Datas do Evento --}}
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="dataInicio" class="col-form-label text-start d-block fw-bold mb-3">{{ __('Data de início') }}</label>
                            <input id="dataInicio" type="date" class="form-control @error('dataInicio') is-invalid @enderror"
                                name="dataInicio" value="{{ old('dataInicio', $evento->dataInicio) }}" required autocomplete="dataInicio" autofocus>

                            @error('dataInicio')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="dataFim" class="col-form-label text-start d-block fw-bold mb-3 ">{{ __('Data de término') }}</label>
                            <input id="dataFim" type="date" class="form-control @error('dataFim') is-invalid @enderror"
                                name="dataFim" value="{{ old('dataFim', $evento->dataFim) }}" required autocomplete="dataFim" autofocus>

                            @error('dataFim')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row align-items-center">
                        <div class="col-md-6">
                            <label for="dataLimiteInscricao" class="col-form-label text-start d-block fw-bold mb-3">
                                {{ __('Data de encerramento de inscrições') }}
                            </label>
                            <input id="dataLimiteInscricao" type="datetime-local"
                                class="form-control @error('dataLimiteInscricao') is-invalid @enderror"
                                name="dataLimiteInscricao"
                                @if(old('dataLimiteInscricao') !=null) value="{{ old('dataLimiteInscricao') }}" @else value="{{$evento->data_limite_inscricao}}" @endif
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
                                {{ __('Informe uma data para encerramento das inscrições no evento. Caso não informada, a data limite para inscrição no evento será um dia prévio a data de início do evento') }}.
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
                                    {{ __('Salvar subevento') }}
                                @else
                                    {{ __('Salvar evento') }}
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
                if ($(this).is(':checked')) {
                    $('.multilingual_fields').show();
                } else {
                    $('.multilingual_fields').hide();
                }
            });

            $('#imagem-loader').click(function() {
                $('#logo-input').click();
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
                $('#icone-input').click();
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
                $('#logo-input-en').click();
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
                $('#icone-input-en').click();
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
                $('#logo-input-es').click();
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
                $('#icone-input-es').click();
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
            // Limpar mensagens de erro anteriores
            document.getElementById('erro-nome').style.display = 'none';
            document.getElementById('erro-email').style.display = 'none';
            document.getElementById('erro-descricao').style.display = 'none';

            let temErro = false;

            // Validar nome do evento
            const nome = document.getElementById('nome').value.trim();
            if (nome === '') {
                document.getElementById('erro-nome').style.display = 'block';
                temErro = true;
            }

            // Validar email
            const email = document.getElementById('email').value.trim();
            if (email === '') {
                document.getElementById('erro-email').style.display = 'block';
                temErro = true;
            }

            // Validar descrição
            const descricao = document.getElementById('descricao').value.trim();
            if (descricao === '') {
                document.getElementById('erro-descricao').style.display = 'block';
                temErro = true;
            }

            // Só prosseguir se não houver erros
            if (!temErro) {
                document.getElementById('etapa-1').style.display = 'none';
                document.getElementById('etapa-2').style.display = 'block';
            }
        }

        function etapaAnterior() {
            document.getElementById('etapa-1').style.display = 'block';
            document.getElementById('etapa-2').style.display = 'none';
        }

    </script>
@endsection
