@extends('coordenador.detalhesEvento')

@section('menu')
    <div id="divListarComissao" class="comissao" style="display: block">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="titulo-detalhes">Listar Comissão Científica</h1>
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
                                <th>
                                    <th>Nome</th>
                                    <th>Especialidade</th>
                                    <th>Celular</th>
                                    <th>E-mail</th>
                                    <th>Função</th>
                                    <th style="text-align:center">Remover</th>
                                </th>
                            </thead>
                                @if ($users != null)
                                  @foreach ($users as $user)
                                  <!-- Modal de exclusão do evento -->
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
                                                  Tem certeza que deseja remover esse membro da comissão do evento?
                                              </div>
                                          <div class="modal-footer">
                                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                                              <button type="submit" class="btn btn-primary" form="removerComissao{{$user->id}}">Sim</button>
                                          </div>
                                      </div>
                                      </div>
                                    </div>
                                  <!-- fim do modal -->
                                  <tbody>

                                      <th>
                                        @if (isset($user->name))
                                          <td>{{$user->name}}</td>
                                          <td>{{$user->especProfissional}}</td>
                                          <td>{{$user->celular}}</td>
                                          <td>{{$user->email}}</td>
                                          <td>
                                              @if($evento->userIsCoordComissaoCientifica($user))
                                                  Coordenador(a) da Comissão
                                              @elseif($user->eixosCoordenados->isNotEmpty())
                                                  Coordenador(a) de Eixo:
                                                  <ul class="list-unstyled mb-0 ps-3">
                                                      @foreach($user->eixosCoordenados as $eixoNome)
                                                          <li><small>- {{ $eixoNome }}</small></li>
                                                      @endforeach
                                                  </ul>
                                              @else
                                                  Membro da Comissão
                                              @endif
                                          </td>
                                          <td style="text-align:center">
                                            <form id="removerComissao{{$user->id}}" action="{{route('coord.remover.comissao', ['id' => $user->id])}}" method="POST">
                                              @csrf
                                              <input type="hidden" name="evento_id" value="{{$evento->id}}">
                                              <a href="#" data-bs-toggle="modal" data-bs-target="#modalRemoverComissao{{$user->id}}">
                                                <img src="{{asset('img/icons/user-times-solid.svg')}}" class="icon-card" style="width:25px">
                                              </a>
                                            </form>
                                          </td>
                                        @else
                                          <td>Usuário temporário - Sem nome</td>
                                          <td>Usuário temporário - Sem Especialidade</td>
                                          <td>Usuário temporário - Sem Celular</td>
                                          <td>{{$user->email}}</td>
                                          <td>
                                            @if($evento->userIsCoordComissaoCientifica($user))
                                                Coordenador
                                            @endif
                                          </td>
                                          <td style="text-align:center">
                                            <form id="removerComissao{{$user->id}}" action="{{route('coord.remover.comissao', ['id' => $user->id])}}" method="POST">
                                              @csrf
                                              <input type="hidden" name="evento_id" value="{{$evento->id}}">
                                              <a href="#" data-bs-toggle="modal" data-bs-target="#modalRemoverComissao{{$user->id}}">
                                                <img src="{{asset('img/icons/user-times-solid.svg')}}" class="icon-card" style="width:25px">
                                              </a>
                                            </form>
                                          </td>
                                        @endif
                                      </th>
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
