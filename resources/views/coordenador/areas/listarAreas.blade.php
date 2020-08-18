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
                                    <img src="{{asset('img/icons/trash-alt-regular.svg')}}" style="width:15px">
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
