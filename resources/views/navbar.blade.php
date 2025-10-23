<nav class="navbar navbar-expand-lg shadow-sm" style="background-color: #114048">
    @php
        $incompleto = optional(Auth::user())->usuarioTemp;
    @endphp
    <div class="container">
        @if($incompleto)
            <a class="navbar-brand" href="">
                <img src="{{ asset('/img/logo-novo.png') }}" alt="" width="210vw">
            </a>
        @else
            <a class="navbar-brand" href="{{route('index')}}">
                <img src="{{ asset('/img/logo-novo.png') }}" alt="" width="210vw">
            </a>
        @endif

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Alterna navegação">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse flex-grow-0"  id="navbarNavAltMarkup">
            <div class="navbar-nav text-right">


                @auth
                    @if($incompleto)
                        <li class="nav-item">
                            <a
                                class="nav-link text-my-primary fw-semibold"
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
                            <a class="nav-link fw-semibold" href="{{ route('home') }}" style="margin-right: 5px; margin-left: 5px; color: white;">
                                @lang('public.meusEventos')
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-semibold" href="{{ route('meusCertificados') }}" style="margin-right: 5px; margin-left: 5px; color: white;">
                                @lang('public.meusCertificados')
                            </a>
                        </li>


                        <li class="nav-item dropdown">
                            <a id="menuDropdown" class="nav-link dropdown-toggle fw-semibold" href="#"  role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: white;">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="menuDropdown">
                                {{-- Link Perfil --}}
                                <a class="dropdown-item" href="{{ route('perfil') }}">
                                    <img src="{{asset('img/icons/perfil.svg')}}" width="20px" alt="">
                                    {{ __('Minha Conta') }}
                                </a>


                                <li class="dropdown-item" >
                                    @lang('public.perfis')
                                    {{-- Link Perfil --}}
                                    <ul class="list-unstyled ms-3 mt-2">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('participante') }}">
                                                <img src="{{asset('img/icons/perfil.svg')}}"  width="20px"  alt="">
                                                {{ __('Área do Participante') }}
                                            </a>
                                        </li>
                                        @if (Auth::user()->revisor->count())
                                            {{-- Rota - Area de Revisores --}}
                                            <li>
                                                <a class="dropdown-item" href="{{ route('revisor.index') }}">
                                                    <img src="{{asset('img/icons/revisor.png')}}"  width="20px"  alt="">
                                                    {{ __('Área do Avaliador') }}
                                                </a>
                                            </li>
                                        @endif
                                        @if (isset(Auth::user()->administrador))
                                            {{-- Rota - Area da Comissao --}}
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.home') }}">
                                                    <img src="{{asset('img/icons/comissao.png')}}"  width="20px"  alt="">
                                                    {{ __('Área do Administrador') }}
                                                </a>
                                            </li>
                                        @endif
                                        @if (Auth::user()->coordComissaoCientifica->count() != 0 || isset(Auth::user()->administrador))
                                            {{-- Rota - Area da Comissao --}}
                                            <li>
                                                <a class="dropdown-item" href="{{ route('cientifica.home') }}">
                                                    <img src="{{asset('img/icons/comissao.png')}}"  width="20px"  alt="">
                                                    {{ __('Área da Comissão Cientifica') }}
                                                </a>
                                            </li>
                                        @endif
                                        @if (Auth::user()->coordComissaoOrganizadora->count() != 0 || isset(Auth::user()->administrador))
                                            {{-- Rota - Area da Comissao --}}
                                            <li>
                                                <a class="dropdown-item" href="{{ route('home.organizadora') }}">
                                                    <img src="{{asset('img/icons/comissao.png')}}"  width="20px"  alt="">
                                                    {{ __('Área da Comissão Organizadora') }}
                                                </a>
                                            </li>
                                        @endif
                                        @if (Auth::user()->membroComissaoEvento->count())
                                            {{-- Rota - Area da Comissao --}}
                                            <li>
                                                <a class="dropdown-item" href="{{ route('home.membro') }}">
                                                    <img src="{{asset('img/icons/comissao.png')}}"  width="20px"  alt="">
                                                    {{ __('Área do Membro da Comissão Científica') }}
                                                </a>
                                            </li>
                                        @endif
                                        @if (Auth::user()->coordEixosTematicos()->exists())
                                            {{-- Rota - Área de coordenador de eixo temático --}}
                                            <li>
                                                <a class="dropdown-item" href="{{ route('coord.eixo.index') }}">
                                                    <img src="{{asset('img/icons/comissao.png')}}"  width="20px"  alt="">
                                                    {{ __('Área do Coordenador de Eixo Temático') }}
                                                </a>
                                            </li>
                                        @endif
                                        @if (Auth::user()->outrasComissoes->count())
                                            {{-- Rota - Area da Comissao --}}
                                            <li>
                                                <a class="dropdown-item" href="{{ route('coord.membroOutrasComissoes') }}">
                                                    <img src="{{asset('img/icons/comissao.png')}}"  width="20px"  alt="">
                                                    {{ __('Área do coordenador de outras comissões') }}
                                                </a>
                                            </li>
                                        @endif
                                        {{-- Rota - Area da Comissao --}}
                                        <li>
                                            <a class="dropdown-item" href="{{ route('coord.index') }}">
                                                <img src="{{asset('img/icons/comissao.png')}}"  width="20px"  alt="">
                                                {{ __('Área do Coordenador de Evento') }}
                                            </a>
                                        </li>
                                        @if ( isset(Auth::user()->coautor) && Auth::user()->coautor->count())
                                            {{-- Rota - Area do coautor--}}
                                            <li>
                                                <a class="dropdown-item" href="{{ route('coautor.listarTrabalhos') }}">
                                                    <img src="{{asset('img/icons/comissao.png')}}"  width="20px"  alt="">
                                                    {{ __('Área de Coautor de Trabalho') }}
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </li>

                                {{-- Link Trabalhos --}}
                                @if (
                                    (Auth::user()->trabalho()->where('status', '!=', 'arquivado')->exists() ||
                                    Auth::user()->coautor()->exists())
                                )
                                    <a class="dropdown-item" href="{{ route('user.meusTrabalhos') }}">
                                        <img src="{{asset('img/icons/file-alt-regular-black.svg')}}"  width="20px"  alt="">
                                        {{ __('Trabalhos Submetidos') }}
                                    </a>
                                @endif

                                {{-- Link Logout --}}
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <img src="{{asset('img/icons/sign-out-alt-solid.svg')}}"  width="20px"  alt="">
                                    {{ __('Sair') }}
                                </a>


                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </ul>
                        </li>
                    @endif


                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link text-white fw-semibold" href="{{ route('login') }}" style="margin-right: 5px; margin-left: 5px">{{ __('Login') }}</a>
                    </li>
                    <li class="nav-item dropdown">
                        <div class="nav-link text-my-primary fw-semibold">
                            |
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link text-white fw-semibold" href="{{ route('preRegistro') }}">{{ __('Cadastre-se') }}</a>
                    </li>
                    <li class="nav-item dropdown">
                        <div class="nav-link text-my-primary fw-semibold">
                            |
                        </div>
                    </li>
                @endauth



                <li class="nav-item dropdown">
                    <a id="navbarDropdown"
                    class="nav-link dropdown-toggle d-inline-flex align-items-center gap-2 text-white fw-semibold"
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



