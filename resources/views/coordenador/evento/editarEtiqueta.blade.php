@extends('coordenador.detalhesEvento')

@section('menu')
    {{-- Evento --}}
    <div id="divEditarEtiquetas" class="eventos" style="display: block">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="titulo-detalhes">Editar Etiquetas</h1>
            </div>
        </div>
        {{-- row card - Edição de Etiquetas --}}

        <div class="row justify-content-center">
            <div class="col-sm-10">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Modelo Atual - Card de Eventos</h5>
                        <p class="card-text">
                        <form method="POST" id="formCardEventos" action="{{route('etiquetas.update', $evento->id)}}">
                            @csrf

                            <div class="row justify-content-left">

                                <div class="col-sm-auto">
                                    <h4 id="classeh4">{{$etiquetas->etiquetanomeevento}}:</h4>
                                </div>
                                <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-nome" style="width:20px"></a>
                                {{-- <button type="button" id="botao-editar-nome" class="btn btn-outline-dark">Editar</button> --}}
                                <div class="col-sm-3" id="etiqueta-nome-evento" style="display: none">
                                    <input type="text" class="form-control etiquetanomeevento" id="etiquetanomeevento" name="etiquetanomeevento" placeholder="Editar Etiqueta">
                                </div>

                            </div>
                            <div class="row justify-content-left">
                                <div class="col-sm-12">
                                    <p>{{$evento->nome}}</p>
                                </div>
                            </div>


                            <div class="row justify-content-left">

                                <div class="col-sm-auto">
                                    <h4 id="classeh5">{{$etiquetas->etiquetadescricaoevento}}:</h4>
                                </div>
                                <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-descricao" style="width:20px"></a>
                                {{-- <button type="button" id="botao-editar-descricao" class="btn btn-outline-dark">Editar</button> --}}
                                <div class="col-sm-3" id="etiqueta-descricao-evento" style="display: none">
                                    <input type="text" class="form-control" id="etiquetadescricaoevento" name="etiquetadescricaoevento" placeholder="Editar Etiqueta">
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-sm-12">
                                    <p>{{$evento->descricao}}</p>
                                </div>
                            </div>

                            <div class="row justify-content-left">
                                <div class="col-sm-auto">
                                    <h4 id="classeh6">{{$etiquetas->etiquetatipoevento}}:</h4>
                                </div>
                                <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-tipo" style="width:20px"></a>
                                {{-- <button type="button" id="botao-editar-tipo" class="btn btn-outline-dark">Editar</button> --}}
                                <div class="col-sm-3" id="etiqueta-tipo-evento" style="display: none">
                                    <input type="text" class="form-control" id="etiquetatipoevento" name="etiquetatipoevento" placeholder="Editar Etiqueta">
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-sm-12">
                                    <p>{{$evento->tipo}}</p>
                                </div>
                            </div>

                            <div class="row justify-content-left">
                                <div class="col-sm-auto info-evento">
                                    <h4 id="classeh7">{{$etiquetas->etiquetadatas}}:</h4>
                                </div>
                                <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-datas" style="width:20px"></a>
                                {{-- <button type="button" id="botao-editar-datas" class="btn btn-outline-dark">Editar</button> --}}
                                <div class="col-sm-3" id="etiqueta-data-evento" style="display: none">
                                    <input type="text" class="form-control" id="etiquetadatas" name="etiquetadatas" placeholder="Editar Etiqueta">
                                </div>
                            </div>
                            <div class="row justify-content-left">
                                <div class="col-sm-12">
                                    <p>
                                        <img class="" alt="">
                                        Data: --/--/-- * --/--/--
                                    </p>
                                </div>
                            </div>

                            <div class="row justify-content-left">
                                <div class="col-sm-auto info-evento">
                                    <h4 id="classeh8">{{$etiquetas->etiquetasubmissoes}}:</h4>
                                </div>
                                <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-submissoes" style="width:20px"></a>
                                {{-- <button type="button" id="botao-editar-submissoes" class="btn btn-outline-dark">Editar</button> --}}
                                <div class="col-sm-3" id="etiqueta-submissoes-evento" style="display: none">
                                    <input type="text" class="form-control" id="etiquetasubmissoes" name="etiquetasubmissoes" placeholder="Editar Etiqueta">
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-sm-12">
                                    <h6>Modalidade: Nome da modalidade aqui</h6>
                                    <p>
                                        <img class="" alt="">
                                        Submissão datas: --/--/-- * --/--/--
                                    </p>
                                    <p>
                                        <img class="" alt="">
                                        Revisão datas: --/--/-- * --/--/--
                                    </p>
                                    <p>
                                        <img class="" alt="">
                                        Resultado data: --/--/--
                                    </p>
                                </div>
                            </div>

                            <div class="row justify-content-left">
                                <div class="col-sm-auto">
                                    <a>
                                        <img class="" src="{{asset('img/icons/file-download-solid.svg')}}" style="width:20px">
                                    </a>
                                    <label for="nomeTrabalho" class="col-form-label" id="classeh9">{{$etiquetas->etiquetabaixarregra}}:</label>
                                    <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-etiqueta-regra" style="width:20px"></a>
                                    {{-- <button type="button" id="botao-editar-etiqueta-regra" class="btn btn-outline-dark">Editar</button> --}}
                                </div>
                                <div class="col-sm-auto" id="etiqueta-baixar-regra-evento" style="display: none">
                                    <input type="text" class="form-control" id="etiquetabaixarregra" name="etiquetabaixarregra" placeholder="Editar Etiqueta">
                                </div>
                            </div>

                            <div class="row justify-content-left">
                                <div class="col-sm-auto">
                                    <a>
                                        <img class="" src="{{asset('img/icons/file-download-solid.svg')}}" style="width:20px">
                                    </a>
                                    <label for="nomeTrabalho" class="col-form-label" id="classeh10">{{$etiquetas->etiquetabaixartemplate}}:</label>
                                    <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-etiqueta-template" style="width:20px"></a>
                                    {{-- <button type="button" id="botao-editar-etiqueta-template" class="btn btn-outline-dark">Editar</button> --}}
                                </div>
                                <div class="col-sm-auto" id="etiqueta-baixar-template-evento" style="display: none">
                                    <input type="text" class="form-control" id="etiquetabaixartemplate" name="etiquetabaixartemplate" placeholder="Editar Etiqueta">
                                </div>
                            </div>

                            <div class="row justify-content-left" style="margin-top: 20px">
                                <div class="col-sm-auto info-evento">
                                    <h4 id="classeh11">{{$etiquetas->etiquetaenderecoevento}}:</h4>
                                </div>
                                <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-endereco" style="width:20px"></a>
                                {{-- <button type="button" id="botao-editar-endereco" class="btn btn-outline-dark">Editar</button> --}}
                                <div class="col-sm-auto" id="etiqueta-endereco-evento" style="display: none">
                                    <input type="text" class="form-control" id="etiquetaenderecoevento" name="etiquetaenderecoevento" placeholder="Editar Etiqueta">
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-sm-12"  style="margin-top: 10px">
                                    Local do evento aqui: {{$evento->endereco->rua}}, {{$evento->endereco->numero}} - {{$evento->endereco->cidade}} / {{$evento->endereco->uf}}.
                                </div>
                            </div>

                            <div class="row justify-content-left" style="margin-top: 10px">
                                <div class="col-sm-auto info-evento">
                                    <h4 id="classeh12">{{$etiquetas->etiquetamoduloinscricao}}:</h4>
                                </div>
                                <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-modulo-inscricao" style="width:20px"></a>
                                {{-- <button type="button" id="botao-editar-modulo-inscricao" class="btn btn-outline-dark">Editar</button> --}}
                                <div class="col-sm-3" id="etiqueta-modulo-inscricao-evento" style="display: none">
                                    <input type="text" class="form-control" id="etiquetamoduloinscricao" name="etiquetamoduloinscricao" placeholder="Editar Etiqueta">
                                </div>
                            </div>
                            <p style="margin-top: 10px">
                                Informações sobre inscrições
                            </p>

                            <div class="row justify-content-left">
                                <div class="col-sm-auto info-evento">
                                    <h4 id="classeh13">{{$etiquetas->etiquetamoduloprogramacao}}:</h4>
                                </div>
                                <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-modulo-programacao" style="width:20px"></a>
                                {{-- <button type="button" id="botao-editar-modulo-programacao" class="btn btn-outline-dark">Editar</button> --}}
                                <div class="col-sm-3" id="etiqueta-modulo-programacao-evento" style="display: none">
                                    <input type="text" class="form-control" id="etiquetamoduloprogramacao" name="etiquetamoduloprogramacao" placeholder="Editar Etiqueta">
                                </div>
                            </div>
                            <p style="margin-top: 10px">
                                Informações sobre programação
                            </p>

                            <div class="row justify-content-left">
                                <div class="col-sm-auto info-evento">
                                    <h4 id="classeh14">{{$etiquetas->etiquetamoduloorganizacao}}:</h4>
                                </div>
                                <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-modulo-organizacao" style="width:20px"></a>
                                {{-- <button type="button" id="botao-editar-modulo-organizacao" class="btn btn-outline-dark">Editar</button> --}}
                                <div class="col-sm-3" id="etiqueta-modulo-organizacao-evento" style="display: none">
                                    <input type="text" class="form-control" id="etiquetamoduloorganizacao" name="etiquetamoduloorganizacao" placeholder="Editar Etiqueta">
                                </div>
                            </div>
                            <p>
                                Informações sobre a organização
                            </p>
                        </form>
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <form method="POST" id="formCardEventosPadrao" action="{{route('etiquetas.update', $evento->id)}}">
                                    @csrf
                                    <input type="hidden" name="etiquetanomeevento"          value="Nome">
                                    <input type="hidden" name="etiquetatipoevento"          value="Tipo">
                                    <input type="hidden" name="etiquetadescricaoevento"     value="Descrição">
                                    <input type="hidden" name="etiquetadatas"               value="Realização">
                                    <input type="hidden" name="etiquetasubmissoes"          value="Submissões">
                                    <input type="hidden" name="etiquetaenderecoevento"      value="Endereço">
                                    <input type="hidden" name="etiquetamoduloinscricao"     value="Inscrições">
                                    <input type="hidden" name="etiquetamoduloprogramacao"   value="Programação">
                                    <input type="hidden" name="etiquetamoduloorganizacao"   value="Organização">
                                    <input type="hidden" name="etiquetabaixarregra"         value="Regras">
                                    <input type="hidden" name="etiquetabaixartemplate"      value="Template">

                                    <button type="submit" class="btn btn-primary" form="formCardEventosPadrao" onclick="return default_edicaoCardEvento()" style="width:100%">
                                        {{ __('Retornar ao Padrão') }}
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary" form="formCardEventos" style="width:100%">
                                    {{ __('Finalizar') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>{{-- end row card --}}

        {{-- Habilitar Modulos --}}
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Módulos</h5>
                        <h6 class="card-subtitle mb-2 text-muted">Escolha quais módulos serão exibidos</h6>
                        <form method="POST" action="{{route('exibir.modulo', $evento->id)}}">
                        @csrf

                        <p class="card-text">
                            <input type="hidden" name="modinscricao" value="false" id="modinscricao">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label for="modinscricao" class="col-form-label">{{ __('Inscrições') }}</label>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-sm-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" @if($etiquetas->modinscricao) checked @endif name="modinscricao" id="modinscricao">
                                        <label class="form-check-label" for="modinscricao">
                                          Habilitar
                                        </label>
                                    </div>

                                    @error('modinscricao')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>{{-- end row--}}
                        </p>

                        <p class="card-text">
                          <input type="hidden" name="modprogramacao" value="false" id="modprogramacao">
                          <div class="row">
                              <div class="col-sm-12">
                                  <label for="modprogramacao" class="col-form-label">{{ __('Programação') }}</label>
                              </div>
                          </div>
                          <div class="row justify-content-center">
                              <div class="col-sm-12">
                                  <div class="form-check">
                                      <input class="form-check-input" type="checkbox" @if($etiquetas->modprogramacao) checked @endif name="modprogramacao" id="modprogramacao">
                                      <label class="form-check-label" for="modprogramacao">
                                        Habilitar
                                      </label>
                                  </div>

                                  @error('modprogramacao')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                                  @enderror
                              </div>

                            </div>
                            <div class="row justify-content-center">
                                <div class="col-sm-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" @if($etiquetas->modprogramacao == true && $evento->exibir_calendario_programacao) checked @endif name="exibir_calendario" id="exibir_calendario">
                                        <label class="form-check-label" for="exibir_calendario">
                                          Exibir com calendário                                    
                                        </label>
                                      </div>
                                  </div>
    
                                  <div class="col-sm-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" @if($etiquetas->modprogramacao == true && !($evento->exibir_calendario_programacao)) checked @endif name="exibir_pdf" id="exibir_pdf">
                                        <label class="form-check-label" for="exibir_pdf">
                                          Exibir o pdf enviado                                    
                                        </label>
                                    </div>
                                </div>
                            </div>{{-- end row--}}

                        </p>

                        <p class="card-text">
                          <input type="hidden" name="modorganizacao" value="false" id="modorganizacao">
                          <div class="row">
                              <div class="col-sm-12">
                                  <label for="modorganizacao" class="col-form-label">{{ __('Organização e Apoio') }}</label>
                              </div>
                          </div>
                          <div class="row justify-content-center">
                              <div class="col-sm-12">

                                  <div class="form-check">
                                      <input class="form-check-input" type="checkbox" @if($etiquetas->modorganizacao) checked @endif name="modorganizacao" id="modorganizacao">
                                      <label class="form-check-label" for="modorganizacao">
                                        Habilitar
                                      </label>
                                  </div>

                                  @error('modorganizacao')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                                  @enderror
                              </div>

                          </div>{{-- end row--}}

                        </p>

                        <p class="card-text">
                          <input type="hidden" name="modsubmissao" value="false" id="modsubmissao">
                          <div class="row">
                              <div class="col-sm-12">
                                  <label for="modsubmissao" class="col-form-label">{{ __('Submissões de Trabalhos') }}</label>
                              </div>
                          </div>
                          <div class="row justify-content-center">
                              <div class="col-sm-12">

                                  <div class="form-check">
                                      <input class="form-check-input" type="checkbox" @if($etiquetas->modsubmissao) checked @endif name="modsubmissao" id="modsubmissao">
                                      <label class="form-check-label" for="modsubmissao">
                                        Habilitar
                                      </label>
                                  </div>

                                  @error('modsubmissao')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                                  @enderror
                              </div>

                          </div>{{-- end row--}}

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
            </div>
        </div>
        {{-- Fim --}}

    </div>

@endsection
