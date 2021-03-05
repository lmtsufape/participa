<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        <script src="{{ asset('js/jquery-3.5.1.slim.min.js')}}"></script>
        <script src="{{ asset('js/jquery-mask-plugin.js')}}"></script>
        
        <script src="{{ asset('js/app.js') }}" defer></script>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

        <script src='{{asset('fullcalendar-5.3.2/lib/main.js')}}'></script>
        <script src='{{asset('fullcalendar-5.3.2/lib/locales-all.js')}}'></script>

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
        <link href="{{ asset('css/styleIndex.css') }}" rel="stylesheet">
        <link href="{{ asset('css/dark-mode.css') }}" rel="stylesheet">
        <link href='{{asset('fullcalendar-5.3.2/lib/main.css')}}' rel='stylesheet' />
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

        <style>
            .flexContainer { 
                display: flex; 
                flex-direction: column; 
            }

            .item {
                
                margin-bottom: 4px;
            }

            
        </style>
        
        <?php
            use App\Revisor;
            use App\User;
            use App\ComissaoEvento;
            use Illuminate\Support\Facades\Auth;
            use Illuminate\Http\Request;
        ?>
        
    </head>
    <body>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="token" content="{{ csrf_token() }}">
        <div id="app">
            <nav class="navbar navbar-expand-md navbar-dark shadow-sm">
                <div class="container">
                    <a class="navbar-brand" href="{{route('index')}}">
                        <img src="{{ asset('/img/logo.png') }}" alt="" style="height: 45px; width: 135px;">
                    </a>
                    <!-- <a id="change-mode" class="navbar-brand">
                        <img id="img-change-mode" src="{{asset('/img/icons/mom.png')}}" alt="" style="height: 40px; width: 45px;">
                    </a>
                    <a id="font-size-plus" class="navbar-brand tam-letra">
                        +A
                    </a>
                    <a id="font-size-min" class="navbar-brand tam-letra">
                        -A
                    </a> -->
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Alterna navegação">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse flex-grow-0"  id="navbarNavAltMarkup">
                        <div class="navbar-nav text-right">
                            <li class="nav-item">
                                @guest
                                    <a class="nav-link" href="{{ route('index') }}" style="margin-right: 5px; margin-left: 5px">
                                        Início 
                                    </a>
                                @else
                                    <a class="nav-link" href="{{ route('home') }}" style="margin-right: 5px; margin-left: 5px">
                                        Início
                                    </a>
                                @endguest
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
                                            {{ __('Área do Revisor') }}
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
                                            {{ __('Área do Membro da Comissão') }}
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
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Cadastre-se') }}</a>
                                </li>
                            @endauth
                        </div>
                    </div>
                </div>
            </nav>

            @hasSection ('sidebar')
                @yield('sidebar')
            @endif

            {{-- <main class="container-fluid"> --}}
            <div>
                @yield('content')
            </div>
            {{-- </main> --}}

        </div>
        @hasSection ('javascript')
            @yield('javascript')
        @else
        @endif
        {{-- <div id="div-input-change-mode" style="display: none;">
            <input id="input-change-mode" type="checkbox">
        </div> --}}
        <script>
            if (localStorage.getItem('dark-mode') == "active") {
                document.getElementById('img-change-mode').src = "{{asset('/img/icons/sun.png')}}"
                document.documentElement.classList.toggle('dark-mode')
            } 

            $(document).ready(function () {
                $('#change-mode').click(function () {
                    document.documentElement.classList.toggle('dark-mode')
                    if (document.getElementById('img-change-mode').src == "{{asset('/img/icons/mom.png')}}") {
                        document.getElementById('img-change-mode').src = "{{asset('/img/icons/sun.png')}}"
                        localStorage.setItem('dark-mode', "active");
                    } else {
                        document.getElementById('img-change-mode').src = "{{asset('/img/icons/mom.png')}}"
                        localStorage.setItem('dark-mode', "no-active");
                    }
                })

                $('#font-size-plus').click(function() {
                    var elemento = $(".acessibilidade");
                    var fonte = parseInt(elemento.css('font-size'));

                    var body = $("body");
                    const fonteNormal = parseInt(body.css('font-size'));
                    fonte++;

                    elemento.css("fontSize", fonte);
                })

                $('#font-size-min').click(function() {
                    var elemento = $(".acessibilidade");
                    var fonte = parseInt(elemento.css('font-size'));

                    var body = $("body");
                    const fonteNormal = parseInt(body.css('font-size'));
                    fonte--;

                    elemento.css("fontSize", fonte);
                })
            })
        </script> 
    </body>
</html>
