@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center titulo">
        <div class="col-sm-12">
            <h1>Nova inscrição para {{$evento->nome}}</h1>
        </div>
    </div>
    @error('atvIguais')
        <div class="row justify-content-center">
            <div class="col-sm-12">
                @include('componentes.mensagens')
            </div>
        </div>
    @enderror
    <form action="{{route('inscricao.checar', ['id' => $evento->id])}}" method="GET">
        <div id="formulario">
            <div class="row form-group">
                <div class="col-sm-8">
                    <h4 style="position: relative; left: 50px;">Promoções disponíveis</h4>
                    <select name="promocao" id="promocao" class="form-control" style="position: relative; left: 50px;" onchange="carregarAtividadesDaPromocao(this)">
                        <option value="" disabled selected>-- Escolha uma promoção --</option>
                        @foreach ($evento->promocoes as $promocao)
                            <option value="{{$promocao->id}}" @if(old('promocao') == $promocao->id) selected @endif>{{$promocao->identificador}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-3" style="position: relative; left: 50px;">
                    <h4 for="cupomDesconto">Cupom de desconto</h4>
                    <input oninput="deixarMaiusculo(event)" id="cupomDesconto" type="text" class="form-control" placeholder="VALE10" onchange="checarCupom(this)" value="{{old('cupom')}}">
                
                    <div id="retorno200" class="valid-feedback" style="display: none;">
                       Cupom valido!
                    </div>
                    <div id="retorno404" class="invalid-feedback" style="display: none;">
                        Cupom inválido.
                    </div>
                    <div id="retorno419" class="invalid-feedback" style="display: none;">
                        Cupom expirado.
                    </div>
                </div>
                <div id="cupom">
    
                </div>
            </div>
            <div class="row form-group">
                <div id="descricaoPromo" class="col-sm-11" style="left: 50px;">
                    @if (old('descricaoPromo') != null)
                        <textarea id='descricaoPromo' class='form-control' name='descricaoPromo' readonly>{{old('descricaoPromo')}}</textarea>
                    @endif
                </div>
            </div>
            <div class="row form-group">
                <div class="col-sm-12">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12" style="position: relative; left: 40px;">
                                <h4>Atividades da promoção</h4>
                            </div>
                        </div>
                        <div id="atividadesPromocionais" class="row">
                            <span id="padraoPromocional" style="margin: 57px;  @if(old('atividadesPromo') != null)display: none; @endif">Nenhuma atividade adicionada para essa promoção.</span>
                            @if (old('atividadesPromo') != null)
                                @foreach (old('atividadesPromo') as $key => $idAtv)
                                    <div id="atvPromocao{{$idAtv}}" class="col-sm-3 atvAdicionais">
                                        <input type="hidden" id="atvPromocaoInput{{old('atividadesPromo.'.$key)}}" name="atividadesPromo[]" value="{{old('atividadesPromo.'.$key)}}">
                                        <div class="card" style="width: 16rem;">
                                            <div class="card-body">
                                                <h4 class="card-title">{{App\Atividade::find(old('atividadesPromo.'.$key))->titulo}}</h4>
                                                <h5 class="card-subtitle mb-2 text-muted">{{App\Atividade::find(old('atividadesPromo.'.$key))->tipoAtividade->descricao}}</h5>
                                                <h6 class="card-subtitle mb-2 text-muted">{{App\Atividade::find(old('atividadesPromo.'.$key))->local}}</h6>
                                                <p class="card-text">{{App\Atividade::find(old('atividadesPromo.'.$key))->descricao}}</p>
                                                <a href='#' class='card-link' data-toggle='modal' data-target='#modalDetalheAtividade{{old('atividadesPromo.'.$key)}}'>Detalhes</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <hr>
                </div>
            </div>
            <div class="row form-group" style="position: relative; top: 25px;">
                <div class="col-sm-12">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-6" style="position: relative; left: 40px;">
                                <h4>Atividades adicionais</h4>
                            </div>
                            <div class="col-sm-6" style="text-align: right;position: relative; right: 40px;" >
                                <a href="#" class="btn btn-secondary" style="font-size: 14px; text-align: right;" data-toggle="modal" data-target="#modalAdicionarAtividade">Adicionar atividade</a>
                            </div> 
                        </div>
                        <div id="atividadesAdicionadas" class="row">
                            <span id="padrao" style="margin: 57px;">Nenhuma atividade adicionada.</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row form-group" style="position: relative; top: 50px;">
                <div class="col-sm-6">
                    
                </div>
                <div class="col-sm-6" style="text-align: right; position: relative; right: 50px;">
                    <h5>Total</h5>
                    @if (old('valorTotal') != null)
                        @if (old('valorTotal') == 0)
                            <p>
                                Gratuita <input type="hidden" name="valorTotal" id="valorTotal" value="0">
                            </p>
                        @else 
                            <p>
                                <span id="spanValorTotal">R$ {{number_format(old('valorTotal'), 2,',','.')}}</span>
                                <input type="hidden" name="valorTotal" id="valorTotal" value="{{old('valorTotal')}}">
                            </p>
                        @endif
                    @else 
                        @if ($evento->valorTaxa <= 0)
                            <p>
                                Gratuita <input type="hidden" name="valorTotal" id="valorTotal" value="0">
                            </p>
                        @else 
                            <p>
                                <span id="spanValorTotal">R$ {{number_format($evento->valorTaxa, 2,',','.')}}</span>
                                <input type="hidden" name="valorTotal" id="valorTotal" value="{{$evento->valorTaxa}}">
                            </p>
                        @endif
                    @endif
                </div>
            </div>
            <div class="row" style="position: relative; left: 50px; margin-top:70px; margin-bottom:50px;">
                <div class="col-sm-5" style="top:5px;">
                <a href="{{route('evento.visualizar', ['id' => $evento->id])}}" class="btn btn-secondary" style="width: 100%; padding: 25px;">Voltar</a>
                </div>
                <div class="col-sm-6">
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 30px;">Avançar</button>
                </div>
            </div>
        </div>
    </form>

    <!-- Modal adicionar atividade -->
        <div class="modal fade modal-example-lg" id="modalAdicionarAtividade" tabindex="-1" role="dialog" aria-labelledby="modalAdicionarAtividadeLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #114048ff; color: white;">
                    <h5 class="modal-title" id="modalAdicionarAtividadeLabel">Adicionar atividades a inscrição</h5>
                        <button id="closeModalButton" type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div id="atividadesModalAdicionarAtividades" class="row" style="position: relative; right: 20px;">
                                @if (count($evento->atividade) > 0)
                                    <div class="col-sm-12">
                                        <span id="padraoModal" style="display: none;">Este evento não possue atividades.</span>
                                    </div>
                                    @foreach ($evento->atividade as $atv)                            
                                        @if ($atv->visibilidade_participante)
                                            <div id="atv{{$atv->id}}" class="col-sm-4 atvAdicionais">
                                                <input type="hidden" id="atvInput{{$atv->id}}" name="atividades[]" value="{{$atv->id}}">
                                                <input type="hidden" id="valorAtv{{$atv->id}}" value="{{$atv->valor}}">
                                                <div class="card" style="width: 220px;">
                                                    <div class="card-body">
                                                        <h4 class="card-title">{{$atv->titulo}}</h4>
                                                        <h5 class="card-subtitle mb-2 text-muted">{{$atv->tipoAtividade->descricao}}</h5>
                                                        @if ($atv->valor == null || $atv->valor <= 0) 
                                                            <h5 class="card-subtitle mb-2 text-muted">Grátis</h5>
                                                        @else 
                                                            <h5 class="card-subtitle mb-2 text-muted">R$ {{number_format($atv->valor, 2,',','.')}}</h5>
                                                        @endif
                                                        <h6 class="card-subtitle mb-2 text-muted">{{$atv->local}}</h6>
                                                        <p class="card-text">{{$atv->descricao}}</p>
                                                        <div id="linksAtv{{$atv->id}}">
                                                            <a href="#" class="card-link" onclick="adicionarAtividadeAhInscricao({{$atv->id}})">Adicionar atividade</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @else 
                                    <div class="col-sm-12">
                                        <span id="padraoModal" style="display: inline;">Este evento não possue atividades.</span>        
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- Fim do modal adicionar atividade -->
    
    <!-- Modal detalhes da atividade -->
    @foreach ($evento->atividade as $atv)
        <div class="modal fade" id="modalDetalheAtividade{{$atv->id}}" tabindex="-1" role="dialog" aria-labelledby="modalDetalheAtividade{{$atv->id}}Label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #114048ff; color: white;">
                    <h5 class="modal-title" id="modalDetalheAtividade{{$atv->id}}Label">Detalhes da atividade {{$atv->titulo}}</h5>
                        <button id="closeModalButton" type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h4>Dados principais</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="tipo">Tipo</label>
                                    <input id="tipo" type="text" class="form-control" disabled value="{{$atv->tipoAtividade->descricao}}">
                                </div>
                                <div class="col-sm-6">
                                    @if ($atv->valor <= 0)
                                        <label for=""></label> 
                                        <input type="text" disabled class="form-control" value="Gratuita">  
                                    @else
                                        <label for="valor">Valor</label>
                                        <input id="valor" type="text" disabled class="form-control" value="R$ {{number_format($atv->valor, 2,',','.')}}">
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <label for="descricao">Descrição</label>
                                    <textarea id="descricao" disabled class="form-control">{{$atv->descricao}}</textarea>
                                </div>
                            </div>
                            <div class="row">                                
                                <div class="col-sm-12">
                                    <hr>
                                    Quando ocorre?
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="">Dias</label>
                                </div>
                                <div class="col-sm-6">
                                    <label for="">Horários</label>
                                </div>
                                @foreach ($atv->datasAtividade as $data)
                                    <div class="col-sm-6">
                                        <input type="date" disabled class="form-control" value="{{$data->data}}">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" disabled class="form-control" value="Das {{DateTime::createFromFormat('H:i:s', $data->hora_inicio)->format('H:i')}} às {{DateTime::createFromFormat('H:i:s', $data->hora_fim)->format('H:i')}}">
                                    </div>
                                @endforeach
                            </div>
                            <div class="row">                                
                                <div class="col-sm-12">
                                    <hr>
                                    Aonde ocorre?
                                </div>
                            </div>
                            <div class="row">                                
                                <div class="col-sm-12">
                                    <input type="text" value="{{$atv->local}}" disabled class="form-control">
                                </div>
                            </div>
                            @if (($atv->vagas != null && $atv->vagas > 0) || ($atv->carga_horaria != null && $atv->carga_horaria > 0) || (count($atv->convidados) > 0))
                                <div class="row">
                                    <div class="col-sm-12">
                                        <hr>
                                        <h4>Dados adicionais</h4>
                                    </div>
                                </div>
                                @if (($atv->vagas != null && $atv->vagas > 0) || ($atv->carga_horaria != null && $atv->carga_horaria > 0))
                                    <div class="row">
                                        @if ($atv->vagas != null && $atv->vagas > 0)
                                            <div class="col-sm-6">
                                                <label for="vagas">Vagas</label>
                                                <input id="vagas" type="text" disabled class="form-control" value="{{$atv->vagas}}">
                                            </div>
                                        @endif
                                        @if ($atv->carga_horaria != null && $atv->carga_horaria > 0)
                                            <div class="col-sm-6">
                                                <label for="carga_horaria">Carga horária</label>
                                                <input id="carga_horaria" type="text" disabled class="form-control" value="{{$atv->carga_horaria}}">
                                            </div>
                                        @endif
                                    </div>
                                @endif
                                @if (count($atv->convidados) > 0)
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <hr>
                                            Convidados
                                        </div>
                                    </div>
                                    <div class="convidadosDeUmaAtividade">
                                        <div class="row">
                                            @foreach ($atv->convidados as $convidado)
                                            <div class="col-sm-3 imagemConvidado">
                                                <img src="{{asset('img/icons/user.png')}}" alt="Foto de {{$convidado->nome}}" width="50px" height="auto">
                                                <h5 class="convidadoNome">{{$convidado->nome}}</h5>
                                                <small class="convidadoFuncao">{{$convidado->funcao}}</small>
                                            </div>
                                            @endforeach  
                                        </div>    
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    {{-- fim modal detalhes da atividade --}}
</div>
    
@endsection
@section('javascript')
<script type="text/javascript">
    @if (old('valorTotal') != null) 
        $(document).ready(function() {
            var select = document.getElementById('promocao');
            carregarAtividadesDaPromocao(select);
        });
    @endif
    function adicionarAtividadeAhInscricao(id) {
        var divAtividadesInscricao = document.getElementById('atividadesAdicionadas');
        var atividade = document.getElementById('atv'+id);
        var divAtividadesModal = document.getElementById('atividadesModalAdicionarAtividades');
        var valorTotal = document.getElementById('valorTotal').value;
        var spanValorTotal = document.getElementById('spanValorTotal');
        var valorAtv = document.getElementById('valorAtv'+id).value;

        if (divAtividadesInscricao.children.length == 1) {
            document.getElementById('padrao').style.display = "none";
        }

        divAtividadesInscricao.appendChild(atividade);
        atividade.className = "col-sm-3";
        atividade.children[2].style.width = "16rem";

        if (divAtividadesModal.children.length == 1) {
            document.getElementById('padraoModal').style.display = "inline";
        }

        if (valorAtv != null && valorAtv > 0) {
            valorTotal = parseFloat(valorTotal) + parseFloat(valorAtv);
        } else {
            valorTotal = parseFloat(valorTotal);
        }

        adicionarLinksDaAtividade(id);
        
        if (valorTotal > 0) {
            $('#spanValorTotal').html("");
            $('#spanValorTotal').append(valorTotal.toLocaleString("pt-BR", { style: "currency" , currency:"BRL"}));
            document.getElementById('valorTotal').value = valorTotal;
        } else {
            $('#spanValorTotal').html("");
            $('#spanValorTotal').append("Gratuita");
            document.getElementById('valorTotal').value = 0;
        }
        // console.log(atividade);
        $('#closeModalButton').click();
    }

    function adicionarLinksDaAtividade(id) {
        var links = "<a href='#' class='card-link' data-toggle='modal' data-target='#modalDetalheAtividade"+id+"'>Detalhes</a>" +
                    "<a href='#' class='card-link' onclick='removerAtividade("+id+")'>Remover</a>";

        $('#linksAtv'+id).html("");
        $('#linksAtv'+id).append(links);
    }

    function removerAtividade(id) {
        var divAtividadesInscricao = document.getElementById('atividadesAdicionadas');
        var atividade = document.getElementById('atv'+id);
        var divAtividadesModal = document.getElementById('atividadesModalAdicionarAtividades');
        var valorTotal = document.getElementById('valorTotal').value;
        var spanValorTotal = document.getElementById('spanValorTotal');
        var valorAtv = document.getElementById('valorAtv'+id).value;

        divAtividadesModal.appendChild(atividade);

        if (divAtividadesInscricao.children.length == 1) {
            document.getElementById('padrao').style.display = "inline";
        }

        if (divAtividadesModal.children.length == 1) {
            document.getElementById('padraoModal').style.display = "none";
        }

        atividade.className = "col-sm-4";
        atividade.children[2].style.width = "220px";

        if (valorAtv != null && valorAtv > 0) {
            valorTotal = parseFloat(valorTotal) - parseFloat(valorAtv);
        } else {
            valorTotal = parseFloat(valorTotal);
        }

        if (valorTotal > 0) {
            $('#spanValorTotal').html("");
            $('#spanValorTotal').append(valorTotal.toLocaleString("pt-BR", { style: "currency" , currency:"BRL"}));
            document.getElementById('valorTotal').value = valorTotal;
        } else {
            $('#spanValorTotal').html("");
            $('#spanValorTotal').append("Gratuita");
            document.getElementById('valorTotal').value = 0;
        }

        removerLinksDaAtividade(id);
    }

    function removerLinksDaAtividade(id) {
        var link = "<a href='#' class='card-link' onclick='adicionarAtividadeAhInscricao("+id+")'>Adicionar atividade</a>";

        $('#linksAtv'+id).html("");
        $('#linksAtv'+id).append(link);
    }

    function carregarAtividadesDaPromocao(select) {
        // console.log(select.value);
        $.ajax({
            url: "{{route('promocao.atividades')}}",
            method: 'get',
            type: 'get',
            data: {
                _token: '{{csrf_token()}}',
                id: select.value,  
            },
            statusCode: {
                404: function() {
                    alert("Escolha uma promoção.");
                }
            },
            success: function(data){
                // console.log(data[0]);
                if (data != null && data.length > 1) {
                    var html = "";
                    var descricao = "";
                    $.each(data, function(i, obj) {
                        if (i == 0) {
                            if (obj.valorPromo != null && obj.valorPromo > 0) {
                                html += "<input type='hidden' name='valorPromocao' id='valorPromocao' value='"+obj.valorPromo+"'>";
                            } else {
                                html += "<input type='hidden' name='valorPromocao' id='valorPromocao' value='"+0+"'>";
                            }
                            descricao += "<textarea id='descricaoPromo' name='descricaoPromo' class='form-control' readonly>"+obj.descricao+"</textarea>";
                        } else if (i > 0) {
                            html += "<div id='atvPromocao"+obj.id+"' class='col-sm-3'>" +
                                        "<input type='hidden' id='atvPromocaoInput"+obj.id+"' name='atividadesPromo[]' value='"+obj.id+"'>" +
                                        "<div class='card' style='width: 16rem;'>" +
                                            "<div class='card-body'>" +
                                                "<h4 class='card-title'>"+obj.titulo+"</h4>" +
                                                "<h5 class='card-subtitle mb-2 text-muted'>"+obj.tipo+"</h5>" +
                                                "<h6 class='card-subtitle mb-2 text-muted'>"+obj.local+"</h6>" +
                                                "<p class='card-text'>"+obj.descricao+"</p>" +
                                                "<a href='#' class='card-link' data-toggle='modal' data-target='#modalDetalheAtividade"+obj.id+"'>Detalhes</a>" +
                                            "</div>" +
                                        "</div>" +
                                    "</div>";
                        }
                    })
                    $('#atividadesPromocionais').html("");
                    $('#atividadesPromocionais').append(html);
                    $('#descricaoPromo').html("");
                    $('#descricaoPromo').append(descricao);
                } else if (data != null && data.length == 1) {
                    var html = "";
                    var descricao = "";
                    $.each(data, function(i, obj) {
                        if (obj.valorPromo != null && obj.valorPromo > 0) {
                            html += "<input type='hidden' name='valorPromocao' id='valorPromocao' value='"+obj.valorPromo+"'>";
                        } else {
                            html += "<input type='hidden' name='valorPromocao' id='valorPromocao' value='"+0+"'>";
                        }
                        html += "<span id='padraoPromocional' style='margin: 57px;'>Nenhuma atividade adicionada para essa promoção.</span>";
                        descricao += "<textarea id='descricaoPromo' name='descricaoPromo' class='form-control' readonly>"+obj.descricao+"</textarea>";
                    })
                    $('#atividadesPromocionais').html("");
                    $('#atividadesPromocionais').append(html);
                    $('#descricaoPromo').html("");
                    $('#descricaoPromo').append(descricao);
                }
            }
        });
        setTimeout(function() {
            var atividadesAdicionais = document.getElementById('atividadesAdicionadas').children;
            var valorTotal = 0.0;
            if (document.getElementById('valorPromocao').value != null && parseFloat(document.getElementById('valorPromocao').value) > 0) {
                valorTotal = parseFloat(document.getElementById('valorPromocao').value);
            }

            for (var c = 0; c < atividadesAdicionais.length; c++) {
                if (c > 0) {
                    console.log(atividadesAdicionais.children[c]);
                    if (atividadesAdicionais.children[c].children[1].value != "Grátis") {
                        valorTotal += parseFloat(atividadesAdicionais.children[c].children[1].value);
                    }
                }
            }

            if (valorTotal > 0) {
                $('#spanValorTotal').html("");
                $('#spanValorTotal').append(valorTotal.toLocaleString("pt-BR", { style: "currency" , currency:"BRL"}));
                document.getElementById('valorTotal').value = valorTotal;
            } else {
                $('#spanValorTotal').html("");
                $('#spanValorTotal').append("Gratuita");
                document.getElementById('valorTotal').value = 0;
            }
        },1000);
    }

    function deixarMaiusculo(e) {
        var inicioCursor = e.target.selectionStart;
        var fimCursor = e.target.selectionEnd;
        e.target.value = e.target.value.toUpperCase();
        e.target.selectionStart = inicioCursor;
        e.target.selectionEnd = fimCursor;
    }

    function checarCupom(input) {
        if (input.value == "") {
            input.className = "form-control",
            document.getElementById('retorno200').style.display = "none";
            document.getElementById('retorno404').style.display = "none";
            document.getElementById('retorno419').style.display = "none";
        } else {
            $.ajax({
                url: "{{route('checar.cupom')}}",
                method: 'get',
                type: 'get',
                data: {
                    _token: '{{csrf_token()}}',
                    nome: input.value,  
                    evento_id: '{{$evento->id}}',
                },
                statusCode: {
                    419: function() {
                        input.className = "form-control is-invalid",
                        document.getElementById('retorno200').style.display = "none";
                        document.getElementById('retorno404').style.display = "none";
                        document.getElementById('retorno419').style.display = "block";
                    },
                    404: function() {
                        input.className = "form-control is-invalid",
                        document.getElementById('retorno200').style.display = "none";
                        document.getElementById('retorno404').style.display = "block";
                        document.getElementById('retorno419').style.display = "none";
                    },
                    200: function() {
                        input.className = "form-control is-valid",
                        document.getElementById('retorno200').style.display = "block";
                        document.getElementById('retorno404').style.display = "none";
                        document.getElementById('retorno419').style.display = "none";
                        $('#cupom').html("");
                        $('#cupom').append("<input type='hidden' name='cupom' value="+input.value+">");
                    }
                },
            });
        }
    }
</script>
@endsection