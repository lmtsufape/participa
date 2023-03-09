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
            background-image: url({{ "/storage/" .$certificado->caminho }});
            background-repeat: no-repeat;
            background-size: 100% 100%;
            width: 1118px;
            height: 790px;
            
        }
        .d-none {
            display: none;
        }
    </style>

</head>
<body style="text-align: center;">
        <div id="container"></div>
        @if($certificado->verso)
            <div id="back" style="margin-top: 10px"></div>
        @endif
        <button onclick="send()">Salvar</button>
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
            stage = new Konva.Stage({
                container: 'container',
                width: 1118,
                height: 790,
                draggable: true,
            });
            
            layer = new Konva.Layer();
            stage.add(layer);
            
            //funcao para salvar os componentes dps que são movidos de local pelo mouse
            function applyTransformerLogic(shape) {
                shape.on('transform', (event) => {
                    if(['top-left', 'top-right', 'bottom-left', 'bottom-right'].includes(shape.getStage().getActiveTransformer().getActiveAnchor()) ) {
                        shape.setAttrs({
                            fontSize: Math.max(shape.fontSize() * shape.scaleX(), 2),
                            width: Math.max(shape.width() * shape.scaleX(), MIN_WIDTH),
                            scaleX: 1,
                            scaleY: 1,
                        });
                    } else if ( ['midle-right', 'middle-left'].includes(shape.getStage().getActiveTransformer().getActiveAnchor()) ) {
                        shape.setAttrs({
                            width: Math.max(shape.width() * shape.scaleX(), MIN_WIDTH),
                            scaleX: 1,
                            scaleY: 1,
                        });
                    }
                })
            }
            
            medidas = @json($medidas);
            medida = medidas.find(m => m.tipo == 1);
            console.log(medidas);
            if(medida === undefined){
                medida = {x: 50, y: 300, largura: 1000, fontSize: 14}
                
            } else {
                var textoCertificado = '{!! json_encode($certificado->texto) !!}';
                console.log(textoCertificado);
                let inicio = textoCertificado.search(':');
                let fim = textoCertificado.search('px');
                let valor = textoCertificado.slice(inicio+1, fim)
                valor = parseInt(valor);
                medida.fontSize = valor;
            }
            imagemTransformer = new Konva.Transformer({
                keepRatio: true,
                enabledAnchors: ['top-left', 'top-right', 'bottom-left', 'bottom-right'],
                boundBoxFunc: (oldBox, newBox) => {
                    if (newBox.width < 50) {
                        return oldBox;
                    }
                    return newBox;
                    },
            });
            layer.add(imagemTransformer);
            texto = new Konva.Text({
                x: parseInt(medida.x),
                y: parseInt(medida.y),
                width: parseInt(medida.largura),
                fontSize: medida.fontSize,
                text: '{!! html_entity_decode(json_encode(strip_tags($certificado->texto))) !!}',
                draggable: true,
                id: 'texto',
                name: 'texto',
            });
            
            texto.on('transform click tap move', (event) => {
            });

            //medidas da data
            medida = medidas.find(m => m.tipo == 2);
            if(medida === undefined)
                medida = {x: 915, y: 350, largura: 450, fontSize: 14};
        
            //temp local == local do evento
            templocal = @json($certificado->local);

            //tempdata == dataCertificado
            tempdata = @json($dataHoje);
            localdata =  templocal + ', ' + tempdata;
            local = new Konva.Text({
                x: parseInt(medida.x),
                y: parseInt(medida.y),
                width: parseInt(medida.largura),
                fontSize: parseInt(medida.fontSize),
                text: localdata.replace(/[\n\r]/g,' ').replace('"', '').replace('"', ''),
                draggable: true,
                name: 'texto',
                id: 'data',
            });
            layer.add(local);
            let MIN_WIDTH = 100;
            textoTransformer = new Konva.Transformer({
                padding: 5,
                rotateEnabled: false,
                keepRatio: true,
                enabledAnchors: ['top-left', 'top-right', 'middle-right', 'middle-left', 'bottom-left', 'bottom-right'],
                // enable only side anchors
                // limit transformer size
                boundBoxFunc: (oldBox, newBox) => {
                if (newBox.width < MIN_WIDTH) {
                    return oldBox;
                }
                return newBox;
                },
            });
            layer.add(textoTransformer);
            applyTransformerLogic(texto);
            layer.add(texto);
            applyTransformerLogic(local);
            let assinaturas = @json($certificado->assinaturas);
            let posicao_inicial_x;
            if (assinaturas.length > 1) {
                posicao_inicial_x = ((1268 - 100) / assinaturas.length) / assinaturas.length;
            } else {
                posicao_inicial_x = 1268 / 2 - 100;
            }
            let i = 0;
            assinaturas.forEach((assinatura, index) => {
                let assinaturaArray = [];
                let imageObj = new Image();
                imageObj.onload = function () {
                    //medida da assinatura
                    let medida = medidas.find(m => m.tipo == 5 && m.assinatura.id == assinatura.id);
                    // add the shape to the layer
                    if(medida === undefined) {
                        //imagem da assinatura
                        yoda = new Konva.Image({
                            x: posicao_inicial_x + (index * 350),
                            y: 600,
                            image: imageObj,
                            draggable: true,
                            id: 'imagem' + assinatura.id,
                            scaleX: 0.2,
                            scaleY: 0.2,
                        });
                        yoda.setAttrs({
                            height: yoda.height() * yoda.scaleY(),
                            width: yoda.width() * yoda.scaleX(),
                            scaleX: 1,
                            scaleY: 1,
                        });
                    } else {
                        //medida da imagem da assinatura
                        let medida = medidas.find(m => m.tipo == 5 && m.assinatura.id == assinatura.id);
                        //imagem da assinatura
                        yoda = new Konva.Image({
                            x: parseInt(medida.x),
                            y: parseInt(medida.y),
                            image: imageObj,
                            draggable: true,
                            id: 'imagem' + assinatura.id,
                            height: parseInt(medida.altura),
                            width: parseInt(medida.largura),
                        });
                    }
                    layer.add(yoda);
                    yoda.on('transform', (event) => {
                        // with enabled anchors we can only change scaleX
                        // so we don't need to reset height
                        // just width
                        if( ['top-left', 'top-right', 'bottom-left', 'bottom-right'].includes(imagemTransformer.getActiveAnchor()) ) {
                            event.target.setAttrs({
                                height: event.target.height() * event.target.scaleY(),
                                width: event.target.width() * event.target.scaleX(),
                                scaleX: 1,
                                scaleY: 1,
                            });
                        } else if ( ['middle-right', 'middle-left'].includes(imagemTransformer.getActiveAnchor()) ) {
                            event.target.setAttrs({
                                width: Math.max(event.target.width() * event.target.scaleX(), MIN_WIDTH),
                                scaleX: 1,
                                scaleY: 1,
                            });
                        }
                    });
                };
                imageObj.src = '/storage/' + assinatura.caminho;
                medida = medidas.find(m => m.tipo == 6 && m.assinatura.id == assinatura.id);
                if(medida === undefined) {
                    redLine = new Konva.Line({
                        points: [posicao_inicial_x + (index * 350), 550 + 106, posicao_inicial_x + (index * 350) + 256, 550 + 106],
                        stroke: 'black',
                        strokeWidth: 3,
                        draggable: true,
                        id: 'linha' + assinatura.id,
                    });
                    layer.add(redLine);
                } else {
                    let x = parseInt(medida.x)
                    let y = parseInt(medida.y)
                    let width = parseInt(medida.largura)
                    console.log(x, y, x + width, y)
                    redLine = new Konva.Line({
                        points: [x, y, x + width, y],
                        stroke: 'black',
                        strokeWidth: 2,
                        draggable: true,
                        id: 'linha' + assinatura.id,
                    });
                    layer.add(redLine);
                }

                //medida do cargo
                medida = medidas.find(m => m.tipo == 4 && m.assinatura.id == assinatura.id);
                //simpletext == cargo
                var simpleText;
                if(medida === undefined) {
                    medida = {x: 915, y: 350, largura: 450, fontSize: 14}
                    simpleText = new Konva.Text({
                        x: posicao_inicial_x + (index * 350) + redLine.width() / 2,
                        y: 682,
                        text: assinatura.cargo,
                        fontSize: 12,
                        fontFamily: 'Arial, Helvetica, sans-serif',
                        draggable: true,
                        id: 'cargo' + assinatura.id,
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
                        x: parseInt(medida.x),
                        y: parseInt(medida.y),
                        text: assinatura.cargo,
                        width: parseInt(medida.largura),
                        fontSize: parseInt(medida.fontSize),
                        fontFamily: 'Arial, Helvetica, sans-serif',
                        draggable: true,
                        id: 'cargo' + assinatura.id,
                        name: 'texto',
                    });
                }
                //assinatura
                applyTransformerLogic(simpleText);
                layer.add(simpleText);

                //simpletext == assinatura
                var simpleText;

                //medida da assinatura
                medida = medidas.find(m => m.tipo == 3 && m.assinatura.id == assinatura.id);
                if(medida === undefined) {
                    simpleText = new Konva.Text({
                        x: posicao_inicial_x + (index * 350) + redLine.width() / 2,
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
                        x: parseInt(medida.x),
                        y: parseInt(medida.y),
                        text: assinatura.nome,
                        width: parseInt(medida.largura),
                        fontSize: parseInt(medida.fontSize),
                        fontFamily: 'Arial, Helvetica, sans-serif',
                        draggable: true,
                        id: 'nome' + assinatura.id,
                        name: 'texto',
                    });
                }
               applyTransformerLogic(simpleText);
                layer.add(simpleText);
            });
            stage.on('mouseover', function () {
                document.body.style.cursor = 'pointer';
            });
            stage.on('mouseover', function () {
                document.body.style.cursor = 'default';
            });
            // clicks should select/deselect shapes
            stage.on('click tap', function (e) {
                // if click on empty area - remove all selections
                if (e.target === stage) {
                    imagemTransformer.nodes([]);
                    textoTransformer.nodes([]);
                    return;
                }
                // do we pressed shift or ctrl?
                let metaPressed = e.evt.shiftKey || e.evt.ctrlKey || e.evt.metaKey;
                let isSelected = imagemTransformer.nodes().indexOf(e.target) >= 0;
                if (!metaPressed && !isSelected) {
                    // if no key pressed and the node is not selected
                    // select just one
                    if(stage.find('.texto').includes(e.target)) {
                        textoTransformer.nodes([e.target]);
                    } else {
                        imagemTransformer.nodes([e.target]);
                    }
                } else if (metaPressed && isSelected) {
                    // if we pressed keys and node was selected
                    // we need to remove it from selection:
                    let nodes = imagemTransformer.nodes().slice(); // use slice to have new copy of array
                    // remove node from array
                    nodes.splice(nodes.indexOf(e.target), 1);
                    imagemTransformer.nodes(nodes);
                } else if (metaPressed && !isSelected) {
                    if(stage.find('.texto').includes(e.target)) {
                        let nodes = textoTransformer.nodes().concat([e.target]);
                        textoTransformer.nodes(nodes);
                    } else {
                        let nodes = imagemTransformer.nodes().concat([e.target]);
                        imagemTransformer.nodes(nodes);
                    }
                }
            });
            var stage1;
            var textoTransformer1;
            var imagemTransformer1;
            if(@json($certificado->verso)) {
                stage1 = new Konva.Stage({
                    container: 'back',
                    width: 1118,
                    height: 790,
                });
                var layer1 = new Konva.Layer();
                stage1.add(layer1);
                textoTransformer1 = new Konva.Transformer({
                    padding: 5,
                    rotateEnabled: false,
                    keepRatio: true,
                    enabledAnchors: ['top-left', 'top-right', 'middle-right', 'middle-left', 'bottom-left', 'bottom-right'],
                    // enable only side anchors
                    // limit transformer size
                    boundBoxFunc: (oldBox, newBox) => {
                    if (newBox.width < MIN_WIDTH) {
                        return oldBox;
                    }
                    return newBox;
                    },
                });
                layer1.add(textoTransformer1);
                imagemTransformer1 = new Konva.Transformer({
                    keepRatio: true,
                    enabledAnchors: ['top-left', 'top-right', 'bottom-left', 'bottom-right'],
                    boundBoxFunc: (oldBox, newBox) => {
                    if (newBox.width < MIN_WIDTH) {
                        return oldBox;
                    }
                    return newBox;
                    },
                });
                layer1.add(imagemTransformer1);
            } else {
                stage1 = stage;
                layer1 = layer;
                textoTransformer1 = textoTransformer;
                imagemTransformer1 = imagemTransformer;
            }
            //medida do hash
            medida = medidas.find(m => m.tipo == 8);
            if(medida === undefined) {
                if(@json($certificado->verso))
                    medida = {x: 74, y: 512, largura: 500, fontSize: 14}
                else
                    medida = {x: 26, y: 742, largura: 500, fontSize: 14}
            }
            var hash = new Konva.Text({
                x: parseInt(medida.x),
                y: parseInt(medida.y),
                width: parseInt(medida.largura),
                fontSize: medida.fontSize,
                text: 'Código para validação do certificado: $2y$10$VN/cOnYHgsW/U5W16uH.Q.MKXDNa.3Z8QqeHl89qjp/TXNLP3yNO6', //exemplo de hash
                draggable: true,
                id: 'hash',
                name: 'texto',
            });
            layer1.add(hash);

            //medida da emissao
            medida = medidas.find(m => m.tipo == 10);
            if(medida === undefined) {
                if(@json($certificado->verso))
                    medida = {x: 630, y: 522, largura: 500, fontSize: 14}
                else
                    medida = {x: 700, y: 750, largura: 500, fontSize: 14}
            }
            var options = { year: 'numeric', month: 'long', day: 'numeric' };
            var today  = new Date();
            var emissao = new Konva.Text({
                x: parseInt(medida.x),
                y: parseInt(medida.y),
                width: parseInt(medida.largura),
                fontSize: medida.fontSize,
                text: 'Certificado emitido pela plataforma Participa em '+ today.toLocaleDateString("pt-BR", options),
                draggable: true,
                id: 'emissao',
                name: 'texto',
            });
            layer1.add(emissao);

            //imagem assinatura
            var imageObj = new Image();
            imageObj.onload = function () {
                
                //medida qrcode
                medida = medidas.find(m => m.tipo == 7);
                // add the shape to the layer
                if(medida === undefined) {
                    if(@json($certificado->verso))
                        medida = {x: 175, y: 280, altura: 200, largura: 200}
                    else
                        medida = {x: 170, y: 600, altura: 100, largura: 100}
                    yoda = new Konva.Image({
                        x: medida.x,
                        y: medida.y,
                        image: imageObj,
                        draggable: true,
                        id: 'qrcode',
                        height: medida.altura,
                        width: medida.largura,
                    });
                } else {
                    //medida qrcode e imagem qrcode
                    medida = medidas.find(m => m.tipo == 7);
                    yoda = new Konva.Image({
                        x: parseInt(medida.x),
                        y: parseInt(medida.y),
                        image: imageObj,
                        draggable: true,
                        id: 'qrcode',
                        height: parseInt(medida.altura),
                        width: parseInt(medida.largura),
                    });
                }
                layer1.add(yoda);
                yoda.on('transform', (event) => {
                    // with enabled anchors we can only change scaleX
                    // so we don't need to reset height
                    // just width
                    if( ['top-left', 'top-right', 'bottom-left', 'bottom-right'].includes(imagemTransformer1.getActiveAnchor()) ) {
                        event.target.setAttrs({
                            height: event.target.height() * event.target.scaleY(),
                            width: event.target.width() * event.target.scaleX(),
                            scaleX: 1,
                            scaleY: 1,
                        });
                    } else if ( ['middle-right', 'middle-left'].includes(imagemTransformer1.getActiveAnchor()) ) {
                        event.target.setAttrs({
                            width: Math.max(event.target.width() * event.target.scaleX(), MIN_WIDTH),
                            scaleX: 1,
                            scaleY: 1,
                        });
                    }
                });
            };
            imageObj.src = "/img/qrcode.png";
            //imagem da logo
            var logoImageObj = new Image();
            logoImageObj.onload = function () {
                //medida da logo
                medida = medidas.find(m => m.tipo == 9);
                // add the shape to the layer
                if(medida === undefined) {
                    if(@json($certificado->verso))
                        medida = {x: 750, y: 280, altura: 200, largura: 200}
                    else
                        medida = {x: 900, y: 600, altura: 100, largura: 100}
                    yoda = new Konva.Image({
                        x: medida.x,
                        y: medida.y,
                        image: logoImageObj,
                        draggable: true,
                        id: 'logo',
                        height: medida.altura,
                        width: medida.largura,
                    });
                } else {
                    medida = medidas.find(m => m.tipo == 9);
                    yoda = new Konva.Image({
                        x: parseInt(medida.x),
                        y: parseInt(medida.y),
                        image: logoImageObj,
                        draggable: true,
                        id: 'logo',
                        height: parseInt(medida.altura),
                        width: parseInt(medida.largura),
                    });
                }
                layer1.add(yoda);
                yoda.on('transform', (event) => {
                    // with enabled anchors we can only change scaleX
                    // so we don't need to reset height
                    // just width
                    if( ['top-left', 'top-right', 'bottom-left', 'bottom-right'].includes(imagemTransformer1.getActiveAnchor()) ) {
                        event.target.setAttrs({
                            height: event.target.height() * event.target.scaleY(),
                            width: event.target.width() * event.target.scaleX(),
                            scaleX: 1,
                            scaleY: 1,
                        });
                    } else if ( ['middle-right', 'middle-left'].includes(imagemTransformer1.getActiveAnchor()) ) {
                        event.target.setAttrs({
                            width: Math.max(event.target.width() * event.target.scaleX(), MIN_WIDTH),
                            scaleX: 1,
                            scaleY: 1,
                        });
                    }
                });
            };
            logoImageObj.src = "/img/logo-icone.png";
            hash.on('transform', (event) => {
                // with enabled anchors we can only change scaleX
                // so we don't need to reset height
                // just width
                if( ['top-left', 'top-right', 'bottom-left', 'bottom-right'].includes(textoTransformer1.getActiveAnchor()) ) {
                hash.setAttrs({
                    fontSize: Math.max(hash.fontSize() * hash.scaleX(), 2),
                    width: Math.max(hash.width() * hash.scaleX(), MIN_WIDTH),
                    scaleX: 1,
                    scaleY: 1,
                });
                } else if ( ['middle-right', 'middle-left'].includes(textoTransformer1.getActiveAnchor()) ) {
                hash.setAttrs({
                    width: Math.max(hash.width() * hash.scaleX(), MIN_WIDTH),
                    scaleX: 1,
                    scaleY: 1,
                });
                }
            });
            emissao.on('transform', (event) => {
                // with enabled anchors we can only change scaleX
                // so we don't need to reset height
                // just width
                if( ['top-left', 'top-right', 'bottom-left', 'bottom-right'].includes(textoTransformer1.getActiveAnchor()) ) {
                emissao.setAttrs({
                    fontSize: Math.max(emissao.fontSize() * emissao.scaleX(), 2),
                    width: Math.max(emissao.width() * emissao.scaleX(), MIN_WIDTH),
                    scaleX: 1,
                    scaleY: 1,
                });
                } else if ( ['middle-right', 'middle-left'].includes(textoTransformer1.getActiveAnchor()) ) {
                emissao.setAttrs({
                    width: Math.max(emissao.width() * emissao.scaleX(), MIN_WIDTH),
                    scaleX: 1,
                    scaleY: 1,
                });
                }
            });
            stage1.on('click tap', function (e) {
                // if click on empty area - remove all selections
                if (e.target === stage1) {
                    textoTransformer1.nodes([]);
                    imagemTransformer1.nodes([]);
                    return;
                }
                // do we pressed shift or ctrl?
                let metaPressed = e.evt.shiftKey || e.evt.ctrlKey || e.evt.metaKey;
                let isSelected = imagemTransformer1.nodes().indexOf(e.target) >= 0;
                if (!metaPressed && !isSelected) {
                    // if no key pressed and the node is not selected
                    // select just one
                    if(stage1.find('.texto').includes(e.target)) {
                        textoTransformer1.nodes([e.target]);
                    } else {
                        imagemTransformer1.nodes([e.target]);
                    }
                } else if (metaPressed && isSelected) {
                    // if we pressed keys and node was selected
                    // we need to remove it from selection:
                    let nodes = imagemTransformer1.nodes().slice(); // use slice to have new copy of array
                    // remove node from array
                    nodes.splice(nodes.indexOf(e.target), 1);
                    imagemTransformer1.nodes(nodes);
                } else if (metaPressed && !isSelected) {
                    if(stage1.find('.texto').includes(e.target)) {
                        let nodes = textoTransformer1.nodes().concat([e.target]);
                        textoTransformer1.nodes(nodes);
                    } else {
                        let nodes = imagemTransformer1.nodes().concat([e.target]);
                        imagemTransformer1.nodes(nodes);
                    }
                }
                });
            function send() {
                ['nome','cargo'].forEach(objeto => {
                    assinaturas.forEach(assinatura => {
                        let box = stage.find('#'+objeto+''+assinatura.id);
                        document.querySelectorAll("input[name="+objeto+"-x-"+assinatura.id+"]")[0].value = box[0].attrs.x;
                        document.querySelectorAll("input[name="+objeto+"-y-"+assinatura.id+"]")[0].value = box[0].attrs.y;
                        document.querySelectorAll("input[name="+objeto+"-largura-"+assinatura.id+"]")[0].value = box[0].attrs.width;
                        document.querySelectorAll("input[name="+objeto+"-fontSize-"+assinatura.id+"]")[0].value = box[0].attrs.fontSize;
                    });
                });
                assinaturas.forEach(assinatura => {
                    let box = stage.find('#linha'+assinatura.id);
                    document.querySelectorAll("input[name=linha-x-"+assinatura.id+"]")[0].value = box[0].position().x + box[0].points()[0];
                    document.querySelectorAll("input[name=linha-y-"+assinatura.id+"]")[0].value = box[0].position().y + box[0].points()[1];
                    document.querySelectorAll("input[name=linha-largura-"+assinatura.id+"]")[0].value = box[0].attrs.points[2] - box[0].attrs.points[0];
                });
                ['texto','data'].forEach(objeto => {
                    let box = stage.find('#'+objeto);
                    document.querySelectorAll("input[name="+objeto+"-x]")[0].value = box[0].attrs.x;
                    document.querySelectorAll("input[name="+objeto+"-y]")[0].value = box[0].attrs.y;
                    document.querySelectorAll("input[name="+objeto+"-largura]")[0].value = box[0].attrs.width;
                    document.querySelectorAll("input[name="+objeto+"-fontSize]")[0].value = box[0].attrs.fontSize;
                });
                ['imagem'].forEach(objeto => {
                    assinaturas.forEach(assinatura => {
                        let box = stage.find('#' + objeto + '' + assinatura.id);
                        document.querySelectorAll("input[name="+objeto+"-x-"+assinatura.id+"]")[0].value = box[0].attrs.x;
                        document.querySelectorAll("input[name="+objeto+"-y-"+assinatura.id+"]")[0].value = box[0].attrs.y;
                        document.querySelectorAll("input[name="+objeto+"-largura-"+assinatura.id+"]")[0].value = box[0].attrs.width;
                        document.querySelectorAll("input[name="+objeto+"-altura-"+assinatura.id+"]")[0].value = box[0].attrs.height;
                    });
                });
                let qrcode = stage1.find('#qrcode');
                document.querySelectorAll("input[name=qrcode-x]")[0].value = qrcode[0].attrs.x;
                document.querySelectorAll("input[name=qrcode-y]")[0].value = qrcode[0].attrs.y;
                document.querySelectorAll("input[name=qrcode-largura]")[0].value = qrcode[0].attrs.width;
                document.querySelectorAll("input[name=qrcode-altura]")[0].value = qrcode[0].attrs.height;
                document.querySelectorAll("input[name=hash-x]")[0].value = hash.attrs.x;
                document.querySelectorAll("input[name=hash-y]")[0].value = hash.attrs.y;
                document.querySelectorAll("input[name=hash-largura]")[0].value = hash.attrs.width;
                document.querySelectorAll("input[name=hash-fontSize]")[0].value = hash.attrs.fontSize;
                let logo = stage1.find('#logo');
                document.querySelectorAll("input[name=logo-x]")[0].value = logo[0].attrs.x;
                document.querySelectorAll("input[name=logo-y]")[0].value = logo[0].attrs.y;
                document.querySelectorAll("input[name=logo-largura]")[0].value = logo[0].attrs.width;
                document.querySelectorAll("input[name=logo-altura]")[0].value = logo[0].attrs.height;
                document.querySelectorAll("input[name=emissao-x]")[0].value = emissao.attrs.x;
                document.querySelectorAll("input[name=emissao-y]")[0].value = emissao.attrs.y;
                document.querySelectorAll("input[name=emissao-largura]")[0].value = emissao.attrs.width;
                document.querySelectorAll("input[name=emissao-fontSize]")[0].value = emissao.attrs.fontSize;
                document.getElementById("form").submit();
            }
        </script>
    </body>
</html>