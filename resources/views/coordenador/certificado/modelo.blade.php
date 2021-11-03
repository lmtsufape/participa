<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style type="text/css">
        body{
            background-image: url({{('storage/'.$certificado->caminho)}});
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: 100% 100%;
        }
        @page {
            margin: 0px 0px 0px 0px;
        }
        .container {
            text-align: center;
            margin: 0px 60px 0px 60px;
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
            margin-top: 44%;
            font-size: 16px;
            text-align: justify;
        }

        .linha {
            text-align: center;
        }

        .linha .assinatura-img {
            position: relative;
            display: inline-block;
            width: 200px;
        }

        .linha #linha-meio {
            width: 100%;
        }
    </style>

</head>
    <body>

        <div class="container">
            @switch($certificado->tipo)
                @case(\App\Models\Submissao\Certificado::TIPO_ENUM['apresentador'])
                    <p class="texto">Certificamos que <strong>NOME DA PESSOA</strong> apresentou <strong>NOME DA MODALIDADE - NOME DA ÁREA/EIXO,
                    com o trabalho "NOME DO TRABALHO", </strong><span style="font-family:Arial, Helvetica, sans-serif; color: red">{!!$certificado->texto!!}</span></p>
                    @break
                @case(\App\Models\Submissao\Certificado::TIPO_ENUM['comissao_cientifica'])
                    <p class="texto">Certificamos que <strong>NOME DA PESSOA</strong> participou <strong>da COMISSÃO CIENTÍFICA </strong>
                    <span style="font-family:Arial, Helvetica, sans-serif; color: red">{!!$certificado->texto!!}</span></p>
                    @break
                @case(\App\Models\Submissao\Certificado::TIPO_ENUM['comissao_organizadora'])
                    <p class="texto">Certificamos que <strong>NOME DA PESSOA</strong> participou <strong>da COMISSÃO ORGANIZADORA </strong>
                    <span style="font-family:Arial, Helvetica, sans-serif; color: red">{!!$certificado->texto!!}</span></p>
                    @break
                @case(\App\Models\Submissao\Certificado::TIPO_ENUM['revisor'])
                    <p class="texto">Certificamos que <strong>NOME DA PESSOA</strong> participou como <strong>avaliador(a)</strong> de trabalhos na
                    <strong> COMISSÃO CIENTÍFICA </strong><span style="font-family:Arial, Helvetica, sans-serif; color: red">{!!$certificado->texto!!}</span></p>
                    @break
                @case(\App\Models\Submissao\Certificado::TIPO_ENUM['participante'])
                    <p class="texto">Certificamos que <strong>NOME DA PESSOA</strong> participou
                    <span style="font-family:Arial, Helvetica, sans-serif; color: red">{!!$certificado->texto!!}</span></p>
                    @break
                @case(\App\Models\Submissao\Certificado::TIPO_ENUM['expositor'])
                    <p class="texto">Certificamos que <strong>NOME DA PESSOA</strong> participou como expositor(a) da <strong>NOME DA MODALIDADE - NOME DA ÁREA/EIXO,
                    </strong><span style="font-family:Arial, Helvetica, sans-serif; color: red">{!!$certificado->texto!!}</span></p>
                    @break
                @default

            @endswitch

            <p class="texto"  style="text-align: right; margin-top: 0%;">{{$certificado->local}}, {{$dataHoje}}.</p>
        </div>
        <div style="position: absolute; bottom: 10%; left:50%; margin-left:-180px;">
            <table>
                <tbody>
                    <tr>
                        @foreach ($certificado->assinaturas as $assinatura)
                            <td>
                                <div class="linha">
                                    <img class="assinatura-img" src="{{asset('./storage/'.$assinatura->caminho)}}" ><br>
                                </div>
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($certificado->assinaturas as $i => $assinatura)
                            <td>
                                <div class="linha">
                                    <hr id="linha-meio">
                                    <p class="assinatura-nome" style="font-size: 16px; font-family:Arial, Helvetica, sans-serif;" >{{$assinatura->nome}}<br>{{$assinatura->cargo}}</p>
                                </div>
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>
