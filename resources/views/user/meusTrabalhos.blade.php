@extends('layouts.app')

@section('content')



@foreach ($trabalhos as $trabalho)
  <div class="modal fade" id="modalTrabalho_{{$trabalho->id}}" tabindex="-1" role="dialog" aria-labelledby="modalTrabalho" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #114048ff; color: white;">
          <h5 class="modal-title" id="exampleModalCenterTitle">Submeter nova versão</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="POST" action="{{route('trabalho.novaVersao')}}" enctype="multipart/form-data">
          @csrf
        <div class="modal-body">

          <div class="row justify-content-center">
            <div class="col-sm-12">
                @error('error')
                  <div class="alert alert-danger">
                    <p>{{$message}}</p>
                  </div>
                @enderror
                @error('tipoExtensao')
                <div class="alert alert-danger">
                  <p>{{$message}}</p>
                </div>
                @enderror
                <input type="hidden" name="trabalhoId" value="{{$trabalho->id}}" id="trabalhoNovaVersaoId">

                {{-- Arquivo  --}}
                <label for="nomeTrabalho" class="col-form-label">{{ __('Novo arquivo para ') }}{{$trabalho->titulo}}</label>

                <div class="custom-file">
                  <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="arquivo">
                </div>

                <small>Arquivos aceitos nos formatos
                  @if($trabalho->modalidade->pdf == true)<span> - pdf</span>@endif
                  @if($trabalho->modalidade->jpg == true)<span> - jpg</span>@endif
                  @if($trabalho->modalidade->jpeg == true)<span> - jpeg</span>@endif
                  @if($trabalho->modalidade->png == true)<span> - png</span>@endif
                  @if($trabalho->modalidade->docx == true)<span> - docx</span>@endif
                  @if($trabalho->modalidade->odt == true)<span> - odt</span>@endif
                  @if($trabalho->modalidade->zip == true)<span> - zip</span>@endif
                  @if($trabalho->modalidade->svg == true)<span> - svg</span>@endif.
                </small>
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
@endforeach

