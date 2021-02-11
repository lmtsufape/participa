@extends('layouts.app')

@section('content')
<div class="container">
    <form id="formConfirmarInscricao" action="" method="">
        @csrf
        <input type="hidden" name="inscricao_id" value="{{$inscricao->id}}">
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
                    <label>Pacote</label>
                    <input type="text" class="form-control" disabled value="{{$promocao->identificador}}">
                </div>
                <div class="col-sm-2">
                    <label>Taxa com o pacote</label>
                    <input type="text" class="form-control" disabled value="@if($promocao->valor != null && $promocao->valor > 0)R$ {{number_format($promocao->valor, 2,',','.')}}@else Gratuita @endif">
                </div>
            @else 
                <div class="col-sm-6">
                    <label>Taxa do evento</label>
                    <input type="text" class="form-control" disabled value="{{$inscricao->categoria->valor_total}}">
                </div>
            @endif
        </div>
        @if ($inscricao->camposPreenchidos()->count() > 0)
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <h5>Informações extras</h5>
                </div>
            </div>
            <div class="row">
                @foreach ($inscricao->camposPreenchidos as $campo)
                    
                    @switch($campo->tipo)
                        @case("endereco")
                            @php
                                $enderecoExtra = App\Models\Submissao\Endereco::find($campo->pivot->valor);
                            @endphp
                            <div class="col-sm-12" style="margin-top: 10px; margin-bottom: 10px;">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label for="endereco-cep-{{$campo->id}}">CEP</label>
                                        <input id="endereco-cep-{{$campo->id}}" name="endereco-cep-{{$campo->id}}" onblur="pesquisacep(this.value, '{{$campo->id}}');" type="text" class="form-control cep" placeholder="00000-000" value="{{$enderecoExtra->cep}}" disabled>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="endereco-bairro-{{$campo->id}}">Bairro</label>
                                        <input type="text" class="form-control" id="endereco-bairro-{{$campo->id}}" name="endereco-bairro-{{$campo->id}}" placeholder="" value="{{$enderecoExtra->bairro}}" disabled>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-sm-9">
                                        <label for="endereco-rua-{{$campo->id}}">Rua</label>
                                        <input type="text" class="form-control" id="endereco-rua-{{$campo->id}}" name="endereco-rua-{{$campo->id}}" placeholder="" value="{{$enderecoExtra->rua}}" disabled>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="endereco-complemento-{{$campo->id}}">Complemento</label>
                                        <input type="text" class="form-control" id="endereco-complemento-{{$campo->id}}" name="endereco-complemento-{{$campo->id}}" placeholder=""  value="{{$enderecoExtra->complemento}}" disabled>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-sm-6">
                                        <label for="endereco-cidade-{{$campo->id}}">Cidade</label>
                                        <input type="text" class="form-control" id="endereco-cidade-{{$campo->id}}" name="endereco-cidade-{{$campo->id}}" placeholder="" value="{{$enderecoExtra->cidade}}" disabled>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="endereco-uf-{{$campo->id}}">UF</label>
                                        <select class="form-control" id="endereco-uf-{{$campo->id}}" name="endereco-uf-{{$campo->id}}" disabled>
                                            <option value="" disabled selected hidden>-- UF --</option>
                                            <option @if($enderecoExtra->uf == "AC") selected @endif value="AC">AC</option>
                                            <option @if($enderecoExtra->uf == "AL") selected @endif value="AL">AL</option>
                                            <option @if($enderecoExtra->uf == "AP") selected @endif value="AP">AP</option>
                                            <option @if($enderecoExtra->uf == "AM") selected @endif value="AM">AM</option>
                                            <option @if($enderecoExtra->uf == "BA") selected @endif value="BA">BA</option>
                                            <option @if($enderecoExtra->uf == "CE") selected @endif value="CE">CE</option>
                                            <option @if($enderecoExtra->uf == "DF") selected @endif value="DF">DF</option>
                                            <option @if($enderecoExtra->uf == "ES") selected @endif value="ES">ES</option>
                                            <option @if($enderecoExtra->uf == "GO") selected @endif value="GO">GO</option>
                                            <option @if($enderecoExtra->uf == "MA") selected @endif value="MA">MA</option>
                                            <option @if($enderecoExtra->uf == "MT") selected @endif value="MT">MT</option>
                                            <option @if($enderecoExtra->uf == "MS") selected @endif value="MS">MS</option>
                                            <option @if($enderecoExtra->uf == "MG") selected @endif value="MG">MG</option>
                                            <option @if($enderecoExtra->uf == "PA") selected @endif value="PA">PA</option>
                                            <option @if($enderecoExtra->uf == "PB") selected @endif value="PB">PB</option>
                                            <option @if($enderecoExtra->uf == "PR") selected @endif value="PR">PR</option>
                                            <option @if($enderecoExtra->uf == "PE") selected @endif value="PE">PE</option>
                                            <option @if($enderecoExtra->uf == "PI") selected @endif value="PI">PI</option>
                                            <option @if($enderecoExtra->uf == "RJ") selected @endif value="RJ">RJ</option>
                                            <option @if($enderecoExtra->uf == "RN") selected @endif value="RN">RN</option>
                                            <option @if($enderecoExtra->uf == "RS") selected @endif value="RS">RS</option>
                                            <option @if($enderecoExtra->uf == "RO") selected @endif value="RO">RO</option>
                                            <option @if($enderecoExtra->uf == "RR") selected @endif value="RR">RR</option>
                                            <option @if($enderecoExtra->uf == "SC") selected @endif value="SC">SC</option>
                                            <option @if($enderecoExtra->uf == "SP") selected @endif value="SP">SP</option>
                                            <option @if($enderecoExtra->uf == "SE") selected @endif value="SE">SE</option>
                                            <option @if($enderecoExtra->uf == "TO") selected @endif value="TO">TO</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="endereco-numero-{{$campo->id}}">Número</label>
                                        <input type="number" class="form-control numero" id="endereco-numero-{{$campo->id}}" name="endereco-numero-{{$campo->id}}" placeholder="10" value="{{$enderecoExtra->numero}}" disabled>
                                        
                                    </div>
                                </div>
                            </div>
                            @break
                        @case("date")
                            <div class="col-sm-4" style="margin-top:10px;">
                                <label for="date-{{$campo->id}}">{{$campo->titulo}}</label>
                                <input class="form-control @error('date-'.$campo->id) is-invalid @enderror" type="date" name="date-{{$campo->id}}" id="date-{{$campo->id}}" value="{{$campo->pivot->valor}}" disabled>
                            </div>
                            @break
                        @case("email")
                            <div class="col-sm-4" style="margin-top:10px;">
                                <label for="email-{{$campo->id}}">{{$campo->titulo}}</label>
                                <input class="form-control @error('email-'.$campo->id) is-invalid @enderror" type="email" name="email-{{$campo->id}}" id="email-{{$campo->id}}" value="{{$campo->pivot->valor}}" disabled>
                            </div>
                            @break
                        @case("text")
                            <div class="col-sm-4" style="margin-top:10px;">
                                <label for="text-{{$campo->id}}">{{$campo->titulo}}</label>
                                <input class="form-control" type="text" name="text-{{$campo->id}}" id="text-{{$campo->id}}" value="{{$campo->pivot->valor}}" disabled>
                            </div>
                            @break
                        @case("cpf")
                            <div class="col-sm-4"  style="margin-top:10px;">
                                <label for="cpf-{{$campo->id}}">{{$campo->titulo}}</label>
                                <input id="cpf-{{$campo->id}}" type="text" class="form-control cpf" name="cpf-{{$campo->id}}" autocomplete="cpf" autofocus value="{{$campo->pivot->valor}}" disabled>
                            </div>
                            @break
                        @case("contato")
                            <div class="col-sm-4" style="margin-top:10px;">
                                <label for="contato-{{$campo->id}}">{{$campo->titulo}}</label>
                                <input id="contato-{{$campo->id}}" type="text" class="form-control celular" name="contato-{{$campo->id}}" autocomplete="contato" autofocus value="{{$campo->pivot->valor}}" disabled>
                            </div>
                            @break
                        @case("file")
                            <div class="col-sm-4"  style="margin-top:10px;">
                                {{$campo->titulo}}: <a href="{{route('download.arquivo.inscricao', ['idInscricao' => $inscricao->id,'idCampo' => $campo->id])}}">Arquivo</a>
                            </div>
                            @break
                    @endswitch
                @endforeach
            </div>         
        @endif
        @if ($promocao != null && count($promocao->atividades) > 0)
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <h5>Atividades extras inclusas na pacote</h5>
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
            
            {{--  --}}
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
            form.action = "{{route('inscricao.voltar', ['id' => $inscricao->id])}}";
            form.method = "GET";
            form.submit();
        }

        function confirmarInscricao() {
            $.ajax({
                url: "{{route('inscricao.confirmar')}}",
                method: 'get',
                type: 'get',
                data: {
                    _token: '{{csrf_token()}}',
                    inscricao_id: '{{$inscricao->id}}',
                },
                statusCode: {
                    200: function() {
                        alert('Inscrição salva, redirecionando para o pagamento...');
                        pagamento();
                    }
                }
            });
            
        }

        function pagamento() {
            var form = document.getElementById('formConfirmarInscricao');
            form.action = "{{route('checkout.index', ['id' => $evento->id])}}"
            form.method = "POST";
            form.submit();
        }
    </script>
@endsection