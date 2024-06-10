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
                        <hr>
                    <form method="POST" id="formCardEventos" action="{{route('etiquetas.update', $evento->id)}}">
                        @csrf

                        <div class="row justify-content-left">
                            <div class="container">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="col-sm-auto">
                                                <h4 id="classeh4">{{$etiquetas->etiquetanomeevento}}:</h4>
                                            </div>
                                            <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-nome" style="width:20px"></a>
                                            {{-- <button type="button" id="botao-editar-nome" class="btn btn-outline-dark">Editar</button> --}}
                                            <div class="col-sm-auto" id="etiqueta-nome-evento" style="display: none">
                                                <input type="text" class="form-control etiquetanomeevento" id="etiquetanomeevento" name="etiquetanomeevento" placeholder="Editar Etiqueta">
                                            </div>
                                        </div>
                                        <div class="row justify-content-left">
                                            <div class="col-sm-12">
                                                <p>{{$evento->nome}}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="row justify-content-left">
                                            <div class="col-sm-auto">
                                                <h4 id="classeh5">{{$etiquetas->etiquetadescricaoevento}}:</h4>
                                            </div>
                                            <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-descricao" style="width:20px"></a>
                                            {{-- <button type="button" id="botao-editar-descricao" class="btn btn-outline-dark">Editar</button> --}}
                                            <div class="col-sm-auto" id="etiqueta-descricao-evento" style="display: none">
                                                <input type="text" class="form-control" id="etiquetadescricaoevento" name="etiquetadescricaoevento" placeholder="Editar Etiqueta">
                                            </div>
                                        </div>
                                        <div class="row justify-content-center">
                                            <div class="col-sm-12">
                                                <p>{{$evento->descricao}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-left">
                            <div class="container">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="row justify-content-left">
                                            <div class="col-sm-auto">
                                                <h4 id="classeh6">{{$etiquetas->etiquetatipoevento}}:</h4>
                                            </div>
                                            <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-tipo" style="width:20px"></a>
                                            {{-- <button type="button" id="botao-editar-tipo" class="btn btn-outline-dark">Editar</button> --}}
                                            <div class="col-sm-auto" id="etiqueta-tipo-evento" style="display: none">
                                                <input type="text" class="form-control" id="etiquetatipoevento" name="etiquetatipoevento" placeholder="Editar Etiqueta">
                                            </div>
                                        </div>
                                        <div class="row justify-content-center">
                                            <div class="col-sm-12">
                                                <p>{{$evento->tipo}}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="row justify-content-left">
                                            <div class="col-sm-auto info-evento">
                                                <h4 id="classeh7">{{$etiquetas->etiquetadatas}}:</h4>
                                            </div>
                                            <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-datas" style="width:20px"></a>
                                            {{-- <button type="button" id="botao-editar-datas" class="btn btn-outline-dark">Editar</button> --}}
                                            <div class="col-sm-auto" id="etiqueta-data-evento" style="display: none">
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
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="container">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="row justify-content-left">
                                            <div class="col-sm-auto info-evento">
                                                <h4 id="classeh8">{{$etiquetas->etiquetasubmissoes}}:</h4>
                                            </div>
                                            <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-submissoes" style="width:20px"></a>
                                            {{-- <button type="button" id="botao-editar-submissoes" class="btn btn-outline-dark">Editar</button> --}}
                                            <div class="col-sm-auto" id="etiqueta-submissoes-evento" style="display: none">
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
                                    </div>
                                    <div class="col-sm-6">
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

                                        <div class="row justify-content-left">
                                            <div class="col-sm-auto">
                                                <a>
                                                    <img class="" src="{{asset('img/icons/file-download-solid.svg')}}" style="width:20px">
                                                </a>
                                                <label for="botao-editar-etiqueta-apresentacao" class="col-form-label" id="classeh9">{{$etiquetas->etiquetabaixarapresentacao}}:</label>
                                                <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-etiqueta-apresentacao" style="width:20px"></a>
                                                {{-- <button type="button" id="botao-editar-etiqueta-apresentacao" class="btn btn-outline-dark">Editar</button> --}}
                                            </div>
                                            <div class="col-sm-auto" id="etiqueta-baixar-apresentacao-evento" style="display: none">
                                                <input type="text" class="form-control" id="etiquetabaixarapresentacao" name="etiquetabaixarapresentacao" placeholder="Editar Etiqueta">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="container">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="row justify-content-left">
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
                                            <div class="col-sm-12">
                                                Local do evento aqui: {{$evento->endereco->rua}}, {{$evento->endereco->numero}} - {{$evento->endereco->cidade}} / {{$evento->endereco->uf}}.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="row justify-content-left">
                                            <div class="col-sm-auto info-evento">
                                                <h4 id="classeh12">{{$etiquetas->etiquetamoduloinscricao}}:</h4>
                                            </div>
                                            <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-modulo-inscricao" style="width:20px"></a>
                                            {{-- <button type="button" id="botao-editar-modulo-inscricao" class="btn btn-outline-dark">Editar</button> --}}
                                            <div class="col-sm-auto" id="etiqueta-modulo-inscricao-evento" style="display: none">
                                                <input type="text" class="form-control" id="etiquetamoduloinscricao" name="etiquetamoduloinscricao" placeholder="Editar Etiqueta">
                                            </div>
                                        </div>
                                        <div class="row justify-content-center">
                                            <div class="col-sm-12">
                                                <p>
                                                    Informações sobre inscrições
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="container">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="row justify-content-left">
                                            <div class="col-sm-auto info-evento">
                                                <h4 id="classeh13">{{$etiquetas->etiquetamoduloprogramacao}}:</h4>
                                            </div>
                                            <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-modulo-programacao" style="width:20px"></a>
                                            {{-- <button type="button" id="botao-editar-modulo-programacao" class="btn btn-outline-dark">Editar</button> --}}
                                            <div class="col-sm-auto" id="etiqueta-modulo-programacao-evento" style="display: none">
                                                <input type="text" class="form-control" id="etiquetamoduloprogramacao" name="etiquetamoduloprogramacao" placeholder="Editar Etiqueta">
                                            </div>
                                        </div>
                                        <div class="row justify-content-center">
                                            <div class="col-sm-12">
                                                <p>
                                                    Informações sobre programação
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="row justify-content-left">
                                            <div class="col-sm-auto info-evento">
                                                <h4 id="classeh14">{{$etiquetas->etiquetamoduloorganizacao}}:</h4>
                                            </div>
                                            <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-modulo-organizacao" style="width:20px"></a>
                                            {{-- <button type="button" id="botao-editar-modulo-organizacao" class="btn btn-outline-dark">Editar</button> --}}
                                            <div class="col-sm-auto" id="etiqueta-modulo-organizacao-evento" style="display: none">
                                                <input type="text" class="form-control" id="etiquetamoduloorganizacao" name="etiquetamoduloorganizacao" placeholder="Editar Etiqueta">
                                            </div>
                                        </div>
                                        <div class="row justify-content-center">
                                            <div class="col-sm-12">
                                                <p>
                                                    Informações sobre a organização
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <hr>
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary" form="formCardEventos" style="width:100%">
                                    {{ __('Finalizar') }}
                                </button>
                            </div>
                    </form>
                    <div class="col-md-6">
                        <form method="POST" id="formCardEventosPadrao" action="{{route('etiquetas.update', $evento->id)}}">
                            @csrf
                            <input type="hidden" name="etiquetanomeevento" value="Nome">
                            <input type="hidden" name="etiquetatipoevento" value="Tipo">
                            <input type="hidden" name="etiquetadescricaoevento" value="Descrição">
                            <input type="hidden" name="etiquetadatas" value="Realização">
                            <input type="hidden" name="etiquetasubmissoes" value="Submissões">
                            <input type="hidden" name="etiquetaenderecoevento" value="Endereço">
                            <input type="hidden" name="etiquetamoduloinscricao" value="Inscrições">
                            <input type="hidden" name="etiquetamoduloprogramacao" value="Programação">
                            <input type="hidden" name="etiquetamoduloorganizacao" value="Organização">
                            <input type="hidden" name="etiquetabaixarregra" value="Regras">
                            <input type="hidden" name="etiquetabaixartemplate" value="Template">
                            <input type="hidden" name="etiquetabaixarapresentacao" value="Modelo de apresentação">

                            <button type="submit" class="btn btn-primary" form="formCardEventosPadrao" onclick="return default_edicaoCardEvento()" style="width:100%">
                                {{ __('Retornar ao Padrão') }}
                            </button>
                        </form>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>{{-- end row card --}}



</div>

@endsection