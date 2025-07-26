@extends('layouts.app')

@section('content')
<div class="container content" style="margin-top: 80px">
    <div class="row">
        <div class="col-sm-12">
          <h1 class="titulo-detalhes">Trabalhos de {{$evento->nome}}</h1>
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

    {{-- Tabela Trabalhos --}}
    @foreach ($trabalhosPorRevisor as $trabalhosDoRevisor)
      @if ($trabalhosDoRevisor != null && count($trabalhosDoRevisor) > 0)
        <div class="row justify-content-center" style="width: 100%;">
          <div class="col-sm-12">
              <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">Trabalhos da área de {{$trabalhosDoRevisor[0]->area->nome}}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Que tem como modalidade {{$trabalhosDoRevisor[0]->modalidade->nome}}
                      <a href="#" data-bs-toggle="modal" data-bs-target="#modalRegrasModalidade{{$trabalhosDoRevisor[0]->modalidade->id}}"><img src="{{asset('/img/icons/eye-regular.svg')}}" alt="Visualizar regras da modalidade" width="15px" title="Visualizar regras da modalidade"></a>
                    </h6>
                    <p class="card-text">
                      <div class="col-sm-12">
                      <table class="table table-hover table-responsive-lg table-sm">
                        <thead>
                          <tr>
                            <th scope="col">Código</th>
                            <th scope="col">Título</th>
                            <th scope="col">Status</th>
                            <th scope="col">Resumo</th>
                            <th scope="col">Baixar</th>
                            {{-- <th scope="col">Avaliar</th> --}}
                            <th scope="col">Questionário</th>
                          </tr>
                        </thead>
                        @foreach($trabalhosDoRevisor as $trabalho)
                            <tr>
                              <td>{{$trabalho->id}}</td>
                              <td>{{$trabalho->titulo}}</td>
                              @if ($trabalho->avaliado == "Avaliado")
                                <td>Avaliado</td>
                              @else
                                <td>Pendente</td>
                              @endif
                              <td>
                                @if ($trabalho->resumo != null)
                                  <a class="resumoTrabalho" href="#" data-bs-toggle="modal" onclick="resumoModal({{$trabalho->id}})" data-bs-target="#exampleModalLong"><img src="{{asset('img/icons/resumo.png')}}" style="width:20px"></a>
                                @else
                                  Sem resumo
                                @endif
                              </td>
                              <td>
                                @if ($trabalho->arquivo != null && $trabalho->arquivo->count() > 0)
                                  <a href="{{route('downloadTrabalho', ['id' => $trabalho->id])}}"><img src="{{asset('img/icons/file-download-solid-black.svg')}}" style="width:20px"></a>
                                @endif
                              </td>
                              @if ($trabalho->avaliado != "Avaliado")
                                @if (now() >= $trabalho->modalidade->inicioRevisao && now() <= $trabalho->modalidade->fimRevisao)
                                  {{-- <td>
                                    <a href="#"><img src="{{asset('img/icons/check-solid.svg')}}" style="width:20px" data-bs-toggle="modal" data-bs-target="#modalAvaliarTrabalho{{$trabalho->id}}"></a>
                                  </td> --}}
                                  <td>
                                    <form action="{{route('revisor.responde')}}" method="get">
                                      @csrf
                                      <input type="hidden" name="revisor_id" value="{{$trabalho->atribuicoes()->where('user_id', auth()->user()->id)->first()->id}}">
                                      <input type="hidden" name="trabalho_id" value="{{$trabalho->id}}">
                                      <input type="hidden" name="evento_id" value="{{$evento->id}}">
                                      <input type="hidden" name="modalidade_id" value="{{$trabalho->modalidade->id}}">
                                      <button type="submit" class="btn btn-success">
                                        Avaliar
                                      </button>
                                    </form>
                                    {{--<h6 class="card-subtitle mb-2 text-muted">Avaliar trabalho
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalAvaliarTrabalho{{$trabalho->id}}"><img src="{{asset('/img/icons/eye-regular.svg')}}" alt="Avaliar trabalho final" width="15px" title="Avaliar trabalho final"></a>
                                    </h6>--}}
                                  </td>
                                @else

                                  <td>
                                    <img src="{{asset('img/icons/check-solid.svg')}}" style="width:20px" title="Avaliação disponível em {{date('d/m/Y',strtotime($trabalho->modalidade->inicioRevisao))}} até {{date('d/m/Y',strtotime($trabalho->modalidade->fimRevisao))}}">
                                  </td>
                                @endif
                                {{-- {{$trabalho->atribuicoes()->where('user_id', auth()->user()->id)->first()->id}} --}}
                                <td>



                                </td>
                              @else
                              <td>
                                <img src="{{asset('img/icons/check-solid.svg')}}" style="width:20px" title="Trabalho já avaliado">
                              </td>
                              @endif
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
                      <strong>Periodo de avaliação</strong>
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

    @foreach ($trabalhosPorRevisor as $trabalhosDoRevisor)
      @foreach($trabalhosDoRevisor as $trabalho)
        <!-- Modal Nota -->
        <div class="modal fade" id="modalAvaliarTrabalho{{$trabalho->id}}" tabindex="-1" role="dialog" aria-labelledby="labelModalAvaliarTrabalho{{$trabalho->id}}" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="labelModalAvaliarTrabalho{{$trabalho->id}}">Avaliar {{$trabalho->titulo}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form id="formAvaliarTrabalho{{$trabalho->id}}" class="" action="{{ route('trabalho.avaliacao.revisor', ['id' => $trabalho->id]) }}" method="POST">
                  @csrf
                  <div class="form-group">
                    <input type="hidden" name="evento_id" value="{{$evento->id}}">
                    <input type="hidden" name="avaliar_trabalho_id" value="{{$trabalho->id}}">
                    <input type="hidden" name="modalidade_id" value="{{$trabalho->modalidade->id}}">
                    <input type="hidden" name="area_id" value="{{$trabalho->area->id}}">
                    @foreach ($trabalho->modalidade->criterios as $criterio)
                      <div class="row">
                        <div class="col-sm-12">
                          <label for="nota">{{$criterio->nome}}</label>
                          <select class="form-control @error('criterio_{{$criterio->id}}') is-invalid @enderror" name="criterio_{{$criterio->id}}" id="" required>
                            <option value="" selected disabled>-- Selecionar opção --</option>
                            @foreach ($criterio->opcoes as $opcao)
                              <option value="{{$opcao->id}}">{{$opcao->nome_opcao}}</option>
                            @endforeach
                          </select>

                          @error('criterio_{{$criterio->id}}')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror
                        </div>
                      </div>
                    @endforeach
                    <div class="row">
                      <div class="col-sm-12">
                        <label for="nota">Parecer final</label>
                        <select class="form-control" name="parecer_final" id="" required>
                          <option value="" selected disabled>-- Selecionar parecer --</option>
                          <option value="Aprovado">Aprovado</option>
                          <option value="Reprovado">Reprovado</option>
                        </select>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12">
                        <label for="nota">Justificativa</label>
                        <textarea class="form-control" name="justificativa" id="" cols="30" rows="5" placeholder="Justificativa" required></textarea>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary" form="formAvaliarTrabalho{{$trabalho->id}}">Atribuir Nota</button>
              </div>
            </div>
          </div>
        </div>
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
