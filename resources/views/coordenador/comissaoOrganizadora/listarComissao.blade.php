@extends('coordenador.detalhesEvento')

@section('menu')
    <div id="divListarComissaoOrganizadora" class="comissao" style="display: block">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="titulo-detalhes">Listar Comissão Organizadora</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">Comissão</h5>
                      <h6 class="card-subtitle mb-2 text-muted">Usuários cadastrados na comissão do seu evento.</h6>
                      <p class="card-text">
                        <table class="table table-hover table-responsive-lg table-sm">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Especialidade</th>
                                    <th>Celular</th>
                                    <th>E-mail</th>
                                    @can('isCoordenadorDaComissaoOrganizadora', $evento)
                                        <th>Direção</th>
                                        <th style="text-align:center">Remover</th>
                                    @endcan
                                </tr>
                            </thead>
                                @if ($users != null)
                                  @foreach ($users as $user)
                                  <!-- Modal de exclusão do evento -->
                                    @can('isCoordenadorDaComissaoOrganizadora', $evento)
                                    <div class="modal fade" id="modalRemoverComissao{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="#label" aria-hidden="true">
                                      <div class="modal-dialog" role="document">
                                      <div class="modal-content">
                                          <div class="modal-header" style="background-color: #114048ff; color: white;">
                                          <h5 class="modal-title" id="#label">Confirmação</h5>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                                              <span aria-hidden="true">&times;</span>
                                          </button>
                                          </div>
                                              <div class="modal-body">
                                                  Tem certeza que deseja remover esse membro da comissão organizadora do evento?
                                              </div>
                                          <div class="modal-footer">
                                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                                              <button type="submit" class="btn btn-primary" form="removerComissao{{$user->id}}">Sim</button>
                                          </div>
                                      </div>
                                      </div>
                                    </div>
                                    @endcan
                                    <!-- fim do modal -->
                                  <tbody>

                                      <tr>
                                        @if (isset($user->name))
                                          <td>{{$user->name}}</td>
                                          <td>{{$user->especProfissional}}</td>
                                          <td>{{$user->celular}}</td>
                                          <td>{{$user->email}}</td>
                                          @can('isCoordenadorDaComissaoOrganizadora', $evento)
                                              <td>
                                                Coordenador
                                              </td>
                                              <td style="text-align:center">
                                                <form id="removerComissao{{$user->id}}" action="{{route('coord.remover.comissao.organizadora', ['id' => $user->id])}}" method="POST">
                                                  @csrf
                                                  <input type="hidden" name="evento_id" value="{{$evento->id}}">
                                                  <a href="#" data-toggle="modal" data-target="#modalRemoverComissao{{$user->id}}">
                                                    <img src="{{asset('img/icons/user-times-solid.svg')}}" class="icon-card" style="width:25px">
                                                  </a>
                                                </form>
                                              </td>
                                          @endcan
                                        @else
                                          <td>Usuário temporário - Sem nome</td>
                                          <td>Usuário temporário - Sem Especialidade</td>
                                          <td>Usuário temporário - Sem Celular</td>
                                          <td>{{$user->email}}</td>
                                          @can('isCoordenadorDaComissaoOrganizadora', $evento)
                                              <td>
                                                Coordenador
                                              </td>
                                              <td style="text-align:center">
                                                <form id="removerComissao{{$user->id}}" action="{{route('coord.remover.comissao.organizadora', ['id' => $user->id])}}" method="POST">
                                                  @csrf
                                                  <input type="hidden" name="evento_id" value="{{$evento->id}}">
                                                  <a href="#" data-toggle="modal" data-target="#modalRemoverComissao{{$user->id}}">
                                                    <img src="{{asset('img/icons/user-times-solid.svg')}}" class="icon-card" style="width:25px">
                                                  </a>
                                                </form>
                                              </td>
                                          @endcan
                                          @endif
                                      </tr>
                                  </tbody>
                                  @endforeach
                                @endif
                        </table>
                      </p>
                    </div>
                  </div>
            </div>
        </div>


        {{-- tabela membros comissão --}}
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-responsive-lg table-hover">

                </table>
            </div>
        </div>
    </div>{{-- End Listar Comissão --}}
@endsection
