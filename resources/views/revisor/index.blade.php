@extends('layouts.app')

@section('content')

<div class="container" style="position: relative; top: 80px;">

    {{-- titulo da página --}}
    <div class="row justify-content-center titulo-detalhes">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-10">
                    <h1>Trabalhos/Atividades a serem avaliadas</h1>
                    <br>
                </div>
                {{-- <div class="col-sm-2">
                    <a href="{{route('evento.criar')}}" class="btn btn-primary">Novo Evento</a>
                </div> --}}
            </div>
        </div>
    </div>

    @if(session('mensagem'))
        <div class="col-md-12" style="margin-top: 5px;">
            <div class="alert alert-success">
                <p>{{session('mensagem')}}</p>
            </div>
        </div>
    @endif
    @if(session('message'))
        <div class="row">
            <div class="col-md-12" style="margin-top: 5px;">
                <div class="alert alert-success">
                    <p>{{session('message')}}</p>
                </div>
            </div>
        </div>
    @endif

    {{--<div class="row cards-eventos-index">
        @foreach ($eventos as $evento)
            @if ($evento->deletado == false)
                <div class="card" style="width: 16rem;">
                    @if(isset($evento->fotoEvento))
                        <img class="img-card" src="{{asset('storage/'.$evento->fotoEvento)}}" class="card-img-top" alt="...">
                    @else
                        <img class="img-card" src="{{asset('img/colorscheme.png')}}" class="card-img-top" alt="...">
                    @endif
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="card-title">
                                    <div class="row">
                                        <div class="col-sm-10">
                                            {{$evento->nome}}
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="btn-group dropright dropdown-options">
                                                <a id="options" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <div onmouseout="this.children[0].src='{{ asset('/img/icons/ellipsis-v-solid.svg') }}';" onmousemove="this.children[0].src='{{ asset('/img/icons/ellipsis-v-solid-hover.svg')}}';">
                                                        <img src="{{asset('img/icons/ellipsis-v-solid.svg')}}" style="width:8px">
                                                    </div>
                                                </a>
                                                <div class="dropdown-menu">
                                                    <a href="{{ route('revisor.trabalhos.evento', ['id' => $evento->id]) }}" class="dropdown-item">
                                                        <img src="{{asset('img/icons/eye-regular.svg')}}" class="icon-card" alt="">
                                                        Visualizar trabalhos
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </h4>

                            </div>
                        </div>
                        <div>
                            <p class="card-text">
                                <img src="{{ asset('/img/icons/calendar.png') }}" alt="" width="20px;" style="position: relative; top: -2px;"> {{date('d/m/Y',strtotime($evento->dataInicio))}} - {{date('d/m/Y',strtotime($evento->dataFim))}}<br>
                                {{-- <strong>Submissão:</strong> {{date('d/m/Y',strtotime($evento->inicioSubmissao))}} - {{date('d/m/Y',strtotime($evento->fimSubmissao))}}<br>
                                <strong>Revisão:</strong> {{date('d/m/Y',strtotime($evento->inicioRevisao))}} - {{date('d/m/Y',strtotime($evento->fimRevisao))}}<br>
                            </p>
                            <p>
                                <div class="row justify-content-center">
                                    <div class="col-sm-12">
                                        <img src="{{ asset('/img/icons/location_pointer.png') }}" alt="" width="18px" height="auto">
                                        {{$evento->endereco->rua}}, {{$evento->endereco->numero}} - {{$evento->endereco->cidade}} / {{$evento->endereco->uf}}.
                                    </div>
                                </div>
                            </p>
                        </div>
                        <p>
                            <a href="{{  route('evento.visualizar',['id'=>$evento->id])  }}" class="visualizarEvento">Visualizar Evento</a>
                        </p>
                    </div>

                </div>
            @endif
        @endforeach
    </div>--}}

    @foreach ($trabalhosPorEvento as $key => $trabalhosPorRevisor)
        @if(count($trabalhosPorRevisor) > 0)
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="titulo-detalhes" style="text-align:center">
                        <a href="{{  route('evento.visualizar',['id'=>$eventos[count($eventos)-1-$key]->id])  }}"> {{$eventos[count($eventos)-1-$key]->nome}} </a>
                    </h3>
                </div>
            </div>
        @endif

        {{-- Tabela Trabalhos --}}
        @foreach ($trabalhosPorRevisor as $trabalhosDoRevisor)
            @if ($trabalhosDoRevisor != null && count($trabalhosDoRevisor) > 0)
            <div class="row justify-content-center" style="width: 100%;">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                        <h5 class="card-title">{{$trabalhosDoRevisor[0]->evento->formSubTrab->etiquetaareatrabalho}}: <span class="card-subtitle mb-2 text-muted" >{{$trabalhosDoRevisor[0]->area->nome}}</span></h5>
                        <h5 class="card-title">Modalidade: <span class="card-subtitle mb-2 text-muted" >{{$trabalhosDoRevisor[0]->modalidade->nome}}</span>
                            <a href="#" data-toggle="modal" data-target="#modalRegrasModalidade{{$trabalhosDoRevisor[0]->modalidade->id}}"><img src="{{asset('/img/icons/eye-regular.svg')}}" alt="Visualizar regras da modalidade" width="15px" title="Visualizar regras da modalidade"></a>
                        </h5>
                        <h5 class="card-title">Período de avaliação: <span class="card-subtitle mb-2 text-muted" >De {{date('d/m/Y',strtotime($trabalhosDoRevisor[0]->modalidade->inicioRevisao))}} até {{date('d/m/Y',strtotime($trabalhosDoRevisor[0]->modalidade->fimRevisao))}}</span></h5>
                        <p class="card-text">
                            <div class="col-sm-12">
                            <table class="table table-hover table-responsive-lg table-sm">
                            <thead>
                                <tr>
                                <th scope="col" style="text-align:center">Código</th>
                                <th scope="col" style="text-align:center">Título</th>
                                <th scope="col" style="text-align:center">Status</th>
                                <th scope="col" style="text-align:center">Resumo</th>
                                <th scope="col" style="text-align:center">Baixar</th>
                                {{-- <th scope="col">Avaliar</th> --}}
                                <th scope="col" style="text-align:center">Questionário</th>
                                <th scope="col" style="text-align:center">Atribuído em</th>
                                </tr>
                            </thead>
                            @foreach($trabalhosDoRevisor as $trabalho)
                                <tr>
                                    <td style="text-align:center">{{$trabalho->id}}</td>
                                    <td style="text-align:center">{{$trabalho->titulo}}</td>
                                    @if ($trabalho->avaliado == "Avaliado")
                                    <td style="text-align:center">Avaliado</td>
                                    @else
                                    <td style="text-align:center">Pendente</td>
                                    @endif
                                    <td style="text-align:center">
                                    @if ($trabalho->resumo != null)
                                        <a class="resumoTrabalho" href="#" data-toggle="modal" onclick="resumoModal({{$trabalho->id}})" data-target="#exampleModalLong"><img src="{{asset('img/icons/resumo.png')}}" style="width:20px"></a>
                                    @else
                                        Sem resumo
                                    @endif
                                    </td>
                                    <td style="text-align:center">
                                    @if ($trabalho->arquivo != null && $trabalho->arquivo->count() > 0)
                                        <a href="{{route('downloadTrabalho', ['id' => $trabalho->id])}}"><img src="{{asset('img/icons/file-download-solid-black.svg')}}" style="width:20px"></a>
                                    @endif
                                    </td>
                                    @if ($trabalho->avaliado != "Avaliado")
                                    @if (now() >= $trabalho->modalidade->inicioRevisao && now() <= $trabalho->modalidade->fimRevisao)
                                        {{-- <td>
                                        <a href="#"><img src="{{asset('img/icons/check-solid.svg')}}" style="width:20px" data-toggle="modal" data-target="#modalAvaliarTrabalho{{$trabalho->id}}"></a>
                                        </td> --}}
                                        <td>
                                        <form action="{{route('revisor.responde')}}" method="get">
                                            @csrf
                                            <input type="hidden" name="revisor_id" value="{{$trabalho->atribuicoes()->where('user_id', auth()->user()->id)->first()->id}}">
                                            <input type="hidden" name="trabalho_id" value="{{$trabalho->id}}">
                                            <input type="hidden" name="evento_id" value="{{$eventos[$key]->id}}">
                                            <input type="hidden" name="modalidade_id" value="{{$trabalho->modalidade->id}}">
                                            <div class="d-flex justify-content-center">
                                                <button type="submit" class="btn btn-success">
                                                Avaliar
                                                </button>
                                            </div>
                                        </form>
                                        </td>
                                    @else
                                        <div class="d-flex justify-content-center">
                                            <td style="text-align:center">
                                                <img src="{{asset('img/icons/check-solid.svg')}}" style="width:20px" title="Avaliação disponível em {{date('d/m/Y',strtotime($trabalho->modalidade->inicioRevisao))}} até {{date('d/m/Y',strtotime($trabalho->modalidade->fimRevisao))}}">
                                            </td>
                                        </div>
                                    @endif
                                    {{-- {{$trabalho->atribuicoes()->where('user_id', auth()->user()->id)->first()->id}} --}}
                                    @else
                                        <div class="d-flex justify-content-center">
                                            <td style="text-align:center">
                                                <img src="{{asset('img/icons/check-solid.svg')}}" style="width:20px" title="Trabalho já avaliado">
                                            </td>
                                        </div>
                                    @endif
                                    <td style="text-align:center">
                                        {{date('d/m/Y H:i',strtotime($trabalho->atribuicoes->first()->pivot->created_at))}}
                                    </td>
                                </tr>
                            @endforeach
                            </table>
                        </p>
                        </div>
                    </div>
                </div>
            </div>
            {{-- @else
            <h4>Nenhum trabalho atribuido para correção</h4> --}}
            @endif
        @endforeach


        @foreach ($trabalhosPorRevisor as $trabalhosDoRevisor)
            <!-- Modal regras da Modalidade-->
            @if($trabalhosDoRevisor != null && count($trabalhosDoRevisor) > 0)
            <div class="modal fade" id="modalRegrasModalidade{{$trabalhosDoRevisor[0]->modalidade->id}}" tabindex="-1" role="dialog" aria-labelledby="#labelModalRegrasModalidade{{$trabalhosDoRevisor[0]->modalidade->id}}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">Regras da modalidade {{$trabalhosDoRevisor[0]->modalidade->nome}} </h5>
                    </div>
                    <div class="modal-body">
                    <div class="container">
                        <div class="row">
                        <div class="col-sm-12">
                            <strong>Período de avaliação</strong>
                            <p id="periodo">
                            De {{date('d/m/Y',strtotime($trabalhosDoRevisor[0]->modalidade->inicioRevisao))}} até {{date('d/m/Y',strtotime($trabalhosDoRevisor[0]->modalidade->fimRevisao))}}
                            </p>
                        </div>
                        </div>
                        <div class="row">
                        <div class="col-sm-12">
                            @if ($trabalhosDoRevisor[0]->modalidade->arquivo != null && $trabalhosDoRevisor[0]->modalidade->arquivo == true)
                            <strong>Forma de avaliação</strong>
                            <p id="formaDeAvaliacao">
                                Avaliação por arquivo submetido
                            </p>
                            @else
                            <strong>Forma de avaliação</strong>
                            <p id="formaDeAvaliacao">
                                Avaliação por texto digitado
                            </p>
                            @endif
                        </div>
                        </div>
                        <div class="row">
                        <div class="col-sm-12">
                            @if ($trabalhosDoRevisor[0]->modalidade->caracteres)
                            <strong>Limite por quantidade caracteres</strong>
                            <p>
                                Minimo: {{number_format($trabalhosDoRevisor[0]->modalidade->mincaracteres, 0, ',', '.')}}<br>
                                Máximo: {{number_format($trabalhosDoRevisor[0]->modalidade->maxcaracteres, 0, ',', '.')}}
                            </p>
                            @elseif ($trabalhosDoRevisor[0]->modalidade->palavras)
                            <strong>Limite por quantidade palavras</strong>
                            <p>
                                Minimo: {{number_format($trabalhosDoRevisor[0]->modalidade->minpalavras, 0, ',', '.')}}<br>
                                Máximo: {{number_format($trabalhosDoRevisor[0]->modalidade->maxpalavras, 0, ',', '.')}}
                            </p>
                            @endif
                        </div>
                        </div>
                        <div class="row">
                        <div class="col-sm-12">
                            @if ($trabalhosDoRevisor[0]->modalidade->regra != null)
                            <a href="{{route('modalidade.regras.download', ['id' => $trabalhosDoRevisor[0]->modalidade->id])}}">Arquivo de regras extras</a>
                            @endif
                        </div>
                        </div>
                    </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
                </div>
            </div>
            @endif
        @endforeach
    @endforeach


    <!-- Modal Resumo-->
    <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="resumoModalLongTitle"></h5>
            </div>
            <div class="modal-body" name="resumoTrabalhoModal" id="resumoTrabalhoModal">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
            </div>
          </div>
        </div>
      </div>
      
</div>

<input type="hidden" name="resumoModalAjax" value="1" id="resumoModalAjax">
@endsection

@section('javascript')
  @if (old('avaliar_trabalho_id') != null)
  <script>
      $(document).ready(function() {
          $('#modalAvaliarTrabalho{{old('avaliar_trabalho_id')}}').modal('show');
      });
  </script>
  @endif

  <script type="text/javascript" >
      function resumoModal(x){
        console.log(x);
        document.getElementById('resumoModalAjax').value = x;
      }

      $(function(){
        $('.resumoTrabalho').click(function(e){
          e.preventDefault();
          $.ajaxSetup({
              headers: {
                  // 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                  'Content-Type': 'application/json',
                  'X-Requested-With': 'XMLHttpRequest'
              }
          });

          jQuery.ajax({
            url: "{{ route('trabalhoResumo') }}",
            method: 'get',
            data: {
              trabalhoId: $('#resumoModalAjax').val()
            },
            success: function(result){
              console.log(result.resumo);
              $('#resumoModalLongTitle').html('Resumo de ' + result.titulo);
              $('#resumoTrabalhoModal').html(result.resumo);
          }});
        });
      });

  </script>
@endsection
