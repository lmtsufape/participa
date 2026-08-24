<style>
    .profile-dropdown {
        width: min(420px, calc(100vw - 2rem));
        padding: .75rem;
        max-height: calc(100vh - 5rem);
        overflow-y: auto;
        border: 1px solid rgba(17, 64, 72, .12);
        border-radius: 8px;
        box-shadow: 0 18px 42px rgba(7, 27, 30, .18);
    }

    .profile-dropdown .dropdown-item {
        display: flex;
        align-items: center;
        gap: .75rem;
        border-radius: 8px;
        padding: .72rem .8rem;
        color: #071b1eff;
        font-weight: 600;
        white-space: normal;
        transition: background-color .16s ease, color .16s ease, transform .16s ease;
    }

    .profile-dropdown .dropdown-item:hover,
    .profile-dropdown .dropdown-item:focus {
        color: #114048ff;
        background: rgba(25, 101, 114, .08);
        transform: translateX(2px);
    }

    .profile-dropdown__icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: 8px;
        color: #fff;
        background: #114048ff;
        flex: 0 0 auto;
        font-size: 1rem;
    }

    .profile-dropdown__section {
        margin-top: .35rem;
        padding-top: .65rem;
        border-top: 1px solid rgba(17, 64, 72, .1);
    }

    .profile-dropdown__section:first-child {
        margin-top: 0;
        padding-top: 0;
        border-top: 0;
    }

    .profile-dropdown__label {
        display: flex;
        align-items: center;
        gap: .45rem;
        color: #196572ff;
        font-size: .78rem;
        font-weight: 800;
        letter-spacing: .04em;
        margin: 0 .25rem .35rem;
        text-transform: uppercase;
    }

    .profile-dropdown__list {
        display: grid;
        gap: .2rem;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .profile-dropdown__section--access .profile-dropdown__list {
        padding: .3rem;
        border-radius: 10px;
        background: rgba(25, 101, 114, .055);
    }

    .profile-dropdown__section--activity .profile-dropdown__icon {
        color: #114048ff;
        background: rgba(25, 101, 114, .12);
    }

    .profile-dropdown__logout {
        margin-top: .35rem;
        padding-top: .65rem;
        border-top: 1px solid rgba(17, 64, 72, .1);
    }

    .profile-dropdown__logout .profile-dropdown__icon {
        background: #071b1eff;
    }
</style>

<nav class="navbar navbar-expand-lg shadow-sm" style="background-color: #034652">
    @php
        $incompleto = optional(Auth::user())->usuarioTemp;
    @endphp
    <div class="container">
        @if($incompleto)
            <a class="navbar-brand" href="">
                <img src="{{ asset('/img/logo-sistema-letra-branca.png') }}" alt="" width="150vw">
            </a>
        @else
            <a class="navbar-brand" href="{{route('index')}}">
                <img src="{{ asset('/img/logo-sistema-letra-branca.png') }}" alt="" width="150vw">
            </a>
        @endif

        <button class="navbar-toggler border-white" type="button" data-bs-toggle="collapse" data-bs-theme="dark" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Alterna navegação">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse flex-grow-0"  id="navbarNavAltMarkup">
            <div class="navbar-nav text-right">
                @auth
                    @if($incompleto)
                        <li class="nav-item">
                            <a
                                class="nav-link text-white fw-semibold"
                                href="{{ route('logout') }}"
                                style="margin-right: 5px; margin-left: 5px"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            >
                                <img
                                    src="{{ asset('img/icons/sign-out-alt-solid.svg') }}"
                                    width="20px"
                                    alt=""
                                >
                                {{ __('Sair') }}
                            </a>
                        </li>

                        <form
                            id="logout-form"
                            action="{{ route('logout') }}"
                            method="POST"
                            style="display: none;"
                        >
                            @csrf
                        </form>
                    @else
                        <li class="nav-item">
                            <a class="nav-link text-white fw-semibold" href="{{ route('home') }}" style="margin-right: 5px; margin-left: 5px">
                                @lang('public.meusEventos')
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white fw-semibold" href="{{ route('meusCertificados') }}" style="margin-right: 5px; margin-left: 5px">
                                @lang('public.meusCertificados')
                            </a>
                        </li>


                        <li class="nav-item dropdown">
                            <a id="menuDropdown" class="nav-link dropdown-toggle text-white fw-semibold" href="#"  role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>
                            @php
                                $temComprovantes = Auth::user()->inscricaos()
                                    ->where('finalizada', true)
                                    ->exists();
                                $temTrabalhos = Auth::user()->trabalho()
                                    ->where('status', '!=', 'arquivado')
                                    ->exists() || Auth::user()->coautor()->exists();
                            @endphp
                            <ul class="dropdown-menu dropdown-menu-end profile-dropdown" aria-labelledby="menuDropdown">
                                <li class="profile-dropdown__section">
                                    <div class="profile-dropdown__label">
                                        <i class="bi bi-person-circle"></i>
                                        @lang('public.conta')
                                    </div>
                                    <ul class="profile-dropdown__list">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('perfil') }}">
                                                <span class="profile-dropdown__icon"><i class="bi bi-person"></i></span>
                                                <span>{{ __('Minha Conta') }}</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="profile-dropdown__section profile-dropdown__section--access">
                                    <div class="profile-dropdown__label">
                                        <i class="bi bi-grid-3x3-gap"></i>
                                        @lang('public.perfisAcesso')
                                    </div>
                                    {{-- Link Perfil --}}
                                    <ul class="profile-dropdown__list">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('participante') }}">
                                                <span class="profile-dropdown__icon"><i class="bi bi-person-badge"></i></span>
                                                {{ __('Área do Participante') }}
                                            </a>
                                        </li>
                                        @if (Auth::user()->revisor->count())
                                            {{-- Rota - Area de Revisores --}}
                                            <li>
                                                <a class="dropdown-item" href="{{ route('revisor.index') }}">
                                                    <span class="profile-dropdown__icon"><i class="bi bi-clipboard-check"></i></span>
                                                    {{ __('Área do Avaliador') }}
                                                </a>
                                            </li>
                                        @endif
                                        @if (isset(Auth::user()->administrador))
                                            {{-- Rota - Area da Comissao --}}
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.home') }}">
                                                    <span class="profile-dropdown__icon"><i class="bi bi-shield-check"></i></span>
                                                    {{ __('Área do Administrador') }}
                                                </a>
                                            </li>
                                        @endif
                                        @if (Auth::user()->coordComissaoCientifica->count() != 0 || isset(Auth::user()->administrador))
                                            {{-- Rota - Area da Comissao --}}
                                            <li>
                                                <a class="dropdown-item" href="{{ route('cientifica.home') }}">
                                                    <span class="profile-dropdown__icon"><i class="bi bi-people"></i></span>
                                                    {{ __('Área da Comissão Cientifica') }}
                                                </a>
                                            </li>
                                        @endif
                                        @if (Auth::user()->coordComissaoOrganizadora->count() != 0 || isset(Auth::user()->administrador))
                                            {{-- Rota - Area da Comissao --}}
                                            <li>
                                                <a class="dropdown-item" href="{{ route('home.organizadora') }}">
                                                    <span class="profile-dropdown__icon"><i class="bi bi-diagram-3"></i></span>
                                                    {{ __('Área da Comissão Organizadora') }}
                                                </a>
                                            </li>
                                        @endif
                                        @if (Auth::user()->membroComissaoEvento->count())
                                            {{-- Rota - Area da Comissao --}}
                                            <li>
                                                <a class="dropdown-item" href="{{ route('home.membro') }}">
                                                    <span class="profile-dropdown__icon"><i class="bi bi-person-workspace"></i></span>
                                                    {{ __('Área do Membro da Comissão Científica') }}
                                                </a>
                                            </li>
                                        @endif
                                        @if (Auth::user()->coordEixosTematicos()->exists())
                                            {{-- Rota - Área de coordenador de eixo temático --}}
                                            <li>
                                                <a class="dropdown-item" href="{{ route('coord.eixo.index') }}">
                                                    <span class="profile-dropdown__icon"><i class="bi bi-columns-gap"></i></span>
                                                    {{ __('Área do Coordenador de Eixo Temático') }}
                                                </a>
                                            </li>
                                        @endif
                                        @if (Auth::user()->outrasComissoes->count())
                                            {{-- Rota - Area da Comissao --}}
                                            <li>
                                                <a class="dropdown-item" href="{{ route('coord.membroOutrasComissoes') }}">
                                                    <span class="profile-dropdown__icon"><i class="bi bi-kanban"></i></span>
                                                    {{ __('Área do coordenador de outras comissões') }}
                                                </a>
                                            </li>
                                        @endif
                                        {{-- Rota - Area da Comissao --}}
                                        <li>
                                            <a class="dropdown-item" href="{{ route('coord.index') }}">
                                                <span class="profile-dropdown__icon"><i class="bi bi-calendar2-event"></i></span>
                                                {{ __('Área do Coordenador de Evento') }}
                                            </a>
                                        </li>
                                        @if ( isset(Auth::user()->coautor) && Auth::user()->coautor->count())
                                            {{-- Rota - Area do coautor--}}
                                            <li>
                                                <a class="dropdown-item" href="{{ route('coautor.listarTrabalhos') }}">
                                                    <span class="profile-dropdown__icon"><i class="bi bi-file-earmark-person"></i></span>
                                                    {{ __('Área de Coautor de Trabalho') }}
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </li>

                                @if($temComprovantes || $temTrabalhos)
                                    <li class="profile-dropdown__section profile-dropdown__section--activity">
                                        <div class="profile-dropdown__label">
                                            <i class="bi bi-folder2-open"></i>
                                            @lang('public.minhaAtividade')
                                        </div>
                                        <ul class="profile-dropdown__list">
                                            @if($temComprovantes)
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('comprovantes') }}">
                                                        <span class="profile-dropdown__icon"><i class="bi bi-receipt"></i></span>
                                                        {{ __('Meus Comprovantes') }}
                                                    </a>
                                                </li>
                                            @endif

                                            @if($temTrabalhos)
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('user.meusTrabalhos') }}">
                                                        <span class="profile-dropdown__icon"><i class="bi bi-file-earmark-text"></i></span>
                                                        {{ __('Trabalhos Submetidos') }}
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </li>
                                @endif

                                {{-- Link Logout --}}
                                <li class="profile-dropdown__logout">
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <span class="profile-dropdown__icon"><i class="bi bi-box-arrow-right"></i></span>
                                        {{ __('Sair') }}
                                    </a>
                                </li>
                            </ul>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    @endif


                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link text-white border-end border-1 px-4 py-0 my-2 fw-semibold" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link text-white border-end border-1 px-4 py-0 my-2 fw-semibold" href="{{ route('preRegistro') }}">{{ __('Cadastre-se') }}</a>
                    </li>
                @endauth



                <li class="nav-item dropdown">
                    <a id="navbarDropdown"
                    class="nav-link dropdown-toggle d-inline-flex align-items-center ms-3 gap-2 text-white fw-semibold"
                    href="#" role="button"
                    data-bs-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                    v-pre>

                        @if(Session::get('locale') === 'pt-BR' || Session::get('locale') === null)
                            <img src="https://flagicons.lipis.dev/flags/4x3/br.svg" alt="Português" style="width: 20px;">
                            Português
                        @elseif(Session::get('locale') === 'en')
                            <img src="https://flagicons.lipis.dev/flags/4x3/us.svg" alt="English" style="width: 20px;">
                            English
                        @elseif(Session::get('locale') === 'es')
                            <img src="https://flagicons.lipis.dev/flags/4x3/es.svg" alt="Español" style="width: 20px;">
                            Español
                        @endif
                    </a>

                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('alterar-idioma', ['lang' => 'en']) }}?url={{ urlencode(request()->fullUrl()) }}">
                            <img src="https://flagicons.lipis.dev/flags/4x3/us.svg" alt="English" style="width: 20px;"> English
                        </a>
                        <a class="dropdown-item" href="{{ route('alterar-idioma', ['lang' => 'pt-BR']) }}?url={{ urlencode(request()->fullUrl()) }}">
                            <img src="https://flagicons.lipis.dev/flags/4x3/br.svg" alt="Português" style="width: 20px;"> Português
                        </a>
                        <a class="dropdown-item" href="{{ route('alterar-idioma', ['lang' => 'es']) }}?url={{ urlencode(request()->fullUrl()) }}">
                            <img src="https://flagicons.lipis.dev/flags/4x3/es.svg" alt="Español" style="width: 20px;"> Español
                        </a>
                    </div>
                </li>

            </div>
        </div>
    </div>
</nav>



<script>
    function mudarIdioma(lang) {

        fetch(`/idioma/${lang}`, { method: 'GET' }) // Certifique-se de que esta rota está definida no seu Laravel
            .then(response => {
                if (response.ok) {
                    console.log(lang);
                    location.reload(); // Recarrega a página para aplicar o idioma
                } else {
                    alert('Falha ao mudar o idioma.');
                }
            })
            .catch(error => console.error('Erro ao mudar idioma:', error));
    }
</script>



