@extends('coordenador.detalhesEvento')

@section('menu')
<div id="divInscricoes" class="comissao" style="display: block">
    <ul class="nav nav-tabs">
        <li id="li_promocoes" class="aba aba-tab" onclick="ativarLink(this)"><a data-toggle="tab" href="#promocoes" style="text-decoration: none;">Promoções</a></li>
        <li id="li_cuponsDeDesconto" class="aba aba-tab" onclick="ativarLink(this)"><a data-toggle="tab" href="#cuponsDeDesconto" style="text-decoration: none;">Cupons de desconto</a></li>
        <li id="li_categoria_participante" class="aba aba-tab" onclick="ativarLink(this)"><a data-toggle="tab" href="#categoriaParticipante" style="text-decoration: none;">Catagorias de participantes</a></li>
        <li id="li_formulari_inscricao" class="aba aba-tab" onclick="ativarLink(this)"><a data-toggle="tab" href="#formularioInscricao" style="text-decoration: none;">Formulario de inscrição</a></li>
    </ul>
    
    <div class="tab-content">
        <div id="promocoes" class="tab-pane fade in active">
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
                                                <th style="text-align:center">Visualizar</th>
                                                <th style="text-align:center">Excluir</th>
                                            </th>
                                        </thead>
                                        @foreach ($promocoes as $promocao)
                                            <tbody>
                                                <th>
                                                    <td>{{$promocao->identificador}}</td>
                                                    <td>Pendencia para programar</td>
                                                    @if ($promocao->valor == null || $promocao->valor <= 0)
                                                        <td>Grátis</td>
                                                    @else 
                                                        <td>R$ {{number_format($promocao->valor, 2,',','.')}} / R$ {{number_format($promocao->valor - $promocao->valor * 0.10, 2,',','.')}}</td>
                                                    @endif
                                                    <td style="text-align:center"><a href="#" data-toggle="modal" data-target="#modalPromocaoEdit{{$promocao->id}}" ><img src="{{asset('img/icons/eye-regular.svg')}}" style="width:20px"></a></td>
                                                    <td style="text-align:center"><a href="#" data-toggle="modal" data-target="#modalPromocaoDelete{{$promocao->id}}"><img src="{{asset('img/icons/trash-alt-regular.svg')}}" class="icon-card" alt=""></a></td>
                                                </th>
                                            </tbody>
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
                                            <button id="criarCupom" data-toggle="modal" data-target="#modalCriarCupom" class="btn btn-primary float-md-right">+ Criar cupom</button>
                                        </div>
                                    </div>
                                </div>
                                <p class="card-text">  
                                <table class="table table-hover table-responsive-lg table-sm" style="position: relative;">
                                    <thead>
                                        <th>
                                            <th>Identificador</th>
                                            <th>Valor descontado</th>
                                            <th>Quantidade disponível/aplicada</th>
                                            <th>Início</th>
                                            <th>Fim</th>
                                            <th style="text-align:center">Excluir</th>
                                        </th>
                                    </thead>
                                    @foreach ($cupons as $cupom)
                                        <tbody>
                                            <th>
                                                <td>{{$cupom->identificador}}</td>
                                                @if ($cupom->porcentagem) 
                                                    <td>{{$cupom->valor}}% do valor da inscrição</td>
                                                @else
                                                    <td>R$ {{number_format($cupom->valor, 2,',','.')}}</td>
                                                @endif
                                                <td>@if($cupom->quantidade_aplicacao == -1) Ilimitado @else {{$cupom->quantidade_aplicacao}} @endif / precisa ser programada</td>
                                                <td>{{date('d/m/Y',strtotime($cupom->inicio))}}</td>
                                                <td>{{date('d/m/Y',strtotime($cupom->fim))}}</td>
                                                <td style="text-align:center"><a href="#" data-toggle="modal" data-target="#modalExcluirCupom{{$cupom->id}}"><img src="{{asset('img/icons/trash-alt-regular.svg')}}" class="icon-card" alt=""></a></td>
                                            </th>
                                        </tbody>

                                        {{-- Modal excluir cupom --}}
                                            <div class="modal fade" id="modalExcluirCupom{{$cupom->id}}" tabindex="-1" role="dialog" aria-labelledby="modalExcluirCupom{{$cupom->id}}Label" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header" style="background-color: #114048ff; color: white;">
                                                    <h5 class="modal-title" id="modalExcluirCupom{{$cupom->id}}Label">Confirmação</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form id="formDeletarCupom{{$cupom->id}}" action="{{route('cupom.destroy', ['id' => $cupom->id])}}" method="GET">
                                                            Tem certeza que deseja excluir esse cupom?
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                                                        <button type="submit" class="btn btn-primary" form="formDeletarCupom{{$cupom->id}}">Sim</button>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                        {{-- Fim modal excluir cupom --}}
                                    @endforeach
                                </table>
                            </p>
                            </div>
                        </div>
                    </div>
                </div>
            </p>
        </div>
        <div id="categoriaParticipante" class="tab-pane fade in active">
            <p>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card" style="width: 100%; right: 30px;">
                            <div class="card-body">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <h5 class="card-title">Categorias de participantes</h5>
                                            <h6 class="card-subtitle mb-2 text-muted">Categorias dos participantes que seu evento irá receber.</h6> 
                                        </div>
                                        <div class="col-sm-6">
                                            <button id="criarCategoria" data-toggle="modal" data-target="#modalCriarCategoria" class="btn btn-primary float-md-right">+ Criar categoria</button>
                                        </div>
                                    </div>
                                </div>
                                <p class="card-text">  
                                <table class="table table-hover table-responsive-lg table-sm" style="position: relative;">
                                    <thead>
                                        <th>
                                            <th>Nome</th>
                                            <th>Quantidade inscrita</th>
                                            <th>Valor arrecadado</th>
                                            <th style="text-align:center">Detalhes</th>
                                            <th style="text-align:center">Excluir</th>
                                        </th>
                                    </thead>
                                    @foreach ($categorias as $categoria)
                                        <tbody>
                                            <th>
                                                <td>{{$categoria->nome}}</td>
                                                <td>Falta implementar</td>
                                                <td>Falta implementar</td>
                                                <td style="text-align:center"><a href="#" data-toggle="modal" data-target="#modalDetalhesCategoria{{$categoria->id}}" ><img src="{{asset('img/icons/eye-regular.svg')}}" style="width:20px"></a></td>
                                                <td style="text-align:center"><a href="#" data-toggle="modal" data-target="#modalExcluirCategoria{{$categoria->id}}"><img src="{{asset('img/icons/trash-alt-regular.svg')}}" class="icon-card" alt=""></a></td>
                                            </th>
                                        </tbody>

                                        {{-- Modal detalhes categoria --}}
                                            <div class="modal fade" id="modalDetalhesCategoria{{$categoria->id}}" tabindex="-1" role="dialog" aria-labelledby="modalDetalhesCategoria{{$categoria->id}}Label" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header" style="background-color: #114048ff; color: white;">
                                                    <h5 class="modal-title" id="modalDetalhesCategoria{{$categoria->id}}Label">Detalhes</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="container">
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <label for="nome">Categoria</label>
                                                                    <input type="text" id="nome" class="form-control" value="{{$categoria->nome}}" disabled>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <label for="valorTotal">Valor pago</label>
                                                                    <input type="text" id="valorTotal" class="form-control" value="R$ {{number_format($categoria->valor_total, 2,',','.')}}" disabled>
                                                                </div>
                                                            </div>
                                                            @if ($categoria->valores != null && count($categoria->valores) > 0)
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <hr>
                                                                        <h4>Periodos com desconto</h4>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        Periodo
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        Desconto / Valor
                                                                    </div>
                                                                </div>
                                                                @foreach ($categoria->valores as $valor)
                                                                <p>
                                                                    <div class="row">
                                                                        <div class="col-sm-6">
                                                                            De {{date('d/m/Y',strtotime($valor->inicio_prazo))}} até {{date('d/m/Y',strtotime($valor->fim_prazo))}}
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            @if ($valor->porcentagem)
                                                                                R$ {{number_format($categoria->valor_total * ($valor->valor / 100), 2,',','.')}} / R$ {{number_format($categoria->valor_total - $categoria->valor_total * ($valor->valor / 100), 2,',','.')}}
                                                                                <br><small>Desconto de {{$valor->valor}}% do valor total</small>
                                                                            @else   
                                                                                R$ {{number_format($valor->valor, 2,',','.')}} / R$ {{number_format($categoria->valor_total - $valor->valor, 2,',','.')}}
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </p>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                        {{-- Fim modal detalhes categoria --}}

                                        {{-- Modal excluir categoria --}}
                                            <div class="modal fade" id="modalExcluirCategoria{{$categoria->id}}" tabindex="-1" role="dialog" aria-labelledby="modalExcluirCategoria{{$categoria->id}}Label" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header" style="background-color: #114048ff; color: white;">
                                                    <h5 class="modal-title" id="modalExcluirCategoria{{$categoria->id}}Label">Confirmação</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form id="formDeletarCategoria{{$categoria->id}}" action="{{route('categoria.destroy', ['id' => $categoria->id])}}" method="GET">
                                                            Tem certeza que deseja excluir essa categoria?
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                                                        <button type="submit" class="btn btn-primary" form="formDeletarCategoria{{$categoria->id}}">Sim</button>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                        {{-- Fim modal excluir cupom --}}
                                    @endforeach 
                                </table>
                            </p>
                            </div>
                        </div>
                    </div>
                </div>
            </p> 
        </div>
        <div id="formularioInscricao" class="tab-pane fade">
            <p>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card" style="width: 100%; right: 30px;">
                            <div class="card-body">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <h5 class="card-title">Campos do formulário</h5>
                                            <h6 class="card-subtitle mb-2 text-muted">Campos que o formulário de inscrição vai ter.</h6> 
                                        </div>
                                        <div class="col-sm-6">
                                            <button id="novoCampo" data-toggle="modal" data-target="#modalNovoCampo" class="btn btn-primary float-md-right">+ Novo campo</button>
                                        </div>
                                    </div>
                                </div>
                                <p class="card-text">  
                                    <div class="container">
                                        <div class="row" style="position: relative; right: 25px;">
                                            @if ($campos != null && count($campos) > 0)
                                                @foreach ($campos as $campo)
                                                    <div class="col-sm-3">
                                                        <div class="card" style="width: 15rem;">
                                                            <div class="card-body">
                                                            <h5 class="card-title">{{$campo->titulo}}</h5>
                                                            @if ($campo->obrigatorio)
                                                                <h6 class="card-subtitle mb-2 text-muted">Obrigatório</h6>
                                                            @else
                                                                <h6 class="card-subtitle mb-2 text-muted">Opcional</h6>
                                                            @endif
                                                            
                                                            <a href="#" class="card-link">Excluir</a>
                                                            <a href="#" class="card-link">Editar</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach 
                                            @else
                                                <div class="col-sm-12" style="position: relative; left: 25px;">
                                                    Nenhum campo extra salvo.
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </p>
        </div>
    </div>
