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
                                                    <a class="botaoAjax" href="#" data-toggle="modal" onclick="criterioId({{$criterio->id}})" data-target="#modalEditarCriterio"><img src="{{asset('img/icons/edit-regular.svg')}}" style="width:20px"></a>
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
    {{-- Modal para edição de critérios --}}
    <div class="modal fade" tabindex="-1" id="modalEditarCriterio" aria-labelledby="modalEditarCriterio" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Editar Critério</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form id="formCriterioUpdate" method="POST" action="{{route('atualizar.criterio')}}">
                    @csrf
                    <input type="hidden" name="eventoId" value="{{$evento->id}}">
                    <input type="hidden" name="modalidadeId" id="modalidadeIdCriterioUpdate" value="">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Nome</label>
                      <input type="text" class="form-control" name="nomeCriterioUpdate" id="nomeCriterioUpdate" value="">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword1">Peso</label>
                      <input type="number" class="form-control" name="pesoCriterioUpdate" id="pesoCriterioUpdate" value="">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" form="formCriterioUpdate">Atualizar</button>
            </div>
          </div>
        </div>
    </div>
    {{-- Fim Modal --}}

@endsection
