@extends('coordenador.detalhesEvento')

@section('menu')
    <div>
      @error('excluirAtividade')  
        @include('componentes.mensagens')
      @enderror
    </div>
    <div id="divListarAreas" style="display: block">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="titulo-detalhes">Listar Áreas</h1>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-sm-12">
              
                <div class="card">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-10">
                          <h5 class="card-title">Áreas</h5>
                        </div>
                        <div class="col-md-2">
                          
                          @component('componentes.modal-area', ['evento' => $evento])
                            
                          @endcomponent
                        </div>

                      </div>
                      <h6 class="card-subtitle mb-2 text-muted">Áreas cadastradas no seu evento</h6>
                      <p class="card-text">
                        <table class="table table-hover table-responsive-lg table-sm">
                            <thead>
                              <tr>
                                <th scope="col">Nome</th>
                                <th scope="col" style="text-align:center">Editar</th>
                                <th scope="col" style="text-align:center">Remover</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach($areas as $area)
                                <tr>
                                  <td>{{$area->nome}}</td>
                                  <td style="text-align:center">
                                    <a href="#" data-toggle="modal" data-target="#modalEditarArea{{$area->id}}"><img src="{{asset('img/icons/edit-regular.svg')}}" style="width:20px"></a>
                                  </td>
                                  <td style="text-align:center">
                                    <form id="formExcluirArea{{$area->id}}" method="POST" action="{{route('area.destroy',$area->id)}}">
                                      {{ csrf_field() }}
                                      {{ method_field('DELETE') }}
                                      <a href="#" data-toggle="modal" data-target="#modalExcluirArea{{$area->id}}">
                                        <img src="{{asset('img/icons/trash-alt-regular.svg')}}" class="icon-card" alt="">
                                      </a>
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

    @foreach($areas as $area)
      <!-- Modal de editar área -->
      <div class="modal fade" id="modalEditarArea{{$area->id}}" tabindex="-1" role="dialog" aria-labelledby="#label" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header" style="background-color: #114048ff; color: white;">
              <h5 class="modal-title" id="#label">Editar área {{$area->nome}}</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form id="formEditarArea{{$area->id}}" action="{{route('area.update', ['id' => $area->id])}}" method="POST">
                @csrf
                <input type="hidden" name="editarAreaId" value="{{$area->id}}">
                <div class="container">
                  <div class="row form-group">
                    <div class="col-sm-12" style="margin-top: 20px; margin-bottom: 20px;">
                      <label for="nome_da_área">Nome</label>
                      <input id="nome_da_área" type="text" class="form-control apenasLetras @error('nome_da_área') is-invalid @enderror" name="nome_da_área" value="@if(old('nome_da_área') != null){{old('nome_da_área')}}@else{{$area->nome}}@endif">
    
                      @error('nome_da_área')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                      @enderror
                    </div>
                  </div>
                </div>
            </form>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-primary" form="formEditarArea{{$area->id}}">Atualizar</button>
            </div>
          </div>
        </div>
      </div>
      <!-- Modal de exclusão da área -->
      <div class="modal fade" id="modalExcluirArea{{$area->id}}" tabindex="-1" role="dialog" aria-labelledby="#label" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header" style="background-color: #114048ff; color: white;">
              <h5 class="modal-title" id="#label">Confirmação</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              Tem certeza que deseja excluir essa área?
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
              <button type="submit" class="btn btn-primary" form="formExcluirArea{{$area->id}}">Sim</button>
            </div>
          </div>
        </div>
      </div>
    @endforeach

@endsection
