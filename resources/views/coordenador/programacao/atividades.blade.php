@extends('coordenador.detalhesEvento')

@section('menu')

<!-- Modal para editar a atividade-->
@foreach ($atividades as $atv)
<div class="modal fade bd-example-modal-lg" id="modalAtividadeEdit{{$atv->id}}" tabindex="-1" role="dialog" aria-labelledby="modalLabelAtividadeEdit{{$atv->id}}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #114048ff; color: white;">
                <h5 class="modal-title" id="modalLabelAtividadeEdit{{$atv->id}}">Editar Atividade</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEdidarAtividade{{$atv->id}}" method="POST" action="{{ route('coord.atividades.update', ['id' => $atv->id]) }}">
                    @csrf
                    <div class="container">
                        <div class="row form-group">
                            <input type="hidden" name="idAtividade" value="{{ $atv->id }}">
                            <input type="hidden" name="eventoId" value="{{ $evento->id }}">
                            <div class="col-sm-6">
                                <label for="titulo">Título*:</label>
                                <input class="form-control @error('titulo') is-invalid @enderror" type="text" name="titulo" id="titulo{{$atv->id}}" value="@if ( old('titulo') != null ) {{ old('titulo') }} @else {{$atv->titulo}} @endif" placeholder="Nova atividade">

                                @error('titulo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-sm-4">
                                <label for="tipo">Tipo*:</label>
                                <select class="form-control @error('tipo') is-invalid @enderror" name="tipo" id="tipo{{$atv->id}}">
                                    <option value="" selected disabled>-- Tipo --</option>
                                    @if (old('titulo') != null)
                                    @foreach ($tipos as $tipo)
                                    <option value="{{ $tipo->id }}" @if(old('tipo')==$tipo->id) selected @endif >{{ $tipo->descricao }}</option>
                                    @endforeach
                                    @else
                                    @foreach ($tipos as $tipo)
                                    <option value="{{ $tipo->id }}" @if($atv->tipo_id == $tipo->id) selected @endif >{{ $tipo->descricao }}</option>
                                    @endforeach
                                    @endif
                                </select>

                                @error('tipo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-sm-2">
                                <button id="buttomFormNovoTipoAtividade{{$atv->id}}" type="button" class="btn btn-primary" style="position: relative; top: 31px; right: 30px;" onclick="exibirFormTipoAtividade({{$atv->id}})">+Tipo</button>
                            </div>
                        </div>
                        <div id="formNovoTipoAtividade{{$atv->id}}" class="form-group" style="display: none;">
                            <div class="row" style="background-color: rgba(242, 253, 144, 0.829); padding: 15px; border: red solid 1px;;">
                                <div class="col-sm-12">
                                    <div class="row" style="justify-content: space-between;">
                                        <label for="nomeTipo">Nome*:</label>
                                        <p style="color: red; font-weight: bold" onclick="exibirFormTipoAtividade({{$atv->id}})">X</p>
                                    </div>
                                    <input class="form-control" type="text" name="nomeTipo" id="nomeTipo{{$atv->id}}" placeholder="Nome do novo tipo">
                                </div>
                                <div class="col-sm-12">
                                    <button id="submitNovoTipoAtividade{{$atv->id}}" type="button" class="btn btn-primary" onclick="salvarTipoAtividadeAjax({{$atv->id}})">Salvar</button>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="row form-group">
                            <div class="col-sm-12">
                                <label for="descricao">Descrição*:</label>
                                <textarea class="form-control @error('descricao') is-invalid @enderror" rows="5" name="descricao" id="descricao{{$atv->id}}" placeholder="Descreva em detalhes sua atividade">@if (old('descricao') != null) {{ old('descricao') }} @else {{$atv->descricao}} @endif</textarea>

                                @error('descricao')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <hr>
                        <div class="row form-group">
                            <div class="col-sm-6">
                                <label for="duracaoAtividade">Duração*:</label>
                                <select class="form-control  @error('duracaoDaAtividade') is-invalid @enderror" name="duracaoDaAtividade" id="duracaoAtividade{{$atv->id}}" onchange="exibirDias({{ $atv->id }})">
                                    <option value="" selected disabled>-- Duração --</option>
                                    @if (old('duracaoDaAtividade') != null)
                                    <option value="1" @if(old('duracaoDaAtividade')=="1" ) selected @endif>Um dia</option>
                                    <option value="2" @if(old('duracaoDaAtividade')=="2" ) selected @endif>Dois dia</option>
                                    <option value="3" @if(old('duracaoDaAtividade')=="3" ) selected @endif>Três dia</option>
                                    <option value="4" @if(old('duracaoDaAtividade')=="4" ) selected @endif>Quatro dia</option>
                                    <option value="5" @if(old('duracaoDaAtividade')=="5" ) selected @endif>Cinco dia</option>
                                    <option value="6" @if(old('duracaoDaAtividade')=="6" ) selected @endif>Seis dia</option>
                                    <option value="7" @if(old('duracaoDaAtividade')=="7" ) selected @endif>Sete dia</option>
                                    @else
                                    @for ($i = 0; $i < count($ids); $i++) @if ($ids[$i]==$atv->id)
                                        <option value="1" @if($duracaoAtividades[$i]=="1" ) selected @endif>Um dia</option>
                                        <option value="2" @if($duracaoAtividades[$i]=="2" ) selected @endif>Dois dia</option>
                                        <option value="3" @if($duracaoAtividades[$i]=="3" ) selected @endif>Três dia</option>
                                        <option value="4" @if($duracaoAtividades[$i]=="4" ) selected @endif>Quatro dia</option>
                                        <option value="5" @if($duracaoAtividades[$i]=="5" ) selected @endif>Cinco dia</option>
                                        <option value="6" @if($duracaoAtividades[$i]=="6" ) selected @endif>Seis dia</option>
                                        <option value="7" @if($duracaoAtividades[$i]=="7" ) selected @endif>Sete dia</option>
                                        @endif
                                        @endfor
                                        @endif
                                        <select>
                                            {{-- {{dd()}} --}}
                                            @error('duracaoDaAtividade')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                            </div>
                            <div class="col-sm-6">
                                <label for="local">Local*:</label>
                                <input class="form-control @error('local') is-invalid @enderror" type="text" name="local" id="local{{$atv->id}}" placeholder="Local da atividade" value="@if(old('local') != null){{old('local')}}@else{{$atv->local}}@endif">

                                @error('local')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        @for ($i = 0; $i < count($ids); $i++) @if ($ids[$i]==$atv->id)
                            <div id="divDuracaoAtividade{{ $atv->id }}" class="row form-group" @if ($duracaoAtividades[$i] !=0) style="display: block" @else style="display: none" @endif>
                                <div class="container" style="background-color: rgb(238, 238, 238); border-radius: 5px; padding: 15px;">
                                    <div id="dia1{{ $atv->id }}" @if (old('duracaoDaAtividade')>= 1) style="display: block" @else @if ($duracaoAtividades[$i] >= 1) style="display: block" @else style="display: none" @endif @endif>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label for="data1">Data*:</label>

                                                <input type="date" class="form-control @error('primeiroDia') is-invalid @enderror" name="primeiroDia" id="data1{{$atv->id}}" value="@if (old('primeiroDia') != null){{date('Y-m-d', strtotime(old('primeiroDia')))}}@elseif(array_key_exists(0, $atv->datasAtividade->toArray())){{date('Y-m-d', strtotime($atv->datasAtividade[0]->data))}}@endif">
                                                @error('primeiroDia')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="col-sm-3">
                                                <label for="inicio1">Início:</label>
                                                <input type="time" class="form-control @error('inicio') is-invalid @enderror" name="inicio" id="inicio1{{$atv->id}}" value="@if (old('inicio1') != null){{old('inicio1')}}@elseif(array_key_exists(0, $atv->datasAtividade->toArray())){{$atv->datasAtividade[0]->hora_inicio}}@endif">

                                                @error('inicio')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="col-sm-3">
                                                <label for="fim1">Fim:</label>
                                                <input type="time" class="form-control @error('fim') is-invalid @enderror" name="fim" id="fim1{{$atv->id}}" value="@if (old('fim1') != null){{old('fim1')}}@elseif(array_key_exists(0, $atv->datasAtividade->toArray())){{$atv->datasAtividade[0]->hora_fim}}@endif">

                                                @error('fim')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div id="dia2{{ $atv->id }}" @if (old('duracaoDaAtividade')>= 2) style="display: block" @elseif(old('duracaoDaAtividade') != null && old('duracaoDaAtividade') < 1) style="display: none" @else @if ($duracaoAtividades[$i]>= 2) style="display: block" @else style="display: none" @endif @endif>
                                            <div class="row" style="margin-top: 10px;">
                                                <div class="col-sm-6">
                                                    <label for="data2">Data 2º dia:</label>
                                                    <input type="date" class="form-control @error('segundoDia') is-invalid @enderror" name="segundoDia" id="data2{{$atv->id}}" value="@if (old('segundoDia') != null){{date('Y-m-d', strtotime(old('segundoDia')))}}@elseif(array_key_exists(1, $atv->datasAtividade->toArray())){{date('Y-m-d', strtotime($atv->datasAtividade[1]->data))}}@endif">

                                                    @error('segundoDia')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                                <div class="col-sm-3">
                                                    <label for="inicio2">Início:</label>
                                                    <input type="time" class="form-control @error('segundoInicio') is-invalid @enderror" name="segundoInicio" id="inicio2{{$atv->id}}" value="@if (old('segundoInicio') != null){{old('segundoInicio')}}@elseif(array_key_exists(1, $atv->datasAtividade->toArray())){{$atv->datasAtividade[1]->hora_inicio}}@endif">

                                                    @error('segundoInicio')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                                <div class="col-sm-3">
                                                    <label for="fim2">Fim:</label>
                                                    <input type="time" class="form-control @error('segundoFim') is-invalid @enderror" name="segundoFim" id="fim2{{$atv->id}}" value="@if (old('segundoFim') != null){{old('segundoFim')}}@elseif(array_key_exists(1, $atv->datasAtividade->toArray())){{$atv->datasAtividade[1]->hora_fim}}@endif">

                                                    @error('segundoFim')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                    </div>
                                    <div id="dia3{{ $atv->id }}" @if (old('duracaoDaAtividade')>= 3) style="display: block" @elseif(old('duracaoDaAtividade') != null && old('duracaoDaAtividade') < 3) style="display: none" @else @if ($duracaoAtividades[$i]>= 3) style="display: block" @else style="display: none" @endif @endif>
                                            <div class="row" style="margin-top: 10px;">
                                                <div class="col-sm-6">
                                                    <label for="data3">Data 3º dia:</label>
                                                    <input type="date" class="form-control @error('terceiroDia') is-invalid @enderror" name="terceiroDia" id="data3{{$atv->id}}" value="@if (old('terceiroDia') != null){{date('Y-m-d', strtotime(old('terceiroDia')))}}@elseif(array_key_exists(2, $atv->datasAtividade->toArray())){{date('Y-m-d', strtotime($atv->datasAtividade[2]->data))}}@endif">

                                                    @error('terceiroDia')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                                <div class="col-sm-3">
                                                    <label for="inicio3">Início:</label>
                                                    <input type="time" class="form-control @error('terceiroInicio') is-invalid @enderror" name="terceiroInicio" id="inicio3{{$atv->id}}" value="@if (old('terceiroInicio') != null){{old('terceiroInicio')}}@elseif(array_key_exists(2, $atv->datasAtividade->toArray())){{$atv->datasAtividade[2]->hora_inicio}}@endif">

                                                    @error('terceiroInicio')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                                <div class="col-sm-3">
                                                    <label for="fim3">Fim:</label>
                                                    <input type="time" class="form-control @error('terceiroFim') is-invalid @enderror" name="terceiroFim" id="fim3{{$atv->id}}" value="@if (old('terceiroFim') != null){{old('terceiroFim')}}@elseif(array_key_exists(2, $atv->datasAtividade->toArray())){{$atv->datasAtividade[2]->hora_fim}}@endif">

                                                    @error('terceiroFim')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                    </div>
                                    <div id="dia4{{ $atv->id }}" @if (old('duracaoDaAtividade')>= 4) style="display: block" @elseif(old('duracaoDaAtividade') != null && old('duracaoDaAtividade') < 4) style="display: none" @else @if ($duracaoAtividades[$i]>= 4) style="display: block" @else style="display: none" @endif @endif>
                                            <div class="row" style="margin-top: 10px;">
                                                <div class="col-sm-6">
                                                    <label for="data4">Data 4º dia:</label>
                                                    <input type="date" class="form-control @error('quartoDia') is-invalid @enderror" name="quartoDia" id="data4{{$atv->id}}" value="@if (old('quartoDia') != null){{date('Y-m-d', strtotime(old('quartoDia')))}}@elseif(array_key_exists(3, $atv->datasAtividade->toArray())){{date('Y-m-d', strtotime($atv->datasAtividade[3]->data))}}@endif">

                                                    @error('quartoDia')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                                <div class="col-sm-3">
                                                    <label for="inicio4">Início:</label>
                                                    <input type="time" class="form-control @error('quartoInicio') is-invalid @enderror" name="quartoInicio" id="inicio4{{$atv->id}}" value="@if (old('quartoInicio') != null){{old('quartoInicio')}}@elseif(array_key_exists(3, $atv->datasAtividade->toArray())){{$atv->datasAtividade[3]->hora_inicio}}@endif">

                                                    @error('quartoInicio')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                                <div class="col-sm-3">
                                                    <label for="fim4">Fim:</label>
                                                    <input type="time" class="form-control @error('quartoFim') is-invalid @enderror" name="quartoFim" id="fim4{{$atv->id}}" value="@if (old('quartoFim') != null){{old('quartoFim')}}@elseif(array_key_exists(3, $atv->datasAtividade->toArray())){{$atv->datasAtividade[3]->hora_fim}}@endif">

                                                    @error('quartoFim')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                    </div>
                                    <div id="dia5{{ $atv->id }}" @if (old('duracaoDaAtividade')>= 5) style="display: block" @elseif(old('duracaoDaAtividade') != null && old('duracaoDaAtividade') < 5) style="display: none" @else @if ($duracaoAtividades[$i]>= 5) style="display: block" @else style="display: none" @endif @endif>
                                            <div class="row" style="margin-top: 10px;">
                                                <div class="col-sm-6">
                                                    <label for="data5">Data 5º dia:</label>
                                                    <input type="date" class="form-control @error('quintoDia') is-invalid @enderror" name="quintoDia" id="data5{{$atv->id}}" value="@if (old('quintoDia') != null){{date('Y-m-d', strtotime(old('quintoDia')))}}@elseif(array_key_exists(4, $atv->datasAtividade->toArray())){{date('Y-m-d', strtotime($atv->datasAtividade[4]->data))}}@endif">

                                                    @error('quintoDia')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                                <div class="col-sm-3">
                                                    <label for="inicio5">Início:</label>
                                                    <input type="time" class="form-control @error('quintoInicio') is-invalid @enderror" name="quintoInicio" id="inicio5{{$atv->id}}" value="@if (old('quintoInicio') != null){{old('quintoInicio')}}@elseif(array_key_exists(4, $atv->datasAtividade->toArray())){{$atv->datasAtividade[4]->hora_inicio}}@endif">

                                                    @error('quintoInicio')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                                <div class="col-sm-3">
                                                    <label for="fim5">Fim:</label>
                                                    <input type="time" class="form-control @error('quintoFim') is-invalid @enderror" name="quintoFim" id="fim5{{$atv->id}}" value="@if (old('quintoFim') != null){{old('quintoFim')}}@elseif(array_key_exists(4, $atv->datasAtividade->toArray())){{$atv->datasAtividade[4]->hora_fim}}@endif">

                                                    @error('quintoFim')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                    </div>
                                    <div id="dia6{{ $atv->id }}" @if (old('duracaoDaAtividade')>= 6) style="display: block" @elseif(old('duracaoDaAtividade') != null && old('duracaoDaAtividade') < 6) style="display: none" @else @if ($duracaoAtividades[$i]>= 6) style="display: block" @else style="display: none" @endif @endif>
                                            <div class="row" style="margin-top: 10px;">
                                                <div class="col-sm-6">
                                                    <label for="data6">Data 6º dia:</label>
                                                    <input type="date" class="form-control @error('sextoDia') is-invalid @enderror" name="sextoDia" id="data6{{$atv->id}}" value="@if (old('sextoDia') != null){{date('Y-m-d', strtotime(old('sextoDia')))}}@elseif(array_key_exists(5, $atv->datasAtividade->toArray())){{date('Y-m-d', strtotime($atv->datasAtividade[5]->data))}}@endif">

                                                    @error('sextoDia')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                                <div class="col-sm-3">
                                                    <label for="inicio6">Início:</label>
                                                    <input type="time" class="form-control @error('sextoInicio') is-invalid @enderror" name="sextoInicio" id="inicio6{{$atv->id}}" value="@if (old('sextoInicio') != null){{old('sextoInicio')}}@elseif(array_key_exists(5, $atv->datasAtividade->toArray())){{$atv->datasAtividade[5]->hora_inicio}}@endif">

                                                    @error('sextoInicio')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                                <div class="col-sm-3">
                                                    <label for="fim6">Fim:</label>
                                                    <input type="time" class="form-control @error('sextoFim') is-invalid @enderror" name="sextoFim" id="fim6{{$atv->id}}" value="@if (old('sextoFim') != null){{old('sextoFim')}}@elseif(array_key_exists(5, $atv->datasAtividade->toArray())){{$atv->datasAtividade[5]->hora_fim}}@endif">

                                                    @error('sextoFim')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                    </div>
                                    <div id="dia7{{ $atv->id }}" @if (old('duracaoDaAtividade')==7) style="display: block" @elseif(old('duracaoDaAtividade') !=null && old('duracaoDaAtividade') < 7) style="display: none" @else @if ($duracaoAtividades[$i]==7) style="display: block" @else style="display: none" @endif @endif>
                                        <div class="row" style="margin-top: 10px;">
                                            <div class="col-sm-6">
                                                <label for="data7">Data 7º dia:</label>
                                                <input type="date" class="form-control @error('setimoDia') is-invalid @enderror" name="setimoDia" id="data7{{$atv->id}}" value="@if (old('setimoDia') != null){{date('Y-m-d', strtotime(old('setimoDia')))}}@elseif(array_key_exists(6, $atv->datasAtividade->toArray())){{date('Y-m-d', strtotime($atv->datasAtividade[6]->data))}}@endif">

                                                @error('setimoDia')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="col-sm-3">
                                                <label for="inicio7">Início:</label>
                                                <input type="time" class="form-control @error('setimoInicio') is-invalid @enderror" name="setimoInicio" id="inicio7{{$atv->id}}" value="@if (old('setimoInicio') != null){{old('setimoInicio')}}@elseif(array_key_exists(6, $atv->datasAtividade->toArray())){{$atv->datasAtividade[6]->hora_inicio}}@endif">

                                                @error('setimoInicio')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="col-sm-3">
                                                <label for="fim7">Fim:</label>
                                                <input type="time" class="form-control @error('setimoFim') is-invalid @enderror" name="setimoFim" id="fim7{{$atv->id}}" value="@if (old('setimoFim') != null){{old('setimoFim')}}@elseif(array_key_exists(6, $atv->datasAtividade->toArray())){{$atv->datasAtividade[6]->hora_fim}}@endif">

                                                @error('setimoFim')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endfor
                            <hr>
                            <button id="buttonAbrirDadosAdicionais{{$atv->id}}" type="button" class="btn btn-primary" style="background-color: white; color: rgb(50, 132, 255); border-color: rgb(50, 132, 255); @if (old('vagas')!=null||old('valor')!=null||old('carga_horaria')!=null||old('palavrasChaves')!=null||old('nomeConvidado')!=null||old('emailConvidado')!=null) display: none; @else display: block; @endif" onclick="abrirDadosAdicionais({{$atv->id}})">+Abrir dados opcionais</button>
                            <button id="buttonFecharDadosAdicionais{{$atv->id}}" type="button" class="btn btn-primary" style="background-color: white; color: rgb(41, 109, 211); border-color: rgb(50, 132, 255); @if (old('vagas')!=null||old('valor')!=null||old('carga_horaria')!=null||old('palavrasChaves')!=null||old('nomeConvidado')!=null||old('emailConvidado')!=null) display: block; @else display: none; @endif" onclick="fecharDadosAdicionais({{$atv->id}})">-Fechar dados opcionais</button>
                            <div id="dadosAdicionaisNovaAtividade{{$atv->id}}" @if (old('vagas')!=null||old('valor')!=null||old('carga_horaria')!=null||old('palavrasChaves')!=null||old('nomeConvidado')!=null||old('emailConvidado')!=null) @else style="display: none;" @endif>
                                <div class="row form-group">
                                    <div class="col-sm-6">
                                        <label for="vagas">Vagas:</label>
                                        <input class="form-control" type="number" name="vagas" id="vagas{{$atv->id}}" placeholder="Quantidade de vagas" value="@if (old('vagas') != null){{old('vagas')}}@else{{$atv->vagas}}@endif">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="valor">Valor:</label>
                                        <input type="number" step="0.01" class="form-control" min="0.01" id="valor{{$atv->id}}" name="valor" placeholder="Valor para participar" value="@if (old('valor') != null){{old('valor') }}@else{{$atv->valor}}@endif">
                                    </div>
                                </div>
                                <hr>
                                <div class="row form-group">
                                    <div class="col-sm-6">
                                        <label for="carga_horaria">Carga horária:</label>
                                        <input type="number" class="form-control" id="carga_horaria{{$atv->id}}" name="carga_horaria" placeholder="Carga horária da atividade" value="@if (old('carga_horaria') != null){{old('carga_horaria')}}@else{{$atv->carga_horaria}}@endif">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="palavrasChaves">Palavras-chave:</label>
                                        <input class="form-control" type="text" name="palavrasChaves" id="palavrasChaves{{$atv->id}}" placeholder='Palavras que ajudam na busca, separe-as por ","' value="@if (old('palavrasChaves') != null){{old('palavrasChaves')}}@else {{ $atv->palavras_chave }} @endif">
                                    </div>
                                </div>
                                <div id="convidadosDeUmaAtividade{{$atv->id}}">
                                    @foreach ($atv->convidados as $convidado)
                                    <div id="convidadoAtividade{{$convidado->id}}" class="row form-group">
                                        <input type="hidden" name="idConvidado[]" id="convidado-{{$convidado->id}}" value="{{$convidado->id}}">
                                        <div class="container">
                                            <h5>Convidado</h5>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label for="nome">Nome:</label>
                                                    <input class="form-control" type="text" name="nomeDoConvidado[]" id="nome{{$atv->id}}{{$convidado->id}}" value="@if(old('nomeConvidado[]') != null){{old('nomeConvidado[]')}}@else{{$convidado->nome}}@endif" placeholder="Nome do convidado">
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="email">E-mail:</label>
                                                    <input class="form-control" type="text" name="emailDoConvidado[]" id="email{{$atv->id}}{{$convidado->id}}" value="@if(old('emailDoConvidado[]') != null){{old('emailDoConvidado[]')}}@else{{$convidado->email}}@endif" placeholder="E-mail do convidado">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label for="funcao">Função:</label>
                                                    <select class="form-control" name="funçãoDoConvidado[]" id="funcao{{$atv->id}}{{$convidado->id}}" onchange="outraFuncaoConvidado({{$atv->id}}{{$convidado->id}}, this, 0)">
                                                        <option value="" selected disabled>-- Função --</option>
                                                        @if (old('funçãoDoConvidado[]'))
                                                        <option @if(old('funçãoDoConvidado[]')=="Palestrante" ) selected @endif value="Palestrante">Palestrante</option>
                                                        <option @if(old('funçãoDoConvidado[]')=="Avaliador" ) selected @endif value="Avaliador">Avaliador</option>
                                                        <option @if(old('funçãoDoConvidado[]')=="Ouvinte" ) selected @endif value="Ouvinte">Ouvinte</option>
                                                        <option @if(old('funçãoDoConvidado[]')=="Outra" ) selected @endif value="Outra">Outra</option>
                                                        @else
                                                        <option @if($convidado->funcao == "Palestrante") selected @endif value="Palestrante">Palestrante</option>
                                                        <option @if($convidado->funcao == "Avaliador") selected @endif value="Avaliador">Avaliador</option>
                                                        <option @if($convidado->funcao == "Ouvinte") selected @endif value="Ouvinte">Ouvinte</option>
                                                        <option @if($convidado->funcao == "Outra") selected @endif value="Outra">Outra</option>
                                                        @if($convidado->funcao != "Ouvinte" && $convidado->funcao != "Avaliador" && $convidado->funcao != "Palestrante")
                                                        <option selected value="{{$convidado->funcao}}">{{$convidado->funcao}}</option>
                                                        @endif
                                                        @endif
                                                    </select>
                                                </div>
                                                <div id="divOutraFuncao{{$atv->id}}{{$convidado->id}}" class="col-sm-4" @if (old('outra[]') !=null) style="display: block;" @else style="display: none;" @endif>
                                                    <label for="Outra">Qual?</label>
                                                    <input type="text" class="form-control @error('outra[]') is-invalid @enderror" name="outra[]" id="outraFuncao{{$atv->id}}{{$convidado->id}}">

                                                    @error('outra[]')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                                {{-- <div class="col-sm-4">
                                                        <button id="buttonformNovaFuncaoDeConvidado{{$atv->id}}{{}}" type="button" class="btn btn-primary" style="position: relative; top: 31px; right: 30px;">+Função</button>
                                            </div> --}}
                                            <div class='col-sm-4'>
                                                <button type='button' onclick='removerConvidadoAtividade({{$convidado->id}})' style='border:none; background-color: rgba(0,0,0,0);'><img src='{{ asset('/img/icons/user-times-solid.svg') }}' width='50px' height='auto' alt='remover convidade' style='padding-top: 28px;'></button>
                                            </div>
                                        </div>
                                        {{-- <div id="formNovaFuncaoDeConvidado{{$atv->id}}" class="form-group" style="display: none; margin-top: 15px;">
                                        <div class="row" style="background-color: rgba(242, 253, 144, 0.829); padding: 15px; border: red solid 1px;;">
                                            <div class="col-sm-12">
                                                <label for="nomeTipo">Nome*:</label>
                                                <input class="form-control" type="text" name="nomeTipo" id="nomeTipoConvidado{{$atv->id}}" placeholder="Nome da nova função do convidado">
                                            </div>
                                            <div class="col-sm-12">
                                                <button id="submitNovaFuncaoDeConvidado{{$atv->id}}" type="button" class="btn btn-primary">Salvar</button>
                                            </div>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                            @endforeach
                    </div>
                    <button id="buttonNovoConvidado{{$atv->id}}" class="btn btn-primary" type="button" onclick="adicionarConvidado({{$atv->id}})">+Adicionar convidado</button>
            </div>
        </div>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <!-- <button type="submit" class="btn btn-primary" onclick="editarAtividade({{$atv->id}})">Salvar</button> -->
        <button type="submit" class="btn btn-primary">Salvar</button>
    </div>
    </form>
</div>
</div>
</div>
@endforeach
<div class="modal fade bd-example-modal-lg" id="modalCriarAtividade" tabindex="-1" role="dialog" aria-labelledby="modalLabelCriarAtividade" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #114048ff; color: white;">
                <h5 class="modal-title" id="modalLabelCriarAtividade">Criar Atividade</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formNovaAtividade" method="POST" action="{{ route('coord.atividades.store') }}">
                    @csrf
                    <div class="container">
                        <div class="row form-group">
                            <input type="hidden" name="idNovaAtividade" value="2">
                            <input type="hidden" name="eventoId" value="{{ $evento->id }}">
                            <div class="col-sm-6">
                                <label for="titulo">Título*:</label>
                                <input class="form-control @error('título') is-invalid @enderror" type="text" name="título" id="titulo" value="{{ old('título')}}" placeholder="Nova atividade" required>

                                @error('título')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-sm-4">
                                <label for="tipo">Tipo*:</label>
                                <select class="form-control @error('tipo') is-invalid @enderror" name="tipo" id="tipo" required>
                                    <option value="" selected disabled>-- Tipo --</option>
                                    @foreach ($tipos as $tipo)
                                    <option value="{{ $tipo->id }}" @if(old('tipo')==$tipo->id) selected @endif >{{ $tipo->descricao }}</option>
                                    @endforeach
                                </select>

                                @error('tipo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-sm-2">
                                <button id="buttomFormNovoTipoAtividade" type="button" class="btn btn-primary" style="position: relative; top: 31px; right: 30px;" onclick="exibirFormTipoAtividade(0)">+Tipo</button>
                            </div>
                        </div>
                        <div id="formNovoTipoAtividade" class="form-group" style="display: none;">
                            <div class="row" style="background-color: rgba(242, 253, 144, 0.829); padding: 15px; border: red solid 1px;;">
                                <div class="col-sm-12">
                                    <div class="row" style="justify-content: space-between;">
                                        <label for="nomeTipo">Nome*:</label>
                                        <p style="color: red; font-weight: bold" onclick="exibirFormTipoAtividade(0)">X</p>
                                    </div>
                                    <input class="form-control apenasLetras" type="text" name="nomeNovoTipo" id="nomeTipo" placeholder="Nome do novo tipo">
                                </div>
                                <div class="col-sm-12">
                                    <button id="submitNovoTipoAtividade" type="button" class="btn btn-primary" onclick="salvarTipoAtividadeAjax(0)">Salvar</button>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="row form-group">
                            <div class="col-sm-12">
                                <label for="descricao">Descrição*:</label>
                                <textarea class="form-control @error('descrição') is-invalid @enderror" rows="5" name="descrição" id="descricao" placeholder="Descreva em detalhes sua atividade">{{ old('descrição') }}</textarea required>

                                @error('descrição')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <hr>
                        <div class="row form-group">
                            <div class="col-sm-6">
                                <label for="duracaoAtividade">Duração*:</label>
                                <select class="form-control  @error('duraçãoDaAtividade') is-invalid @enderror" name="duraçãoDaAtividade" id="duracaoAtividade" onchange="exibirDias(0)" required>
                                    <option value="" selected disabled>-- Duração --</option>
                                    <option value="1" @if(old('duraçãoDaAtividade')=="1" ) selected @endif>Um dias</option>
                                    <option value="2" @if(old('duraçãoDaAtividade')=="2" ) selected @endif>Dois dias</option>
                                    <option value="3" @if(old('duraçãoDaAtividade')=="3" ) selected @endif>Três dias</option>
                                    <option value="4" @if(old('duraçãoDaAtividade')=="4" ) selected @endif>Quatro dias</option>
                                    <option value="5" @if(old('duraçãoDaAtividade')=="5" ) selected @endif>Cinco dias</option>
                                    <option value="6" @if(old('duraçãoDaAtividade')=="6" ) selected @endif>Seis dias</option>
                                    <option value="7" @if(old('duraçãoDaAtividade')=="7" ) selected @endif>Sete dias</option>
                                    <select>
                                        @error('duraçãoDaAtividade')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                            </div>
                            <div class="col-sm-6">
                                <label for="local">Local*:</label>
                                <input class="form-control @error('local') is-invalid @enderror" type="text" name="local" id="local" placeholder="Local da atividade" value="{{ old('local') }}" required>

                                @error('local')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div id="divDuracaoAtividade" class="row form-group" style="display: none;">
                            <div class="container" style="background-color: rgb(238, 238, 238); border-radius: 5px; padding: 15px;">
                                <div id="dia1" style="display: none;">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="data1">Data*:</label>
                                            <input type="date" class="form-control @error('primeiroDia') is-invalid @enderror" name="primeiroDia" id="data1" value="{{ old('primeiroDia') }}">

                                            @error('primeiroDia')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="inicio1">Início:</label>
                                            <input type="time" class="form-control @error('inicio') is-invalid @enderror" name="inicio" id="inicio1" value="{{ old('inicio') }}">

                                            @error('inicio')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="fim1">Fim:</label>
                                            <input type="time" class="form-control @error('fim') is-invalid @enderror" name="fim" id="fim1" value="{{ old('fim') }}">

                                            @error('fim')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div id="dia2" style="display: none;">
                                    <div class="row" style="margin-top: 10px;">
                                        <div class="col-sm-6">
                                            <label for="data2">Data 2º dia:</label>
                                            <input type="date" class="form-control @error('segundoDia') is-invalid @enderror" name="segundoDia" id="data2" value="{{ old('segundoDia') }}">

                                            @error('segundoDia')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="inicio2">Início:</label>
                                            <input type="time" class="form-control @error('segundoInicio') is-invalid @enderror" name="segundoInicio" id="inicio2" value="{{ old('segundoInicio') }}">

                                            @error('segundoInicio')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="fim2">Fim:</label>
                                            <input type="time" class="form-control @error('segundoFim') is-invalid @enderror" name="segundoFim" id="fim2" value="{{ old('segundoFim') }}">

                                            @error('segundoFim')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div id="dia3" style="display: none;">
                                    <div class="row" style="margin-top: 10px;">
                                        <div class="col-sm-6">
                                            <label for="data3">Data 3º dia:</label>
                                            <input type="date" class="form-control @error('terceiroDia') is-invalid @enderror" name="terceiroDia" id="data3" value="{{ old('terceiroDia') }}">

                                            @error('terceiroDia')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="inicio3">Início:</label>
                                            <input type="time" class="form-control @error('terceiroInicio') is-invalid @enderror" name="terceiroInicio" id="inicio3" value="{{ old('terceiroInicio') }}">

                                            @error('terceiroInicio')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="fim3">Fim:</label>
                                            <input type="time" class="form-control @error('terceiroFim') is-invalid @enderror" name="terceiroFim" id="fim3" value="{{ old('terceiroFim') }}">

                                            @error('terceiroFim')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div id="dia4" style="display: none;">
                                    <div class="row" style="margin-top: 10px;">
                                        <div class="col-sm-6">
                                            <label for="data4">Data 4º dia:</label>
                                            <input type="date" class="form-control @error('quartoDia') is-invalid @enderror" name="quartoDia" id="data4" value="{{ old('quartoDia') }}">

                                            @error('quartoDia')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="inicio4">Início:</label>
                                            <input type="time" class="form-control @error('quartoInicio') is-invalid @enderror" name="quartoInicio" id="inicio4" value="{{ old('quartoInicio') }}">

                                            @error('quartoInicio')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="fim4">Fim:</label>
                                            <input type="time" class="form-control @error('quartoFim') is-invalid @enderror" name="quartoFim" id="fim4" value="{{ old('quartoFim') }}">

                                            @error('quartoFim')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div id="dia5" style="display: none;">
                                    <div class="row" style="margin-top: 10px;">
                                        <div class="col-sm-6">
                                            <label for="data5">Data 5º dia:</label>
                                            <input type="date" class="form-control @error('quintoDia') is-invalid @enderror" name="quintoDia" id="data5" value="{{ old('quintoDia') }}">

                                            @error('quintoDia')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="inicio5">Início:</label>
                                            <input type="time" class="form-control @error('quintoInicio') is-invalid @enderror" name="quintoInicio" id="inicio5" value="{{ old('quintoInicio') }}">

                                            @error('quintoInicio')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="fim5">Fim:</label>
                                            <input type="time" class="form-control @error('quintoFim') is-invalid @enderror" name="quintoFim" id="fim5" value="{{ old('quintoFim') }}">

                                            @error('quintoFim')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div id="dia6" style="display: none;">
                                    <div class="row" style="margin-top: 10px;">
                                        <div class="col-sm-6">
                                            <label for="data6">Data 6º dia:</label>
                                            <input type="date" class="form-control @error('sextoDia') is-invalid @enderror" name="sextoDia" id="data6" value="{{ old('sextoDia') }}">

                                            @error('sextoDia')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="inicio6">Início:</label>
                                            <input type="time" class="form-control @error('sextoInicio') is-invalid @enderror" name="sextoInicio" id="inicio6" value="{{ old('sextoInicio') }}">

                                            @error('sextoInicio')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="fim6">Fim:</label>
                                            <input type="time" class="form-control @error('sextoFim') is-invalid @enderror" name="sextoFim" id="fim6" value="{{ old('sextoFim') }}">

                                            @error('sextoFim')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div id="dia7" style="display: none;">
                                    <div class="row" style="margin-top: 10px;">
                                        <div class="col-sm-6">
                                            <label for="data7">Data 7º dia:</label>
                                            <input type="date" class="form-control @error('setimoDia') is-invalid @enderror" name="setimoDia" id="data7" value="{{ old('setimoDia') }}">

                                            @error('setimoDia')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="inicio7">Início:</label>
                                            <input type="time" class="form-control @error('setimoInicio') is-invalid @enderror" name="setimoInicio" id="inicio7" value="{{ old('setimoInicio') }}">

                                            @error('setimoInicio')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="fim7">Fim:</label>
                                            <input type="time" class="form-control @error('setimoFim') is-invalid @enderror" name="setimoFim" id="fim7" value="{{ old('setimoFim') }}">

                                            @error('setimoFim')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <button id="buttonAbrirDadosAdicionais" type="button" class="btn btn-primary" style="background-color: white; color: rgb(50, 132, 255); border-color: rgb(50, 132, 255); @if (old('vagas')!=null||old('valor')!=null||old('carga_horaria')!=null||old('palavrasChaves')!=null||old('nomeConvidado')!=null||old('emailConvidado')!=null) display: none; @else display: block; @endif" onclick="abrirDadosAdicionais(0)">+Abrir dados opcionais</button>
                        <button id="buttonFecharDadosAdicionais" type="button" class="btn btn-primary" style="background-color: white; color: rgb(41, 109, 211); border-color: rgb(50, 132, 255); @if (old('vagas')!=null||old('valor')!=null||old('carga_horaria')!=null||old('palavrasChaves')!=null||old('nomeConvidado')!=null||old('emailConvidado')!=null) display: block; @else display: none; @endif" onclick="fecharDadosAdicionais(0)">-Fechar dados opcionais</button>
                        <div id="dadosAdicionaisNovaAtividade" @if (old('vagas')!=null||old('valor')!=null||old('carga_horaria')!=null||old('palavrasChaves')!=null||old('nomeConvidado')!=null||old('emailConvidado')!=null) @else style="display: none;" @endif>
                            <hr>
                            <div class="row form-group">
                                <div class="col-sm-6">
                                    <label for="vagas">Vagas:</label>
                                    <input class="form-control" type="number" name="vagas" id="vagas" placeholder="Quantidade de vagas" value="{{ old('vagas') }}">
                                </div>
                                <div class="col-sm-6">
                                    <label for="valor">Valor:</label>
                                    <input type="number" step="0.01" class="form-control" min="0.01" id="valor" name="valor" placeholder="Valor para participar" value="{{ old('valor') }}">
                                </div>
                            </div>
                            <hr>
                            <div class="row form-group">
                                <div class="col-sm-6">
                                    <label for="carga_horaria">Carga horária:</label>
                                    <input type="number" class="form-control" id="carga_horaria" name="carga_horaria" placeholder="Carga horária da atividade" value="{{ old('carga_horaria') }}">
                                </div>
                                <div class="col-sm-6">
                                    <label for="palavrasChaves">Palavras-chave:</label>
                                    <input class="form-control apenasLetras" type="text" name="palavrasChaves" id="palavrasChaves" placeholder='Palavras que ajudam na busca, separe-as por ","' value="{{ old('palavrasChaves') }}">
                                </div>
                            </div>
                            <hr>
                            <div id="convidadosDeUmaAtividade">
                                <div class="row form-group">
                                    <div class="container">
                                        <h5>Convidado</h5>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label for="nome">Nome:</label>
                                                <input class="form-control apenasLetras @error('nomeDoConvidado[]') is-invalid @enderror" type="text" name="nomeDoConvidado[]" id="nome" value="{{ old('nomeDoConvidado[]') }}" placeholder="Nome do convidado">

                                                @error('nomeDoConvidado[]')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="email">E-mail:</label>
                                                <input class="form-control @error('emailDoConvidado[]') is-invalid @enderror" type="email" name="emailDoConvidado[]" id="email" value="{{ old('emailDoConvidado[]') }}" placeholder="E-mail do convidado">

                                                @error('emailDoConvidado[]')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label for="funcao">Função:</label>
                                                <select class="form-control @error('funçãoDoConvidado[]') is-invalid @enderror" name="funçãoDoConvidado[]" id="funcaoConvidado" onchange="outraFuncaoConvidado(0, this, 0)">
                                                    <option value="" selected disabled>-- Função --</option>
                                                    <option value="Palestrante">Palestrante</option>
                                                    <option value="Avaliador">Avaliador</option>
                                                    <option value="Ouvinte">Ouvinte</option>
                                                    <option value="Outra">Outra</option>
                                                </select>

                                                @error('funçãoDoConvidado[]')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div id="divOutraFuncao" class="col-sm-6" @if (old('outra[]') !=null) style="display: block;" @else style="display: none;" @endif>
                                                <label for="Outra">Qual?</label>
                                                <input type="text" class="form-control apenasLetras @error('outra[]') is-invalid @enderror" name="outra[]" id="outraFuncao">

                                                @error('outra[]')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            {{-- <div class="col-sm-4">
                                                <button id="buttonformNovaFuncaoDeConvidado" type="button" class="btn btn-primary" style="position: relative; top: 31px; right: 30px;">+Função</button>
                                            </div> --}}
                                        </div>
                                        {{-- <div id="formNovaFuncaoDeConvidado" class="form-group" style="display: none; margin-top: 15px;">
                                            <div class="row" style="background-color: rgba(242, 253, 144, 0.829); padding: 15px; border: red solid 1px;;">
                                                <div class="col-sm-12">
                                                    <label for="nomeTipo">Nome*:</label>
                                                    <input class="form-control" type="text" name="nomeTipo" id="nomeTipoConvidado" placeholder="Nome da nova função do convidado">
                                                </div>
                                                <div class="col-sm-12">
                                                    <button id="submitNovaFuncaoDeConvidado" type="button" class="btn btn-primary">Salvar</button>
                                                </div>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                            <button id="buttonNovoConvidado" class="btn btn-primary" type="button" onclick="adicionarConvidado(0)">+Adicionar convidado</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button id="submitNovaAtividade" type="submit" class="btn btn-primary">Salvar</button>
            </div>

        </div>
    </div>
</div>
@foreach ($atividades as $atv)
<!-- Modal de exclusão -->
<div class="modal fade" id="modalExcluirAtividade{{$atv->id}}" tabindex="-1" role="dialog" aria-labelledby="modalExcluirAtividadeLabel{{$atv->id}}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #114048ff; color: white;">
                <h5 class="modal-title" id="modalExcluirAtividadeLabel{{$atv->id}}">Confirmar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Tem certeza que deseja excluir?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <form method="POST" action="{{ route('coord.atividade.destroy', ['id' => $atv->id]) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modal para adicionar o pdf com a programação -->
<div class="modal fade" id="modalAdicionarPdf" tabindex="-1" role="dialog" aria-labelledby="modalAdicionarPdfLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #114048ff; color: white;">
                <h5 class="modal-title" id="modalAdicionarPdfLabel">Adicionar PDF com a programação</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('coord.evento.pdf.programacao', ['id' => $evento->id]) }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="container">
                        <div class="row form-group">
                            <div class="col-sm-12">
                                <label for="pdf_programacao">Arquivo com a programação:</label>
                                <input type="file" name="pdf_programacao" id="pdf_programacao">
                                <br>
                                <small>Para mudar o arquivo presente enviar um novo*</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="divListarComissao" class="comissao" style="display: block">
    <div class="row">
        <div class="col-sm-12">
            <h1 class="titulo-detalhes">Programação</h1>
        </div>
    </div>
    {{-- Alerta para mensagens de sucesso
    @if(session('mensagem'))
    <div class="row">
        <div class="col-md-12" style="margin-top: 5px;">
            <div class="alert alert-success">
                <p>{{session('mensagem')}}</p>
</div>
</div>
</div>
@endif --}}
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Atividades</h5>
                <h6 class="card-subtitle mb-2 text-muted">Atividades que seu evento irá realizar.</h6>
                <small>Clique em uma atividade para editar</small>

                <div class="container">
                    <div class="row">
                        <div class="col-sm-9">
                            <button id="adicionarPdf" data-toggle="modal" data-target="#modalAdicionarPdf" class="btn btn-primary float-md-right" style="position: relative; bottom: 50px; left: 100px;">+ PDF com as atividades</button>
                        </div>
                        <div class="col-sm-3">
                            <button id="criarAtividade" data-toggle="modal" data-target="#modalCriarAtividade" class="btn btn-primary float-md-right" style="position: relative; bottom: 50px; margin-left: 20px;">+ Criar atividade</button>
                        </div>
                    </div>
                </div>
                <p class="card-text">
                <table class="table table-hover table-responsive-lg table-sm" style="position: relative; top: -22px;">
                    <thead>
                        <th>
                        <th>Título</th>
                        <th>Tipo</th>
                        <th>Vagas</th>
                        <th>Valor</th>
                        <th>Local</th>
                        <th>Carga Horária</th>
                        <th>Visibilidade</th>
                        <th>Inscritos</th>
                        <th>Excluir</th>
                        </th>
                    </thead>
                    @foreach ($atividades as $atv)

                    <tbody>
                        <th>
                        <td data-toggle="modal" data-target="#modalAtividadeEdit{{$atv->id}}" onclick="abrirEditor({{$atv->id}})">{{$atv->titulo}}</td>
                        <td data-toggle="modal" data-target="#modalAtividadeEdit{{$atv->id}}" onclick="abrirEditor({{$atv->id}})">{{$atv->tipoAtividade->descricao}}</td>
                        <td data-toggle="modal" data-target="#modalAtividadeEdit{{$atv->id}}" onclick="abrirEditor({{$atv->id}})">@if(empty($atv->vagas)) Ilimitado @else {{$atv->vagas}} @endif</td>
                        <td data-toggle="modal" data-target="#modalAtividadeEdit{{$atv->id}}" onclick="abrirEditor({{$atv->id}})">@if(empty($atv->valor)) Grátis @else R$ {{$atv->valor}},00 @endif</td>
                        <td data-toggle="modal" data-target="#modalAtividadeEdit{{$atv->id}}" onclick="abrirEditor({{$atv->id}})">{{$atv->local}}</td>
                        <td data-toggle="modal" data-target="#modalAtividadeEdit{{$atv->id}}" onclick="abrirEditor({{$atv->id}})">@if(empty($atv->carga_horaria)) Nenhuma @else {{$atv->carga_horaria}} @endif</td>
                        <td><input id="checkbox_{{$atv->id}}" type="checkbox" @if($atv->visibilidade_participante) checked @endif onclick="setVisibilidadeAtv({{$atv->id}})"></td>
                        <td><a type="button" class="btn btn-primary" href="{{route('atividades.inscritos',['id'=> $atv->id])}}">Lista</a></td>
                        <td data-toggle="modal" data-target="#modalExcluirAtividade{{$atv->id}}"><button style="border: none; background-color: rgba(255, 255, 255, 0);"><img src="{{asset('img/icons/trash-alt-regular.svg')}}" class="icon-card" alt=""></button></td>
                        </th>
                    </tbody>
                    @endforeach
                </table>
                </p>
            </div>
        </div>
    </div>
</div>


</div>{{-- End Listar atividades --}}


@endsection

@section('javascript')
<script type="text/javascript">
    CKEDITOR.replace('descricao');

    function abrirEditor(id) {
        var textareaId = 'descricao' + id;
        CKEDITOR.replace(textareaId);
    }


    //Função para controlar a exibição da div para cadastro de um novo tipo de atividade
    function exibirFormTipoAtividade(id) {

        if (id == 0) {
            if (document.getElementById('formNovoTipoAtividade').style.display == "block") {
                document.getElementById('formNovoTipoAtividade').style.display = "none";
            } else {
                document.getElementById('formNovoTipoAtividade').style.display = "block";
            }
        } else {
            if (document.getElementById('formNovoTipoAtividade' + id).style.display == "block") {
                document.getElementById('formNovoTipoAtividade' + id).style.display = "none";
            } else {
                document.getElementById('formNovoTipoAtividade' + id).style.display = "block";
            }
        }
    }

    function salvarTipoAtividadeAjax(id) {
        if (id == 0) {
            $.ajax({
                url: "{{route('coord.tipo.store.ajax')}}",
                method: 'get',
                type: 'get',
                data: {
                    _token: '{{csrf_token()}}',
                    name: $('#nomeTipo').val(),
                    evento_id: "{{$evento->id}}",
                },
                statusCode: {
                    404: function() {
                        alert("O nome é obrigatório");
                    }
                },
                success: function(data) {
                    // var data = JSON.parse(result);
                    if (data != null) {
                        if (data.length > 0) {
                            if ($('#tipo').val() == null || $('#tipo').val() == "") {
                                var option = '<option selected disabled>-- Tipo --</option>';
                            }
                            $.each(data, function(i, obj) {
                                if ($('#tipo').val() != null && $('#tipo').val() == obj.id && i > 0) {
                                    option += '<option value="' + obj.id + '">' + obj.descricao + '</option>';
                                } else if (i == 0) {
                                    option = '<option selected disabled>-- Tipo --</option>';
                                } else {
                                    option += '<option value="' + obj.id + '">' + obj.descricao + '</option>';
                                }
                            })
                        } else {
                            var option = "<option selected disabled>-- Tipo --</option>";
                        }
                        $('#tipo').html(option).show();
                        if (data.length > 0) {
                            for (var i = 0; i < data.length; i++) {
                                // console.log('---------------------------------'+i+'------------------------');
                                // console.log(data[i].descricao);
                                // console.log(document.getElementById('nomeTipo').value);
                                // console.log(data[i].descricao === document.getElementById('nomeTipo').value);
                                if (data[i].descricao === document.getElementById('nomeTipo').value) {
                                    document.getElementById('tipo').selectedIndex = i;
                                }
                            }
                        }
                        document.getElementById('nomeTipo').value = "";
                        $('#buttomFormNovoTipoAtividade').click();
                    }
                }
            });
        } else {
            $.ajax({
                url: "{{route('coord.tipo.store.ajax')}}",
                method: 'get',
                type: 'get',
                data: {
                    _token: '{{csrf_token()}}',
                    name: $('#nomeTipo' + id).val(),
                    evento_id: "{{$evento->id}}",
                },
                statusCode: {
                    404: function() {
                        alert("O nome é obrigatório");
                    }
                },
                success: function(data) {
                    if (data != null) {
                        if (data.length > 0) {
                            if ($('#tipo' + id).val() == null || $('#tipo' + id).val() == "") {
                                var option = '<option selected disabled>-- Tipo --</option>';
                            }
                            $.each(data, function(i, obj) {
                                if ($('#tipo' + id).val() != null && $('#tipo' + id).val() == obj.id && i > 0) {
                                    option += '<option value="' + obj.id + '">' + obj.descricao + '</option>';
                                } else if (i == 0) {
                                    option = '<option selected disabled>-- Tipo --</option>';
                                } else {
                                    option += '<option value="' + obj.id + '">' + obj.descricao + '</option>';
                                }
                            })
                        } else {
                            var option = "<option selected disabled>-- Tipo --</option>";
                        }
                        $('#tipo' + id).html(option).show();
                        if (data.length > 0) {
                            for (var i = 0; i < data.length; i++) {
                                // console.log('---------------------------------'+i+'------------------------');
                                // console.log(data[i].descricao);
                                // console.log(document.getElementById('nomeTipo').value);
                                // console.log(data[i].descricao === document.getElementById('nomeTipo').value);
                                if (data[i].descricao === document.getElementById('nomeTipo' + id).value) {
                                    document.getElementById('tipo' + id).selectedIndex = i;
                                }
                            }
                        }
                        document.getElementById('nomeTipo' + id).value = "";
                        $('#buttomFormNovoTipoAtividade' + id).click();
                    }
                }
            });
        }
    }
</script>
@endsection