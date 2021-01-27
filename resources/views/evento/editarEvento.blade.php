@extends('layouts.app')

@section('content')
<div class="banner-perfil"  style="position: relative; top: 65px;">
    <div class="row justify-content-center curved" style="margin-bottom:-5px">
    
    </div>
    
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#114048ff"
            fill-opacity="1" d="M0,288L80,261.3C160,235,320,181,480,176C640,171,800,213,960,
            218.7C1120,224,1280,192,1360,176L1440,160L1440,0L1360,0C1280,0,1120,0,960,0C800,
            0,640,0,480,0C320,0,160,0,80,0L0,0Z"></path>
            </svg>
        </div>
    </div>
</div>
<div class="card-perfil justify-content-center">
    <div class="card" style="width: 80%;">
        <div class="card-body">
            <div class="container">
                <div class="row justify-content-center titulo">
                    <h1>Editar {{$evento->nome}}</h1>
                </div>

                <form action="{{route('evento.update',['id' => $evento->id])}}" method="POST" enctype="multipart/form-data">
                @csrf
                    <div class="row subtitulo">
                        <div class="col-sm-12">
                            <p>Informações Gerais</p>
                        </div>
                    </div>
                    {{-- nome | Tipo--}}
                    <div class="row justify-content-center">
                        <div class="col-sm-6">{{--Nome do evento--}}
                            <label for="nome" class="col-form-label">{{ __('Nome*') }}</label>
                            <input id="nome" type="text" class="form-control @error('nome') is-invalid @enderror" name="nome" @if(old('nome') != null) value="{{ old('nome') }}" @else value="{{$evento->nome}}" @endif required autocomplete="nome" autofocus>

                            @error('nome')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>{{--End Nome do evento--}}
                        {{--Número de Participantes--}}
                        {{-- <div class="col-sm-3">
                            <label for="numeroParticipantes" class="col-form-label">{{ __('N° de Participantes') }}</label>
                            <input value="{{$evento->numeroParticipantes}}" id="numeroParticipantes" type="number" class="form-control @error('numeroParticipantes') is-invalid @enderror" name="numeroParticipantes" value="{{ old('numeroParticipantes') }}" required autocomplete="numeroParticipantes" autofocus>

                            @error('numeroParticipantes')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div> --}}
                        {{-- Tipo do evento --}}
                        <div class="col-sm-3">
                            <label for="tipo" class="col-form-label">{{ __('Tipo*') }}</label>
                            <!-- <input value="{{$evento->tipo}}" id="tipo" type="text" class="form-control @error('tipo') is-invalid @enderror" name="tipo" value="{{ old('tipo') }}" required autocomplete="tipo" autofocus> -->
                            <select id="tipo" type="text" class="form-control @error('tipo') is-invalid @enderror" name="tipo" required>
                            @if (old('tipo') != null)
                                <option @if(old('tipo') == "Congresso") selected @endif value="Congresso">Congresso</option>
                                <option @if(old('tipo') == "Encontro") selected @endif value="Encontro">Encontro</option>
                                <option @if(old('tipo') == "Seminário") selected @endif value="Seminário">Seminário</option>
                                <option @if(old('tipo') == "Mesa redonda") selected @endif value="Mesa redonda">Mesa redonda</option>
                                <option @if(old('tipo') == "Simpósio") selected @endif value="Simpósio">Simpósio</option>
                                <option @if(old('tipo') == "Painel") selected @endif value="Painel">Painel</option>
                                <option @if(old('tipo') == "Fórum") selected @endif value="Fórum">Fórum</option>
                                <option @if(old('tipo') == "Conferência") selected @endif value="Conferência">Conferência</option>
                                <option @if(old('tipo') == "Jornada") selected @endif value="Jornada">Jornada</option>
                                <option @if(old('tipo') == "Cursos") selected @endif value="Cursos">Cursos</option>
                                <option @if(old('tipo') == "Colóquio") selected @endif value="Colóquio">Colóquio</option>
                                <option @if(old('tipo') == "Semana") selected @endif value="Semana">Semana</option>
                                <option @if(old('tipo') == "Workshop") selected @endif value="Workshop">Workshop</option>
                                <option @if(old('tipo') == "outro") selected @endif value="outro">Outro</option>
                            @else
                                <option @if($evento->tipo == "Congresso") selected @endif value="Congresso">Congresso</option>
                                <option @if($evento->tipo == "Encontro") selected @endif value="Encontro">Encontro</option>
                                <option @if($evento->tipo == "Seminário") selected @endif value="Seminário">Seminário</option>
                                <option @if($evento->tipo == "Mesa redonda") selected @endif value="Mesa redonda">Mesa redonda</option>
                                <option @if($evento->tipo == "Simpósio") selected @endif value="Simpósio">Simpósio</option>
                                <option @if($evento->tipo == "Painel") selected @endif value="Painel">Painel</option>
                                <option @if($evento->tipo == "Fórum") selected @endif value="Fórum">Fórum</option>
                                <option @if($evento->tipo == "Conferência") selected @endif value="Conferência">Conferência</option>
                                <option @if($evento->tipo == "Jornada") selected @endif value="Jornada">Jornada</option>
                                <option @if($evento->tipo == "Cursos") selected @endif value="Cursos">Cursos</option>
                                <option @if($evento->tipo == "Colóquio") selected @endif value="Colóquio">Colóquio</option>
                                <option @if($evento->tipo == "Semana") selected @endif value="Semana">Semana</option>
                                <option @if($evento->tipo == "Workshop") selected @endif value="Workshop">Workshop</option>
                                <option @if($evento->tipo == "outro") selected @endif value="outro">Outro</option>
                            @endif
                            </select>
                            @error('tipo')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>{{-- Tipo do evento --}}
                        <div class="col-sm-3">
                            <label for="recolhimento" class="col-form-label">{{ __('Recolhimento*') }}</label>
                            <select name="recolhimento" id="recolhimento" class="form-control @error('recolhimento') is-invalid @enderror">
                                @if (old('recolhimento') != null)
                                    <option @if(old('recolhimento') == "apoiado") selected @endif value="apoiado">Apoiado</option>
                                    <option @if(old('recolhimento') == "gratuito") selected @endif value="gratuito">Gratuito</option>
                                    <option @if(old('recolhimento') == "pago") selected @endif value="pago">Pago</option>
                                @else 
                                    <option @if($evento->recolhimento == "apoiado") selected @endif value="apoiado">Apoiado</option>
                                    <option @if($evento->recolhimento == "gratuito") selected @endif value="gratuito">Gratuito</option>
                                    <option @if($evento->recolhimento == "pago") selected @endif value="pago">Pago</option>
                                @endif
                            </select>

                            @error('recolhimento')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>{{-- end nome | Participantes | Tipo--}}

                    {{-- Descricao Evento --}}
                    <div class="row justify-content-center">
                        <div class="col-sm-7">
                            <div class="form-group">
                                <label for="exampleFormControlTextarea1">Descrição*</label>
                                <textarea class="form-control @error('descricao') is-invalid @enderror" id="descricao" name="descricao" rows="8">@if(old('descricao') != null) {{ old('descricao') }} @else {{$evento->descricao}} @endif</textarea>
                                @error('descricao')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="form-group">
                                <label for="fotoEvento">Logo</label>
                                <div id="imagem-loader" class="imagem-loader">
                                    @if ($evento->fotoEvento != null)
                                        <img id="logo-preview" src="{{asset('storage/eventos/'.$evento->id.'/logo.png')}}" alt="">
                                    @else
                                        <img id="logo-preview" src="{{asset('/img/nova_imagem.PNG')}}" alt="">
                                    @endif
                                </div>
                                <div style="display: none;">
                                    <input type="file" id="logo-input" class="form-control @error('fotoEvento') is-invalid @enderror" name="fotoEvento" value="{{ old('fotoEvento') }}" id="fotoEvento">
                                </div>
                                <small style="position: relative; top: 5px;">Tamanho minimo: 1024 x 425;<br>Formato: JPEG, JPG, PNG</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-7">

                        </div>
                        <div class="col-sm-5">
                            @error('fotoEvento')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{$message}}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                    {{-- Início do Evento --}}
                    <div class="col-sm-3">
                        <label for="dataInicio" class="col-form-label">{{ __('Início*') }}</label>
                        <input id="dataInicio" type="date" class="form-control @error('dataInicio') is-invalid @enderror" name="dataInicio" @if(old('dataInicio') != null) value="{{ old('dataInicio') }}" @else value="{{$evento->dataInicio}}" @endif required autocomplete="dataInicio" autofocus>

                        @error('dataInicio')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>{{--End Início do Evento --}}
                    {{-- Fim do Evento --}}
                    <div class="col-sm-3">
                        <label for="dataFim" class="col-form-label">{{ __('Fim*') }}</label>
                        <input id="dataFim" type="date" class="form-control @error('dataFim') is-invalid @enderror" name="dataFim" @if(old('dataFim') != null) value="{{ old('dataFim') }}" @else value="{{$evento->dataFim}}" @endif required autocomplete="dataFim" autofocus>

                        @error('dataFim')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>{{-- end Fim do Evento --}}
                    </div>

                    <div class="row subtitulo" style="margin-top:20px">
                        <div class="col-sm-12">
                            <p>Endereço</p>
                        </div>
                    </div>

                    {{-- Rua | Número | Bairro --}}
                    <div class="row justify-content-center">
                        <div class="col-sm-4">
                            <label for="cep" class="col-form-label">{{ __('CEP') }}</label>
                            <input onblur="pesquisacep(this.value);" id="cep" type="text" class="form-control @error('cep') is-invalid @enderror" name="cep" @if(old('cep') != null) value="{{ old('cep') }}" @else value="{{$endereco->cep}}" @endif required autocomplete="cep" autofocus>

                            @error('cep')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-sm-6">
                            <label for="rua" class="col-form-label">{{ __('Rua*') }}</label>
                            <input id="rua" type="text" class="form-control @error('rua') is-invalid @enderror" name="rua" @if(old('rua') != null) value="{{ old('rua') }}" @else value="{{$endereco->rua}}" @endif required autocomplete="rua" autofocus>

                            @error('rua')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-sm-2">
                            <label for="numero" class="col-form-label">{{ __('Número*') }}</label>
                            <input id="numero" type="text" class="form-control @error('numero') is-invalid @enderror" name="numero" @if(old('numero') != null) value="{{ old('numero') }}" @else value="{{$endereco->numero}}" @endif required autocomplete="numero" autofocus>

                            @error('numero')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>


                    </div>{{--end Rua | Número | Bairro --}}

                    <div class="row justify-content-center">
                        <div class="col-sm-3">
                            <label for="bairro" class="col-form-label">{{ __('Bairro*') }}</label>
                            <input id="bairro" type="text" class="form-control @error('bairro') is-invalid @enderror" name="bairro" @if(old('bairro') != null) value="{{ old('bairro') }}" @else value="{{$endereco->bairro}}" @endif required autocomplete="bairro" autofocus>

                            @error('bairro')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-sm-3">
                            <label for="cidade" class="col-form-label">{{ __('Cidade*') }}</label>
                            <input id="cidade" type="text" class="form-control apenasLetras @error('cidade') is-invalid @enderror" name="cidade" @if(old('cidade') != null) value="{{ old('cidade') }}" @else value="{{$endereco->cidade}}" @endif required autocomplete="cidade" autofocus>

                            @error('cidade')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-sm-4">
                            <label for="complemento" class="col-form-label">{{ __('Complemento*') }}</label>
                            <input id="complemento" type="text" class="form-control apenasLetras @error('complemento') is-invalid @enderror" name="complemento" value="@if(old('complemento') != null){{old('complemento')}}@else{{$evento->endereco->complemento}}@endif" required autocomplete="complemento" autofocus>
            
                            @error('complemento')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-sm-2">
                            <label for="uf" class="col-form-label">{{ __('UF*') }}</label>
                            {{-- <input id="uf" type="text" class="form-control @error('uf') is-invalid @enderror" name="uf" value="{{ old('uf') }}" required autocomplete="uf" autofocus> --}}
                            <select value="{{$endereco->uf}}" class="form-control @error('uf') is-invalid @enderror" id="uf" name="uf">
                                @if(old('uf') != null)
                                    <option value="" disabled selected hidden>-- UF --</option>
                                    <option @if(old('uf') == 'AC') selected @endif value="AC">Acre</option>
                                    <option @if(old('uf') == 'AL') selected @endif value="AL">Alagoas</option>
                                    <option @if(old('uf') == 'AP') selected @endif value="AP">Amapá</option>
                                    <option @if(old('uf') == 'AM') selected @endif value="AM">Amazonas</option>
                                    <option @if(old('uf') == 'BA') selected @endif value="BA">Bahia</option>
                                    <option @if(old('uf') == 'CE') selected @endif value="CE">Ceará</option>
                                    <option @if(old('uf') == 'DF') selected @endif value="DF">Distrito Federal</option>
                                    <option @if(old('uf') == 'ES') selected @endif value="ES">Espírito Santo</option>
                                    <option @if(old('uf') == 'GO') selected @endif value="GO">Goiás</option>
                                    <option @if(old('uf') == 'MA') selected @endif value="MA">Maranhão</option>
                                    <option @if(old('uf') == 'MT') selected @endif value="MT">Mato Grosso</option>
                                    <option @if(old('uf') == 'MS') selected @endif value="MS">Mato Grosso do Sul</option>
                                    <option @if(old('uf') == 'MG') selected @endif value="MG">Minas Gerais</option>
                                    <option @if(old('uf') == 'PA') selected @endif value="PA">Pará</option>
                                    <option @if(old('uf') == 'PB') selected @endif value="PB">Paraíba</option>
                                    <option @if(old('uf') == 'PR') selected @endif value="PR">Paraná</option>
                                    <option @if(old('uf') == 'PE') selected @endif value="PE">Pernambuco</option>
                                    <option @if(old('uf') == 'PI') selected @endif value="PI">Piauí</option>
                                    <option @if(old('uf') == 'RJ') selected @endif value="RJ">Rio de Janeiro</option>
                                    <option @if(old('uf') == 'RN') selected @endif value="RN">Rio Grande do Norte</option>
                                    <option @if(old('uf') == 'RS') selected @endif value="RS">Rio Grande do Sul</option>
                                    <option @if(old('uf') == 'RO') selected @endif value="RO">Rondônia</option>
                                    <option @if(old('uf') == 'RR') selected @endif value="RR">Roraima</option>
                                    <option @if(old('uf') == 'SC') selected @endif value="SC">Santa Catarina</option>
                                    <option @if(old('uf') == 'SP') selected @endif value="SP">São Paulo</option>
                                    <option @if(old('uf') == 'SE') selected @endif value="SE">Sergipe</option>
                                    <option @if(old('uf') == 'TO') selected @endif value="TO">Tocantins</option>
                                @else 
                                    <option value="" disabled selected hidden>-- UF --</option>
                                    <option @if($endereco->uf == 'AC') selected @endif value="AC">Acre</option>
                                    <option @if($endereco->uf == 'AL') selected @endif value="AL">Alagoas</option>
                                    <option @if($endereco->uf == 'AP') selected @endif value="AP">Amapá</option>
                                    <option @if($endereco->uf == 'AM') selected @endif value="AM">Amazonas</option>
                                    <option @if($endereco->uf == 'BA') selected @endif value="BA">Bahia</option>
                                    <option @if($endereco->uf == 'CE') selected @endif value="CE">Ceará</option>
                                    <option @if($endereco->uf == 'DF') selected @endif value="DF">Distrito Federal</option>
                                    <option @if($endereco->uf == 'ES') selected @endif value="ES">Espírito Santo</option>
                                    <option @if($endereco->uf == 'GO') selected @endif value="GO">Goiás</option>
                                    <option @if($endereco->uf == 'MA') selected @endif value="MA">Maranhão</option>
                                    <option @if($endereco->uf == 'MT') selected @endif value="MT">Mato Grosso</option>
                                    <option @if($endereco->uf == 'MS') selected @endif value="MS">Mato Grosso do Sul</option>
                                    <option @if($endereco->uf == 'MG') selected @endif value="MG">Minas Gerais</option>
                                    <option @if($endereco->uf == 'PA') selected @endif value="PA">Pará</option>
                                    <option @if($endereco->uf == 'PB') selected @endif value="PB">Paraíba</option>
                                    <option @if($endereco->uf == 'PR') selected @endif value="PR">Paraná</option>
                                    <option @if($endereco->uf == 'PE') selected @endif value="PE">Pernambuco</option>
                                    <option @if($endereco->uf == 'PI') selected @endif value="PI">Piauí</option>
                                    <option @if($endereco->uf == 'RJ') selected @endif value="RJ">Rio de Janeiro</option>
                                    <option @if($endereco->uf == 'RN') selected @endif value="RN">Rio Grande do Norte</option>
                                    <option @if($endereco->uf == 'RS') selected @endif value="RS">Rio Grande do Sul</option>
                                    <option @if($endereco->uf == 'RO') selected @endif value="RO">Rondônia</option>
                                    <option @if($endereco->uf == 'RR') selected @endif value="RR">Roraima</option>
                                    <option @if($endereco->uf == 'SC') selected @endif value="SC">Santa Catarina</option>
                                    <option @if($endereco->uf == 'SP') selected @endif value="SP">São Paulo</option>
                                    <option @if($endereco->uf == 'SE') selected @endif value="SE">Sergipe</option>
                                    <option @if($endereco->uf == 'TO') selected @endif value="TO">Tocantins</option>
                                @endif
                            </select>

                            @error('uf')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                    </div>

                    <div class="row justify-content-center" style="margin: 20px 0 20px 0">
                        <div class="col-md-6" style="padding-left:0">
                            {{-- <a class="btn btn-secondary botao-form" href="{{route('coord.home')}}">Voltar</a> --}}
                        </div>
                        <div class="col-md-6" style="padding-ridht:0">
                            <button type="submit" class="btn btn-atualizar-perfil botao-form">
                                {{ __('Salvar') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@include('componentes.footer')
@endsection
@section('javascript')
  <script type="text/javascript" >
    $(document).ready(function($){
        $('#cep').mask('00000-000');
        $(".apenasLetras").mask("#", {
            maxlength: false,
            translation: {
                '#': {pattern: /[A-zÀ-ÿ ]/, recursive: true}
            }
        });
        $('#numero').mask('0000000000000');

        $('#imagem-loader').click(function() {
            $('#logo-input').click();
            $('#logo-input').change(function() {
                if (this.files && this.files[0]) {
                    var file = new FileReader();
                    file.onload = function(e) {
                        document.getElementById("logo-preview").src = e.target.result;
                    };       
                    file.readAsDataURL(this.files[0]);
                }
            })
        });
    });

    function limpa_formulário_cep() {
            //Limpa valores do formulário de cep.
            document.getElementById('rua').value=("");
            document.getElementById('bairro').value=("");
            document.getElementById('cidade').value=("");
            document.getElementById('uf').value=("");
    }
    function meu_callback(conteudo) {
        if (!("erro" in conteudo)) {
            //Atualiza os campos com os valores.
            document.getElementById('rua').value=(conteudo.logradouro);
            document.getElementById('bairro').value=(conteudo.bairro);
            document.getElementById('cidade').value=(conteudo.localidade);
            document.getElementById('uf').value=(conteudo.uf);
        } //end if.
        else {
            //CEP não Encontrado.
            limpa_formulário_cep();
            alert("CEP não encontrado.");
        }
    }
    function pesquisacep(valor) {
        //Nova variável "cep" somente com dígitos.
        var cep = valor.replace(/\D/g, '');
        //Verifica se campo cep possui valor informado.
        if (cep != "") {
            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;
            //Valida o formato do CEP.
            if(validacep.test(cep)) {
                //Preenche os campos com "..." enquanto consulta webservice.
                document.getElementById('rua').value="...";
                document.getElementById('bairro').value="...";
                document.getElementById('cidade').value="...";
                document.getElementById('uf').value="...";
                //Cria um elemento javascript.
                var script = document.createElement('script');
                //Sincroniza com o callback.
                script.src = 'https://viacep.com.br/ws/'+ cep + '/json/?callback=meu_callback';
                //Insere script no documento e carrega o conteúdo.
                document.body.appendChild(script);
            } //end if.
            else {
                //cep é inválido.
                limpa_formulário_cep();
                alert("Formato de CEP inválido.");
            }
        } //end if.
        else {
            //cep sem valor, limpa formulário.
            limpa_formulário_cep();
        }
    };
  </script>
@endsection