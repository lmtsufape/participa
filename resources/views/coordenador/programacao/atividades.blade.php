@extends('coordenador.detalhesEvento')

@section('menu')
  
<!-- Modal para editar a atividade-->
@foreach ($atividades as $atv)
    <div class="modal fade" id="modalAtividadeEdit{{$atv->id}}" tabindex="-1" role="dialog" aria-labelledby="modalLabelAtividadeEdit{{$atv->id}}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabelAtividadeEdit{{$atv->id}}">Editar Atividade</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('coord.atividades.update', ['id' => $atv->id]) }}">
                <div class="modal-body">
                        @csrf
                        <div class="container">
                            <div class="row form-group">
                                <input type="hidden" name="idAtividade" id="id" value="{{$atv->id}}">
                                <div class="col-sm-6">
                                    <label for="titulo">Titulo:</label>
                                    <input class="form-control" type="text" name="titulo" id="titulo" value="{{$atv->titulo}}">
                                </div>
                                <div class="col-sm-6">
                                    <label for="tipo">Tipo:</label>
                                    <select class="form-control" name="tipo" id="tipo">
                                        @foreach ($tipos as $tipo)
                                            <option value="{{ $tipo->id }}" @if($tipo->id === $atv->tipoAtividade->id) selected @endif>{{ $tipo->descricao }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-12">
                                    <label for="descricao">Descricao:</label>
                                    <textarea class="form-control" rows="5" name="descricao" id="descricao">{{ $atv->descricao }}</textarea>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-6">
                                    <label for="vagas">Vagas:</label>
                                    <input class="form-control" type="number" name="vagas" id="vagas" value="{{$atv->vagas}}">
                                </div>
                                <div class="col-sm-6">
                                    <label for="valor">Valor:</label>
                                    <input type="number" step="0.01" class="form-control" min="0.01" id="valor" name="valor" value="{{$atv->valor}}">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-6">
                                    <label for="local">Local:</label>
                                    <input class="form-control" type="text" name="local" id="local" value="{{$atv->local}}">
                                </div>
                                <div class="col-sm-6">
                                    <label for="carga_horaria">Carga horária:</label>
                                    <input type="number" class="form-control" id="carga_horaria" name="carga_horaria" value="{{$atv->carga_horaria}}">
                                </div>
                            </div>
                        </div>
                        
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
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
                            <div class="col-sm-6">
                                <label for="titulo">Titulo*:</label>
                                <input class="form-control @error('titulo') is-invalid @enderror" type="text" name="titulo" id="titulo" value="{{ old('titulo')}}" placeholder="Nova atividade">
                                
                                @error('titulo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-sm-4">
                                <label for="tipo">Tipo*:</label>
                                <select class="form-control @error('tipo') is-invalid @enderror" name="tipo" id="tipo">
                                    <option value="" selected disabled>-- Tipo --</option>
                                    @foreach ($tipos as $tipo)
                                        <option value="{{ $tipo->id }}" @if(old('tipo') == $tipo->id) selected @endif >{{ $tipo->descricao }}</option>
                                    @endforeach
                                </select>

                                @error('tipo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-sm-2">
                                <button id="buttomFormNovoTipoAtividade" type="button" class="btn btn-primary" style="position: relative; top: 31px; right: 30px;">+Tipo</button>
                            </div>
                        </div>
                        <div id="formNovoTipoAtividade" class="form-group" style="display: none;">
                            <div class="row" style="background-color: rgba(242, 253, 144, 0.829); padding: 15px; border: red solid 1px;;">
                                <div class="col-sm-12">
                                    <label for="nomeTipo">Nome*:</label>
                                    <input class="form-control" type="text" name="nomeTipo" id="nomeTipo" placeholder="Nome do novo tipo">
                                </div>
                                <div class="col-sm-12">
                                    <button id="submitNovoTipoAtividade" type="button" class="btn btn-primary">Salvar</button>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row form-group">
                            <div class="container">
                                <h5>Convidado</h5>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label for="nome">Nome:</label>
                                        <input class="form-control" type="text" name="nomeDoConvidado" id="nome"  value="{{ old('nomeConvidado') }}" placeholder="Nome do convidado">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="email">E-mail:</label>
                                        <input class="form-control" type="text" name="emailDoConvidado" id="email" value="{{ old('emailConvidado') }}" placeholder="E-mail do convidado">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-8">
                                        <label for="funcao">Função:</label>
                                        <select class="form-control" name="funçãoDoConvidado" id="funcao">
                                            <option value="" selected disabled>-- Função --</option>
                                            <option value="Palestrate">Palestrate</option>
                                            <option value="Avaliador">Avaliador</option>
                                            <option value="Ouvinte">Ouvinte</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <button id="buttonformNovaFuncaoDeConvidado" type="button" class="btn btn-primary" style="position: relative; top: 31px; right: 30px;">+Função</button>
                                    </div>
                                </div>
                                <div id="formNovaFuncaoDeConvidado" class="form-group" style="display: none; margin-top: 15px;">
                                    <div class="row" style="background-color: rgba(242, 253, 144, 0.829); padding: 15px; border: red solid 1px;;">
                                        <div class="col-sm-12">
                                            <label for="nomeTipo">Nome*:</label>
                                            <input class="form-control" type="text" name="nomeTipo" id="nomeTipo" placeholder="Nome da nova função do convidado">
                                        </div>
                                        <div class="col-sm-12">
                                            <button id="submitNovaFuncaoDeConvidado" type="button" class="btn btn-primary">Salvar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row form-group">
                            <div class="col-sm-12">
                                <label for="descricao">Descricao*:</label>
                                <textarea class="form-control @error('descricao') is-invalid @enderror" rows="5" name="descricao" id="descricao" placeholder="Descreva em detalhes sua atividade">{{ old('descricao') }}</textarea>
                                
                                @error('descricao')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <hr>
                        <div class="row form-group">
                            <div class="col-sm-12">
                                <label for="duracaoAtividade">Duração*:</label>
                                <select class="form-control  @error('duracaoDaAtividade') is-invalid @enderror" name="duracaoDaAtividade" id="duracaoAtividade">
                                    <option value="" selected disabled>-- Duração --</option>
                                    <option value="1" @if(old('duracaoDaAtividade') == "1") selected @endif >Um dia</option>
                                    <option value="2" @if(old('duracaoDaAtividade') == "2") selected @endif>Dois dia</option>
                                    <option value="3" @if(old('duracaoDaAtividade') == "3") selected @endif>Três dia</option>
                                    <option value="4" @if(old('duracaoDaAtividade') == "4") selected @endif>Quatro dia</option>
                                    <option value="5" @if(old('duracaoDaAtividade') == "5") selected @endif>Cinco dia</option>
                                    <option value="6" @if(old('duracaoDaAtividade') == "6") selected @endif>Seis dia</option>
                                    <option value="7" @if(old('duracaoDaAtividade') == "7") selected @endif>Sete dia</option>
                                <select>
                                @error('duracaoDaAtividade')
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
                                <label for="local">Local*:</label>
                                <input class="form-control @error('local') is-invalid @enderror" type="text" name="local" id="local"  placeholder="Local da atividade"  value="{{ old('local') }}">
                            
                                @error('local')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-sm-6">
                                <label for="carga_horaria">Carga horária:</label>
                                <input type="number" class="form-control" id="carga_horaria" name="carga_horaria" placeholder="Carga horária da atividade" value="{{ old('carga_horaria') }}">
                            </div>
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
<div id="divListarComissao" class="comissao" style="display: block">
    <div class="row">
        <div class="col-sm-12">
            <h1 class="titulo-detalhes">Programação</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Atividades</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Atividades que seu evento irá realizar.</h6>
                    <div class="rightButton">
                       <button data-toggle="modal" data-target="#modalCriarAtividade" class="btn btn-primary float-md-right" style="position: relative; bottom: 50px;">+ Criar atividade</button>
                    </div>
                    <small>Clique em uma atividade para editar</small>
                    <p class="card-text">  
                    <table class="table table-hover table-responsive-lg table-sm" style="position: relative; top: -22px;">
                        <thead>
                            <th>
                                <th>Titulo</th>
                                <th>Tipo</th>
                                <th>Vagas</th>
                                <th>Valor</th>
                                <th>Local</th>
                                <th>Carga Horária</th>
                                <th>Visibilidade</th>
                                <th>Excluir</th>
                            </th>
                        </thead>
                        @foreach ($atividades as $atv)
                            <tbody>
                                <th>
                                    <td data-toggle="modal" data-target="#modalAtividadeEdit{{$atv->id}}">{{$atv->titulo}}</td>
                                    <td data-toggle="modal" data-target="#modalAtividadeEdit{{$atv->id}}">{{$atv->tipoAtividade->descricao}}</td>
                                    <td data-toggle="modal" data-target="#modalAtividadeEdit{{$atv->id}}">{{$atv->vagas}}</td>
                                    <td data-toggle="modal" data-target="#modalAtividadeEdit{{$atv->id}}">R$ {{$atv->valor}},00</td>
                                    <td data-toggle="modal" data-target="#modalAtividadeEdit{{$atv->id}}">{{$atv->local}}</td>
                                    <td data-toggle="modal" data-target="#modalAtividadeEdit{{$atv->id}}">{{$atv->carga_horaria}}</td>
                                    <td><input id="checkbox_{{$atv->id}}" type="checkbox" @if($atv->visibilidade_participante) checked @endif onclick="setVisibilidadeAtv({{$atv->id}})"></td>
                                    <td><img src="{{asset('img/icons/trash-alt-regular.svg')}}" class="icon-card" alt=""></td>
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
