<style>
    .cbee-header-top {
        background-color: #f2a440;
        width: 100%;
    }

    #navCbee {
        justify-content: center;
    }

    .navbar-nav {
        margin-bottom: 0;
    }

    .navbar-nav.ms-auto {
        margin-left: 0 !important;
    }

    .auth-buttons .btn-auth {
        color: white;
        font-weight: 700;
        font-size: 0.85rem;
        text-decoration: none;
        padding: 8px 16px;
        border-radius: 4px;
        transition: all 0.3s;
        text-transform: uppercase;
    }
    .auth-buttons .btn-login:hover {
        background: rgba(255, 255, 255, 0.2);
    }
    .auth-buttons .btn-register {
        border: 2px solid white;
        margin-left: 10px;
    }
    .auth-buttons .btn-register:hover {
        background: white;
        color: #f2a440 !important;
    }

    .cbee-nav-main {
        background-color: #3d93a9 !important;
        padding: 0;
    }
    .cbee-nav-main .nav-link {
        color: #ffffff !important;
        padding: 15px 20px !important;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    .cbee-nav-main .nav-item:hover > .nav-link {
        background-color: #52b2cc;
    }

    .dropdown-menu {
        background-color: #3d93a9;
        border: none;
        border-radius: 0;
        margin-top: 0;
    }
    .dropdown-item {
        color: white !important;
        text-transform: uppercase;
        font-size: 0.8rem;
        padding: 10px 20px;
        border-bottom: 1px solid #52b2cc;
    }
    .dropdown-item:hover {
        background-color: #52b2cc;
    }
</style>

<header>
    @php $incompleto = optional(Auth::user())->usuarioTemp; @endphp

    <div class="cbee-header-top">
        <div class="container-fluid p-0" style="display: flex; justify-content: center; align-items: center; overflow: hidden;">
            <a href="{{ $incompleto ? '#' : route('index') }}" style="width: 100%;">
                <img src="{{ asset('/img/cabecalhocbee.png') }}" alt="Logo" style="width: 100%; height: auto; display: block;">
            </a>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg cbee-nav-main">
        <div class="container">
            <button class="navbar-toggler border-white" type="button" data-bs-toggle="collapse" data-bs-target="#navCbee">
                <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-center" id="navCbee">
                <ul class="navbar-nav align-items-center">
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Sobre</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('sobre.cbee', ['locale' => app()->getLocale()]) }}">Sobre o CBEE</a></li>
                            <li><a class="dropdown-item" href="{{ route('sobre.sbee', ['locale' => app()->getLocale()]) }}">Sobre a SBEE</a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cronograma', ['locale' => app()->getLocale()]) }}">Cronograma</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Inscrições</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="https://cbee.etnobiologia.org/evento/1">Inscreva-se</a></li>
                            <li><a class="dropdown-item" href="{{ route('associe-se', ['locale' => app()->getLocale()]) }}">Associe-se</a></li>
                            <li><a class="dropdown-item" href="{{ route('normas', ['locale' => app()->getLocale()]) }}">Normas</a></li>
                            <li><a class="dropdown-item" href="{{ route('premiacoes', ['locale' => app()->getLocale()]) }}">Premiações</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Programação</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('programacao.geral', ['locale' => app()->getLocale()]) }}">Programação Geral</a></li>
                            <li><a class="dropdown-item" href="{{ route('programacao.feira', ['locale' => app()->getLocale()]) }}">Feira da Agrobiodiversidade</a></li>
                            <li><a class="dropdown-item" href="{{ route('programacao.mostra', ['locale' => app()->getLocale()]) }}">Mostra Audiovisual</a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('noticias', ['locale' => app()->getLocale()]) }}">Notícias</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('informacoes.uteis', ['locale' => app()->getLocale()]) }}">Informações Úteis</a>
                    </li>

                    @guest
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="nav-link">Entrar</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('preRegistro') }}" class="nav-link">Cadastre-se</a>
                        </li>
                    @endguest

                    @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-bold" href="#" id="userDrop" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="userDrop">
                            <li><a class="dropdown-item py-2" href="{{ route('perfil') }}"><i class="bi bi-person-fill me-2"></i>{{ __('Minha Conta') }}</a></li>
                            
                            @if (Auth::user()->trabalho()->where('status', '!=', 'arquivado')->exists() || Auth::user()->coautor()->exists())   
                                <li><a class="dropdown-item" href="{{ route('user.meusTrabalhos') }}"><i class="bi bi-file-earmark-text me-2"></i>{{ __('Trabalhos Submetidos') }}</a></li>
                            @endif

                            <li><a class="dropdown-item" href="{{ route('participante') }}"><i class="bi bi-person-fill me-2"></i>{{ __('Área do Participante') }}</a></li>
                            
                            @if (Auth::user()->revisor->count())
                                <li><a class="dropdown-item" href="{{ route('revisor.index') }}"><i class="bi bi-people-fill me-2"></i>{{ __('Área do Avaliador') }}</a></li>
                            @endif

                            @if (isset(Auth::user()->administradors))
                                <li><a class="dropdown-item" href="{{ route('admin.home') }}"><i class="bi bi-people-fill me-2"></i>{{ __('Área do Administrador') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('coord.index') }}"><i class="bi bi-people-fill me-2"></i>{{ __('Área do Coordenador de Evento') }}</a></li>
                            @endif

                            @if (Auth::user()->coordComissaoCientifica->count() != 0 || isset(Auth::user()->administradors))
                                <li><a class="dropdown-item" href="{{ route('cientifica.home') }}"><i class="bi bi-people-fill me-2"></i>{{ __('Área da Comissão Científica') }}</a></li>
                            @endif

                            @if (Auth::user()->coordComissaoOrganizadora->count() != 0 || isset(Auth::user()->administradors))
                                <li><a class="dropdown-item" href="{{ route('home.organizadora') }}"><i class="bi bi-people-fill me-2"></i>{{ __('Área da Comissão Organizadora') }}</a></li>
                            @endif

                            @if (Auth::user()->coordEixosTematicos()->exists())
                                <li><a class="dropdown-item" href="{{ route('coord.eixo.index') }}"><i class="bi bi-people-fill me-2"></i>{{ __('Área do Coordenador de Eixo') }}</a></li>
                            @endif

                            <li><hr class="dropdown-divider opacity-50"></li>
                            <li>
                                <a class="dropdown-item py-2 text-danger fw-bold" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right me-2"></i> Sair
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endauth

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <img src="https://flagicons.lipis.dev/flags/4x3/{{ Session::get('locale') == 'en' ? 'us' : (Session::get('locale') == 'es' ? 'es' : 'br') }}.svg" width="20">
                            {{ strtoupper(Session::get('locale') ?? 'PT') }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('alterar-idioma', ['lang' => 'pt-BR']) }}?url={{ urlencode(request()->fullUrl()) }}">Português</a></li>
                            <li><a class="dropdown-item" href="{{ route('alterar-idioma', ['lang' => 'en']) }}?url={{ urlencode(request()->fullUrl()) }}">English</a></li>
                            <li><a class="dropdown-item" href="{{ route('alterar-idioma', ['lang' => 'es']) }}?url={{ urlencode(request()->fullUrl()) }}">Español</a></li>
                        </ul>
                    </li>
                </ul>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            </div>
        </div>
    </nav>
</header>

<script>
    function mudarIdioma(lang) {
        fetch(`/idioma/${lang}`, { method: 'GET' })
            .then(response => {
                if (response.ok) { location.reload(); } 
                else { alert('Erro ao mudar idioma.'); }
            })
            .catch(error => console.error('Erro:', error));
    }
</script>