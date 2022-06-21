@extends('layouts.app')
@section('content')
    @foreach ($atividades as $atv)
        <div class="modal fade bd-example modal-show-atividade" id="modalAtividadeShow{{ $atv->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabelAtividadeShow{{ $atv->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #114048ff; color: white;">
                        <h5 class="modal-title" id="modalLabelAtividadeShow{{ $atv->id }}">{{ $atv->titulo }}</h5>
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
                                        {{ $atv->tipoAtividade->descricao }}
                                    </p>
                                </div>
                            </div>
                            <hr>
                            <div class="row form-group">
                                <div class="col-sm-12">
                                    <h4 for="descricao">Descrição</label>
                                        <p>
                                            {{ $atv->descricao }}
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
                                                <img src="{{ asset('img/icons/user.png') }}" alt="Foto de {{ $convidado->nome }}" width="50px" height="auto">
                                                <h5 class="convidadoNome">{{ $convidado->nome }}</h5>
                                                <small class="convidadoFuncao">{{ $convidado->funcao }}</small>
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
                                        {{ $atv->local }}
                                    </p>
                                </div>
                            </div>
                            @if ($atv->vagas != null || $atv->valor != null)
                                <hr>
                                <div class="row">
                                    @if ($atv->vagas != null)
                                        <div class="col-sm-6">
                                            <label for="vagas">Vagas:</label>
                                            <h4 class="vagas">{{ $atv->vagas }}</h4>
                                        </div>
                                    @endif
                                    @if ($atv->valor != null)
                                        <div class="col-sm-6">
                                            <label for="valor">Valor:</label>
                                            <h4 class="valor">R$ {{ $atv->valor }}</h4>
                                        </div>
                                    @endif
                                </div>
                            @endif
                            @if ($atv->carga_horaria != null)
                                <hr>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label for="carga_horaria">Carga horária para estudantes:</label>
                                        <h4 class="carga_horaria">{{ $atv->carga_horaria }}</h4>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        @if ($isInscrito)
                            @if($atv->vagas > 0 && Auth::user()->atividades()->find($atv->id) == null)
                                <a type="button" class="btn btn-primary" href="{{route('atividades.inscricao', ['id'=>$atv->id])}}">Inscrever-se</a>
                            @elseif(Auth::user()->atividades()->find($atv->id) != null)
                                <a type="button" class="btn btn-primary"  href="{{route('atividades.cancelarInscricao', ['id'=>$atv->id])}}">Cancelar Inscrição</a>
                            @else
                                <button type="button" class="btn btn-danger"  style="pointer-events: none">Sem Vagas</button>
                            @endif
                        @endif
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
                <form method="POST" action="{{ route('trabalho.novaVersao') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row justify-content-center">
                            <div class="col-sm-12">
                                @if ($hasFile)
                                    <input type="hidden" name="trabalhoId" value="" id="trabalhoNovaVersaoId">
                                @endif
                                <input type="hidden" name="eventoId" value="{{ $evento->id }}">
                                {{-- Arquivo --}}
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
            @if (isset($evento->fotoEvento))
                <div class="banner-evento">
                    <img style="background-size: cover" src="{{ asset('storage/' . $evento->fotoEvento) }}" alt="">
                </div>
            @else
                <div class="banner-evento">
                    <img style="background-size: cover" src="{{ asset('img/colorscheme.png') }}" alt="">
                </div>
                {{-- <img class="front-image-evento" src="{{asset('img/colorscheme.png')}}" alt=""> --}}
            @endif
        </div>
    </div>
    <div class="modal fade" id="modalInscrever" tabindex="-1" role="dialog" aria-labelledby="#label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #114048ff; color: white;">
                    <h5 class="modal-title" id="#label">Confirmação</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('inscricao.inscrever', ['evento_id' => $evento->id]) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        Tem certeza que deseja se inscrever nesse evento?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                        <button type="submit" class="btn btn-primary">Sim</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="div-informacoes-evento container" style="@if (isset($evento->fotoEvento))margin-top: -75px;@else margin-top: -200px; @endif">
        <div class="row justify-content-center">
            <div class="col-sm-10">
                <div class="row">
                    <div class="col-xl-8">
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-sm-12">
                                <div class="card sombra-card" style="width: 100%;">
                                    <div class="card-body">
                                        <div class="container">
                                            {{-- @if (!Auth::check())
                                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                        <strong> A submissão de um trabalho é possível apenas quando cadastrado no sistema. </strong>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    @endif --}}
                                            @if (session('message'))
                                                <div class="alert alert-success">
                                                    {{ session('message') }}
                                                </div>
                                            @endif
                                            @if (session('error'))
                                                <div class="alert alert-danger">
                                                    {{ session('error') }}
                                                </div>
                                            @endif
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <h2 style="font-weight: bold; border-bottom: solid 3px #114048ff;">{{ $evento->nome }}</h2>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-sm-12" style="text-align: justify;">
                                                    {!! $evento->descricao !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if ($etiquetas->modprogramacao == true && $evento->exibir_calendario_programacao)
                            <div class="row" style="margin-bottom: 10px;">
                                <div class="col-sm-12">
                                    <div class="card sombra-card" style="width: 100%;">
                                        <div class="card-body">
                                            <div class="form-row">
                                                <div class="col-sm-12 form-group">
                                                    <h4 style="font-weight: bold; border-bottom: solid 3px #114048ff;">Programação</h4>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-sm-12 form-group">
                                                    <div id="wrap">
                                                        <div id='calendar-wrap' style="width: 100%;">
                                                            <div id='calendar'></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-xl-4">
                        @if ($etiquetas->modinscricao == true)
                            <div class="row" style="margin-bottom: 10px;">
                                <div class="col-sm-12">
                                    <div class="card sombra-card" style="width: 100%;">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <h4 style="font-weight: bold; border-bottom: solid 3px #114048ff;">{{ $etiquetas->etiquetamoduloinscricao }}</h4>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <button id="btn-inscrevase" class="btn btn-primary" data-toggle="modal" data-target="#modalInscrever" @if ($isInscrito) disabled @endif>@if ($isInscrito) Já inscrito @else Inscreva-se @endif</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-sm-12">
                                <div class="card sombra-card" style="width: 100%;">
                                    <div class="card-body">
                                        <div class="form-row">
                                            <div class="col-sm-12 form-group">
                                                <h4 style="font-weight: bold; border-bottom: solid 3px #114048ff;">Informações</h4>
                                            </div>
                                        </div>
                                        @if (!$evento->exibir_calendario_programacao && $etiquetas->modprogramacao == true && $evento->pdf_programacao != null)
                                            <div class="form-row justify-content-center">
                                                <div class="col-sm-3 form-group " style="position: relative; text-align: center;">
                                                    <div class="div-icon-programacao">
                                                        <img class="icon-programacao" src="{{ asset('img/icons/Icon awesome-file-pdf.svg') }}" alt="PDF com a programação">
                                                    </div>
                                                </div>
                                                <div class="col-sm-8 form-inline">
                                                    <span class="titulo">
                                                        <a href="{{ asset('storage/' . $evento->pdf_programacao) }}" target="_black">{{ $etiquetas->etiquetamoduloprogramacao }}</a>
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($evento->pdf_arquivo != null && $evento->modarquivo)
                                            <div class="form-row justify-content-center">
                                                <div class="col-sm-3 form-group " style="position: relative; text-align: center;">
                                                    <div class="div-icon-programacao">
                                                        <img class="icon-programacao" src="{{ asset('img/icons/Icon awesome-file-pdf.svg') }}" alt="PDF com a programação">
                                                    </div>
                                                </div>
                                                <div class="col-sm-8 form-inline">
                                                    <span class="titulo">
                                                        <a href="{{ asset('storage/' . $evento->pdf_arquivo) }}" target="_black">{{ $etiquetas->etiquetaarquivo }}</a>
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                        @foreach ($evento->arquivoInfos as $arquivo)
                                            <div class="form-row justify-content-center">
                                                <div class="col-sm-3 form-group " style="position: relative; text-align: center;">
                                                    <div class="div-icon-programacao">
                                                        <img class="icon-programacao" src="{{ asset('img/icons/Icon awesome-file-pdf.svg') }}" alt="">
                                                    </div>
                                                </div>
                                                <div class="col-sm-8 form-inline">
                                                    <span class="titulo">
                                                        <a href="{{ asset('storage/'.$arquivo->path) }}" target="_black">{{ $arquivo->nome }}</a>
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                        @if ($etiquetas->modsubmissao == true)
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    {{-- <h5>{{$etiquetas->etiquetasubmissoes}}</h5> --}}
                                                    <div class="accordion" id="accordion_modalidades" style="font-size: 10px;">
                                                        @foreach ($modalidades as $modalidade)
                                                            @if (Carbon\Carbon::parse($modalidade->inicioResultado) >= $mytime)
                                                                <div class="accordion-group">
                                                                    <div class="accordion-heading">
                                                                        <a class="accordion-button accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion_modalidades" href="{{ '#collapse_' . $modalidade->id }}">
                                                                            <div class="row">
                                                                                <div class="col-sm-10">
                                                                                    <div class="row">
                                                                                        <div class="col-sm-3">
                                                                                            <img class="enviar-trabalho" src="{{ asset('img/icons/Icon awesome-arrow-up.svg') }}" alt="Enviar trabalho">
                                                                                        </div>
                                                                                        <div class="col-sm-9" style="position: relative; left: 8px; margin-top: 8px; ">
                                                                                            <span style="font-size: 14px;">Envio de trabalhos</span><br>
                                                                                            <span style="font-size: 12px;">(modalidade <strong>{{ $modalidade->nome }}</strong>)</span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-2" style="margin-top: 15px;">
                                                                                    <img src="{{ asset('img/icons/Icon ionic-ios-arrow-down.svg') }}" alt="Seta que abre o accordion" width="15px;">
                                                                                </div>
                                                                            </div>
                                                                        </a>
                                                                    </div>
                                                                    <div id="{{ 'collapse_' . $modalidade->id }}" class="accordion-body in collapse" style="height: auto;">
                                                                        {{-- <div class="accordion-inner"> --}}
                                                                        <div class="row">
                                                                            <div class="col-sm-12">
                                                                                <table>
                                                                                    <tr>
                                                                                        <td><img class="" src="{{ asset('img/icons/calendar-pink.png') }}" alt="" style="width:20px;"></td>
                                                                                        <td>Envio:</td>
                                                                                        <td>{{ date('d/m/Y H:i', strtotime($modalidade->inicioSubmissao)) }}</td>
                                                                                        <td>- {{ date('d/m/Y H:i', strtotime($modalidade->fimSubmissao)) }}</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td><img class="" src="{{ asset('img/icons/calendar-yellow.png') }}" alt="" style="width:20px;"></td>
                                                                                        <td>Avaliação:</td>
                                                                                        <td>{{ date('d/m/Y H:i', strtotime($modalidade->inicioRevisao)) }}</td>
                                                                                        <td>- {{ date('d/m/Y H:i', strtotime($modalidade->fimRevisao)) }}</td>
                                                                                    </tr>
                                                                                    @if ($modalidade->inicioCorrecao && $modalidade->fimCorrecao)
                                                                                        <tr>
                                                                                            <td><img class="" src="{{ asset('img/icons/calendar-yellow.png') }}" alt="" style="width:20px;"></td>
                                                                                            <td>Correção:</td>
                                                                                            <td>{{ date('d/m/Y H:i', strtotime($modalidade->inicioCorrecao)) }}</td>
                                                                                            <td>- {{ date('d/m/Y H:i', strtotime($modalidade->fimCorrecao)) }}</td>
                                                                                        </tr>
                                                                                    @endif
                                                                                    @if ($modalidade->inicioValidacao && $modalidade->fimValidacao)
                                                                                        <tr>
                                                                                            <td><img class="" src="{{ asset('img/icons/calendar-yellow.png') }}" alt="" style="width:20px;"></td>
                                                                                            <td>Validação:</td>
                                                                                            <td>{{ date('d/m/Y H:i', strtotime($modalidade->inicioValidacao)) }}</td>
                                                                                            <td>- {{ date('d/m/Y H:i', strtotime($modalidade->fimValidacao)) }}</td>
                                                                                        </tr>
                                                                                    @endif
                                                                                    <tr>
                                                                                        <td><img class="" src="{{ asset('img/icons/calendar-green.png') }}" alt="" style="width:20px;"></td>
                                                                                        <td>Resultado:</td>
                                                                                        <td>{{ date('d/m/Y  H:i', strtotime($modalidade->inicioResultado)) }}</td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-sm-12">
                                                                                @if (Carbon\Carbon::parse($modalidade->inicioSubmissao) <= $mytime)
                                                                                    @if ($mytime <= Carbon\Carbon::parse($modalidade->fimSubmissao))
                                                                                        @if ($modalidade->arquivo == true)
                                                                                            @if (isset($modalidade->regra))
                                                                                                <div style="margin-top: 20px; margin-bottom: 10px;">
                                                                                                    <a href="{{ route('modalidade.regras.download', ['id' => $modalidade->id]) }}" target="_new" style="font-size: 14px; color: #114048ff; text-decoration: none;">
                                                                                                        <img class="" src="{{ asset('img/icons/file-download-solid.svg') }}" style="width:20px;">&nbsp;{{ $evento->formEvento->etiquetabaixarregra }}
                                                                                                    </a>
                                                                                                </div>
                                                                                            @endif
                                                                                            @if (isset($modalidade->modelo_apresentacao))
                                                                                                <div style="margin-top: 20px; margin-bottom: 10px;">
                                                                                                    <a href="{{ route('modalidade.modelos.download', ['id' => $modalidade->id]) }}" target="_new" style="font-size: 14px; color: #114048ff; text-decoration: none;">
                                                                                                        <img class="" src="{{ asset('img/icons/file-download-solid.svg') }}" style="width:20px;">&nbsp;{{$evento->formEvento->etiquetabaixarapresentacao}}
                                                                                                    </a>
                                                                                                </div>
                                                                                            @endif
                                                                                            @if (isset($modalidade->template))
                                                                                                <div style="margin-top: 20px; margin-bottom: 10px;">
                                                                                                    <a href="{{ route('modalidade.template.download', ['id' => $modalidade->id]) }}" target="_new" style="font-size: 14px; color: #114048ff; text-decoration: none;">
                                                                                                        <img class="" src="{{ asset('img/icons/file-download-solid.svg') }}" style="width:20px;">&nbsp;{{ $evento->formEvento->etiquetabaixartemplate }}
                                                                                                    </a>
                                                                                                </div>
                                                                                            @endif
                                                                                        @else
                                                                                            @if (isset($modalidade->modelo_apresentacao))
                                                                                                <div style="margin-top: 20px; margin-bottom: 10px;">
                                                                                                    <a href="{{ route('modalidade.modelos.download', ['id' => $modalidade->id]) }}" target="_new" style="font-size: 14px; color: #114048ff; text-decoration: none;">
                                                                                                        <img class="" src="{{ asset('img/icons/file-download-solid.svg') }}" style="width:20px;">&nbsp;{{$evento->formEvento->etiquetabaixarapresentacao}}
                                                                                                    </a>
                                                                                                </div>
                                                                                            @endif
                                                                                            @if (isset($modalidade->regra))
                                                                                                <div style="margin-top: 20px; margin-bottom: 10px;">
                                                                                                    <a href="{{ route('modalidade.regras.download', ['id' => $modalidade->id]) }}" target="_new" style="font-size: 14px; color: #114048ff; text-decoration: none;">
                                                                                                        <img class="" src="{{ asset('img/icons/file-download-solid.svg') }}" style="width:20px;">&nbsp;{{ $evento->formEvento->etiquetabaixarregra }}
                                                                                                    </a>
                                                                                                </div>
                                                                                            @endif
                                                                                        @endif
                                                                                        <a class="btn button-card-visualizar-evento" href="{{ route('trabalho.index', ['id' => $evento->id, 'idModalidade' => $modalidade->id]) }}" style="width: 100%; font-weight: bold;">SUBMETER TRABALHO</a>
                                                                                    @else
                                                                                        {{-- Permitindo Coordenador ou Comissao Cientifica submeter trabalhos mesmo passada a data de submissao --}}
                                                                                        @can('isCoordenadorOrCoordenadorDasComissoes', $evento)
                                                                                            @if (isset($modalidade->modelo_apresentacao))
                                                                                                <div style="margin-top: 20px; margin-bottom: 10px;">
                                                                                                    <a href="{{ route('modalidade.modelos.download', ['id' => $modalidade->id]) }}" target="_new" style="font-size: 14px; color: #114048ff; text-decoration: none;">
                                                                                                        <img class="" src="{{ asset('img/icons/file-download-solid.svg') }}" style="width:20px;">&nbsp;{{$evento->formEvento->etiquetabaixarapresentacao}}
                                                                                                    </a>
                                                                                                </div>
                                                                                            @endif
                                                                                            @if ($modalidade->arquivo == true)
                                                                                                @if (isset($modalidade->regra))
                                                                                                    <div style="margin-top: 20px; margin-bottom: 10px;">
                                                                                                        <a href="{{ route('modalidade.regras.download', ['id' => $modalidade->id]) }}" target="_new" style="font-size: 14px; color: #114048ff; text-decoration: none;">
                                                                                                            <img class="" src="{{ asset('img/icons/file-download-solid.svg') }}" style="width:20px">&nbsp;{{ $evento->formEvento->etiquetabaixarregra }}
                                                                                                        </a>
                                                                                                    </div>
                                                                                                @endif
                                                                                                @if (isset($modalidade->template))
                                                                                                    <div style="margin-top: 20px; margin-bottom: 10px;">
                                                                                                        <a href="{{ route('modalidade.template.download', ['id' => $modalidade->id]) }}" target="_new" style="font-size: 14px; color: #114048ff; text-decoration: none;">
                                                                                                            <img class="" src="{{ asset('img/icons/file-download-solid.svg') }}" style="width:20px">&nbsp;{{ $evento->formEvento->etiquetabaixartemplate }}
                                                                                                        </a>
                                                                                                    </div>
                                                                                                @endif
                                                                                            @else
                                                                                                @if (isset($modalidade->modelo_apresentacao))
                                                                                                    <div style="margin-top: 20px; margin-bottom: 10px;">
                                                                                                        <a href="{{ route('modalidade.modelos.download', ['id' => $modalidade->id]) }}" target="_new" style="font-size: 14px; color: #114048ff; text-decoration: none;">
                                                                                                            <img class="" src="{{ asset('img/icons/file-download-solid.svg') }}" style="width:20px;">&nbsp;{{$evento->formEvento->etiquetabaixarapresentacao}}
                                                                                                        </a>
                                                                                                    </div>
                                                                                                @endif
                                                                                                @if (isset($modalidade->regra))
                                                                                                    <div style="margin-top: 20px; margin-bottom: 10px;">
                                                                                                        <a href="{{ route('modalidade.regras.download', ['id' => $modalidade->id]) }}" target="_new" style="font-size: 14px; color: #114048ff; text-decoration: none;">
                                                                                                            <img class="" src="{{ asset('img/icons/file-download-solid.svg') }}" style="width:20px">&nbsp;{{ $evento->formEvento->etiquetabaixarregra }}
                                                                                                        </a>
                                                                                                    </div>
                                                                                                @endif
                                                                                            @endif
                                                                                            <a class="btn button-card-visualizar-evento white-color" href="{{ route('trabalho.index', ['id' => $evento->id, 'idModalidade' => $modalidade->id]) }}" style="width: 100%; font-weight: bold;">SUBMETER TRABALHO</a>
                                                                                        @endcan
                                                                                    @endif
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        {{-- </div> --}}
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="form-row" style="margin-top: 15px; justify-content: center;">
                                            <div class="col-sm-3" style="text-align: center;">
                                                <div class="div-icon-programacao">
                                                    <img src="{{ asset('img/icons/Icon material-email.svg') }}" alt="PDF com a programação" style="margin-top: 5px;">
                                                </div>
                                            </div>
                                            <div class="col-sm-8">
                                                <a href="mailto:{{ $evento->coordenador->email }}">
                                                    <div style="margin-top: 18px;">
                                                        <h6 style="margin-bottom: 0px; font-size: 16px; font-weight: bold;">Contato</h6>
                                                        {{-- {{$evento->coordenador->email}} --}}
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($evento->memorias->count())
                            <div class="row" style="margin-bottom: 10px;">
                                <div class="col-sm-12">
                                    <div class="card sombra-card" style="width: 100%;">
                                        <div class="card-body">
                                            <div class="form-row">
                                                <div class="col-sm-12 form-group">
                                                    <h4 style="font-weight: bold; border-bottom: solid 3px #114048ff;">Memória</h4>
                                                </div>
                                            </div>
                                            @foreach ($evento->memorias as $memoria)
                                                <div class="form-row justify-content-center">
                                                    <div class="col-sm-3 form-group ">
                                                        <div class="div-icon-programacao d-flex justify-content-center">
                                                            @if($memoria->arquivo)
                                                                <img class="icon-programacao" src="{{ asset('img/icons/Icon awesome-file-pdf.svg') }}" alt="">
                                                            @elseif($memoria->link)
                                                                <img class="icon-programacao" src="{{ asset('img/icons/link-solid.svg') }}" alt="">
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-8 form-inline">
                                                        <span class="titulo">
                                                            @if($memoria->arquivo)
                                                                <a href="/storage/{{ $memoria->arquivo }}" target="_blank">{{ $memoria->titulo }}</a>
                                                            @elseif($memoria->link)
                                                                <a href="{{ $memoria->link }}" target="_blank">{{ $memoria->titulo }}</a>
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
                @if ($subeventos->count() > 0)
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card sombra-card" style="width: 100%;">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <h4 style="font-weight: bold; border-bottom: solid 3px #114048ff;">Subeventos</h4>
                                        </div>
                                    </div>
                                    <div class="row">
                                        @foreach ($subeventos as $subevento)
                                            <div class="col-sm-12 col-md-6 col-xl-4 d-flex align-items-stretch justify-content-center">
                                                <div class="card" style="width: 15rem;">
                                                    @if (isset($subevento->fotoEvento))
                                                        <img class="img-card" src="{{ asset('storage/' . $subevento->fotoEvento) }}" class="card-img-top" alt="...">
                                                    @else
                                                        <img class="img-card" src="{{ asset('img/colorscheme.png') }}" class="card-img-top" alt="...">
                                                    @endif
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <h5 class="card-title">
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                                <a href="{{ route('evento.visualizar', ['id' => $subevento->id]) }}" style="text-decoration: inherit;">
                                                                                    {{ $subevento->nome }}
                                                                                </a>
                                                                        </div>
                                                                    </div>
                                                                </h5>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <p class="card-text">
                                                                <img src="{{ asset('/img/icons/calendar.png') }}" alt="" width="20px;" style="position: relative; top: -2px;"> {{ date('d/m/Y', strtotime($subevento->dataInicio)) }} - {{ date('d/m/Y', strtotime($subevento->dataFim)) }}<br>
                                                            </p>
                                                            <p>
                                                            <div class="row justify-content-center">
                                                                <div class="col-sm-12">
                                                                    <img src="{{ asset('/img/icons/location_pointer.png') }}" alt="" width="18px" height="auto">
                                                                    {{ $subevento->endereco->rua }}, {{ $subevento->endereco->numero }} - {{ $subevento->endereco->cidade }} / {{ $subevento->endereco->uf }}.
                                                                </div>
                                                            </div>
                                                            </p>
                                                            <div>
                                                                <div>
                                                                    <a href="{{ route('evento.visualizar', ['id' => $subevento->id]) }}">
                                                                        <i class="far fa-eye" style="color: black"></i>&nbsp;&nbsp;Visualizar evento
                                                                    </a>
                                                                </div>
                                                                @can('isCoordenadorOrCoordenadorDasComissoes', $subevento)
                                                                    <div>
                                                                        <a href="{{ route('coord.detalhesEvento', ['eventoId' => $subevento->id]) }}">
                                                                            <i class="fas fa-cog" style="color: black"></i>&nbsp;&nbsp;Configurar evento
                                                                        </a>
                                                                    </div>
                                                                    @can('isCoordenador', $subevento)
                                                                    <div>
                                                                        <form id="formExcluirEvento{{ $subevento->id }}" method="POST" action="{{ route('evento.deletar', $subevento->id) }}">
                                                                            {{ csrf_field() }}
                                                                            {{ method_field('DELETE') }}
                                                                            <a href="#" data-toggle="modal" data-target="#modalExcluirEvento{{ $subevento->id }}">
                                                                                <i class="far fa-trash-alt" style="color: black"></i>&nbsp;&nbsp;Deletar
                                                                            </a>
                                                                        </form>
                                                                    </div>
                                                                    @endcan
                                                                @endcan
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Modal de exclusão do evento -->
                                            <div class="modal fade" id="modalExcluirEvento{{ $subevento->id }}" tabindex="-1" role="dialog" aria-labelledby="#label" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header" style="background-color: #114048ff; color: white;">
                                                            <h5 class="modal-title" id="#label">Confirmação</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Tem certeza de deseja excluir esse evento?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                                                            <button type="submit" class="btn btn-primary" form="formExcluirEvento{{ $subevento->id }}">Sim</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- fim do modal -->
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @include('componentes.footer')
@endsection
@section('javascript')
    <script>
        $(document).ready(function() {
            $(".accordion-button").click(function() {
                var img = this.children[0].children[1].children[0];
                if (this.classList.contains("collapsed")) {
                    img.src = "{{ asset('img/icons/Icon ionic-ios-arrow-up.svg') }}";
                } else {
                    img.src = "{{ asset('img/icons/Icon ionic-ios-arrow-down.svg') }}";
                }
            });
        });
        var botoes = document.getElementsByClassName('cor-aleatoria');
        for (var i = 0; i < botoes.length; i++) {
            botoes[i].style.backgroundColor = '#' + Math.floor(Math.random() * 16777215).toString(16);
        }
        function changeTrabalho(x) {
            document.getElementById('trabalhoNovaVersaoId').value = x;
        }
    </script>
    @if ($dataInicial != '' && $evento->exibir_calendario_programacao)
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
                    initialDate: "{{ $dataInicial->data }}",
                    headerToolbar: {
                        left: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek',
                        center: 'title',
                        right: 'prev,next today'
                    },
                    locale: 'pt-br',
                    editable: false,
                    eventClick: function(info) {
                        var idModal = "#modalAtividadeShow" + info.event.id;
                        $(idModal).modal('show');
                    },
                    events: "{{ route('atividades.json', ['id' => $evento->id]) }}",
                });
                calendar.render();
            });
        </script>
    @endif
@endsection
