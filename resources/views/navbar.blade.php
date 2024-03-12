<nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{route('index')}}">
            <img src="{{ asset('/img/logo.png') }}" alt="" style="height: 45px; width: 135px;">
        </a>
         {{-- <a id="change-mode" class="navbar-brand">
            <img id="img-change-mode" src="/img/icons/mom.png" alt="" style="height: 40px; width: 45px;">
        </a>
        <a id="font-size-plus" class="navbar-brand tam-letra">
            +A
        </a>
        <a id="font-size-min" class="navbar-brand tam-letra">
            -A
        </a> --}}
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Alterna navegação">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse flex-grow-0"  id="navbarNavAltMarkup">
            <div class="navbar-nav text-right">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('index') }}" style="margin-right: 5px; margin-left: 5px">
                    @lang('public.inicio')
                    </a>
                </li>
                <li class="nav-item">
                    @guest
                    @else
                        <a class="nav-link" href="{{ route('home') }}" style="margin-right: 5px; margin-left: 5px">
                        @lang('public.meusEventos')
                        </a>
                    @endguest
                </li>
                <li class="nav-item">
                    @guest
                    @else
                        <a class="nav-link" href="{{ route('meusCertificados') }}" style="margin-right: 5px; margin-left: 5px">
                        @lang('public.meusCertificados')
                        </a>
                    @endguest
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('validarCertificado') }}" style="margin-right: 5px; margin-left: 5px">
                    @lang('public.validarCertificado')
                    </a>
                </li>
                @auth
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    @lang('public.perfis')
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        {{-- Link Perfil --}}
                        <a class="dropdown-item" href="{{ route('home') }}">
                            <img src="{{asset('img/icons/perfil.svg')}}" alt="">
                            {{ __('Área do Participante') }}
                        </a>
                        @if (Auth::user()->revisor->count())
                            {{-- Rota - Area de Revisores --}}
                            <a class="dropdown-item" href="{{ route('revisor.index') }}">
                                <img src="{{asset('img/icons/revisor.png')}}" alt="">
                                {{ __('Área do Avaliador') }}
                            </a>
                        @endif

                        @if (isset(Auth::user()->administradors))
                            {{-- Rota - Area da Comissao --}}
                            <a class="dropdown-item" href="{{ route('admin.home') }}">
                                <img src="{{asset('img/icons/comissao.png')}}" alt="">
                                {{ __('Área do Administrador') }}
                            </a>
                        @endif

                        @if (isset(Auth::user()->coordComissaoCientifica))
                            {{-- Rota - Area da Comissao --}}

                            <a class="dropdown-item" href="{{ route('cientifica.home') }}">

                                <img src="{{asset('img/icons/comissao.png')}}" alt="">
                                {{ __('Área da Comissão Cientifica') }}
                            </a>
                        @endif

                        @if (isset(Auth::user()->coordComissaoOrganizadora))
                            {{-- Rota - Area da Comissao --}}
                            <a class="dropdown-item" href="{{ route('home.organizadora') }}">
                                <img src="{{asset('img/icons/comissao.png')}}" alt="">
                                {{ __('Área da Comissão Organizadora') }}
                            </a>
                        @endif

                        @if (Auth::user()->membroComissaoEvento->count())
                            {{-- Rota - Area da Comissao --}}
                            <a class="dropdown-item" href="{{ route('home.membro') }}">
                                <img src="{{asset('img/icons/comissao.png')}}" alt="">
                                {{ __('Área do Membro da Comissão Científica') }}
                            </a>
                        @endif

                        @if (Auth::user()->outrasComissoes->count())
                            {{-- Rota - Area da Comissao --}}
                            <a class="dropdown-item" href="{{ route('coord.membroOutrasComissoes') }}">
                                <img src="{{asset('img/icons/comissao.png')}}" alt="">
                                {{ __('Área do coordenador de outras comissões') }}
                            </a>
                        @endif

                        @if (isset(Auth::user()->coordEvento))
                            {{-- Rota - Area da Comissao --}}
                            <a class="dropdown-item" href="{{ route('coord.index') }}">
                                <img src="{{asset('img/icons/comissao.png')}}" alt="">
                                {{ __('Área do Coordenador de Evento') }}
                            </a>
                        @endif

                        @if ( isset(Auth::user()->coautor) && Auth::user()->coautor->count())
                            {{-- Rota - Area do coautor--}}
                            <a class="dropdown-item" href="{{ route('coautor.index') }}">
                                <img src="{{asset('img/icons/comissao.png')}}" alt="">
                                {{ __('Área de Coautor de Trabalho') }}
                            </a>
                        @endif
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        {{-- Link Perfil --}}
                        <a class="dropdown-item" href="{{ route('perfil') }}">
                            <img src="{{asset('img/icons/perfil.svg')}}" alt="">
                            {{ __('Minha Conta') }}
                        </a>

                        {{-- Link Trabalhos --}}
                        <a class="dropdown-item" href="{{ route('user.meusTrabalhos') }}">
                            <img src="{{asset('img/icons/file-alt-regular-black.svg')}}" alt="">
                            {{ __('Trabalhos Submetidos') }}
                        </a>

                        {{-- Link Logout --}}
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <img src="{{asset('img/icons/sign-out-alt-solid.svg')}}" alt="">
                            {{ __('Sair') }}
                        </a>


                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>

                <!-- <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        Idioma
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a href="locale/pt-BR" class="dropdown-item">Portugues</a>
                        <a href="locale/en" class="dropdown-item">Ingles</a>
                        <a href="locale/es" class="dropdown-item">Espanhol</a>
                    </div>

                </li> -->
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="{{ route('login') }}" style="margin-right: 5px; margin-left: 5px">{{ __('Login') }}</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="{{ route('register', app()->getLocale()) }}">{{ __('Cadastre-se') }}</a>
                    </li>
                @endauth

                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        <span id="idiomaAtual"><img src="https://flagicons.lipis.dev/flags/4x3/br.svg" alt="Português" style="width: 20px;"> Português </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#" onclick="trocarIdioma('en', 'https://flagicons.lipis.dev/flags/4x3/us.svg', 'English')">
                            <img src="https://flagicons.lipis.dev/flags/4x3/us.svg" alt="English" style="width: 20px;"> English
                        </a>
                        <a class="dropdown-item" href="#" onclick="trocarIdioma('es', 'https://flagicons.lipis.dev/flags/4x3/es.svg', 'Español')">
                            <img src="https://flagicons.lipis.dev/flags/4x3/es.svg" alt="Español" style="width: 20px;"> Español
                        </a>
                        <a class="dropdown-item" href="#" onclick="trocarIdioma('fr', 'https://flagicons.lipis.dev/flags/4x3/fr.svg', 'Français')">
                            <img src="https://flagicons.lipis.dev/flags/4x3/fr.svg" alt="Français" style="width: 20px;">Français
                        </a>
                        <a class="dropdown-item" href="#" onclick="trocarIdioma('pt', 'https://flagicons.lipis.dev/flags/4x3/br.svg', 'Português')">
                            <img src="https://flagicons.lipis.dev/flags/4x3/br.svg" alt="Português" style="width: 20px;">Português
                        </a>
                    </div>
                </li>



            </div>
        </div>
    </div>
