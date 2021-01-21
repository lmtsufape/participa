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
    <link href='{{asset('fullcalendar-5.3.2/lib/main.css')}}' rel='stylesheet' />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
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
        {{-- Navbar --}}
        <nav class="navbar navbar-expand-md navbar-dark shadow-sm">
            <div class="container">
                <div class="row">
                    <div class="col-sm-2">
                        <a class="navbar-brand" href="{{route('index')}}">
                            <img src="{{ asset('/img/logo.png') }}" alt="" style="height: 45px; width: 135px;">
                        </a>
                    </div>
                    <div class="navbar-center">
                        <a class="navbar-brand" href="#">
                            <img src="{{asset('/img/icons/sun.png')}}" alt="" style="height: 40px; width: 45px;">
                        </a>
                    </div>
                    <div class="navbar-center">
                        <a class="navbar-brand tam-letra" href="#">
                            +A
                        </a>
                    </div>
                    <div class="navbar-center">
                        <a class="navbar-brand tam-letra" href="#">
                            -A
                        </a>
                    </div>
                </div>


                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                @guest
                                    <a class="nav-link" href="{{ route('index') }}" >
                                        Início 
                                    </a>
                                @else
                                    <a class="nav-link" href="{{ route('home') }}" >
                                        Início
                                    </a>
                                @endguest
                        </div>
                        @guest
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Cadastre seu evento') }}</a>
                                    {{-- <a class="nav-link" data-toggle="modal" data-target="#modalCadastro">{{ __('Cadastro') }}</a> --}}
                                </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                {{-- <a class="nav-link" data-toggle="modal" data-target="#modalLogin">{{ __('Login') }}</a> --}}
                            </li>
                        @else

                                {{-- $ComissaoEvento = ComissaoEvento::where('user_id', Auth::user()->id)->first(); --}}
                            
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
                                    @if (isset(Auth::user()->revisor))
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

                                    @if (isset(Auth::user()->membroComissaoEvento))
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
</body>
</html>
