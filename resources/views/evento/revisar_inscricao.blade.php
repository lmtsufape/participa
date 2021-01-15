@extends('layouts.app')

@section('content')
<div class="container">
    <form id="formConfirmarInscricao" action="" method="">
        @csrf
        <div class="row justify-content-center titulo">
            <div class="col-sm-12">
                <h1>Revise os dados e confirme o pagamento</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <h3>Meus dados</h3>
            </div>
        </div>
        <input type="hidden" name="user_id" value="{{auth()->user()->id}}">
        <div class="row">
            <div class="col-sm-6">
                <label for="nome">Usuário</label>
                <input id="nome" type="text" class="form-control" disabled value="{{auth()->user()->name}}">
            </div>
            <div class="col-sm-6">
                <label for="cpf">CPF</label>
                <input id="cpf" type="text" class="form-control" disabled value="{{auth()->user()->cpf}}">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <label for="email">E-mail</label>
                <input id="email" type="text" class="form-control" disabled value="{{auth()->user()->email}}">
            </div>
            <div class="col-sm-6">
                <label for="celular">Celular</label>
                <input id="celular" type="text" class="form-control" disabled value="{{auth()->user()->celular}}">
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-sm-12">
                <h3>Dados da inscrição</h3>
            </div>
        </div>
        <input type="hidden" name="evento_id" value="{{$evento->id}}">
        <div class="row">
            <div class="col-sm-6">
                <label>Evento</label>
                <input type="text" class="form-control" disabled value="{{$evento->nome}}">
            </div>
            @if ($promocao != null)
                <input type="hidden" name="promocao_id" value="{{$promocao->id}}">
                <div class="col-sm-4">
                    <label>Promoção</label>
                    <input type="text" class="form-control" disabled value="{{$promocao->identificador}}">
                </div>
                <div class="col-sm-2">
                    <label>Taxa com a promoção</label>
                    <input type="text" class="form-control" disabled value="@if($promocao->valor != null && $promocao->valor > 0)R$ {{number_format($promocao->valor, 2,',','.')}}@else Gratuita @endif">
                </div>
            @else 
                <div class="col-sm-6">
                    <label>Taxa do evento</label>
                    <input type="text" class="form-control" disabled value="{{$evento->valorTaxas}}">
                </div>
            @endif
        </div>
        @if ($promocao != null && count($promocao->atividades) > 0)
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <h5>Atividades extras inclusas na promoção</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    @if ($promocao->atividades != null)
                        <table class="table table-hover table-responsive-lg table-sm">
                            <thead>
                                <th>
                                    <th>Titulo</th>
                                    <th>Tipo</th>
                                    <th>Local</th>
                                </th>
                            </thead>
                            @foreach ($promocao->atividades as $atv)
                                <tbody>
                                    <th>
                                        <td>{{$atv->titulo}}</td>
                                        <td>{{$atv->tipoAtividade->descricao}}</td>
                                        <td>{{$atv->local}}</td>
                                    </th>
                                </tbody>
                            @endforeach
                        </table>
                    @endif
                </div>
            </div>
        @endif
        
        @if ($atividades != null)
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <h5>Atividades adicionadas a inscrição</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-hover table-responsive-lg table-sm">
                        <thead>
                            <th>
                                <th>Titulo</th>
                                <th>Tipo</th>
                                <th>Local</th>
                                <th>Valor</th>
                            </th>
                        </thead>
                        @foreach ($atividades as $atv)
                            <input type="hidden" name="atividades[]" value="{{$atv->id}}">
                            <tbody>
                                <th>
                                    <td>{{$atv->titulo}}</td>
                                    <td>{{$atv->tipoAtividade->descricao}}</td>
                                    <td>{{$atv->local}}</td>
                                    <td>@if ($atv->valor != null && $atv->valor > 0)R$ {{number_format($atv->valor, 2,',','.')}}@else Gratuita @endif</td>
                                </th>
                            </tbody>
                        @endforeach
                    </table>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="@if($cupom != null) col-sm-3 @else col-sm-6 @endif">
                <label for="metodo">Método de pagamento</label>
                <select name="metodo" class="form-control" id="metodo" required>
                    <option value="" selected disabled>-- Selecione o método --</option>
                    <option value="boleto">Boleto bancário</option>
                    <option value="cartao">Cartão de Crédito</option>
                </select>
            </div>
            <div class="@if($cupom != null) col-sm-3 @else col-sm-6 @endif">
                <label for="valorTotal">Valor total</label>
                <input id="valorTotal" type="text" class="form-control" disabled value="R$ {{number_format($valor, 2,',','.')}}">
                <input type="hidden" name="valorTotal" value="{{$valor}}">
            </div>
            @if ($cupom != null)
                <div class="col-sm-3">
                    <label for="cupom">Cupom de desconto</label>
                    <input type="text" id="cupom" disabled class="form-control" value="{{$cupom->identificador}}">
                    <input type="hidden" name="cupom" value="{{$cupom->id}}">
                    @if ($cupom->porcentagem)
                        <small>O cupom vale {{$cupom->valor}}% de desconto.</small>
                    @else 
                        <small>O cupom vale R$ {{number_format($cupom->valor, 2,',','.')}} de desconto.</small>
                    @endif
                </div>
                <div class="col-sm-3">
                    <label for="valorComDesconto">Valor final</label>
                    <input type="hidden" name="valorFinal" value="{{$valorComDesconto}}">
                    @if ($valorComDesconto <= 0)
                        <input type="text" id="valorComDesconto" disabled class="form-control" value="R$ {{number_format(0, 2,',','.')}}">
                    @else 
                        <input type="text" id="valorComDesconto" disabled class="form-control" value="R$ {{number_format($valorComDesconto, 2,',','.')}}">
                    @endif
                </div>
            @endif
        </div>
        <div class="row" style="margin-top: 50px; margin-bottom: 50px;">
            <div class="col-sm-6">
                <button type="button" class="btn btn-secondary" style="width: 100%; padding: 30px;" onclick="voltarTela()">Voltar</button>
            </div>
            <div class="col-sm-6">
                <button type="button" class="btn btn-primary" style="width: 100%; padding: 30px;" onclick="confirmarInscricao()">Confirmar inscrição</button>
            </div>
        </div>
    </form>
</div>
@endsection
@section('javascript')
    <script type="text/javascript">
        function voltarTela() {
            var form = document.getElementById('formConfirmarInscricao');
            form.action = "{{route('inscricao.voltar', ['id' => $evento->id])}}";
            form.method = "GET";
            form.submit();
        }

        function confirmarInscricao() {
            var form = document.getElementById('formConfirmarInscricao');
            form.action = "{{route('checkout.index', ['id' => $evento->id])}}"
            form.method = "POST";
            form.submit();
        }
    </script>
@endsection