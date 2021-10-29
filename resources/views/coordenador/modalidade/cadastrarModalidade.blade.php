@extends('coordenador.detalhesEvento')

@section('menu')

    {{-- Modalidade --}}
    <div id="divCadastrarModalidades" class="modalidades" style="display: block">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="titulo-detalhes">Cadastrar Modalidade</h1>
            </div>
        </div>
        {{-- row card --}}
        <div class="row justify-content-center">
            <div class="col-sm-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Nova Modalidade</h5>
                        <h6 class="card-subtitle mb-2 text-muted">Cadastre uma nova modalidade para o seu evento</h6>
                        <form method="POST" action="{{route('modalidade.store')}}" enctype="multipart/form-data">
                        @csrf
                        <p class="card-text">
                            <input type="hidden" name="eventoId" value="{{$evento->id}}">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label for="nomeModalidade" class="col-form-label">*{{ __('Nome') }}</label>

                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-sm-12">
                                    <input id="nomeModalidade" type="text" class="form-control @error('nomeModalidade') is-invalid @enderror" name="nomeModalidade" value="{{ old('nomeModalidade') }}" required autocomplete="nomeModalidade" autofocus>

                                    @error('nomeModalidade')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                            </div>{{-- end row--}}

                        </p>

                        {{-- Data: inicioSubmissao | fimSubmissao --}}
                        <div class="row justify-content-center">
                            {{-- @php
                                date_default_timezone_set('America/Recife');
                            @endphp --}}
                            <div class="col-sm-6">
                                <label for="inicioSubmissao" class="col-form-label">{{ __('Início da Submissão') }}</label>
                                <input id="inicioSubmissao" type="datetime-local" class="form-control @error('inícioDaSubmissão') is-invalid @enderror" name="inícioDaSubmissão" value="{{ old('inícioDaSubmissão') }}" autocomplete="inicioSubmissao" autofocus>

                                @error('inícioDaSubmissão')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-sm-6">
                                <label for="fimSubmissao" class="col-form-label">{{ __('Fim da Submissão') }}</label>
                                <input id="fimSubmissao" type="datetime-local" class="form-control @error('fimDaSubmissão') is-invalid @enderror" name="fimDaSubmissão" value="{{ old('fimDaSubmissão') }}" autocomplete="fimSubmissao" autofocus>

                                @error('fimDaSubmissão')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        {{-- end Data: inicioSubmissao | fimSubmissao --}}

                        {{-- Data: inicioRevisao | fimRevisao --}}
                        <div class="row justify-content-center">

                            <div class="col-sm-6">
                                <label for="inicioRevisao" class="col-form-label">{{ __('Início da Revisão') }}</label>
                                <input id="inicioRevisao" type="datetime-local" class="form-control @error('inícioDaRevisão') is-invalid @enderror" name="inícioDaRevisão" value="{{ old('inícioDaRevisão') }}" autocomplete="inicioRevisao" autofocus>

                                @error('inícioDaRevisão')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-sm-6">
                                <label for="fimRevisao" class="col-form-label">{{ __('Fim da Revisão') }}</label>
                                <input id="fimRevisao" type="datetime-local" class="form-control @error('fimDaRevisão') is-invalid @enderror" name="fimDaRevisão" value="{{ old('fimDaRevisão') }}" autocomplete="fimRevisao" autofocus>

                                @error('fimDaRevisão')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        {{-- end Data: inicioRevisão | fimRevisao --}}

                        {{-- Data: inicioCorrecao | fimCorrecao --}}
                        <div class="row justify-content-center">

                            <div class="col-sm-6">
                                <label for="inicioCorrecao" class="col-form-label">{{ __('Início da Correção') }} <i data-toggle="tooltip" data-placement="top" title="Opcional" class="fas fa-exclamation-circle"></i></label>
                                <input id="inicioCorrecao" type="datetime-local" class="form-control @error('inícioCorreção') is-invalid @enderror" name="inícioCorreção" value="{{ old('inícioCorreção') }}" autocomplete="inicioCorrecao" autofocus>

                                @error('inícioCorreção')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-sm-6">
                                <label for="fimCorrecao" class="col-form-label">{{ __('Fim da Correção') }} <i data-toggle="tooltip" data-placement="top" title="Opcional" class="fas fa-exclamation-circle"></i></label>
                                <input id="fimCorrecao" type="datetime-local" class="form-control @error('fimCorreção') is-invalid @enderror" name="fimCorreção" value="{{ old('fimCorreção') }}" autocomplete="fimCorrecao" autofocus>

                                @error('fimCorreção')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        {{-- end Data: inicioCorrecao | fimCorrecao --}}

                        {{-- Data: inicioValidacao | fimValidacao --}}
                        <div class="row justify-content-center">

                            <div class="col-sm-6">
                                <label for="inicioValidacao" class="col-form-label">
                                    {{ __('Início da Validação') }}
                                    <i data-toggle="tooltip" data-placement="top" title="Opcional" class="fas fa-exclamation-circle"></i>
                                </label>
                                <input id="inicioValidacao" type="datetime-local" class="form-control @error('inícioValidação') is-invalid @enderror" name="inícioValidação" value="{{ old('inícioValidação') }}" autocomplete="inicioValidacao" autofocus>

                                @error('inícioValidação')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-sm-6">
                                <label for="fimValidacao" class="col-form-label">{{ __('Fim da Validação') }} <i data-toggle="tooltip" data-placement="top" title="Opcional" class="fas fa-exclamation-circle"></i></label>
                                <input id="fimValidacao" type="datetime-local" class="form-control @error('fimValidação') is-invalid @enderror" name="fimValidação" value="{{ old('fimValidação') }}" autocomplete="fimValidacao" autofocus>

                                @error('fimValidação')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        {{-- end Data: inicioValidacao | fimValidacao --}}

                        {{-- Data: resultado --}}
                        <div class="row">

                            <div class="col-sm-6">
                                <label for="inicioResultado" class="col-form-label">{{ __('Resultado') }}</label>
                                <input id="inicioResultado" type="datetime-local" class="form-control @error('resultado') is-invalid @enderror" name="resultado" value="{{ old('resultado') }}" autocomplete="inicioResultado" autofocus>

                                @error('resultado')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        {{-- end Data: resultado --}}

                        {{-- Inicio - Tipo de submissão --}}
                        <div class="row">

                            <div class="col-sm-6">
                                <div class="form-check" style="margin-top: 10px">
                                    <input class="form-check-input incluir-resumo" type="checkbox" name="texto" id="id-custom_field-account-1-2" @if (old('texto') == true) checked @endif>
                                    <label class="form-check-label" for="resumo">
                                        Adicionar campo resumo por texto
                                    </label>
                                    @error('resumo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div id="restricoes-resumo-texto" @if (old('texto') == true) style="display: block;" @else style="display: none;" @endif>
                                    <label class="col-form-label">{{ __('Restrições de resumo:') }}</label>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="limit" id="id-limit-custom_field-account-1-1" value="limit-option1" @if (old('limit') == "limit-option1") checked @endif>
                                        <label class="form-check-label" for="texto">
                                            Quantidade de caracteres
                                        </label>
                                        </div>
                                        <div class="form-check">
                                        <input class="form-check-input" type="radio" name="limit" id="id-limit-custom_field-account-1-2" value="limit-option2" @if (old('limit') == "limit-option2") checked @endif>
                                        <label class="form-check-label" for="arquivo">
                                            Quantidade de palavras
                                        </label>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6" id="min-max-caracteres" @if (old('limit') == "limit-option1") style="display: block" @else style="display: none" @endif>
                                            <div class="form-group">
                                                <label class="col-form-label">{{ __('Mínimo') }}</label>
                                                <div>
                                                  <input class="form-control" type="number" id="min_caracteres" name="mincaracteres" value="{{old('mincaracteres')}}">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-form-label">{{ __('Máximo') }}</label>
                                                <div>
                                                  <input class="form-control" type="number" id="max_caracteres" name="maxcaracteres"  value="{{old('maxcaracteres')}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6" id="min-max-palavras" @if (old('limit') == "limit-option2") style="display: block" @else style="display: none" @endif>
                                            <div class="form-group">
                                                <label class="col-form-label">{{ __('Mínimo') }}</label>
                                                <div>
                                                  <input class="form-control" type="number" id="min_palavras" name="minpalavras" value="{{old('minpalavras')}}">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-form-label">{{ __('Máximo') }}</label>
                                                <div>
                                                  <input class="form-control" type="number" id="max_palavras" name="maxpalavras" value="{{old('maxpalavras')}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-check" style="margin-top: 10px">
                                    <input class="form-check-input incluirarquivo" type="checkbox" name="arquivo" id="id-custom_field-account-1-2" @if(old('arquivo') == true) checked @endif>
                                    <label class="form-check-label" for="arquivo">
                                        Incluir submissão por arquivo
                                    </label>
                                    @error('arquivo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-sm-6" id="tipo-arquivo" @if(old('arquivo') == true) style="display: block" @else style="display: none" @endif>

                                <div class="titulo-detalhes" style="margin-top: 10px"></div>
                                <label class="col-form-label">{{ __('Tipos de extensão aceitas') }}</label>

                                <div class="form-check" style="margin-top: 10px">
                                    <input class="form-check-input" type="checkbox" id="defaultCheck1" name="pdf" @if(old('pdf')) checked @endif>
                                    <label class="form-check-label" for="defaultCheck1">
                                        .pdf
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="defaultCheck1" name="jpg" @if(old('jpg')) checked @endif>
                                    <label class="form-check-label" for="defaultCheck1">
                                        .jpg
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="defaultCheck1" name="jpeg" @if(old('jpeg')) checked @endif>
                                    <label class="form-check-label" for="defaultCheck1">
                                        .jpeg
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="defaultCheck1" name="png" @if(old('png')) checked @endif>
                                    <label class="form-check-label" for="defaultCheck1">
                                        .png
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="defaultCheck1" name="docx" @if(old('docx')) checked @endif>
                                    <label class="form-check-label" for="defaultCheck1">
                                        .docx
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="defaultCheck1" name="odt" @if(old('odt')) checked @endif>
                                    <label class="form-check-label" for="defaultCheck1">
                                        .odt
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="defaultCheck1" name="zip" @if(old('zip')) checked @endif>
                                    <label class="form-check-label" for="defaultCheck1">
                                        .zip
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="defaultCheck1" name="svg" @if(old('svg')) checked @endif>
                                    <label class="form-check-label" for="defaultCheck1">
                                        .svg
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            {{-- Arquivo de Modelos --}}
                            <div class="col-sm-12" style="margin-top: 20px;" >
                                <label for="nomeTrabalho" class="col-form-label">{{ __('Enviar modelo de apresentação:') }}</label>

                                <div class="custom-file">
                                  <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="arquivoModelos">
                                </div>
                                <small>O arquivo selecionado deve ser no formato ODT, OTT, DOCX, DOC, RTF, PDF, PPT, PPTX ou ODP de até 2 MB.</small>
                                @error('arquivoModelos')
                                <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                                  <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            {{-- Arquivo de Regras  --}}
                            <div class="col-sm-12" style="margin-top: 20px;">
                              <label for="arquivoRegras" class="col-form-label">{{ __('Enviar regras:') }}</label>

                              <div class="custom-file">
                                <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="arquivoRegras">
                              </div>
                              <small>O arquivo selecionado deve ser no formato PDF de até 2 MB.</small>
                              @error('arquivoRegras')
                              <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                                <strong>{{ $message }}</strong>
                              </span>
                              @enderror
                            </div>
                            {{-- Arquivo de Templates --}}
                            <div class="col-sm-12" id="area-template" style="margin-top: 20px; @if(old('arquivo')) display:block; @else display:none; @endif" >
                                <label for="nomeTrabalho" class="col-form-label">{{ __('Enviar template:') }}</label>

                                <div class="custom-file">
                                  <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="arquivoTemplates">
                                </div>
                                <small>O arquivo selecionado deve ser no formato ODT, OTT, DOCX, DOC, RTF, TXT ou PDF de até 2 MB.</small>
                                @error('arquivoTemplates')
                                <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                                  <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <br>
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" style="width:100%">
                                    {{ __('Finalizar') }}
                                </button>
                            </div>
                        </div>
                        </form>
                    </div>
                    </div>
            </div>
            {{-- <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Áreas por Modalidade</h5>
                        <h6 class="card-subtitle mb-2 text-muted">Vincule as Áreas de acordo com cada modalidade</h6>
                        <form method="POST" action="{{route('areaModalidade.store')}}">
                        @csrf
                        <p class="card-text">
                            <input type="hidden" name="eventoId" value="{{$evento->id}}">
                            <div class="row justify-content-center">
                                <div class="col-sm-6">
                                    <label for="modalidadeId" class="col-form-label">{{ __('Modalidade') }}</label>
                                    <select class="form-control @error('modalidadeId') is-invalid @enderror" id="modalidadeId" name="modalidadeId">
                                        <option value="" disabled selected hidden> Modalidade </option>
                                        @foreach($modalidades as $modalidade)
                                        <option value="{{$modalidade->id}}">{{$modalidade->nome}}</option>
                                        @endforeach
                                    </select>

                                    @error('modalidadeId')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label for="areaId" class="col-form-label">{{ __('Área') }}</label>
                                    <select class="form-control @error('areaId') is-invalid @enderror" id="areaId" name="areaId">
                                        <option value="" disabled selected hidden> Área </option>
                                        @foreach($areas as $area)
                                            <option value="{{$area->id}}">{{$area->nome}}</option>
                                        @endforeach
                                    </select>

                                    @error('areaId')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </p>

                        <div class="row justify-content-center">

                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" style="width:100%">
                                    {{ __('Finalizar') }}
                                </button>
                            </div>
                        </div>
                        </form>

                    </div>
                </div>
            </div> --}}
        </div>{{-- end row card --}}
    </div>

@endsection
