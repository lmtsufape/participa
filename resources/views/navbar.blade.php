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
                        Início
                    </a>
                </li>
                <li class="nav-item">
                    @guest
                    @else
                        <a class="nav-link" href="{{ route('home') }}" style="margin-right: 5px; margin-left: 5px">
                            Meus Eventos
                        </a>
                    @endguest
                </li>
                <li class="nav-item">
                    @guest
                    @else
                        <a class="nav-link" href="{{ route('meusCertificados') }}" style="margin-right: 5px; margin-left: 5px">
                            Meus Certificados
                        </a>
                    @endguest
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('validarCertificado') }}" style="margin-right: 5px; margin-left: 5px">
                        Validar Certificado
                    </a>
                </li>
                @auth
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        Perfis
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

                            <a class="dropdown-item" href="{{ route('home.user') }}">

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
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="{{ route('login') }}" style="margin-right: 5px; margin-left: 5px">{{ __('Login') }}</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="{{ route('register', app()->getLocale()) }}">{{ __('Cadastre-se') }}</a>
                    </li>
                @endauth
            </div>
        </div>
    </div>
</nav>
