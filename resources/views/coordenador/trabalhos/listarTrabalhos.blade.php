@extends('coordenador.detalhesEvento')

@section('menu')
    <!-- Trabalhos -->
    <div id="divListarTrabalhos" style="display: block">

        <div class="row titulo-detalhes">
            <div class="col-sm-10">
                <h1 class="">Trabalhos</h1>
            </div>

            <form method="GET" action="{{route('distribuicao')}}">
              <input type="hidden" name="eventoId" value="{{$evento->id}}">
              <div class="row justify-content-center">
                <div class="col-md-12">
                  <button onclick="event.preventDefault();" data-toggle="modal" data-target="#modalDistribuicaoAutomatica" class="btn btn-primary" style="width:100%">
                    {{ __('Distribuir Trabalhos') }}
                  </button>
                </div>
              </div>
            </form>

        </div>

    {{-- Tabela Trabalhos --}}
    <div class="row">
      <div class="col-sm-12">
        <table class="table table-hover table-responsive-lg table-sm">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Área</th>
              <th scope="col">Modalidade</th>
              <th scope="col">Revisores</th>
              <th scope="col" style="text-align:center">Baixar</th>
              <th scope="col" style="text-align:center">Visualizar</th>
            </tr>
          </thead>
          <tbody>
            @php $i = 0; @endphp
            @foreach($trabalhos as $trabalho)

            <tr>
              <td>{{$trabalho->id}}</td>
              <td>{{$trabalho->area->nome}}</td>
              <td>{{$trabalho->modalidade->nome}}</td>
              <td>
                @foreach($trabalho->atribuicao as $atribuicao)
                {{$atribuicao->revisor->user->email}},
                @endforeach
              </td>
              <td style="text-align:center">
                @php $arquivo = ""; $i++; @endphp
                @foreach($trabalho->arquivo as $key)
                @php
                if($key->versaoFinal == true){
                  $arquivo = $key->nome;
                }
                @endphp
                @endforeach
                @if (!(empty($trabalho->arquivo->items)))
                    <a href="{{route('downloadTrabalho', ['id' => $trabalho->id])}}"><img src="{{asset('img/icons/file-download-solid-black.svg')}}" style="width:20px"></a>
                @endif
              </td>
              <td style="text-align:center">
                <a class="botaoAjax" href="#" data-toggle="modal" onclick="trabalhoId({{$trabalho->id}})" data-target="#modalTrabalho"><img src="{{asset('img/icons/eye-regular.svg')}}" style="width:20px"></a>
              </td>

            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

    </div>

</div>
<!-- End Trabalhos -->
<!-- Modal Trabalho -->
<div class="modal fade" id="modalDistribuicaoAutomatica" tabindex="-1" role="dialog" aria-labelledby="modalDistribuicaoAutomatica" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Trabalho</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="GET" action="{{ route('distribuicaoAutomaticaPorArea') }}" id="formDistribuicaoPorArea">
        <div class="modal-body">
          <input type="hidden" name="eventoId" value="{{$evento->id}}">
          <div class="row">
            <div class="col-sm-12">
                <label for="areaId" class="col-form-label">{{ __('Área') }}</label>
                <select class="form-control @error('areaId') is-invalid @enderror" id="areaIdformDistribuicaoPorArea" name="areaId">
                    <option value="" disabled selected hidden> Área </option>
                    @foreach($areas as $area)
                        <option value="{{$area->id}}">{{$area->nome}}</option>
                    @endforeach
                </select>

                @error('areaId')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
          </div>
          <div class="row">
              <div class="col-sm-12">
                  <label for="numeroDeRevisoresPorTrabalho" class="col-form-label">{{ __('Número de revisores por trabalho') }}</label>
              </div>
          </div>
          <div class="row justify-content-center">
              <div class="col-sm-12">
                  <input id="numeroDeRevisoresPorTrabalhoInput" type="number" min="1" class="form-control @error('numeroDeRevisoresPorTrabalho') is-invalid @enderror" name="numeroDeRevisoresPorTrabalho" value="{{ old('numeroDeRevisoresPorTrabalho') }}" required autocomplete="numeroDeRevisoresPorTrabalho" autofocus>

                  @error('numeroDeRevisoresPorTrabalho')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                  @enderror
              </div>

          </div>{{-- end row--}}
        </div>
      </form>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button id="numeroDeRevisoresPorTrabalhoButton" disabled onclick="document.getElementById('formDistribuicaoPorArea').submit();" type="button" class="btn btn-primary">Distribuir</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal Trabalho -->
<div class="modal fade" id="modalTrabalho" tabindex="-1" role="dialog" aria-labelledby="modalTrabalho" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Trabalho</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row justify-content-center">
          <div class="col-sm-12">
            <h5>Título</h5>
            <p id="tituloTrabalhoAjax"></p>
          </div>

        </div>
        <div class="row justify-content-center">
          <div class="col-sm-12">
            <h5>Resumo</h5>
            <p id="resumoTrabalhoAjax"></p>
          </div>
        </div>

        <div class="row justify-content-center" style="margin-top:20px">
          <div class="col-sm-12">
            <h5>Remover Revisor</h5>
          </div>
        </div>
        <form action="{{ route('atribuicao.delete') }}" method="post">
          @csrf
          <input type="hidden" name="eventoId" value="{{$evento->id}}">
          <input type="hidden" name="trabalhoId" value="" id="removerRevisorTrabalhoId">
        <div class="row justify-content-center">
          <div class="col-sm-9">
              <div id="revisoresAjax" class="revisoresTrabalho" style="padding-left:20px">
                <div id="cblist">

                </div>
              </div>
          </div>
          <div class="col-sm-3">
            <button type="submit" class="btn btn-primary" id="removerRevisorTrabalho">Remover Revisor</button>
          </div>
        </div>
      </form>
        <div class="row">
          <div class="col-sm-12">
            <h5>Adicionar Revisor</h5>
          </div>
        </div>
        <form action="{{ route('distribuicaoManual') }}" method="post">
          @csrf
          <input type="hidden" name="trabalhoId" value="" id="distribuicaoManualTrabalhoId">
          <input type="hidden" name="eventoId" value="{{$evento->id}}">
          <div class="row" >
            <div class="col-sm-9">
              <div class="form-group">
                <select name="revisorId" class="form-control" id="selectRevisorTrabalho">


                </select>
              </div>
            </div>
            <div class="col-sm-3">
              <button type="submit" class="btn btn-primary" id="addRevisorTrabalho">Adicionar Revisor</button>
            </div>
        </form>
        </div>
        </div>
      <div class="modal-footer">


      </div>
    </div>
  </div>
</div>
@endsection
