<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style type="text/css">
        body{
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: 100% 100%;
        }
        @page {
            margin: 2cm 2cm 0px 2cm;
        }
        .container {
            text-align: center;
            margin: 0px 0px 0px 0px;
        }
        .certificado-texto {
            color: rgb(15, 3, 85);
            position: relative;
            margin-top: 180px;
        }
        .texto {
            font-family:Arial, Helvetica, sans-serif;
            color: rgb(12, 14, 13);
            position: relative;
            margin-top: 34%;
            font-size: 16px;
            text-align: justify;
        }

        .linha {
            text-align: center;
            margin-right: 15px;
        }

        .linha .assinatura-img {
            position: relative;
            display: inline-block;
            width: 200px;
            margin-right: 15px;
        }

        .linha #linha-meio {
            width: 100%;
        }
    </style>

</head>
    <body style="background-image: url({{ storage_path('/app/public/'.$certificado->caminho) }});">

        <div class="container">
            @if ($cargo == 'Apresentador')
                <p class="texto" >Certificamos que <span style="text-transform:uppercase"><strong>{{$user->name}}</strong></span> apresentou {{$trabalho->modalidade->nome}} - {{$trabalho->evento->formSubTrab->etiquetaareatrabalho}}:<strong> {{$trabalho->area->nome}},
                </strong>com o trabalho <span style="text-transform:uppercase"><strong>"{{$trabalho->titulo}}"</strong></span></strong></strong><span style="font-family:Arial, Helvetica, sans-serif;">{!!$certificado->texto!!}</span></p>

                <p class="texto"  style="text-align: right; margin-top: 0%;">{{$certificado->local}}, {{$dataHoje}}.</p>
            @elseif($cargo == 'Comissão Científica')
                <p class="texto" >Certificamos que <span style="text-transform:uppercase"><strong>{{$user->name}}</strong></span> participou como membro da </strong>COMISSÃO CIENTÍFICA </strong>
                <span style="font-family:Arial, Helvetica, sans-serif;">{!!$certificado->texto!!}</span></p>

                <p class="texto"  style="text-align: right; margin-top: 0%;">{{$certificado->local}}, {{$dataHoje}}.</p>
            @elseif($cargo == 'Coordenador comissão científica')
                <p class="texto" >Certificamos que <span style="text-transform:uppercase"><strong>{{$user->name}}</strong></span> participou como coordenador/a da <strong>COMISSÃO CIENTÍFICA </strong>
                <span style="font-family:Arial, Helvetica, sans-serif;">{!!$certificado->texto!!}</span></p>

                <p class="texto"  style="text-align: right; margin-top: 0%;">{{$certificado->local}}, {{$dataHoje}}.</p>
            @elseif($cargo == 'Comissão Organizadora')
                <p class="texto" >Certificamos que <span style="text-transform:uppercase"><strong>{{$user->name}}</strong></span> participou como membro da </strong>COMISSÃO ORGANIZADORA </strong>
                <span style="font-family:Arial, Helvetica, sans-serif;">{!!$certificado->texto!!}</span></p>

                <p class="texto"  style="text-align: right; margin-top: 0%;">{{$certificado->local}}, {{$dataHoje}}.</p>
            @elseif($cargo == 'Revisor')
                <p class="texto" >Certificamos que <span style="text-transform:uppercase"><strong>{{$user->name}}</strong></span> participou como avaliador/a de trabalhos na
                 <strong>COMISSÃO CIENTÍFICA </strong><span style="font-family:Arial, Helvetica, sans-serif;">{!!$certificado->texto!!}</span></p>

                <p class="texto"  style="text-align: right; margin-top: 0%;">{{$certificado->local}}, {{$dataHoje}}.</p>
            @elseif($cargo == 'Participante')
                <p class="texto" >Certificamos que <span style="text-transform:uppercase"><strong>{{$user->name}}</strong></span> participou <span style="font-family:Arial, Helvetica, sans-serif;">{!!$certificado->texto!!}</span></p>

                <p class="texto"  style="text-align: right; margin-top: 0%;">{{$certificado->local}}, {{$dataHoje}}.</p>
            @elseif($cargo == 'Expositor')
                <p class="texto" >Certificamos que <span style="text-transform:uppercase"><strong>{{$user->name}}</strong></span> participou como PALESTRANTE da <strong>Mesa de Diálogo</strong> <strong>"{{$trabalho->titulo}}"</strong>, no {{$trabalho->evento->formSubTrab->etiquetaareatrabalho}}: {{$trabalho->area->nome}} <span style="font-family:Arial, Helvetica, sans-serif;">{!!$certificado->texto!!}</span></p>

                <p class="texto"  style="text-align: right; margin-top: 0%;">{{$certificado->local}}, {{$dataHoje}}.</p>
            @else
                <p class="texto" >Certificamos que <span style="text-transform:uppercase"><strong>{{$user->name}}</strong></span> participou do evento  <strong>{{$evento->nome}}</strong>,
                como  <strong>{{$cargo}}</strong> do dia  <strong>{{date('d/m/Y',strtotime($evento->dataInicio))}}</strong> ao dia <strong>{{date('d/m/Y',strtotime($evento->dataFim))}}.</strong></p>

                <p class="texto"  style="text-align: right; margin-top: 0%;">{{$certificado->local}}, {{$dataHoje}}.</p>
            @endif
        </div>
        <div style="position: absolute; bottom: 10%; left: @if($certificado->assinaturas->count() >= 3) 18% @else 38%; @endif ">
            <table>
                <tbody>
                    <tr>
                        @foreach ($certificado->assinaturas as $assinatura)
                            <td>
                                <div class="linha">
                                    <img class="assinatura-img" style="top: 33px;" src="{{ storage_path('/app/public/'.$assinatura->caminho) }}" ><br>
                                </div>
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($certificado->assinaturas as $i => $assinatura)
                            <td>
                                <div class="linha">
                                    <hr id="linha-meio">
                                    <p class="assinatura-nome" style="font-size: 16px; font-family:Arial, Helvetica, sans-serif;"  >{{$assinatura->nome}}<br>{{$assinatura->cargo}}</p>
                                </div>
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>
