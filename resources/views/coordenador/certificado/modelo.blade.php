<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://unpkg.com/konva@8.3.5/konva.min.js"></script>
    <style>
        @page { margin:0px; }
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
        #btn {
            align-self: center;
            width: 100px;
            height: 50px;
            
        }
    </style>

</head>
<body style="text-align: center;">
        <div id="container"></div>
        @if($certificado->verso)
            <div id="back" style="margin-top: 10px"></div>
        @endif
        <button onclick="send()" id="btn">Salvar</button>
        <form id="form" action="{{route('coord.cadastrarmedida')}}" class="d-none" method="POST">
        <!--<form id="form" action="{{route('coord.cadastrarmedida')}}" class="d-none" method="POST">-->
            @csrf
            <input type="text" name="certificado_id" value="{{$certificado->id}}"><br>
            @foreach ($certificado->assinaturas as $assinatura)
                @foreach (['nome', 'imagem', 'cargo', 'linha'] as $objeto)
                    @foreach (['x', 'y', 'largura', 'altura', 'fontSize'] as $medida)
                    {{$objeto}}-{{$medida}}-{{$assinatura->id}} <input type="text" name="{{$objeto}}-{{$medida}}-{{$assinatura->id}}" value="0"> <br>
                    @endforeach
                @endforeach
            @endforeach
            @foreach (['texto', 'data', 'hash', 'emissao'] as $objeto)
                @foreach (['largura', 'x', 'y', 'fontSize'] as $medida)
                    {{$objeto}}-{{$medida}} <input type="text" name="{{$objeto}}-{{$medida}}" value="0"><br>
                @endforeach
            @endforeach
            @foreach (['qrcode', 'logo'] as $objeto)
                @foreach (['x', 'y', 'largura', 'altura'] as $medida)
                    {{$objeto}}-{{$medida}}<input type="text" name="{{$objeto}}-{{$medida}}" value="0"><br>
                @endforeach
            @endforeach
        </form>

        <script>
            let MIN_WIDTH = 100;
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
                        } else if (['midle-right', 'middle-left'].includes(transformer.getActiveAnchor())) {
                            // ['midle-right', 'middle-left'].includes(textoTransformer.getActiveAnchor()) ) {
                            console.log("e aqui")
                            shape.setAttrs({
                                width: Math.max(shape.width() * shape.scaleX(), MIN_WIDTH),
                                scaleX: 1,
                                scaleY: 1,
                            });
                        } else if (['bottom-center', 'top-center'].includes(transformer.getActiveAnchor())) {
                            // ['midle-right', 'middle-left'].includes(textoTransformer.getActiveAnchor()) ) {
                            console.log("e aqui")
                            shape.setAttrs({
                                height: event.target.height() * event.target.scaleY(),
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
                            console.log("lateral")
                            shape.setAttrs({
                                width: Math.max(event.target.width() * event.target.scaleX(), MIN_WIDTH),
                                scaleX: 1,
                                scaleY: 1,
                            });
                        } else if(['bottom-center', 'top-center'].includes(transformer.getActiveAnchor())) {
                            console.log("top, bottom", event.target.height(), event.target.scaleY())
                            console.log(shape.attrs.height, shape.attrs.scaleY)
                            shape.setAttrs({
                                height: event.target.height() * event.target.scaleY(),
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
                enabledAnchors: ['top-left', 'top-right', 'middle-right', 'middle-left', 'bottom-left', 'bottom-right', 'bottom-center', 'top-center'],
                draggable: true,
                // enable only side anchors
                // limit transformer size
                boundBoxFunc: (oldBox, newBox) => {
                /*if (newBox.width < MIN_WIDTH) {
                    return oldBox;
                }*/
                return newBox;
                },
            });
            layer.add(transformer);
            stage.add(layer);
             
            medidas = @json($medidas);
            //medidas texto 
            medida = medidas.find(m => m.tipo == 1);
            
            if(medida === undefined){
                medida = {x: 50, y: 300, largura: 1000, fontSize: 18}
            } 
            
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
            applyTransformerLogic(texto);
            layer.add(texto);
            //medidas da data
            medida = medidas.find(m => m.tipo == 2);
            if(medida === undefined) {
                medida = {x: 915, y: 350, largura: 450, fontSize: 14};
            }
            templocal = @json($certificado->local);
            tempdata = @json($dataHoje);
            localdata =  templocal + ', ' + tempdata;
            local = new Konva.Text({
                x: parseInt(medida.x),
                y: parseInt(medida.y),
                width: parseInt(medida.largura),
                fontSize: parseInt(medida.fontSize),
                text: localdata.replace(/[\n\r]/g,' ').replace('"', '').replace('"', ''),
                draggable: true,
                name: 'data',
                id: 'data',
            });
            applyTransformerLogic(local);
            layer.add(local);
            
            let assinaturas = @json($certificado->assinaturas);
            let posicao_inicial_x;
            if (assinaturas.length > 1) {
                posicao_inicial_x = ((1268 - 500) / assinaturas.length) / assinaturas.length;
            } else {
                posicao_inicial_x = 1268 / 2 - 100;
            }
            let i = 0;
            assinaturas.forEach((assinatura, index) => {
                let assinaturaArray = [];
                let imageObj = new Image();
                imageObj.onload = function () {
                    //imagem da assinatura
                    let medida = medidas.find(m => m.tipo == 5 && m.assinatura.id == assinatura.id);
                    // add the shape to the layer
                    console.log(medida = medidas.find(m => m.tipo == 5 && m.assinatura.id == assinatura.id))
                    if(medida === undefined) {
                        yoda = new Konva.Image({
                            x: posicao_inicial_x + (index * 350) - 150,
                            y: 600,
                            image: imageObj,
                            draggable: true,
                            id: 'imagem' + assinatura.id,
                            name: 'imagem' + assinatura.id,
                            scaleX: 0.2,
                            scaleY: 0.2,
                        });
                        yoda.setAttrs({
                            height: yoda.height() * yoda.scaleY(),
                            width: yoda.width() * yoda.scaleX(),
                            scaleX: 1,
                            scaleY: 1,
                        });
                        console.log("entrou no if" + yoda);
                    } else {
                        //medida da imagem da assinatura
                        let medida = medidas.find(m => m.tipo == 5 && m.assinatura.id == assinatura.id);
                        // console.log(medida);
                        yoda = new Konva.Image({
                            x: parseInt(medida.x),
                            y: parseInt(medida.y),
                            image: imageObj,
                            draggable: true,
                            id: 'imagem' + assinatura.id,
                            name: 'imagem' + assinatura.id,
                            height: parseInt(medida.altura),
                            width: parseInt(medida.largura),
                        });
                        console.log("entrou no else")
                    }
                    layer.add(yoda);
                    applyTransformerLogic(yoda);
                   
                };
                //aqui acaba a funcao onload
                imageObj.src = '/storage/' + assinatura.caminho;
                //medida da linha da assinatura
                medida = medidas.find(m => m.tipo == 6 && m.assinatura.id == assinatura.id);

                if(medida === undefined) {
                    medida = {x: posicao_inicial_x + (index * 350) - 150,y: 550 + 106, largura: (index * 250) + 256};
                }
                
                let x = parseInt(medida.x)
                let y = parseInt(medida.y)
                let sum = parseInt(medida.sum);
                let width = parseInt(medida.largura)
                let largura = parseInt(medida.largura);
                let redLine = new Konva.Line({
                    points: [x, y, x + width, y],
                    stroke: 'black',
                    strokeWidth: 2,
                    draggable: true,
                    id: 'linha' + assinatura.id,
                });

                layer.add(redLine);
                //medida da linha da assinatura
                // medida = medidas.find(m => m.tipo == 6 && m.assinatura.id == assinatura.id);
                // if(medida === undefined) {
                //     redLine = new Konva.Line({
                //         points: [posicao_inicial_x + (index * 350), 550 + 106, posicao_inicial_x + (index * 350) + 256, 550 + 106],
                //         stroke: 'black',
                //         strokeWidth: 3,
                //         draggable: true,
                //         id: 'linha' + assinatura.id,
                //     });
                //     //layer.add(redLine);
                // } else {
                //     let x = parseInt(medida.x)
                //     let y = parseInt(medida.y)
                //     let width = parseInt(medida.largura)
                //    // console.log(x, y, x + width, y)
                //     redLine = new Konva.Line({
                //         points: [x, y, x + width, y],
                //         stroke: 'black',
                //         strokeWidth: 2,
                //         draggable: true,
                //         id: 'linha' + assinatura.id,
                //     });
                //     // layer.add(redLine);
                // }
                // layer.add(redLine);
                
                //assinatura do cargo
                var medida = medidas.find(m => m.tipo == 4 && m.assinatura.id == assinatura.id);
                var simpleText;
                if(medida === undefined) {
                    medida = {x: posicao_inicial_x + (index * 350) - 50, y: 682, largura: 500, fontSize: 14}
                }
                simpleText = new Konva.Text({
                    x: parseInt(medida.x),
                    y: parseInt(medida.y),
                    width: parseInt(medida.largura),
                    text: assinatura.cargo,
                    fontSize: parseInt(medida.fontSize),
                    fontFamily: 'Arial, Helvetica, sans-serif',
                    draggable: true,
                    id: 'cargo' + assinatura.id,
                    name: 'cargo' + assinatura.id,
                });
                //     simpleText = new Konva.Text({
                //         x: posicao_inicial_x + (index * 350) + redLine.width() / 2,
                //         y: 682,
                //         text: assinatura.cargo,
                //         fontSize: 12,
                //         fontFamily: 'Arial, Helvetica, sans-serif',
                //         draggable: true,
                //         id: 'cargo' + assinatura.id,
                //         name: 'cargo' + assinatura.id,
                //     });
                //     simpleText.setAttrs({
                //         x: simpleText.x() - simpleText.width() / 2,
                //         width: simpleText.width() * simpleText.scaleX(),
                //         scaleX: 1,
                //         scaleY: 1,
                //     });
                // } else {
                //     simpleText = new Konva.Text({
                //         x: parseInt(medida.x),
                //         y: parseInt(medida.y),
                //         text: assinatura.cargo,
                //         width: parseInt(medida.largura),
                //         fontSize: parseInt(medida.fontSize),
                //         fontFamily: 'Arial, Helvetica, sans-serif',
                //         draggable: true,
                //         id: 'cargo' + assinatura.id,
                //         name: 'cargo' + assinatura.id,
                //     });
                // }
                //assinatura
                applyTransformerLogic(simpleText);
                layer.add(simpleText);

                //nome da assinatura
                var simpleText;

                medida = medidas.find(m => m.tipo == 3 && m.assinatura.id == assinatura.id);
                if(medida === undefined) {
                    medida = {x:posicao_inicial_x + (index * 350) - 160 + (redLine.width() / 2), y: 666, fontSize: 14 }
                }
                simpleText = new Konva.Text({
                    x: parseInt(medida.x),
                    y: parseInt(medida.y),
                    text: assinatura.nome,
                    width: parseInt(medida.largura),
                    fontSize: parseInt(medida.fontSize),
                    fontFamily: 'Arial, Helvetica, sans-serif',
                    scaleX: 1,
                    scaleY: 1,
                    draggable: true,
                    id: 'nome' + assinatura.id,
                    name: 'texto',
                });
                // if(medida === undefined) {
                //     simpleText = new Konva.Text({
                //         x: posicao_inicial_x + (index * 350) - 160 + redLine.width() / 2,
                //         y: 666,
                //         text: assinatura.nome,
                //         fontSize: 12,
                //         fontFamily: 'Arial, Helvetica, sans-serif',
                //         draggable: true,
                //         id: 'nome' + assinatura.id,
                //         name: 'texto',
                //     });
                //     simpleText.setAttrs({
                //         x: simpleText.x() - simpleText.width() / 2,
                //         width: simpleText.width() * simpleText.scaleX(),
                //         scaleX: 1,
                //         scaleY: 1,
                //     });
                // } else {
                //     simpleText = new Konva.Text({
                //         x: parseInt(medida.x),
                //         y: parseInt(medida.y),
                //         text: assinatura.nome,
                //         width: parseInt(medida.largura),
                //         fontSize: parseInt(medida.fontSize),
                //         fontFamily: 'Arial, Helvetica, sans-serif',
                //         draggable: true,
                //         id: 'nome' + assinatura.id,
                //         name: 'texto',
                //     });
                // }
            applyTransformerLogic(simpleText);
            layer.add(simpleText);
            });
            //aqui acaba foreach assinatura
            
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
                    transformer.nodes([]);
                    return;
                }
                // do we pressed shift or ctrl?
                let metaPressed = e.evt.shiftKey || e.evt.ctrlKey || e.evt.metaKey;
                let isSelected = transformer.nodes().indexOf(e.target) >= 0;
                //console.log(metaPressed, isSelected);
                if (!metaPressed && !isSelected) {
                    transformer.nodes([e.target]);
                } else if (metaPressed && isSelected) {
                    let nodes = transformer.nodes().slice(); // use slice to have new copy of array
                    // remove node from array
                    nodes.splice(nodes.indexOf(e.target), 1);
                    transformer.nodes(nodes);
                } else if (metaPressed && !isSelected) {
                    let nodes = transformer.nodes().concat([e.target]);
                    transformer.nodes(nodes);
                }
            });
            
            //verso certificado
            
             //verso certificado
            
            var verso;
            var versoTransformer;
            if(@json($certificado->verso)) {

                verso = new Konva.Stage({
                    container: 'back',
                    width: 1118,
                    height: 790,
                    draggable: true,
                });

                var layer1 = new Konva.Layer();

                verso.add(layer1);

                versoTransformer = new Konva.Transformer({
                    padding: 5,
                    rotateEnabled: false,
                    keepRatio: true,
                    enabledAnchors: ['top-left', 'top-right', 'middle-right', 'middle-left', 'bottom-left',
                     'bottom-right', 'top-left', 'top-right', 'bottom-left', 'bottom-right'],
                   
                    boundBoxFunc: (oldBox, newBox) => {
                    // if (newBox.width < MIN_WIDTH) {
                    //     return oldBox;
                    // }
                    return newBox;
                    },
                });
                layer1.add(versoTransformer);
                // versoTransformer = new Konva.Transformer({
                //     keepRatio: true,
                //     enabledAnchors: ['top-left', 'top-right', 'bottom-left', 'bottom-right'],
                //     boundBoxFunc: (oldBox, newBox) => {
                //     if (newBox.width < MIN_WIDTH) {
                //         return oldBox;
                //     }
                //     return newBox;
                //     },
                // });
                // layer1.add(versoTransformer);
            } else {
                verso = stage;
                layer1 = layer;
                versoTransformer = transformer;
                //versoTransformer = imagemTransformer;
            }
            //medida do hash
            medida = medidas.find(m => m.tipo == 8);
            if(medida === undefined) {
                if(@json($certificado->verso)) {
                    medida = {x: 74, y: 512, largura: 500, fontSize: 14}
                } else {
                    medida = {x: 26, y: 742, largura: 500, fontSize: 14}
                }
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
            //applyTransformerLogic(hash);
            console.log(applyTransformerLogic(hash));
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
                    //medida qrcode
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
                    if( ['top-left', 'top-right', 'bottom-left', 'bottom-right'].includes(versoTransformer.getActiveAnchor()) ) {
                        event.target.setAttrs({
                            height: event.target.height() * event.target.scaleY(),
                            width: event.target.width() * event.target.scaleX(),
                            scaleX: 1,
                            scaleY: 1,
                        });
                    } else if ( ['middle-right', 'middle-left'].includes(versoTransformer.getActiveAnchor()) ) {
                        event.target.setAttrs({
                            width: Math.max(event.target.width() * event.target.scaleX(), MIN_WIDTH),
                            scaleX: 1,
                            scaleY: 1,
                        });
                    }
                });
            };
            imageObj.src = "/img/qrcode.png";
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
                    //medida da logo
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
                    if( ['top-left', 'top-right', 'bottom-left', 'bottom-right'].includes(versoTransformer.getActiveAnchor()) ) {
                        event.target.setAttrs({
                            height: event.target.height() * event.target.scaleY(),
                            width: event.target.width() * event.target.scaleX(),
                            scaleX: 1,
                            scaleY: 1,
                        });
                    } else if ( ['middle-right', 'middle-left'].includes(versoTransformer.getActiveAnchor()) ) {
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
                if( ['top-left', 'top-right', 'bottom-left', 'bottom-right'].includes(versoTransformer.getActiveAnchor()) ) {
                hash.setAttrs({
                    fontSize: Math.max(hash.fontSize() * hash.scaleX(), 2),
                    width: Math.max(hash.width() * hash.scaleX(), MIN_WIDTH),
                    scaleX: 1,
                    scaleY: 1,
                });
                } else if ( ['middle-right', 'middle-left'].includes(versoTransformer.getActiveAnchor()) ) {
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
                if( ['top-left', 'top-right', 'bottom-left', 'bottom-right'].includes(versoTransformer.getActiveAnchor()) ) {
                emissao.setAttrs({
                    fontSize: Math.max(emissao.fontSize() * emissao.scaleX(), 2),
                    width: Math.max(emissao.width() * emissao.scaleX(), MIN_WIDTH),
                    scaleX: 1,
                    scaleY: 1,
                });
                } else if ( ['middle-right', 'middle-left'].includes(versoTransformer.getActiveAnchor()) ) {
                emissao.setAttrs({
                    width: Math.max(emissao.width() * emissao.scaleX(), MIN_WIDTH),
                    scaleX: 1,
                    scaleY: 1,
                });
                }
            });
            
            verso.on('click tap', function (e) {
                // if click on empty area - remove all selections
                if (e.target === verso) {
                    versoTransformer.nodes([]);
                    versoTransformer.nodes([]);
                    return;
                }
                // do we pressed shift or ctrl?
                let metaPressed = e.evt.shiftKey || e.evt.ctrlKey || e.evt.metaKey;
                let isSelected = versoTransformer.nodes().indexOf(e.target) >= 0;
                if (!metaPressed && !isSelected) {
                    // if no key pressed and the node is not selected
                    // select just one
                    if(verso.find('.texto').includes(e.target)) {
                        versoTransformer.nodes([e.target]);
                    } else {
                        versoTransformer.nodes([e.target]);
                    }
                } else if (metaPressed && isSelected) {
                    // if we pressed keys and node was selected
                    // we need to remove it from selection:
                    let nodes = versoTransformer.nodes().slice(); // use slice to have new copy of array
                    // remove node from array
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
                }
                });
                
            function send() {
                
                let xGlobal = (stage.attrs.x == undefined)?0:stage.attrs.x;       
                let yGlobal = (stage.attrs.y == undefined)?0:stage.attrs.y;                 
               
                let xGlobalVerso = (verso.attrs.x == undefined)?0:verso.attrs.x;       
                let yGlobalVerso = (verso.attrs.y == undefined)?0:verso.attrs.y;  

                ['nome','cargo'].forEach(objeto => {
                    assinaturas.forEach(assinatura => {
                        let box = stage.find('#'+objeto+''+assinatura.id);
                        document.querySelectorAll("input[name="+objeto+"-x-"+assinatura.id+"]")[0].value = box[0].attrs.x + xGlobal;
                        document.querySelectorAll("input[name="+objeto+"-y-"+assinatura.id+"]")[0].value = box[0].attrs.y + yGlobal;
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
                    document.querySelectorAll("input[name="+objeto+"-x]")[0].value = box[0].attrs.x + xGlobal;
                    document.querySelectorAll("input[name="+objeto+"-y]")[0].value = box[0].attrs.y + yGlobal;
                    document.querySelectorAll("input[name="+objeto+"-largura]")[0].value = box[0].attrs.width;
                    document.querySelectorAll("input[name="+objeto+"-fontSize]")[0].value = box[0].attrs.fontSize;
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