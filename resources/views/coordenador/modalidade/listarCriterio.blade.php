@extends('coordenador.detalhesEvento')

@section('menu')

    <div id="divListarCriterio" class="comissao">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="titulo-detalhes">Listar Critérios</h1>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">Critérios</h5>
                      <h6 class="card-subtitle mb-2 text-muted">Critérios cadastrados por modalidades</h6>
                      <p class="card-text">
                        <table class="table table-hover table-responsive-lg table-sm">
                            <thead>
                            <tr>
                                <th scope="col">Nome</th>
                                <th scope="col">Peso</th>
                                <th scope="col">Modalidade</th>
                                <th scope="col">Editar</th>
                                <th scope="col">Excluir</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($criterios as $criterio)
                                    @foreach ($modalidades as $modalidade)
                                        @if ($modalidade->id == $criterio->modalidadeId)
                                            <tr>
                                                <td>{{$criterio->nome}}</td>
                                                <td>{{$criterio->peso}}</td>
                                                <td>{{$modalidade->nome}}</td>
                                                <td style="text-align:center">
                                                    <a href="#" data-toggle="modal" data-target="#modalEditarCriterio{{$criterio->id}}"><img src="{{asset('img/icons/edit-regular.svg')}}" style="width:20px"></a>
                                                </td>
                                                <td style="text-align:center">
                                                    <a href="#" data-toggle="modal" data-target="#modalExcluirCriterio{{$criterio->id}}"><img src="{{asset('img/icons/lixo.png')}}" style="width:20px"></a> 
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                      </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($criterios as $criterio)
        {{-- Modal para edição de critérios --}}
        <div class="modal fade" tabindex="-1" id="modalEditarCriterio{{$criterio->id}}" aria-labelledby="modalEditarCriterio{{$criterio->id}}" role="dialog">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #114048ff; color: white;">
                <h5 class="modal-title">Editar {{$criterio->nome}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <form id="formCriterioUpdate" method="POST" action="{{route('atualizar.criterio', ['id' => $criterio->id])}}">
                        @csrf
                        <div class="form-group">
                            <input type="hidden" name="eventoId" value="{{$evento->id}}">
                            <label for="exampleInputEmail1">Nome</label>
                            <input type="text" class="form-control" name="nomeCriterioUpdate" id="nomeCriterioUpdate" value="{{$criterio->nome}}" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Peso</label>
                            <input type="number" class="form-control" name="pesoCriterioUpdate" id="pesoCriterioUpdate" value="{{$criterio->peso}}" required>
                        </div>
                        <div class="form-group">
                            <h5>Opções para avaliação</h5>
                        </div>
                        @foreach ($criterio->opcoes as $i => $opcao)
                            @if ($i == 0)
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-7">
                                            <h6>Opções</h6>
                                        </div>
                                        <div class="col-sm-5">
                                            <h6>Valores reais</h6>
                                        </div>
                                    </div>
                                </div> 
                            @endif
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-7">
                                        <input type="hidden" name="idOpcaoCriterio[]" value="{{$opcao->id}}" >
                                        <input type="text" class="form-control" name="opcaoCriterio[]" value="{{$opcao->nome_opcao}}"required>
                                    </div>
                                    <div class="col-sm-5">
                                        <input type="number" class="form-control" name="valor_real_criterio[]" value="{{$opcao->valor_real}}" required onchange="validandoValorReal(this)">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" form="formCriterioUpdate">Atualizar</button>
                </div>
            </div>
            </div>
        </div>
        {{-- Fim Modal --}}

        {{-- Modal para excluir critérios --}}
        <div class="modal fade" tabindex="-1" id="modalExcluirCriterio{{$criterio->id}}" aria-labelledby="modalExcluirCriterio{{$criterio->id}}" role="dialog">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #114048ff; color: white;">
                <h5 class="modal-title">Confirmação</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    Tem certeza que deseja excluir {{$criterio->nome}}?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                    <a href="{{route('criterio.destroy', ['evento_id' => $evento->id, 'id' => $criterio->id])}}"><button type="button" class="btn btn-primary">Sim</button></a>
                </div>
            </div>
            </div>
        </div>
        {{-- Fim Modal --}}
    @endforeach

@endsection
