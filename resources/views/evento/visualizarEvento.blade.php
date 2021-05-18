@extends('layouts.app')

@section('content')

@foreach ($atividades as $atv)
  <div class="modal fade bd-example modal-show-atividade" id="modalAtividadeShow{{$atv->id}}" tabindex="-1" role="dialog" aria-labelledby="modalLabelAtividadeShow{{$atv->id}}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header" style="background-color: #114048ff; color: white;">
            <h5 class="modal-title" id="modalLabelAtividadeShow{{$atv->id}}">{{ $atv->titulo }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
            <div class="modal-body">
              <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <h4 for="tipo">Tipo</h4>
                        <p>
                          {{$atv->tipoAtividade->descricao}}
                        </p>
                    </div>
                </div>
                <hr>
                <div class="row form-group">
                    <div class="col-sm-12">
                        <h4 for="descricao">Descrição</label>
                        <p>
                          {{$atv->descricao}}
                        </p>
                    </div>
                </div>
                @if (count($atv->convidados) > 0)
                  <hr>
                  <h4>Convidados</h4>
                  <div class="convidadosDeUmaAtividade">
                    <div class="row">
                      @foreach ($atv->convidados as $convidado)
                        <div class="col-sm-3 imagemConvidado">
                            <img src="{{asset('img/icons/user.png')}}" alt="Foto de {{$convidado->nome}}" width="50px" height="auto">
                            <h5 class="convidadoNome">{{$convidado->nome}}</h5>
                            <small class="convidadoFuncao">{{$convidado->funcao}}</small>
                        </div>
                      @endforeach
                    </div>
                  </div>
                @endif
                <hr>
                <div class="row">
                    <div class="col-sm-12">
                        <h4 for="local">Local</h4>
                        <p class="local">
                          {{$atv->local}}
                        </p>
                    </div>
                </div>
                @if ($atv->vagas != null || $atv->valor != null)
                  <hr>
                  <div class="row">
                    @if ($atv->vagas != null)
                      <div class="col-sm-6">
                        <label for="vagas">Vagas:</label>
                        <h4 class="vagas">{{$atv->vagas}}</h4>
                      </div>
                    @endif
                    @if ($atv->valor != null)
                      <div class="col-sm-6">
                        <label for="valor">Valor:</label>
                        <h4 class="valor">R$ {{$atv->valor}}</h4>
                      </div>
                    @endif
                  </div>
                @endif
                @if ($atv->carga_horaria != null)
                <hr>
                <div class="row">
                  <div class="col-sm-12">
                    <label for="carga_horaria">Carga horária para estudantes:</label>
                    <h4 class="carga_horaria">{{$atv->carga_horaria}}</h4>
                  </div>
                </div>
                @endif
            </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
  </div>
@endforeach

<div class="modal fade" id="modalTrabalho" tabindex="-1" role="dialog" aria-labelledby="modalTrabalho" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Submeter nova versão</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" action="{{route('trabalho.novaVersao')}}" enctype="multipart/form-data">
        @csrf
      <div class="modal-body">

        <div class="row justify-content-center">
          <div class="col-sm-12">
              @if($hasFile)
                <input type="hidden" name="trabalhoId" value="" id="trabalhoNovaVersaoId">
              @endif
              <input type="hidden" name="eventoId" value="{{$evento->id}}">

              {{-- Arquivo  --}}
              <label for="nomeTrabalho" class="col-form-label">{{ __('Arquivo') }}</label>

              <div class="custom-file">
                <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="arquivo">
              </div>
              <small>O arquivo Selecionado deve ser no formato PDF de até 2mb.</small>
              @error('arquivo')
              <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary">Salvar</button>
      </div>
    </form>
    </div>
  </div>
</div>



<div class="container-fluid content">
    <div class="row">
        @if(isset($evento->fotoEvento))
          <div class="banner-evento">
              <img style="background-size: cover" src="{{asset('storage/eventos/'.$evento->id.'/logo.png')}}" alt="">
          </div>
        @else
          <div class="banner-evento">
              <img style="background-size: cover" src="{{asset('img/colorscheme.png')}}" alt="">
          </div>
          {{-- <img class="front-image-evento" src="{{asset('img/colorscheme.png')}}" alt=""> --}}
        @endif
    </div>
</div>

<div class="card-visualizar-evento justify-content-center">
  <div class="card" style="width: 80%;">
    <div class="card-body">
      {{-- <h5 class="card-title">Special title treatment</h5>
      <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
      <a href="#" class="btn btn-primary">Go somewhere</a> --}}
      <div class="container" style="margin-top:20px">
        @if(!Auth::check())
          <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong> A submissão de um trabalho é possível apenas quando cadastrado no sistema. </strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        @endif

        {{-- <div class="row margin">
            <div class="col-sm-12">
                <h4>{{$etiquetas->etiquetanomeevento}}:</h4>
            </div>
        </div> --}}
        <div class="row margin">
            <div class="col-sm-12">
                <h1>
                    {{$evento->nome}}
                </h1>
            </div>
        </div>

        {{-- <div class="row margin">
            <div class="col-sm-12">
                <h4>{{$etiquetas->etiquetadescricaoevento}}:</h4>
            </div>
        </div> --}}
        <div class="row margin">
            <div class="col-sm-12">
                <p>{{$evento->descricao}}</p>
            </div>
        </div>

        {{-- <div class="row margin">
          <div class="col-sm-12">
              <h4>{{$etiquetas->etiquetatipoevento}}:</h4>
          </div>
        </div> --}}
        <div class="row">
          <div class="col-sm-6">
            <div class="container">


              <hr>
              <div class="row">
                @if ($etiquetas->modsubmissao == true)
                  <div class="col-sm-12 info-evento">
                    <h5>{{$etiquetas->etiquetasubmissoes}}</h5>
											<div class="accordion" id="accordion_modalidades">
												@foreach ($modalidades as $modalidade)
													<div class="accordion-group">


														<div class="accordion-heading accordion-modadalidade">
															<a class="accordion-button accordion-toggle titulo-modalidade" data-toggle="collapse" data-parent="#accordion_modalidades" href="{{ '#collapse_' . $modalidade->id }}">
																<h6>Modalidade: {{$modalidade->nome}}</h6>
															</a>
														</div>

														<div id="{{ 'collapse_' . $modalidade->id }}" class="accordion-body in collapse" style="height: auto;">
															<div class="accordion-inner">
																<table>
																	<tr>
																			{{-- @php
																																						 date_default_timezone_set('America/Recife');
																																						 @endphp --}}
																		<td><img class="" src="{{asset('img/icons/calendar-pink.png')}}" alt=""></td>
																		<td>Envio:</td>
																		<td>{{date('d/m/Y H:i',strtotime($modalidade->inicioSubmissao))}}</td>
																		<td>- {{date('d/m/Y H:i',strtotime($modalidade->fimSubmissao))}}</td>
																	</tr>
																	<tr>
																		<td><img class="" src="{{asset('img/icons/calendar-yellow.png')}}" alt=""></td>
																		<td>Avaliação:</td>
																		<td>{{date('d/m/Y H:i',strtotime($modalidade->inicioRevisao))}}</td>
																		<td>- {{date('d/m/Y H:i',strtotime($modalidade->fimRevisao))}}</td>
																	</tr>
																	@if($modalidade->inicioCorrecao && $modalidade->fimCorrecao)
																		<tr>
																			<td><img class="" src="{{asset('img/icons/calendar-yellow.png')}}" alt=""></td>
																			<td>Correção:</td>
																			<td>{{date('d/m/Y H:i',strtotime($modalidade->inicioCorrecao))}}</td>
																			<td>- {{date('d/m/Y H:i',strtotime($modalidade->fimCorrecao))}}</td>
																		</tr>
																	@endif

																	@if($modalidade->inicioValidacao && $modalidade->fimValidacao)
																		<tr>
																			<td><img class="" src="{{asset('img/icons/calendar-yellow.png')}}" alt=""></td>
																			<td>Validação:</td>
																			<td>{{date('d/m/Y H:i',strtotime($modalidade->inicioValidacao))}}</td>
																			<td>- {{date('d/m/Y H:i',strtotime($modalidade->fimValidacao))}}</td>
																		</tr>
																	@endif
																	<tr>
																		<td><img class="" src="{{asset('img/icons/calendar-green.png')}}" alt=""></td>
																		<td>Resultado:</td>
																		<td>{{date('d/m/Y  H:i',strtotime($modalidade->inicioResultado))}}</td>
																	</tr>
																</table>

																{{-- {{dd(Carbon\Carbon::parse($modalidade->inicioSubmissao))}} --}}
																@if(Carbon\Carbon::parse($modalidade->inicioSubmissao) <= $mytime)
																	@if($mytime <= Carbon\Carbon::parse($modalidade->fimSubmissao))
																		@if ($modalidade->arquivo == true)
																			@if(isset($modalidade->regra))
																				<div style="margin-top: 20px; margin-bottom: 10px;">
																					<a href="{{route('modalidade.regras.download', ['id' => $modalidade->id])}}" target="_new" style="font-size: 14px; color: #114048ff; text-decoration: none;" >
																						<img class="" src="{{asset('img/icons/file-download-solid.svg')}}" style="width:20px">&nbsp;{{$evento->formEvento->etiquetabaixarregra}}
																					</a>
																				</div>
																			@endif
																			@if (isset($modalidade->template))
																				<div style="margin-top: 20px; margin-bottom: 10px;">
																					<a href="{{route('modalidade.template.download', ['id' => $modalidade->id])}}" target="_new" style="font-size: 14px; color: #114048ff; text-decoration: none;" >
																						<img class="" src="{{asset('img/icons/file-download-solid.svg')}}" style="width:20px">&nbsp;{{$evento->formEvento->etiquetabaixartemplate}}
																					</a>
																				</div>
																			@endif
																		@else
																			@if(isset($modalidade->regra))
																				<div style="margin-top: 20px; margin-bottom: 10px;">
																						<a href="{{route('modalidade.regras.download', ['id' => $modalidade->id])}}" target="_new" style="font-size: 14px; color: #114048ff; text-decoration: none;" >
																								<img class="" src="{{asset('img/icons/file-download-solid.svg')}}" style="width:20px">&nbsp;{{$evento->formEvento->etiquetabaixarregra}}
																						</a>
																				</div>
																			@endif
																		@endif
																		<div class="col-md-12 botao-form-left" style="">
																			<a class="btn button-card-visualizar-evento white-color" href="{{route('trabalho.index',['id'=>$evento->id, 'idModalidade' => $modalidade->id])}}">SUBMETER TRABALHO</a>
																		</div>
																	@endif
																@endif
															</div>
															<hr>
														</div>
													</div>
                        @endforeach
											</div>
                  </div>
                @endif
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            {{-- Modulo de inscrição --}}
            @if ($etiquetas->modinscricao == true)
              <div class="row margin">
                <div class="col-sm-12 info-evento">
                    <h5>{{$etiquetas->etiquetamoduloinscricao}}:</h5>
                    <p>
                        @if ($isInscrito)
                          Você já está inscrito nesse evento.
                        @else
                          <a class="btn btn-primary" href="{{route('inscricao.create', ['id' => $evento->id])}}">Realizar inscrição</a>
                        @endif
                        {{-- <a class="btn btn-primary" href="{{route('inscricao.create', ['id' => $evento->id])}}">Realizar inscrição</a> --}}
                    </p>
                </div>
              </div>
            @endif
            {{-- Modulo Organização --}}
            @if ($etiquetas->modorganizacao == true)
              <div class="row margin">
                <div class="col-sm-12 info-evento">
                    <h5>{{$etiquetas->etiquetamoduloorganizacao}}:</h5>
                    <p>
                        LOCAL DA ORGANIZAÇÃO
                    </p>
                </div>
              </div>
            @endif
          </div>
        </div>

        <div class="row">
          {{-- Modulo Programação --}}
          @if ($etiquetas->modprogramacao == true)
            <div class="col-sm-12 info-evento">
                <h5>{{$etiquetas->etiquetamoduloprogramacao}}</h5>
                <p>
                    {{-- LOCAL DA PROGRAMAÇÃO --}}
                    @if (!($evento->exibir_calendario_programacao) && $etiquetas->modprogramacao == true && $evento->pdf_programacao != null)
                      <iframe src="{{asset('storage/' . $evento->pdf_programacao)}}" width="100%" height="500" style="border: none;"></iframe>
                    @elseif ($evento->exibir_calendario_programacao && $etiquetas->modprogramacao == true)
                      @if ($atividades != null && count($atividades) > 0)
                        <div id="wrap">
                          <div id='calendar-wrap' style="width: 100%;">
                            <div id='calendar'></div>
                          </div>
                        </div>
                      @else
                        Nenhuma atividade programada
                      @endif
                    @else
                        Nenhuma atividade programada
                    @endif
                </p>
            </div>
          @endif
        </div>

        {{-- @if($hasFile == true)
          <div class="row margin">
              <div class="col-sm-12">
                  <h1>
                      Meus Trabalhos
                  </h1>
              </div>
          </div>
          @if($hasTrabalho)
            <div class="row margin">
                <div class="col-sm-12 info-evento">
                    <h4>Como Autor</h4>
                </div>
            </div>

            <!-- Tabela de trabalhos -->

            <div class="row justify-content-center">
              <div class="col-sm-12">

                <table class="table table-responsive-lg table-hover">
                  <thead>
                    <tr>
                      <th>Título</th>
                      <th style="text-align:center">Baixar</th>
                      <th style="text-align:center">Nova Versão</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($trabalhos as $trabalho)
                      <tr>
                        <td>{{$trabalho->titulo}}</td>
                        <td style="text-align:center">
                          @php $arquivo = ""; @endphp
                          @foreach($trabalho->arquivo as $key)
                            @php
                              if($key->versaoFinal == true){
                                $arquivo = $key->nome;
                              }
                            @endphp
                          @endforeach
                          <a href="{{route('download', ['file' => $arquivo])}}" target="_new" style="font-size: 20px; color: #114048ff;" >
                              <img class="" src="{{asset('img/icons/file-download-solid.svg')}}" style="width:20px">
                          </a>
                        </td>
                        <td style="text-align:center">
                          @if($evento->inicioSubmissao <= $mytime)
                            @if($mytime < $evento->fimSubmissao)
                              <a href="#" onclick="changeTrabalho({{$trabalho->id}})" data-toggle="modal" data-target="#modalTrabalho" style="color:#114048ff">
                                <img class="" src="{{asset('img/icons/file-upload-solid.svg')}}" style="width:20px">
                              </a>
                            @endif
                          @endif
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          @endif

          @if($hasTrabalhoCoautor)
            <div class="row margin">
                <div class="col-sm-12 info-evento">
                    <h4>Como Coautor</h4>
                </div>
            </div>

            <div class="row justify-content-center">
              <div class="col-sm-12">

                <table class="table table-responsive-lg table-hover">
                  <thead>
                    <tr>
                      <th>Título</th>
                      <th  style="text-align:center">Baixar</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($trabalhosCoautor as $trabalho)
                      <tr>
                        <td>{{$trabalho->titulo}}</td>
                        <td style="text-align:center">
                          @php $arquivo = ""; @endphp
                          @foreach($trabalho->arquivo as $key)
                            @php
                              if($key->versaoFinal == true){
                                $arquivo = $key->nome;
                              }
                            @endphp
                          @endforeach
                          <a href="{{route('download', ['file' => $arquivo])}}" target="_new" style="font-size: 20px; color: #114048ff;" >
                              <img class="" src="{{asset('img/icons/file-download-solid.svg')}}" style="width:20px">
                          </a>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          @endif
        @endif --}}

        {{-- <div class="row justify-content-center" style="margin: 20px 0 20px 0"> --}}

            {{-- <div class="col-md-6 botao-form-left" style="">
                <a class="btn btn-secondary botao-form" href="{{route('cancelarCadastro')}}" style="width:100%">Voltar</a>
            </div> --}}

            {{-- @if($evento->inicioSubmissao <= $mytime)
              @if($mytime < $evento->fimSubmissao)
                <div class="col-md-6 botao-form-right" style="">
                  <a class="btn btn-primary botao-form" href="{{route('trabalho.index',['id'=>$evento->id])}}" style="width:100%">Submeter Trabalho</a>
                </div>
              @endif
            @endif --}}

        {{-- </div> --}}
      </div>
    </div>
  </div>
</div>

@include('componentes.footer')

@endsection

@section('javascript')
<script>
  var botoes = document.getElementsByClassName('cor-aleatoria');
  for (var i = 0; i < botoes.length; i++) {
    botoes[i].style.backgroundColor = '#'+Math.floor(Math.random()*16777215).toString(16);
  }

  function changeTrabalho(x){
    document.getElementById('trabalhoNovaVersaoId').value = x;
  }
</script>
  @if ($dataInicial != "" && $evento->exibir_calendario_programacao)
    <script>
      document.addEventListener('DOMContentLoaded', function() {

      /* initialize the external events
      -----------------------------------------------------------------*/

      // var containerEl = document.getElementById('external-events-list');
      // new FullCalendar.Draggable(containerEl, {
      //   itemSelector: '.fc-event',
      //   eventData: function(eventEl) {
      //     return {
      //       title: eventEl.innerText.trim()
      //     }
      //   }
      // });

      //// the individual way to do it
      // var containerEl = document.getElementById('external-events-list');
      // var eventEls = Array.prototype.slice.call(
      //   containerEl.querySelectorAll('.fc-event')
      // );
      // eventEls.forEach(function(eventEl) {
      //   new FullCalendar.Draggable(eventEl, {
      //     eventData: {
      //       title: eventEl.innerText.trim(),
      //     }
      //   });
      // });

      /* initialize the calendar
      -----------------------------------------------------------------*/

      var calendarEl = document.getElementById('calendar');
      var calendar = new FullCalendar.Calendar(calendarEl, {
        initialDate: "{{$dataInicial->data}}",
        headerToolbar: {
          left: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek',
          center: 'title',
          right: 'prev,next today'
        },
        locale: 'pt-br',
        editable: false,
        eventClick: function (info) {
          var idModal = "#modalAtividadeShow"+info.event.id;
          $(idModal).modal('show');
        },
        events: "{{ route('atividades.json', ['id' => $evento->id]) }}",

      });
      calendar.render();
      });
    </script>
  @endif
@endsection
