@extends('coordenador.detalhesEvento')

@section('menu')
<div id="divListarRevisores" style="display: block">

    <div class="row">
        <div class="col-sm-12">
            <h1 class="titulo-detalhes">Adicionar avaliadores ao evento</h1>
        </div>
    </div>
    @if(session('error'))
    <div class="row">
        <div class="col-md-12" style="margin-top: 5px;">
            <div class="alert alert-danger">
                <p>{{session('error')}}</p>
            </div>
        </div>
    </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                  <div class="container">
                    <div class="row">
                      <div class="col-sm-6" style="right: 1.6%;">
                        <h5 class="card-title">Avaliadores</h5>
                        <h6 class="card-subtitle mb-2 text-muted">Avaliadores atualmente cadastrados</h6>
                      </div>
                      <div class="col-sm-6" style="left: 2.2%;">
                        <form class="form-inline">
                          <div class="form-group mx-sm-3 mb-2">
                            <select class="form-control" name="area_revisores" id="area_revisores">
                              @foreach ($areas as $area)
                                <option value="{{$area->id}}">{{$area->nome}}</option>
                              @endforeach
                            </select>
                          </div>
                          <button type="button" class="btn btn-primary mb-2" onclick="revisoresPorArea()">Filtrar</button>
                        </form>
                      </div>
                    </div>
                  </div>
                  {{-- onclick="revisoresPorArea()" --}}

                  <p class="card-text">
                    <table id="revisores_cadastrados" class="table table-hover table-responsive-lg table-sm">
                        <thead>
                          <tr>
                            <th scope="col">Nome</th>
                            <th scope="col">Área</th>
                            <th scope="col" style="text-align:center">Em Andamento</th>
                            <th scope="col" style="text-align:center">Finalizados</th>
                            <th scope="col" style="text-align:center">Visualizar</th>
                            <th scope="col" style="text-align:center">Convidar</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($revisores as $revisor)
                            @if ($revisor->eventosComoRevisor()->where('evento_id', $evento->id)->first() != null)
                              <tr>
                                <td>{{$revisor->user->email}}</td>
                                <td>{{$revisor->area->nome}}</td>
                                <td style="text-align:center">{{$revisor->correcoesEmAndamento}}</td>
                                <td style="text-align:center">{{$revisor->trabalhosCorrigidos}}</td>
                                <td style="text-align:center">
                                  <a href="#" data-toggle="modal" data-target="#modalRevisor{{$revisor->id}}">
                                    <img src="{{asset('img/icons/eye-regular.svg')}}" style="width:20px">
                                  </a>
                                </td>
                                <td style="text-align:center">
                                    <form action="{{route('revisor.convite.evento', ['id' => $evento->id])}}" method="get" >
                                        <input type="hidden" name="id" value='{{$revisor->user->id}}'>
                                        <button class="btn btn-primary btn-sm" type="submit">
                                            Enviar convite
                                        </button>
                                    </form>
                                </td>
                              </tr>
                            @endif
                          @endforeach
                        </tbody>
                      </table>
                      {{-- @if(count($revisores) > 0 && isset($revisores))
                        <form action="{{route('revisor.emailTodos')}}" method="POST" >
                            @csrf
                              <input type="hidden" name="revisores" value='@json($revisores)'>
                              <button class="btn btn-primary btn-sm" type="submit">
                                  Lembrar todos
                              </button>
                          </form>
                      @endif --}}

                  </p>
                </div>
              </div>
        </div>
    </div>

    <div class="row justify-content-center">
      <div class="col-sm-12">
          <div class="card">
              <div class="card-body">
                <h5 class="card-title">Avaliadores convidados</h5>
                <h6 class="card-subtitle mb-2 text-muted">Avaliadores que foram convidados</h6>
                <p class="card-text">
                  <table class="table table-hover table-responsive-lg table-sm">
                      <thead>
                        <tr>
                          <th scope="col">Nome</th>
                          <th scope="col">Área</th>
                          <th scope="col" style="text-align:center">Em Andamento</th>
                          <th scope="col" style="text-align:center">Finalizados</th>
                          <th scope="col" style="text-align:center">Visualizar</th>
                          <th scope="col" style="text-align:center">Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($revisores as $revisor)
                          @if ($revisor->eventosComoRevisor()->where('evento_id', $evento->id)->first() != null)
                            <tr>
                              <td>{{$revisor->user->email}}</td>
                              <td>{{$revisor->area->nome}}</td>
                              <td style="text-align:center">{{$revisor->correcoesEmAndamento}}</td>
                              <td style="text-align:center">{{$revisor->trabalhosCorrigidos}}</td>
                              <td style="text-align:center">
                                <a href="#" data-toggle="modal" data-target="#modalRevisor{{$revisor->id}}">
                                  <img src="{{asset('img/icons/eye-regular.svg')}}" style="width:20px">
                                </a>
                              </td>
                              <td style="text-align:center">
                                @if ($revisor->eventosComoRevisor()->where([['evento_id', $evento->id], ['convite_aceito', true]])->first() != null)
                                  <h6 style="color: rgb(5, 77, 5)">Aceito</h6>
                                @elseif ($revisor->eventosComoRevisor()->where([['evento_id', $evento->id], ['convite_aceito', null]])->first() != null)
                                  <h6 style="color: rgb(0, 0, 0)">Pendente</h6>
                                @else
                                  <h6 style="color: rgb(107, 25, 11)">Rejeitado</h6>
                                @endif
                              </td>
                            </tr>
                          @endif
                        @endforeach
                      </tbody>
                    </table>

                    {{-- @if(count($revisores) > 0 && isset($revisores))
                      <form action="{{route('revisor.emailTodos')}}" method="POST" >
                          @csrf
                            <input type="hidden" name="revisores" value='@json($revisores)'>
                            <button class="btn btn-primary btn-sm" type="submit">
                                Lembrar todos
                            </button>
                        </form>
                    @endif --}}

                </p>

              </div>
            </div>
      </div>
  </div>
</div>

<!-- Modal Revisor -->
@foreach ($revisores as $revisor)
<div class="modal fade" id="modalRevisor{{$revisor->id}}" tabindex="-1" role="dialog" aria-labelledby="modalRevisor{{$revisor->id}}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Revisor</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row justify-content-center">
          <div class="col-sm-6">
            <label for="">Nome</label>
            <h5>{{$revisor->user->name}}</h5>
          </div>
          <div class="col-sm-6">
            <label for="">E-mail</label>
            <h5>{{$revisor->user->email}}</h5>
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col-sm-6">
            <label for="">Área</label>
            <h5>{{$revisor->area->nome}}</h5>
          </div>
          <div class="col-sm-6">
            <label for="">Instituição</label>
            <h5>{{$revisor->user->instituicao}}</h5>
          </div>
        </div>

        <div class="row justify-content-center" style="margin-top:20px">
          <div class="col-sm-12">
            <h4>Trabalhos</h4>
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col-sm-12">
            <table class="table table-hover table-responsive-lg table-sm">
                <thead>
                  <tr>
                    <th scope="col">Título</th>
                    <th scope="col">Status</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($revisor->trabalhosAtribuidos as $trabalho)
                    <tr>
                      <td>{{$trabalho->titulo}}</td>
                      <td>{{$trabalho->avaliado}}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
          </div>
        </div>
        </div>
    </div>
  </div>
</div>
@endforeach
@endsection
