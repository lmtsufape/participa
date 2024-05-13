@extends('coordenador.detalhesEvento')
<!-- Adiciona o arquivo js para funções apenas dessa pagina -->
<script src="{{ asset('js/js_listar_revisores.js') }}" defer></script>
@section('menu')

    <div id="divListarRevisores" style="display: block">
        @error('errorRevisor')
          @include('componentes.mensagens')
        @enderror
        <div class="row">
            <div class="col-sm-12">
                <h1 class="titulo-detalhes">Listar Avaliadores</h1>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-sm-9">
                          <h5 class="card-title">Avaliadores</h5>
                          <h6 class="card-subtitle mb-2 text-muted">Avaliadores cadastrados no seu evento</h6>
                        </div>
                        <div class="col-sm-3" style="text-align: right;">
                          <button class="btn btn-primary" data-toggle="modal" data-target="#modalCadastrarRevisor">+ Cadastrar revisor</button>
                        </div>
                      </div>
                      <p class="card-text">
                        <table class="table table-hover table-responsive-lg table-sm">
                            <thead>
                              <tr>
                                <th scope="col">Nome</th>
                                <th scope="col">E-mail</th>
                                <th scope="col" style="text-align:center">Em Andamento</th>
                                <th scope="col" style="text-align:center">Finalizados</th>
                                <th scope="col" style="text-align:center">Visualizar</th>
                                <th scope="col" style="text-align:center">Remover</th>
                                <th scope="col" style="text-align:center">Lembrar</th>
                                <th scope="col" style="text-align:center">Cadastro</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach($revisores as $revisor)
                                <tr>
                                  <td data-toggle="modal" data-target="#modalEditarRevisor{{$revisor->id}}">{{$revisor->name}}</td>
                                  <td data-toggle="modal" data-target="#modalEditarRevisor{{$revisor->id}}">{{$revisor->email}}</td>
                                  <td data-toggle="modal" data-target="#modalEditarRevisor{{$revisor->id}}" style="text-align:center">
                                    @if($contadores->where('user_id', $revisor->id)->isNotEmpty())
                                        {{$contadores->where('user_id', $revisor->id)->sum('processando_count')}}
                                    @else
                                        0
                                    @endif
                                </td>
                                  <td data-toggle="modal" data-target="#modalEditarRevisor{{$revisor->id}}" style="text-align:center">
                                    @if($contadores->where('user_id', $revisor->id)->isNotEmpty())
                                        {{$contadores->where('user_id', $revisor->id)->sum('avaliados_count')}}
                                    @else
                                        0
                                    @endif
                                </td>
                                  <td style="text-align:center">
                                    <a href="#" data-toggle="modal" data-target="#modalRevisor{{$revisor->id}}">
                                      <img src="{{asset('img/icons/eye-regular.svg')}}" style="width:20px">
                                    </a>
                                  </td>
                                  <td style="text-align:center">
                                    <form id="removerRevisor{{$revisor->id}}" action="{{route('remover.revisor', ['id' => $revisor->id, 'evento_id' => $evento->id])}}" method="POST">
                                      @csrf
                                      <a href="#" data-toggle="modal" data-target="#modalRemoverRevisor{{$revisor->id}}">
                                        <img src="{{asset('img/icons/user-times-solid.svg')}}" class="icon-card" style="width:25px">
                                      </a>
                                    </form>
                                  </td>
                                  <td style="text-align:center">
                                      {{-- <form action="{{route('revisor.email')}}" method="POST" >
                                        @csrf
                                          <input type="hidden" name="user" value= '@json($revisor->user)'>
                                          <button class="btn btn-primary btn-sm" type="submit">
                                              Enviar e-mail
                                          </button>
                                      </form> --}}
                                      @component('componentes.modal', ['revisor' => $revisor, 'evento' => $evento])

                                      @endcomponent
                                  </td>
                                  <td style="text-align:center">
                                    <form id="reenviarEmailRevisor{{$revisor->id}}" action="{{route('revisor.reenviarEmail', ['id' => $revisor->id, 'evento_id' => $evento->id])}}" method="POST">
                                      @csrf
                                      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalReenviarEmailRevisor{{$revisor->id}}">
                                        Reenviar cadastro
                                      </button>
                                    </form>
                                  </td>
                                </tr>
                              @endforeach
                            </tbody>
                          </table>
                          @if(count($revisores) > 0 && isset($revisores))
                            <form action="{{route('revisor.emailTodos')}}" method="POST" >
                                @csrf
                                  <input type="hidden" name="revisores" value='@json($revisores)'>
                                  <button class="btn btn-primary btn-sm" type="submit">
                                      Lembrar todos os avaliadores com revisão pendente
                                  </button>
                              </form>
                              <form action="{{route('revisor.emailCadastroTodos', $evento->id)}}" method="POST" >
                                @csrf
                                  <button class="btn btn-primary btn-sm" type="submit">
                                      Lembrar todos os avaliadores com cadastro incompleto
                                  </button>
                              </form>
                          @endif

                      </p>

                    </div>
                  </div>
            </div>
        </div>
    </div>
    @foreach($revisores as $revisor)
      <!-- Modal de exclusão do evento -->
        <div class="modal fade" id="modalRemoverRevisor{{$revisor->id}}" tabindex="-1" role="dialog" aria-labelledby="#label" aria-hidden="true">
          <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header" style="background-color: #114048ff; color: white;">
              <h5 class="modal-title" id="#label">Confirmação</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                  <span aria-hidden="true">&times;</span>
              </button>
              </div>
                  <div class="modal-body">
                      Tem certeza que deseja remover esse avaliador do evento?
                  </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                  <button type="submit" class="btn btn-primary" form="removerRevisor{{$revisor->id}}">Sim</button>
              </div>
          </div>
          </div>
        </div>
      <!-- fim do modal -->
      <!-- Modal de reenvio de email -->
        <div class="modal fade" id="modalReenviarEmailRevisor{{$revisor->id}}" tabindex="-1" role="dialog" aria-labelledby="#label" aria-hidden="true">
          <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header" style="background-color: #114048ff; color: white;">
              <h5 class="modal-title" id="#label">Confirmação</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                  <span aria-hidden="true">&times;</span>
              </button>
              </div>
                  <div class="modal-body">
                      Tem certeza que deseja reenviar um e-mail de cadastro para esse avaliador?
                  </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                  <button type="submit" class="btn btn-primary" form="reenviarEmailRevisor{{$revisor->id}}">Sim</button>
              </div>
          </div>
          </div>
        </div>
      <!-- fim do modal -->
      <!-- Modal Revisor -->
      <div class="modal fade" id="modalRevisor{{$revisor->id}}" tabindex="-1" role="dialog" aria-labelledby="modalRevisor{{$revisor->id}}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
          <div class="modal-content">
            <div class="modal-header" style="background-color: #114048ff; color: white;">
              <h5 class="modal-title" id="exampleModalCenterTitle">Visualizar revisor</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row justify-content-center">
                <div class="col-sm-6">
                  <h5 for="">Nome</h5>
                  <div>@if($revisor->name != null){{$revisor->name}}@else O usuário precisa completar o cadastro @endif</div>
                </div>
                <div class="col-sm-6">
                  <h5 for="">E-mail</h5>
                  <div>{{$revisor->email}}</div>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-sm-6">
                  <h5 for="">Instituição</h5>
                  <div>@if($revisor->instituicao != null){{$revisor->instituicao}}@else O usuário precisa completar o cadastro @endif</div>
                </div>
              </div>
              <br>
              <div class="row justify-content-center">
                <div class="col-sm-6">
                  <h5 for="">Áreas de correção</h5>
                  @foreach ($revisor->revisor()->where('evento_id', $evento->id)->distinct('areaId')->get() as $user)
                    <div>{{$user->area->nome}}</div>
                  @endforeach
                </div>
                <div class="col-sm-6">
                  <h5 for="">Modalidades de correção</h5>
                  @foreach ($revisor->revisor()->where('evento_id', $evento->id)->distinct('modalidadeId')->get() as $user)
                    <div>{{$user->modalidade->nome}}</div>
                  @endforeach
                </div>

              </div>
              <br>
              {{-- <div class="row justify-content-center" style="margin-top:20px">

              </div> --}}
              <div class="row justify-content-center">
                <div class="col-sm-12">
                  @foreach ($revisor->revisor()->where('evento_id', $evento->id)->get() as $revisorDosTrabalhos)
                  @if (count($revisorDosTrabalhos->trabalhosAtribuidos) > 0)
                      <div class="container">
                        <div class="row">
                          <h4 style="text-align: left;">Trabalhos da área de {{$revisorDosTrabalhos->area->nome}} na modalidade {{$revisorDosTrabalhos->modalidade->nome}}</h4>
                        </div>
                        <div class="row">
                          <table class="table table-hover table-responsive-lg table-sm">
                            <thead>
                              <tr>
                                <th scope="col" class="col-7">Título</th>
                                <th scope="col" class="col-5">Status</th>

                              </tr>
                            </thead>
                            <tbody>
                              @foreach ($revisorDosTrabalhos->trabalhosAtribuidos()->orderBy('titulo')->get() as $trabalho)
                                <tr>
                                  <td>
                                      <a href="{{route('coord.listarTrabalhos', [ 'eventoId' => $evento->id, 'titulo', 'asc', 'rascunho'])}}#trab{{$trabalho->id}}">{{$trabalho->titulo}}</a></td>
                                  <td>
                                    @if ($trabalho->avaliado($revisor))
                                      Avaliado
                                    @else
                                      Processando
                                    @endif
                                  </td>
                                </tr>
                              @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                    @endif
                  @endforeach
                </div>
              </div>
              </div>
          </div>
        </div>
      </div>

      {{-- Modal editar revisor --}}
      <div class="modal fade" id="modalEditarRevisor{{$revisor->id}}" tabindex="-1" role="dialog" aria-labelledby="modalEditarRevisor{{$revisor->id}}Label" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header" style="background-color: #114048ff; color: white;">
              <h5 class="modal-title" id="modalEditarRevisor{{$revisor->id}}Label">Editar revisor</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form id="editarRevisor{{$revisor->id}}" method="POST" action="{{route('revisor.update')}}">
                    @csrf
                    <p class="card-text">
                        <div class="container">
                          <input type="hidden" name="eventoId" value="{{$evento->id}}">
                          <input type="hidden" name="editarRevisor" value="{{$revisor->id}}">
                          <div class="row">
                              <div class="col-sm-6">
                                  <label for="emailRevisor{{$revisor->id}}" class="col-form-label">{{ __('Email do Revisor') }}</label>
                                  <input id="emailRevisor{{$revisor->id}}" type="email" class="form-control @error('emailRevisor') is-invalid @enderror" name="emailRevisor" value="{{$revisor->email}}" autocomplete="emailRevisor" disabled>

                                  @error('emailRevisor')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                                  @enderror
                              </div>
                          </div>
                          <div  class="row">
                            <div class="col-sm-6">
                              <h6 for="areaRevisor" class="col-form-label">{{ __('Selecione as áreas') }}</h6>
                            <input type="checkbox" value="1" id="chk_marcar_desmarcar_todas_areas" onclick="marcar_desmarcar_todos_checkbox_por_classe(this, 'checkbox_area_{{ $revisor->id }}')">
                            <label for="chk_marcar_desmarcar_todas_areas"><b>Selecionar todas</b></label>
                              @php
                                  $areasRevisor = $revisor->revisor()->distinct('areaId')->get();
                                  $modalidadesRevisor = $revisor->revisor()->distinct('modalidadeId')->get();
                              @endphp
                              @if (old('areasEditadas_'.$revisor->id) != null)
                                @foreach ($areas as $area)
                                  <div class="row">
                                    <div class="col-sm-12">
                                      <input class="checkbox_area_{{ $revisor->id }}" id="area_{{$area->id}}" type="checkbox" name="areasEditadas_{{$revisor->id}}[]" value="{{$area->id}}" @if(in_array($area->id, old('areasEditadas_'.$revisor->id))) checked @endif>
                                      <label for="area_{{$area->id}}">{{$area->nome}}</label>
                                    </div>
                                  </div>
                                @endforeach
                              @else
                                @foreach ($areas as $area)
                                <div class="row">
                                  <div class="col-sm-12">
                                    <input class="checkbox_area_{{ $revisor->id }}"  id="area_{{$area->id}}" type="checkbox" name="areasEditadas_{{$revisor->id}}[]" value="{{$area->id}}" @if($areasRevisor->contains('areaId', $area->id)) checked @endif>
                                    <label for="area_{{$area->id}}">{{$area->nome}}</label>
                                  </div>
                                </div>
                                @endforeach
                              @endif

                              @error('areasEditadas_'.$revisor->id)
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                              @enderror

                            </div>
                            <div class="col-sm-6">
                              <h6 for="modalidadeRevisor" class="col-form-label">{{ __('Selecione as modalidades') }}</h6>
                              <input type="checkbox" value="1" id="chk_marcar_desmarcar_todas_modalidades" onclick="marcar_desmarcar_todos_checkbox_por_classe(this, 'checkbox_modalidade_{{ $revisor->id }}')">
                              <label for="chk_marcar_desmarcar_todas_modalidades"><b>Selecionar todas</b></label>
                              @if (old('modalidadesEditadas_'.$revisor->id) != null)
                                @foreach ($modalidades as $modalidade)
                                    <div class="row">
                                      <div class="col-sm-12">
                                        <input class="checkbox_modalidade_{{ $revisor->id }}" id="modalidade_{{$modalidade->id}}" type="checkbox" name="modalidadesEditadas_{{$revisor->id}}[]" value="{{$modalidade->id}}" @if(in_array($modalidade->id, old('modalidadesEditadas_'.$revisor->id))) checked @endif>
                                        <label for="modalidade_{{$modalidade->id}}">{{$modalidade->nome}}</label>
                                      </div>
                                    </div>
                                @endforeach
                              @else
                                @foreach ($modalidades as $modalidade)
                                <div class="row">
                                  <div class="col-sm-12">
                                    <input class="checkbox_modalidade_{{ $revisor->id }}" id="modalidade_{{$modalidade->id}}" type="checkbox" name="modalidadesEditadas_{{$revisor->id}}[]" value="{{$modalidade->id}}"  @if($modalidadesRevisor->contains('modalidadeId', $modalidade->id)) checked @endif>
                                    <label for="modalidade_{{$modalidade->id}}">{{$modalidade->nome}}</label>
                                  </div>
                                </div>
                                @endforeach
                              @endif

                              @error('modalidadesEditadas_'.$revisor->id)
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                            </div>
                          </div>
                        </div>
                    </p>
                </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-primary" form="editarRevisor{{$revisor->id}}">{{ __('Salvar') }}</button>
            </div>
          </div>
        </div>
      </div>
      {{-- Fim modal editar revisor --}}
    @endforeach

    <!-- Revisores -->
    <div class="modal fade" id="modalCadastrarRevisor" tabindex="-1" role="dialog" aria-labelledby="modalCadastrarRevisorLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background-color: #114048ff; color: white;">
            <h5 class="modal-title" id="modalCadastrarRevisorLabel">Cadastrar um novo avaliador</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <form id="cadastrarRevisorForm" method="POST" action="{{route('revisor.store')}}">
                  @csrf
                  <p class="card-text">
                      <div class="container">
                        <input type="hidden" name="eventoId" value="{{$evento->id}}">
                        <input type="hidden" name="cadastrarRevisor" value="0">
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="emailRevisor" class="col-form-label">{{ __('Email do Avaliador') }}</label>
                                <input id="emailRevisor" type="email" class="form-control @error('emailRevisor') is-invalid @enderror" name="emailRevisor" value="{{old('emailRevisor')}}" required autocomplete="emailRevisor" autofocus>

                                @error('emailRevisor')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div  class="row">
                          <div class="col-sm-6">
                            <h6 for="areaRevisor" class="col-form-label">{{ __('Selecione as áreas') }}</h6>
                            <input type="checkbox" value="1" id="btn_marcar_desmarcar_todas_areas" onclick="marcar_desmarcar_todos_checkbox_por_classe(this, 'checkbox_area')">
                            <label for="btn_marcar_desmarcar_todas_areas"><b>Selecionar todas</b></label>
                            @if (old('areas') != null)
                              @foreach ($areas as $area)
                                <div class="row">
                                  <div class="col-sm-12">
                                    <input class="checkbox_area" id="area_{{$area->id}}" type="checkbox" name="areas[]" value="{{$area->id}}" @if(in_array($area->id, old('areas'))) checked @endif>
                                    <label for="area_{{$area->id}}">{{$area->nome}}</label>
                                  </div>
                                </div>
                              @endforeach
                            @else
                              @foreach ($areas as $area)
                              <div class="row">
                                <div class="col-sm-12">
                                  <input class="checkbox_area" id="area_{{$area->id}}" type="checkbox" name="areas[]" value="{{$area->id}}">
                                  <label for="area_{{$area->id}}" >{{$area->nome}}</label>
                                </div>
                              </div>
                              @endforeach
                            @endif

                            @error('areas')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                          </div>
                          <div class="col-sm-6">
                              <h6 for="modalidadeRevisor" class="col-form-label">{{ __('Selecione as modalidades') }}</h6>
                              <input type="checkbox" value="1" id="btn_marcar_desmarcar_todas_modalidades" onclick="marcar_desmarcar_todos_checkbox_por_classe(this, 'checkbox_modalidade')">
                              <label for="btn_marcar_desmarcar_todas_modalidades"><b>Selecionar todas</b></label>
                              @if (old('modalidades') != null)
                                @foreach ($modalidades as $modalidade)
                                    <div class="row">
                                      <div class="col-sm-12">
                                        <input class="checkbox_modalidade" id="modalidade_{{$modalidade->id}}" type="checkbox" name="modalidades[]" value="{{$modalidade->id}}" @if(in_array($modalidade->id, old('modalidades'))) checked @endif>
                                        <label for="modalidade_{{$modalidade->id}}" >{{$modalidade->nome}}</label>
                                      </div>
                                    </div>
                                @endforeach
                              @else
                                @foreach ($modalidades as $modalidade)
                                <div class="row">
                                  <div class="col-sm-12">
                                    <input class="checkbox_modalidade" id="modalidade_{{$modalidade->id}}" type="checkbox" name="modalidades[]" value="{{$modalidade->id}}">
                                    <label for="modalidade_{{$modalidade->id}}" >{{$modalidade->nome}}</label>
                                  </div>
                                </div>
                                @endforeach
                              @endif

                              @error('modalidades')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                              @enderror
                          </div>
                        </div>
                      </div>
                  </p>
              </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary" form="cadastrarRevisorForm">{{ __('Finalizar') }}</button>
          </div>
        </div>
      </div>
    </div>

@endsection
