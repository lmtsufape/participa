@extends('coordenador.detalhesEvento')

@section('menu')
    <div id="divListarModalidades" class="modalidades" style="display: block">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="titulo-detalhes">Listar Modalidades</h1>
            </div>
        </div>

        <div class="row justify-content-center">
            {{-- table modalidades --}}
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">Modalidades</h5>
                      <h6 class="card-subtitle mb-2 text-muted">Modalidades cadastradas no seu evento</h6>
                      <p class="card-text">
                        <table class="table table-hover table-responsive-lg table-sm">
                            <thead>
                            <tr>
                                <th scope="col">Nome</th>
                                <th scope="col">Editar</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($modalidades as $modalidade)
                                <tr>
                                    <td>{{$modalidade->nome}}</td>
                                    <td style="text-align:center">
                                        <a class="botaoAjax" href="#" data-toggle="modal" onclick="modalidadeId({{$modalidade->id}})" data-target="#modalEditarModalidade"><img src="{{asset('img/icons/edit-regular.svg')}}" style="width:20px"></a>
                                    </td>
                                </tr>
                                @endforeach


                            </tbody>
                        </table>
                      </p>
                    </div>
                  </div>

            </div>{{-- end table--}}

            {{-- table modalidades Área--}}
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">Áreas por Modalidade</h5>
                      <h6 class="card-subtitle mb-2 text-muted">Áreas correspondentes à cada modalidade do seu evento</h6>
                      <p class="card-text">
                        <table class="table table-hover table-responsive-lg table-sm">
                            <thead>
                              <tr>
                                <th scope="col">Modalidade</th>
                                <th scope="col">Área</th>
                              </tr>
                            </thead>
                            <tbody>
                                @foreach($areaModalidades as $areaModalidade)
                                  <tr>
                                    <td>{{$areaModalidade->modalidade->nome}}</td>
                                    <td>{{$areaModalidade->area->nome}}</td>
                                  </tr>
                                @endforeach


                            </tbody>
                          </table>
                      </p>
                    </div>
                  </div>

            </div>{{-- end table área--}}
        </div>
    </div>
        <div id="divClassificacao" class="classificacao">
            <h1>Classificação</h1>
        </div>
        <div id="divAtividades" class="atividades">
            <h1>Atividades</h1>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="modalEditarModalidade" tabindex="-1" role="dialog" aria-labelledby="modalEditarModalidade" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Editar Modalidade</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div>
                                        @error('marcarextensao')
                                          @include('componentes.mensagens')
                                        @enderror
                                    </div>
                                    <form method="POST" action="{{route('modalidade.update')}}" enctype="multipart/form-data">
                                    @csrf
                                    <p class="card-text">
                                        <input type="hidden" name="modalidadeEditId" id="modalidadeEditId" value="">
                                        <input type="hidden" name="eventoId" value="{{$evento->id}}">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <label for="nomeModalidadeEdit" class="col-form-label">*{{ __('Nome') }}</label>
                                            </div>
                                        </div>
                                        <div class="row justify-content-center">
                                            <div class="col-sm-12">
                                                <input id="nomeModalidadeEdit" type="text" class="form-control @error('nomeModalidadeEdit') is-invalid @enderror" name="nomeModalidadeEdit" value="" required autocomplete="nomeModalidadeEdit" autofocus>

                                                @error('nomeModalidadeEdit')
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
                                            <label for="inicioSubmissaoEdit" class="col-form-label">{{ __('Início da Submissão') }}</label>
                                            <input id="inicioSubmissaoEdit" type="date" class="form-control @error('inicioSubmissaoEdit') is-invalid @enderror" name="inicioSubmissaoEdit" value="{{ old('inicioSubmissaoEdit') }}" autocomplete="inicioSubmissaoEdit" autofocus>

                                            @error('inicioSubmissaoEdit')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="fimSubmissaoEdit" class="col-form-label">{{ __('Fim da Submissão') }}</label>
                                            <input id="fimSubmissaoEdit" type="date" class="form-control @error('fimSubmissaoEdit') is-invalid @enderror" name="fimSubmissaoEdit" value="{{ old('fimSubmissaoEdit') }}" autocomplete="fimSubmissaoEdit" autofocus>

                                            @error('fimSubmissaoEdit')
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
                                            <label for="inicioRevisaoEdit" class="col-form-label">{{ __('Início da Revisão') }}</label>
                                            <input id="inicioRevisaoEdit" type="date" class="form-control @error('inicioRevisaoEdit') is-invalid @enderror" name="inicioRevisaoEdit" value="{{ old('inicioRevisaoEdit') }}" autocomplete="inicioRevisaoEdit" autofocus>

                                            @error('inicioRevisaoEdit')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="fimRevisaoEdit" class="col-form-label">{{ __('Fim da Revisão') }}</label>
                                            <input id="fimRevisaoEdit" type="date" class="form-control @error('fimRevisaoEdit') is-invalid @enderror" name="fimRevisaoEdit" value="{{ old('fimRevisaoEdit') }}" autocomplete="fimRevisaoEdit" autofocus>

                                            @error('fimRevisaoEdit')
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
                                            <label for="inicioResultadoEdit" class="col-form-label">{{ __('Início do Resultado') }}</label>
                                            <input id="inicioResultadoEdit" type="date" class="form-control @error('inicioResultadoEdit') is-invalid @enderror" name="inicioResultadoEdit" value="{{ old('inicioResultadoEdit') }}" autocomplete="inicioResultadoEdit" autofocus>

                                            @error('inicioResultadoEdit')
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
                                            <label class="col-form-label">*{{ __('Restrições de resumo:') }}</label>

                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="limitEdit" id="id-limit-custom_field-accountEdit-1-1" value="limit-option1Edit">
                                                <label class="form-check-label" for="texto">
                                                    Quantidade de caracteres
                                                </label>
                                                </div>
                                                <div class="form-check">
                                                <input class="form-check-input" type="radio" name="limitEdit" id="id-limit-custom_field-accountEdit-1-2" value="limit-option2Edit">
                                                <label class="form-check-label" for="arquivo">
                                                    Quantidade de palavras
                                                </label>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-6" id="min-max-caracteresEdit" style="display: none">
                                                    <div class="form-group">
                                                        <label class="col-form-label">{{ __('MínimoC') }}</label>
                                                        <div>
                                                          <input class="form-control" type="number" id="mincaracteresEdit" name="mincaracteresEdit">
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-form-label">{{ __('MáximoC') }}</label>
                                                        <div>
                                                          <input class="form-control" type="number" id="maxcaracteresEdit" name="maxcaracteresEdit">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-6" id="min-max-palavrasEdit" style="display: none">
                                                    <div class="form-group">
                                                        <label class="col-form-label">{{ __('MínimoP') }}</label>
                                                        <div>
                                                          <input class="form-control" type="number" id="minpalavrasEdit" name="minpalavrasEdit">
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-form-label">{{ __('MáximoP') }}</label>
                                                        <div>
                                                          <input class="form-control" type="number" id="maxpalavrasEdit" name="maxpalavrasEdit">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-check" style="margin-top: 10px">
                                                <input class="form-check-input incluirarquivoEdit" type="checkbox" name="arquivoEdit" id="id-custom_field-accountEdit-1-2">
                                                <label class="form-check-label" for="arquivoEdit">
                                                    Incluir submissão por arquivo
                                                </label>
                                                @error('arquivoEdit')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6" id="tipo-arquivoEdit" style="display: none">

                                            <div class="titulo-detalhes" style="margin-top: 10px"></div>
                                            <label class="col-form-label">{{ __('Tipos de extensão aceitas') }}</label>

                                            <div class="form-check" style="margin-top: 10px">
                                                <input class="form-check-input" type="checkbox" id="pdfEdit" name="pdfEdit">
                                                <label class="form-check-label" for="pdfEdit">
                                                    .pdf
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="jpgEdit" name="jpgEdit">
                                                <label class="form-check-label" for="jpgEdit">
                                                    .jpg
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="jpegEdit" name="jpegEdit">
                                                <label class="form-check-label" for="jpegEdit">
                                                    .jpeg
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="pngEdit" name="pngEdit">
                                                <label class="form-check-label" for="pngEdit">
                                                    .png
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="docxEdit" name="docxEdit">
                                                <label class="form-check-label" for="docxEdit">
                                                    .docx
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="odtEdit" name="odtEdit">
                                                <label class="form-check-label" for="odtEdit">
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
                                            <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="arquivoRegrasEdit">
                                          </div>
                                          <small>O arquivo Selecionado deve ser no formato PDF de até 2mb.</small>
                                          @error('arquivoRegras')
                                          <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                                            <strong>{{ $message }}</strong>
                                          </span>
                                          @enderror
                                        </div>
                                        {{-- Arquivo de Templates --}}
                                        <div class="col-sm-12" id="area-templateEdit" style="margin-top: 20px; display:none">
                                            <label for="nomeTrabalho" class="col-form-label">{{ __('Enviar template:') }}</label>

                                            <div class="custom-file">
                                              <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="arquivoTemplatesEdit">
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
                    </div>{{-- end row card --}}


                    </div>
                </div>
                {{-- <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary">Save changes</button>
                </div> --}}
              </div>
            </div>
        </div>
        {{-- Fim Modal --}}
@endsection
