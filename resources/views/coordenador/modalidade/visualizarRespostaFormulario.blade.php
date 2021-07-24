@extends('coordenador.detalhesEvento')

@section('menu')

    <div id="divListarCriterio" class="comissao">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="titulo-detalhes"> <strong> {{$trabalho->titulo}}</strong> <br>
                    Modalidade: <strong> {{$modalidade->nome}}</strong><br>
                    Revisor: <strong> {{$revisorUser->name}}</strong><br>
                </h3>
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
                                        @if($resposta->revisor->user_id == $revisorUser->id)

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
            @if ($trabalho->arquivoAvaliacao != null)
                <a href="{{route('downloadAvaliacao', ['trabalhoId' => $trabalho->id, 'revisorUserId' => $revisorUser->id])}}"><img src="{{asset('img/icons/file-download-solid-black.svg')}}" style="width:20px"></a>
            @endif

            </div>
        </div>

    @endforeach


@endsection
