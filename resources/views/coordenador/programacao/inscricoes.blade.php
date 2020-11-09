@extends('coordenador.detalhesEvento')

@section('menu')
<div id="divInscricoes" class="comissao" style="display: block">
    {{-- <div class="row">
        <div class="col-sm-12">
            <h1 class="titulo-detalhes">Inscrições</h1>
        </div>
    </div> --}}
    <ul class="nav nav-tabs">
        <li id="li_promocoes" class="aba aba-tab" onclick="ativarLink(this)"><a data-toggle="tab" href="#promocoes" style="text-decoration: none;">Promoções</a></li>
        <li id="li_cuponsDeDesconto" class="aba aba-tab" onclick="ativarLink(this)"><a data-toggle="tab" href="#cuponsDeDesconto" style="text-decoration: none;">Cupons de desconto</a></li>
    </ul>
    
    <div class="tab-content">
        <div id="promocoes" class="tab-pane fade in active">
            {{-- <h3>Promoções</h3> --}}
            <p>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card" style="width: 100%; right: 25px;">
                            <div class="card-body">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <h5 class="card-title">Promoções</h5>
                                            <h6 class="card-subtitle mb-2 text-muted">Promoções ou pacotes que o evento pode oferecer.</h6>
                                            <small>Clique em uma promoção para editar</small>
                                        </div>
                                        <div class="col-sm-6">
                                            <button id="criarPromocao" data-toggle="modal" data-target="#modalCriarPromocao" class="btn btn-primary float-md-right">+ Criar promoção</button>
                                        </div>
                                    </div>
                                </div>
                                <p class="card-text" style="position:relative; top: 30px;">  
                                    <table class="table table-hover table-responsive-lg table-sm" style="position: relative;">
                                        <thead>
                                            <th>
                                                <th>Identificador</th>
                                                <th>Valor pago/recebido</th>
                                                <th>Visualizar</th>
                                                <th>Excluir</th>
                                            </th>
                                        </thead>
                                        {{-- @foreach ($atividades as $atv)
                                        
                                            <tbody>
                                                <th>
                                                    <td data-toggle="modal" data-target="#modalAtividadeEdit{{$atv->id}}">{{$atv->titulo}}</td>
                                                    <td data-toggle="modal" data-target="#modalAtividadeEdit{{$atv->id}}">{{$atv->tipoAtividade->descricao}}</td>
                                                    <td data-toggle="modal" data-target="#modalAtividadeEdit{{$atv->id}}">@if(empty($atv->vagas)) Ilimitado @else {{$atv->vagas}} @endif</td>
                                                    <td data-toggle="modal" data-target="#modalAtividadeEdit{{$atv->id}}">@if(empty($atv->valor)) Grátis @else R$ {{$atv->valor}},00 @endif</td>
                                                    <td data-toggle="modal" data-target="#modalAtividadeEdit{{$atv->id}}">{{$atv->local}}</td>
                                                    <td data-toggle="modal" data-target="#modalAtividadeEdit{{$atv->id}}">@if(empty($atv->carga_horaria)) Nenhuma @else {{$atv->carga_horaria}} @endif</td>
                                                    <td><input id="checkbox_{{$atv->id}}" type="checkbox" @if($atv->visibilidade_participante) checked @endif onclick="setVisibilidadeAtv({{$atv->id}})"></td>
                                                    <td data-toggle="modal" data-target="#modalExcluirAtividade{{$atv->id}}"><button style="border: none; background-color: rgba(255, 255, 255, 0);"><img src="{{asset('img/icons/trash-alt-regular.svg')}}" class="icon-card" alt=""></button></td>
                                                </th>
                                            </tbody>
                                        @endforeach --}}
                                    </table>
                                </p>
                            </div>
                          </div>
                    </div>
                </div>
            </p>
        </div>
        <div id="cuponsDeDesconto" class="tab-pane fade">
            {{-- <h3>Cupons de desconto</h3> --}}
            <p>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card" style="width: 100%; right: 30px;">
                            <div class="card-body">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <h5 class="card-title">Cupons de desconto</h5>
                                            <h6 class="card-subtitle mb-2 text-muted">Cupons que podem ser aplicados na hora da inscrição.</h6> 
                                        </div>
                                        <div class="col-sm-6">
                                            <button id="criarAtividade" data-toggle="modal" data-target="#modalCriarAtividade" class="btn btn-primary float-md-right">+ Criar cupom</button>
                                        </div>
                                    </div>
                                </div>
                                <p class="card-text">  
                                <table class="table table-hover table-responsive-lg table-sm" style="position: relative;">
                                    <thead>
                                        <th>
                                            <th>Identificador</th>
                                            <th>Valor descontado</th>
                                            <th>Detalhes</th>
                                            <th>Excluir</th>
                                        </th>
                                    </thead>
                                    {{-- @foreach ($atividades as $atv)
                                    
                                        <tbody>
                                            <th>
                                                <td data-toggle="modal" data-target="#modalAtividadeEdit{{$atv->id}}">{{$atv->titulo}}</td>
                                                <td data-toggle="modal" data-target="#modalAtividadeEdit{{$atv->id}}">{{$atv->tipoAtividade->descricao}}</td>
                                                <td data-toggle="modal" data-target="#modalAtividadeEdit{{$atv->id}}">@if(empty($atv->vagas)) Ilimitado @else {{$atv->vagas}} @endif</td>
                                                <td data-toggle="modal" data-target="#modalAtividadeEdit{{$atv->id}}">@if(empty($atv->valor)) Grátis @else R$ {{$atv->valor}},00 @endif</td>
                                                <td data-toggle="modal" data-target="#modalAtividadeEdit{{$atv->id}}">{{$atv->local}}</td>
                                                <td data-toggle="modal" data-target="#modalAtividadeEdit{{$atv->id}}">@if(empty($atv->carga_horaria)) Nenhuma @else {{$atv->carga_horaria}} @endif</td>
                                                <td><input id="checkbox_{{$atv->id}}" type="checkbox" @if($atv->visibilidade_participante) checked @endif onclick="setVisibilidadeAtv({{$atv->id}})"></td>
                                                <td data-toggle="modal" data-target="#modalExcluirAtividade{{$atv->id}}"><button style="border: none; background-color: rgba(255, 255, 255, 0);"><img src="{{asset('img/icons/trash-alt-regular.svg')}}" class="icon-card" alt=""></button></td>
                                            </th>
                                        </tbody>
                                    @endforeach --}}
                                </table>
                            </p>
                            </div>
                        </div>
                    </div>
                </div>
            </p>
    </div>
</div>
@endsection