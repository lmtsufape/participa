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

            <p class="card-text">

                @foreach ($form->perguntas as $index => $pergunta)
                    @if($pergunta->visibilidade == true)
                        @if($pergunta->respostas->first()->opcoes->count())
                            @if ($respostas[$loop->index]->opcoes()->first()->visibilidade == true)
                                <div class="card">
                                    <div class="card-body">
                                        <p><strong>{{$pergunta->pergunta}}</strong></p>
                                        @foreach ($pergunta->respostas->first()->opcoes as $opcao)
                                            <div class="form-check">
                                                @if ($respostas[$index] != null && $respostas[$index]->opcoes != null && $respostas[$index]->opcoes->pluck('titulo')->contains($opcao->titulo))
                                                    <input class="form-check-input" type="radio" name="{{$pergunta->id}}" checked name="{{$pergunta->id}}[]" value="{{$opcao->titulo}}" id="{{$opcao->id}}" disabled>
                                                @else
                                                    <input class="form-check-input" type="radio" name="{{$pergunta->id}}" name="{{$pergunta->id}}[]" value="{{$opcao->titulo}}" id="{{$opcao->id}}" disabled>
                                                @endif
                                                <label class="form-check-label" for="{{$opcao->id}}">
                                                    {{$opcao->titulo}}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @elseif($pergunta->respostas->first()->paragrafo->count())
                            @forelse ($pergunta->respostas as $resposta)
                                @if(($resposta->revisor != null || $resposta->trabalho != null) && $resposta->revisor_id == $revisor->id)
                                    @if(($resposta->trabalho->id == $trabalho->id) && $resposta->paragrafo->visibilidade == true)
                                        <div class="card">
                                            <div class="card-body">
                                                <p><strong>{{$pergunta->pergunta}}</strong></p>
                                                <p class="card-text">
                                                    <p>{{$resposta->paragrafo->resposta}}</p>
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                                @empty
                                <p>Sem respostas</p>
                            @endforelse
                        @endif

                    @endif

                @endforeach
            </p>

            </div>
        </div>

    @endforeach
    @if ($arquivoAvaliacao != null)
        <a class="btn btn-primary" href="{{route('downloadAvaliacao', ['trabalhoId' => $trabalho->id, 'revisorUserId' => $revisorUser->id])}}">
            <div class="btn-group">
                <img src="{{asset('img/icons/file-download-solid.svg')}}" style="width:15px">
                <h6 style="margin-left: 5px; margin-top:1px; margin-bottom: 1px;">Baixar trabalho corrigido</h6>
            </div>
        </a>
    @endif
</div>

@endsection
