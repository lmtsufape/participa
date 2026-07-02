@extends('layouts.app')

@section('content')

<div class="container mb-4 position-relative">
    <x-admin.content-header title="Formulário de avaliação"
        description="Preencha o formulário abaixo com atenção e responsabilidade."
    />
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3 g-md-0">
                <p class="col-md-3 mb-0 text-muted text-break border-end pe-md-3">
                    Evento
                    <strong class="d-block text-my-primary">
                        {{$evento->nome}}
                    </strong>
                </p>
                <p class="col-md-3 mb-0 text-muted text-break border-end px-md-3">
                    Modalidade
                    <strong class="d-block text-my-primary">
                        {{ $data['modalidade']->nome }}
                    </strong>
                </p>
                <p class="col-md-3 mb-0 text-muted text-break border-end px-md-3">
                    {{$evento->formSubTrab->etiquetaareatrabalho}}
                    <strong class="d-block text-my-primary">
                        {{$data['trabalho']->area->nome}}
                    </strong>
                </p>
                <p class="col-md-3 mb-0 text-muted text-break border-end ps-md-3">
                    {{$evento->formSubTrab->etiquetatitulotrabalho}}
                    <strong class="d-block text-my-primary">
                        {{$data['trabalho']->titulo}}
                    </strong>
                </p>
            </div>
        </div>
    </div>

    @forelse ($forms as $form)
        <div class="card shadow-lg">
            <div class="card-header bg-white py-4 px-4">
                <h3 class="card-title h4 text-my-primary"><strong>{{$form->titulo}}</strong></h3>
                @if ($form->instrucoes)
                    <div class="alert alert-success mt-3 mb-0">
                        <div class="fw-semibold text-success mb-1">
                            Orientações aos(as) avaliadores(as):
                        </div>

                        <div class="text-muted text-break">
                            {!! $form->instrucoes !!}
                        </div>
                    </div>
                @endif
            </div>
            <div class="card-body">
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
                    @if ($data['modalidade']->arquivo == true)
                        <div class="row justify-content-center">
                            {{-- Submeter Arquivo Avalicao --}}

                            <div class="col-sm-12 mt-2">
                                <label for="arquivo" class="form-label"><strong>Trabalho corrigido e/ou com comentários (opcional):</strong> </label>
                                <input type="file" class="form-control" name="arquivo" id="arquivo" accept=".pdf, .odt, .docx, .rtf">

                                <small><strong>Extensão de arquivos aceitas:</strong>
                                    <span> / ".pdf"</span>
                                    <span> / ".docx"</span>
                                    <span> / ".odt"</span>
                                    <span> / ".rtf"</span>
                                </small>
                                @error('arquivo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    @endif

                    <div class="row justify-content-center mt-4">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-secondary w-100" onclick="window.location='{{ route('revisor.index') }}'">Cancelar</button>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary w-100" id="submeterFormBotao">
                                {{ __('Enviar avaliação') }}
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>

    @empty
        <h4>Não há formulário para ser respondido</h4>
    @endforelse
</div>


@endsection