</nav>

<div id="google_translate_element" style="display: none"></div>

<script type="text/javascript">
    var comboGoogleTradutor = null; //Varialvel global

    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'pt',
            includedLanguages: 'en,es,fr,pt',
            layout: google.translate.TranslateElement.InlineLayout.HORIZONTAL
        }, 'google_translate_element');

        comboGoogleTradutor = document.getElementById("google_translate_element").querySelector(".goog-te-combo");
    }

    function changeEvent(el) {
        if (el.fireEvent) {
            el.fireEvent('onchange');
        } else {
            var evObj = document.createEvent("HTMLEvents");

            evObj.initEvent("change", false, true);
            el.dispatchEvent(evObj);
        }
    }



    document.addEventListener("DOMContentLoaded", function() {
        // Pode remover o setTimeout se não estiver dependendo de algo que precise esperar para carregar
        setTimeout(function() {
            var idiomaAtual = document.documentElement.lang;
            switch (idiomaAtual) {
                case 'en':
                    trocarIdioma('en', 'https://flagicons.lipis.dev/flags/4x3/us.svg', 'English');
                    break;
                case 'es':
                    trocarIdioma('es', 'https://flagicons.lipis.dev/flags/4x3/es.svg', 'Español');
                    break;
                case 'pt':
                    trocarIdioma('pt', 'https://flagicons.lipis.dev/flags/4x3/br.svg', 'Português');
                    break;
                case 'fr':
                    trocarIdioma('fr', 'https://flagicons.lipis.dev/flags/4x3/fr.svg', 'Français')
                    break;
                // Outros casos conforme necessário
            }
        },1000);
    });



    function trocarIdioma(sigla, urlBandeira, nomeIdioma) {
        if (comboGoogleTradutor) {
            comboGoogleTradutor.value = sigla;
            changeEvent(comboGoogleTradutor); // Dispara a troca
        }

        // Atualiza o ícone e o texto da bandeira escolhida
        document.getElementById('idiomaAtual').innerHTML = `<img src="${urlBandeira}" alt="${nomeIdioma}" style="width: 20px;"> ${nomeIdioma}`;
    }






</script>
<script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>


