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
            <div class="modal-body">
                <form method="POST" action="{{ route('coord.atividades.update', ['id' => $atv->id]) }}">
                    @csrf
                    <div class="container">
                        <div class="row form-group">
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
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
            </div>
        </div>
    </div>
@endforeach
<div class="modal fade" id="modalCriarAtividade" tabindex="-1" role="dialog" aria-labelledby="modalLabelCriarAtividade" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalLabelCriarAtividade">Editar Atividade</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('coord.atividades.update', ['id' => $atv->id]) }}">
                @csrf
                <div class="container">
                    <div class="row form-group">
                        <div class="col-sm-6">
                            <label for="titulo">Titulo:</label>
                            <input class="form-control" type="text" name="titulo" id="titulo" value="">
                        </div>
                        <div class="col-sm-6">
                            <label for="tipo">Tipo:</label>
                            <select class="form-control" name="tipo" id="tipo">
                                <option value="" selected disabled>-- Tipo --</option>
                                @foreach ($tipos as $tipo)
                                    <option value="{{ $tipo->id }}">{{ $tipo->descricao }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-12">
                            <label for="descricao">Descricao:</label>
                            <textarea class="form-control" rows="5" name="descricao" id="descricao"></textarea>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-6">
                            <label for="vagas">Vagas:</label>
                            <input class="form-control" type="number" name="vagas" id="vagas" value="">
                        </div>
                        <div class="col-sm-6">
                            <label for="valor">Valor:</label>
                            <input type="number" step="0.01" class="form-control" min="0.01" id="valor" name="valor" value="">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-6">
                            <label for="local">Local:</label>
                            <input class="form-control" type="text" name="local" id="local" value="">
                        </div>
                        <div class="col-sm-6">
                            <label for="carga_horaria">Carga horária:</label>
                            <input type="number" class="form-control" id="carga_horaria" name="carga_horaria" value="">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            <button type="submit" class="btn btn-primary">Salvar</button>
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