<div class="container content">
    {{-- titulo da página --}}
    <div class="row justify-content-center titulo-detalhes">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-12">
                    <h1>Meus Trabalhos</h1>
                </div>

            </div>
        </div>
    </div>
    <br>
    @if(session('mensagem'))
        <div class="col-md-12" style="margin-top: 5px;">
            <div class="alert alert-success">
              <p>{{session('mensagem')}}</p>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-sm-12">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
    <div class="row margin">
        <div class="col-sm-12 info-evento">
            <h4>Como Autor</h4>
        </div>
    </div>

    <!-- Tabela de trabalhos -->

    <div class="row justify-content-center">
        <div class="col-sm-12">

        @if (count($trabalhos) > 0)
          <table class="table table-responsive-lg table-hover">
              <thead>
              <tr>
                  <th>Evento</th>
                  <th>ID</th>
                  <th>Título</th>
                  <th>Apresentação</th>
                  <th style="text-align:center">Coautores</th>
                  <th style="text-align:center">Baixar</th>
                  <th style="text-align:center">Editar</th>
                  <th style="text-align:center">Excluir</th>
                  <th style="text-align:center">Pareceres</th>
                  <th style="text-align:center">Correção</th>
                  {{-- <th style="text-align:center">Arquivar</th> --}}
              </tr>
              </thead>
              <tbody>
              @foreach($trabalhos as $trabalho)
                  <tr>
                  <td>{{$trabalho->evento->nome}}</td>
                  <td>{{$trabalho->id}}</td>
                  <td>{{$trabalho->titulo}}</td>
                  <td>{{$trabalho->tipo_apresentacao}}</td>
                  <td style="text-align:center">
                    <a data-toggle="modal" data-target="#modalCoautoresTrabalho_{{$trabalho->id}}" style="cursor: pointer;">
                      <img src="{{asset('img/icons/eye-regular.svg')}}" style="width:20px">
                    </a>
                  </td>
                  <td style="text-align:center">
                    @if($trabalho->arquivo()->where('versaoFinal', true)->first() != null && Storage::disk()->exists($trabalho->arquivo()->where('versaoFinal', true)->first()->nome))
                      <a href="{{route('downloadTrabalho', ['id' => $trabalho->id])}}" target="_new" style="font-size: 20px; color: #114048ff;" >
                          <img class="" src="{{asset('img/icons/file-download-solid.svg')}}" style="width:20px">
                      </a>
                    @else
                        <a href="#" onclick="return false;" id="download-{{$trabalho->id}}" data-trigger="focus" data-toggle="popover" title="Download não disponível" data-content="Não foi enviado arquivo para este trabalho" style="font-size: 20px; color: #114048ff;" >
                            <img class="" src="{{asset('img/icons/file-download-solid.svg')}}" style="width:20px">
                        </a>
                    @endif
                  </td>
                  <td style="text-align:center">
                      <a href="#" onclick="return false;" @if($agora <= $trabalho->modalidade->fimSubmissao) data-toggle="modal" data-target="#modalEditarTrabalho_{{$trabalho->id}}" style="color:#114048ff" @else data-toggle="popover" data-trigger="focus" data-placement="bottom" title="Não permitido" data-content="A edição do trabalho só é permitida durante o periodo de submissão." @endif>
                        <img class="" src="{{asset('img/icons/edit-regular.svg')}}" style="width:20px">
                      </a>
                  </td>
                  <td style="text-align:center">
                    <a href="#" onclick="return false;" @if($agora <= $trabalho->modalidade->fimSubmissao) data-toggle="modal" data-target="#modalExcluirTrabalho_{{$trabalho->id}}" style="color:#114048ff" @else data-toggle="popover" data-trigger="focus" data-placement="bottom" title="Não permitido" data-content="A exclusão do trabalho só é permitida durante o periodo de submissão." @endif>
                      <img class="" src="{{asset('img/icons/trash-alt-regular.svg')}}" style="width:20px">
                    </a>
                  </td>

                <td style="text-align:center">
                    @if($trabalho->atribuicoes->count() == 1)
                      {{--Aqui será tratado as revisoes anteriores onde so houve a avalicao de um revisor
                          Nesses casos o trabalho->status foi setado como avaliado quando encaminhado, entao
                          deixaremos seguir dessa forma caso o trabalho tenha tido uma atribuicao--}}
                      @foreach ($trabalho->atribuicoes as $revisor)
                          @if($trabalho->avaliado($revisor->user))
                            <a @if ($trabalho->status == 'avaliado' || $trabalho->getParecerAtribuicao($revisor->user) == "encaminhado") href="{{route('user.visualizarParecer', ['eventoId' => $trabalho->evento->id, 'modalidadeId' => $trabalho->modalidadeId, 'trabalhoId' => $trabalho->id, 'revisorId' => $revisor->id,'id' => $trabalho->id])}}" @else href="#" onclick="return false;" data-toggle="popover" data-trigger="focus" data-placement="bottom" title="Não disponível" data-content="O parecer do trabalho estará disponível assim que revisado." @endif>
                                <img src="{{asset('img/icons/eye-regular.svg')}}" style="width:20px">
                            </a>
                            <br>
                          @endif
                      @endforeach
                    @elseif($trabalho->atribuicoes->count() > 1)
                      @foreach ($trabalho->atribuicoes as $revisor)
                        @if($trabalho->avaliado($revisor->user))
                          <a @if ($trabalho->getParecerAtribuicao($revisor->user) == "encaminhado") href="{{route('user.visualizarParecer', ['eventoId' => $trabalho->evento->id, 'modalidadeId' => $trabalho->modalidadeId, 'trabalhoId' => $trabalho->id, 'revisorId' => $revisor->id,'id' => $trabalho->id])}}" @else href="#" onclick="return false;" data-toggle="popover" data-trigger="focus" data-placement="bottom" title="Não disponível" data-content="O parecer do trabalho estará disponível assim que revisado." @endif>
                              <img src="{{asset('img/icons/eye-regular.svg')}}" style="width:20px">
                          </a>
                          <br>
                        @endif
                      @endforeach
                    @else
                      <a href="#" onclick="return false;" data-toggle="popover" data-trigger="focus" data-placement="bottom" title="Não disponível" data-content="O parecer do trabalho estará disponível assim que revisado.">
                          <img src="{{asset('img/icons/eye-regular.svg')}}" style="width:20px">
                      </a>
                    @endif

                    @if ($trabalho->pareceres->where('parecer_final', true)->count() > 0)
                        <a href="#" onclick="return false;" data-toggle="modal" data-target="#modalparecerfinal{{$trabalho->id}}">
                            <img src="{{asset('img/icons/eye-regular.svg')}}" style="width:20px" title="Parecer final">
                        </a>
                        <div class="modal fade" id="modalparecerfinal{{$trabalho->id}}" tabindex="-1"
                            aria-labelledby="modalparecerfinal{{$trabalho->id}}Label" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header" style="background-color: #114048ff; color: white;">
                                        <h5>Parecer final</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    @php
                                        $parecer = $trabalho->pareceres->where('parecer_final', true)->first();
                                    @endphp
                                    <div class="modal-body">
                                        <p class="text-left">
                                            Resultado: {{$parecer->resultado == 'positivo' ? 'Aprovado' : 'Reprovado'}}
                                        </p>
                                        <p class="text-left">
                                            {{$parecer->justificativa}}
                                        </p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </td>

                <td style="text-align:center">
                    {{--Desabilitando temporariamente a restricao de aprovacao para correcao--}}
                    {{--@if($trabalho->aprovado !== false)--}}
                    @if($trabalho->modalidade->inicioCorrecao > date(01-01-2021))
                        <a href="#" @if($trabalho->modalidade->inicioCorrecao <= $agora && $agora <= $trabalho->modalidade->fimCorrecao) data-toggle="modal" data-target="#modalCorrecaoTrabalho_{{$trabalho->id}}" style="color:#114048ff" @else onclick="return false;" data-toggle="popover" data-trigger="focus" data-placement="bottom" title="Não permitido" data-content="A correção do trabalho só é permitida durante o período de correção. De {{date('d/m/Y H:i', strtotime($trabalho->modalidade->inicioCorrecao))}} a {{date('d/m/Y H:i', strtotime($trabalho->modalidade->fimCorrecao))}}" @endif>
                            <img class="" src="{{asset('img/icons/file-upload-solid.svg')}}" style="width:20px">
                        </a>
                    @else
                        <a href="#" @if($trabalho->modalidade->inicioCorrecao <= $agora && $agora <= $trabalho->modalidade->fimCorrecao) data-toggle="modal" data-target="#modalCorrecaoTrabalho_{{$trabalho->id}}" style="color:#114048ff" @else onclick="return false;" data-toggle="popover" data-trigger="focus" data-placement="bottom" title="Não permitido" data-content="A correção não está habilitada para este trabalho." @endif>
                            <img class="" src="{{asset('img/icons/file-upload-solid.svg')}}" style="width:20px">
                        </a>
                    @endif
                    {{--@else
                        <a data-toggle="popover" data-placement="bottom" title="Não permitido" data-content="A correção não está disponível para o seu trabalho.">
                            <img class="" src="{{asset('img/icons/file-upload-solid.svg')}}" style="width:20px">
                        </a>
                    @endif--}}
                </td>

                  {{-- <td style="text-align:center">
                    <form action="{{ route('trabalho.arquivar') }}" method="post">
                        @csrf
                        <input type="hidden" name="trabalho_id" value="{{ $trabalho->id }}">
                        <button type="submit" class="btn btn-warning">Arquivar</button>
                    </form>
                  </td> --}}
                  </tr>
              @endforeach
              </tbody>
          </table>
        @else
          Você não submeteu nenhum trabalho...
        @endif
        </div>
    </div>

    <br>

    <div class="row margin">
        <div class="col-sm-12 info-evento">
            <h4>Como Coautor</h4>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-sm-12">

        @if ($trabalhosCoautor != null && count($trabalhosCoautor) > 0)
          <table class="table table-responsive-lg table-hover">
              <thead>
              <tr>
                  <th>Evento</th>
                  <th>Título</th>
                  <th>Autor</th>
                  <th style="text-align:center">Baixar</th>
              </tr>
              </thead>
              <tbody>
              @foreach($trabalhosCoautor as $trabalho)
                  <tr>
                    <td>{{$trabalho->evento->nome}}</td>
                    <td>{{$trabalho->titulo}}</td>
                    <td>{{$trabalho->autor->name}}</td>
                    <td style="text-align:center">
                    @if($trabalho->arquivo()->where('versaoFinal', true)->first() != null && Storage::disk()->exists($trabalho->arquivo()->where('versaoFinal', true)->first()->nome))
                        <a href="{{route('downloadTrabalho', ['id' => $trabalho->id])}}" target="_new" style="font-size: 20px; color: #114048ff;" >
                            <img class="" src="{{asset('img/icons/file-download-solid.svg')}}" style="width:20px">
                        </a>
                    @else
                        <a href="#" onclick="return false;" data-toggle="popover" data-trigger="focus" data-trigger="focus" title="Download não disponível" data-content="Não foi enviado arquivo para este trabalho" style="font-size: 20px; color: #114048ff;" >
                            <img class="" src="{{asset('img/icons/file-download-solid.svg')}}" style="width:20px">
                        </a>
                    @endif
                    </td>
                  </tr>
              @endforeach
              </tbody>
          </table>
        @else
          Você não participa como coautor em nenhum trabalho...
        @endif
        </div>
    </div>

