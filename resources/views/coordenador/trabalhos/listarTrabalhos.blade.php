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
                <img onclick="document.getElementById('formDownload{{$i}}').submit();" class="" src="{{asset('img/icons/file-download-solid-black.svg')}}" style="width:20px" alt="">
                <form method="GET" action="{{ route('download') }}" target="_new" id="formDownload{{$i}}">
                  <input type="hidden" name="file" value="{{$arquivo}}">
                </form>
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

   </div><!-- End Trabalhos -->
@endsection
