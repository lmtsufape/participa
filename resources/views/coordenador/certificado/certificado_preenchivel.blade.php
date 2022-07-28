<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style type="text/css">
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

        .page_break { page-break-before: always; }

    </style>
</head>
    <body>
        @php
            $tipos = App\Models\Submissao\Medida::TIPO_ENUM;
        @endphp
        <div class="container" style="background-image: url({{ storage_path('/app/public/'.$certificado->caminho) }});">
            <div id="texto" style="
                left: {{$certificado->medidas->where("tipo", $tipos["texto"])->first()->x}}px;
                font-size: {{$certificado->medidas->where("tipo", $tipos["texto"])->first()->fontSize}}px;
                top:{{$certificado->medidas->where("tipo", $tipos["texto"])->first()->y}}px;
                width: {{$certificado->medidas->where("tipo", $tipos["texto"])->first()->largura}}px;">
                {!! str_replace(['%NOME_PESSOA%', '%TITULO_TRABALHO%', '%NOME_EVENTO%', '%TITULO_PALESTRA%', '%CPF%', '%NOME_COMISSAO%'], [$user->name ?: $user->nome,$trabalho->titulo ?? "VARIAVEL INDEFINIDA", $evento->nome, $palestra->titulo ?? 'VARIAVEL INDEFINIDA', $user->cpf, $comissao->nome ?? 'VARIAVEL INDEFINIDA'], $certificado->texto) !!}
            </div>
            <div id="data" style="
                left: {{$certificado->medidas->where("tipo", $tipos["data"])->first()->x}}px;
                font-size: {{$certificado->medidas->where("tipo", $tipos["data"])->first()->fontSize}}px;
                top: {{$certificado->medidas->where("tipo", $tipos["data"])->first()->y}}px;
                width: {{$certificado->medidas->where("tipo", $tipos["data"])->first()->largura}}px;">
                {{ $certificado->local }} , {{ $dataHoje }}
            </div>
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
        @if ($certificado->verso)
            </div>

            <div class="page_break"></div>
            <div class="container" style="background-image: url({{ storage_path('/app/public/'.$certificado->caminho) }});">
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
                Certificado emitido pela plataforma Participa em {{ $now }}
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
