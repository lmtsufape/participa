@extends('layouts.app')

@section('content')
<div class="container content mb-5 position-relative">
    <style>
        .etapas {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #ccc;
            margin-bottom: 20px;
            font-family: sans-serif;
        }
        
        .etapa {
            flex: 1;
            text-align: left;
            padding: 10px 0;
            color: #aaa;
            font-weight: normal;
            border-bottom: 2px solid transparent;
        }
        
        .etapa.ativa {
            color: #004d51;
            font-weight: bold;
            border-bottom: 2px solid #004d51;
        }

        .required-field::after {
            content: "*";
            color: #D44100;
            margin-left: 2px;
        }
    </style>
    
    <br><br>

    @if(session('sucesso'))
        <div class="alert alert-success">
            {{ session('sucesso') }}
        </div>
    @endif

    @if(session('erro'))
        <div class="alert alert-danger">
            {{ session('erro') }}
        </div>
    @endif

    <div class="row titulo text-center" style="color: #034652;">
        <h2 style="font-weight: bold;">{{__('Cadastro')}}</h2>
    </div>

    @if(Auth::check())
        <form method="POST" action="{{ route('administrador.criarUsuario', app()->getLocale()) }}">
    @else
        <form method="POST" action="{{ route('enviarCodigo') }}">
    @endif
        <div id="etapa-1">
            <div class="etapas" style="font-weight: 500;">
                <div class="etapa ativa">
                    <p>1. {{ __('Validação de cadastro') }}</p>
                </div>
                <div class="etapa">
                    <p>2. {{__('Informações de cadastro')}}</p>
                </div>
            </div>

            @csrf
            {{-- Nome | CPF | E-mail --}}
            <div class="container card">
                <br>

                <div class="form-group row">
                    <div class="col-md-12">
                        <label for="nome" class="col-form-label required-field">{{ __('Nome completo') }}</label>
                        <input id="nome" type="text" class="form-control apenasLetras @error('nome') is-invalid @enderror" name="nome" value="{{ old('nome') }}"  autocomplete="nome" autofocus>

                        @error('nome')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ __($message) }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <div class="col-md-6">
                        <div class="custom-control custom-radio custom-control-inline col-form-label">
                            <input type="radio" id="customRadioInline1" name="customRadioInline" class="custom-control-input" checked>
                            <label class="custom-control-label me-2" for="customRadioInline1">CPF</label>

                            <input type="radio" @error('passaporte') checked @enderror id="customRadioInline2" name="customRadioInline" class="custom-control-input">
                            <label class="custom-control-label me-2" for="customRadioInline2">{{__('CNPJ')}}</label>

                            <input type="radio" @error('passaporte') checked @enderror id="customRadioInline3" name="customRadioInline" class="custom-control-input">
                            <label class="custom-control-label " for="customRadioInline3">{{__('Passaporte')}}</label>
                        </div>

                        <div id="fieldCPF" @error('passaporte') style="display: none" @enderror>
                            <input id="cpf" type="text" class="form-control @error('cpf') is-invalid @enderror" name="cpf" value="{{ old('cpf') }}" autocomplete="cpf" placeholder="CPF" autofocus>

                            @error('cpf')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ __($message) }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div id="fieldCNPJ" @error('passaporte') style="display: block" @enderror style="display: none" >
                            <input id="cnpj" type="text" class="form-control @error('cnpj') is-invalid @enderror" name="cnpj" placeholder="{{__('CNPJ')}}" value="{{ old('cnpj') }}"  autocomplete="cnpj" autofocus>

                            @error('cnpj')
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

                    <div class="col-md-6">
                        <label for="email" class="col-form-label required-field">{{ __('E-mail') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  autocomplete="email">

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ __($message) }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row form-group mb-3">
                    <div class="col-md-10"></div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100" style="background-color: #034652; color: white; border-color: #034652;">
                            {{ __('Continuar') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        </form>
</div>
@endsection

@section('javascript')
  <script type="text/javascript" >
    $(document).ready(function($){

      $('#cpf').mask('000.000.000-00');
      $('#cnpj').mask('00.000.000/0000-00');
      if($('html').attr('lang') == 'en') {
      } else if ($('html').attr('lang') == 'pt-BR') {
        $('#cep').blur(function () {
            pesquisacep(this.value);
        });
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"/>
  <script type="text/javascript">

    $(document).ready(function(){
        // $("#fieldPassaporte").hide();
        $("#customRadioInline1").click(function(){
            $("#fieldPassaporte").hide();
            $("#fieldCNPJ").hide();
            $("#fieldCPF").show();
        });

        $("#customRadioInline2").click(function(){
            $("#fieldPassaporte").hide();
            $("#fieldCNPJ").show();
            $("#fieldCPF").hide();
        });

        $("#customRadioInline3").click(function(){
            $("#fieldPassaporte").show();
            $("#fieldCNPJ").hide();
            $("#fieldCPF").hide();
        });

    });

    function proximaEtapa() {
        document.getElementById('etapa-1').style.display = 'none';
        document.getElementById('etapa-2').style.display = 'block';
    }

    function etapaAnterior() {
        document.getElementById('etapa-1').style.display = 'block';
        document.getElementById('etapa-2').style.display = 'none';
    }

  </script>

@endsection