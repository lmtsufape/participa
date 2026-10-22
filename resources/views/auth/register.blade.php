@extends('layouts.app')

@section('content')

    <div class="container content mb-5 position-relative">

        @php
            /*
             * No cadastro o documento já foi definido/validado
             * na etapa anterior.
             */
            $documentoTipo = old(
                'documento_tipo',
                session('cpf') ? 'cpf' : (session('cnpj') ? 'cnpj' : (session('passaporte') ? 'passaporte' : null)),
            );

            $paisAtual = old('pais', session('pais', 'brasil'));
        @endphp

        <x-ui.content-header title="{{ __('Informações de cadastro') }}"
            description="Preencha o formulário abaixo com atenção e responsabilidade."
        />

        @if (Auth::check())
            <form method="POST" action="{{ route('administrador.criarUsuario', app()->getLocale()) }}">
        @else
            <form method="POST" action="{{ route('register', app()->getLocale()) }}">
        @endif

        @csrf

        <div>
            @include('user._form')
            {{-- =========================================================
                TERMOS
            ========================================================== --}}

            <div class="container">
                {{-- Autorização de imagem --}}
                <div class="mb-3 mt-3">

                    <input name="termosImagem" class="form-check-input" type="checkbox" value="true"
                        id="termosImagem" @checked(old('termosImagem')) required>

                    <label class="form-check-label" for="termosImagem">
                        {{ __('Concordo com o') }}

                        <a href="#modal-termo-de-cessao" data-bs-toggle="modal" data-bs-target="#modal-termo-de-cessao">
                            Termo de autorização de imagem, voz e performance
                        </a>.
                    </label>

                </div>

                {{-- Termos da plataforma --}}
                <div class="mb-3">

                    <input name="termos" class="form-check-input" type="checkbox" value="true" id="termos"
                        @checked(old('termos')) required>

                    <label class="form-check-label" for="termos">
                        {{ __('Concordo e respeitarei os') }}

                        <a href="#modal-termo-de-uso" data-bs-toggle="modal" data-bs-target="#modal-termo-de-uso">
                            Termos de uso da plataforma
                        </a>.
                    </label>

                </div>
                    <button type="submit" class="btn btn-my-primary w-25">
                        {{ __('Confirmar Cadastro') }}
                    </button>
            </div>
        </form>

    </div>

    @include('auth.modal-termo-de-uso')

@endsection


@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {

            /*
             * Máscaras
             */
            $('#cpf_display').mask('000.000.000-00');
            $('#cnpj_display').mask('00.000.000/0000-00');

            @if ($paisAtual === 'brasil' || !$paisAtual)

                $('#cep').mask('00000-000');

                $('#cep').blur(function() {
                    pesquisacep(this.value);
                });
            @endif


            /*
             * Campos que aceitam letras/caracteres permitidos.
             */
            $(".apenasLetras").mask("#", {
                maxlength: false,
                translation: {
                    '#': {
                        pattern: /[A-zÀ-ÿ0-9\s\-\.\(\)\[\]\{\}\/\\,;&@#$%*+=|<>!?\~`'"]/,
                        recursive: true
                    }
                }
            });


            /*
             * Gênero - "Outro"
             */
            function atualizarOutroGenero() {

                const genero = $('input[name="genero"]:checked').val();

                $('#outroGenero')
                    .prop('disabled', genero !== 'outro');

                if (genero !== 'outro') {
                    $('#outroGenero').val('');
                }
            }

            $('input[name="genero"]').on('change', atualizarOutroGenero);

            atualizarOutroGenero();


            /*
             * Raça - "Outra"
             */
            function atualizarOutraRaca() {

                const raca = $('input[name="raca"]:checked').val();

                $('#outraRaca')
                    .prop('disabled', raca !== 'outra_raca');

                if (raca !== 'outra_raca') {
                    $('#outraRaca').val('');
                }
            }

            $('input[name="raca"]').on('change', atualizarOutraRaca);

            atualizarOutraRaca();


            /*
             * Comunidade tradicional
             */
            function atualizarComunidadeTradicional() {

                const valor =
                    $('input[name="comunidadeTradicional"]:checked').val();

                $('#nomeComunidadeTradicional')
                    .prop('disabled', valor !== 'true');

                if (valor !== 'true') {
                    $('#nomeComunidadeTradicional').val('');
                }
            }

            $('input[name="comunidadeTradicional"]')
                .on('change', atualizarComunidadeTradicional);

            atualizarComunidadeTradicional();


            /*
             * Organização
             */
            function atualizarOrganizacao() {

                const valor =
                    $('input[name="participacaoOrganizacao"]:checked').val();

                $('#nomeOrganizacao')
                    .prop('disabled', valor !== 'true');

                if (valor !== 'true') {
                    $('#nomeOrganizacao').val('');
                }
            }

            $('input[name="participacaoOrganizacao"]')
                .on('change', atualizarOrganizacao);

            atualizarOrganizacao();


            /*
             * Necessidade especial "Outra"
             */
            function atualizarOutraNecessidade() {

                const marcada =
                    $('#necessidade_outra_necessidade').is(':checked');

                $('#outraNecessidadeEspecial')
                    .prop('disabled', !marcada);

                if (!marcada) {
                    $('#outraNecessidadeEspecial').val('');
                }
            }

            $('#necessidade_outra_necessidade')
                .on('change', atualizarOutraNecessidade);

            atualizarOutraNecessidade();


            /*
             * Evita marcar "Nenhuma" juntamente com outras necessidades.
             */
            $('input[name="necessidadesEspeciais[]"]').on('change', function() {

                if ($(this).val() === 'nenhuma' && $(this).is(':checked')) {

                    $('input[name="necessidadesEspeciais[]"]')
                        .not(this)
                        .prop('checked', false);

                } else if ($(this).is(':checked')) {

                    $('#necessidade_nenhuma')
                        .prop('checked', false);

                }

                atualizarOutraNecessidade();
            });

        });


        /*
         |--------------------------------------------------------------------------
         | ViaCEP
         |--------------------------------------------------------------------------
         */

        function limpa_formulário_cep() {

            document.getElementById('rua').value = '';
            document.getElementById('bairro').value = '';
            document.getElementById('cidade').value = '';
            document.getElementById('uf').value = '';

        }


        function meu_callback(conteudo) {

            if (!('erro' in conteudo)) {

                document.getElementById('rua').value =
                    conteudo.logradouro;

                document.getElementById('bairro').value =
                    conteudo.bairro;

                document.getElementById('cidade').value =
                    conteudo.localidade;

                document.getElementById('uf').value =
                    conteudo.uf;

            } else {

                limpa_formulário_cep();

                alert('CEP não encontrado.');

            }

        }


        function pesquisacep(valor) {

            const cep = valor.replace(/\D/g, '');

            if (!cep) {
                limpa_formulário_cep();
                return;
            }

            const validacep = /^[0-9]{8}$/;

            if (!validacep.test(cep)) {

                limpa_formulário_cep();

                alert('Formato de CEP inválido.');

                return;
            }

            document.getElementById('rua').value = '...';
            document.getElementById('bairro').value = '...';
            document.getElementById('cidade').value = '...';
            document.getElementById('uf').value = '...';

            const script = document.createElement('script');

            script.src =
                'https://viacep.com.br/ws/' +
                cep +
                '/json/?callback=meu_callback';

            document.body.appendChild(script);

        }
    </script>

    <script src="{{ asset('js/celular.js') }}" defer></script>

    <script src="{{ asset('js/jquery-mask-plugin.js') }}" defer></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />
@endsection
