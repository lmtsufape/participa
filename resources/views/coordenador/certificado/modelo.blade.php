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
            margin: 30px 30px 0px 30px;
        }
        .container {
            text-align: center;
            color: white;
        }
        .certificado-texto {
            color: rgb(15, 3, 85);
            position: relative;
            margin-top: 180px;
        }
        .texto {
            font-family: 'Times New Roman', Times, serif;
            color: rgb(12, 14, 13);
            position: relative;
            margin-top: 70px;
            font-size: 20px;
        }

        .linha {
            text-align: center;
            margin-left: 200px;
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
            <h1 style="font-size: 40px;" class="certificado-texto" >Certificado </h1>
            <p class="texto" >Certificamos que <strong> Nome da pessoa </strong>  participou do evento  <strong> Nome do evento </strong>,
            como  <strong> Cargo da pessoa </strong> do dia  <strong> Datas do eventos </strong></p>

            <p class="texto" style="margin-top: 120px">Garanhuns, {{$dataHoje}}.</p>

        </div>
        <div style="margin-top: 70px;">
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
                                    <p class="assinatura-nome" style="font-size: 20px;" >{{$assinatura->nome}}<br>{{$assinatura->cargo}}</p>
                                </div>
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>