</div>


@foreach ($trabalhos as $trabalho)
  @if($agora <= $trabalho->modalidade->fimSubmissao)
    <!-- Modal  excluir trabalho -->
    <div class="modal fade" id="modalExcluirTrabalho_{{$trabalho->id}}" tabindex="-1" aria-labelledby="modalExcluirTrabalho_{{$trabalho->id}}Label" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background-color: #114048ff; color: white;">
            <h5 class="modal-title" id="modalExcluirTrabalho_{{$trabalho->id}}Label">Excluir {{$trabalho->id}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="formExcluirTrab{{$trabalho->id}}" action="{{route('excluir.trabalho', ['id' => $trabalho->id])}}" method="POST">
              @csrf
              <p>
                Tem certeza que deseja excluir esse trabalho?
              </p>
              <small>Ninguém poderá ver seu trabalho após a exclusão.</small>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
            <button type="submit" class="btn btn-primary" form="formExcluirTrab{{$trabalho->id}}">Sim</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Fim Modal excluir trabalho -->
    <!-- Modal  editar trabalho -->
    <div class="modal fade" id="modalEditarTrabalho_{{$trabalho->id}}" tabindex="-1" aria-labelledby="modalEditarTrabalho_{{$trabalho->id}}Label" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background-color: #114048ff; color: white;">
            <h5 class="modal-title" id="modalEditarTrabalho_{{$trabalho->id}}Label">Editar {{$trabalho->id}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="formEditarTrab{{$trabalho->id}}" action="{{route('editar.trabalho', ['id' => $trabalho->id])}}" method="POST" enctype="multipart/form-data">
              @csrf
              <input type="hidden" name="modalidade{{$trabalho->id}}" value="{{$trabalho->modalidade->id}}">
              @php
                $formSubTraba = $trabalho->evento->formSubTrab;
                $ordem = explode(",", $formSubTraba->ordemCampos);
                array_splice($ordem, 6, 0, "midiaExtra");
                array_splice( $ordem, 5, 0, "apresentacao");
                $modalidade = $trabalho->modalidade;
                $areas = $trabalho->evento->areas;
              @endphp
              <input type="hidden" name="trabalhoEditId" value="{{$trabalho->id}}">
              @error('numeroMax'.$trabalho->id)
                <div class="row">
                  <div class="col-md-12">
                    <div class="alert alert-danger" role="alert">
                      {{ $message }}
                    </div>
                  </div>
                </div>
              @enderror
              @foreach ($ordem as $indice)
                @if ($indice == "etiquetatitulotrabalho")
                  <div class="row justify-content-center">
                    {{-- Nome Trabalho  --}}
                    <div class="col-sm-12">
                        <label for="nomeTrabalho_{{$trabalho->id}}" class="col-form-label">{{ $formSubTraba->etiquetatitulotrabalho }}</label>
                        <input id="nomeTrabalho_{{$trabalho->id}}" type="text" class="form-control @error('nomeTrabalho'.$trabalho->id) is-invalid @enderror" name="nomeTrabalho{{$trabalho->id}}" value="{{old('nomeTrabalho'.$trabalho->id, $trabalho->titulo)}}" required autocomplete="nomeTrabalho" autofocus>

                        @error('nomeTrabalho'.$trabalho->id)
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                  </div>
                @endif
                @if ($indice == "etiquetacoautortrabalho")
                    <div class="flexContainer" style="margin-top:20px">
                        <div class="row">
                            <div class="col">
                                <h4>Autor(a)</h4>
                            </div>
                            <div class="col mr-5">
                                <div class="float-right">
                                    <a href="#" style="color: #196572ff;text-decoration: none;" title="Clique aqui para adicionar coautor(es), se houver" onclick="montarLinhaInput(this, {{$trabalho->id}}, event)" id="addCoautor_{{$trabalho->id}}">
                                        <i class="fas fa-user-plus fa-2x"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div id="coautores{{$trabalho->id}}" class="flexContainer " >
                            @if (old('nomeCoautor_'.$trabalho->id) != null)
                                @foreach (old('nomeCoautor_'.$trabalho->id) as $i => $nomeCoautor)
                                    <div class="item card">
                                        <div class="row card-body">
                                            <div class="col-sm-4">
                                                <label>E-mail</label>
                                                <input type="email" style="margin-bottom:10px" value="{{old('emailCoautor_'.$trabalho->id)[$i]}}" class="form-control emailCoautor" name="emailCoautor_{{$trabalho->id}}[]" placeholder="E-mail">
                                            </div>
                                            <div class="col-sm-5">
                                                <label>Nome Completo</label>
                                                <input type="text" style="margin-bottom:10px" value="{{$nomeCoautor}}" class="form-control emailCoautor" name="nomeCoautor_{{$trabalho->id}}[]" placeholder="Nome">
                                            </div>
                                            <div class="col-sm-3">
                                                <a style="color: #d30909;" href="#" onclick="deletarCoautor(this, {{$trabalho->id}}, event)" class="delete pr-2">
                                                    <i class="fas fa-user-times fa-2x"></i>
                                                </a>
                                                <a href="#" onclick="mover(this.parentElement.parentElement.parentElement, 1, {{$trabalho->id}}, event)">
                                                    <i class="fas fa-arrow-up fa-2x" id="arrow-up" style=""></i>
                                                </a>
                                                <a href="#" onclick="mover(this.parentElement.parentElement.parentElement, 0, {{$trabalho->id}}, event)">
                                                    <i class="fas fa-arrow-down fa-2x" id="arrow-down" style="margin-top:35px"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="item card">
                                    <div class="row card-body">
                                        <div class="col-sm-4">
                                            <label>E-mail</label>
                                            <input type="email" style="margin-bottom:10px" value="{{$trabalho->autor->email}}" oninput="buscarEmail(this)" class="form-control emailCoautor" name="emailCoautor_{{$trabalho->id}}[]" placeholder="E-mail" required>
                                        </div>
                                        <div class="col-sm-5">
                                            <label>Nome Completo</label>
                                            <input type="text" style="margin-bottom:10px" value="{{$trabalho->autor->name}}" class="form-control emailCoautor" name="nomeCoautor_{{$trabalho->id}}[]" placeholder="Nome" required>
                                        </div>
                                        <div class="col-sm-3">
                                            <a style="color: #d30909;" href="#" onclick="deletarCoautor(this, {{$trabalho->id}}, event)" class="delete pr-2">
                                                <i class="fas fa-user-times fa-2x"></i>
                                            </a>
                                            <a href="#" onclick="mover(this.parentElement.parentElement.parentElement, 1, {{$trabalho->id}}, event)">
                                                <i class="fas fa-arrow-up fa-2x" id="arrow-up" style=""></i>
                                            </a>
                                            <a href="#" onclick="mover(this.parentElement.parentElement.parentElement, 0, {{$trabalho->id}}, event)">
                                                <i class="fas fa-arrow-down fa-2x" id="arrow-down" style="margin-top:35px"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @if(! $trabalho->coautors->isEmpty())
                                    <h4 id="title-coautores{{$trabalho->id}}" style="margin-top:20px">Coautor(es)</h4>
                                @endif
                                @foreach ($trabalho->coautors as $i => $coautor)
                                <div class="item card">
                                    <div class="row card-body">
                                        <div class="col-sm-4">
                                            <label>E-mail</label>
                                            <input type="email" style="margin-bottom:10px" value="{{$coautor->user->email}}" oninput="buscarEmail(this)" class="form-control emailCoautor" name="emailCoautor_{{$trabalho->id}}[]" placeholder="E-mail" required>
                                        </div>
                                        <div class="col-sm-5">
                                            <label>Nome Completo</label>
                                            <input type="text" style="margin-bottom:10px" value="{{$coautor->user->name}}" class="form-control emailCoautor" name="nomeCoautor_{{$trabalho->id}}[]" placeholder="Nome" required>
                                        </div>
                                        <div class="col-sm-3">
                                            <a h style="color: #d30909;" href="#" onclick="deletarCoautor(this, {{$trabalho->id}}, event)" class="delete pr-2">
                                            <i class="fas fa-user-times fa-2x"></i>
                                            </a>
                                            <a href="#" onclick="mover(this.parentElement.parentElement.parentElement, 1, {{$trabalho->id}}, event)">
                                            <i class="fas fa-arrow-up fa-2x" id="arrow-up" style=""></i>
                                            </a>
                                            <a href="#" onclick="mover(this.parentElement.parentElement.parentElement, 0, {{$trabalho->id}}, event)">
                                            <i class="fas fa-arrow-down fa-2x" id="arrow-down" style="margin-top:35px"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endif
                @if ($modalidade->texto && $indice == "etiquetaresumotrabalho")
                  @if ($modalidade->caracteres == true)
                    <div class="row justify-content-center">
                      <div class="col-sm-12">
                          <label for="resumo_{{$trabalho->id}}" class="col-form-label">{{$formSubTraba->etiquetaresumotrabalho}}</label>
                          <textarea id="resumo_{{$trabalho->id}}" class="char-count form-control @error('resumo'.$trabalho->id) is-invalid @enderror" data-ls-module="charCounter" minlength="{{$modalidade->mincaracteres}}" maxlength="{{$modalidade->maxcaracteres}}" name="resumo{{$trabalho->id}}"  autocomplete="resumo" autofocusrows="5">{{old('resumo'.$trabalho->id, $trabalho->resumo)}}</textarea>
                          <p class="text-muted"><small><span id="resumo{{$trabalho->id}}">{{strlen($trabalho->resumo)}}</span></small> - Min Caracteres: {{$modalidade->mincaracteres}} - Max Caracteres: {{$modalidade->maxcaracteres}}</p>
                          @error('resumo'.$trabalho->id)
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                          @enderror

                      </div>
                    </div>
                  @elseif ($modalidade->palavras == true)
                    <div class="row justify-content-center">
                      <div class="col-sm-12">
                          <label for="resumo_{{$trabalho->id}}" class="col-form-label">{{$formSubTraba->etiquetaresumotrabalho}}</label>
                          <textarea id="resumo_{{$trabalho->id}}" class="form-control palavra @error('resumo'.$trabalho->id) is-invalid @enderror" name="resumo{{$trabalho->id}}" autocomplete="resumo" autofocusrows="5">{{old('resumo'.$trabalho->id, $trabalho->resumo)}}</textarea>
                          <p class="text-muted"><small><span id="resumo{{$trabalho->id}}">{{count(explode(" ", $trabalho->resumo))}}</span></small> - Min Palavras: {{$modalidade->minpalavras}} - Max Palavras: {{$modalidade->maxpalavras}}</p>
                          @error('resumo'.$trabalho->id)
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                          @enderror

                      </div>
                    </div>
                  @endif
                @endif
                @if ($indice == "etiquetaareatrabalho")
                  <!-- Areas -->
                  <div class="row justify-content-center">
                      <div class="col-sm-12">
                          <label for="area_{{$trabalho->id}}" class="col-form-label">{{$formSubTraba->etiquetaareatrabalho}}</label>
                          <select id="area_{{$trabalho->id}}" class="form-control @error('area'.$trabalho->id) is-invalid @enderror" name="area{{$trabalho->id}}" required>
                              <option value="" disabled selected hidden>-- Área --</option>
                              {{-- Apenas um teste abaixo --}}
                              @if (old('area'.$trabalho->id) != null)
                                @foreach($areas as $area)
                                  <option value="{{$area->id}}" @if(old('area'.$trabalho->id) == $area->id) selected @endif>{{$area->nome}}</option>
                                @endforeach
                              @else
                                @foreach($areas as $area)
                                  <option value="{{$area->id}}" @if($trabalho->areaId == $area->id) selected @endif>{{$area->nome}}</option>
                                @endforeach
                              @endif

                          </select>
                          @error('area'.$trabalho->id)
                          <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                            <strong>{{ $message }}</strong>
                          </span>
                          @enderror
                      </div>
                  </div>
                @endif
                @if ($indice == "apresentacao")
                    @if ($trabalho->modalidade->apresentacao)
                        <div class="row justify-content-center mt-4">
                            <div class="col-sm-12">
                                <label for="area"
                                    class="col-form-label"><strong>{{ __('Forma de apresentação do trabalho') }}</strong>
                                </label>
                                <select name="tipo_apresentacao" id="tipo_apresentacao" class="form-control @error('tipo_apresentacao') is-invalid @enderror" required>
                                    <option value="" selected disabled>{{__('-- Selecione a forma de apresentação do trabalho --')}}</option>
                                    @foreach ($trabalho->modalidade->tiposApresentacao as $tipo)
                                    <option @if(old('tipo_apresentacao') == $tipo->tipo || $trabalho->tipo_apresentacao == $tipo->tipo) selected @endif value="{{$tipo->tipo}}">{{__($tipo->tipo)}}</option>
                                    @endforeach
                                </select>

                                @error('tipo_apresentacao')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    @endif
                @endif
                @if ($indice == "midiaExtra")
                    <div class="row justify-content-center">
                        @foreach ($modalidade->midiasExtra as $midia)
                            <div class="col-sm-12" style="margin-top: 20px;">
                                <label for="{{$midia->nome}}"
                                    class="col-form-label"><strong>{{$midia->nome}}</strong>
                                </label>
                                <a href="{{route('downloadMidiaExtra', ['id' => $trabalho->id, 'id_midia' => $midia->id])}}">Arquivo atual</a>
                                <br>
                                <small>Para trocar o arquivo envie um novo.</small>
                                <div class="custom-file">
                                    <input type="file" class="filestyle"
                                        data-placeholder="Nenhum arquivo" data-text="Selecionar"
                                        data-btnClass="btn-primary-lmts" name="{{$midia->nome}}">
                                </div>
                                <small><strong>Extensão de arquivos aceitas:</strong>
                                    @if($midia->pdf == true)
                                        <span> / ".pdf"</span>
                                    @endif
                                    @if($midia->jpg == true)
                                        <span> / ".jpg"</span>
                                    @endif
                                    @if($midia->jpeg == true)
                                        <span> / ".jpeg"</span>
                                    @endif
                                    @if($midia->png == true)
                                        <span> / ".png"</span>
                                    @endif
                                    @if($midia->docx == true)
                                        <span> / ".docx"</span>
                                    @endif
                                    @if($midia->odt == true)
                                        <span> / ".odt"</span>
                                    @endif
                                    @if($midia->zip == true)
                                        <span> / ".zip"</span>
                                    @endif
                                    @if($midia->svg == true)
                                        <span> / ".svg"</span>
                                    @endif
                                    @if($midia->mp4 == true)
                                        <span> / ".mp4"</span>
                                    @endif
                                    @if($midia->mp3 == true)
                                        <span> / ".mp3"</span>
                                    @endif. </small>
                                @error($midia->nome)
                                <span class="invalid-feedback" role="alert"
                                    style="overflow: visible; display:block">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                @endif
                @if ($indice == "etiquetauploadtrabalho")
                  <div class="row justify-content-center">
                    {{-- Submeter trabalho --}}

                    @if ($modalidade->arquivo == true)
                      <div class="col-sm-12" style="margin-top: 20px;">
                        <label for="nomeTrabalho" class="col-form-label">{{$formSubTraba->etiquetauploadtrabalho}}:</label>
                          <a href="{{route('downloadTrabalho', ['id' => $trabalho->id])}}">Arquivo atual</a>
                        <br>
                        <small>Para trocar o arquivo envie um novo.</small>
                        <div class="custom-file">
                          <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="arquivo{{$trabalho->id}}">
                        </div>
                        <small>Arquivos aceitos nos formatos
                            @if($trabalho->modalidade->pdf == true)<span> - pdf</span>@endif
                            @if($trabalho->modalidade->jpg == true)<span> - jpg</span>@endif
                            @if($trabalho->modalidade->jpeg == true)<span> - jpeg</span>@endif
                            @if($trabalho->modalidade->png == true)<span> - png</span>@endif
                            @if($trabalho->modalidade->docx == true)<span> - docx</span>@endif
                            @if($trabalho->modalidade->odt == true)<span> - odt</span>@endif
                            @if($trabalho->modalidade->zip == true)<span> - zip</span>@endif
                            @if($trabalho->modalidade->svg == true)<span> - svg</span>@endif
                            @if($trabalho->modalidade->mp4 == true)<span> - mp4</span>@endif
                            @if($trabalho->modalidade->mp3 == true)<span> - mp3</span>@endif.
                        </small>
                        @error('arquivo'.$trabalho->id)
                          <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                      </div>
                    @endif
                  </div>
                @endif
                @if ($indice == "etiquetacampoextra1")
                  @if ($formSubTraba->checkcampoextra1 == true)
                    @if ($formSubTraba->tipocampoextra1 == "textosimples")
                      {{-- Texto Simples --}}
                      <div class="row justify-content-center">
                        {{-- Nome Trabalho  --}}
                        <div class="col-sm-12">
                              <label for="campoextra1simples_{{$trabalho->id}}" class="col-form-label">{{ $formSubTraba->etiquetacampoextra1}}:</label>
                              <input id="campoextra1simples_{{$trabalho->id}}" type="text" class="form-control @error('campoextra1simples') is-invalid @enderror" name="campoextra1simples" value="{{ old('campoextra1simples') }}" required autocomplete="campoextra1simples" autofocus>

                              @error('campoextra1simples')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                              @enderror
                        </div>
                      </div>
                    @elseif ($formSubTraba->tipocampoextra1 == "textogrande")
                      {{-- Texto Grande --}}
                      <div class="row justify-content-center">
                      <div class="col-sm-12">
                            <label for="campoextra1grande" class="col-form-label">{{ $formSubTraba->etiquetacampoextra1}}:</label>
                            <textarea id="campoextra1grande" type="text" class="form-control @error('campoextra1grande') is-invalid @enderror" name="campoextra1grande" value="{{ old('campoextra1grande') }}" required autocomplete="campoextra1grande" autofocus></textarea>

                            @error('campoextra1grande')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                      </div>
                    @elseif ($formSubTraba->tipocampoextra1 == "upload")
                      <div class="col-sm-12" style="margin-top: 20px;">
                        <label for="campoextra1arquivo" class="col-form-label">{{ $formSubTraba->etiquetacampoextra1}}:</label>

                        <div class="custom-file">
                          <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="campoextra1arquivo" required>
                        </div>
                        <small>Algum texto aqui?</small>
                        @error('campoextra1arquivo')
                        <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                          <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                      </div>
                    @endif
                  @endif
                @endif
                @if ($indice == "etiquetacampoextra2")
                  @if ($formSubTraba->checkcampoextra2 == true)
                    @if ($formSubTraba->tipocampoextra2 == "textosimples")
                      <div class="row justify-content-center">
                        {{-- Nome Trabalho  --}}
                      <div class="col-sm-12">
                            <label for="campoextra2simples" class="col-form-label">{{ $formSubTraba->etiquetacampoextra2}}:</label>
                            <input id="campoextra2simples" type="text" class="form-control @error('campoextra2simples') is-invalid @enderror" name="campoextra2simples" value="{{ old('campoextra2simples') }}" required autocomplete="campoextra2simples" autofocus>

                            @error('campoextra2simples')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                      </div>
                    @elseif ($formSubTraba->tipocampoextra2 == "textogrande")
                      <div class="row justify-content-center">
                        {{-- Nome Trabalho  --}}
                      <div class="col-sm-12">
                            <label for="campoextra2grande" class="col-form-label">{{ $formSubTraba->etiquetacampoextra2}}:</label>
                            <textarea id="campoextra2grande" type="text" class="form-control @error('campoextra2grande') is-invalid @enderror" name="campoextra2grande" value="{{ old('campoextra2grande') }}" required autocomplete="campoextra2grande" autofocus></textarea>

                            @error('campoextra2grande')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                      </div>
                    @elseif ($formSubTraba->tipocampoextra2 == "upload")
                      <div class="col-sm-12" style="margin-top: 20px;">
                        <label for="campoextra2arquivo" class="col-form-label">{{ $formSubTraba->etiquetacampoextra2}}:</label>

                        <div class="custom-file">
                          <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="campoextra2arquivo" required>
                        </div>
                        <small>Algum texto aqui?</small>
                        @error('campoextra2arquivo')
                        <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                          <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                      </div>
                    @endif
                  @endif
                @endif
                @if ($indice == "etiquetacampoextra3")
                  @if ($formSubTraba->checkcampoextra3 == true)
                    @if ($formSubTraba->tipocampoextra3 == "textosimples")
                      <div class="row justify-content-center">
                        {{-- Nome Trabalho  --}}
                      <div class="col-sm-12">
                            <label for="campoextra3simples" class="col-form-label">{{ $formSubTraba->etiquetacampoextra3}}:</label>
                            <input id="campoextra3simples" type="text" class="form-control @error('campoextra3simples') is-invalid @enderror" name="campoextra3simples" value="{{ old('campoextra3simples') }}" required autocomplete="campoextra3simples" autofocus>

                            @error('campoextra3simples')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                      </div>
                    @elseif ($formSubTraba->tipocampoextra3 == "textogrande")
                      <div class="row justify-content-center">
                        {{-- Nome Trabalho  --}}
                      <div class="col-sm-12">
                            <label for="campoextra3grande" class="col-form-label">{{ $formSubTraba->etiquetacampoextra3}}:</label>
                            <textarea id="campoextra3grande" type="text" class="form-control @error('campoextra3grande') is-invalid @enderror" name="campoextra3grande" value="{{ old('campoextra3grande') }}" required autocomplete="campoextra3grande" autofocus></textarea>

                            @error('campoextra3grande')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                      </div>
                    @elseif ($formSubTraba->tipocampoextra3 == "upload")
                      {{-- Arquivo de Regras  --}}
                      <div class="col-sm-12" style="margin-top: 20px;">
                        <label for="campoextra3arquivo" class="col-form-label">{{ $formSubTraba->etiquetacampoextra3}}:</label>

                        <div class="custom-file">
                          <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="campoextra3arquivo" required>
                        </div>
                        <small>Algum texto aqui?</small>
                        @error('campoextra3arquivo')
                        <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                          <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                      </div>
                    @endif
                  @endif
                @endif
                @if ($indice == "etiquetacampoextra4")
                  @if ($formSubTraba->checkcampoextra4 == true)
                    @if ($formSubTraba->tipocampoextra4 == "textosimples")
                      <div class="row justify-content-center">
                        {{-- Nome Trabalho  --}}
                      <div class="col-sm-12">
                            <label for="campoextra4simples" class="col-form-label">{{ $formSubTraba->etiquetacampoextra4}}:</label>
                            <input id="campoextra4simples" type="text" class="form-control @error('campoextra4simples') is-invalid @enderror" name="campoextra4simples" value="{{ old('campoextra4simples') }}" required autocomplete="campoextra4simples" autofocus>

                            @error('campoextra4simples')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                      </div>
                    @elseif ($formSubTraba->tipocampoextra4 == "textogrande")
                      <div class="row justify-content-center">
                        {{-- Nome Trabalho  --}}
                      <div class="col-sm-12">
                            <label for="campoextra4grande" class="col-form-label">{{ $formSubTraba->etiquetacampoextra4}}:</label>
                            <textarea id="campoextra4grande" type="text" class="form-control @error('campoextra4grande') is-invalid @enderror" name="campoextra4grande" value="{{ old('campoextra4grande') }}" required autocomplete="campoextra4grande" autofocus></textarea>

                            @error('campoextra4grande')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                      </div>
                    @elseif ($formSubTraba->tipocampoextra4 == "upload")
                      {{-- Arquivo de Regras  --}}
                      <div class="col-sm-12" style="margin-top: 20px;">
                        <label for="campoextra4arquivo" class="col-form-label">{{$formSubTraba->etiquetacampoextra4}}:</label>

                        <div class="custom-file">
                          <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="campoextra4arquivo" required>
                        </div>
                        <small>Algum texto aqui?</small>
                        @error('campoextra4arquivo')
                        <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                          <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                      </div>
                    @endif
                  @endif
                @endif
                @if ($indice == "etiquetacampoextra5")
                  @if ($formSubTraba->checkcampoextra5 == true)
                    @if ($formSubTraba->tipocampoextra5 == "textosimples")
                      <div class="row justify-content-center">
                        {{-- Nome Trabalho  --}}
                      <div class="col-sm-12">
                            <label for="campoextra5simples" class="col-form-label">{{ $formSubTraba->etiquetacampoextra5}}:</label>
                            <input id="campoextra5simples" type="text" class="form-control @error('campoextra5simples') is-invalid @enderror" name="campoextra5simples" value="{{ old('campoextra5simples') }}" required autocomplete="campoextra5simples" autofocus>

                            @error('campoextra5simples')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                      </div>
                    @elseif ($formSubTraba->tipocampoextra5 == "textogrande")
                      <div class="row justify-content-center">
                        {{-- Nome Trabalho  --}}
                      <div class="col-sm-12">
                            <label for="campoextra5" class="col-form-label">{{ $formSubTraba->etiquetacampoextra5}}:</label>
                            <textarea id="campoextra5grande" type="text" class="form-control @error('campoextra5grande') is-invalid @enderror" name="campoextra5grande" value="{{ old('campoextra5grande') }}" required autocomplete="campoextra5grande" autofocus></textarea>

                            @error('campoextra5grande')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                      </div>
                    @elseif ($formSubTraba->tipocampoextra5 == "upload")
                      {{-- Arquivo de Regras  --}}
                      <div class="col-sm-12" style="margin-top: 20px;">
                        <label for="campoextra5arquivo" class="col-form-label">{{ $formSubTraba->etiquetacampoextra5}}:</label>

                        <div class="custom-file">
                          <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="campoextra5arquivo" required>
                        </div>
                        <small>Algum texto aqui?</small>
                        @error('campoextra5arquivo')
                        <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                          <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                      </div>
                    @endif
                  @endif
                @endif
              @endforeach
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary" form="formEditarTrab{{$trabalho->id}}">Salvar</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Fim Modal editar trabalho -->
  @endif
@endforeach

@foreach ($trabalhos as $trabalho)
    <!-- Modal de coautores trabalho -->
    <div class="modal fade" id="modalCoautoresTrabalho_{{$trabalho->id}}" tabindex="-1" aria-labelledby="modalCoautoresTrabalho_{{$trabalho->id}}Label" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background-color: #114048ff; color: white;">
            <h5 class="modal-title">Coautores do trabalho {{$trabalho->titulo}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <label for="autor" style="font-weight: bold">{{__('Autor')}}:</label>
            <p>{{$trabalho->autor->name}}</p>
            <label for="autor" style="font-weight: bold">{{__('Coautores')}}:</label>
            @foreach ($trabalho->coautors as $coautor)
                <p>{{$coautor->user->name}}</p>
            @endforeach
          </div>
        </div>
      </div>
    </div>
    <!-- Fim Modal coautores do trabalho -->
@endforeach

@foreach ($trabalhos as $trabalho)
  @if($trabalho->modalidade->inicioCorrecao <= $agora && $agora <= $trabalho->modalidade->fimCorrecao)
    <!-- Modal  correcao trabalho -->
    <div class="modal fade" id="modalCorrecaoTrabalho_{{$trabalho->id}}" tabindex="-1" aria-labelledby="modalCorrecaoTrabalho_{{$trabalho->id}}Label" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background-color: #114048ff; color: white;">
            <h5 class="modal-title" id="modalCorrecaoTrabalho_{{$trabalho->id}}Label">Correção do trabalho {{$trabalho->titulo}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="formCorrecaoTrabalho{{$trabalho->id}}" action="{{route('trabalho.correcao', ['id' => $trabalho->id])}}" method="POST" enctype="multipart/form-data">
              @csrf

              @php
                $formSubTraba = $trabalho->evento->formSubTrab;
                $ordem = explode(",", $formSubTraba->ordemCampos);
                $modalidade = $trabalho->modalidade;
              @endphp
              <input type="hidden" name="trabalhoCorrecaoId" value="{{$trabalho->id}}">
              @error('numeroMax'.$trabalho->id)
                <div class="row">
                  <div class="col-md-12">
                    <div class="alert alert-danger" role="alert">
                      {{ $message }}
                    </div>
                  </div>
                </div>
              @enderror
              @foreach ($ordem as $indice)
                @if ($indice == "etiquetatitulotrabalho")
                  <div class="row justify-content-center">
                    {{-- Nome Trabalho  --}}
                    <div class="col-sm-12">
                        <label for="nomeTrabalho_{{$trabalho->id}}" class="col-form-label">{{ $formSubTraba->etiquetatitulotrabalho }}</label>
                        <input id="nomeTrabalho_{{$trabalho->id}}" type="text" class="form-control @error('nomeTrabalho'.$trabalho->id) is-invalid @enderror" name="nomeTrabalho{{$trabalho->id}}" value="@if(old('nomeTrabalho'.$trabalho->id)!=null){{old('nomeTrabalho'.$trabalho->id)}}@else{{$trabalho->titulo}}@endif"  autocomplete="nomeTrabalho" autofocus disabled>

                        @error('nomeTrabalho'.$trabalho->id)
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                  </div>
                  <div class="row justify-content-center">
                    {{-- Autor Trabalho  --}}
                    <div class="col-sm-12">
                        <label for="autorTrabalho_{{$trabalho->autor->id}}" class="col-form-label">Autor</label>
                        <input id="autorTrabalho_{{$trabalho->autor->id}}" type="text" class="form-control @error('autorTrabalho'.$trabalho->autor->id) is-invalid @enderror" name="autorTrabalho{{$trabalho->autor->id}}" value="@if(old('autorTrabalho'.$trabalho->autor->id)!=null){{old('autorTrabalho'.$trabalho->autor->id)}}@else{{$trabalho->autor->name}}@endif"  autocomplete="autorTrabalho" autofocus disabled>
                    </div>
                  </div>
                @endif
                @if ($indice == "etiquetacoautortrabalho")
                  <div class="flexContainer" style="margin-top:20px">

                        <div id="coautores{{$trabalho->id}}" class="flexContainer " >
                            @if($trabalho->coautors->first() != null)
                                <h4>Co-autores</h4>
                                @foreach ($trabalho->coautors as $i => $coautor)
                                <div class="item card">
                                    <div class="row card-body">
                                        <div class="col-sm-4">
                                            <label>E-mail</label>
                                            <input type="email" style="margin-bottom:10px" value="{{$coautor->user->email}}" oninput="buscarEmail(this)" class="form-control emailCoautor" name="emailCoautor_{{$trabalho->id}}[]" placeholder="E-mail" disabled>
                                        </div>
                                        <div class="col-sm-5">
                                            <label>Nome Completo</label>
                                            <input type="text" style="margin-bottom:10px" value="{{$coautor->user->name}}" class="form-control emailCoautor" name="nomeCoautor_{{$trabalho->id}}[]" placeholder="Nome" disabled>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        </div>

                    </div>
                @endif
                @if ($indice == "etiquetaareatrabalho")
                  <!-- Areas -->
                  <div class="row justify-content-center">
                      <div class="col-sm-12">
                          <label for="area_{{$trabalho->id}}" class="col-form-label">{{$formSubTraba->etiquetaareatrabalho}}</label>
                          <select id="area_{{$trabalho->id}}" class="form-control @error('area'.$trabalho->id) is-invalid @enderror" name="area{{$trabalho->id}}" required>
                              <option value="{{$trabalho->area->nome}}" selected disabled>{{$trabalho->area->nome}}</option>
                          </select>
                          @error('area'.$trabalho->id)
                            <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror
                      </div>
                  </div>
                @endif
                @if ($indice == "etiquetauploadtrabalho")
                  <div class="row justify-content-center">
                    {{-- Submeter trabalho corrigido --}}

                    @if ($modalidade->arquivo == true)
                      <div class="col-sm-12" style="margin-top: 20px;">
                        @if($trabalho->arquivoCorrecao()->first() != null)
                            <label for="nomeTrabalho" class="col-form-label">Upload de Correção do Trabalho:</label>
                                <a href="{{route('downloadCorrecao', ['id' => $trabalho->id])}}">Arquivo atual</a>
                            <br>
                            <small>Para trocar o arquivo envie um novo.</small>
                        @endif
                        <div class="custom-file">
                          <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="arquivoCorrecao" required>
                        </div>
                        <small>Arquivos aceitos nos formatos
                          @if($modalidade->pdf == true)<span> - pdf</span>@endif
                          @if($modalidade->jpg == true)<span> - jpg</span>@endif
                          @if($modalidade->jpeg == true)<span> - jpeg</span>@endif
                          @if($modalidade->png == true)<span> - png</span>@endif
                          @if($modalidade->docx == true)<span> - docx</span>@endif
                          @if($modalidade->odt == true)<span> - odt</span>@endif
                          @if($modalidade->zip == true)<span> - zip</span>@endif
                          @if($modalidade->svg == true)<span> - svg</span>@endif.
                        </small>
                        @error('arquivo'.$trabalho->id)
                          <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                      </div>
                    @endif
                  </div>
                @endif
              @endforeach
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary" form="formCorrecaoTrabalho{{$trabalho->id}}">Enviar correção</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Fim Modal correcao trabalho -->
  @endif
@endforeach

@endsection

@section('javascript')
@if(old('trabalhoEditId'))
  <script>
    $(document).ready(function() {
      $('#modalEditarTrabalho_{{old('trabalhoEditId')}}').modal('show');
    })
  </script>
@endif

<script>
  function montarLinhaInput(div, id, event){
    var coautores = document.getElementById("coautores"+id);
    var html = "";
    if (coautores.children.length==1){
        html = `<h4 id="title-coautores${id}" style="margin-top:20px">Coautor(es)</h4>`;
    }
    event.preventDefault();
    html += `
    <div class="item card">
        <div class="row card-body">
            <div class="col-sm-4">
                <label>E-mail</label>
                <input type="email" style="margin-bottom:10px" class="form-control emailCoautor" name="emailCoautor_${id}[]" placeholder="E-mail">
            </div>
            <div class="col-sm-5">
                <label>Nome Completo</label>
                <input type="text" style="margin-bottom:10px" class="form-control emailCoautor" name="nomeCoautor_${id}[]" placeholder="Nome">
            </div>
            <div class="col-sm-3">
                <a style="color: #d30909;" href="#" onclick="deletarCoautor(this, ${id}, event)" class="delete pr-2">
                    <i class="fas fa-user-times fa-2x"></i>
                </a>
                <a href="#" onclick="mover(this.parentElement.parentElement.parentElement, 1, ${id}, event)">
                    <i class="fas fa-arrow-up fa-2x" id="arrow-up" style=""></i>
                </a>
                <a href="#" onclick="mover(this.parentElement.parentElement.parentElement, 0, ${id}, event)">
                    <i class="fas fa-arrow-down fa-2x" id="arrow-down" style="margin-top:35px"></i>
                </a>
            </div>
        </div>
    </div>
    `;

    $('#coautores'+id).append(html);
  }

  function deletarCoautor(div, id, event) {
    event.preventDefault();
    var titulo = document.getElementById("title-coautores"+id);
    var coautores = document.getElementById("coautores"+id);
    $('#title-coautores'+id).remove();
    div.parentElement.parentElement.parentElement.remove();
    if(coautores.children.length >= 2) {
        coautores.insertBefore(titulo, coautores.children[1]);
    }
  }

  $(document).ready(function(){
    $('.char-count').keyup(function() {

        var maxLength = parseInt($(this).attr('maxlength'));
        var length = $(this).val().length;
        // var newLength = maxLength-length;

        var name = $(this).attr("name");
        $('#'+name).text(length);
    });
  });

  $(document).ready(function(){
    $('.palavra').keyup(function() {
        var myText = this.value.trim();
        var wordsArray = myText.split(/\s+/g);
        var words = wordsArray.length;
        var min = parseInt(($('#minpalavras').text()));
        var max = parseInt(($('#maxpalavras').text()));
        if(words < min || words > max) {
            this.setCustomValidity('Número de palavras não permitido. Você possui atualmente '+words+' palavras.');
        } else {
            this.setCustomValidity('');
        }
        var name = $(this).attr("name");
        $('#'+name).text(words);
    });
  });


  function mover(div, direcao, id, event) {
    event.preventDefault();
    var coautores = document.getElementById("coautores"+id);

    var hcoautores;
    if(coautores.children.length > 2) {
        hcoautores = coautores.children[1];
        coautores.children[1].remove();
    }
    if(direcao == 0) {
      for(var i = 0; i < coautores.children.length; i++) {
        if (coautores.children[i] == div && coautores.children[i+1] != null) {
          var baixo = coautores.children[i+1];
          var cima = coautores.children[i];
          coautores.children[i+1].remove();
          coautores.insertBefore(baixo, cima);
          break;
        }
      }
    } else if (direcao == 1) {
      for(var i = 0; i < coautores.children.length; i++) {
        if (coautores.children[i] == div && coautores.firstChild != div) {
          var baixo = coautores.children[i];
          var cima = coautores.children[i-1];
          coautores.children[i].remove();
          coautores.insertBefore(baixo, cima);
          break;
        }
      }
    }
    if(coautores.children.length >= 2){
        coautores.insertBefore(hcoautores, coautores.children[1]);
        console.log('');
    }
  }

  function buscarEmail(input) {
    var emailBuscado = input.value;
    var inputName = input.parentElement.parentElement.children[1].children[1];

    let data = {email: emailBuscado,};

    if (!(emailBuscado=="" || emailBuscado.indexOf('@')==-1 || emailBuscado.indexOf('.')==-1)) {
      $.ajax({
        type: 'GET',
        url: '{{ route("search.user") }}',
        data: data,
        dataType: 'json',
        success: function(res) {
          if(res.user[0] != null) {
            inputName.value = res.user[0]['name'];
          }
        },
        error: function(err){
            // console.log('err')
            // console.log(err)
        }
      });
    }
  }

</script>

@endsection
