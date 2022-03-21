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
        @page { margin: 0; }
        .container {
            text-align: center;
            margin: 2cm 2cm 0px 2cm;
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
        @php
            $indice = 0;
            $indice2 = 0;
            $quantAssinaturas = $certificado->assinaturas->count();
        @endphp
        @while ($quantAssinaturas > 0)
            @php
                if($quantAssinaturas >= 3)
                    $comeco = 1.3;
                elseif($quantAssinaturas == 2) {
                    $comeco = 6.7;
                }else{
                    $comeco = 17.4;
                }
                $esquerda = $quantAssinaturas*$comeco + $comeco;
            @endphp
            <div style="position: absolute; bottom: 8%; left:{{$esquerda}}%;"> 
                <table>
                    <tbody>
                        <tr>
                            @foreach ($certificado->assinaturas as $i => $assinatura)
                                @if($i == $indice)
                                    <td>
                                        <div class="linha" style="margin-left: 60px;">
                                            <img class="assinatura-img" style="top: 33px;" src="{{ storage_path('/app/public/'.$assinatura->caminho)}}" ><br>
                                        </div>
                                    </td>
                                    @php
                                        $indice += 1;
                                        $quantAssinaturas -= 1;
                                    @endphp
                                    @if($i+1 >= 3 || $quantAssinaturas == 0)
                                        @break;
                                    @endif
                                @endif
                            @endforeach
                        </tr>
                        <tr>
                            @foreach ($certificado->assinaturas as $j => $assinatura)
                                @if($j == $indice2)
                                    @php
                                        $indice2 += 1;
                                    @endphp
                                    <td>
                                        <div class="linha" style="margin-left: 60px;">
                                            <hr id="linha-meio">
                                            <p class="assinatura-nome" style="max-width:250px; max-height:250px; font-size: 12px; font-family:Arial, Helvetica, sans-serif;" >{{$assinatura->nome}}</p>
                                            <p class="assinatura-nome" style="max-width:200px; max-height:200px; font-size: 12px; font-family:Arial, Helvetica, sans-serif; margin-left: 23px; margin-top: -12px;">{{$assinatura->cargo}}</p>
                                        </div>
                                    </td>
                                    @if($j+1 >= 3)
                                        @break;
                                    @endif
                                @endif
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        @endwhile
    </body>
</html>
