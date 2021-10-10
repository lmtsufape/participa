<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style type="text/css">
        @page {
            margin: 30px 30px 30px 30px;
        }
        .container {
            position: relative;
            text-align: center;
            color: white;
        }
        .certificado {
            position: absolute;
            width: 100%;
            transform: translate(0%, 0%);
        }
        .certificado-texto {
            color: rgb(3, 85, 71);
            position: absolute;
            top: 25%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .texto {
            font-family: 'Times New Roman', Times, serif;
            color: rgb(12, 14, 13);
            position: relative;
            top: 30%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .assinatura-img {
            position: absolute;
            width: 30%;
            bottom: 10%;
        }

        .assinatura-nome {
            color: rgb(1, 17, 14);
            position: absolute;
            bottom: 0%;
        }

        .frame {
            height: 560px; /* Can be anything */
            width: 560px; /* Can be anything */
            position: relative;
            bottom: 20%;
        }
    </style>

</head>
    <body>
        <div class="container">
            <img class="certificado" src="{{asset('storage/'.$certificado->caminho)}}">
            <h1 style="font-size: 40px;" class="certificado-texto" >Certificado </h1><br><br><br>
            <p class="texto" >Certificamos que <strong>{{$user->name}}</strong>  participou do evento  <strong>{{$evento->nome}}</strong>,
            como  <strong>{{$cargo}}</strong> do dia  <strong>{{date('d/m/Y',strtotime($evento->dataInicio))}}</strong> ao dia <strong>{{date('d/m/Y',strtotime($evento->dataFim))}}.</strong></p><br>

        </div>
        @foreach ($certificado->assinaturas as $assinatura)
            <div class="frame">
                <img class="assinatura-img" src="{{asset('storage/'.$assinatura->caminho)}}"><br>
                <p class="assinatura-nome">{{$assinatura->nome}}</p>
                <p class="assinatura-nome">{{$assinatura->cargo}}</p>
            </div>
        @endforeach
    </body>
</html>
