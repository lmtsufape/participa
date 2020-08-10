@extends('coordenador.detalhesEvento')

@section('menu')
    {{-- Informações --}}
    <div id="divInformacoes" class="informacoes" style="display: block">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="titulo-detalhes">Informações</h1>
            </div>
        </div>

        <!-- Row trabalhos -->
        <div class="row justify-content-center">
          <div class="col-sm-8">


            <div class="row justify-content-center">
              <div class="col-sm-12">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">Trabalhos</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Informações referente aos trabalhos enviados</h6>
                    <p class="card-text">
                      <div class="row justify-content-center">
                        <div class="col-sm-12">
                          <table class="table table-responsive-lg table-hover">
                            <thead>
                              <tr>
                                <th style="text-align:center">Enviados</th>
                                <th style="text-align:center">Avaliados</th>
                                <th style="text-align:center">Pendentes</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td style="text-align:center"> {{$trabalhosEnviados}} </td>
                                <td style="text-align:center"> {{$trabalhosAvaliados}} </td>
                                <td style="text-align:center"> {{$trabalhosPendentes}} </td>
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
                                <th style="text-align:center">Número de Revisores</th>
                                <th style="text-align:center">Número de Integrantes na Comissão</th>

                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td style="text-align:center"> {{$numeroRevisores}} </td>
                                <td style="text-align:center"> {{$numeroComissao}} </td>
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

          </div>



        </div><!-- end Row trabalhos -->

    </div>

@endsection
