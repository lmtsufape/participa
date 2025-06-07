@extends('coordenador.detalhesEvento')

@section('menu')

<div id="divListarCandidatos">
    @include('componentes.mensagens')
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
                  </div>
                  <p class="card-text">
                    <table class="table table-hover table-responsive-lg table-sm">
                        <thead>
                          <tr>
                            <th scope="col">Nome</th>
                            <th scope="col">E-mail</th>
                            <th scope="col" style="text-align:center">Status</th>
                            <th scope="col" style="text-align:center">Ações</th>
                          </tr>
                        </thead>
                        <tbody>
                          @php
                            $candidaturasExemplo = [
                                (object)['id' => 1, 'user' => (object)['name' => 'Ana Carolina', 'email' => 'ana.carolina@example.com'], 'aprovado' => false, 'lattes_link' => 'http://lattes.cnpq.br/123', 'resumo_lattes' => 'Doutora em Agroecologia pela UFRPE, com experiência em sistemas agroflorestais.', 'eixos' => ['Eixo 01: Agriculturas Urbanas', 'Eixo 05: Campesinato, Soberania e Segurança Alimentar e Nutricional'], 'avaliou_antes' => 'sim', 'idiomas' => ['espanhol']],
                                (object)['id' => 2, 'user' => (object)['name' => 'Bruno Martins', 'email' => 'bruno.martins@example.com'], 'aprovado' => true, 'lattes_link' => 'http://lattes.cnpq.br/456', 'resumo_lattes' => 'Mestre em Extensão Rural, focado em comunicação popular.', 'eixos' => ['Eixo 03: Arte, Cultura, Comunicação Popular, Mídias Sociais e Agroecologia'], 'avaliou_antes' => 'nao', 'idiomas' => ['nao']],
                            ];
                          @endphp
                          
                          @foreach($candidaturasExemplo as $candidatura)
                            <tr>
                              <td>{{$candidatura->user->name}}</td>
                              <td>{{$candidatura->user->email}}</td>
                              <td style="text-align:center">
                                @if($candidatura->aprovado)
                                  <span class="badge bg-success">Aprovado</span>
                                @else
                                  <span class="badge bg-warning text-dark">Em Análise</span>
                                @endif
                              </td>
                              <td style="text-align:center">
                                  <div class="d-flex justify-content-center">
                                    <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#modalVisualizarCandidato{{$candidatura->id}}">
                                        <img src="{{asset('img/icons/eye-regular.svg')}}" style="width:20px" alt="Visualizar">
                                    </button>

                                    @if (!$candidatura->aprovado)
                                      <form action="#" method="POST" class="ms-2"> 
                                        @csrf
                                        @method('PUT') {{-- Método para atualização --}}
                                        <button type="submit" class="btn btn-sm btn-success" title="Aprovar Candidatura">
                                            <img src="{{asset('img/icons/check-solid.svg')}}" style="width:15px" alt="Aprovar">
                                        </button>
                                      </form>
                                    
                                      <form action="#" method="POST" class="ms-2"> 
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" title="Rejeitar Candidatura">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16" style="vertical-align: middle;">
                                            <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                                          </svg>    
                                        </button>
                                      </form>
                                    @endif
                                  </div>
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
@foreach($candidaturasExemplo as $candidatura)
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
