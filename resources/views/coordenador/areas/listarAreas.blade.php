@extends('coordenador.detalhesEvento')

@section('menu')
    <div id="divListarAreas" style="display: block">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="titulo-detalhes">Listar Áreas</h1>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-sm-8">
              
                <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">Áreas</h5>
                      <h6 class="card-subtitle mb-2 text-muted">Áreas cadastradas no seu evento</h6>
                      <p class="card-text">
                        <table class="table table-hover table-responsive-lg table-sm">
                            <thead>
                              <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nome</th>
                                <th scope="col" style="text-align:center">Remover</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach($areas as $area)
                                <tr>
                                  <th scope="row">{{$area->id}}</th>
                                  <td>{{$area->nome}}</td>
                                  <td style="text-align:center">
                                    <!-- Modal de exclusão da área -->
                                    <div class="modal fade" id="#modalExcluirArea{{$area->id}}" tabindex="-1" role="dialog" aria-labelledby="#label" aria-hidden="true">
                                      <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="#label">Confirmação</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                            </button>
                                          </div>
                                          <div class="modal-body">
                                            Tem certeza de deseja excluir essa área?
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                            
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <form method="POST" action="{{route('area.destroy',$area->id)}}">
                                      {{ csrf_field() }}
                                      {{ method_field('DELETE') }}
                                      <button type="submit" class="dropdown-item" data-toggle="modal" data-target="#modalExcluirArea{{$area->id}}">
                                        <img src="{{asset('img/icons/trash-alt-regular.svg')}}" class="icon-card" alt="">
                                      </button>
                                    </form>
                                  </td>
                                </tr>
                                
                
                              @endforeach
                            </tbody>
                          </table>
                      </p>
                    </div>
                  </div>
            </div>
        </div>
    </div>


@endsection