</div>

<!-- Modal criar promocao -->
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
                                    <label for="quantidade">Disponibilidade* <img src="{{asset('img/icons/interrogacao.png')}}" alt="" width="15px;" style='position:relative; left:5px; border: solid 1px; border-radius:50px; padding: 2px;' title='Coloque 0 para a disponibilidade ser ilimitada.'></label>
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
{{-- Fim do modal criar promoção --}}

@foreach ($promocoes as $promocao)
{{-- Modal visualizar promocao --}}
    <div class="modal fade modal-example-lg" id="modalPromocaoEdit{{$promocao->id}}" tabindex="-1" role="dialog" aria-labelledby="modalPromocaoEdit{{$promocao->id}}Label" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #114048ff; color: white;">
            <h5 class="modal-title" id="modalPromocaoEdit{{$promocao->id}}Label">{{$promocao->identificador}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6">
                            <h5>Valor que o inscrito irá pagar</h5>
                            @if ($promocao->valor == null || $promocao->valor <= 0)
                                <p>A promoção é gratuita</p>
                            @else
                                <p>R$ {{number_format($promocao->valor, 2,',','.')}}</p>
                            @endif
                        </div>
                        <div class="col-sm-6">
                            <h5>Valor recebido</h5>
                            @if ($promocao->valor == null || $promocao->valor <= 0)
                                <p>A promoção é gratuita</p>
                            @else
                                <p>R$ {{number_format($promocao->valor - $promocao->valor * 0.10, 2,',','.')}}*<br><span style="font-size: 10px;">*Taxa de 10%</span></p>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <h5>Descrição</h5>
                            <textarea class="form-control" id="" disabled>{{$promocao->descricao}}</textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <hr>
                            <h4>Lotes</h4>
                        </div>
                    </div>
                    <div class="row">
                        @foreach ($promocao->lotes as $lote)
                            <div class="col-sm-3">
                                <h5>Data início</h5>
                                <p>
                                    {{date('d/m/Y',strtotime($lote->inicio_validade))}}
                                </p>
                            </div>
                            <div class="col-sm-3">
                                <h5>Data final</h5>
                                <p>
                                    {{date('d/m/Y',strtotime($lote->fim_validade))}}
                                </p>
                            </div>
                            <div class="col-sm-5">
                                <h5>Quantidade disponível/aplicada</h5>
                                <p>
                                    {{$lote->quantidade_de_aplicacoes}}/Pendencia para programar
                                </p>
                            </div>
                        @endforeach
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <hr>
                            <h4>Atividades inclusas na promoção</h4>
                        </div>
                    </div>
                    <div class="row">
                        <table class="table table-hover table-responsive-lg table-sm" style="position: relative;">
                            <thead>
                                <th>
                                    <th>Título</th>
                                    <th>Local</th>
                                    <th>Valor original</th>
                                </th>
                            </thead>
                            @foreach ($promocao->atividades()->orderBy('titulo')->get() as $atv)
                                <tbody>
                                    <th>
                                        <td>{{$atv->titulo}}</td>
                                        <td>{{$atv->local}}</td>
                                        @if ($atv->valor == null || $atv->valor <= 0)
                                            <th>Grátis</th>
                                        @else 
                                            <th>R$ {{number_format($atv->valor, 2,',','.')}}</th>
                                        @endif
                                    </th>
                                </tbody>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>    
{{-- Fim modal visualizar promocao --}}
{{-- Modal de confirmação para deletar a promoção --}}
    <div class="modal fade" id="modalPromocaoDelete{{$promocao->id}}" tabindex="-1" role="dialog" aria-labelledby="modalPromocaoDelete{{$promocao->id}}Label" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #114048ff; color: white;">
            <h5 class="modal-title" id="modalPromocaoDelete{{$promocao->id}}Label">Confirmação</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <form id="formDeletarPromocao{{$promocao->id}}" action="{{route('promocao.destroy', ['id' => $promocao->id])}}" method="POST">
                    @csrf
                    Tem certeza que deseja excluir essa promoção?
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                <button type="submit" class="btn btn-primary" form="formDeletarPromocao{{$promocao->id}}">Sim</button>
            </div>
        </div>
        </div>
    </div>
{{-- Fim do modal de confirmação --}}
@endforeach
{{-- Modal cria cupom de desconto --}}
    <div class="modal fade" id="modalCriarCupom" tabindex="-1" role="dialog" aria-labelledby="modalCriarCupomLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #114048ff; color: white;">
            <h5 class="modal-title" id="modalCriarCupomLabel">Criar cupom</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <form id="formCriarCupom" action="{{route('cupom.store')}}" method="POST">
                    @csrf
                    <input type="hidden" name="evento_id" id="" value="{{$evento->id}}">
                    <input type="hidden" name="criarCupom" id="" value="0">
                    <div class="container">
                        <div class="row form-group">
                            <div class="col-sm-8">
                                <label for="identificadorCupom">Identificador*</label>
                                <input id="identificadorCupom" name="identificador" type="text" class="form-control @error('identificador') is-invalid @enderror" value="{{old('identificador')}}" oninput="deixarMaiusculo(event)">
                            
                                @error('identificador')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-sm-4"> 
                                <label for="quantidadeCupom">Disponibilidade* <img src="{{asset('img/icons/interrogacao.png')}}" alt="" width="15px;" style='position:relative; left:5px; border: solid 1px; border-radius:50px; padding: 2px;' title='Coloque 0 para a disponibilidade ser ilimitada.'></label>
                                <input id="quantidadeCupom" name="quantidade" class="form-control  @error('quantidade') is-invalid @enderror" type="number" placeholder="10" value="{{old('quantidade')}}">
                            
                                @error('quantidade')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-sm-4">
                                <label for="">Valor do desconto*</label>
                                <br>
                                @if (old('tipo_valor') != null)
                                    <input id="porcetagem" type="radio" name="tipo_valor" value="porcentagem" onchange="alterarPlaceHolderDoNumero(this)" required @if(old('tipo_valor') == "porcentagem") checked @endif>
                                    <label for="porcetagem">Porcentagem</label><br>
                                    <input id="real" type="radio" name="tipo_valor" value="real" onchange="alterarPlaceHolderDoNumero(this)" required @if(old('tipo_valor') == "real") checked @endif>
                                    <label for="real">Real</label>
                                @else 
                                    <input id="porcetagem" type="radio" name="tipo_valor" value="porcentagem" onchange="alterarPlaceHolderDoNumero(this)" required >
                                    <label for="porcetagem">Porcentagem</label><br>
                                    <input id="real" type="radio" name="tipo_valor" value="real" onchange="alterarPlaceHolderDoNumero(this)" required>
                                    <label for="real">Real</label>
                                @endif
                            </div>
                            <div class="col-sm-8" style="position: relative; top: 45px;">
                                <input id="valorCupom" name="valor" type="number" class="form-control real @error('number') is-invalid @enderror" placeholder="" value="{{old('valor')}}">

                                @error('number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-sm-6"> 
                                <label for="inicio">Data de início*</label>
                                <input id="inicio" name="início" class="form-control @error('início') is-invalid @enderror" type="date" value="{{old('início')}}">
                                
                                @error('início')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-sm-6"> 
                                <label for="fim">Data de fim*</label>
                                <input id="fim" name="fim" class="form-control @error('fim') is-invalid @enderror" type="date" value="{{old('fim')}}">
                            
                                @error('fim')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary" form="formCriarCupom">Salvar</button>
            </div>
        </div>
        </div>
    </div>
{{-- Fim do modal criar cupom --}}

{{-- Modal criar categoria --}}
    <div class="modal fade" id="modalCriarCategoria" tabindex="-1" role="dialog" aria-labelledby="modalCriarCategoriaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #114048ff; color: white;">
            <h5 class="modal-title" id="modalCriarCategoriaLabel">Criar categoria</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <form id="formCriarCategoria" action="{{route('categoria.participante.store')}}" method="POST">
                    @csrf
                    <input type="hidden" name="evento_id" id="" value="{{$evento->id}}">
                    <input type="hidden" name="criarCategoria" id="" value="0">
                    <div class="container">
                        <div class="row form-group">
                            <div class="col-sm-6">
                                <label for="nome">Nome*</label>
                                <input id="nome" name="nome" type="text" class="form-control apenasLetras @error('nome') is-invalid @enderror" value="{{old('nome')}}" placeholder="Estudante">
                            
                                @error('nome')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-sm-6">
                                <label for="valor_total">Valor da inscrição*</label>
                                <input id="valor_total" name="valor_total" type="text" class="form-control @error('valor_total') is-invalid @enderror" value="{{old('valor_total')}}" placeholder="R$ 50,00">
                            
                                @error('valor_total')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div id="periodosCategoria">
                            @if (old('criarCategoria') != null && old('tipo_valor') != null)
                            @foreach (old('tipo_valor') as $i => $item)
                                @if ($i == 0)
                                    <div id='tituloDePeriodo' class='row form-group'>
                                        <div class='col-sm-12'>
                                            <hr>
                                            <h4>Periodos de desconto</h4>
                                        </div>
                                    </div>
                                    <div class='peridodoDesconto'>
                                        <div class='row form-group'>
                                            <div class='col-sm-4'>
                                                <label for=''>Valor do desconto*</label>
                                                <br>
                                                <select class='form-control @error('tipo_valor.'.$i) is-invalid @enderror' name='tipo_valor[]' required>
                                                    <option value='' disabled selected>-- Escolha o tipo de valor --</option>
                                                    <option value='porcentagem' @if(old('tipo_valor.'.$i) == "porcentagem") selected @endif>Porcentagem</option>
                                                    <option value='real' @if(old('tipo_valor.'.$i) == "real") selected @endif>Real</option>
                                                </select>

                                                @error('tipo_valor.'.$i)
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class='col-sm-6'>
                                                <label for="valorDesconto">Valor</label>
                                                <input id='valorDesconto' name='valorDesconto[]' type='number' class='form-control real @error('valorDesconto.'.$i) is-invalid @enderror' placeholder='' value='{{old('valorDesconto.'.$i)}}' required>
                                            
                                                @error('valorDesconto.'.$i)
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class='row form-group'>
                                            <div class='col-sm-5'> 
                                                <label for='inicio'>Data de início*</label> 
                                                <input id='inicio' name='inícioDesconto[]' class='form-control @error('inícioDesconto.'.$i) is-invalid @enderror' type='date' value='{{old('inícioDesconto.'.$i)}}' required>
                                                @error('inícioDesconto.'.$i)
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class='col-sm-5'>
                                                <label for='fim'>Data de fim*</label>
                                                <input id='fim' name='fimDesconto[]' class='form-control @error('fimDesconto.'.$i) is-invalid @enderror' type='date' value='{{old('fimDesconto.'.$i)}}' required>
                                                @error('fimDesconto.'.$i)
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            
                                            <div class='col-sm-2' style='position: relative; top: 35px;'>
                                                <a type='button' onclick='removerPeriodoDesconto(this)'><img src='{{asset('img/icons/trash-alt-regular.svg')}}' class='icon-card' alt=''></a>
                                            </div>
                                        </div>
                                    </div>
                                @else 
                                    <div class='peridodoDesconto'>
                                        <div class='row form-group'>
                                            <div class='col-sm-4'>
                                                <label for=''>Valor do desconto*</label>
                                                <br>
                                                <select class='form-control @error('tipo_valor.'.$i) is-invalid @enderror' name='tipo_valor[]' required>
                                                    <option value='' disabled selected>-- Escolha o tipo de valor --</option>
                                                    <option value='porcentagem' @if(old('tipo_valor.'.$i) == "porcentagem") selected @endif>Porcentagem</option>
                                                    <option value='real' @if(old('tipo_valor.'.$i) == "real") selected @endif>Real</option>
                                                </select>

                                                @error('tipo_valor.'.$i)
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class='col-sm-6'>
                                                <label for="valorDesconto">Valor</label>
                                                <input id='valorDesconto' name='valorDesconto[]' type='number' class='form-control real @error('valorDesconto.'.$i) is-invalid @enderror' placeholder='' value='{{old('valorDesconto.'.$i)}}' required>
                                            
                                                @error('valorDesconto.'.$i)
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class='row form-group'>
                                            <div class='col-sm-5'> 
                                                <label for='inicio'>Data de início*</label> 
                                                <input id='inicio' name='inícioDesconto[]' class='form-control @error('inícioDesconto.'.$i) is-invalid @enderror' type='date' value='{{old('inícioDesconto.'.$i)}}' required>
                                                @error('inícioDesconto.'.$i)
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class='col-sm-5'>
                                                <label for='fim'>Data de fim*</label>
                                                <input id='fim' name='fimDesconto[]' class='form-control @error('fimDesconto.'.$i) is-invalid @enderror' type='date' value='{{old('fimDesconto.'.$i)}}' required>
                                                @error('fimDesconto.'.$i)
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            
                                            <div class='col-sm-2' style='position: relative; top: 35px;'>
                                                <a type='button' onclick='removerPeriodoDesconto(this)'><img src='{{asset('img/icons/trash-alt-regular.svg')}}' class='icon-card' alt=''></a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                            @endif
                        </div>
                        <div class="row form-group">
                            <div class="col-sm-12 justify-content-center">
                                <button type="button" class="btn btn-primary" style="width: 100%;" onclick="adicionarPeriodoCategoria()">Adicionar periodo antecipado</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary" form="formCriarCategoria">Salvar</button>
            </div>
        </div>
        </div>
    </div>
{{-- Fim modal criar categoria --}}
@endsection