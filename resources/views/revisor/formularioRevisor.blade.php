@extends('layouts.app')

@section('content')

<div class="container mb-4" style="position: relative; top: 80px;">
    <div id="divListarCriterio" class="comissao">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="titulo-detalhes">Formulário(s) da molidade: <br> <strong> {{$data['modalidade']->nome}}</strong> </h3>
            </div>
        </div>
    </div>
    @if(session('message'))
    <div class="row">
        <div class="col-md-12" style="margin-top: 5px;">
            <div class="alert alert-success">
                <p>{{session('message')}}</p>
            </div>
        </div>
    </div>
    @endif
    {{-- {{dd($data['modalidade']->forms)}} --}}
    <div class="row">
        <div class="col-md-12">
            @forelse ($forms as $form)
                <div class="card" style="width: 48rem;">
                    <div class="card-body">
                    <h5 class="card-title">{{$form->titulo}}</h5>

                    <p class="card-text">

                        <form action="{{route('revisor.salvar.respostas')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="revisor_id" value="{{$data['revisor']->id}}">
                            <input type="hidden" name="trabalho_id" value="{{$data['trabalho']->id}}">
                            <input type="hidden" name="modalidade_id" value="{{$data['modalidade']->id}}">
                            <input type="hidden" name="form_id[]" value="{{$form->id}}">
                            @foreach ($form->perguntas as $pergunta)
                                <div class="card">
                                    <div class="card-body">
                                        <p><b>{{$pergunta->pergunta}}</b></p>
                                        {{-- @if(!isset($pergunta->respostas->opcoes))
                                            Resposta com Multipla escolha:
                                        @else --}}
                                                <input type="hidden" name="pergunta_id[]" value="{{$pergunta->id}}">
                                                <p><b>Resposta: </b></p>
                                                <textarea type="text" style="margin-bottom:10px"  class="form-control " name="resposta[]" required></textarea>

                                        {{-- @endif --}}
                                    </div>
                                </div>
                            @endforeach
                            <div class="row justify-content-center">
                                {{-- Submeter Arquivo Avalicao --}}

                                @if ($data['modalidade']->arquivo == true)
                                  <div class="col-sm-12" style="margin-top: 20px;">
                                    <label for="nomeTrabalho" class="col-form-label"><strong>Upload de Avaliação</strong> </label>

                                    <div class="custom-file">
                                      <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="arquivo" required>
                                    </div>
                                    <small><strong>Extensão de arquivos aceitas:</strong>
                                      @if($data['modalidade']->pdf == true)<span> / ".pdf"</span>@endif
                                      @if($data['modalidade']->jpg == true)<span> / ".jpg"</span>@endif
                                      @if($data['modalidade']->jpeg == true)<span> / ".jpeg"</span>@endif
                                      @if($data['modalidade']->png == true)<span> / ".png"</span>@endif
                                      @if($data['modalidade']->docx == true)<span> / ".docx"</span>@endif
                                      @if($data['modalidade']->odt == true)<span> / ".odt"</span>@endif
                                      @if($data['modalidade']->zip == true)<span> / ".zip"</span>@endif
                                      @if($data['modalidade']->svg == true)<span> / ".svg"</span>@endif.
                                    </small>
                                    @error('arquivo')
                                    <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                                      <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                  </div>
                                @endif
                            </div>

                            <div class="row justify-content-center">
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-secondary" onclick="window.location='{{ route('revisor.trabalhos.evento', ['id' => $evento->id]) }}'" style="width:100%">Cancelar</button>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary" style="width:100%">
                                        {{ __('Salvar respostas') }}
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
                                <option value="checkbox">Multipla escolha</option>

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
