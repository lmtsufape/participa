@extends('layouts.app')

@section('content')
<div class="container content" style="position: relative; top: 50px;">


    <div class="row titulo">
        <h1>{{__('Cadastro')}}</h1>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register', app()->getLocale()) }}">

        <div class="row subtitulo">
            <div class="col-sm-12">
                <p>{{__('País')}}</p>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-4">
                <label for="pais" class="col-form-label">{{ __('País') }}*</label>
                <select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" class="form-control @error('pais') is-invalid @enderror" id="pais">
                    <option value="" disabled selected hidden>-- {{__('País')}} --</option>
                    <option @if($pais == 'brasil') selected @endif value="/pt-BR/register/brasil">{{__('Brasil')}}</option>
                    <option @if($pais == 'usa') selected @endif value="/en/register/usa">{{__('Estados Unidos da América')}}</option>
                    <option @if($pais == 'outro') selected @endif value="/en/register/outro">{{__('Outro')}}</option>
                </select>
                <input type="hidden" name="pais" value="{{$pais}}">
                <small>{{__('O formulário seguirá os padrões desse país')}}.</small>

                @error('pais')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ __($message) }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="row subtitulo">
            <div class="col-sm-12">
                <p>{{__('Informações Pessoais')}}</p>
            </div>
        </div>

        @csrf
        {{-- Nome | CPF --}}
        <div class="form-group row">

            <div class="col-md-6">
                <label for="name" class="col-form-label">{{ __('Nome Completo') }}*</label>
                <input id="name" type="text" class="form-control apenasLetras @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}"  autocomplete="name" autofocus>

                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ __($message) }}</strong>
                    </span>
                @enderror
            </div>
            <div class="col-md-6">
                  <div class="custom-control custom-radio custom-control-inline col-form-label">
                    <input type="radio" id="customRadioInline1" name="customRadioInline" class="custom-control-input" checked>
                    <label class="custom-control-label" for="customRadioInline1">CPF</label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" @error('passaporte') checked @enderror id="customRadioInline2" name="customRadioInline" class="custom-control-input">
                    <label class="custom-control-label " for="customRadioInline2">{{__('Passaporte')}}</label>
                  </div>

                  <div id="fieldCPF" @error('passaporte') style="display: none" @enderror>
                    <input id="cpf" type="text" class="form-control @error('cpf') is-invalid @enderror" name="cpf" value="{{ old('cpf') }}" autocomplete="cpf" placeholder="CPF" autofocus>

                    @error('cpf')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ __($message) }}</strong>
                        </span>
                    @enderror
                  </div>
                  <div id="fieldPassaporte" @error('passaporte') style="display: block" @enderror style="display: none" >
                    <input id="passaporte" type="text" class="form-control @error('passaporte') is-invalid @enderror" name="passaporte" placeholder="{{__('Passaporte')}}" value="{{ old('passaporte') }}"  autocomplete="passaporte" autofocus>

                    @error('passaporte')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ __($message) }}</strong>
                        </span>
                    @enderror
                  </div>

            </div>

        </div>
        {{-- Instituição de Ensino e Celular --}}
        <div class="form-group row">
            <div class="col-md-8">
                <label for="instituicao" class="col-form-label">{{ __('Instituição') }}*</label>
                <input id="instituicao" type="text" class="form-control apenasLetras @error('instituicao') is-invalid @enderror" name="instituicao" value="{{ old('instituicao') }}"  autocomplete="instituicao" autofocus>

                @error('instituicao')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ __($message) }}</strong>
                    </span>
                @enderror
            </div>
            <div class="col-md-4">
                <label for="celular" class="col-form-label">{{ __('Celular') }}*</label><br>
                <input id="phone" class="form-control celular @error('celular') is-invalid @enderror" type="tel" name="celular" value="{{old('celular')}}" required autocomplete="celular" onkeyup="process(event)">
                <div class="alert alert-info mt-1" style="display: none"></div>
                <div id="celular-invalido" class="alert alert-danger mt-1" role="alert"   style="display: none"></div>

                @error('celular')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ __($message) }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        {{-- Email | Senha | Confirmar Senha --}}
        <div class="form-group row">

            <div class="col-md-4">
                <label for="email" class="col-form-label">{{ __('E-Mail') }}*</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  autocomplete="email">

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ __($message) }}</strong>
                    </span>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="password" class="col-form-label">{{ __('Senha') }}*</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password"  autocomplete="new-password">
                <small>{{__('Deve ter no mínimo 8 caracteres (letras ou números)')}}.</small>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ __($message) }}</strong>
                    </span>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="password-confirm" class="col-form-label">{{ __('Confirme a Senha') }}*</label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation"  autocomplete="new-password">
            </div>
        </div>


        <div class="row subtitulo">
            <div class="col-sm-12">
                <p>{{__('Endereço')}}</p>
            </div>
        </div>

        {{-- Rua | Número | Bairro --}}
        <div class="form-group row">
          <div class="col-md-2">
              <label for="cep" class="col-form-label">{{ __('CEP') }}</label>
              <input value="{{old('cep')}}" id="cep" type="text"  autocomplete="cep" name="cep" autofocus class="form-control field__input a-field__input" placeholder="{{__('CEP')}}" size="10" maxlength="9" >
              @error('cep')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ __($message) }}</strong>
                  </span>
              @enderror
          </div>
        </div>
        <div class="form-group row">


            <div class="col-md-6">
                <label for="rua" class="col-form-label">{{ __('Rua') }}</label>
                <input value="{{old('rua')}}" id="rua" type="text" class="form-control @error('rua') is-invalid @enderror" name="rua"  autocomplete="new-password">

                @error('rua')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ __($message) }}</strong>
                    </span>
                @enderror
            </div>

            <div class="col-md-2">
                <label for="numero" class="col-form-label">{{ __('Número') }}</label>
                <input value="{{old('numero')}}" id="numero" type="text" class="form-control @error('numero') is-invalid @enderror" name="numero" autocomplete="numero" maxlength="10">

                @error('numero')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ __($message) }}</strong>
                    </span>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="bairro" class="col-form-label">{{ __('Bairro') }}</label>
                <input value="{{old('bairro')}}" id="bairro" type="text" class="form-control @error('bairro') is-invalid @enderror" name="bairro"  autocomplete="bairro">

                @error('bairro')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ __($message) }}</strong>
                    </span>
                @enderror
            </div>

          </div>

          <div class="form-group row">

            <div class="col-md-4">
                <label for="cidade" class="col-form-label">{{ __('Cidade') }}</label>
                <input value="{{old('cidade')}}" id="cidade" type="text" class="form-control apenasLetras @error('cidade') is-invalid @enderror" name="cidade"  autocomplete="cidade">

                @error('cidade')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ __($message) }}</strong>
                    </span>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="complemento" class="col-form-label">{{ __('Complemento') }}</label>
                <input type="text" value="{{old('complemento')}}" id="complemento" class="form-control  @error('complemento') is-invalid @enderror" name="complemento" >

                @error('complemento')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ __($message) }}</strong>
                    </span>
                @enderror
            </div>

            <div class="col-sm-4" id="groupformufinput">
                <label for="ufInput" class="col-form-label">{{ __('UF') }}</label>
                <input type="text" value="{{old('uf')}}" id="ufInput" class="form-control  @error('uf') is-invalid @enderror" name="uf" >

                @error('uf')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ __($message) }}</strong>
                    </span>
                @enderror
            </div>

            <div class="col-sm-4" id="groupformuf">
                <label for="uf" class="col-form-label">{{ __('UF') }}</label>
                {{-- <input id="uf" type="text" class="form-control @error('uf') is-invalid @enderror" name="uf" value="{{ old('uf') }}"  autocomplete="uf" autofocus> --}}
                <select class="form-control @error('uf') is-invalid @enderror" id="uf" name="uf">
                    <option value="" disabled selected hidden>-- {{__('UF')}} --</option>
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
                    <strong>{{ __($message) }}</strong>
                </span>
                @enderror
            </div>
          </div>

          <div class="row justify-content-between mt-4">
            <div class="col-md-5">
                <a class="btn btn-secondary w-100" href="{{route('cancelarCadastro')}}">{{__('Cancelar cadastro')}}</a>
            </div>
            <div class="col-md-5 d-flex justify-content-end">
                <button type="submit" class="btn btn-primary w-100">
                    {{ __('Finalizar cadastro') }}
                </button>
            </div>
        </div>
    </form>

