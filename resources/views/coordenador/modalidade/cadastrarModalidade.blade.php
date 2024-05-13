@extends('coordenador.detalhesEvento')
@section('menu')
<div id="divCadastrarModalidades" class="modalidades" style="display: block">
    <div class="row">
        <div class="col-sm-12">
            <h1 class="titulo-detalhes">Cadastrar Modalidade</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Nova Modalidade</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Cadastre uma nova modalidade para o seu evento</h6>
                    <form method="POST" action="{{route('modalidade.store')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-text">
                            <input type="hidden" name="eventoId" value="{{$evento->id}}">
                            <div class="form-group">
                                <label for="nomeModalidade" class="col-form-label font-weight-bold">*{{ __('Nome') }}</label>
                                <input id="nomeModalidade" type="text" class="form-control @error('nome') is-invalid @enderror" name="nome" value="{{ old('nome') }}" required autocomplete="nome" autofocus>
                                @error('nome')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="inicioSubmissao" class="col-form-label font-weight-bold">{{ __('Início da Submissão') }}</label>
                                    <input id="inicioSubmissao" type="datetime-local" class="form-control @error('inicioSubmissao') is-invalid @enderror" name="inicioSubmissao" value="{{ old('inicioSubmissao') }}" autocomplete="inicioSubmissao" autofocus>
                                    @error('inicioSubmissao')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="fimSubmissao" class="col-form-label font-weight-bold">{{ __('Fim da Submissão') }}</label>
                                    <input id="fimSubmissao" type="datetime-local" class="form-control @error('fimSubmissao') is-invalid @enderror" name="fimSubmissao" value="{{ old('fimSubmissao') }}" autocomplete="fimSubmissao" autofocus>
                                    @error('fimSubmissao')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-sm-6">
                                    <label for="inicioRevisao" class="col-form-label font-weight-bold">{{ __('Início da Avaliação') }}</label>
                                    <input id="inicioRevisao" type="datetime-local" class="form-control @error('inicioRevisao') is-invalid @enderror" name="inicioRevisao" value="{{ old('inicioRevisao') }}" autocomplete="inicioRevisao" autofocus>
                                    @error('inicioRevisao')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="fimRevisao" class="col-form-label font-weight-bold">{{ __('Fim da Avaliação') }}</label>
                                    <input id="fimRevisao" type="datetime-local" class="form-control @error('fimRevisao') is-invalid @enderror" name="fimRevisao" value="{{ old('fimRevisao') }}" autocomplete="fimRevisao" autofocus>
                                    @error('fimRevisao')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-sm-6">
                                    <label for="inicioCorrecao" class="col-form-label font-weight-bold">{{ __('Início da Correção') }} <i data-toggle="tooltip" data-placement="top" title="Opcional" class="fas fa-exclamation-circle"></i></label>
                                    <input id="inicioCorrecao" type="datetime-local" class="form-control @error('inicioCorrecao') is-invalid @enderror" name="inicioCorrecao" value="{{ old('inicioCorrecao') }}" autocomplete="inicioCorrecao" autofocus>
                                    @error('inicioCorrecao')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="fimCorrecao" class="col-form-label font-weight-bold">{{ __('Fim da Correção') }} <i data-toggle="tooltip" data-placement="top" title="Opcional" class="fas fa-exclamation-circle"></i></label>
                                    <input id="fimCorrecao" type="datetime-local" class="form-control @error('fimCorrecao') is-invalid @enderror" name="fimCorrecao" value="{{ old('fimCorrecao') }}" autocomplete="fimCorrecao" autofocus>
                                    @error('fimCorrecao')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-sm-6">
                                    <label for="inicioValidacao" class="col-form-label font-weight-bold">
                                        {{ __('Início da Validação') }}
                                        <i data-toggle="tooltip" data-placement="top" title="Opcional" class="fas fa-exclamation-circle"></i>
                                    </label>
                                    <input id="inicioValidacao" type="datetime-local" class="form-control @error('inicioValidacao') is-invalid @enderror" name="inicioValidacao" value="{{ old('inicioValidacao') }}" autocomplete="inicioValidacao" autofocus>
                                    @error('inicioValidacao')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="fimValidacao" class="col-form-label font-weight-bold">{{ __('Fim da Validação') }} <i data-toggle="tooltip" data-placement="top" title="Opcional" class="fas fa-exclamation-circle"></i></label>
                                    <input id="fimValidacao" type="datetime-local" class="form-control @error('fimValidacao') is-invalid @enderror" name="fimValidacao" value="{{ old('fimValidacao') }}" autocomplete="fimValidacao" autofocus>
                                    @error('fimValidacao')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-sm-6">
                                    <label for="inicioResultado" class="col-form-label font-weight-bold">{{ __('Resultado') }}</label>
                                    <input id="inicioResultado" type="datetime-local" class="form-control @error('inicioResultado') is-invalid @enderror" name="inicioResultado" value="{{ old('inicioResultado') }}" autocomplete="inicioResultado" autofocus>
                                    @error('inicioResultado')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div id="datas" x-data="handler()">
                                <template x-for="(data, index) in datas" :key="index">
                                    <div class="form-row">
                                        <div class="form-group col-md-3" x-data="{ id: $id('data-extra-nome') }">
                                            <label :for="id" class="col-form-label font-weight-bold">
                                                {{ __('Nome da data') }}*
                                            </label>
                                            <input :id="id" type="text" class="form-control" x-model="data.nome" name="nomeDataExtra[]" required>
                                            @error('nomeDataExtra[]')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-3" x-data="{ id: $id('data-extra-inicio') }">
                                            <label :for="id" class="col-form-label font-weight-bold">
                                                {{ __('Data inicial') }}*
                                            </label>
                                            <input :id="id" type="datetime-local" class="form-control" x-model="data.inicio" name="inicioDataExtra[]" required>
                                            @error('inicioDataExtra[]')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-3" x-data="{ id: $id('data-extra-final') }">
                                            <label :for="id" class="col-form-label font-weight-bold">
                                                {{ __('Data final') }}*
                                            </label>
                                            <input :id="id" type="datetime-local" class="form-control" x-model="data.fim" name="finalDataExtra[]" required>
                                            @error('finalDataExtra[]')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-3" x-data="{ id: $id('data-extra-submissao') }">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" x-model="data.permitirSubmissao" :id="id" :name="'submissaoDataExtra[' + index + ']'">
                                                <label class="form-check-label" :for="id">
                                                    <strong>Permitir submissão</strong>
                                                </label>
                                                <div class="d-flex justify-content-center">
                                                    <button title="Remover data" type="button" @click="removeData(index)" style="color: #d30909;" class="btn pb-0">
                                                        <img class="mt-2 text-danger" src="{{asset('img/icons/calendar-times-solid.svg')}}" alt="ícone de adicionar data extra" width="30px">
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <button @click="adicionaData" class="btn btn-primary btn-padding border mb-2" style="text-decoration: none; border-radius: 14px; background-color: #E5B300" title="Clique aqui para adicionar datas extras" type="button">
                                    <img class="mt-2" src="{{asset('img/icons/calendar-plus-black-solid.svg')}}" alt="ícone de adicionar data extra" width="30px">
                                    <br> Adicionar data
                                </button>
                            </div>
                            <div x-data="{texto: '{{old('texto', 0)}}', limit: '{{old('limit')}}'}">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" id="avaliacaoDuranteSubmissaocheck" onclick="mensagemSubmissao()" x-model="avaliacaoDuranteSubmissao" type="checkbox" name="avaliacaoDuranteSubmissao">
                                        <label class="form-check-label font-weight-bold" for="avaliacaoDuranteSubmissaocheck">

                                            {{ __('Permitir avaliação durante o período de submissão') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" id="textocheck" x-model="texto" type="checkbox" value="1" name="texto">
                                        <label class="form-check-label font-weight-bold" for="textocheck">
                                            Adicionar campo resumo por texto
                                        </label>
                                    </div>
                                </div>
                                <template x-if="texto == 1">
                                    <div style="margin-top: -1rem; margin-left: 1.3rem;">
                                        <label class="col-form-label font-weight-bold">{{ __('Restrições de resumo:') }}</label>
                                        <div class="form-check">
                                            <input class="form-check-input" id="caracterestextocheck" type="radio" x-model="limit" name="limit" value="limit-option1" @if (old('limit')=="limit-option1" ) checked @endif>
                                            <label class="form-check-label" for="caracterestextocheck">
                                                Quantidade de caracteres
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" id="palavrastextocheck" type="radio" x-model="limit" name="limit" value="limit-option2" @if (old('limit')=="limit-option2" ) checked @endif>
                                            <label class="form-check-label" for="palavrastextocheck">
                                                Quantidade de palavras
                                            </label>
                                        </div>
                                        @error('limit')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </template>
                                <template x-if="limit=='limit-option1' && texto == true">
                                    <div class="form-row" style="margin-left: 2.3rem;">
                                        <div class="form-group col-md-6">
                                            <label class="col-form-label" for="mincaracterestextocheck">{{ __('Mínimo') }}</label>
                                            <input class="form-control @error('mincaracteres') is-invalid @enderror" id="mincaracterestextocheck" type="number" name="mincaracteres" value="{{old('mincaracteres')}}">
                                            @error('mincaracteres')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="col-form-label" for="maxcaracterestextocheck">{{ __('Máximo') }}</label>
                                            <input class="form-control @error('maxcaracteres') is-invalid @enderror" id="maxcaracterestextocheck" type="number" name="maxcaracteres" value="{{old('maxcaracteres')}}">
                                            @error('maxcaracteres')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </template>
                                <template x-if="limit=='limit-option2' && texto == true">
                                    <div class="form-row" style="margin-left: 2.3rem;">
                                        <div class="form-group col-md-6">
                                            <label class="col-form-label" for="minpalavrastextocheck">{{ __('Mínimo') }}</label>
                                            <input class="form-control @error('minpalavras') is-invalid @enderror" id="minpalavrastextocheck" type="number" name="minpalavras" value="{{old('minpalavras')}}">
                                            @error('minpalavras')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="col-form-label" for="maxpalavrastextocheck">{{ __('Máximo') }}</label>
                                            <input class="form-control @error('maxpalavras') is-invalid @enderror" id="maxpalavrastextocheck" type="number" name="maxpalavras" value="{{old('maxpalavras')}}">
                                            @error('maxpalavras')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <div x-data="{arquivo: '{{old('arquivo')}}' == '1'}">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input incluirarquivo @error('arquivo') is-invalid @enderror" id="arquivocheck" x-model="arquivo" type="checkbox" name="arquivo" value="1">
                                        <label class="form-check-label font-weight-bold" for="arquivocheck">
                                            Incluir submissão por arquivo
                                        </label>
                                    </div>
                                    @error('arquivo')
                                    <div class="invalid-feedback d-flex mt-0" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    @enderror
                                </div>
                                <template x-if="arquivo==true">
                                    <div style="margin-top:-1rem; margin-left:1.3rem;">
                                        <label class="col-form-label font-weight-bold">{{ __('Tipos de extensão aceitas') }}</label>
                                        <div class="form-row mb-2 row-cols-4 row-cols-md-5" style="margin-left: 1rem;">
                                            <div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="pdfarquivocheck" name="pdf" value="1" @if(old('pdf')) checked @endif>
                                                    <label class="form-check-label" for="pdfarquivocheck">
                                                        .pdf
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="docxarquivocheck" name="docx" value="1" @if(old('docx')) checked @endif>
                                                    <label class="form-check-label" for="docxarquivocheck">
                                                        .docx
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="odtarquivocheck" name="odt" value="1" @if(old('odt')) checked @endif>
                                                    <label class="form-check-label" for="odtarquivocheck">
                                                        .odt
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="odparquivocheck" name="odp" value="1" @if(old('odp')) checked @endif>
                                                    <label class="form-check-label" for="odparquivocheck">
                                                        .odp
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="pptxarquivocheck" name="pptx" value="1" @if(old('pptx')) checked @endif>
                                                    <label class="form-check-label" for="pptxarquivocheck">
                                                        .pptx
                                                    </label>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="odsarquivocheck" name="ods" value="1" @if(old('ods')) checked @endif>
                                                    <label class="form-check-label" for="odsarquivocheck">
                                                        .ods
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="xlsxarquivocheck" name="xlsx" value="1" @if(old('xlsx')) checked @endif>
                                                    <label class="form-check-label" for="xlsxarquivocheck">
                                                        .xlsx
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="csvarquivocheck" name="csv" value="1" @if(old('csv')) checked @endif>
                                                    <label class="form-check-label" for="csvarquivocheck">
                                                        .csv
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="ziparquivocheck" name="zip" value="1" @if(old('zip')) checked @endif>
                                                    <label class="form-check-label" for="ziparquivocheck">
                                                        .zip
                                                    </label>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="mp3arquivocheck" name="mp3" value="1" @if(old('mp3')) checked @endif>
                                                    <label class="form-check-label" for="mp3arquivocheck">
                                                        .mp3
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="oggarquivocheck" name="ogg" value="1" @if(old('ogg')) checked @endif>
                                                    <label class="form-check-label" for="oggarquivocheck">
                                                        .ogg
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="wavarquivocheck" name="wav" value="1" @if(old('wav')) checked @endif>
                                                    <label class="form-check-label" for="wavarquivocheck">
                                                        .wav
                                                    </label>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="mp4arquivocheck" name="mp4" value="1" @if(old('mp4')) checked @endif>
                                                    <label class="form-check-label" for="mp4arquivocheck">
                                                        .mp4
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="ogvarquivocheck" name="ogv" value="1" @if(old('ogv')) checked @endif>
                                                    <label class="form-check-label" for="ogvarquivocheck">
                                                        .ogv
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="mpgarquivocheck" name="mpg" value="1" @if(old('mpg')) checked @endif>
                                                    <label class="form-check-label" for="mpgarquivocheck">
                                                        .mpg
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="mpegarquivocheck" name="mpeg" value="1" @if(old('mpeg')) checked @endif>
                                                    <label class="form-check-label" for="mpegarquivocheck">
                                                        .mpeg
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="mkvarquivocheck" name="mkv" value="1" @if(old('mkv')) checked @endif>
                                                    <label class="form-check-label" for="mkvarquivocheck">
                                                        .mkv
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="aviarquivocheck" name="avi" value="1" @if(old('avi')) checked @endif>
                                                    <label class="form-check-label" for="aviarquivocheck">
                                                        .avi
                                                    </label>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="jpgarquivocheck" name="jpg" value="1" @if(old('jpg')) checked @endif>
                                                    <label class="form-check-label" for="jpgarquivocheck">
                                                        .jpg
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="jpegarquivocheck" name="jpeg" value="1" @if(old('jpeg')) checked @endif>
                                                    <label class="form-check-label" for="jpegarquivocheck">
                                                        .jpeg
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="pngarquivocheck" name="png" value="1" @if(old('png')) checked @endif>
                                                    <label class="form-check-label" for="pngarquivocheck">
                                                        .png
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="svgarquivocheck" name="svg" value="1" @if(old('svg')) checked @endif>
                                                    <label class="form-check-label" for="svgarquivocheck">
                                                        .svg
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <div x-data="{apresentacao: '{{old('apresentacao')}}' == '1'}">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input apresentacao @error('apresentacao') is-invalid @enderror" id="apresentacaocheck" x-model="apresentacao" type="checkbox" name="apresentacao">
                                        <label class="form-check-label font-weight-bold" for="apresentacaocheck">
                                            {{ __('Habilitar escolha da forma de apresentação do trabalho:') }}
                                        </label>
                                    </div>
                                    @error('apresentacao')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <template x-if="apresentacao == true">
                                    <div style="margin-top:-1rem; margin-left:1.3rem;">
                                        <label class="col-md-12 col-form-label font-weight-bold ml-0 pl-0">{{ __('Selecione a(s) forma(s) de apresentação do trabalho que poderá(ão) ser escolhida(s) pelo(a) autor(a) ') }} <span style="color: red">{{ __('(não obrigatório)')}}</span>:</label>
                                        <div class="form-row mb-2 row-cols-3 ml-0">
                                            <div class="form-check">
                                                <input class="form-check-input" id="remotoapresentacaocheck" type="checkbox" value="1" name="remoto" @if(old('remoto')) checked @endif>
                                                <label class="form-check-label" for="remotoapresentacaocheck">
                                                    Remoto
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" id="presencialapresentacaocheck" type="checkbox" value="1" name="presencial" @if(old('presencial')) checked @endif>
                                                <label class="form-check-label" for="presencialapresentacaocheck">
                                                    Presencial
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" id="adistanciaapresentacaocheck" type="checkbox" value="1" name="a_distancia" @if(old('a_distancia')) checked @endif>
                                                <label class="form-check-label" for="adistanciaapresentacaocheck">
                                                    À distância
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" id="semipresencialapresentacaocheck" type="checkbox" value="1" name="semipresencial" @if(old('semipresencial')) checked @endif>
                                                <label class="form-check-label" for="semipresencialapresentacaocheck">
                                                    Semipresencial
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <div x-data="{arquivo: '{{old('arquivo')}}' == 'on'}">
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" id="submissocheck" x-model="submissaoUnica" type="checkbox" name="submissaoUnica" value="on">
                                            <label class="form-check-label font-weight-bold" for="submissaoUnicacheck">

                                                {{ __('Habilitar submissão única para avaliação') }}
                                            </label>
                                        </div>
                                        @error('submissaoUnica')
                                        <div class="invalid-feedback d-flex mt-0" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="nomeTrabalho" class="col-form-label font-weight-bold">Modelo de apresentação de slides:</label>
                                    <input type="file" class="filestyle custom-file" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="arquivoModelos">
                                    <small>O arquivo selecionado deve ser no formato ODT, OTT, DOCX, DOC, RTF, PDF, PPT, PPTX ou ODP de até 2 MB.</small>
                                    @error('arquivoModelos')
                                    <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="arquivoRegras" class="col-form-label font-weight-bold">{{ __('Enviar') }} {{$evento->formEvento->etiquetabaixarregra}}:</label>
                                    <input type="file" class="filestyle custom-file" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="arquivoRegras">
                                    <small>O arquivo selecionado deve ser no formato PDF de até 2 MB.</small>
                                    @error('arquivoRegras')
                                    <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="arquivoInstrucoes" class="col-form-label font-weight-bold">{{ __('Enviar') }} {{$evento->formEvento->etiquetabaixarinstrucoes}}:</label>
                                    <input id="arquivoInstrucoes" type="file" class="filestyle custom-file" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="arquivoInstrucoes">
                                    <small>O arquivo selecionado deve ser no formato PDF de até 2 MB.</small>
                                    @error('arquivoInstrucoes')
                                    <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="nomeTrabalho" class="col-form-label font-weight-bold">Modelo visual do texto :</label>
                                    <input type="file" class="filestyle custom-file" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="arquivoTemplates">
                                    <small>O arquivo selecionado deve ser no formato ODT, OTT, DOCX, DOC, RTF, TXT ou PDF de até 2 MB.</small>
                                    @error('arquivoTemplates')
                                    <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div x-data="construct()">
                                    <div class="d-flex justify-content-end">
                                        <button type="button" id="btn-adicionar-escolhar" @click="adicionaDocumento()" class="btn btn-primary">Requisitar novo documento
                                        </button>
                                    </div>
                                    <div id="docs" class="form-group">
                                        <template x-for="(documento, index) in documentos" :key="index">
                                            <div class="form-group">
                                                <div x-id="['nomedocumentoinput']">
                                                    <label :for="$id('nomedocumentoinput')" class="form-label font-weight-bold">Nome do documento<span style="color: red;">*</span></label>
                                                    <div class="d-flex align-items-center">
                                                        <input :id="$id('nomedocumentoinput')" x-model="documento.nome" :name="'documentosExtra['+index+'][]'" type="text" class="form-control mr-1" placeholder="Digite o nome do documento aqui..." required>
                                                        <a @click="removeDocumento(index)" style="cursor: pointer">
                                                            <img width="20px;" src="{{asset('img/trashVermelho.svg')}}" alt="Apagar" title="Apagar">
                                                        </a>
                                                    </div>
                                                    @error('documentosExtra.*')
                                                    <div class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                                                        <strong>{{ $message }}</strong>
                                                    </div>
                                                    @enderror
                                                </div>
                                                <label class="col-form-label font-weight-bold ml-3">{{ __('Tipos de extensão aceitas') }}</label>
                                                <div class="form-row mr-0 ml-3 row-cols-4 row-cols-md-5">
                                                    <div>
                                                        <div class="form-check" x-id="['documentopdfcheck']">
                                                            <input :id="$id('documentopdfcheck')" class="form-check-input" type="checkbox" :name="'documentosExtra['+index+'][]'" x-model="documento.extensoes" value="pdf">
                                                            <label class="form-check-label" :for="$id('documentopdfcheck')">
                                                                .pdf
                                                            </label>
                                                        </div>
                                                        <div class="form-check" x-id="['documentodocxcheck']">
                                                            <input :id="$id('documentodocxcheck')" class="form-check-input" type="checkbox" :name="'documentosExtra['+index+'][]'" x-model="documento.extensoes" value="docx">
                                                            <label class="form-check-label" :for="$id('documentodocxcheck')">
                                                                .docx
                                                            </label>
                                                        </div>
                                                        <div class="form-check" x-id="['documentoodtcheck']">
                                                            <input :id="$id('documentoodtcheck')" class="form-check-input" type="checkbox" :name="'documentosExtra['+index+'][]'" x-model="documento.extensoes" value="odt">
                                                            <label class="form-check-label" :for="$id('documentoodtcheck')">
                                                                .odt
                                                            </label>
                                                        </div>
                                                        <div class="form-check" x-id="['documentopptxcheck']">
                                                            <input :id="$id('documentopptxcheck')" class="form-check-input" type="checkbox" :name="'documentosExtra['+index+'][]'" x-model="documento.extensoes" value="pptx">
                                                            <label class="form-check-label" :for="$id('documentopptxcheck')">
                                                                .pptx
                                                            </label>
                                                        </div>
                                                        <div class="form-check" x-id="['documentoodpcheck']">
                                                            <input :id="$id('documentoodpcheck')" class="form-check-input" type="checkbox" :name="'documentosExtra['+index+'][]'" x-model="documento.extensoes" value="odp">
                                                            <label class="form-check-label" :for="$id('documentoodpcheck')">
                                                                .odp
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="form-check" x-id="['documentoodscheck']">
                                                            <input :id="$id('documentoodscheck')" class="form-check-input" type="checkbox" :name="'documentosExtra['+index+'][]'" x-model="documento.extensoes" value="ods">
                                                            <label class="form-check-label" :for="$id('documentoodscheck')">
                                                                .ods
                                                            </label>
                                                        </div>
                                                        <div class="form-check" x-id="['documentoxlsxcheck']">
                                                            <input :id="$id('documentoxlsxcheck')" class="form-check-input" type="checkbox" :name="'documentosExtra['+index+'][]'" x-model="documento.extensoes" value="xlsx">
                                                            <label class="form-check-label" :for="$id('documentoxlsxcheck')">
                                                                .xlsx
                                                            </label>
                                                        </div>
                                                        <div class="form-check" x-id="['documentocsvcheck']">
                                                            <input :id="$id('documentocsvcheck')" class="form-check-input" type="checkbox" :name="'documentosExtra['+index+'][]'" x-model="documento.extensoes" value="csv">
                                                            <label class="form-check-label" :for="$id('documentocsvcheck')">
                                                                .csv
                                                            </label>
                                                        </div>
                                                        <div class="form-check" x-id="['documentozipcheck']">
                                                            <input :id="$id('documentozipcheck')" class="form-check-input" type="checkbox" :name="'documentosExtra['+index+'][]'" x-model="documento.extensoes" value="zip">
                                                            <label class="form-check-label" :for="$id('documentozipcheck')">
                                                                .zip
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="form-check" x-id="['documentomp3check']">
                                                            <input :id="$id('documentomp3check')" class="form-check-input" type="checkbox" :name="'documentosExtra['+index+'][]'" x-model="documento.extensoes" value="mp3">
                                                            <label class="form-check-label" :for="$id('documentomp3check')">
                                                                .mp3
                                                            </label>
                                                        </div>
                                                        <div class="form-check" x-id="['documentooggcheck']">
                                                            <input :id="$id('documentooggcheck')" class="form-check-input" type="checkbox" :name="'documentosExtra['+index+'][]'" x-model="documento.extensoes" value="ogg">
                                                            <label class="form-check-label" :for="$id('documentooggcheck')">
                                                                .ogg
                                                            </label>
                                                        </div>
                                                        <div class="form-check" x-id="['documentowavcheck']">
                                                            <input :id="$id('documentowavcheck')" class="form-check-input" type="checkbox" :name="'documentosExtra['+index+'][]'" x-model="documento.extensoes" value="wav">
                                                            <label class="form-check-label" :for="$id('documentowavcheck')">
                                                                .wav
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="form-check" x-id="['documentomp4check']">
                                                            <input :id="$id('documentomp4check')" class="form-check-input" type="checkbox" :name="'documentosExtra['+index+'][]'" x-model="documento.extensoes" value="mp4">
                                                            <label class="form-check-label" :for="$id('documentomp4check')">
                                                                .mp4
                                                            </label>
                                                        </div>
                                                        <div class="form-check" x-id="['documentoogvcheck']">
                                                            <input :id="$id('documentoogvcheck')" class="form-check-input" type="checkbox" :name="'documentosExtra['+index+'][]'" x-model="documento.extensoes" value="ogv">
                                                            <label class="form-check-label" :for="$id('documentoogvcheck')">
                                                                .ogv
                                                            </label>
                                                        </div>
                                                        <div class="form-check" x-id="['documentompgcheck']">
                                                            <input :id="$id('documentompgcheck')" class="form-check-input" type="checkbox" :name="'documentosExtra['+index+'][]'" x-model="documento.extensoes" value="mpg">
                                                            <label class="form-check-label" :for="$id('documentompgcheck')">
                                                                .mpg
                                                            </label>
                                                        </div>
                                                        <div class="form-check" x-id="['documentompegcheck']">
                                                            <input :id="$id('documentompegcheck')" class="form-check-input" type="checkbox" :name="'documentosExtra['+index+'][]'" x-model="documento.extensoes" value="mpeg">
                                                            <label class="form-check-label" :for="$id('documentompegcheck')">
                                                                .mpeg
                                                            </label>
                                                        </div>
                                                        <div class="form-check" x-id="['documentomkvcheck']">
                                                            <input :id="$id('documentomkvcheck')" class="form-check-input" type="checkbox" :name="'documentosExtra['+index+'][]'" x-model="documento.extensoes" value="mkv">
                                                            <label class="form-check-label" :for="$id('documentomkvcheck')">
                                                                .mkv
                                                            </label>
                                                        </div>
                                                        <div class="form-check" x-id="['documentoavicheck']">
                                                            <input :id="$id('documentoavicheck')" class="form-check-input" type="checkbox" :name="'documentosExtra['+index+'][]'" x-model="documento.extensoes" value="avi">
                                                            <label class="form-check-label" :for="$id('documentoavicheck')">
                                                                .avi
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="form-check" x-id="['documentojpgcheck']">
                                                            <input :id="$id('documentojpgcheck')" class="form-check-input" type="checkbox" :name="'documentosExtra['+index+'][]'" x-model="documento.extensoes" value="jpg">
                                                            <label class="form-check-label" :for="$id('documentojpgcheck')">
                                                                .jpg
                                                            </label>
                                                        </div>
                                                        <div class="form-check" x-id="['documentojpegcheck']">
                                                            <input :id="$id('documentojpegcheck')" class="form-check-input" type="checkbox" :name="'documentosExtra['+index+'][]'" x-model="documento.extensoes" value="jpeg">
                                                            <label class="form-check-label" :for="$id('documentojpegcheck')">
                                                                .jpeg
                                                            </label>
                                                        </div>
                                                        <div class="form-check" x-id="['documentopngcheck']">
                                                            <input :id="$id('documentopngcheck')" class="form-check-input" type="checkbox" :name="'documentosExtra['+index+'][]'" x-model="documento.extensoes" value="png">
                                                            <label class="form-check-label" :for="$id('documentopngcheck')">
                                                                .png
                                                            </label>
                                                        </div>
                                                        <div class="form-check" x-id="['documentosvgcheck']">
                                                            <input :id="$id('documentosvgcheck')" class="form-check-input" type="checkbox" :name="'documentosExtra['+index+'][]'" x-model="documento.extensoes" value="svg">
                                                            <label class="form-check-label" :for="$id('documentosvgcheck')">
                                                                .svg
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                    </>
                                    <div class="row justify-content-center">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary" style="width:100%">
                                                {{ __('Finalizar') }}
                                            </button>
                                        </div>
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
@parent
<script>
    function handler() {
        inicial = [];
        oldDatas = @json(old('datasExtras'));
        if (oldDatas != null) {
            for (let index = 0; index < oldDatas.length; index++) {
                inicial.push({
                    inicio: oldDatas[index].inicio,
                    fim: oldDatas[index].fim,
                    permitirSubmissao: oldDatas[index].permitirSubmissao,
                    nome: oldDatas[index].nome
                })

            }
        }
        return {
            datas: inicial,
            adicionaData() {
                this.datas.push({
                    inicio: '',
                    fim: '',
                    permitirSubmissao: '0',
                    nome: ''
                });
            },
            removeData(index) {
                this.datas.splice(index, 1);
            },
        }
    }

    function construct() {
        inicial = [];
        oldDocumentosExtra = @json(old('documentosExtra'));
        if (oldDocumentosExtra != null) {
            for (let index = 0; index < oldDocumentosExtra.length; index++) {
                inicial.push({
                    nome: oldDocumentosExtra[index][0],
                    extensoes: oldDocumentosExtra[index].slice(1),
                })

            }
        }
        return {
            documentos: inicial,
            adicionaDocumento() {
                this.documentos.push({
                    nome: '',
                    extensoes: [],
                });
            },
            removeDocumento(index) {
                this.documentos.splice(index, 1);
            },
        }
    }

    function mensagemSubmissao() {

        var input = document.getElementById("avaliacaoDuranteSubmissaocheck");

        // Remove o parâmetro "required"
        input.removeAttribute("required");
        alert('oi');
    }
</script>
@endsection
