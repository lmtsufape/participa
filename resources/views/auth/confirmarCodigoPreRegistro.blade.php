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
    
    <br><br>

    <div class="row titulo text-center" style="color: #034652;">
        <h2 style="font-weight: bold;">{{__('Cadastro')}}</h2>
    </div>

    @if(Auth::check())
        <form method="POST" action="{{ route('administrador.criarUsuario', app()->getLocale()) }}">
    @else
        <form method="POST" action="{{ route('verificarCodigo') }}">
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
            <div class="container card my-3">
                <input type="hidden" value="{{ $email }}" name="email">

                <div class="form-group row">
                    <div class="col-md-12">
                        <label for="codigo" class="col-form-label required-field">{{ __('Código') }}</label>
                        <input id="codigo" type="text" class="form-control @error('codigo') is-invalid @enderror" name="codigo" value="{{ old('codigo') }}"  autocomplete="codigo" autofocus>
                    </div>
                </div>

                <div class="row form-group my-3">
                    <div class="col-md-10"></div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100" style="background-color: #034652; color: white; border-color: #034652;">
                            {{ __('Confirmar código') }}
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