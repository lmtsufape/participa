<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <script src="{{ asset('js/jquery-3.4.1.min.js')}}"></script>
    <script src="{{ asset('js/jquery-mask-plugin.js')}}"></script>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/styleIndex.css') }}" rel="stylesheet">
    <?php
        use App\Revisor;
        use App\User;
        use App\ComissaoEvento;
        use Illuminate\Support\Facades\Auth;
        use Illuminate\Http\Request;
    ?>
    
</head>
<body>
    <div id="app">
        {{-- Navbar --}}
        <nav class="navbar navbar-expand-md navbar-dark  shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{route('cancelarCadastro')}}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                {{-- <a class="nav-link" data-toggle="modal" data-target="#modalLogin">{{ __('Login') }}</a> --}}
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Cadastro') }}</a>
                                    {{-- <a class="nav-link" data-toggle="modal" data-target="#modalCadastro">{{ __('Cadastro') }}</a> --}}
                                </li>
                            @endif
                        @else
                            <?php 

                                $revisor = Revisor::where("revisorId", Auth::user()->id)->first();
                                $ComissaoEvento = ComissaoEvento::where('userId', Auth::user()->id)->first();
                            
                            
                            ?>
                            <li class="nav-item dropdown" style="margin-right: 40px">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Perfis <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    {{-- Link Perfil --}}
                                    <a class="dropdown-item" href="{{ route('home') }}">
                                        <img src="{{asset('img/icons/perfil.svg')}}" alt="">
                                        {{ __('Área do Participante') }}
                                    </a>
                                    @if (isset($revisor))
                                        {{-- Rota - Area de Revisores --}}
                                        <a class="dropdown-item" href="{{ route('avaliar.trabalhos') }}">
                                            <img src="{{asset('img/icons/revisor.png')}}" alt="">
                                            {{ __('Área do Revisor') }}
                                        </a>
                                    @endif

                                    @if (isset($ComissaoEvento))
                                        {{-- Rota - Area da Comissao --}}
                                        <a class="dropdown-item" href="{{ route('home') }}">
                                            <img src="{{asset('img/icons/comissao.png')}}" alt="">
                                            {{ __('Área da Comissão') }}
                                        </a>
                                    @endif


                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
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
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <img src="{{asset('img/icons/sign-out-alt-solid.svg')}}" alt="">
                                        {{ __('Sair') }}
                                    </a>


                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        @hasSection ('sidebar')
            @yield('sidebar')
        @endif

        {{-- <main class="container-fluid"> --}}
        @yield('content')
        {{-- </main> --}}

    </div>
    @hasSection ('javascript')
    @yield('javascript')
    @else
    @endif
    <script>
        
    </script>
</body>
</html>
