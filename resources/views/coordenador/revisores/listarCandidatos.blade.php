@extends('coordenador.detalhesEvento')

@section('menu')

<div id="divListarCandidatos">
    <div class="row">
        <div class="col-sm-12">
            <h1 class="titulo-detalhes">Candidaturas para Avaliador(a)</h1>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-sm-9">
                        <h5 class="card-title">Candidatos</h5>
                        <h6 class="card-subtitle mb-2 text-muted">Lista de candidatos a avaliadores para o evento</h6>
                    </div>
                    <div>
                        <a href="{{ route('coord.candidatos.exportar', $evento) }}" class="btn btn-primary">Exportar .xlsx</a>
                    </div>
                  </div>
                  <p class="card-text">
                    <table class="table table-hover table-responsive-lg table-sm">
                        <thead>
                          <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nome</th>
                            <th scope="col">E-mail</th>
                            <th scope="col" style="text-align:center">Status</th>
                            <th scope="col" style="text-align:center">Ações</th>
                          </tr>
                        </thead>
                        <tbody>


                        @foreach($candidaturas  as $candidatura)
                            <tr>
                              <td>{{$loop->iteration}}</td>  
                              <td>{{$candidatura->user->name}}</td>
                              <td>{{$candidatura->user->email}}</td>
                              <td class="text-center">
                                @if ($candidatura->aprovado)
                                    <span class="badge bg-success">Aprovado</span>
                                @elseif ($candidatura->em_analise)
                                    <span class="badge bg-warning text-dark">Em Análise</span>
                                @else
                                    <span class="badge bg-danger">Reprovado</span>
                                @endif
                            </td>
                              <td style="text-align:center">
                                  <div class="d-flex justify-content-center">
                                    <button class="btn btn-sm btn-light m-1" data-bs-toggle="modal" data-bs-target="#modalVisualizarCandidato{{$candidatura->id}}">
                                        <img src="{{asset('img/icons/eye-regular.svg')}}" style="width:20px" alt="Visualizar">
                                    </button>

                                    @if ($candidatura->em_analise)
                                        <button type="submit" class="btn btn-sm btn-success m-1" title="Aprovar Candidatura" data-bs-toggle="modal" data-bs-target="#confirmAprovarModal{{ $candidatura->id }}">
                                            <img src="{{asset('img/icons/check-solid.svg')}}" style="width:15px" alt="Aprovar">
                                        </button>

                                        <button type="submit" class="btn btn-sm btn-danger m-1" title="Rejeitar Candidatura" data-bs-toggle="modal" data-bs-target="#confirmRejeitarModal{{ $candidatura->id }}">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16" style="vertical-align: middle;">
                                            <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                                          </svg>
                                        </button>
                                      </form>
                                    @endif
                                  </div>
                              </td>
                            </tr>


                            <div class="modal fade" id="confirmAprovarModal{{ $candidatura->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-success text-white">
                                    <h5 class="modal-title">Confirmar Aprovação</h5>
                                    <button type="button" class="btn-close btn-close-white " data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                    Tem certeza de que deseja <strong>aprovar</strong> a candidatura de
                                    <em>{{ $candidatura->user->name }}</em>?
                                    </div>
                                    <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <form action="{{ route('coord.candidaturas.aprovar', $candidatura->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="evento_id" value="{{ $evento->id }}">
                                        <input type="hidden" name="user_id"   value="{{ $candidatura->user->id }}">
                                        <button type="submit" class="btn btn-success">Sim, Aprovar</button>
                                    </form>
                                    </div>
                                </div>
                                </div>
                            </div>


                            <div class="modal fade" id="confirmRejeitarModal{{ $candidatura->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title">Confirmar reprovação</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            Tem certeza de que deseja <strong>reprovar</strong> a candidatura de
                                            <em>{{ $candidatura->user->name }}</em>?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <form action="{{ route('coord.candidaturas.rejeitar', $candidatura->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="evento_id" value="{{ $evento->id }}">
                                                <input type="hidden" name="user_id"   value="{{ $candidatura->user->id }}">
                                                <button type="submit" class="btn btn-danger">Sim, reprovar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        </tbody>
                    </table>
                  </p>
                </div>
              </div>
        </div>
    </div>
</div>
@foreach($candidaturas as $candidatura)
<!-- Modal Visualizar Candidato -->
<div class="modal fade" id="modalVisualizarCandidato{{$candidatura->id}}" tabindex="-1" aria-labelledby="modalVisualizarCandidatoLabel{{$candidatura->id}}" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #114048ff; color: white;">
        <h5 class="modal-title" id="modalVisualizarCandidatoLabel{{$candidatura->id}}">Detalhes da Candidatura</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        {{-- Informações do Usuário --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label fw-bold">Nome do Candidato</label>
                <p>{{$candidatura->user->name}}</p>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">E-mail</label>
                <p>{{$candidatura->user->email}}</p>
            </div>
        </div>

        <hr>

        <div class="row mb-3">
            <div class="col-12">
                <label class="form-label fw-bold">Link para o currículo Lattes</label>
                <p><a href="{{$candidatura->lattes_link}}" target="_blank">{{$candidatura->lattes_link}}</a></p>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12">
                <label class="form-label fw-bold">Resumo simplificado do Lattes</label>
                <p style="text-align: justify;">{{$candidatura->resumo_lattes}}</p>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12">
                <label class="form-label fw-bold">Eixos temáticos de preferência</label>
                <ul>
                    @foreach($candidatura->eixos as $eixo)
                        <li>{{$eixo}}</li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label fw-bold">Já avaliou resumos do CBA em outros anos?</label>
                <p class="text-capitalize">{{$candidatura->avaliou_antes}}</p>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Disponibilidade para avaliar em outros idiomas</label>
                <p class="text-capitalize">{{ implode(', ', $candidatura->idiomas) }}</p>
            </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>
@endforeach


@endsection
