@extends('layouts.app')

@section('content')

<div class="container mb-4 position-relative">
    <h3>Evento: {{$evento->nome}}</h3>
    <h3>Modalidade: {{$data['modalidade']->nome}}</h3>
    <h3>{{$evento->formSubTrab->etiquetaareatrabalho}}: {{$data['trabalho']->area->nome}}</h3>
    <h3 class="titulo-detalhes">{{$evento->formSubTrab->etiquetatitulotrabalho}}: {{$data['trabalho']->titulo}}</h3>
    @if(session('message'))
    <div class="row">
        <div class="col-md-12" style="margin-top: 5px;">
            <div class="alert alert-success">
                <p>{{session('message')}}</p>
            </div>
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
    {{-- {{dd($data['modalidade']->forms)}} --}}
    <div class="row">
        <div class="col-md-12">
            @forelse ($forms as $form)
                <div class="card" style="width: 48rem;">
                    <div class="card-body">
                    <h5 class="card-title">{{$form->titulo}}</h5>
                    @if ($form->instrucoes)
                        <h5 class="card-title mt-4 mb-0">Orientações aos(as) avaliadores(as):</h5>
                        {!! $form->instrucoes !!}
                    @endif

                    <p class="card-text">

                        <form action="{{route('revisor.salvar.respostas')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="revisor_id" value="{{$data['revisor']->id}}">
                            <input type="hidden" name="trabalho_id" value="{{$data['trabalho']->id}}">
                            <input type="hidden" name="modalidade_id" value="{{$data['modalidade']->id}}">
                            <input type="hidden" name="form_id[]" value="{{$form->id}}">
                            @foreach ($form->perguntas->sortBy("id") as $pergunta)
                                <div class="card">
                                    <div class="card-body">

                                        <p><b>{!! $pergunta->pergunta !!}</b></p>
                                        <input type="hidden" name="pergunta_id[]" value="{{$pergunta->id}}">
                                        @if($pergunta->respostas->first()->opcoes->count())
                                            @foreach ($pergunta->respostas->first()->opcoes as $opcao)
                                            <div class="form-check">
                                                <input class="form-check-input" required type="radio" name="{{$pergunta->id}}" value="{{$opcao->titulo}}" id="{{$opcao->id}}">
                                                <label class="form-check-label" for="{{$opcao->id}}">
                                                  {!! $opcao->titulo !!}
                                                </label>
                                              </div>
                                            @endforeach
                                        @else
                                            <textarea type="text" style="margin-bottom:10px"  class="form-control " name="{{$pergunta->id}}" required></textarea>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                            <div class="row justify-content-center">
                                {{-- Submeter Arquivo Avalicao --}}

                                @if ($data['modalidade']->arquivo == true)
                                  <div class="col-sm-12" style="margin-top: 20px;">
                                    <label for="nomeTrabalho" class="col-form-label"><strong>Trabalho corrigido e/ou com comentários (opcional):</strong> </label>

                                    <div class="custom-file">
                                      <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="arquivo" accept=".pdf, .odt, .docx, .rtf">
                                    </div>
                                    <small><strong>Extensão de arquivos aceitas:</strong>
                                      <span> / ".pdf"</span>
                                      <span> / ".docx"</span>
                                      <span> / ".odt"</span>
                                      <span> / ".rtf"</span>
                                    </small>
                                    @error('arquivo')
                                    <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                                      <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                  </div>
                                @endif
                            </div>
                            <br>

                            <div class="row justify-content-center">
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-secondary" onclick="window.location='{{ route('revisor.index') }}'" style="width:100%">Cancelar</button>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary" style="width:100%" id="submeterFormBotao">
                                        {{ __('Enviar avaliação') }}
                                    </button>
                                </div>
                            </div>

                        </form>
                    </p>

                    </div>
                </div>

            @empty
                <h4>Não há formulário para ser respondido</h4>
            @endforelse
        </div>
    </div>

</div>


@endsection

{{-- <div class="row">
    <div class="col-md-12">
        <div id="coautores" class="flexContainer " >
            <div class="item card" style="order:1">
                <div class="row card-body">
                    <div class="col-sm-12">
                        <label>Pergunta</label>
                        <input type="text" syle="margin-bottom:10px"   class="form-control " name="pergunta[]" value="{{$pergunta}}" required>
                    </div>
                    <div class="col-sm-8" >
                        <label>Resposta</label>
                        <div class="row" id="row1">
                            <div class="col-md-12">
                                <input type="text" style="margin-bottom:10px"  class="form-control " name="resposta[]" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">Tipo</label>
                            <select onchange="escolha(this.value)" name="tipo[]" class="form-control" id="FormControlSelect">
                                <option value="paragrafo">Parágrafo</option>
                                <option value="checkbox">Múltipla escolha</option>

                            </select>
                        </div>
                    </div>
                    <div class="col-md-5"></div>
                    <div class="col-sm-7">
                        <a href="#" class="delete pr-2 mr-2">
                            <i class="fas fa-trash-alt fa-2x"></i>
                        </a>
                        <a href="#" onclick="myFunction(event)">
                        <i class="fas fa-arrow-up fa-2x" id="arrow-up" style=""></i>
                        </a>
                        <a href="#" onclick="myFunction(event)">
                        <i class="fas fa-arrow-down fa-2x" id="arrow-down" style="margin-top:35px"></i>
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}
