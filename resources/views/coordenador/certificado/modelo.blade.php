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
    <body style="background-image: url({{asset('storage/'.$certificado->caminho)}});">

        <div class="container">
            @switch($certificado->tipo)
                @case(\App\Models\Submissao\Certificado::TIPO_ENUM['apresentador'])
                    <p class="texto">Certificamos que <strong>NOME DA PESSOA</strong> apresentou nome da modalidade - nome da etiqueta do trabalho (área/eixo):<strong> nome da área/eixo,
                    </strong>com o trabalho <strong>"NOME DO TRABALHO"</strong><span style="font-family:Arial, Helvetica, sans-serif; color: red">{!!$certificado->texto!!}</span></p>
                    @break
                @case(\App\Models\Submissao\Certificado::TIPO_ENUM['coordenador_comissao_cientifica'])
                    <p class="texto">Certificamos que <strong>NOME DA PESSOA</strong> participou como coordenador/a da <strong>COMISSÃO CIENTÍFICA </strong>
                    <span style="font-family:Arial, Helvetica, sans-serif; color: red">{!!$certificado->texto!!}</span></p>
                    @break
                @case(\App\Models\Submissao\Certificado::TIPO_ENUM['comissao_cientifica'])
                    <p class="texto">Certificamos que <strong>NOME DA PESSOA</strong> participou como membro da <strong>COMISSÃO CIENTÍFICA </strong>
                    <span style="font-family:Arial, Helvetica, sans-serif; color: red">{!!$certificado->texto!!}</span></p>
                    @break
                @case(\App\Models\Submissao\Certificado::TIPO_ENUM['comissao_organizadora'])
                    <p class="texto">Certificamos que <strong>NOME DA PESSOA</strong> participou <strong>da COMISSÃO ORGANIZADORA </strong>
                    <span style="font-family:Arial, Helvetica, sans-serif; color: red">{!!$certificado->texto!!}</span></p>
                    @break
                @case(\App\Models\Submissao\Certificado::TIPO_ENUM['revisor'])
                    <p class="texto">Certificamos que <strong>NOME DA PESSOA</strong> participou como avaliador/a de trabalhos na
                    <strong>COMISSÃO CIENTÍFICA </strong><span style="font-family:Arial, Helvetica, sans-serif; color: red">{!!$certificado->texto!!}</span></p>
                    @break
                @case(\App\Models\Submissao\Certificado::TIPO_ENUM['participante'])
                    <p class="texto">Certificamos que <strong>NOME DA PESSOA</strong> participou
                    <span style="font-family:Arial, Helvetica, sans-serif; color: red">{!!$certificado->texto!!}</span></p>
                    @break
                @case(\App\Models\Submissao\Certificado::TIPO_ENUM['expositor'])
                    <p class="texto">Certificamos que <strong>NOME DA PESSOA</strong> participou como PALESTRANTE da <strong>Mesa de Diálogo</strong> <strong>"titulo do trabalho"</strong>, no etiqueta do trabalho (área/eixo): <strong>nome da área/eixo</strong>
                    <span style="font-family:Arial, Helvetica, sans-serif; color: red">{!!$certificado->texto!!}</span></p>
                    @break

                @default

            @endswitch

            <p class="texto"  style="text-align: right; margin-top: 0%;">{{$certificado->local}}, {{$dataHoje}}.</p>
        </div>
        <div style="position: absolute; bottom: 10%; left:50%; margin-left:-205px;">
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
