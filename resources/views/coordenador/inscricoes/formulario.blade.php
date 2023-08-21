@extends('coordenador.detalhesEvento')
@section('menu')
    <div id="" style="display: block">
        <div class="row">
            <div class="col-md-12">
                <h1 class="titulo-detalhes">Formulário de inscrição</h1>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <h5 class="card-title">Campos do formulário</h5>
                                <h6 class="card-subtitle mb-2 text-muted">Campos que o formulário de inscrição vai ter.</h6>
                            </div>
                            <div class="col-sm-6">
                                <button id="criarCampo" data-toggle="modal" data-target="#modalCriarCampo" class="btn btn-primary float-md-right">+ Novo campo</button>
                            </div>
                        </div>
                        <p class="card-text">
                            <div class="row" style="position: relative; right: 25px;">
                                @if ($campos != null && count($campos) > 0)
                                    @foreach ($campos as $campo)
                                        <div class="col-sm-3">
                                            <div class="card" style="width: 15rem; height: 11rem;">
                                                <div class="card-body">
                                                <h5 class="card-title">{{$campo->titulo}}</h5>
                                                @if ($campo->obrigatorio)
                                                    <h6 class="card-subtitle mb-2 text-muted">Obrigatório</h6>
                                                @else
                                                    <h6 class="card-subtitle mb-2 text-muted">Opcional</h6>
                                                @endif
                                                @if ($campo->tipo == "text")
                                                    <h6 class="card-subtitle mb-2 text-muted">Campo de texto</h6>
                                                @elseif ($campo->tipo == "email")
                                                    <h6 class="card-subtitle mb-2 text-muted">E-mail</h6>
                                                @elseif ($campo->tipo == "date")
                                                    <h6 class="card-subtitle mb-2 text-muted">Calendario</h6>
                                                @elseif ($campo->tipo == "file")
                                                    <h6 class="card-subtitle mb-2 text-muted">Submeter um arquivo</h6>
                                                @elseif ($campo->tipo == "endereco")
                                                    <h6 class="card-subtitle mb-2 text-muted">Campos de endereço</h6>
                                                @elseif ($campo->tipo == "contato")
                                                    <h6 class="card-subtitle mb-2 text-muted">Contato</h6>
                                                @elseif ($campo->tipo == "cpf")
                                                    <h6 class="card-subtitle mb-2 text-muted">Campo de CPF</h6>
                                                @endif
                                                <a href="#" class="card-link button-a btn-excluir" data-toggle="modal" data-target="#modalCampoDelete{{$campo->id}}">Excluir</a>
                                                <a href="#" class="card-link button-a btn-editar" data-toggle="modal" data-target="#modalCampoEdit{{$campo->id}}">Editar</a>
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
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalCriarCampo" tabindex="-1" role="dialog" aria-labelledby="modalCriarCampoLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #114048ff; color: white;">
            <h5 class="modal-title" id="modalCriarCampoLabel">Novo campo do formulario</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <form id="formCriarCampo" action="{{route('campo.formulario.store')}}" method="POST">
                    @csrf
                    <input type="hidden" name="evento_id" id="" value="{{$evento->id}}">
                    <input type="hidden" name="criarCampo" id="" value="0">
                    <input type="hidden" id="tipo_campo" name="tipo_campo" value="">
                    <input type="hidden" name="para_todas" value="1">
                    @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                          <p>{{$error}}</p>
                        @endforeach
                      </div>
                  @endif
                    <div class="container">
                        <div id="escolherInput">
                            <p>
                                <div class="row justify-content-center">
                                    <div class="col-sm-6">
                                        <button id="btn-tipo-email" type="button" class="btn btn-primary largura-maxima" onclick="mostrarCampos('email')">E-mail</button>
                                    </div>
                                </div>
                            </p>
                            <p>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <button id="btn-tipo-text" type="button" class="btn btn-primary largura-maxima" onclick="mostrarCampos('text')">Texto</button>
                                    </div>
                                    <div class="col-sm-6">
                                        <button id="btn-tipo-file" type="button" class="btn btn-primary largura-maxima" onclick="mostrarCampos('file')">Arquivo</button>
                                    </div>
                                </div>
                            </p>
                            <p>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <button id="btn-tipo-date" type="button" class="btn btn-primary largura-maxima" onclick="mostrarCampos('date')">Data</button>
                                    </div>
                                    <div class="col-sm-6">
                                        <button id="btn-tipo-endereco" type="button" class="btn btn-primary largura-maxima" onclick="mostrarCampos('endereco')">Endereço</button>
                                    </div>
                                </div>
                            </p>
                            <p>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <button id="btn-tipo-date" type="button" class="btn btn-primary largura-maxima" onclick="mostrarCampos('cpf')">CPF</button>
                                    </div>
                                    <div class="col-sm-6">
                                        <button id="btn-tipo-contato" type="button" class="btn btn-primary largura-maxima" onclick="mostrarCampos('contato')">Contato</button>
                                    </div>
                                </div>
                            </p>
                        </div>
                        <div id="preencherDados" style="display: none;">
                            <div class="row form-group">
                                <div class="col-sm-12">
                                    <label for="titulo_do_campo">Titulo do campo*</label>
                                    <input type="text" id="titulo_do_campo" name="titulo_do_campo" class="form-control @error('titulo_do_campo') is-invalid @enderror" required value="{{old('titulo_do_campo')}}">

                                    @error('titulo_do_campo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-12">
                                    <input type="checkbox" value="1" id="campo_obrigatorio" name="campo_obrigatorio" @if (old('campo_obrigatorio') != null) checked @endif>
                                    <label for="campo_obrigatorio">Campo obrigatório</label>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <hr>
                                    <h4>Exemplo</h4>
                                    <div id="tituloExemplo" class="">
                                        <label id="labelCampoExemplo" for="campoExemplo"></label>
                                        <p><input type="" class="" id="campoExemplo" style="display: block"></p>
                                        <p><input type="text" class="form-control" id="campoExemploCpf" style="display: none;"></p>
                                        <p><input type="text" class="form-control" id="campoExemploNumero" style="display: none;"></p>
                                    </div>
                                </div>
                            </div>
                            <div id="divEnderecoExemplo" class="" style="display: none;">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label for="cep">CEP</label>
                                        <input onblur="pesquisacep(this.value);" type="text" class="form-control" id="cep" placeholder="00000-000">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="bairro">Bairro</label>
                                        <input type="text" class="form-control" id="bairro" placeholder="Centro">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label for="rua">Rua</label>
                                        <input type="text" class="form-control" id="rua" placeholder="Av. 15 de Novembro">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label for="cidade">Cidade</label>
                                        <input type="text" class="form-control" id="cidade" placeholder="Recife">
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="uf">UF</label>
                                        <select class="form-control" id="uf">
                                            <option value="" disabled selected hidden>-- UF --</option>
                                            <option value="AC">AC</option>
                                            <option value="AL">AL</option>
                                            <option value="AP">AP</option>
                                            <option value="AM">AM</option>
                                            <option value="BA">BA</option>
                                            <option value="CE">CE</option>
                                            <option value="DF">DF</option>
                                            <option value="ES">ES</option>
                                            <option value="GO">GO</option>
                                            <option value="MA">MA</option>
                                            <option value="MT">MT</option>
                                            <option value="MS">MS</option>
                                            <option value="MG">MG</option>
                                            <option value="PA">PA</option>
                                            <option value="PB">PB</option>
                                            <option value="PR">PR</option>
                                            <option value="PE">PE</option>
                                            <option value="PI">PI</option>
                                            <option value="RJ">RJ</option>
                                            <option value="RN">RN</option>
                                            <option value="RS">RS</option>
                                            <option value="RO">RO</option>
                                            <option value="RR">RR</option>
                                            <option value="SC">SC</option>
                                            <option value="SP">SP</option>
                                            <option value="SE">SE</option>
                                            <option value="TO">TO</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="numero">Número</label>
                                        <input type="number" class="form-control" id="numero" placeholder="10">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div id="botoesDeSubmissao" class="modal-footer" style="display: none;">
                <button type="button" class="btn btn-secondary" onclick="voltarBotoes()">Voltar</button>
                <button type="submit" class="btn btn-primary" form="formCriarCampo">Salvar</button>
            </div>
        </div>
        </div>
    </div>
    {{-- Fim do modal criar campo --}}

    @foreach ($campos as $campo)
    {{-- modal excluir campo --}}
    <div class="modal fade" id="modalCampoDelete{{$campo->id}}" tabindex="-1" role="dialog" aria-labelledby="modalCampoDelete{{$campo->id}}Label" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #114048ff; color: white;">
            <h5 class="modal-title" id="modalCampoDelete{{$campo->id}}Label">Confirmação</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <form id="formDeletarCampo{{$campo->id}}" action="{{route('campo.destroy', ['id' => $campo->id])}}" method="POST">
                    @csrf
                    Tem certeza que deseja excluir esse campo?
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                <button type="submit" class="btn btn-primary" form="formDeletarCampo{{$campo->id}}">Sim</button>
            </div>
        </div>
        </div>
    </div>
    {{-- fim modal excluir campo --}}
    {{-- modal editar campo --}}
    <div class="modal fade" id="modalCampoEdit{{$campo->id}}" tabindex="-1" role="dialog" aria-labelledby="modalCampoEdit{{$campo->id}}Label" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #114048ff; color: white;">
            <h5 class="modal-title" id="modalCampoEdit{{$campo->id}}Label">Editar campo {{$campo->titulo}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <form id="formEditCampo{{$campo->id}}" action="{{route('campo.edit', ['id' => $campo->id])}}" method="POST">
                    @csrf
                    <input type="hidden" name="evento_id" value="{{$evento->id}}">
                    <input type="hidden" name="campo_id" value="{{$campo->id}}">
                    <input type="hidden" name="para_todas" value="1">
                    <div class="container">
                        <div class="row form-group">
                            <div class="col-sm-12">
                                <label for="titulo_do_campo{{$campo->id}}">Titulo do campo*</label>
                                <input type="text" id="titulo_do_campo{{$campo->id}}" name="titulo_do_campo" class="form-control @error('titulo_do_campo') is-invalid @enderror" required value="@if(old('titulo_do_campo') != null){{old('titulo_do_campo')}}@else{{$campo->titulo}}@endif">

                                @error('titulo_do_campo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-sm-12">
                                <input type="checkbox" id="campo_obrigatorio{{$campo->id}}" name="campo_obrigatório" @if (old('campo_obrigatorio') != null) checked @elseif($campo->obrigatorio) checked @endif>
                                <label for="campo_obrigatorio{{$campo->id}}">Campo obrigatório</label>

                                @error('campo_obrigatório')
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
                <button type="submit" class="btn btn-primary" form="formEditCampo{{$campo->id}}">Salvar</button>
            </div>
        </div>
        </div>
    </div>
    {{-- fim modal editar campo --}}
    @endforeach
@endsection