</div>

@include('componentes.footer')

@endsection

@section('javascript')
  <script type="text/javascript" >
    $(document).ready(function($){

      $('#cpf').mask('000.000.000-00');
      if($('html').attr('lang') == 'en') {
        //$('#celular').mask('(000) 000-0000');
        $('#groupformuf').addClass('d-none');
        $("#uf").prop('disabled', true);
      } else if ($('html').attr('lang') == 'pt-BR') {
        $('#cep').blur(function () {
            pesquisacep(this.value);
        });
        $('#groupformufinput').addClass('d-none');
        $("#ufInput").prop('disabled', true);
        var SPMaskBehavior = function (val) {
          return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        },
        spOptions = {
          onKeyPress: function(val, e, field, options) {
            field.mask(SPMaskBehavior.apply({}, arguments), options);
          }
        };
        //$('#celular').mask(SPMaskBehavior, spOptions);
        $('#cep').mask('00000-000');
      }
      $(".apenasLetras").mask("#", {
        maxlength: false,
        translation: {
            '#': {pattern: /[A-zÀ-ÿ ]/, recursive: true}
        }
      });
      //$('#numero').mask('0000000000000');

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
  <script src="{{ asset('js/celular.js') }}" defer></script>
  <script src="{{ asset('js/jquery-mask-plugin.js')}}" defer></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"/>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script type="text/javascript">

    $(document).ready(function(){
        // $("#fieldPassaporte").hide();
        $("#customRadioInline1").click(function(){
            $("#fieldPassaporte").hide();
            $("#fieldCPF").show();
        });

        $("#customRadioInline2").click(function(){
            $("#fieldPassaporte").show();
            $("#fieldCPF").hide();
        });

    });

  </script>
    
@endsection
