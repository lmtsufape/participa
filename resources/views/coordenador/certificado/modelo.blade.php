<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://unpkg.com/konva@8.3.5/konva.min.js"></script>
    <style>
        @page { margin: 0; }
        #container, #back{
            background-repeat: no-repeat;
            background-size: 100% 100%;
            width: 1118px;
            height: 790px;
        }

        #container {
            background-image: url({{ "/storage/" .$certificado->caminho }});
        }

        .d-none {
            display: none;
        }
        #btn {
            align-self: center;
            width: 100px;
            height: 50px;

        }

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
    </style>

</head>
<body style="text-align: center;">
        <div id="container"></div>
        @if($certificado->verso)
            @if ($certificado->has_imagem_verso)
                <div id="back" style="margin-top: 10px; background-image: url('/storage/{{$certificado->imagem_verso }}')"></div>
            @else
                <div id="back" style="margin-top: 10px; background-image: url('/storage/{{$certificado->caminho }}')"></div>
            @endif
        @endif
        <button id="btn" onclick="send()">Salvar</button>
        <form id="form" action="{{route('coord.cadastrarmedida')}}" class="d-none" method="POST">
            @csrf
            <input type="text" name="certificado_id" value="{{$certificado->id}}">
            @foreach ($certificado->assinaturas as $assinatura)
                @foreach (['nome', 'imagem', 'cargo', 'linha'] as $objeto)
                    @foreach (['x', 'y', 'largura', 'altura', 'fontSize'] as $medida)
                        <input type="text" name="{{$objeto}}-{{$medida}}-{{$assinatura->id}}" value="0">
                    @endforeach
                @endforeach
            @endforeach
            @foreach (['texto', 'data', 'hash', 'emissao'] as $objeto)
                @foreach (['largura', 'x', 'y', 'fontSize'] as $medida)
                    <input type="text" name="{{$objeto}}-{{$medida}}" value="0">
                @endforeach
            @endforeach
            @foreach (['qrcode', 'logo'] as $objeto)
                @foreach (['x', 'y', 'largura', 'altura'] as $medida)
                    <input type="text" name="{{$objeto}}-{{$medida}}" value="0">
                @endforeach
            @endforeach
        </form>

        <script>
            let MIN_WIDTH = 50;
            //funcao para salvar os componentes dps que são movidos de local pelo mouse
            function applyTransformerLogic(shape) {
                shape.on('transform', (event) => {
                    console.log("Chegou", transformer.getActiveAnchor())
                    if(stage.find('.texto').includes(shape)) {
                        console.log("Texto", shape.getFontSize())
                        if(['top-left', 'top-right', 'bottom-left', 'bottom-right'].includes(transformer.getActiveAnchor()) ) {
                            console.log("diagonal")
                            shape.setAttrs({
                                fontSize: Math.max(shape.fontSize() * shape.scaleX(), 2),
                                width: Math.max(shape.width() * shape.scaleX(), MIN_WIDTH),
                                scaleX: 1,
                                scaleY: 1,
                            });
                        } else if ( ['middle-right', 'middle-left'].includes(transformer.getActiveAnchor()) ) {
                            event.target.setAttrs({
                                width: Math.max(event.target.width() * event.target.scaleX(), MIN_WIDTH),
                                scaleX: 1,
                                scaleY: 1,
                            });
                        }

                    } else {
                        //imagem
                        console.log("Imagem")
                        if( ['top-left', 'top-right', 'bottom-left', 'bottom-right'].includes(transformer.getActiveAnchor()) ) {
                            console.log("diagonal")
                            shape.setAttrs({
                                height: event.target.height() * event.target.scaleY(),
                                width: event.target.width() * event.target.scaleX(),
                                scaleX: 1,
                                scaleY: 1,
                            });
                        } else if ( ['middle-right', 'middle-left'].includes(transformer.getActiveAnchor()) ) {
                            event.target.setAttrs({
                                width: Math.max(event.target.width() * event.target.scaleX(), MIN_WIDTH),
                                scaleX: 1,
                                scaleY: 1,
                            });
                        }
                    }
                })
            }
            stage = new Konva.Stage({
                container: 'container',
                width: 1118,
                height: 790,
                draggable: true,

            });

            layer = new Konva.Layer();

            transformer = new Konva.Transformer({
                padding: 5,
                rotateEnabled: false,
                keepRatio: true,
                enabledAnchors: ['top-left', 'top-right','bottom-left', 'bottom-right', 'middle-right', 'middle-left'],
                draggable: true,
                boundBoxFunc: (oldBox, newBox) => {
                if (newBox.width < MIN_WIDTH) {
                    return oldBox;
                }
                return newBox;
                },
            });

            layer.add(transformer);
            stage.add(layer);

            medidas = @json($medidas);
            medidaDescricao = medidas.find(m => m.tipo == 1);

            if(medidaDescricao === undefined){
                medidaDescricao = {x: 50, y: 300, largura: 1000, fontSize: 18}
            }

            var descricao = new Konva.Text({
                x: parseInt(medidaDescricao.x),
                y: parseInt(medidaDescricao.y),
                width: parseInt(medidaDescricao.largura / 1.2),
                fontSize: medidaDescricao.fontSize,
                text: '{!! html_entity_decode(json_encode(strip_tags($certificado->texto))) !!}',
                draggable: true,
                id: 'texto',
                name: 'texto',
                align: 'justify',
            });

            applyTransformerLogic(descricao);
            layer.add(descricao);
            descricao.on('transform click tap move', (event) => {
            });

            medidaData = medidas.find(m => m.tipo == 2);
            if(medidaData === undefined)
                medidaData = {x: 915, y: 350, largura: 450, fontSize: 16};

            var templocal = @json($certificado->local);
            var tempdata = @json($dataHoje);
            var localdata =  templocal + ', ' + tempdata;
            var local = new Konva.Text({
                x: parseInt(medidaData.x),
                y: parseInt(medidaData.y),
                width: parseInt(medidaData.largura / 1.3),
                fontSize: parseInt(medidaData.fontSize),
                text: localdata.replace(/[\n\r]/g,' ').replace('"', '').replace('"', ''),
                draggable: true,
                name: 'texto',
                id: 'data',
            });
            layer.add(local);
            applyTransformerLogic(local);

            let assinaturas = @json($certificado->assinaturas);
            let posicao_inicial_x;
            if (assinaturas.length > 1) {
                posicao_inicial_x = ((1268 - 100) / assinaturas.length) / assinaturas.length;
            } else {
                posicao_inicial_x = (1268 / 2) - 100;
            }
            let i = 0;

            let mostrar_assinaturas = ! @json($certificado->imagem_assinada);

            if (mostrar_assinaturas) {
                assinaturas.forEach((assinatura, index) => {
                    let assinaturaArray = [];
                    let imageObj = new Image();
                    var imagemAssinatura;
                    imageObj.onload = function () {
                        let medidaImagemAssinatra = medidas.find(m => m.tipo == 5 && m.assinatura.id == assinatura.id);
                        if(medidaImagemAssinatra === undefined) {
                            imagemAssinatura = new Konva.Image({
                                x: posicao_inicial_x + (index * 350) - 100,
                                y: 600,
                                image: imageObj,
                                draggable: true,
                                id: 'imagem' + assinatura.id,
                                scaleX: 0.2,
                                scaleY: 0.2,
                            });
                            imagemAssinatura.setAttrs({
                                height: imagemAssinatura.height() * imagemAssinatura.scaleY(),
                                width: imagemAssinatura.width() * imagemAssinatura.scaleX(),
                                scaleX: 1,
                                scaleY: 1,
                            });
                        } else {
                            imagemAssinatura = new Konva.Image({
                                x: parseInt(medidaImagemAssinatra.x),
                                y: parseInt(medidaImagemAssinatra.y),
                                image: imageObj,
                                draggable: true,
                                id: 'imagem' + assinatura.id,
                                height: parseInt(medidaImagemAssinatra.altura),
                                width: parseInt(medidaImagemAssinatra.largura),
                            });
                        }

                        layer.add(imagemAssinatura);
                        applyTransformerLogic(imagemAssinatura);
                    };
                    //aqui acaba a funcao onload
                    imageObj.src = '/storage/' + assinatura.caminho;

                    //medida da linha da assinatura
                    var medidaLinha = medidas.find(m => m.tipo == 6 && m.assinatura.id == assinatura.id);

                    if(medidaLinha === undefined) {
                        medidaLinha = {x: posicao_inicial_x + (index * 350) - 100, y: 550 + 106, largura: (index * 250) + 256};
                    }
                    let x = parseInt(medidaLinha.x)
                    let y = parseInt(medidaLinha.y)
                    //let sum = parseInt(medidaLinha.sum);
                    let width = parseInt(medidaLinha.largura)
                    let largura = parseInt(medidaLinha.largura);
                    let linha = new Konva.Line({
                        points: [x, y, x + width, y],
                        stroke: 'black',
                        strokeWidth: 2,
                        draggable: true,
                        id: 'linha' + assinatura.id,
                    });
                    layer.add(linha);

                    //cargo
                    var cargo;
                    medidaCargo = medidas.find(m => m.tipo == 4 && m.assinatura.id == assinatura.id);
                    if(medidaCargo === undefined) {
                        medidaCargo = {x: posicao_inicial_x + (index * 350) - 50, y: 682, largura: 500, fontSize: 14}
                    }
                    cargo = new Konva.Text({
                        x: parseInt(medidaCargo.x),
                        y: parseInt(medidaCargo.y),
                        width: parseInt(medidaCargo.largura),
                        text: assinatura.cargo,
                        fontSize: parseInt(medidaCargo.fontSize),
                        fontFamily: 'Arial, Helvetica, sans-serif',
                        draggable: true,
                        id: 'cargo' + assinatura.id,
                        name: 'cargo' + assinatura.id,
                    });
                    //cargo
                    applyTransformerLogic(cargo);
                    layer.add(cargo);

                    //nome da assinatura
                    var assinatura;
                    var medidaAssinatura = medidas.find(m => m.tipo == 3 && m.assinatura.id == assinatura.id);

                    if(medidaAssinatura === undefined) {
                        simpleText = new Konva.Text({
                            x: posicao_inicial_x + (index * 350) + (linha.width() / 2) - 100,
                            y: 666,
                            text: assinatura.nome,
                            fontSize: 12,
                            fontFamily: 'Arial, Helvetica, sans-serif',
                            draggable: true,
                            id: 'nome' + assinatura.id,
                            name: 'texto',
                        });
                        simpleText.setAttrs({
                            x: simpleText.x() - simpleText.width() / 2,
                            width: simpleText.width() * simpleText.scaleX(),
                            scaleX: 1,
                            scaleY: 1,
                        });
                    } else {
                        simpleText = new Konva.Text({
                            x: parseInt(medidaAssinatura.x),
                            y: parseInt(medidaAssinatura.y),
                            text: assinatura.nome,
                            width: parseInt(medidaAssinatura.largura),
                            fontSize: parseInt(medidaAssinatura.fontSize),
                            fontFamily: 'Arial, Helvetica, sans-serif',
                            draggable: true,
                            id: 'nome' + assinatura.id,
                            name: 'texto',
                        });
                    }

                    applyTransformerLogic(simpleText);
                    layer.add(simpleText);
                });
            }

            stage.on('mouseover', function () {
                document.body.style.cursor = 'pointer';
            });

            stage.on('mouseover', function () {
                document.body.style.cursor = 'default';
            });

            stage.on('click tap', function (e) {
                if (e.target === stage) {
                    transformer.nodes([]);
                    return;
                }
                let metaPressed = e.evt.shiftKey || e.evt.ctrlKey || e.evt.metaKey;
                let isSelected = transformer.nodes().indexOf(e.target) >= 0;

                if (!metaPressed && !isSelected) {
                    transformer.nodes([e.target]);

                } else if (metaPressed && isSelected) {
                    let nodes = transformer.nodes().slice();
                    nodes.splice(nodes.indexOf(e.target), 1);
                    transformer.nodes(nodes);

                } else if (metaPressed && !isSelected) {
                    let nodes = transformer.nodes().concat([e.target]);
                    transformer.nodes(nodes);
                } else {
                    return;
                }
            });

            var verso;
            var versoTransformer;

            if(@json($certificado->verso)) {
                verso = new Konva.Stage({
                    container: 'back',
                    width: 1118,
                    height: 790,
                    draggable: true,
                });
                var versoLayer = new Konva.Layer();
                verso.add(versoLayer);

                versoTransformer = new Konva.Transformer({
                    padding: 5,
                    rotateEnabled: false,
                    keepRatio: true,
                    enabledAnchors: ['top-left', 'top-right', 'bottom-left', 'bottom-right'],

                    boundBoxFunc: (oldBox, newBox) => {
                    if (newBox.width < MIN_WIDTH) {
                        return oldBox;
                    }
                    return newBox;
                    },
                });
                } else {
                    verso = stage;
                    versoLayer = layer;
                    versoTransformer = transformer
                }
            versoLayer.add(versoTransformer);
            //medida do hash
            medidaHash = medidas.find(m => m.tipo == 8);
            if(medidaHash === undefined) {
                if(@json($certificado->verso))
                    medidaHash = {x: 74, y: 512, largura: 500, fontSize: 14}
                else
                    medidaHash = {x: 26, y: 742, largura: 500, fontSize: 14}
            }
            var hash = new Konva.Text({
                x: parseInt(medidaHash.x),
                y: parseInt(medidaHash.y),
                width: parseInt(medidaHash.largura),
                fontSize: medidaHash.fontSize,
                text: 'Código para validação do certificado: $2y$10$VN/cOnYHgsW/U5W16uH.Q.MKXDNa.3Z8QqeHl89qjp/TXNLP3yNO6', //exemplo de hash
                draggable: true,
                id: 'hash',
                name: 'texto',
            });
            versoLayer.add(hash);
            //medida da emissao
            medidaEmissao = medidas.find(m => m.tipo == 10);
            if(medidaEmissao === undefined) {
                if(@json($certificado->verso))
                medidaEmissao = {x: 630, y: 522, largura: 500, fontSize: 14}
                else
                medidaEmissao = {x: 700, y: 750, largura: 500, fontSize: 14}
            }
            var options = { year: 'numeric', month: 'long', day: 'numeric' };
            var today  = new Date();
            var emissao = new Konva.Text({
                x: parseInt(medidaEmissao.x),
                y: parseInt(medidaEmissao.y),
                width: parseInt(medidaEmissao.largura),
                fontSize: medidaEmissao.fontSize,
                text: 'Certificado emitido pela plataforma Participa em '+ today.toLocaleDateString("pt-BR", options),
                draggable: true,
                id: 'emissao',
                name: 'texto',
            });
            versoLayer.add(emissao);

            var imageObj = new Image(), qrcode;
            imageObj.onload = function () {
                medidaQrcode = medidas.find(m => m.tipo == 7);
                //medida do qrcode
                if(medidaQrcode === undefined) {
                    if(@json($certificado->verso))
                        medidaQrcode = {x: 175, y: 280, altura: 200, largura: 200}
                    else
                        medidaQrcode = {x: 170, y: 600, altura: 100, largura: 100}

                    qrcode = new Konva.Image({
                        x: medidaQrcode.x,
                        y: medidaQrcode.y,
                        image: imageObj,
                        draggable: true,
                        id: 'qrcode',
                        height: medidaQrcode.altura,
                        width: medidaQrcode.largura,
                    });
                } else {
                    qrcode = new Konva.Image({
                        x: parseInt(medidaQrcode.x),
                        y: parseInt(medidaQrcode.y),
                        image: imageObj,
                        draggable: true,
                        id: 'qrcode',
                        height: parseInt(medidaQrcode.altura),
                        width: parseInt(medidaQrcode.largura),
                    });
                }
                versoLayer.add(qrcode);

                qrcode.on('transform', (event) => {
                    if( ['top-left', 'top-right', 'bottom-left', 'bottom-right'].includes(versoTransformer.getActiveAnchor()) ) {
                        event.target.setAttrs({
                            height: event.target.height() * event.target.scaleY(),
                            width: event.target.width() * event.target.scaleX(),
                            scaleX: 1,
                            scaleY: 1,
                        });
                    }
                });
            };
            imageObj.src = "/img/qrcode.png";

            var logoImageObj = new Image(), logo;
            logoImageObj.onload = function () {
                medidaLogo = medidas.find(m => m.tipo == 9);
                if(medidaLogo === undefined) {
                    if(@json($certificado->verso))
                        medidaLogo = {x: 750, y: 280, altura: 200, largura: 200}
                    else
                        medidaLogo = {x: 900, y: 600, altura: 100, largura: 100}

                    logo = new Konva.Image({
                        x: medidaLogo.x,
                        y: medidaLogo.y,
                        image: logoImageObj,
                        draggable: true,
                        id: 'logo',
                        height: medidaLogo.altura,
                        width: medidaLogo.largura,
                    });
                } else {
                    logo = new Konva.Image({
                        x: parseInt(medidaLogo.x),
                        y: parseInt(medidaLogo.y),
                        image: logoImageObj,
                        draggable: true,
                        id: 'logo',
                        height: parseInt(medidaLogo.altura),
                        width: parseInt(medidaLogo.largura),
                    });
                }
                versoLayer.add(logo);
                logo.on('transform', (event) => {
                    if( ['top-left', 'top-right', 'bottom-left', 'bottom-right'].includes(versoTransformer.getActiveAnchor()) ) {
                        event.target.setAttrs({
                            height: event.target.height() * event.target.scaleY(),
                            width: event.target.width() * event.target.scaleX(),
                            scaleX: 1,
                            scaleY: 1,
                        });
                    }
                });
            };
            logoImageObj.src = "/img/logo-icone.png";

            hash.on('transform', (event) => {
                if( ['top-left', 'top-right', 'bottom-left', 'bottom-right'].includes(versoTransformer.getActiveAnchor()) ) {
                    hash.setAttrs({
                        fontSize: Math.max(hash.fontSize() * hash.scaleX(), 2),
                        width: Math.max(hash.width() * hash.scaleX(), MIN_WIDTH),
                        scaleX: 1,
                        scaleY: 1,
                    });
                } else if ( ['middle-right', 'middle-left'].includes(transformer.getActiveAnchor()) ) {
                    event.target.setAttrs({
                        width: Math.max(event.target.width() * event.target.scaleX(), MIN_WIDTH),
                        scaleX: 1,
                        scaleY: 1,
                    });
                }
            });

            emissao.on('transform', (event) => {
                if( ['top-left', 'top-right', 'bottom-left', 'bottom-right'].includes(versoTransformer.getActiveAnchor()) ) {
                    emissao.setAttrs({
                        fontSize: Math.max(emissao.fontSize() * emissao.scaleX(), 2),
                        width: Math.max(emissao.width() * emissao.scaleX(), MIN_WIDTH),
                        scaleX: 1,
                        scaleY: 1,
                    });
                } else if ( ['middle-right', 'middle-left'].includes(transformer.getActiveAnchor()) ) {
                    event.target.setAttrs({
                        width: Math.max(event.target.width() * event.target.scaleX(), MIN_WIDTH),
                        scaleX: 1,
                        scaleY: 1,
                    });
                }
            });
            verso.on('click tap', function (e) {
                if (e.target === verso) {
                    versoTransformer.nodes([]);
                    return;
                }
                let metaPressed = e.evt.shiftKey || e.evt.ctrlKey || e.evt.metaKey;
                let isSelected = versoTransformer.nodes().indexOf(e.target) >= 0;

                if (!metaPressed && !isSelected) {
                    if(verso.find('.texto').includes(e.target)) {
                        versoTransformer.nodes([e.target]);
                    } else {
                        versoTransformer.nodes([e.target]);
                    }
                } else if (metaPressed && isSelected) {
                    let nodes = versoTransformer.nodes().slice();
                    nodes.splice(nodes.indexOf(e.target), 1);
                    versoTransformer.nodes(nodes);
                } else if (metaPressed && !isSelected) {
                    if(verso.find('.texto').includes(e.target)) {
                        let nodes = versoTransformer.nodes().concat([e.target]);
                        versoTransformer.nodes(nodes);
                    } else {
                        let nodes = versoTransformer.nodes().concat([e.target]);
                        versoTransformer.nodes(nodes);
                    }
                } else {
                    return;
                }
                });

            function send() {
                let xGlobal = (stage.attrs.x == undefined)?0:stage.attrs.x;
                let yGlobal = (stage.attrs.y == undefined)?0:stage.attrs.y;

                let xGlobalVerso = (verso.attrs.x == undefined)?0:verso.attrs.x;
                let yGlobalVerso = (verso.attrs.y == undefined)?0:verso.attrs.y;
                if (mostrar_assinaturas) {
                    ['nome','cargo'].forEach(objeto => {
                        assinaturas.forEach(assinatura => {
                            let box = stage.find('#'+objeto+''+assinatura.id);
                            document.querySelectorAll("input[name="+objeto+"-x-"+assinatura.id+"]")[0].value = box[0].attrs.x + xGlobal;
                            document.querySelectorAll("input[name="+objeto+"-y-"+assinatura.id+"]")[0].value = box[0].attrs.y + yGlobal;
                            document.querySelectorAll("input[name="+objeto+"-largura-"+assinatura.id+"]")[0].value = box[0].attrs.width;
                            document.querySelectorAll("input[name="+objeto+"-fontSize-"+assinatura.id+"]")[0].value = box[0].attrs.fontSize;
                        });
                    });
                    ['imagem'].forEach(objeto => {
                        assinaturas.forEach(assinatura => {
                            let box = stage.find('#' + objeto + '' + assinatura.id);
                            document.querySelectorAll("input[name="+objeto+"-x-"+assinatura.id+"]")[0].value = box[0].attrs.x + xGlobal;
                            document.querySelectorAll("input[name="+objeto+"-y-"+assinatura.id+"]")[0].value = box[0].attrs.y + yGlobal;
                            document.querySelectorAll("input[name="+objeto+"-largura-"+assinatura.id+"]")[0].value = box[0].attrs.width;
                            document.querySelectorAll("input[name="+objeto+"-altura-"+assinatura.id+"]")[0].value = box[0].attrs.height;
                        });
                    });
                    assinaturas.forEach(assinatura => {
                        let box = stage.find('#linha'+assinatura.id);
                        document.querySelectorAll("input[name=linha-x-"+assinatura.id+"]")[0].value = box[0].position().x + box[0].points()[0] + xGlobal;
                        document.querySelectorAll("input[name=linha-y-"+assinatura.id+"]")[0].value = box[0].position().y + box[0].points()[1] + yGlobal;
                        document.querySelectorAll("input[name=linha-largura-"+assinatura.id+"]")[0].value = box[0].attrs.points[2] - box[0].attrs.points[0];
                    });
                }
                ['texto','data'].forEach(objeto => {
                    let box = stage.find('#'+objeto);
                    document.querySelectorAll("input[name="+objeto+"-x]")[0].value = box[0].attrs.x + xGlobal;
                    document.querySelectorAll("input[name="+objeto+"-y]")[0].value = box[0].attrs.y + yGlobal;
                    document.querySelectorAll("input[name="+objeto+"-largura]")[0].value = box[0].attrs.width;
                    document.querySelectorAll("input[name="+objeto+"-fontSize]")[0].value = box[0].attrs.fontSize;
                });
                let qrcode = verso.find('#qrcode');
                document.querySelectorAll("input[name=qrcode-x]")[0].value = qrcode[0].attrs.x + xGlobalVerso;
                document.querySelectorAll("input[name=qrcode-y]")[0].value = qrcode[0].attrs.y + yGlobalVerso;
                document.querySelectorAll("input[name=qrcode-largura]")[0].value = qrcode[0].attrs.width;
                document.querySelectorAll("input[name=qrcode-altura]")[0].value = qrcode[0].attrs.height;

                document.querySelectorAll("input[name=hash-x]")[0].value = hash.attrs.x + xGlobalVerso;
                document.querySelectorAll("input[name=hash-y]")[0].value = hash.attrs.y + yGlobalVerso;
                document.querySelectorAll("input[name=hash-largura]")[0].value = hash.attrs.width;
                document.querySelectorAll("input[name=hash-fontSize]")[0].value = hash.attrs.fontSize;

                let logo = verso.find('#logo');
                document.querySelectorAll("input[name=logo-x]")[0].value = logo[0].attrs.x + xGlobalVerso;
                document.querySelectorAll("input[name=logo-y]")[0].value = logo[0].attrs.y + yGlobalVerso;
                document.querySelectorAll("input[name=logo-largura]")[0].value = logo[0].attrs.width;
                document.querySelectorAll("input[name=logo-altura]")[0].value = logo[0].attrs.height;

                document.querySelectorAll("input[name=emissao-x]")[0].value = emissao.attrs.x + xGlobalVerso;
                document.querySelectorAll("input[name=emissao-y]")[0].value = emissao.attrs.y + yGlobalVerso;
                document.querySelectorAll("input[name=emissao-largura]")[0].value = emissao.attrs.width;
                document.querySelectorAll("input[name=emissao-fontSize]")[0].value = emissao.attrs.fontSize;

                document.getElementById("form").submit();
            }
        </script>
    </body>
</html>
