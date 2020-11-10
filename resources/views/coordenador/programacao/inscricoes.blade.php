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
                                            {{-- <small>Clique em uma promoção para editar</small> --}}
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
                                                <th>Quantidade total/aplicada</th>
                                                <th>Valor pago/recebido</th>
                                                <th>Visualizar</th>
                                                <th>Excluir</th>
                                            </th>
                                        </thead>
                                        @foreach ($promocoes as $promocao)
                                            {{-- <tbody>
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
                                            </tbody> --}}
                                        @endforeach
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

<!-- Modal -->
<div class="modal fade modal-example-lg" id="modalCriarPromocao" tabindex="-1" role="dialog" aria-labelledby="modalCriarPromocaoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #114048ff; color: white;">
          <h5 class="modal-title" id="modalCriarPromocaoLabel">Criar promoção</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="formCriarPromocao" action="{{route('promocao.store')}}" method="POST">
                @csrf
                <input type="hidden" name="novaPromocao" id="" value="0">
                <input type="hidden" name="evento_id" value="{{$evento->id}}">
                <div class="container form-group">
                    <div class="row">
                        <div class="col-sm-8">
                            <label for="identificador">Identificador*</label>
                            <input id="identificador" name="identificador" class="form-control apenasLetras @error('identificador') is-invalid @enderror" type="text" placeholder="Pacote padrão" value="{{old('identificador')}}">
                            
                            @error('identificador')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-sm-4">
                            <label for="valor">Valor da promoção*</label>
                            <input id="valor" name="valor" class="form-control @error('valor') is-invalid @enderror" type="number" placeholder="0 para promoção grátis" value="{{old('valor')}}">
                        
                            @error('valor')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="descricao">Descrição</label>
                            <textarea class="form-control @error('descrição') is-invalid @enderror" name="descrição" id="descricao" cols="30" rows="3" placeholder="Pacote padrão para estudantes">{{old('descrição')}}</textarea>
                            
                            @error('descrição')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <hr>
                            <h5>Lotes</h5>
                        </div>
                    </div>
                    {{-- {{dd(old('dataDeInício'))}} --}}
                    <div id="lotes">
                        @if (old('dataDeInício') != null || old('dataDeFim') != null || old('disponibilidade') != null)
                            @foreach (old('dataDeInício') as $key => $dataInicio)
                                @if ($key == 0)
                                    <div class="row">
                                        <div class="col-sm-4"> 
                                            <label for="dataDeInicio">Data de início*</label>
                                            <input id="dataDeInicio" name="dataDeInício[]" class="form-control @error('dataDeInício.'.$key) is-invalid @enderror" type="date" value="{{old('dataDeInício.'.$key)}}">
                                            
                                            @error('dataDeInício.'.$key)
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-4"> 
                                            <label for="dataDeFim">Data de fim*</label>
                                            <input id="dataDeFim" name="dataDeFim[]" class="form-control @error('dataDeFim.'.$key) is-invalid @enderror" type="date" value="{{old('dataDeFim.'.$key)}}">
                                        
                                            @error('dataDeFim.'.$key)
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-3"> 
                                            <label for="quantidade">Disponibilidade*</label>
                                            <input id="quantidade" name="disponibilidade[]" class="form-control  @error('disponibilidade.'.$key) is-invalid @enderror" type="number" placeholder="10" value="{{old('disponibilidade.'.$key)}}">
                                        
                                            @error('disponibilidade.'.$key)
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-1">
                                            <a href="#" title="Adicionar lote" onclick="adicionarLoteAhPromocao()"><img src="{{asset('img/icons/plus-square-solid_black.svg')}}" alt="" width="35px" style="position: relative; top: 32px;"></a>
                                        </div>
                                    </div>
                                @else 
                                    <div class="row">
                                        <div class="col-sm-4"> 
                                            <label for="dataDeInicio">Data de início*</label>
                                            <input id="dataDeInicio" name="dataDeInício[]" class="form-control @error('dataDeInício.'.$key) is-invalid @enderror" type="date" value="{{old('dataDeInício.'.$key)}}">
                                            
                                            @error('dataDeInício.'.$key)
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-4"> 
                                            <label for="dataDeFim">Data de fim*</label>
                                            <input id="dataDeFim" name="dataDeFim[]" class="form-control @error('dataDeFim.'.$key) is-invalid @enderror" type="date" value="{{old('dataDeFim.'.$key)}}">
                                        
                                            @error('dataDeFim.'.$key)
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-3"> 
                                            <label for="quantidade">Disponibilidade*</label>
                                            <input id="quantidade" name="disponibilidade[]" class="form-control  @error('disponibilidade.'.$key) is-invalid @enderror" type="number" placeholder="10" value="{{old('disponibilidade.'.$key)}}">
                                        
                                            @error('disponibilidade.'.$key)
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-1">
                                            <a href="#" title="Remover lote" onclick="removerLoteDaPromocao(this)"><img src="{{asset('img/icons/lixo.png')}}" alt="" width="35px" style="position: relative; top: 32px;"></a>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @else 
                            <div class="row">
                                <div class="col-sm-4"> 
                                    <label for="dataDeInicio">Data de início*</label>
                                    <input id="dataDeInicio" name="dataDeInício[]" class="form-control @error('dataDeInício.*') is-invalid @enderror" type="date">
                                    
                                    @error('dataDeInício.*')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-sm-4"> 
                                    <label for="dataDeFim">Data de fim*</label>
                                    <input id="dataDeFim" name="dataDeFim[]" class="form-control @error('dataDeFim.*') is-invalid @enderror" type="date">
                                
                                    @error('dataDeFim.*')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-sm-3"> 
                                    <label for="quantidade">Disponibilidade*</label>
                                    <input id="quantidade" name="disponibilidade[]" class="form-control  @error('disponibilidade.*') is-invalid @enderror" type="number" placeholder="10">
                                
                                    @error('disponibilidade.*')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-sm-1">
                                    <a href="#" title="Adicionar lote" onclick="adicionarLoteAhPromocao()"><img src="{{asset('img/icons/plus-square-solid_black.svg')}}" alt="" width="35px" style="position: relative; top: 32px;"></a>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <hr>
                            <h5>Atividades inclusas na promoção</h5>
                        </div>
                    </div>
                    <div class="row">
                        @foreach ($atividades as $atv)
                            <div class="col-sm-3">
                                @if (old('atividades') != null && in_array($atv->id, old('atividades'))) 
                                    <input id="atividade_{{$atv->id}}" type="checkbox" value="{{$atv->id}}" name="atividades[]" checked>
                                    <label for="atividade_{{$atv->id}}">{{$atv->titulo}}</label>
                                @else 
                                    <input id="atividade_{{$atv->id}}" type="checkbox" value="{{$atv->id}}" name="atividades[]">
                                    <label for="atividade_{{$atv->id}}">{{$atv->titulo}}</label>
                                @endif
                            </div>
                        @endforeach
                    </div>
                  </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary" form="formCriarPromocao">Salvar</button>
        </div>
      </div>
    </div>
</div>
@endsection