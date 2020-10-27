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
                                        <a href="#" data-toggle="modal" data-target="#modalEditarModalidade{{$modalidade->id}}"><img src="{{asset('img/icons/edit-regular.svg')}}" style="width:20px"></a>
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
            {{-- <div class="col-sm-6">
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

            </div> --}}
            {{-- end table área --}}
        </div>
    </div>
        <div id="divClassificacao" class="classificacao">
            <h1>Classificação</h1>
        </div>
        <div id="divAtividades" class="atividades">
            <h1>Atividades</h1>
        </div>


        @foreach ($modalidades as $modalidade)
        <!-- Modal -->
            <div class="modal fade" id="modalEditarModalidade{{$modalidade->id}}" tabindex="-1" role="dialog" aria-labelledby="modalEditarModalidade" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Editar {{$modalidade->nome}}</h5>
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
                                            <input type="hidden" name="modalidadeEditId" id="modalidadeEditId" value="{{$modalidade->id}}">
                                            <input type="hidden" name="eventoId" value="{{$evento->id}}">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <label for="nomeModalidadeEdit" class="col-form-label">*{{ __('Nome') }}</label>
                                                </div>
                                            </div>
                                            <div class="row justify-content-center">
                                                <div class="col-sm-12">
                                                <input id="nomeModalidadeEdit" type="text" class="form-control @error('nome') is-invalid @enderror" name="nome" value="@if(old('nome')!=null){{old('nome')}}@else{{$modalidade->nome}}@endif" required autocomplete="nomes" autofocus>

                                                    @error('nome')
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
                                                <input id="inicioSubmissaoEdit" type="date" class="form-control @error('inícioSubmissão') is-invalid @enderror" name="inícioSubmissão" value="@if(old('inícioSubmissão')!=null){{old('inícioSubmissão')}}@else{{$modalidade->inicioSubmissao}}@endif" autocomplete="inícioSubmissão" autofocus>

                                                @error('inícioSubmissão')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="fimSubmissaoEdit" class="col-form-label">{{ __('Fim da Submissão') }}</label>
                                                <input id="fimSubmissaoEdit" type="date" class="form-control @error('fimSubmissão') is-invalid @enderror" name="fimSubmissão" value="@if(old('fimSubmissão')!=null){{old('fimSubmissão')}}@else{{$modalidade->fimSubmissao}}@endif" autocomplete="fimSubmissão" autofocus>

                                                @error('fimSubmissão')
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
                                                <input id="inicioRevisaoEdit" type="date" class="form-control @error('inícioRevisão') is-invalid @enderror" name="inícioRevisão" value="@if(old('inícioRevisão')!=null){{old('inícioRevisão')}}@else{{$modalidade->inicioRevisao}}@endif" autocomplete="inícioRevisão" autofocus>

                                                @error('inícioRevisão')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="fimRevisaoEdit" class="col-form-label">{{ __('Fim da Revisão') }}</label>
                                                <input id="fimRevisaoEdit" type="date" class="form-control @error('fimRevisão') is-invalid @enderror" name="fimRevisão" value="@if(old('fimRevisão')!=null){{old('fimRevisão')}}@else{{$modalidade->fimRevisao}}@endif" autocomplete="fimRevisão" autofocus>

                                                @error('fimRevisão')
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
                                                <input id="inicioResultado" type="date" class="form-control @error('inicioResultado') is-invalid @enderror" name="inicioResultado" value="@if(old('inicioResultado')){{old('inicioResultado')}}@else{{$modalidade->inicioResultado}}@endif" autocomplete="inicioResultado" autofocus>

                                                @error('inicioResultado')
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
                                                    <input class="form-check-input" type="radio" name="limitEdit" onchange="exibirLimite({{$modalidade->id}},this)" value="caracteres" id="id-limit-custom_field-accountEdit-1-1" @if (old('limitEdit') == 'caracteres') checked @else @if($modalidade->caracteres) checked @endif @endif>
                                                    <label class="form-check-label" for="texto">
                                                        Quantidade de caracteres
                                                    </label>
                                                    </div>
                                                    <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="limitEdit" onchange="exibirLimite({{$modalidade->id}},this)" value="palavras" id="id-limit-custom_field-accountEdit-1-2" @if (old('limitEdit') == 'palavras') checked @else @if($modalidade->palavras) checked @endif @endif>
                                                    <label class="form-check-label" for="arquivo">
                                                        Quantidade de palavras
                                                    </label>
                                                </div>

                                                
                                                <div id="caracteres{{$modalidade->id}}" class="row" style="@if (old('limitEdit') == 'caracteres')display:block;@else @if($modalidade->caracteres && old('limitEdit') == null)display:block;@else display:none;@endif @endif">
                                                    <div class="col-sm-6" id="min-max-caracteresEdit" >
                                                        <div class="form-group">
                                                            <label class="col-form-label">{{ __('Mínimo') }}</label>
                                                            <div>
                                                            <input class="form-control" type="number" id="mincaracteres" name="mincaracteres" value="@if(old('mincaracteres')!=null){{old('mincaracteres')}}@else{{$modalidade->mincaracteres}}@endif">
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="col-form-label">{{ __('Máximo') }}</label>
                                                            <div>
                                                            <input class="form-control" type="number" id="maxcaracteres" name="maxcaracteres" value="@if(old('maxcaracteres')!=null){{old('maxcaracteres')}}@else{{$modalidade->maxcaracteres}}@endif">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div id="palavras{{$modalidade->id}}" class="row" style="@if (old('limitEdit') == 'palavras')display:block;@else @if($modalidade->palavras && old('limitEdit') == null)display:block;@else display:none;@endif @endif">
                                                    <div class="col-sm-6" id="min-max-palavrasEdit">
                                                        <div class="form-group">
                                                            <label class="col-form-label">{{ __('Mínimo') }}</label>
                                                            <div>
                                                            <input class="form-control" type="number" id="minpalavras" name="minpalavras" value="@if(old('minpalavras')!=null){{old('minpalavras')}}@else{{$modalidade->minpalavras}}@endif">
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="col-form-label">{{ __('Máximo') }}</label>
                                                            <div>
                                                            <input class="form-control" type="number" id="maxpalavras" name="maxpalavras" value="@if(old('maxpalavras')!=null){{old('maxpalavras')}}@else{{$modalidade->maxpalavras}}@endif">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-check" style="margin-top: 10px">
                                                    <input class="form-check-input incluirarquivoEdit" type="checkbox" onchange="exibirTiposArquivo({{$modalidade->id}},this)" name="arquivoEdit" id="id-custom_field-accountEdit-1-2" @if(old('arquivoEdit') == "on") checked @else @if ($modalidade->arquivo == true) checked @endif @endif>
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

                                        <div class="row tiposDeArquivos{{$modalidade->id}}" style="@if(old('arquivoEdit')=="on") display: block @else @if($modalidade->arquivo == true) display: block @else display: none @endif @endif">
                                            <div class="col-sm-6" id="tipo-arquivoEdit">

                                                <div class="titulo-detalhes" style="margin-top: 10px"></div>
                                                <label class="col-form-label">{{ __('Tipos de extensão aceitas') }}</label>

                                                <div class="form-check" style="margin-top: 10px">
                                                    <input class="form-check-input" type="checkbox" id="pdfEdit" name="pdfEdit" @if(old('pdfEdit') == "on") checked @else @if ($modalidade->pdf == true) checked @endif @endif>
                                                    <label class="form-check-label" for="pdfEdit">
                                                        .pdf
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="jpgEdit" name="jpgEdit" @if(old('jpgEdit') == "on") checked @else @if ($modalidade->jpg == true) checked @endif @endif>
                                                    <label class="form-check-label" for="jpgEdit">
                                                        .jpg
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="jpegEdit" name="jpegEdit" @if(old('jpegEdit') == "on") checked @else @if ($modalidade->jpeg == true) checked @endif @endif>
                                                    <label class="form-check-label" for="jpegEdit">
                                                        .jpeg
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="pngEdit" name="pngEdit" @if(old('pngEdit') == "on") checked @else @if ($modalidade->png == true) checked @endif @endif>
                                                    <label class="form-check-label" for="pngEdit">
                                                        .png
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="docxEdit" name="docxEdit" @if(old('docxEdit') == "on") checked @else @if ($modalidade->docx == true) checked @endif @endif>
                                                    <label class="form-check-label" for="docxEdit">
                                                        .docx
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="odtEdit" name="odtEdit" @if(old('odtEdit') == "on") checked @else @if ($modalidade->odt == true) checked @endif @endif>
                                                    <label class="form-check-label" for="odtEdit">
                                                        .odt
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row justify-content-center">
                                            {{-- Arquivo de Regras  --}}
                                            <div class="col-sm-12" style="margin-top: 20px;">
                                                <label for="arquivoRegras" class="col-form-label">{{ __('Enviar regras:') }}</label> @if ($modalidade->regra != null) <a href="{{route('modalidade.regras.download', ['id' => $modalidade->id])}}">Arquivo atual</a> @endif

                                            <div class="custom-file">
                                                <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="arquivoRegras">
                                            </div>
                                            <small>O arquivo Selecionado deve ser no formato PDF de até 2mb.</small><br>
                                            <small>Se deseja alterar o arquivo, envie a nova versão.</small>
                                            @error('arquivoRegras')
                                            <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                            </div>
                                            {{-- Arquivo de Templates --}}
                                            <div class="col-sm-12 tiposDeArquivos{{$modalidade->id}}" id="area-templateEdit" style="@if(old('arquivoEdit')=="on") display: block @else @if($modalidade->arquivo == true) display: block @else display: none @endif @endif">
                                                <label for="nomeTrabalho" class="col-form-label">{{ __('Enviar template:') }}</label> @if ($modalidade->template != null) <a href="{{route('modalidade.template.download', ['id' => $modalidade->id])}}">Arquivo atual</a> @endif

                                                <div class="custom-file">
                                                <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="arquivoTemplates">
                                                </div>
                                                <small>O arquivo Selecionado deve ser no formato PDF de até 2mb.</small><br>
                                                <small>Se deseja alterar o arquivo, envie a nova versão.</small>
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
        @endforeach
        {{-- Fim Modal --}}
@endsection
