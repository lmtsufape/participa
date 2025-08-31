@extends('layouts.app')
@section('sidebar')


@endsection
@section('content')
    <div class="container">
        <div id="divListarCriterio" class="comissao">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="titulo-detalhes"> <strong> {{$trabalho->titulo}}</strong> <br>
                        Modalidade: <strong> {{$modalidade->nome}}</strong><br>
                        Avaliador: <strong> {{$revisorUser->name}}</strong><br>
                    </h3>
                </div>
            </div>
        </div>
        <form id="editarRespostas" action="{{route('revisor.editar.respostas')}}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="trabalho_id" value="{{$trabalho->id}}">
            <input type="hidden" name="revisor_id" value="{{$revisor->id}}">
            @foreach ($modalidade->forms as $form)
                <div class="card">
                    <div class="card-body">
                    <h5 class="card-title">{{$form->titulo}}</h5>
                    <p class="card-text">
                        @foreach ($form->perguntas()->orderBy('id')->get() as $index => $pergunta)
                        <input type="hidden" name="pergunta_id[]" value="{{$pergunta->id}}">
                            <div class="card">
                                <div class="card-body">
                                    <p><strong>{{strip_tags($pergunta->pergunta)}}</strong> <span><small style="float: right">Pergunta visível para o autor? <input type="checkbox" name="pergunta_checkBox[]" value="{{$pergunta->id}}" {{  ($pergunta->visibilidade == true ? ' checked' : '') }} disabled></small></span>
                                    </p>
                                    @if($pergunta->respostas()->exists() && $pergunta->respostas->first()->opcoes->count())
                                        @if ($respostas[$pergunta->id] != null)
                                            <input type="hidden" name="opcao_id[]" value="{{$respostas[$pergunta->id]->opcoes[0]->id}}">
                                        @endif
                                    @foreach ($pergunta->respostas->first()->opcoes->sortBy('id') as $opcao)
                                        <div class="form-check">
                                            @if ($respostas[$pergunta->id] != null && $respostas[$pergunta->id]->opcoes != null && $respostas[$pergunta->id]->opcoes->pluck('titulo')->contains($opcao->titulo))
                                                <input class="form-check-input" type="radio" name="{{$pergunta->id}}" checked value="{{$respostas[$pergunta->id]->opcoes[0]->titulo}}" id="{{$opcao->id}}">
                                            @else
                                                <input class="form-check-input" type="radio" name="{{$pergunta->id}}" value="{{$opcao->titulo}}" id="{{$opcao->id}}">
                                            @endif
                                            <label class="form-check-label" for="{{$opcao->id}}">
                                                {{$opcao->titulo}}
                                            </label>
                                        </div>
                                        @endforeach
                                       @if ($respostas[$pergunta->id] != null)
                                            <div class="col-form-label text-md-left">
                                                <small>Resposta visível para o autor? (selecione se sim) </small>
                                                <input type="checkbox"
                                                    name="visivilidade_opcoes[]"
                                                    value="{{$respostas[$pergunta->id]->opcoes->first()->id}}"
                                                    {{ ($respostas[$pergunta->id]->opcoes->first()->visibilidade == true ? ' checked' : '') }}
                                                    {{ $pergunta->visibilidade == true ? '' : 'disabled' }}
                                                >
                                            </div>
                                       @endif
                                    @elseif($pergunta->respostas->first()->paragrafo != null)
                                        @forelse ($pergunta->respostas as $resposta)
                                            @if($resposta->revisor != null && $resposta->trabalho != null  && $resposta->paragrafo != null)
                                                @if($resposta->revisor->user_id == $revisorUser->id && $resposta->trabalho->id == $trabalho->id)
                                                    <p class="card-text">
                                                        <input type="hidden" name="resposta_paragrafo_id[]" value="{{$resposta->paragrafo->id}}">
                                                        <textarea id="resposta{{$resposta->paragrafo->id}}" type="text" class="form-control @error('resposta'.$resposta->paragrafo->id) is-invalid @enderror" name="resposta{{$resposta->paragrafo->id}}" required>@if(old('resposta'.$resposta->paragrafo->id)!=null){{old('resposta'.$resposta->paragrafo->id)}}@else{{($resposta->paragrafo->resposta)}}@endif</textarea>
                                                    </p>
                                                    <div class="col-form-label text-md-left">
                                                        <small>Resposta visível para o autor? (selecione se sim) </small><input type="checkbox" name="paragrafo_checkBox[]" value="{{$resposta->paragrafo->id}}" {{  ($resposta->paragrafo->visibilidade == true ? ' checked' : '') }} >
                                                    </div>
                                                @endif
                                            @endif
                                        @empty
                                            <p>Sem respostas</p>
                                        @endforelse
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        <div class="col-form-label text-md-left">
                            <small>Selecionar todas as respostas </small><input id="selecionarTodas" type="checkbox" onclick="select_all()">
                        </div>
                    </p>
                    </div>
                </div>
            @endforeach
            <div class="col-sm-12" style="margin-top: 20px;">
                <small>Para trocar o arquivo de avaliação do avaliador, envie um novo.</small><br>
                <div class="custom-file">
                    <input type="file" class="form-control" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="arquivoAvaliacao">
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
        </form>
        <div class="d-flex flex-column flex-md-row mt-4 gap-3">
            <button type="button" class="btn btn-md btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{$trabalho->id}}">
                Deletar Avaliação
            </button>

            <button type="submit" class="btn btn-md btn-primary" form="editarRespostas" id="submeterFormBotao">
                {{ __('Editar Avaliação') }}
            </button>

            @if ($arquivoAvaliacao != null)
                <a class="btn btn-md btn-primary btn-group" href="{{route('downloadAvaliacao', ['trabalhoId' => $trabalho->id, 'revisorUserId' => $revisorUser->id])}}">
                    <img class="mr-1" src="{{asset('img/icons/file-download-solid.svg')}}" style="width:1em">Baixar trabalho corrigido
                </a>
            @endif
            <form class="d-none" action="{{route('coord.evento.avisoCorrecao', $evento->id)}}" method="POST" id="avisoCorrecao">
                @csrf
                <input type="hidden" name="trabalhosSelecionados[]" value="{{$trabalho->id}}">
            </form>
            <button class="btn btn-md btn-primary" form="avisoCorrecao" type="submit">Lembrete de envio de versão corrigida do texto</button>
            @if ($trabalho->avaliado($revisor->user))
                @if ($trabalho->getParecerAtribuicao($revisor->user) != "encaminhado")
                    <a href="{{ route('trabalho.encaminhar', [$trabalho->id, $revisor]) }}" class="btn btn-md btn-success">
                        Encaminhar parecer para autor/a
                    </a>
                @else
                    <a href="{{ route('trabalho.encaminhar', [$trabalho->id, $revisor]) }}" class="btn btn-md btn-secondary">
                        Desfazer encaminhamento da avaliação
                    </a>
                @endif
            @endif
            <button class="btn btn-md btn-success"
                data-bs-toggle="modal" data-bs-target="#avaliacao-corrigir-{{$trabalho->id}}">
                @if(!$trabalho->permite_correcao)Liberar para correção @else Bloquear para correção @endif
            </button>
        </div>

        @push('modais')
            @include('coordenador.trabalhos.avaliacao-modal', ['trabalho' => $trabalho, 'valor' => 'null', 'descricao' => 'corrigir'])
        @endpush
    </div>

    @include('components.delete_modal', ['route' => 'coord.avaliacao.destroy', 'param' => 'trabalho_id', 'entity_id' => $trabalho->id, 'element' => $revisor->id, 'param_element' => 'revisor_id'])
    @stack('modais')

@endsection

@section('javascript')
    @parent
    <script type="text/javascript">
        var respostas;

        function select_all() {
            respostas = document.getElementsByName('paragrafo_checkBox[]');
            if (document.getElementById('selecionarTodas').checked)
            {
                for (i = 0; i < respostas.length; i++) {
                    if(!respostas[i].checked & !respostas[i].disabled){
                        respostas[i].checked = true;
                    }
                }
            } else {
                for (i = 0; i < respostas.length; i++) {
                    if(respostas[i].checked){
                        respostas[i].checked = false;
                    }
                }
            }
        }

    </script>
@endsection
