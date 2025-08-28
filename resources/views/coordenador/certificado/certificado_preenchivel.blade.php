<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        @page { margin: 0; }

        .container {
            position: absolute;
            width: 1118px;
            height: 790px;
            border-style: solid;
            background-size: 100% 100%
        }

        #texto,#data {
            position: absolute;
        }

        #texto {
            text-align: justify;
        }

        .page_break { page-break-before: always; }

        @font-face {
            font-family: 'Friends';
            src: url('{{ public_path('fonts/friends/TTF/Friends-Normal.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'Friends';
            src: url('{{ public_path('fonts/friends/TTF/Friends-Black.ttf') }}') format('truetype');
            font-weight: bold;
            font-style: normal;
        }

        @font-face {
            font-family: 'Friends';
            src: url('{{ public_path('fonts/friends/TTF/Friends-Italic.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: italic;
        }

        @font-face {
            font-family: 'Friends';
            src: url('{{ public_path('fonts/friends/TTF/Friends-BlackItalic.ttf') }}') format('truetype');
            font-weight: bold;
            font-style: italic;
        }

        @font-face {
            font-family: 'Friends';
            src: url('{{ public_path('fonts/friends/TTF/Friends-SemiBold.ttf') }}') format('truetype');
            font-weight: 600;
            font-style: normal;
        }

        @font-face {
            font-family: 'Friends';
            src: url('{{ public_path('fonts/friends/TTF/Friends-SemiBoldItalic.ttf') }}') format('truetype');
            font-weight: 600;
            font-style: italic;
        }

        @font-face {
            font-family: 'Friends';
            src: url('{{ public_path('fonts/friends/TTF/Friends-Thin.ttf') }}') format('truetype');
            font-weight: 100;
            font-style: normal;
        }

        @font-face {
            font-family: 'Friends';
            src: url('{{ public_path('fonts/friends/TTF/Friends-ThinItalic.ttf') }}') format('truetype');
            font-weight: 100;
            font-style: italic;
        }

        @font-face {
            font-family: 'Friends';
            src: url('{{ public_path('fonts/friends/TTF/Friends-UltraBold.ttf') }}') format('truetype');
            font-weight: 800;
            font-style: normal;
        }

        @font-face {
            font-family: 'Friends';
            src: url('{{ public_path('fonts/friends/TTF/Friends-UltraBoldItalic.ttf') }}') format('truetype');
            font-weight: 800;
            font-style: italic;
        }

        @font-face {
            font-family: 'Friends';
            src: url('{{ public_path('fonts/friends/TTF/Friends-UltraLight.ttf') }}') format('truetype');
            font-weight: 200;
            font-style: normal;
        }

        @font-face {
            font-family: 'Friends';
            src: url('{{ public_path('fonts/friends/TTF/Friends-UltraLightItalic.ttf') }}') format('truetype');
            font-weight: 200;
            font-style: italic;
        }

        @font-face {
            font-family: 'Friends';
            src: url('{{ public_path('fonts/friends/TTF/Friends-NormalItalic.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: italic;
        }

        @font-face {
            font-family: 'Anchor';
            src: url('{{ public_path('fonts/anchor.ttf') }}') format('truetype');
            font-weight: medium;
            font-style: normal;
        }
    </style>
</head>
    @php
        function extractFontFamily($html) {
            if (preg_match('/font-family:\s*([^;"]+)/i', $html, $matches)) {
                return trim($matches[1]);
            }
            return null;
        }
        $fontf = extractFontFamily($texto);
    @endphp
    <body style="font-family: '{{$fontf}}'">
        @php
            $tipos = App\Models\Submissao\Medida::TIPO_ENUM;
        @endphp
        <div class="container" style="background-image: url({{ storage_path('/app/public/'.$certificado->caminho) }});">
            <div id="texto" style="
                left: {{$certificado->medidas->where("tipo", $tipos["texto"])->first()->x}}px;
                font-size: {{$certificado->medidas->where("tipo", $tipos["texto"])->first()->fontSize}}px;
                top:{{$certificado->medidas->where("tipo", $tipos["texto"])->first()->y}}px;
                width: {{$certificado->medidas->where("tipo", $tipos["texto"])->first()->largura}}px;">
                {!!
                    str_replace(
                        ['%AUTOR%', '%NOME_PESSOA%', '%TITULO_TRABALHO%', '%NOME_EVENTO%', '%TITULO_PALESTRA%', '%CPF%', '%NOME_COMISSAO%', '%COAUTORES%', '%NOME_ATIVIDADE%', '%CARGA_HORARIA%'],
                        [$trabalho->autor->name ?? "VARIAVEL INDEFINIDA", $user->name ?: $user->nome,$trabalho->titulo ?? "VARIAVEL INDEFINIDA", $evento->nome, $palestra->titulo ?? 'VARIAVEL INDEFINIDA', $user->cpf, $comissao->nome ?? 'VARIAVEL INDEFINIDA', $coautores ?? '', $atividade->titulo ?? 'VARIAVEL INDEFINIDA', $atividade->carga_horaria ?? 'VARIABLE INDEFINIDA'],
                        $texto
                    )
                !!}
            </div>
            <div id="data" style="
                left: {{$certificado->medidas->where("tipo", $tipos["data"])->first()->x}}px;
                font-size: {{$certificado->medidas->where("tipo", $tipos["data"])->first()->fontSize}}px;
                top: {{$certificado->medidas->where("tipo", $tipos["data"])->first()->y}}px;
                width: {{$certificado->medidas->where("tipo", $tipos["data"])->first()->largura}}px;">
                {{ $certificado->local }}, {{ $dataHoje }}
            </div>
            @if(!$certificado->imagem_assinada)
                @foreach ($certificado->assinaturas as $assinatura)
                    @php
                        $medida = $certificado->medidas->where('tipo', $tipos["imagem_assinatura"])->where('assinatura_id', $assinatura->id)->first();
                    @endphp
                    <img id="data"
                         src="{{ storage_path('/app/public/'.$assinatura->caminho)}}"
                         style="
                    left: {{$medida->x}}px;
                    top: {{$medida->y}}px;
                    width: {{$medida->largura}}px;
                    padding-bottom: 5px;
                    @if($certificado->medidas->where('tipo', $tipos["linha_assinatura"])->where('assinatura_id', $assinatura->id)->first() == null)
                    border-bottom: 2px solid black;
                    @endif
                    "
                    >
                    @php
                        $medida = $certificado->medidas->where('tipo', $tipos["nome_assinatura"])->where('assinatura_id', $assinatura->id)->first();
                    @endphp
                    <div id="data" style="
                    left: {{$medida->x}}px;
                    font-size: {{$medida->fontSize}}px;
                    top: {{$medida->y}}px;
                    width: {{$medida->largura}}px;">
                        {{ $assinatura->nome }}
                    </div>
                    @php
                        $medida = $certificado->medidas->where('tipo', $tipos["linha_assinatura"])->where('assinatura_id', $assinatura->id)->first();
                    @endphp
                    @if($medida != null)
                        <div id="data" style="
                        left: {{$medida->x}}px;
                        top: {{$medida->y}}px;
                        width: {{$medida->largura}}px;
                        background-color: blue;
                        border-bottom: 2px solid black;">
                        </div>
                    @endif
                    @php
                        $medida = $certificado->medidas->where('tipo', $tipos["cargo_assinatura"])->where('assinatura_id', $assinatura->id)->first();
                    @endphp
                    <div id="data" style="
                    left: {{$medida->x}}px;
                    font-size: {{$medida->fontSize}}px;
                    top: {{$medida->y}}px;
                    width: {{$medida->largura}}px;">
                        {{ $assinatura->cargo }}
                    </div>
                @endforeach
            @endif
        @if ($certificado->verso)
            </div>

            <div class="page_break"></div>
            @if ($certificado->has_imagem_verso)
                <div class="container" style="background-image: url({{ storage_path('/app/public/'.$certificado->imagem_verso) }});">
            @else
                <div class="container" style="background-image: url({{ storage_path('/app/public/'.$certificado->caminho) }});">
            @endif
        @endif
            @php
                $medida = $certificado->medidas->where('tipo', $tipos["qrcode"])->first();
            @endphp
            <img style="
                position: absolute;
                left: {{$medida->x}}px;
                top: {{$medida->y}}px;
                width: {{$medida->largura}}px;"
                src="data:image/png;base64, {{ $qrcode }}">
            @php
                $medida = $certificado->medidas->where('tipo', $tipos["hash"])->first();
            @endphp
            <p style="
                position: absolute;
                left: {{$medida->x}}px;
                font-size: {{$medida->fontSize}}px;
                top: {{$medida->y}}px;
                width: {{$medida->largura}}px;">
                Código para validação do certificado: <br>
                {{$validacao}}
            </p>
            @php
                $medida = $certificado->medidas->where('tipo', $tipos["emissao"])->first();
            @endphp
            <p style="
                position: absolute;
                left: {{$medida->x}}px;
                font-size: {{$medida->fontSize}}px;
                top: {{$medida->y}}px;
                width: {{$medida->largura}}px;">
                Certificado emitido pela plataforma {{config('app.name')}} em {{ $now }}
            </p>
            @php
                $medida = $certificado->medidas->where('tipo', $tipos["logo"])->first();
            @endphp
            <img style="
                position: absolute;
                left: {{$medida->x}}px;
                top: {{$medida->y}}px;
                width: {{$medida->largura}}px;"
                src="img/logo-icone.png">
        </div>
    </body>
</html>
