@extends('layouts.app')

@section('content')
    <div class="banner-perfil position-absolute w-100 mt-n2">
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
    <div class="card-perfil justify-content-center position-relative">
        <div class="card card-change-mode" style="width: 80%;">
            <div class="card-body">
                <div class="container">
                    <div class="row justify-content-center titulo">
                        <h1>Editar {{$evento->nome}}</h1>
                    </div>

                    <form action="{{route('evento.update',['id' => $evento->id])}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row subtitulo">
                            <div class="col-sm-12">
                                Informações Gerais
                            </div>
                        </div>
                        {{-- nome | Tipo--}}
                        <div class="form-row">
                            <div class="col-sm-6 form-group">
                                <label for="nome" class="col-form-label">{{ __('Nome*') }}</label>
                                <input id="nome" type="text" class="form-control @error('nome') is-invalid @enderror" name="nome" value="{{ old('nome', $evento->nome) }}"
                                       required autocomplete="nome" autofocus>

                                @error('nome')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-sm-6 form-group">
                                <label for="email" class="col-form-label">{{ __('E-mail de contato*') }}</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $evento->email) }}"
                                       required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            @if($evento->evento_pai_id != null)
                                <div class="col-sm-6 form-group">
                                    <label for="email_coordenador" class="col-form-label">{{ __('E-mail do coordenador') }}</label>
                                    @if($evento->coordenadoresEvento()->exists())
                                        <input class="form-control @error('email_coordenador') is-invalid @enderror" type="email" value="{{old('email_coordenador', $evento->coordenadoresEvento()->first()->email)}}" name="email_coordenador" id="email_coordenador">
                                    @else
                                        <input class="form-control @error('email_coordenador') is-invalid @enderror" type="email" value="{{old('email_coordenador')}}" name="email_coordenador" id="email_coordenador">
                                    @endif

                                    @error('email_coordenador')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            @endif
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
                            <div class="@if($evento->evento_pai_id != null) col-sm-3 @else col-sm-6 @endif form-group">
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
                            <div class="@if($evento->evento_pai_id != null) col-sm-3 @else col-sm-6 @endif form-group">
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
                        <br>
                        {{-- Descricao Evento --}}
                        <div class="form-group">
                            <label for="descricao">Descrição*</label>
                            <textarea class="form-control @error('descricao') is-invalid @enderror" id="descricao" name="descricao"
                                        rows="8">@if(old('descricao') != null) {{ old('descricao') }} @else {{$evento->descricao}} @endif</textarea>
                            @error('descricao')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-row">
                            <div class="col-sm-7 form-group">
                                <label for="fotoEvento">Banner</label>
                                <div id="imagem-loader" class="imagem-loader">
                                    @if ($evento->fotoEvento != null)
                                        <img id="logo-preview" class="img-fluid" src="{{asset('storage/'.$evento->fotoEvento)}}" alt="">
                                    @else
                                        <img id="logo-preview" class="img-fluid" src="{{asset('/img/nova_imagem.PNG')}}" alt="">
                                    @endif
                                </div>
                                <div style="display: none;">
                                    <input type="file" id="logo-input" class="form-control @error('fotoEvento') is-invalid @enderror" name="fotoEvento" value="{{ old('fotoEvento') }}"
                                            id="fotoEvento">
                                </div>
                                <small style="position: relative; top: 5px;">Tamanho minimo: 1024 x 425;<br>Formato: JPEG, JPG, PNG</small>
                                @error('fotoEvento')
                                <br>
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{$message}}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-sm-5 form-group">
                                <label for="icone">Ícone</label>
                                <div id="imagem-loader-icone" class="imagem-loader">
                                    @if ($evento->icone != null)
                                        <img id="icone-preview" class="img-fluid" src="{{asset('storage/'.$evento->icone)}}" alt="">
                                    @else
                                        <img id="icone-preview" class="img-fluid" src="{{asset('/img/nova_imagem.PNG')}}" alt="">
                                    @endif
                                </div>
                                <div style="display: none;">
                                    <input type="file" id="icone-input" class="form-control @error('icone') is-invalid @enderror" name="icone" value="{{ old('icone') }}" id="icone">
                                </div>
                                <small style="position: relative; top: 5px;">O arquivo será redimensionado para 600 x 600;<br>Formato: JPEG, JPG, PNG</small>
                                @error('icone')
                                <br>
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{$message}}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            {{-- Início do Evento --}}
                            <div class="col-sm-3 form-group">
                                <label for="dataInicio" class="col-form-label">{{ __('Início*') }}</label>
                                <input id="dataInicio" type="date" class="form-control @error('dataInicio') is-invalid @enderror" name="dataInicio"
                                       value="{{ old('dataInicio', $evento->dataInicio) }}" required autocomplete="dataInicio" autofocus>

                                @error('dataInicio')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>{{--End Início do Evento --}}
                            {{-- Fim do Evento --}}
                            <div class="col-sm-3 form-group">
                                <label for="dataFim" class="col-form-label">{{ __('Fim*') }}</label>
                                <input id="dataFim" type="date" class="form-control @error('dataFim') is-invalid @enderror" name="dataFim"
                                       value="{{ old('dataFim', $evento->dataFim) }}"
                                       required autocomplete="dataFim" autofocus>

                                @error('dataFim')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>{{-- end Fim do Evento --}}
                        </div>

                        <div class="form-row">
                            <div class="col-sm-12">
                                <label for="dataLimiteInscricao" class="col-form-label">
                                    {{ __('Data de encerramento de inscrições') }}
                                    <small>
                                        (<span style="color: red">Disponível ao habilitar o módulo de inscrição!</span>)<br>
                                        Informe uma data para encerramento das inscrições no evento. Caso não informada, a data limite para inscrição no evento será um dia prévio a data de início do evento.
                                    </small>
                                </label>
                            </div>
                            <div class="col-sm-3 form-group">
                                <input id="dataLimiteInscricao" type="datetime-local" class="form-control @error('dataLimiteInscricao') is-invalid @enderror" name="dataLimiteInscricao" @if(old('dataLimiteInscricao') != null) value="{{ old('dataLimiteInscricao') }}" @else value="{{$evento->data_limite_inscricao}}" @endif autocomplete="dataLimiteInscricao" autofocus>
                                @error('dataLimiteInscricao')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row subtitulo">
                            <div class="col-sm-12">
                                Endereço
                            </div>
                        </div>

                        {{-- Rua | Número | Bairro --}}
                        <div class="form-row">
                            <div class="col-sm-4 form-group">
                                <label for="cep" class="col-form-label">{{ __('CEP') }}</label>
                                <input onblur="pesquisacep(this.value);" id="cep" type="text" class="form-control @error('cep') is-invalid @enderror" name="cep"
                                       value="{{ old('cep', $endereco->cep) }}" required autocomplete="cep" autofocus>
                                @error('cep')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-sm-6 form-group">
                                <label for="rua" class="col-form-label">{{ __('Rua*') }}</label>
                                <input id="rua" type="text" class="form-control @error('rua') is-invalid @enderror" name="rua" value="{{ old('rua', $endereco->rua) }}"
                                       required autocomplete="rua" autofocus>
                                @error('rua')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-sm-2 form-group">
                                <label for="numero" class="col-form-label">{{ __('Número*') }}</label>
                                <input id="numero" type="text" class="form-control @error('numero') is-invalid @enderror" name="numero" value="{{ old('numero', $endereco->numero) }}"
                                       required autocomplete="numero" autofocus maxlength="10">
                                @error('numero')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>


                        </div>{{--end Rua | Número | Bairro --}}

                        <div class="form-row">
                            <div class="col-sm-3 form-group">
                                <label for="bairro" class="col-form-label">{{ __('Bairro*') }}</label>
                                <input id="bairro" type="text" class="form-control @error('bairro') is-invalid @enderror" name="bairro" value="{{ old('bairro', $endereco->bairro) }}"
                                       required autocomplete="bairro" autofocus>

                                @error('bairro')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                @enderror
                            </div>
                            <div class="col-sm-3 form-group">
                                <label for="cidade" class="col-form-label">{{ __('Cidade*') }}</label>
                                <input id="cidade" type="text" class="form-control apenasLetras @error('cidade') is-invalid @enderror" name="cidade"
                                       value="{{ old('cidade', $endereco->cidade) }}" required autocomplete="cidade" autofocus>

                                @error('cidade')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                @enderror
                            </div>
                            <div class="col-sm-4 form-group">
                                <label for="complemento" class="col-form-label">{{ __('Complemento') }}</label>
                                <input id="complemento" type="text" class="form-control apenasLetras @error('complemento') is-invalid @enderror" name="complemento"
                                       value="@if(old('complemento') != null){{old('complemento')}}@else{{$evento->endereco->complemento}}@endif" autocomplete="complemento" autofocus>

                                @error('complemento')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                @enderror
                            </div>
                            <div class="col-sm-2 form-group">
                                <label for="uf" class="col-form-label">{{ __('UF*') }}</label>
                                {{-- <input id="uf" type="text" class="form-control @error('uf') is-invalid @enderror" name="uf" value="{{ old('uf') }}" required autocomplete="uf" autofocus> --}}
                                <select class="form-control @error('uf') is-invalid @enderror" id="uf" name="uf">
                                    <option value="" disabled selected hidden>-- UF --</option>
                                    <option @selected(old('uf', $endereco->uf) == 'AC') value="AC">Acre</option>
                                    <option @selected(old('uf', $endereco->uf) == 'AL') value="AL">Alagoas</option>
                                    <option @selected(old('uf', $endereco->uf) == 'AP') value="AP">Amapá</option>
                                    <option @selected(old('uf', $endereco->uf) == 'AM') value="AM">Amazonas</option>
                                    <option @selected(old('uf', $endereco->uf) == 'BA') value="BA">Bahia</option>
                                    <option @selected(old('uf', $endereco->uf) == 'CE') value="CE">Ceará</option>
                                    <option @selected(old('uf', $endereco->uf) == 'DF') value="DF">Distrito Federal</option>
                                    <option @selected(old('uf', $endereco->uf) == 'ES') value="ES">Espírito Santo</option>
                                    <option @selected(old('uf', $endereco->uf) == 'GO') value="GO">Goiás</option>
                                    <option @selected(old('uf', $endereco->uf) == 'MA') value="MA">Maranhão</option>
                                    <option @selected(old('uf', $endereco->uf) == 'MT') value="MT">Mato Grosso</option>
                                    <option @selected(old('uf', $endereco->uf) == 'MS') value="MS">Mato Grosso do Sul</option>
                                    <option @selected(old('uf', $endereco->uf) == 'MG') value="MG">Minas Gerais</option>
                                    <option @selected(old('uf', $endereco->uf) == 'PA') value="PA">Pará</option>
                                    <option @selected(old('uf', $endereco->uf) == 'PB') value="PB">Paraíba</option>
                                    <option @selected(old('uf', $endereco->uf) == 'PR') value="PR">Paraná</option>
                                    <option @selected(old('uf', $endereco->uf) == 'PE') value="PE">Pernambuco</option>
                                    <option @selected(old('uf', $endereco->uf) == 'PI') value="PI">Piauí</option>
                                    <option @selected(old('uf', $endereco->uf) == 'RJ') value="RJ">Rio de Janeiro</option>
                                    <option @selected(old('uf', $endereco->uf) == 'RN') value="RN">Rio Grande do Norte</option>
                                    <option @selected(old('uf', $endereco->uf) == 'RS') value="RS">Rio Grande do Sul</option>
                                    <option @selected(old('uf', $endereco->uf) == 'RO') value="RO">Rondônia</option>
                                    <option @selected(old('uf', $endereco->uf) == 'RR') value="RR">Roraima</option>
                                    <option @selected(old('uf', $endereco->uf) == 'SC') value="SC">Santa Catarina</option>
                                    <option @selected(old('uf', $endereco->uf) == 'SP') value="SP">São Paulo</option>
                                    <option @selected(old('uf', $endereco->uf) == 'SE') value="SE">Sergipe</option>
                                    <option @selected(old('uf', $endereco->uf) == 'TO') value="TO">Tocantins</option>
                                </select>
                                @error('uf')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row justify-content-end mt-4">
                            <div class="col-md-6">
                                <button type="submit" class="btn button-prevent-multiple-submits btn-primary w-100">
                                    {{ __('Salvar') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection
@section('javascript')
    <script type="text/javascript">
        $(document).ready(function ($) {

            // CKEDITOR.replace('descricao');

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

            $('#imagem-loader').click(function () {
                $('#logo-input').click();
                $('#logo-input').change(function () {
                    if (this.files && this.files[0]) {
                        var file = new FileReader();
                        file.onload = function (e) {
                            document.getElementById("logo-preview").src = e.target.result;
                        };
                        file.readAsDataURL(this.files[0]);
                    }
                })
            });

            $('#imagem-loader-icone').click(function () {
                $('#icone-input').click();
                $('#icone-input').change(function () {
                    if (this.files && this.files[0]) {
                        var file = new FileReader();
                        file.onload = function (e) {
                            document.getElementById("icone-preview").src = e.target.result;
                        };
                        file.readAsDataURL(this.files[0]);
                    }
                })
            });
        });

        function limpa_formulário_cep() {
            //Limpa valores do formulário de cep.
            document.getElementById('rua').value = ("");
            document.getElementById('bairro').value = ("");
            document.getElementById('cidade').value = ("");
            document.getElementById('uf').value = ("");
        }

        function meu_callback(conteudo) {
            if (!("erro" in conteudo)) {
                //Atualiza os campos com os valores.
                document.getElementById('rua').value = (conteudo.logradouro);
                document.getElementById('bairro').value = (conteudo.bairro);
                document.getElementById('cidade').value = (conteudo.localidade);
                document.getElementById('uf').value = (conteudo.uf);
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
                if (validacep.test(cep)) {
                    //Preenche os campos com "..." enquanto consulta webservice.
                    document.getElementById('rua').value = "...";
                    document.getElementById('bairro').value = "...";
                    document.getElementById('cidade').value = "...";
                    document.getElementById('uf').value = "...";
                    //Cria um elemento javascript.
                    var script = document.createElement('script');
                    //Sincroniza com o callback.
                    script.src = 'https://viacep.com.br/ws/' + cep + '/json/?callback=meu_callback';
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
