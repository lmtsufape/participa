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
            <div class="col-sm-6">
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
                                    <input id="nomeModalidade" type="text" class="form-control apenasLetras @error('nomeModalidade') is-invalid @enderror" name="nomeModalidade" value="{{ old('nomeModalidade') }}" required autocomplete="nomeModalidade" autofocus>

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

                            <div class="col-sm-6">
                                <label for="inicioSubmissao" class="col-form-label">{{ __('Início da Submissão') }}</label>
                                <input id="inicioSubmissao" type="date" class="form-control @error('inícioDaSubmissão') is-invalid @enderror" name="inícioDaSubmissão" value="{{ old('inícioDaSubmissão') }}" autocomplete="inicioSubmissao" autofocus>

                                @error('inícioDaSubmissão')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-sm-6">
                                <label for="fimSubmissao" class="col-form-label">{{ __('Fim da Submissão') }}</label>
                                <input id="fimSubmissao" type="date" class="form-control @error('fimDaSubmissão') is-invalid @enderror" name="fimDaSubmissão" value="{{ old('fimDaSubmissão') }}" autocomplete="fimSubmissao" autofocus>

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
                                <input id="inicioRevisao" type="date" class="form-control @error('inícioDaRevisão') is-invalid @enderror" name="inícioDaRevisão" value="{{ old('inícioDaRevisão') }}" autocomplete="inicioRevisao" autofocus>

                                @error('inícioDaRevisão')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-sm-6">
                                <label for="fimRevisao" class="col-form-label">{{ __('Fim da Revisão') }}</label>
                                <input id="fimRevisao" type="date" class="form-control @error('fimDaRevisão') is-invalid @enderror" name="fimDaRevisão" value="{{ old('fimDaRevisão') }}" autocomplete="fimRevisao" autofocus>

                                @error('fimDaRevisão')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        {{-- end Data: inicioRevisão | fimRevisao --}}

                        {{-- Data: resultado --}}
                        <div class="row">

                            <div class="col-sm-6">
                                <label for="inicioResultado" class="col-form-label">{{ __('Início do Resultado') }}</label>
                                <input id="inicioResultado" type="date" class="form-control @error('inícioDoResultado') is-invalid @enderror" name="inícioDoResultado" value="{{ old('inícioDoResultado') }}" autocomplete="inicioResultado" autofocus>

                                @error('inícioDoResultado')
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
                                <label class="col-form-label">{{ __('Restrições de resumo:') }}</label>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="limit" id="id-limit-custom_field-account-1-1" value="limit-option1" required>
                                    <label class="form-check-label" for="texto">
                                        Quantidade de caracteres
                                    </label>
                                    </div>
                                    <div class="form-check">
                                    <input class="form-check-input" type="radio" name="limit" id="id-limit-custom_field-account-1-2" value="limit-option2" required>
                                    <label class="form-check-label" for="arquivo">
                                        Quantidade de palavras
                                    </label>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6" id="min-max-caracteres" style="display: none">
                                        <div class="form-group">
                                            <label class="col-form-label">{{ __('Mínimo') }}</label>
                                            <div>
                                              <input class="form-control" type="number" id="min_caracteres" name="mincaracteres">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-form-label">{{ __('Máximo') }}</label>
                                            <div>
                                              <input class="form-control" type="number" id="max_caracteres" name="maxcaracteres">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6" id="min-max-palavras" style="display: none">
                                        <div class="form-group">
                                            <label class="col-form-label">{{ __('Mínimo') }}</label>
                                            <div>
                                              <input class="form-control" type="number" id="min_palavras" name="minpalavras">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-form-label">{{ __('Máximo') }}</label>
                                            <div>
                                              <input class="form-control" type="number" id="max_palavras" name="maxpalavras">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-check" style="margin-top: 10px">
                                    <input class="form-check-input incluirarquivo" type="checkbox" name="arquivo" id="id-custom_field-account-1-2">
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
                            <div class="col-sm-6" id="tipo-arquivo" style="display: none">

                                <div class="titulo-detalhes" style="margin-top: 10px"></div>
                                <label class="col-form-label">{{ __('Tipos de extensão aceitas') }}</label>

                                <div class="form-check" style="margin-top: 10px">
                                    <input class="form-check-input" type="checkbox" id="defaultCheck1" name="pdf">
                                    <label class="form-check-label" for="defaultCheck1">
                                        .pdf
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="defaultCheck1" name="jpg">
                                    <label class="form-check-label" for="defaultCheck1">
                                        .jpg
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="defaultCheck1" name="jpeg">
                                    <label class="form-check-label" for="defaultCheck1">
                                        .jpeg
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="defaultCheck1" name="png">
                                    <label class="form-check-label" for="defaultCheck1">
                                        .png
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="defaultCheck1" name="docx">
                                    <label class="form-check-label" for="defaultCheck1">
                                        .docx
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="defaultCheck1" name="odt">
                                    <label class="form-check-label" for="defaultCheck1">
                                        .odt
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            {{-- Arquivo de Regras  --}}
                            <div class="col-sm-12" style="margin-top: 20px;">
                              <label for="arquivoRegras" class="col-form-label">{{ __('Enviar regras:') }}</label>

                              <div class="custom-file">
                                <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="arquivoRegras">
                              </div>
                              <small>O arquivo Selecionado deve ser no formato PDF de até 2mb.</small>
                              @error('arquivoRegras')
                              <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                                <strong>{{ $message }}</strong>
                              </span>
                              @enderror
                            </div>
                            {{-- Arquivo de Templates --}}
                            <div class="col-sm-12" id="area-template" style="margin-top: 20px; display:none">
                                <label for="nomeTrabalho" class="col-form-label">{{ __('Enviar template:') }}</label>

                                <div class="custom-file">
                                  <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="arquivoTemplates">
                                </div>
                                <small>O arquivo Selecionado deve ser no formato PDF de até 2mb.</small>
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
            <div class="col-sm-6">
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
                </div>{{-- End card--}}
            </div>
        </div>{{-- end row card --}}
    </div>

@endsection
