<style>
    .cbee-header-top {
        background-color: #f2a440;
        padding: 15px 0;
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
        <div class="container d-flex justify-content-between align-items-center">
            <a href="{{ $incompleto ? '#' : route('index') }}">
                <img src="{{ asset('/img/logo_congresso_etno.png') }}" alt="Logo" style="max-height: 120px;">
            </a>

            <div class="d-flex align-items-center gap-3">
                @guest
                    <div class="auth-buttons d-none d-md-flex align-items-center">
                        <a href="{{ route('login') }}" class="btn-auth btn-login">Login</a>
                        <a href="{{ route('preRegistro') }}" class="btn-auth btn-register">Cadastre-se</a>
                    </div>
                @endguest

                <div class="dropdown">
                    <a class="nav-link dropdown-toggle text-white fw-bold" href="#" role="button" data-bs-toggle="dropdown">
                        <img src="https://flagicons.lipis.dev/flags/4x3/{{ Session::get('locale') == 'en' ? 'us' : (Session::get('locale') == 'es' ? 'es' : 'br') }}.svg" width="20">
                        {{ strtoupper(Session::get('locale') ?? 'PT') }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('alterar-idioma', ['lang' => 'pt-BR']) }}?url={{ urlencode(request()->fullUrl()) }}">Português</a></li>
                        <li><a class="dropdown-item" href="{{ route('alterar-idioma', ['lang' => 'en']) }}?url={{ urlencode(request()->fullUrl()) }}">English</a></li>
                        <li><a class="dropdown-item" href="{{ route('alterar-idioma', ['lang' => 'es']) }}?url={{ urlencode(request()->fullUrl()) }}">Español</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg cbee-nav-main">
        <div class="container">
            <button class="navbar-toggler border-white" type="button" data-bs-toggle="collapse" data-bs-target="#navCbee">
                <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
            </button>

            <div class="collapse navbar-collapse" id="navCbee">
                <ul class="navbar-nav me-auto">
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Sobre</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Sobre o CBEE</a></li>
                            <li><a class="dropdown-item" href="#">Sobre a SBEE</a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">Cronograma</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Inscrições</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Inscreva-se</a></li>
                            <li><a class="dropdown-item" href="#">Normas</a></li>
                            <li><a class="dropdown-item" href="#">Premiações</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Programação</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Programação Geral</a></li>
                            <li><a class="dropdown-item" href="#">Feira da Agrobiodiversidade</a></li>
                            <li><a class="dropdown-item" href="#">Mostra Audiovisual</a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">Associe-se</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">Informações Úteis</a>
                    </li>
                </ul>

                @auth
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-bold" href="#" id="userDrop" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="userDrop">
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('perfil') }}">
                                    <i class="bi bi-person-fill me-2"></i>{{ __('Minha Conta') }}
                                </a>
                            </li>
                            
                            @if (
                                (Auth::user()->trabalho()->where('status', '!=', 'arquivado')->exists() ||
                                Auth::user()->coautor()->exists())
                            )   
                                <li>
                                    <a class="dropdown-item" href="{{ route('user.meusTrabalhos') }}">
                                        <i class="bi bi-file-earmark-text me-2"></i>{{ __('Trabalhos Submetidos') }}
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a class="dropdown-item" href="{{ route('participante') }}">
                                    <i class="bi bi-person-fill me-2"></i>{{ __('Área do Participante') }}
                                </a>
                            </li>
                            @if (Auth::user()->revisor->count())
                                {{-- Rota - Area de Revisores --}}
                                <li>
                                    <a class="dropdown-item" href="{{ route('revisor.index') }}">
                                        <i class="bi bi-people-fill me-2"></i>{{ __('Área do Avaliador') }}
                                    </a>
                                </li>
                            @endif
                            @if (isset(Auth::user()->administradors))
                                {{-- Rota - Area da Comissao --}}
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.home') }}">
                                        <i class="bi bi-people-fill me-2"></i>{{ __('Área do Administrador') }}
                                    </a>
                                </li>
                            @endif
                            @if (Auth::user()->coordComissaoCientifica->count() != 0 || isset(Auth::user()->administradors))
                                {{-- Rota - Area da Comissao --}}
                                <li>
                                    <a class="dropdown-item" href="{{ route('cientifica.home') }}">
                                        <i class="bi bi-people-fill me-2"></i>{{ __('Área da Comissão Cientifica') }}
                                    </a>
                                </li>
                            @endif
                            @if (Auth::user()->coordComissaoOrganizadora->count() != 0 || isset(Auth::user()->administradors))
                                {{-- Rota - Area da Comissao --}}
                                <li>
                                    <a class="dropdown-item" href="{{ route('home.organizadora') }}">
                                        <i class="bi bi-people-fill me-2"></i>{{ __('Área da Comissão Organizadora') }}
                                    </a>
                                </li>
                            @endif
                            @if (Auth::user()->membroComissaoEvento->count())
                                {{-- Rota - Area da Comissao --}}
                                <li>
                                    <a class="dropdown-item" href="{{ route('home.membro') }}">
                                        <i class="bi bi-people-fill me-2"></i>{{ __('Área do Membro da Comissão Científica') }}
                                    </a>
                                </li>
                            @endif
                            @if (Auth::user()->coordEixosTematicos()->exists())
                                {{-- Rota - Área de coordenador de eixo temático --}}
                                <li>
                                    <a class="dropdown-item" href="{{ route('coord.eixo.index') }}">
                                        <i class="bi bi-people-fill me-2"></i>{{ __('Área do Coordenador de Eixo Temático') }}
                                    </a>
                                </li>
                            @endif
                            @if (Auth::user()->outrasComissoes->count())
                                {{-- Rota - Area da Comissao --}}
                                <li>
                                    <a class="dropdown-item" href="{{ route('coord.membroOutrasComissoes') }}">
                                        <i class="bi bi-people-fill me-2"></i>{{ __('Área do coordenador de outras comissões') }}
                                    </a>
                                </li>
                            @endif
                            {{-- Rota - Area da Comissao --}}
                            <li>
                                <a class="dropdown-item" href="{{ route('coord.index') }}">
                                    <i class="bi bi-people-fill me-2"></i>{{ __('Área do Coordenador de Evento') }}
                                </a>
                            </li>
                            @if ( isset(Auth::user()->coautor) && Auth::user()->coautor->count())
                                {{-- Rota - Area do coautor--}}
                                <li>
                                    <a class="dropdown-item" href="{{ route('coautor.listarTrabalhos') }}">
                                        <i class="bi bi-people-fill me-2"></i>{{ __('Área de Coautor de Trabalho') }}
                                    </a>
                                </li>
                            @endif
                            <li><hr class="dropdown-divider opacity-50"></li>
                            
                            <li>
                                <a class="dropdown-item py-2 text-danger fw-bold" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right me-2"></i> Sair
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                @endauth
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