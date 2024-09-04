@extends('coordenador.detalhesEvento')

@section('menu')
    {{-- Informações --}}
    <div id="divInformacoes" class="informacoes" style="display: block">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="titulo-detalhes">Informações</h1>
            </div>
        </div>

        @if ($evento->publicado == false)
          <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Atenção!</strong> Seu evento não está exposto ao público, para publicá-lo vá em Publicar -> Publicar evento
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        @endif

        <!-- Row trabalhos -->
        <div class="row justify-content-center">
          <div class="col-sm-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Inscrições</h5>
                    <h6 class="card-subtitle mb-2">Informações referente as inscrições no evento</h6>
                    <div class="card-text">
                        <div class="table-responsive text-center">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Inscrições</th>
                                        <th>Validadas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{$evento->inscricaos_count}}</td>
                                        <td>{{$evento->inscricoes_validadas_count}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row justify-content-center">
              <div class="col-sm-12">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">Trabalhos (geral)</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Informações referente aos trabalhos enviados</h6>
                    <p class="card-text">
                      <div class="row justify-content-center">
                        <div class="col-sm-12">
                          <table class="table table-responsive-lg table-hover">
                            <thead>
                              <tr>
                                <th style="text-align:center">Enviados</th>
                                <th style="text-align:center">Arquivados</th>
                                <th style="text-align:center">Avaliados</th>
                                <th style="text-align:center">Pendentes</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td style="text-align:center"> {{$evento->enviados_count}} </td>
                                <td style="text-align:center"> {{$evento->arquivados_count}} </td>
                                <td style="text-align:center"> {{$evento->avaliados_count}} </td>
                                <td style="text-align:center"> {{$evento->pendentes_count}} </td>
                              </tr>
                            </tbody>
                          </table>

                        </div>

                      </div>
                    </p>
                  </div>
                </div>
              </div>
            </div>

            @isset($evento->modalidades)
                @foreach ($evento->modalidades as $modalidade)
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Trabalhos da modalidade {{$modalidade->nome}}</h5>
                            <h6 class="card-subtitle mb-2">Informações referente aos trabalhos enviados na modalidade</h6>
                            <p class="card-text">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Enviados</th>
                                                <th>Arquivados</th>
                                                <th>Avaliados</th>
                                                <th>Pendentes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{$modalidade->enviados_count}}</td>
                                                <td>{{$modalidade->arquivados_count}}</td>
                                                <td>{{$modalidade->avaliados_count}}</td>
                                                <td>{{$modalidade->pendentes_count}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </p>
                        </div>
                    </div>
                @endforeach
            @endisset

            <div class="row justify-content-center">
              <div class="col-sm-12">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">Organização</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Informações referentes ao número de participantes na organização do evento</h6>
                    <p class="card-text">
                      <div class="row justify-content-center">
                        <div class="col-sm-12">
                          <table class="table table-responsive-lg table-hover">
                            <thead>
                              <tr>
                                <th style="text-align:center">Número de Avaliadores</th>
                                <th style="text-align:center">Número de Integrantes na Comissão Científica</th>
                                <th style="text-align:center">Número de Integrantes na Comissão Organizadora</th>

                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td style="text-align:center">{{$evento->revisores_count}}</td>
                                <td style="text-align:center">{{$evento->comissao_cientifica_count}}</td>
                                <td style="text-align:center">{{$evento->comissao_organizadora_count}}</td>
                              </tr>
                            </tbody>
                          </table>

                        </div>

                      </div>
                    </p>
                  </div>
                </div>
              </div>

            </div>

            @isset($evento->atividade)
                @foreach ($evento->atividade as $atividade)
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Atividade {{$atividade->titulo}}</h5>
                            <h6 class="card-subtitle mb-2">Informações referente as inscrições na atividade</h6>
                            <p class="card-text">
                                <div class="table-responsive text-center">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Inscritos</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{$atividade->inscritos_count}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </p>
                        </div>
                    </div>
                @endforeach
            @endisset

          </div>



        </div><!-- end Row trabalhos -->

    </div>

@endsection
