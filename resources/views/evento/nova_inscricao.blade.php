@extends('layouts.app')

@section('content')
<div class="container-fluid content">
    <div class="row">
        @if($evento->is_multilingual && Session::get('idiomaAtual') === 'en' && isset($evento->fotoEvento_en))
            <div class="banner-evento">
                <img src="{{asset('storage/'.$evento->fotoEvento_en)}}" alt="">
            </div>
        @elseif($evento->is_multilingual && Session::get('idiomaAtual') === 'es' && isset($evento->fotoEvento_es))
            <div class="banner-evento">
                <img src="{{asset('storage/'.$evento->fotoEvento_es)}}" alt="">
            </div>
        @elseif(isset($evento->fotoEvento))
          <div class="banner-evento">
              <img src="{{asset('storage/eventos/'.$evento->id.'/logo.png')}}" alt="">
          </div>
        @else
          <div class="banner-evento">
              <img src="{{asset('img/colorscheme.png')}}" alt="">
          </div>
          {{-- <img class="front-image-evento" src="{{asset('img/colorscheme.png')}}" alt=""> --}}
        @endif
    </div>
</div>

<div class="card-inscricao-evento justify-content-center">
    <div id="card-inscricao" class="card" style="width: 80%;">
        <div class="card-body">
            <div class="container">
                <div class="row justify-content-center titulo-detalhes">
                    <div class="col-sm-12">
                        @if($evento->is_multilingual && Session::get('idiomaAtual') === 'en')
                            <h1>New registration for {{$evento->nome_en}}</h1>
                        @elseif($evento->is_multilingual && Session::get('idiomaAtual') === 'es')
                            <h1>Nueva inscripción para {{$evento->nome_es}}</h1>
                        @else
                            <h1>Nova inscrição para {{$evento->nome}}</h1>
                        @endif
                    </div>
                </div>
                @error('atvIguais')
                    <div class="row justify-content-center">
                        <div class="col-sm-12">
                            @include('componentes.mensagens')
                        </div>
                    </div>
                @enderror
                <br>

                <form id="form-inscricao" action="{{route('inscricao.checar', ['id' => $evento->id])}}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @if ($inscricao != null)
                        <input type="hidden" name="revisandoInscricao" value="{{$inscricao->id}}">
                        <div id="formulario">
                            <div class="row form-group">
                                <div class="col-sm-12">
                                    <label for="categoria">Escolha sua categoria como participante</label>
                                    <select name="categoria" id="categoria" class="form-control" onchange="carregarInfoExtra(this)">
                                        <option value="" disabled selected>-- Escolha sua categoria --</option>
                                        @foreach ($evento->categoriasParticipantes as $categoria)
                                            <option value="{{$categoria->id}}" @if(old('categoria') == $categoria->id) selected @elseif(old('categoria') == null && $inscricao->categoria->id == $categoria->id) selected @endif>{{$categoria->nome}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @foreach($evento->categoriasParticipantes as $categoria)
                                <div class="campos-extras" id="campos-extras-{{$categoria->id}}"  @if(old('categoria') == $categoria->id)style="display: block;"@elseif(old('categoria') == null && $inscricao->categoria->id == $categoria->id) style="display: block;" @else style="display: none;" @endif>
                                    <div class="row form-group">
                                        @foreach ($categoria->camposNecessarios()->orderBy('tipo')->get() as $campo)
                                            @php
                                                $campoPreechido = $inscricao->camposPreenchidos()->where('campo_formulario_id', '=', $campo->id)->first();
                                            @endphp
                                            @if($campo->tipo == "endereco")
                                                @php
                                                    $enderecoPreenchido = App\Models\Submissao\Endereco::find($campoPreechido->id);
                                                @endphp
                                                <div class="col-sm-12" style="margin-top: 10px; margin-bottom: 10px;">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label for="endereco-cep-{{$campo->id}}">CEP</label>
                                                            <input id="endereco-cep-{{$campo->id}}" name="endereco-cep-{{$campo->id}}" onblur="pesquisacep(this.value, '{{$campo->id}}');" type="text" class="form-control cep @error('endereco-cep-'.$campo->id) is-invalid @enderror" placeholder="00000-000" @if($campo->obrigatorio) required @endif value="@if(old('endereco-cep-'.$campo->id) != null){{old('endereco-cep-'.$campo->id)}}@elseif($inscricao->categoria->id == $categoria->id && $campoPreechido->id == $campo->id){{$enderecoPreenchido->cep}}@endif">

                                                            @error('endereco-cep-'.$campo->id)
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <label for="endereco-bairro-{{$campo->id}}">Bairro</label>
                                                            <input type="text" class="form-control @error('endereco-bairro-'.$campo->id) is-invalid @enderror" id="endereco-bairro-{{$campo->id}}" name="endereco-bairro-{{$campo->id}}" placeholder="" @if($campo->obrigatorio) required @endif value="@if(old('endereco-bairro-'.$campo->id) != null){{old('endereco-bairro-'.$campo->id)}}@elseif($inscricao->categoria->id == $categoria->id && $campoPreechido->id == $campo->id){{$enderecoPreenchido->bairro}}@endif">

                                                            @error('endereco-bairro-'.$campo->id)
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="row" style="margin-top: 10px;">
                                                        <div class="col-sm-9">
                                                            <label for="endereco-rua-{{$campo->id}}">Rua</label>
                                                            <input type="text" class="form-control @error('endereco-rua-'.$campo->id) is-invalid @enderror" id="endereco-rua-{{$campo->id}}" name="endereco-rua-{{$campo->id}}" placeholder="" @if($campo->obrigatorio) required @endif value="@if(old('endereco-rua-'.$campo->id) != null){{old('endereco-rua-'.$campo->id)}}@elseif($inscricao->categoria->id == $categoria->id && $campoPreechido->id == $campo->id){{$enderecoPreenchido->rua}}@endif">

                                                            @error('endereco-rua-'.$campo->id)
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label for="endereco-complemento-{{$campo->id}}">Complemento</label>
                                                            <input type="text" class="form-control @error('endereco-complemento-'.$campo->id) is-invalid @enderror" id="endereco-complemento-{{$campo->id}}" name="endereco-complemento-{{$campo->id}}" placeholder="" @if($campo->obrigatorio) required @endif value="@if(old('endereco-complemento-'.$campo->id) != null){{old('endereco-complemento-'.$campo->id)}}@elseif($inscricao->categoria->id == $categoria->id && $campoPreechido->id == $campo->id){{$enderecoPreenchido->complemento}}@endif">

                                                            @error('endereco-complemento-'.$campo->id)
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="row" style="margin-top: 10px;">
                                                        <div class="col-sm-6">
                                                            <label for="endereco-cidade-{{$campo->id}}">Cidade</label>
                                                            <input type="text" class="form-control @error('endereco-cidade-'.$campo->id) is-invalid @enderror" id="endereco-cidade-{{$campo->id}}" name="endereco-cidade-{{$campo->id}}" placeholder="" @if($campo->obrigatorio) required @endif value="@if(old('endereco-cidade-'.$campo->id) != null){{old('endereco-cidade-'.$campo->id)}}@elseif($inscricao->categoria->id == $categoria->id && $campoPreechido->id == $campo->id){{$enderecoPreenchido->cidade}}@endif">

                                                            @error('endereco-cidade-'.$campo->id)
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label for="endereco-uf-{{$campo->id}}">UF</label>
                                                            <select class="form-control @error('endereco-uf-'.$campo->id) is-invalid @enderror" id="endereco-uf-{{$campo->id}}" name="endereco-uf-{{$campo->id}}" @if($campo->obrigatorio) required @endif>
                                                                @if (old('endereco-uf-'.$campo->id) == null && $inscricao->categoria->id == $categoria->id && $campoPreechido->id == $campo->id)
                                                                    <option value="" disabled selected hidden>-- UF --</option>
                                                                    <option @if($enderecoPreenchido->uf == "AC") selected @endif value="AC">AC</option>
                                                                    <option @if($enderecoPreenchido->uf == "AL") selected @endif value="AL">AL</option>
                                                                    <option @if($enderecoPreenchido->uf == "AP") selected @endif value="AP">AP</option>
                                                                    <option @if($enderecoPreenchido->uf == "AM") selected @endif value="AM">AM</option>
                                                                    <option @if($enderecoPreenchido->uf == "BA") selected @endif value="BA">BA</option>
                                                                    <option @if($enderecoPreenchido->uf == "CE") selected @endif value="CE">CE</option>
                                                                    <option @if($enderecoPreenchido->uf == "DF") selected @endif value="DF">DF</option>
                                                                    <option @if($enderecoPreenchido->uf == "ES") selected @endif value="ES">ES</option>
                                                                    <option @if($enderecoPreenchido->uf == "GO") selected @endif value="GO">GO</option>
                                                                    <option @if($enderecoPreenchido->uf == "MA") selected @endif value="MA">MA</option>
                                                                    <option @if($enderecoPreenchido->uf == "MT") selected @endif value="MT">MT</option>
                                                                    <option @if($enderecoPreenchido->uf == "MS") selected @endif value="MS">MS</option>
                                                                    <option @if($enderecoPreenchido->uf == "MG") selected @endif value="MG">MG</option>
                                                                    <option @if($enderecoPreenchido->uf == "PA") selected @endif value="PA">PA</option>
                                                                    <option @if($enderecoPreenchido->uf == "PB") selected @endif value="PB">PB</option>
                                                                    <option @if($enderecoPreenchido->uf == "PR") selected @endif value="PR">PR</option>
                                                                    <option @if($enderecoPreenchido->uf == "PE") selected @endif value="PE">PE</option>
                                                                    <option @if($enderecoPreenchido->uf == "PI") selected @endif value="PI">PI</option>
                                                                    <option @if($enderecoPreenchido->uf == "RJ") selected @endif value="RJ">RJ</option>
                                                                    <option @if($enderecoPreenchido->uf == "RN") selected @endif value="RN">RN</option>
                                                                    <option @if($enderecoPreenchido->uf == "RS") selected @endif value="RS">RS</option>
                                                                    <option @if($enderecoPreenchido->uf == "RO") selected @endif value="RO">RO</option>
                                                                    <option @if($enderecoPreenchido->uf == "RR") selected @endif value="RR">RR</option>
                                                                    <option @if($enderecoPreenchido->uf == "SC") selected @endif value="SC">SC</option>
                                                                    <option @if($enderecoPreenchido->uf == "SP") selected @endif value="SP">SP</option>
                                                                    <option @if($enderecoPreenchido->uf == "SE") selected @endif value="SE">SE</option>
                                                                    <option @if($enderecoPreenchido->uf == "TO") selected @endif value="TO">TO</option>
                                                                @else
                                                                    <option value="" disabled selected hidden>-- UF --</option>
                                                                    <option @if(old('endereco-uf-'.$campo->id) == "AC") selected @endif value="AC">AC</option>
                                                                    <option @if(old('endereco-uf-'.$campo->id) == "AL") selected @endif value="AL">AL</option>
                                                                    <option @if(old('endereco-uf-'.$campo->id) == "AP") selected @endif value="AP">AP</option>
                                                                    <option @if(old('endereco-uf-'.$campo->id) == "AM") selected @endif value="AM">AM</option>
                                                                    <option @if(old('endereco-uf-'.$campo->id) == "BA") selected @endif value="BA">BA</option>
                                                                    <option @if(old('endereco-uf-'.$campo->id) == "CE") selected @endif value="CE">CE</option>
                                                                    <option @if(old('endereco-uf-'.$campo->id) == "DF") selected @endif value="DF">DF</option>
                                                                    <option @if(old('endereco-uf-'.$campo->id) == "ES") selected @endif value="ES">ES</option>
                                                                    <option @if(old('endereco-uf-'.$campo->id) == "GO") selected @endif value="GO">GO</option>
                                                                    <option @if(old('endereco-uf-'.$campo->id) == "MA") selected @endif value="MA">MA</option>
                                                                    <option @if(old('endereco-uf-'.$campo->id) == "MT") selected @endif value="MT">MT</option>
                                                                    <option @if(old('endereco-uf-'.$campo->id) == "MS") selected @endif value="MS">MS</option>
                                                                    <option @if(old('endereco-uf-'.$campo->id) == "MG") selected @endif value="MG">MG</option>
                                                                    <option @if(old('endereco-uf-'.$campo->id) == "PA") selected @endif value="PA">PA</option>
                                                                    <option @if(old('endereco-uf-'.$campo->id) == "PB") selected @endif value="PB">PB</option>
                                                                    <option @if(old('endereco-uf-'.$campo->id) == "PR") selected @endif value="PR">PR</option>
                                                                    <option @if(old('endereco-uf-'.$campo->id) == "PE") selected @endif value="PE">PE</option>
                                                                    <option @if(old('endereco-uf-'.$campo->id) == "PI") selected @endif value="PI">PI</option>
                                                                    <option @if(old('endereco-uf-'.$campo->id) == "RJ") selected @endif value="RJ">RJ</option>
                                                                    <option @if(old('endereco-uf-'.$campo->id) == "RN") selected @endif value="RN">RN</option>
                                                                    <option @if(old('endereco-uf-'.$campo->id) == "RS") selected @endif value="RS">RS</option>
                                                                    <option @if(old('endereco-uf-'.$campo->id) == "RO") selected @endif value="RO">RO</option>
                                                                    <option @if(old('endereco-uf-'.$campo->id) == "RR") selected @endif value="RR">RR</option>
                                                                    <option @if(old('endereco-uf-'.$campo->id) == "SC") selected @endif value="SC">SC</option>
                                                                    <option @if(old('endereco-uf-'.$campo->id) == "SP") selected @endif value="SP">SP</option>
                                                                    <option @if(old('endereco-uf-'.$campo->id) == "SE") selected @endif value="SE">SE</option>
                                                                    <option @if(old('endereco-uf-'.$campo->id) == "TO") selected @endif value="TO">TO</option>
                                                                @endif
                                                            </select>

                                                            @error('endereco-uf-'.$campo->id)
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label for="endereco-numero-{{$campo->id}}">Número</label>
                                                            <input type="number" class="form-control numero @error('endereco-numero-'.$campo->id) is-invalid @enderror" id="endereco-numero-{{$campo->id}}" name="endereco-numero-{{$campo->id}}" placeholder="10" @if($campo->obrigatorio) required @endif value="@if(old('endereco-numero-'.$campo->id) != null){{old('endereco-numero-'.$campo->id)}}@elseif($inscricao->categoria->id == $categoria->id && $campoPreechido->id == $campo->id){{$enderecoPreenchido->numero}}@endif" maxlength="10">

                                                            @error('endereco-numero-'.$campo->id)
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif($campo->tipo == "date")
                                                <div class="col-sm-4" style="margin-top:10px;">
                                                    <label for="date-{{$campo->id}}">{{$campo->titulo}}</label>
                                                    <input class="form-control @error('date-'.$campo->id) is-invalid @enderror" type="date" name="date-{{$campo->id}}" id="date-{{$campo->id}}" @if($campo->obrigatorio) required @endif value="@if(old('date-'.$campo->id) != null){{old('date-'.$campo->id)}}@elseif($inscricao->categoria->id == $categoria->id && $campoPreechido->id == $campo->id){{$campoPreechido->pivot->valor}}@endif">

                                                    @error('date-'.$campo->id)
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            @elseif($campo->tipo == "email")
                                                <div class="col-sm-4" style="margin-top:10px;">
                                                    <label for="email-{{$campo->id}}">{{$campo->titulo}}</label>
                                                    <input class="form-control @error('email-'.$campo->id) is-invalid @enderror" type="email" name="email-{{$campo->id}}" id="email-{{$campo->id}}" @if($campo->obrigatorio) required @endif value="@if(old('email-'.$campo->id) != null){{old('email-'.$campo->id)}}@elseif($inscricao->categoria->id == $categoria->id && $campoPreechido->id == $campo->id){{$campoPreechido->pivot->valor}}@endif">

                                                    @error('email-'.$campo->id)
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            @elseif($campo->tipo == "text")
                                                <div class="col-sm-4" style="margin-top:10px;">
                                                    <label for="text-{{$campo->id}}">{{$campo->titulo}}</label>
                                                    <input class="form-control @error('text-'.$campo->id) is-invalid @enderror" type="text" name="text-{{$campo->id}}" id="text-{{$campo->id}}" @if($campo->obrigatorio) required @endif value="@if(old('text-'.$campo->id) != null){{old('text-'.$campo->id)}}@elseif($inscricao->categoria->id == $categoria->id && $campoPreechido->id == $campo->id){{$campoPreechido->pivot->valor}}@endif">

                                                    @error('text-'.$campo->id)
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            @elseif($campo->tipo == "cpf")
                                                <div class="col-sm-4"  style="margin-top:10px;">
                                                    <label for="cpf-{{$campo->id}}">{{$campo->titulo}}</label>
                                                    <input id="cpf-{{$campo->id}}" type="text" class="form-control cpf @error('cpf-'.$campo->id) is-invalid @enderror" name="cpf-{{$campo->id}}" autocomplete="cpf" autofocus  @if($campo->obrigatorio) required @endif value="@if(old('cpf-'.$campo->id) != null){{old('cpf-'.$campo->id)}}@elseif($inscricao->categoria->id == $categoria->id && $campoPreechido->id == $campo->id){{$campoPreechido->pivot->valor}}@endif">

                                                    @error('cpf-'.$campo->id)
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            @elseif($campo->tipo == "contato")
                                                <div class="col-sm-4" style="margin-top:10px;">
                                                    <label for="contato-{{$campo->id}}">{{$campo->titulo}}</label>
                                                    <input id="contato-{{$campo->id}}" type="text" class="form-control celular @error('contato-'.$campo->id) is-invalid @enderror" name="contato-{{$campo->id}}" autocomplete="contato" autofocus  @if($campo->obrigatorio) required @endif value="@if(old('contato-'.$campo->id) != null){{old('contato-'.$campo->id)}}@elseif($inscricao->categoria->id == $categoria->id && $campoPreechido->id == $campo->id){{$campoPreechido->pivot->valor}}@endif">

                                                    @error('contato-'.$campo->id)
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            @elseif($campo->tipo == "file")
                                                <div class="col-sm-12"  style="margin-top:10px;">
                                                    <label for="file-{{$campo->id}}" class="">{{$campo->titulo}}</label>@if($inscricao->categoria->id == $categoria->id && $campoPreechido->id == $campo->id) <a href="{{route('download.arquivo.inscricao', ['idInscricao' => $inscricao->id,'idCampo' => $campoPreechido->id])}}">Arquivo atual</a> @endif<br>
                                                    <input type="file" id="file-{{$campo->id}}" class="form-control-file  @error('file-'.$campo->id) is-invalid @enderror" name="file-{{$campo->id}}" @if($campo->obrigatorio) required @endif>
                                                    <br>
                                                    @if($inscricao->categoria->id == $categoria->id && $campoPreechido->id == $campo->id)
                                                        <small>Para substituir o arquivo envie outro</small>
                                                    @endif
                                                    @error('file-'.$campo->id)
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach

                            @foreach($evento->categoriasParticipantes as $categoria)
                                <div id="extra-form-pkt-atv-{{$categoria->id}}" class="extra-form-pkt-atv" @if(old('categoria') == $categoria->id)style="display: block;"@elseif(old('categoria') == null && $inscricao->categoria->id == $categoria->id) style="display: block;" @else style="display: none;" @endif>
                                    <div class="row form-group">
                                        <div class="col-sm-9">
                                            @if ($categoria->promocoes()->count() > 0)
                                                <label for="promocao">Pacote</label>
                                                <select name="promocao" id="promocao" class="form-control" onchange="carregarAtividadesDoPacote(this)">
                                                    <option value="" disabled selected>-- Escolha um pacote --</option>
                                                    @foreach ($categoria->promocoes as $pacote)
                                                        <option value="{{$pacote->id}}" @if(old('promocao') == $pacote->id) selected @elseif(old('promocao')==null && $inscricao->promocao != null && $inscricao->promocao->id == $pacote->id) selected @endif>{{$pacote->identificador}}</option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <label for="promocao">Pacote</label>
                                                <input type="text" disabled class="form-control" value="Não existe pacotes para essa categoria">
                                            @endif
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="cupomDesconto">Cupom de desconto</label>
                                            <input oninput="deixarMaiusculo(event)" id="cupomDesconto-{{$categoria->id}}" type="text" class="form-control" placeholder="" onchange="checarCupom(this)" value="@if(old('cupom') != null){{old('cupom')}}@elseif(old('cupom')==null && $inscricao->cupomDesconto != null){{$inscricao->cupomDesconto->identificador}}@endif">

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

                                    @if ($inscricao->promocao != null)
                                        <div class="row form-group">
                                            <div id="descricaoPromo" class="col-sm-12">
                                                @if ($inscricao->promocao->descricao != null)
                                                    <textarea id='descricaoPromo' class='form-control' name='descricaoPromo' readonly>{{$inscricao->promocao->descricao}}</textarea>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <h4>Atividades incluidas no pacote</h4>
                                                    </div>
                                                </div>
                                                <div id="atividadesPromocionais" class="row">
                                                    <span id="padraoPromocional" style="margin: 20px; @if($inscricao->promocao->atividades != null)display: none; @endif">Nenhuma atividade adicionada para essa promoção.</span>
                                                    @if ($inscricao->promocao->atividades != null)
                                                        @foreach ($inscricao->promocao->atividades as $atv)
                                                            <div id="atvPromocao{{$atv->id}}" class="col-sm-3 atvAdicionais">
                                                                <input type="hidden" id="atvPromocaoInput{{$atv->id}}" name="atividadesPromo[]" value="{{$atv->id}}">
                                                                <div class="card" style="width: 16rem;">
                                                                    <div class="card-body">
                                                                        <h4 class="card-title">{{$atv->titulo}}</h4>
                                                                        <h5 class="card-subtitle mb-2 text-muted">{{$atv->tipoAtividade->descricao}}</h5>
                                                                        <h6 class="card-subtitle mb-2 text-muted">{{$atv->local}}</h6>
                                                                        <p class="card-text">{{$atv->descricao}}</p>
                                                                        <a href='#' class='card-link' data-bs-toggle='modal' data-bs-target='#modalDetalheAtividade{{$atv->id}}'>Detalhes</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        @if ($categoria->promocoes()->count() > 0)
                                            <div class="row form-group">
                                                <div id="descricaoPromo" class="col-sm-12">
                                                    @if (old('descricaoPromo') != null)
                                                        <textarea id='descricaoPromo' class='form-control' name='descricaoPromo' readonly>{{old('descricaoPromo')}}</textarea>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col-sm-12">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <h4>Atividades incluidas no pacote</h4>
                                                        </div>
                                                    </div>
                                                    <div id="atividadesPromocionais" class="row">
                                                        <span id="padraoPromocional" style="margin: 20px; @if(old('atividadesPromo') != null)display: none; @endif">Nenhuma atividade adicionada para essa promoção.</span>
                                                        @if (old('atividadesPromo') != null)
                                                            @foreach (old('atividadesPromo') as $key => $idAtv)
                                                                <div id="atvPromocao{{$idAtv}}" class="col-sm-3 atvAdicionais">
                                                                    <input type="hidden" id="atvPromocaoInput{{old('atividadesPromo.'.$key)}}" name="atividadesPromo[]" value="{{old('atividadesPromo.'.$key)}}">
                                                                    <div class="card" style="width: 16rem;">
                                                                        <div class="card-body">
                                                                            <h4 class="card-title">{{App\Models\Submissao\Atividade::find(old('atividadesPromo.'.$key))->titulo}}</h4>
                                                                            <h5 class="card-subtitle mb-2 text-muted">{{App\Models\Submissao\Atividade::find(old('atividadesPromo.'.$key))->tipoAtividade->descricao}}</h5>
                                                                            <h6 class="card-subtitle mb-2 text-muted">{{App\Models\Submissao\Atividade::find(old('atividadesPromo.'.$key))->local}}</h6>
                                                                            <p class="card-text">{{App\Models\Submissao\Atividade::find(old('atividadesPromo.'.$key))->descricao}}</p>
                                                                            <a href='#' class='card-link' data-bs-toggle='modal' data-bs-target='#modalDetalheAtividade{{old('atividadesPromo.'.$key)}}'>Detalhes</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <hr>
                                        </div>
                                    </div>
                                    <div class="row form-group" style="position: relative; top: 25px;">
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <h4>Atividades adicionais</h4>
                                                </div>
                                                <div class="col-sm-6" style="text-align: right;">
                                                    <a href="#" class="btn btn-secondary" style="font-size: 14px; text-align: right;" data-bs-toggle="modal" data-bs-target="#modalAdicionarAtividade">Adicionar atividade</a>
                                                </div>
                                            </div>
                                            <div id="atividadesAdicionadas" class="row">
                                                @if ($atividadesExtras != null)
                                                    @foreach ($atividadesExtras as $atv)
                                                        <div id="atv{{$atv->id}}" class="col-sm-3 atvAdicionais">
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
                                                                        <a href='#' class='card-link' data-bs-toggle='modal' data-bs-target='#modalDetalheAtividade{{$atv->id}}'>Detalhes</a>
                                                                        <a href='#' class='card-link' onclick='removerAtividade({{$atv->id}})'>Remover</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <span id="padrao" style="margin: 20px;">Nenhuma atividade adicionada.</span>
                                                @endif

                                            </div>
                                        </div>
                                    </div>


                                </div>
                            @endforeach
                            <div class="row form-group" style="position: relative; top: 50px;">
                                <div class="col-sm-6">

                                </div>
                                <div class="col-sm-6" style="text-align: right; position: relative; right: 50px;">
                                    <h5>Total</h5>
                                    @if (old('valorTotal') != null)
                                        @if (old('valorTotal') == 0)
                                            <p>
                                                <span id="spanValorTotal">Gratuita</span>
                                                <input type="hidden" name="valorTotal" id="valorTotal" value="0">
                                            </p>
                                        @else
                                            <p>
                                                <span id="spanValorTotal">R$ {{number_format(old('valorTotal'), 2,',','.')}}</span>
                                                <input type="hidden" name="valorTotal" id="valorTotal" value="{{old('valorTotal')}}">
                                            </p>
                                        @endif
                                    @else
                                        @if ($valorTotal <= 0)
                                            <p>
                                                <span id="spanValorTotal">Gratuita</span>
                                                <input type="hidden" name="valorTotal" id="valorTotal" value="0">
                                            </p>
                                        @else
                                            <p>
                                                <span id="spanValorTotal">R$ {{number_format($valorTotal, 2,',','.')}}</span>
                                                <input type="hidden" name="valorTotal" id="valorTotal" value="{{$valorTotal}}">
                                            </p>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="row" style="position: relative; margin-top:70px; margin-bottom:50px;">
                                <div class="col-sm-6" style="top:5px;">
                                <a href="{{route('evento.visualizar', ['id' => $evento->id])}}" class="btn btn-secondary" style="width: 100%; padding: 25px;">Voltar</a>
                                </div>
                                <div class="col-sm-6">

                                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 30px;" form="form-inscricao">Avançar</button>
                                </div>
                            </div>
                        </div>
                    @else
                        <div id="formulario">
                            <div class="row form-group">
                                <div class="col-sm-12">
                                    <label for="categoria">Escolha sua categoria como participante</label>
                                    <select name="categoria" id="categoria" class="form-control" onchange="carregarInfoExtra(this)">
                                        <option value="" disabled selected>-- Escolha sua categoria --</option>
                                        @foreach ($evento->categoriasParticipantes as $categoria)
                                            <option value="{{$categoria->id}}" @if(old('categoria') == $categoria->id) selected @endif>{{$categoria->nome}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @foreach($evento->categoriasParticipantes as $categoria)
                                <div class="campos-extras" id="campos-extras-{{$categoria->id}}"  @if(old('categoria') == $categoria->id)style="display: block;"@else style="display: none;" @endif>
                                    <div class="row form-group">
                                        @foreach ($categoria->camposNecessarios()->orderBy('tipo')->get() as $campo)
                                            @if($campo->tipo == "endereco")
                                                <div class="col-sm-12" style="margin-top: 10px; margin-bottom: 10px;">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label for="endereco-cep-{{$campo->id}}">CEP</label>
                                                            <input id="endereco-cep-{{$campo->id}}" name="endereco-cep-{{$campo->id}}" onblur="pesquisacep(this.value, '{{$campo->id}}');" type="text" class="form-control cep @error('endereco-cep-'.$campo->id) is-invalid @enderror" placeholder="00000-000" @if($campo->obrigatorio) required @endif value="@if(old('endereco-cep-'.$campo->id) != null){{old('endereco-cep-'.$campo->id)}}@endif">

                                                            @error('endereco-cep-'.$campo->id)
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <label for="endereco-bairro-{{$campo->id}}">Bairro</label>
                                                            <input type="text" class="form-control @error('endereco-bairro-'.$campo->id) is-invalid @enderror" id="endereco-bairro-{{$campo->id}}" name="endereco-bairro-{{$campo->id}}" placeholder="" @if($campo->obrigatorio) required @endif value="@if(old('endereco-bairro-'.$campo->id) != null){{old('endereco-bairro-'.$campo->id)}}@endif">

                                                            @error('endereco-bairro-'.$campo->id)
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="row" style="margin-top: 10px;">
                                                        <div class="col-sm-9">
                                                            <label for="endereco-rua-{{$campo->id}}">Rua</label>
                                                            <input type="text" class="form-control @error('endereco-rua-'.$campo->id) is-invalid @enderror" id="endereco-rua-{{$campo->id}}" name="endereco-rua-{{$campo->id}}" placeholder="" @if($campo->obrigatorio) required @endif value="@if(old('endereco-rua-'.$campo->id) != null){{old('endereco-rua-'.$campo->id)}}@endif">

                                                            @error('endereco-rua-'.$campo->id)
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label for="endereco-complemento-{{$campo->id}}">Complemento</label>
                                                            <input type="text" class="form-control @error('endereco-complemento-'.$campo->id) is-invalid @enderror" id="endereco-complemento-{{$campo->id}}" name="endereco-complemento-{{$campo->id}}" placeholder="" @if($campo->obrigatorio) required @endif value="@if(old('endereco-complemento-'.$campo->id) != null){{old('endereco-complemento-'.$campo->id)}}@endif">

                                                            @error('endereco-complemento-'.$campo->id)
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="row" style="margin-top: 10px;">
                                                        <div class="col-sm-6">
                                                            <label for="endereco-cidade-{{$campo->id}}">Cidade</label>
                                                            <input type="text" class="form-control @error('endereco-cidade-'.$campo->id) is-invalid @enderror" id="endereco-cidade-{{$campo->id}}" name="endereco-cidade-{{$campo->id}}" placeholder="" @if($campo->obrigatorio) required @endif value="@if(old('endereco-cidade-'.$campo->id) != null){{old('endereco-cidade-'.$campo->id)}}@endif">

                                                            @error('endereco-cidade-'.$campo->id)
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label for="endereco-uf-{{$campo->id}}">UF</label>
                                                            <select class="form-control @error('endereco-uf-'.$campo->id) is-invalid @enderror" id="endereco-uf-{{$campo->id}}" name="endereco-uf-{{$campo->id}}" @if($campo->obrigatorio) required @endif>
                                                                <option value="" disabled selected hidden>-- UF --</option>
                                                                <option @if(old('endereco-uf-'.$campo->id) == "AC") selected @endif value="AC">AC</option>
                                                                <option @if(old('endereco-uf-'.$campo->id) == "AL") selected @endif value="AL">AL</option>
                                                                <option @if(old('endereco-uf-'.$campo->id) == "AP") selected @endif value="AP">AP</option>
                                                                <option @if(old('endereco-uf-'.$campo->id) == "AM") selected @endif value="AM">AM</option>
                                                                <option @if(old('endereco-uf-'.$campo->id) == "BA") selected @endif value="BA">BA</option>
                                                                <option @if(old('endereco-uf-'.$campo->id) == "CE") selected @endif value="CE">CE</option>
                                                                <option @if(old('endereco-uf-'.$campo->id) == "DF") selected @endif value="DF">DF</option>
                                                                <option @if(old('endereco-uf-'.$campo->id) == "ES") selected @endif value="ES">ES</option>
                                                                <option @if(old('endereco-uf-'.$campo->id) == "GO") selected @endif value="GO">GO</option>
                                                                <option @if(old('endereco-uf-'.$campo->id) == "MA") selected @endif value="MA">MA</option>
                                                                <option @if(old('endereco-uf-'.$campo->id) == "MT") selected @endif value="MT">MT</option>
                                                                <option @if(old('endereco-uf-'.$campo->id) == "MS") selected @endif value="MS">MS</option>
                                                                <option @if(old('endereco-uf-'.$campo->id) == "MG") selected @endif value="MG">MG</option>
                                                                <option @if(old('endereco-uf-'.$campo->id) == "PA") selected @endif value="PA">PA</option>
                                                                <option @if(old('endereco-uf-'.$campo->id) == "PB") selected @endif value="PB">PB</option>
                                                                <option @if(old('endereco-uf-'.$campo->id) == "PR") selected @endif value="PR">PR</option>
                                                                <option @if(old('endereco-uf-'.$campo->id) == "PE") selected @endif value="PE">PE</option>
                                                                <option @if(old('endereco-uf-'.$campo->id) == "PI") selected @endif value="PI">PI</option>
                                                                <option @if(old('endereco-uf-'.$campo->id) == "RJ") selected @endif value="RJ">RJ</option>
                                                                <option @if(old('endereco-uf-'.$campo->id) == "RN") selected @endif value="RN">RN</option>
                                                                <option @if(old('endereco-uf-'.$campo->id) == "RS") selected @endif value="RS">RS</option>
                                                                <option @if(old('endereco-uf-'.$campo->id) == "RO") selected @endif value="RO">RO</option>
                                                                <option @if(old('endereco-uf-'.$campo->id) == "RR") selected @endif value="RR">RR</option>
                                                                <option @if(old('endereco-uf-'.$campo->id) == "SC") selected @endif value="SC">SC</option>
                                                                <option @if(old('endereco-uf-'.$campo->id) == "SP") selected @endif value="SP">SP</option>
                                                                <option @if(old('endereco-uf-'.$campo->id) == "SE") selected @endif value="SE">SE</option>
                                                                <option @if(old('endereco-uf-'.$campo->id) == "TO") selected @endif value="TO">TO</option>
                                                            </select>

                                                            @error('endereco-uf-'.$campo->id)
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label for="endereco-numero-{{$campo->id}}">Número</label>
                                                            <input type="number" class="form-control numero @error('endereco-numero-'.$campo->id) is-invalid @enderror" id="endereco-numero-{{$campo->id}}" name="endereco-numero-{{$campo->id}}" placeholder="10" @if($campo->obrigatorio) required @endif value="@if(old('endereco-numero-'.$campo->id) != null){{old('endereco-numero-'.$campo->id)}}@endif" maxlength="10">

                                                            @error('endereco-numero-'.$campo->id)
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif($campo->tipo == "date")
                                                <div class="col-sm-4" style="margin-top:10px;">
                                                    <label for="date-{{$campo->id}}">{{$campo->titulo}}</label>
                                                    <input class="form-control @error('date-'.$campo->id) is-invalid @enderror" type="date" name="date-{{$campo->id}}" id="date-{{$campo->id}}" @if($campo->obrigatorio) required @endif value="@if(old('date-'.$campo->id) != null){{old('date-'.$campo->id)}}@endif">

                                                    @error('date-'.$campo->id)
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            @elseif($campo->tipo == "email")
                                                <div class="col-sm-4" style="margin-top:10px;">
                                                    <label for="email-{{$campo->id}}">{{$campo->titulo}}</label>
                                                    <input class="form-control @error('email-'.$campo->id) is-invalid @enderror" type="email" name="email-{{$campo->id}}" id="email-{{$campo->id}}" @if($campo->obrigatorio) required @endif value="@if(old('email-'.$campo->id) != null){{old('email-'.$campo->id)}}@endif">

                                                    @error('email-'.$campo->id)
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            @elseif($campo->tipo == "text")
                                                <div class="col-sm-4" style="margin-top:10px;">
                                                    <label for="text-{{$campo->id}}">{{$campo->titulo}}</label>
                                                    <input class="form-control @error('text-'.$campo->id) is-invalid @enderror" type="text" name="text-{{$campo->id}}" id="text-{{$campo->id}}" @if($campo->obrigatorio) required @endif value="@if(old('text-'.$campo->id) != null){{old('text-'.$campo->id)}}@endif">

                                                    @error('text-'.$campo->id)
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            @elseif($campo->tipo == "cpf")
                                                <div class="col-sm-4"  style="margin-top:10px;">
                                                    <label for="cpf-{{$campo->id}}">{{$campo->titulo}}</label>
                                                    <input id="cpf-{{$campo->id}}" type="text" class="form-control cpf @error('cpf-'.$campo->id) is-invalid @enderror" name="cpf-{{$campo->id}}" autocomplete="cpf" autofocus  @if($campo->obrigatorio) required @endif value="@if(old('cpf-'.$campo->id) != null){{old('cpf-'.$campo->id)}}@endif">

                                                    @error('cpf-'.$campo->id)
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            @elseif($campo->tipo == "contato")
                                                <div class="col-sm-4" style="margin-top:10px;">
                                                    <label for="contato-{{$campo->id}}">{{$campo->titulo}}</label>
                                                    <input id="contato-{{$campo->id}}" type="text" class="form-control celular @error('contato-'.$campo->id) is-invalid @enderror" name="contato-{{$campo->id}}" autocomplete="contato" autofocus  @if($campo->obrigatorio) required @endif value="@if(old('contato-'.$campo->id) != null){{old('contato-'.$campo->id)}}@endif">

                                                    @error('contato-'.$campo->id)
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            @elseif($campo->tipo == "file")
                                                <div class="col-sm-12"  style="margin-top:10px;">
                                                    <label for="file-{{$campo->id}}" class="">{{$campo->titulo}}</label><br>
                                                    <input type="file" id="file-{{$campo->id}}" class="form-control-file  @error('file-'.$campo->id) is-invalid @enderror" name="file-{{$campo->id}}" @if($campo->obrigatorio) required @endif>
                                                    <br>
                                                    @error('file-'.$campo->id)
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach

                            @foreach($evento->categoriasParticipantes as $categoria)
                                <div id="extra-form-pkt-atv-{{$categoria->id}}" class="extra-form-pkt-atv" @if(old('categoria') == $categoria->id)style="display: block;"@else style="display: none;" @endif>
                                    <div class="row form-group">
                                        <div class="col-sm-9">
                                            @if ($categoria->promocoes()->count() > 0)
                                                <label for="promocao">Pacote</label>
                                                <select name="promocao" id="promocao" class="form-control" onchange="carregarAtividadesDoPacote(this)">
                                                    <option value="" disabled selected>-- Escolha um pacote --</option>
                                                    @foreach ($categoria->promocoes as $pacote)
                                                        <option value="{{$pacote->id}}" @if(old('promocao') == $pacote->id) selected @endif>{{$pacote->identificador}}</option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <label for="promocao">Pacote</label>
                                                <input type="text" disabled class="form-control" value="Não existe pacotes para essa categoria">
                                            @endif
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="cupomDesconto">Cupom de desconto</label>
                                            <input oninput="deixarMaiusculo(event)" id="cupomDesconto-{{$categoria->id}}" type="text" class="form-control" placeholder="" onchange="checarCupom(this)" value="{{old('cupom')}}">

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
                                    @if ($categoria->promocoes()->count() > 0)
                                        <div class="row form-group">
                                            <div id="descricaoPromo" class="col-sm-12">
                                                @if (old('descricaoPromo') != null)
                                                    <textarea id='descricaoPromo' class='form-control' name='descricaoPromo' readonly>{{old('descricaoPromo')}}</textarea>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <h4>Atividades incluidas no pacote</h4>
                                                    </div>
                                                </div>
                                                <div id="atividadesPromocionais" class="row">
                                                    <span id="padraoPromocional" style="margin: 20px; @if(old('atividadesPromo') != null)display: none; @endif">Nenhuma atividade adicionada para essa promoção.</span>
                                                    @if (old('atividadesPromo') != null)
                                                        @foreach (old('atividadesPromo') as $key => $idAtv)
                                                            <div id="atvPromocao{{$idAtv}}" class="col-sm-3 atvAdicionais">
                                                                <input type="hidden" id="atvPromocaoInput{{old('atividadesPromo.'.$key)}}" name="atividadesPromo[]" value="{{old('atividadesPromo.'.$key)}}">
                                                                <div class="card" style="width: 16rem;">
                                                                    <div class="card-body">
                                                                        <h4 class="card-title">{{App\Models\Submissao\Atividade::find(old('atividadesPromo.'.$key))->titulo}}</h4>
                                                                        <h5 class="card-subtitle mb-2 text-muted">{{App\Models\Submissao\Atividade::find(old('atividadesPromo.'.$key))->tipoAtividade->descricao}}</h5>
                                                                        <h6 class="card-subtitle mb-2 text-muted">{{App\Models\Submissao\Atividade::find(old('atividadesPromo.'.$key))->local}}</h6>
                                                                        <p class="card-text">{{App\Models\Submissao\Atividade::find(old('atividadesPromo.'.$key))->descricao}}</p>
                                                                        <a href='#' class='card-link' data-bs-toggle='modal' data-bs-target='#modalDetalheAtividade{{old('atividadesPromo.'.$key)}}'>Detalhes</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <hr>
                                        </div>
                                    </div>
                                    <div class="row form-group" style="position: relative; top: 25px;">
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <h4>Atividades adicionais</h4>
                                                </div>
                                                <div class="col-sm-6" style="text-align: right;">
                                                    <a href="#" class="btn btn-secondary" style="font-size: 14px; text-align: right;" data-bs-toggle="modal" data-bs-target="#modalAdicionarAtividade">Adicionar atividade</a>
                                                </div>
                                            </div>
                                            <div id="atividadesAdicionadas" class="row">
                                                <span id="padrao" style="margin: 20px;">Nenhuma atividade adicionada.</span>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            @endforeach
                            <div class="row form-group" style="position: relative; top: 50px;">
                                <div class="col-sm-6">

                                </div>
                                <div class="col-sm-6" style="text-align: right; position: relative; right: 50px;">
                                    <h5>Total</h5>
                                    @if (old('valorTotal') != null)
                                        @if (old('valorTotal') == 0)
                                            <p>
                                                <span id="spanValorTotal">Gratuita</span>
                                                <input type="hidden" name="valorTotal" id="valorTotal" value="0">
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
                                                <span id="spanValorTotal">Gratuita</span>
                                                <input type="hidden" name="valorTotal" id="valorTotal" value="0">
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
                            <div class="row" style="position: relative; margin-top:70px; margin-bottom:50px;">
                                <div class="col-sm-6" >
                                <a href="{{route('evento.visualizar', ['id' => $evento->id])}}" class="btn btn-secondary" style="width: 100%">Voltar</a>
                                </div>
                                <div class="col-sm-6">


                                    <button id="confirmar-inscricao" onclick="submitOnClick()"  type="submit" class="btn btn-primary" style="width: 100%" form="form-inscricao">Avançar</button>
                                </div>
                            </div>
                        </div>
                    @endif

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
                                        <div id="atividadesModalAdicionarAtividades" class="row" style="position: relative;">
                                            @if (count($evento->atividade) > 0)
                                                <div class="col-sm-12">
                                                    <span id="padraoModal" style="display: none;">Este evento não possue atividades.</span>
                                                </div>
                                                @foreach ($evento->atividade as $atv)
                                                    @if ($inscricao != null && $atividadesExtras != null)
                                                        @if ($atv->visibilidade_participante && !($atividadesExtras->contains($atv)))
                                                            <div id="atv{{$atv->id}}" class="col-sm-3 atvAdicionais">
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
                                                    @else
                                                        @if ($atv->visibilidade_participante)
                                                            <div id="atv{{$atv->id}}" class="col-sm-3 atvAdicionais">
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
                                                <label for="tipo-{{$atv->id}}">Tipo</label>
                                                <input id="tipo-{{$atv->id}}" type="text" class="form-control" disabled value="{{$atv->tipoAtividade->descricao}}">
                                            </div>
                                            <div class="col-sm-6">
                                                @if ($atv->valor <= 0)
                                                    <label for="text-{{$atv->id}}"></label>
                                                    <input type="text-{{$atv->id}}" disabled class="form-control" value="Gratuita">
                                                @else
                                                    <label for="valor">Valor</label>
                                                    <input id="valor-{{$atv->id}}" type="text" disabled class="form-control" value="R$ {{number_format($atv->valor, 2,',','.')}}">
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <label for="descricao-{{$atv->id}}">Descrição</label>
                                                <textarea id="descricao-{{$atv->id}}" disabled class="form-control">{{$atv->descricao}}</textarea>
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
                                                            <label for="vagas-{{$atv->id}}">Vagas</label>
                                                            <input id="vagas-{{$atv->id}}" type="text" disabled class="form-control" value="{{$atv->vagas}}">
                                                        </div>
                                                    @endif
                                                    @if ($atv->carga_horaria != null && $atv->carga_horaria > 0)
                                                        <div class="col-sm-6">
                                                            <label for="carga_horaria-{{$atv->id}}">Carga horária</label>
                                                            <input id="carga_horaria-{{$atv->id}}" type="text" disabled class="form-control" value="{{$atv->carga_horaria}}">
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
        </div>
    </div>
</div>


@endsection
@section('javascript')submit
<script type="text/javascript">
    @if (old('valorTotal') != null)
        $(document).ready(function() {
            var select = document.getElementById('promocao');
            carregarAtividadesDoPacote(select);
        });
    @endif

    $(document).ready(function($) {
        $('.cep').mask('00000-000');
        $('.cpf').mask('000.000.000-00');

        //$('.numero').mask('0000000000000');
        var SPMaskBehavior = function (val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        },
        spOptions = {
            onKeyPress: function(val, e, field, options) {
            field.mask(SPMaskBehavior.apply({}, arguments), options);
            }
        };
      $('.celular').mask(SPMaskBehavior, spOptions);
    })

    //Funcao para deixar o botao da submissao onclick
    function submitOnClick() {
        document.getElementById("confirmar-inscricao").disabled = true;
        //enviar form
        document.getElementById("form-inscricao").submit();
        // adiciona um manipulador de eventos para o evento 'onsubmit'
        document.getElementById("form-inscricao").addEventListener("submit", function(event) {
        // ativa o botão de envio do formulário novamente após a submissão
        document.getElementById("confirmar-inscricao").disabled = false;
        });
    }
    //caso eu queira colocar uma funcao anonima que ativará o botao dps de um certo tempo:
    /**
     * function submitOnClick() {
        document.getElementById("confirmar-inscricao").disabled = true;
        setTimeout(function() {
            document.getElementById("confirmar-inscricao").disabled = false;
        }, 1000)
    }
     *
     */
    //outra forma que pode funcionar seria:
    /**
     * aqui o form
     * <form id="meu-form" action="/submit-form" method="post">
        <!-- campos do formulário aqui -->
        <button type="submit" id="confirmar-inscricao">Confirmar inscrição</button>
        </form>

     * aqui a função:
    const form = document.getElementById('meu-form');
    const botao = document.getElementById('confirmar-inscricao');

    form.addEventListener('submit', function(event) {
    botao.disabled = true;
    });
    estamos adicionando um listener de event ao form que escuta o evento submit.
    QUando ele for acionado, a funcao anonima passada como argumento eh executada.
    Isso significa que quando o user apertar o botao para confirmar inscricao, ele desabitará o botao
    e fcara assim até que o form seja enviado e a página recarregada ou redirecionada
     */

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
        var links = "<a href='#' class='card-link' data-bs-toggle='modal' data-bs-target='#modalDetalheAtividade"+id+"'>Detalhes</a>" +
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

    function carregarAtividadesDoPacote(select) {
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
                                        "<div class='card' style='width: 12rem;'>" +
                                            "<div class='card-body'>" +
                                                "<h4 class='card-title'>"+obj.titulo+"</h4>" +
                                                "<h5 class='card-subtitle mb-2 text-muted'>"+obj.tipo+"</h5>" +
                                                "<h6 class='card-subtitle mb-2 text-muted'>"+obj.local+"</h6>" +
                                                "<p class='card-text'>"+obj.descricao+"</p>" +
                                                "<a href='#' class='card-link' data-bs-toggle='modal' data-bs-target='#modalDetalheAtividade"+obj.id+"'>Detalhes</a>" +
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
                        html += "<span id='padraoPromocional' style='position: relative; margin: 57px;'>Nenhuma atividade adicionada para essa promoção.</span>";
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
            // alert(valorTotal);
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

    function carregarInfoExtra(select) {
        if (select.value != "") {
            var divs = document.getElementsByClassName("campos-extras");
            var divs_pacotes = document.getElementsByClassName("extra-form-pkt-atv");
            for (var i = 0; i < divs.length; i++) {
                divs[i].style.display = "none";
            }
            for (var i = 0; i < divs_pacotes.length; i++) {
                divs_pacotes[i].style.display = "none";
            }

            document.getElementById("campos-extras-"+select.value).style.display = "block";
            document.getElementById("extra-form-pkt-atv-"+select.value).style.display = "block";

            $.ajax({
                url: "{{route('ajax.valor.categoria')}}",
                method: 'get',
                type: 'get',
                data: {
                    _token: '{{csrf_token()}}',
                    categoria_id: select.value,
                },
                success: function(valorCategoria){
                    var valor = valorCategoria.valor;
                    if (valor > 0) {
                        $('#spanValorTotal').html("");
                        $('#spanValorTotal').append("R$ " + Number(valor).toFixed(2));
                        document.getElementById('valorTotal').value = valor;
                    } else {
                        $('#spanValorTotal').html("");
                        $('#spanValorTotal').append("Gratuita");
                        document.getElementById('valorTotal').value = 0;
                    }
                }
            });
        }
    }

    var id_campo_cep = 0;
    function limpa_formulário_cep(id) {
            //Limpa valores do formulário de cep.
            document.getElementById('endereco-rua-'+id).value=("");
            document.getElementById('endereco-bairro-'+id).value=("");
            document.getElementById('endereco-cidade-'+id).value=("");
            document.getElementById('endereco-uf-'+id).value=("");
    }

    function meu_callback(conteudo) {
        if (!("erro" in conteudo)) {
            //Atualiza os campos com os valores.
            document.getElementById('endereco-rua-'+id_campo_cep).value=(conteudo.logradouro);
            document.getElementById('endereco-bairro-'+id_campo_cep).value=(conteudo.bairro);
            document.getElementById('endereco-cidade-'+id_campo_cep).value=(conteudo.localidade);
            document.getElementById('endereco-uf-'+id_campo_cep).value=(conteudo.uf);
        } //end if.
        else {
            //CEP não Encontrado.
            limpa_formulário_cep();
            alert("CEP não encontrado.");
        }
    }

    function pesquisacep(valor, id) {
        id_campo_cep = id;
        //Nova variável "cep" somente com dígitos.
        var cep = valor.replace(/\D/g, '');
        //Verifica se campo cep possui valor informado.
        if (cep != "") {
            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;
            //Valida o formato do CEP.
            if(validacep.test(cep)) {
                //Preenche os campos com "..." enquanto consulta webservice.
                document.getElementById('endereco-rua-'+id).value="...";
                document.getElementById('endereco-bairro-'+id).value="...";
                document.getElementById('endereco-cidade-'+id).value="...";
                document.getElementById('endereco-uf-'+id).value="...";
                //Cria um elemento javascript.
                var script = document.createElement('script');
                //Sincroniza com o callback.
                script.src = 'https://viacep.com.br/ws/'+ cep + '/json/?callback=meu_callback';
                //Insere script no documento e carrega o conteúdo.
                document.body.appendChild(script);
            } //end if.
            else {
                //cep é inválido.
                limpa_formulário_cep(id);
                alert("Formato de CEP inválido.");
            }
        } //end if.
        else {
            //cep sem valor, limpa formulário.
            limpa_formulário_cep(id);
        }
    }
</script>
@endsection
