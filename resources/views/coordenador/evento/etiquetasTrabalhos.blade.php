@extends('coordenador.detalhesEvento')

@section('menu')

    {{-- Submissão de Trabalhos - edição de etiquetas --}}
    <div id="divEditarEtiquetasSubTrabalho" class="eventos" style="display: block">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="titulo-detalhes">Editar Etiquetas</h1>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-sm-10">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Modelo Atual - Form de Submissão de Trabalhos</h5>
                        <p class="card-text">
                        <form id="formSubmTrabaEtiquetas" method="POST" action="{{route('etiquetas_sub_trabalho.update', $evento->id)}}">
                        @csrf
                        <?php
                            $ordemCampos = explode(",", $etiquetasSubTrab->ordemCampos);
                        ?>
                        @foreach ($ordemCampos as $indice)
                            @if ($indice == "etiquetatitulotrabalho")
                                <div class="card" id="bisavo">
                                    <div class="card-body">
                                        <div class="row" id="1" value="1">
                                            <div class="col-sm-auto">
                                                <label for="nomeTrabalho" class="col-form-label" id="classeh15">{{$etiquetasSubTrab->etiquetatitulotrabalho}}:</label>
                                            </div>
                                            @if($evento->is_multilingual)
                                            <div class="col-sm-auto">
                                                <img src="https://flagicons.lipis.dev/flags/4x3/us.svg" alt="English" style="width: 20px;">
                                                <label for="nomeTrabalho" class="col-form-label" id="classeh15">{{$etiquetasSubTrab->etiquetatitulotrabalho_en}}:</label>
                                            </div>
                                            @endif
                                            <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-titulo" style="width:20px"></a>
                                            {{-- <button type="button" id="botao-editar-titulo" class="btn btn-outline-dark">Editar</button> --}}
                                            <div class="col-sm-3" id="etiqueta-titulo-trabalho" style="display: none">
                                                <input type="text" class="form-control" id="etiquetatitulotrabalho" name="etiquetatitulotrabalho" placeholder="Editar Etiqueta">
                                                @if($evento->is_multilingual)
                                                <input type="text" class="form-control" id="etiquetatitulotrabalho_en" name="etiquetatitulotrabalho_en" placeholder="Editar Etiqueta em inglês">
                                                @endif
                                            </div>
                                            <a class="move-up"><img src="{{asset('img/icons/sobe.png')}}" id="antesTitulo" style="width:20px; margin-left:10px"></a>
                                            <a class="move-down"><img src="{{asset('img/icons/desce.png')}}" id="depoisTitulo" style="width:20px"></a>
                                            <input id="nomeTrabalho" type="text" class="form-control" style="margin-top: 10px" disabled><br/>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($indice == "etiquetaautortrabalho")
                                <div class="card" id="bisavo">
                                    <div class="card-body">
                                        <div class="row justify-content-left" id="2" style="margin-top: 10px">
                                            <div class="col-sm-auto">
                                                <label for="nomeTrabalho" class="col-form-label" id="classeh16">{{$etiquetasSubTrab->etiquetaautortrabalho}}:</label>
                                            </div>
                                            <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-autor" style="width:20px"></a>
                                            {{-- <button type="button" id="botao-editar-autor" class="btn btn-outline-dark">Editar</button> --}}
                                            <div class="col-sm-3" id="etiqueta-autor-trabalho" style="display: none">
                                                <input type="text" class="form-control" style="margin-top: 10px" id="etiquetaautortrabalho" name="etiquetaautortrabalho" placeholder="Editar Etiqueta">
                                            </div>
                                            <a class="move-up"><img src="{{asset('img/icons/sobe.png')}}" id="antesAutor" style="width:20px; margin-left:10px"></a>
                                            <a class="move-down"><img src="{{asset('img/icons/desce.png')}}" id="depoisAutor" style="width:20px"></a>
                                            <input class="form-control" type="text" style="margin-top: 10px" disabled><br/>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($indice == "etiquetacoautortrabalho")
                                <div class="card" id="bisavo">
                                    <div class="card-body">
                                        <div class="row justify-content-left" id="3" style="margin-top: 10px">
                                            <div class="col-sm-auto">
                                            <a href="#" class="btn btn-primary" id="classeh17" style="width:100%;margin-top:10px" disabled>{{$etiquetasSubTrab->etiquetacoautortrabalho}}:</a>
                                            </div>
                                            <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-coautor" style="width:20px"></a>
                                            {{-- <button type="button" id="botao-editar-coautor" class="btn btn-outline-dark">Editar</button> --}}
                                            <div class="col-sm-3" id="etiqueta-coautor-trabalho" style="display: none">
                                                <input type="text" class="form-control" id="etiquetacoautortrabalho" name="etiquetacoautortrabalho" placeholder="Editar Etiqueta">
                                            </div>
                                            <a class="move-up"><img src="{{asset('img/icons/sobe.png')}}" id="antesAutor" style="width:20px; margin-left:10px"></a>
                                            <a class="move-down"><img src="{{asset('img/icons/desce.png')}}" id="depoisAutor" style="width:20px"></a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($indice == "etiquetaresumotrabalho")
                                <div class="card" id="bisavo">
                                    <div class="card-body">
                                        <div class="row justify-content-left">
                                            <div class="col-sm-auto">
                                                <label for="resumo" class="col-form-label" id="classeh18">{{$etiquetasSubTrab->etiquetaresumotrabalho}}:</label>
                                            </div>
                                            @if($evento->is_multilingual)
                                                <div class="col-sm-auto">
                                                    <img src="https://flagicons.lipis.dev/flags/4x3/us.svg" alt="English" style="width: 20px;">
                                                    <label for="resumo_en" class="col-form-label" id="classeh18">{{$etiquetasSubTrab->etiquetaresumotrabalho_en}}:</label>
                                                </div>
                                            @endif
                                            <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-resumo" style="width:20px"></a>
                                            {{-- <button type="button" id="botao-editar-resumo" class="btn btn-outline-dark">Editar</button> --}}
                                            <div class="col-sm-auto" id="etiqueta-resumo-trabalho" style="display: none">
                                                <input type="text" class="form-control" id="etiquetaresumotrabalho" name="etiquetaresumotrabalho" placeholder="Editar Etiqueta">
                                                @if($evento->is_multilingual)
                                                <input type="text" class="form-control" id="etiquetaresumotrabalho_en" name="etiquetaresumotrabalho_en" placeholder="Editar Etiqueta em Inglês">
                                                @endif
                                            </div>
                                            <a class="move-up"><img src="{{asset('img/icons/sobe.png')}}" id="antesAutor" style="width:20px; margin-left:10px"></a>
                                            <a class="move-down"><img src="{{asset('img/icons/desce.png')}}" id="depoisAutor" style="width:20px"></a>
                                            <textarea id="resumo" class="char-count form-control @error('resumo') is-invalid @enderror" data-ls-module="charCounter" style="margin-top: 10px" disabled></textarea>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($indice == "etiquetaareatrabalho")
                                <div class="card" id="bisavo">
                                    <div class="card-body">
                                        <!-- Areas -->
                                        <div class="row justify-content-left">
                                            <div class="col-auto">
                                                <label for="area" class="col-form-label" id="classeh19">{{$etiquetasSubTrab->etiquetaareatrabalho}}:</label>
                                            </div>
                                            <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-area" style="width:20px"></a>
                                            {{-- <button type="button" id="botao-editar-area" class="btn btn-outline-dark">Editar</button> --}}
                                            <div class="col-sm-auto" id="etiqueta-area-trabalho" style="display: none">
                                                <input type="text" class="form-control" id="etiquetaareatrabalho" name="etiquetaareatrabalho" placeholder="Editar Etiqueta">
                                            </div>
                                            <a class="move-up"><img src="{{asset('img/icons/sobe.png')}}" id="antesAutor" style="width:20px; margin-left:10px"></a>
                                            <a class="move-down"><img src="{{asset('img/icons/desce.png')}}" id="depoisAutor" style="width:20px"></a>
                                            <select class="form-control @error('area') is-invalid @enderror" id="area" name="areaId" style="margin-top: 10px" disabled>
                                                <option value="" disabled selected hidden>-- Área --</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($indice == "etiquetauploadtrabalho")
                                <div class="card" id="bisavo">
                                    <div class="card-body">
                                        <div class="row justify-content-left">
                                            {{-- Arquivo --}}
                                            <div class="col-sm-auto">
                                                <label for="nomeTrabalho" class="col-form-label" id="classeh20">{{$etiquetasSubTrab->etiquetauploadtrabalho}}:</label>
                                            </div>
                                            <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-upload" style="width:20px"></a>
                                            {{-- <button type="button" id="botao-editar-upload" class="btn btn-outline-dark">Editar</button> --}}
                                            <div class="col-sm-auto" id="etiqueta-upload-trabalho" style="display: none">
                                                <input type="text" class="form-control" id="etiquetauploadtrabalho" name="etiquetauploadtrabalho" placeholder="Editar Etiqueta">
                                            </div>
                                            <a class="move-up"><img src="{{asset('img/icons/sobe.png')}}" id="antesAutor" style="width:20px; margin-left:10px"></a>
                                            <a class="move-down"><img src="{{asset('img/icons/desce.png')}}" id="depoisAutor" style="width:20px"></a>
                                        </div>
                                        <div class="custom-file" style="margin-top: 10px">
                                            <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" disabled>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($indice == "etiquetacampoextra1")
                                <div class="card" id="bisavo">
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <input type="hidden" name="checkcampoextra1" value="false" id="checkcampoextra1">
                                            <label for="etiquetacampoextra1" class="col-sm-auto col-form-label" id="classeh21">{{$etiquetasSubTrab->etiquetacampoextra1}}:</label>
                                            @if($evento->is_multilingual)
                                            <img src="https://flagicons.lipis.dev/flags/4x3/us.svg" alt="English" style="width: 20px;">
                                            <label for="etiquetacampoextra1_en" class="col-form-label" id="classeh21">{{$etiquetasSubTrab->etiquetacampoextra1_en}}:</label>
                                            @endif
                                            <div class="col-sm-auto">
                                            <input type="text" class="form-control" id="etiquetacampoextra1" name="etiquetacampoextra1" placeholder="Editar Etiqueta">
                                            @if($evento->is_multilingual)
                                            <input type="text" class="form-control" id="etiquetacampoextra1_en" name="etiquetacampoextra1_en" placeholder="Editar Etiqueta em Inglês">
                                            @endif
                                            </div>
                                            <div class="form-group col-sm-3">
                                                <select class="form-control" id="exampleFormControlSelect1" name="select_campo1">
                                                @if ($etiquetasSubTrab->tipocampoextra1 == "textosimples")
                                                    <option value="textosimples" selected>Texto Simples</option>
                                                    <option value="textogrande">Texto grande</option>
                                                    <option value="upload">Upload</option>
                                                @elseif ($etiquetasSubTrab->tipocampoextra1 == "textogrande")
                                                    <option value="textosimples">Texto Simples</option>
                                                    <option value="textogrande" selected>Texto grande</option>
                                                    <option value="upload">Upload</option>
                                                @elseif ($etiquetasSubTrab->tipocampoextra1 == "upload")
                                                    <option value="textosimples">Texto Simples</option>
                                                    <option value="textogrande">Texto grande</option>
                                                    <option value="upload" selected>Upload</option>
                                                @else
                                                    <option value="textosimples" selected>Texto Simples</option>
                                                    <option value="textogrande">Texto grande</option>
                                                    <option value="upload" selected>Upload</option>
                                                @endif
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-2">
                                                <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="gridCheck" @if($etiquetasSubTrab->checkcampoextra1) checked @endif name="checkcampoextra1">
                                                <label class="form-check-label" for="gridCheck">
                                                    Exibir
                                                </label>
                                                </div>
                                            </div>
                                            <a class="move-up"><img src="{{asset('img/icons/sobe.png')}}" id="antesAutor" style="width:20px; margin-left:10px"></a>
                                            <a class="move-down"><img src="{{asset('img/icons/desce.png')}}" id="depoisAutor" style="width:20px"></a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($indice == "etiquetacampoextra2")
                                <div class="card" id="bisavo">
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <input type="hidden" name="checkcampoextra2" value="false" id="checkcampoextra2">
                                            <label for="etiquetacampoextra2" class="col-sm-auto col-form-label" id="classeh22">{{$etiquetasSubTrab->etiquetacampoextra2}}:</label>
                                            @if($evento->is_multilingual)
                                            <img src="https://flagicons.lipis.dev/flags/4x3/us.svg" alt="English" style="width: 20px;">
                                            <label for="etiquetacampoextra2_en" class="col-form-label" id="classeh22">{{$etiquetasSubTrab->etiquetacampoextra2_en}}:</label>
                                            @endif
                                            <div class="col-sm-auto">
                                            <input type="text" class="form-control" id="etiquetacampoextra2" name="etiquetacampoextra2" placeholder="Editar Etiqueta">
                                            @if($evento->is_multilingual)
                                            <input type="text" class="form-control" id="etiquetacampoextra2_en" name="etiquetacampoextra2_en" placeholder="Editar Etiqueta em Inglês">
                                            @endif
                                            </div>
                                            <div class="form-group col-sm-3">
                                                <select class="form-control" id="exampleFormControlSelect1" name="select_campo2">
                                                    @if ($etiquetasSubTrab->tipocampoextra2 == "textosimples")
                                                    <option value="textosimples" selected>Texto Simples</option>
                                                    <option value="textogrande">Texto grande</option>
                                                    <option value="upload">Upload</option>
                                                    @elseif ($etiquetasSubTrab->tipocampoextra2 == "textogrande")
                                                    <option value="textosimples">Texto Simples</option>
                                                    <option value="textogrande" selected>Texto grande</option>
                                                    <option value="upload">Upload</option>
                                                    @elseif ($etiquetasSubTrab->tipocampoextra2 == "upload")
                                                    <option value="textosimples">Texto Simples</option>
                                                    <option value="textogrande">Texto grande</option>
                                                    <option value="upload" selected>Upload</option>
                                                    @else
                                                    <option value="textosimples" selected>Texto Simples</option>
                                                    <option value="textogrande">Texto grande</option>
                                                    <option value="upload" selected>Upload</option>
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-2">
                                                <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="gridCheck" @if($etiquetasSubTrab->checkcampoextra2) checked @endif name="checkcampoextra2">
                                                <label class="form-check-label" for="gridCheck">
                                                    Exibir
                                                </label>
                                                </div>
                                            </div>
                                            <a class="move-up"><img src="{{asset('img/icons/sobe.png')}}" id="antesAutor" style="width:20px; margin-left:10px"></a>
                                            <a class="move-down"><img src="{{asset('img/icons/desce.png')}}" id="depoisAutor" style="width:20px"></a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($indice == "etiquetacampoextra3")
                                <div class="card" id="bisavo">
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <input type="hidden" name="checkcampoextra3" value="false" id="checkcampoextra3">
                                            <label for="inputEmail3" class="col-sm-auto col-form-label" id="classeh23">{{$etiquetasSubTrab->etiquetacampoextra3}}:</label>
                                            @if($evento->is_multilingual)
                                            <img src="https://flagicons.lipis.dev/flags/4x3/us.svg" alt="English" style="width: 20px;">
                                            <label for="etiquetacampoextra3_en" class="col-form-label" id="classeh23">{{$etiquetasSubTrab->etiquetacampoextra3_en}}:</label>
                                            @endif
                                            <div class="col-sm-auto">
                                            <input type="text" class="form-control" id="etiquetacampoextra3" name="etiquetacampoextra3" placeholder="Editar Etiqueta">
                                            @if($evento->is_multilingual)
                                            <input type="text" class="form-control" id="etiquetacampoextra3_en" name="etiquetacampoextra3_en" placeholder="Editar Etiqueta em Inglês">
                                            @endif
                                            </div>
                                            <div class="form-group col-sm-3">
                                                <select class="form-control" id="exampleFormControlSelect1" name="select_campo3">
                                                    @if ($etiquetasSubTrab->tipocampoextra3 == "textosimples")
                                                    <option value="textosimples" selected>Texto Simples</option>
                                                    <option value="textogrande">Texto grande</option>
                                                    <option value="upload">Upload</option>
                                                    @elseif ($etiquetasSubTrab->tipocampoextra3 == "textogrande")
                                                    <option value="textosimples">Texto Simples</option>
                                                    <option value="textogrande" selected>Texto grande</option>
                                                    <option value="upload">Upload</option>
                                                    @elseif ($etiquetasSubTrab->tipocampoextra3 == "upload")
                                                    <option value="textosimples">Texto Simples</option>
                                                    <option value="textogrande">Texto grande</option>
                                                    <option value="upload" selected>Upload</option>
                                                    @else
                                                    <option value="textosimples" selected>Texto Simples</option>
                                                    <option value="textogrande">Texto grande</option>
                                                    <option value="upload" selected>Upload</option>
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-2">
                                                <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="gridCheck" @if($etiquetasSubTrab->checkcampoextra3) checked @endif name="checkcampoextra3">
                                                <label class="form-check-label" for="gridCheck">
                                                    Exibir
                                                </label>
                                                </div>
                                            </div>
                                            <a class="move-up"><img src="{{asset('img/icons/sobe.png')}}" id="antesAutor" style="width:20px; margin-left:10px"></a>
                                            <a class="move-down"><img src="{{asset('img/icons/desce.png')}}" id="depoisAutor" style="width:20px"></a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($indice == "etiquetacampoextra4")
                                <div class="card" id="bisavo">
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <input type="hidden" name="checkcampoextra4" value="false" id="checkcampoextra4">
                                            <label for="inputEmail3" class="col-sm-auto col-form-label" id="classeh24">{{$etiquetasSubTrab->etiquetacampoextra4}}:</label>
                                            @if($evento->is_multilingual)
                                            <img src="https://flagicons.lipis.dev/flags/4x3/us.svg" alt="English" style="width: 20px;">
                                            <label for="etiquetacampoextra4_en" class="col-form-label" id="classeh24">{{$etiquetasSubTrab->etiquetacampoextra4_en}}:</label>
                                            @endif
                                            <div class="col-sm-auto">
                                            <input type="text" class="form-control" id="etiquetacampoextra4" name="etiquetacampoextra4" placeholder="Editar Etiqueta">
                                            @if($evento->is_multilingual)
                                            <input type="text" class="form-control" id="etiquetacampoextra4_en" name="etiquetacampoextra4_en" placeholder="Editar Etiqueta em Inglês">
                                            @endif
                                            </div>
                                            <div class="form-group col-sm-3">
                                                <select class="form-control" id="exampleFormControlSelect1" name="select_campo4">
                                                    @if ($etiquetasSubTrab->tipocampoextra4 == "textosimples")
                                                    <option value="textosimples" selected>Texto Simples</option>
                                                    <option value="textogrande">Texto grande</option>
                                                    <option value="upload">Upload</option>
                                                    @elseif ($etiquetasSubTrab->tipocampoextra4 == "textogrande")
                                                    <option value="textosimples">Texto Simples</option>
                                                    <option value="textogrande" selected>Texto grande</option>
                                                    <option value="upload">Upload</option>
                                                    @elseif ($etiquetasSubTrab->tipocampoextra4 == "upload")
                                                    <option value="textosimples">Texto Simples</option>
                                                    <option value="textogrande">Texto grande</option>
                                                    <option value="upload" selected>Upload</option>
                                                    @else
                                                    <option value="textosimples" selected>Texto Simples</option>
                                                    <option value="textogrande">Texto grande</option>
                                                    <option value="upload" selected>Upload</option>
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-2">
                                                <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="gridCheck" @if($etiquetasSubTrab->checkcampoextra4) checked @endif name="checkcampoextra4">
                                                <label class="form-check-label" for="gridCheck">
                                                    Exibir
                                                </label>
                                                </div>
                                            </div>
                                            <a class="move-up"><img src="{{asset('img/icons/sobe.png')}}" id="antesAutor" style="width:20px; margin-left:10px"></a>
                                            <a class="move-down"><img src="{{asset('img/icons/desce.png')}}" id="depoisAutor" style="width:20px"></a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($indice == "etiquetacampoextra5")
                                <div class="card" id="bisavo">
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <input type="hidden" name="checkcampoextra5" value="false" id="checkcampoextra5">
                                            <label for="inputEmail3" class="col-sm-auto col-form-label" id="classeh25">{{$etiquetasSubTrab->etiquetacampoextra5}}:</label>
                                            @if($evento->is_multilingual)
                                            <img src="https://flagicons.lipis.dev/flags/4x3/us.svg" alt="English" style="width: 20px;">
                                            <label for="etiquetacampoextra5_en" class="col-form-label" id="classeh25">{{$etiquetasSubTrab->etiquetacampoextra4_en}}:</label>
                                            @endif
                                            <div class="col-sm-auto">
                                            <input type="text" class="form-control" id="etiquetacampoextra5" name="etiquetacampoextra5" placeholder="Editar Etiqueta">
                                            @if($evento->is_multilingual)
                                            <input type="text" class="form-control" id="etiquetacampoextra5_en" name="etiquetacampoextra5_en" placeholder="Editar Etiqueta em Inglês">
                                            @endif
                                            </div>
                                            <div class="form-group col-sm-3">
                                                <select class="form-control" id="exampleFormControlSelect1" name="select_campo5">
                                                    @if ($etiquetasSubTrab->tipocampoextra5 == "textosimples")
                                                    <option value="textosimples" selected>Texto Simples</option>
                                                    <option value="textogrande">Texto grande</option>
                                                    <option value="upload">Upload</option>
                                                    @elseif ($etiquetasSubTrab->tipocampoextra5 == "textogrande")
                                                    <option value="textosimples">Texto Simples</option>
                                                    <option value="textogrande" selected>Texto grande</option>
                                                    <option value="upload">Upload</option>
                                                    @elseif ($etiquetasSubTrab->tipocampoextra5 == "upload")
                                                    <option value="textosimples">Texto Simples</option>
                                                    <option value="textogrande">Texto grande</option>
                                                    <option value="upload" selected>Upload</option>
                                                    @else
                                                    <option value="textosimples" selected>Texto Simples</option>
                                                    <option value="textogrande">Texto grande</option>
                                                    <option value="upload" selected>Upload</option>
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-2">
                                                <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="gridCheck" @if($etiquetasSubTrab->checkcampoextra5) checked @endif name="checkcampoextra5">
                                                <label class="form-check-label" for="gridCheck">
                                                    Exibir
                                                </label>
                                                </div>
                                            </div>
                                            <a class="move-up"><img src="{{asset('img/icons/sobe.png')}}" id="antesAutor" style="width:20px; margin-left:10px"></a>
                                            <a class="move-down"><img src="{{asset('img/icons/desce.png')}}" id="depoisAutor" style="width:20px"></a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach

                        </form>
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <form id="formSubmTrabaEtiquetasPadrao" method="POST" action="{{route('etiquetas_sub_trabalho.update', $evento->id)}}">
                                    @csrf
                                    <input type="hidden" name="etiquetatitulotrabalho"  value="Título">
                                    <input type="hidden" name="etiquetatitulotrabalho_en"  value="Title">
                                    <input type="hidden" name="etiquetaautortrabalho"   value="Autor(a)">
                                    <input type="hidden" name="etiquetacoautortrabalho" value="Coautor(es)">
                                    <input type="hidden" name="etiquetaresumotrabalho"  value="Resumo">
                                    <input type="hidden" name="etiquetaresumotrabalho_en"  value="Summary">
                                    <input type="hidden" name="etiquetaareatrabalho"    value="Área">
                                    <input type="hidden" name="etiquetauploadtrabalho"  value="Upload de Trabalho">
                                    <input type="hidden" name="etiquetacampoextra1"     value="Campo Extra">
                                    <input type="hidden" name="etiquetacampoextra2"     value="Campo Extra">
                                    <input type="hidden" name="etiquetacampoextra3"     value="Campo Extra">
                                    <input type="hidden" name="etiquetacampoextra4"     value="Campo Extra">
                                    <input type="hidden" name="etiquetacampoextra5"     value="Campo Extra">
                                    <input type="hidden" name="etiquetacampoextra1_en"     value="Extra Field">
                                    <input type="hidden" name="etiquetacampoextra2_en"     value="Extra Field">
                                    <input type="hidden" name="etiquetacampoextra3_en"     value="Extra Field">
                                    <input type="hidden" name="etiquetacampoextra4_en"     value="Extra Field">
                                    <input type="hidden" name="etiquetacampoextra5_en"     value="Extra Field">
                                    <input type="hidden" name="select_campo1"           value="textosimples">
                                    <input type="hidden" name="select_campo2"           value="textosimples">
                                    <input type="hidden" name="select_campo3"           value="textosimples">
                                    <input type="hidden" name="select_campo4"           value="textosimples">
                                    <input type="hidden" name="select_campo5"           value="textosimples">
                                    <input type="hidden" name="checkcampoextra1"        value="false">
                                    <input type="hidden" name="checkcampoextra2"        value="false">
                                    <input type="hidden" name="checkcampoextra3"        value="false">
                                    <input type="hidden" name="checkcampoextra4"        value="false">
                                    <input type="hidden" name="checkcampoextra5"        value="false">

                                    <button type="submit" class="btn btn-primary" form="formSubmTrabaEtiquetasPadrao" onclick='return default_formsubmtraba()' style="width:100%">
                                        {{ __('Retornar ao Padrão') }}
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary" form="formSubmTrabaEtiquetas" style="width:100%">
                                    {{ __('Finalizar') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>{{-- end row card --}}




    </div>
    {{-- Template 2 - edição de etiquetas --}}

@endsection
@section('script')

    <script type="text/javascript">


    // Exibir campo ao mesmo tempo que é escrito
    $(document).ready(function() {

        // Tela de evento
        $('#etiquetanomeevento').on('keyup', function() {
            if($(this).val().length == 0){
                $('#classeh4').html("{{$etiquetas->etiquetanomeevento}}:");
            }else {
                $('#classeh4').html($(this).val()+":");
            }
        });

        $('#etiquetadescricaoevento').on('keyup', function() {
            if($(this).val().length == 0){
                $('#classeh5').html("{{$etiquetas->etiquetadescricaoevento}}:");
            }else {
                $('#classeh5').html($(this).val()+":");
            }
        });

        $('#etiquetatipoevento').on('keyup', function() {
            if($(this).val().length == 0){
                $('#classeh6').html("{{$etiquetas->etiquetatipoevento}}:");
            }else {
                $('#classeh6').html($(this).val()+":");
            }
        });

        $('#etiquetadatas').on('keyup', function() {
            if($(this).val().length == 0){
                $('#classeh7').html("{{$etiquetas->etiquetadatas}}:");
            }else {
                $('#classeh7').html($(this).val()+":");
            }
        });

        $('#etiquetasubmissoes').on('keyup', function() {
            if($(this).val().length == 0){
                $('#classeh8').html("{{$etiquetas->etiquetasubmissoes}}:");
            }else {
                $('#classeh8').html($(this).val()+":");
            }
        });

        $('#etiquetabaixarregra').on('keyup', function() {
            if($(this).val().length == 0){
                $('#classeh9').html("{{$etiquetas->etiquetabaixarregra}}:");
            }else {
                $('#classeh9').html($(this).val()+":");
            }
        });

        $('#etiquetabaixartemplate').on('keyup', function() {
            if($(this).val().length == 0){
                $('#classeh10').html("{{$etiquetas->etiquetabaixartemplate}}:");
            }else {
                $('#classeh10').html($(this).val()+":");
            }
        });

        $('#etiquetaenderecoevento').on('keyup', function() {
            if($(this).val().length == 0){
                $('#classeh11').html("{{$etiquetas->etiquetaenderecoevento}}:");
            }else {
                $('#classeh11').html($(this).val()+":");
            }
        });

        $('#etiquetamoduloinscricao').on('keyup', function() {
            if($(this).val().length == 0){
                $('#classeh12').html("{{$etiquetas->etiquetamoduloinscricao}}:");
            }else {
                $('#classeh12').html($(this).val()+":");
            }
        });

        $('#etiquetamoduloprogramacao').on('keyup', function() {
            if($(this).val().length == 0){
                $('#classeh13').html("{{$etiquetas->etiquetamoduloprogramacao}}:");
            }else {
                $('#classeh13').html($(this).val()+":");
            }
        });

        $('#etiquetamoduloorganizacao').on('keyup', function() {
            if($(this).val().length == 0){
                $('#classeh14').html("{{$etiquetas->etiquetamoduloorganizacao}}:");
            }else {
                $('#classeh14').html($(this).val()+":");
            }
        });


        // Tela de submissão de trabalho
        $('#etiquetatitulotrabalho').on('keyup', function() {
            if($(this).val().length == 0){
                $('#classeh15').html("{{$etiquetasSubTrab->etiquetatitulotrabalho}}:");
            }else {
                $('#classeh15').html($(this).val()+":");
            }
        });

        $('#etiquetaautortrabalho').on('keyup', function() {
            if($(this).val().length == 0){
                $('#classeh16').html("{{$etiquetasSubTrab->etiquetaautortrabalho}}:");
            }else {
                $('#classeh16').html($(this).val()+":");
            }
        });

        $('#etiquetacoautortrabalho').on('keyup', function() {
            if($(this).val().length == 0){
                $('#classeh17').html("{{$etiquetasSubTrab->etiquetacoautortrabalho}}:");
            }else {
                $('#classeh17').html($(this).val()+":");
            }
        });

        $('#etiquetaresumotrabalho').on('keyup', function() {
            if($(this).val().length == 0){
                $('#classeh18').html("{{$etiquetasSubTrab->etiquetaresumotrabalho}}:");
            }else {
                $('#classeh18').html($(this).val()+":");
            }
        });

        $('#etiquetaareatrabalho').on('keyup', function() {
            if($(this).val().length == 0){
                $('#classeh19').html("{{$etiquetasSubTrab->etiquetaareatrabalho}}:");
            }else {
                $('#classeh19').html($(this).val()+":");
            }
        });

        $('#etiquetauploadtrabalho').on('keyup', function() {
            if($(this).val().length == 0){
                $('#classeh20').html("{{$etiquetasSubTrab->etiquetauploadtrabalho}}:");
            }else {
                $('#classeh20').html($(this).val()+":");
            }
        });

        $('#etiquetacampoextra1').on('keyup', function() {
            if($(this).val().length == 0){
                $('#classeh21').html("{{$etiquetasSubTrab->etiquetacampoextra1}}:");
            }else {
                $('#classeh21').html($(this).val()+":");
            }
        });

        $('#etiquetacampoextra2').on('keyup', function() {
            if($(this).val().length == 0){
                $('#classeh22').html("{{$etiquetasSubTrab->etiquetacampoextra2}}:");
            }else {
                $('#classeh22').html($(this).val()+":");
            }
        });

        $('#etiquetacampoextra3').on('keyup', function() {
            if($(this).val().length == 0){
                $('#classeh23').html("{{$etiquetasSubTrab->etiquetacampoextra3}}:");
            }else {
                $('#classeh23').html($(this).val()+":");
            }
        });

        $('#etiquetacampoextra4').on('keyup', function() {
            if($(this).val().length == 0){
                $('#classeh24').html("{{$etiquetasSubTrab->etiquetacampoextra4}}:");
            }else {
                $('#classeh24').html($(this).val()+":");
            }
        });

        $('#etiquetacampoextra5').on('keyup', function() {
            if($(this).val().length == 0){
                $('#classeh25').html("{{$etiquetasSubTrab->etiquetacampoextra5}}:");
            }else {
                $('#classeh25').html($(this).val()+":");
            }
        });
    });
</script>
@endsection
