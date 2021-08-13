@extends('layouts.app')

@section('content')


<div class="container content" style="margin-top: 80px;">
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

                @foreach ($form->perguntas as $pergunta)
                    <div class="card">
                        <div class="card-body">
                            <p><strong>{{$pergunta->pergunta}}</strong></p>
                            @if($pergunta->respostas->first()->opcoes->count())
                                Resposta com Multipla escolha:
                            @elseif($pergunta->respostas->first()->paragrafo->count() )
                                @forelse ($pergunta->respostas as $resposta)
                                    @if($resposta->revisor != null || $resposta->trabalho != null)
                                        @if($resposta->trabalho->id == $trabalho->id)
                                            <p class="card-text">
                                                <p>{{$resposta->paragrafo->resposta}}</p>
                                            </p>
                                        @endif
                                    @endif
                                    @empty
                                    <p>Sem respostas</p>
                                @endforelse
                            @endif
                        </div>
                    </div>

                @endforeach
            </p>

            </div>
        </div>

    @endforeach
    @if ($trabalho->arquivoAvaliacao()->first() !== null)
        <a class="btn btn-primary" href="{{route('downloadAvaliacao', ['trabalhoId' => $trabalho->id, 'revisorUserId' => $revisorUser->id])}}">
            <div class="btn-group">
                <img src="{{asset('img/icons/file-download-solid.svg')}}" style="width:15px">
                <h6 style="margin-left: 5px; margin-top:1px; margin-bottom: 1px;">Baixar trabalho corrigido</h6>
            </div>
        </a>
    @endif
</div>

@endsection
