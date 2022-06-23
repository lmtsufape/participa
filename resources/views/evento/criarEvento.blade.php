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
    <div class="card card-change-mode" style="width: 80%;">
        <div class="card-body">
            <div class="container">


                <div class="row justify-content-center titulo">
                    @if ($eventoPai ?? '')
                        <h1>Novo Subevento</h1>
                    @else
                        <h1>Novo Evento</h1>
                    @endif

                </div>

                <form action="{{route('evento.criar')}}" method="POST" enctype="multipart/form-data">
                @csrf
                    @if ($eventoPai ?? '')
                        <input type="hidden" name="eventoPai" value="{{$eventoPai->id}}">
                    @endif
                    {{-- <input type="hidden" name="" value="">
                    <input type="hidden" name="" value="">
                    <input type="hidden" name="" value="">
                    <input type="hidden" name="" value="">
                    <input type="hidden" name="" value=""> --}}
                    <div class="row subtitulo">
                        <div class="col-sm-12">
                            <p>Informações Gerais</p>
                            @error('eventoPai')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    {{-- nome | Participantes | Tipo--}}
                    <div class="row justify-content-center">
                        <div class="col-sm-6">
                            <label for="nome" class="col-form-label">{{ __('Nome*') }}</label>
                            <input id="nome" type="text" class="form-control @error('nome') is-invalid @enderror" name="nome" value="{{ old('nome') }}" required autocomplete="nome" autofocus>

                            @error('nome')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        {{-- <div class="col-sm-3">
                            <label for="numeroParticipantes" class="col-form-label">{{ __('N° de Participantes') }}</label>
                            <input id="numeroParticipantes" type="number" class="form-control @error('numeroParticipantes') is-invalid @enderror" name="numeroParticipantes" value="{{ old('numeroParticipantes') }}" required autocomplete="numeroParticipantes" autofocus>

                            @error('numeroParticipantes')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div> --}}

                        <div class="col-sm-3">
                            <label for="tipo" class="col-form-label">{{ __('Tipo*') }}</label>
                            <select id="tipo" type="text" class="form-control @error('tipo') is-invalid @enderror" name="tipo" required>
                                <option disabled selected hidden value="">-- Tipo --</option>
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
                            </select>

                            @error('tipo')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="col-sm-3">
                            <label for="recolhimento" class="col-form-label">{{ __('Recolhimento') }}</label>
                            <select name="recolhimento" id="recolhimento" class="form-control @error('recolhimento') is-invalid @enderror">
                                @if (old('recolhimento') != null)
                                    <option @if(old('recolhimento') == "") selected @endif value="">-- Recolhimento --</option>
                                    <option @if(old('recolhimento') == "apoiado") selected @endif value="apoiado">Apoiado</option>
                                    <option @if(old('recolhimento') == "gratuito") selected @endif value="gratuito">Gratuito</option>
                                    <option @if(old('recolhimento') == "pago") selected @endif value="pago">Pago</option>
                                @else
                                    <option value="">-- Recolhimento --</option>
                                    <option value="apoiado">Apoiado</option>
                                    <option value="gratuito">Gratuito</option>
                                    <option value="pago">Pago</option>
                                @endif
                            </select>

                            @error('recolhimento')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>{{-- end nome | Participantes | Tipo--}}
                    <br>
                    {{-- Descricao Evento --}}
                    <div class="row justify-content-center">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="exampleFormControlTextarea1">Descrição*</label>
                                <textarea class="form-control @error('descricao') is-invalid @enderror" required autocomplete="descricao" autofocus id="descricao" name="descricao" rows="8">{{ old('descricao') }}</textarea>
                                @error('descricao')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-7">
                            <div class="form-group">
                                <label for="fotoEvento">Banner</label>
                                <div id="imagem-loader" class="imagem-loader">
                                    <img id="logo-preview" src="{{asset('/img/nova_imagem.PNG')}}" alt="">
                                </div>
                                <div style="display: none;">
                                    <input type="file" id="logo-input" class="form-control @error('fotoEvento') is-invalid @enderror" name="fotoEvento" value="{{ old('fotoEvento') }}" id="fotoEvento">
                                </div>
                                <small style="position: relative; top: 5px;">Tamanho minimo: 1024 x 425;<br>Formato: JPEG, JPG, PNG</small>
                            </div>
                            @error('fotoEvento')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{$message}}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-sm-5">
                            <div class="form-group">
                                <label for="icone">Ícone</label>
                                <div id="imagem-loader-icone" class="imagem-loader">
                                    <img id="icone-preview" src="{{asset('/img/nova_imagem.PNG')}}" alt="">
                                </div>
                                <div style="display: none;">
                                    <input type="file" id="icone-input" class="form-control @error('icone') is-invalid @enderror" name="icone" value="{{ old('icone') }}" id="icone">
                                </div>
                                <small style="position: relative; top: 5px;">Tamanho minimo: 1024 x 425;<br>Formato: JPEG, JPG, PNG</small>
                            </div>
                            @error('icone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{$message}}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <!-- Inicio e fim do evento -->
                    <div class="row">
                        <div class="col-sm-3">
                            <label for="dataInicio" class="col-form-label">{{ __('Início*') }}</label>
                            <input id="dataInicio" type="date" class="form-control @error('dataInicio') is-invalid @enderror" name="dataInicio" value="{{ old('dataInicio') }}" required autocomplete="dataInicio" autofocus>

                            @error('dataInicio')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-sm-3">
                            <label for="dataFim" class="col-form-label">{{ __('Fim*') }}</label>
                            <input id="dataFim" type="date" class="form-control @error('dataFim') is-invalid @enderror" name="dataFim" value="{{ old('dataFim') }}" required autocomplete="dataFim" autofocus>

                            @error('dataFim')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        {{-- <div class="col-sm-3">
                            <label for="timezone" class="col-form-label">{{ __('TimeZone') }}</label>
                            <select class="custom-select" id="inputGroupSelect01">
                                <option selected>Selecione...</option>
                                <option value="America/Rio_branco">America/Rio_branco</option>
                                <option value="America/Belem">America/Belem</option>
                                <option value="America/Recife">America/Recife</option>
                              </select>

                            @error('timezone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div> --}}
                    </div><!-- end Inicio e fim do evento -->

                    {{-- Foto Evento --}}
                    <div class="row justify-content-center" style="margin-top:10px">

                    </div>

                    <div class="row subtitulo" style="margin-top:20px">
                        <div class="col-sm-12">
                            <p>Endereço</p>
                        </div>
                    </div>
                    {{-- Rua | Número | Bairro --}}
                    <div class="row justify-content-center">
                        <div class="col-sm-4">
                            <label for="cep" class="col-form-label">{{ __('CEP*') }}</label>
                            <input value="{{ old('cep') }}" onblur="pesquisacep(this.value);" id="cep" name="cep" type="text" class="form-control @error('cep') is-invalid @enderror" required autocomplete="cep">

                            @error('cep')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-sm-6">
                            <label for="rua" class="col-form-label">{{ __('Rua*') }}</label>
                            <input id="rua" type="text" class="form-control @error('rua') is-invalid @enderror" name="rua" value="{{ old('rua') }}" required autocomplete="rua" autofocus>

                            @error('rua')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-sm-2">
                            <label for="numero" class="col-form-label">{{ __('Número*') }}</label>
                            <input id="numero" type="text" class="form-control @error('numero') is-invalid @enderror" name="numero" value="{{ old('numero') }}" required autocomplete="numero" autofocus maxlength="10">

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
                            <input id="bairro" type="text" class="form-control @error('bairro') is-invalid @enderror" name="bairro" value="{{ old('bairro') }}" required autocomplete="bairro" autofocus>

                            @error('bairro')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-sm-3">
                            <label for="cidade" class="col-form-label">{{ __('Cidade*') }}</label>
                            <input id="cidade" type="text" class="form-control apenasLetras @error('cidade') is-invalid @enderror" name="cidade" value="{{ old('cidade') }}" required autocomplete="cidade" autofocus>

                            @error('cidade')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-sm-4">
                            <label for="complemento" class="col-form-label">{{ __('Complemento') }}</label>
                            <input id="complemento" type="text" class="form-control apenasLetras @error('complemento') is-invalid @enderror" name="complemento" value="{{ old('complemento') }}" autocomplete="complemento" autofocus>

                            @error('complemento')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-sm-2">
                            <label for="uf" class="col-form-label">{{ __('UF*') }}</label>
                            {{-- <input id="uf" type="text" class="form-control @error('uf') is-invalid @enderror" name="uf" value="{{ old('uf') }}" required autocomplete="uf" autofocus> --}}
                            <select class="form-control @error('uf') is-invalid @enderror" id="uf" name="uf">
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
                            </select>

                            @error('uf')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                    </div>

                    <div class="row justify-content-start" style="margin: 30px 0 20px 0">
                        <div class="form-check">
                            <input name="termos" class="form-check-input @error('termos') is-invalid @enderror" type="checkbox" value="true" id="termos">
                            <label class="form-check-label" for="termos">
                                Concordo e respeitarei os <a href=" {{route('termos.de.uso')}} ">termos de uso</a> da plataforma Participa
                            </label>
                            @error('termos')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                          </div>
                    </div>

                    <div class="row justify-content-center" style="margin: 20px 0 20px 0">

                        <div class="col-md-7" style="padding-left:0">
                            {{-- <a class="btn btn-secondary botao-form" href="{{route('coord.home')}}" style="width:100%">Cancelar</a> --}}
                        </div>
                        <div class="col-md-5" style="padding-right:0">
                            <button type="submit" class="btn btn-atualizar-perfil botao-form" style="width:100%">
                                @if ($eventoPai ?? '')
                                    {{ __('Criar Subevento') }}
                                @else
                                    {{ __('Criar Evento') }}
                                @endif
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
        CKEDITOR.replace( 'descricao' );
        $('#cep').mask('00000-000');
        $(".apenasLetras").mask("#", {
            maxlength: false,
            translation: {
                '#': {pattern: /[A-zÀ-ÿ ]/, recursive: true}
            }
        });
        /*$('#numero').mask('#', {
            maxlength: false,
            translation: {
                '#': {pattern: /[0-9\\s/n]/, recursive: true}
            }
        });*/

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

        $('#imagem-loader-icone').click(function() {
            $('#icone-input').click();
            $('#icone-input').change(function() {
                if (this.files && this.files[0]) {
                    var file = new FileReader();
                    file.onload = function(e) {
                        document.getElementById("icone-preview").src = e.target.result;
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
