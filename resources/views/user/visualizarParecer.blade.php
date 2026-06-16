@extends('layouts.app')

@section('content')

<div class="container content">
    {{-- titulo da página --}}
    <div class="row justify-content-center titulo-detalhes">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-12">
                    <h1>{{$trabalho->titulo}}</h1>
                </div>
            </div>
        </div>
    </div>
    @foreach ($modalidade->forms as $form)
        <div class="card" style="width: 48rem;">
            <div class="card-body">
                <h5 class="card-title">{{$form->titulo}}</h5>

                <div class="card-text">
                    @foreach ($form->perguntas as $index => $pergunta)
                        @if($pergunta->visibilidade == true)
                            
                            @if($pergunta->respostas->first() && $pergunta->respostas->first()->opcoes && $pergunta->respostas->first()->opcoes->count())
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <p><strong>{!! $pergunta->pergunta !!}</strong></p>
                                        @foreach ($pergunta->respostas->first()->opcoes as $opcao)
                                            <div class="form-check">
                                                @if ($respostas[$index] != null && $respostas[$index]->opcoes != null && $respostas[$index]->opcoes->pluck('titulo')->contains($opcao->titulo))
                                                    <input class="form-check-input" type="radio" name="{{$pergunta->id}}_{{$index}}" checked value="{{$opcao->titulo}}" id="{{$opcao->id}}" disabled>
                                                @else
                                                    <input class="form-check-input" type="radio" name="{{$pergunta->id}}_{{$index}}" value="{{$opcao->titulo}}" id="{{$opcao->id}}" disabled>
                                                @endif
                                                <label class="form-check-label" for="{{$opcao->id}}">
                                                    {{$opcao->titulo}}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                            @elseif($pergunta->respostas->first() && $pergunta->respostas->first()->paragrafo)
                                @forelse ($pergunta->respostas as $resposta)
                                    @if(($resposta->revisor != null || $resposta->trabalho != null) && $resposta->revisor_id == $revisor->id)
                                        @if ($resposta->trabalho_id == $trabalho->id)
                                            <div class="card mb-3">
                                                <div class="card-body">
                                                    <p><strong>{!! $pergunta->pergunta !!}</strong></p>
                                                    <div class="card-text">
                                                        <p>{{$resposta->paragrafo->resposta}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                @empty
                                    <p class="text-muted">Sem respostas</p>
                                @endforelse
                            @endif

                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach

    @if ($arquivoAvaliacao != null)
        <a class="btn btn-primary mt-3" href="{{route('downloadAvaliacao', ['trabalhoId' => $trabalho->id, 'revisorUserId' => $revisorUser->id])}}">
            <div class="btn-group">
                <img src="{{asset('img/icons/file-download-solid.svg')}}" style="width:15px">
                <h6 style="margin-left: 5px; margin-top:1px; margin-bottom: 1px;">Baixar trabalho corrigido</h6>
            </div>
        </a>
    @endif
</div>

@endsection