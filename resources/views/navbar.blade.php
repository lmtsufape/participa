<nav class="navbar navbar-expand-lg bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{route('index')}}">
            <img src="{{ asset('/img/logo.png') }}" alt="" width="150vw">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Alterna navegação">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse flex-grow-0"  id="navbarNavAltMarkup">
            <div class="navbar-nav text-right">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('validarCertificado') }}" style="margin-right: 5px; margin-left: 5px">
                    @lang('public.validarCertificado')
                    </a>
                </li>



                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}" style="margin-right: 5px; margin-left: 5px">
                            @lang('public.meusEventos')
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('meusCertificados') }}" style="margin-right: 5px; margin-left: 5px">
                            @lang('public.meusCertificados')
                        </a>
                    </li>


                    <li class="nav-item dropdown">
                        <a id="menuDropdown" class="nav-link dropdown-toggle" href="#"  role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
                                    @if (isset(Auth::user()->administradors))
                                        {{-- Rota - Area da Comissao --}}
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.home') }}">
                                                <img src="{{asset('img/icons/comissao.png')}}"  width="20px"  alt="">
                                                {{ __('Área do Administrador') }}
                                            </a>
                                        </li>
                                    @endif
                                    @if (Auth::user()->coordComissaoCientifica->count() != 0 || isset(Auth::user()->administradors))
                                        {{-- Rota - Area da Comissao --}}
                                        <li>
                                            <a class="dropdown-item" href="{{ route('cientifica.home') }}">
                                                <img src="{{asset('img/icons/comissao.png')}}"  width="20px"  alt="">
                                                {{ __('Área da Comissão Cientifica') }}
                                            </a>
                                        </li>
                                    @endif
                                    @if (Auth::user()->coordComissaoOrganizadora->count() != 0 || isset(Auth::user()->administradors))
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
                            <a class="dropdown-item" href="{{ route('user.meusTrabalhos') }}">
                                <img src="{{asset('img/icons/file-alt-regular-black.svg')}}"  width="20px"  alt="">
                                {{ __('Trabalhos Submetidos') }}
                            </a>

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
                    <span id="idiomaAtual">
                        @if(Session::get('locale') === 'pt-BR')
                            <img src="https://flagicons.lipis.dev/flags/4x3/br.svg" alt="Português" style="width: 20px;">Português
                        @elseif(Session::get('locale') === 'en')
                            <img src="https://flagicons.lipis.dev/flags/4x3/us.svg" alt="English" style="width: 20px;">English
                        @endif
                    </span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('alterar-idioma', ['lang' => 'en']) }}?url={{ urlencode(request()->fullUrl()) }}" >
                            <img src="https://flagicons.lipis.dev/flags/4x3/us.svg" alt="English" style="width: 20px;">English</a>

                        <a class="dropdown-item" href="{{ route('alterar-idioma', ['lang' => 'pt-BR']) }}?url={{ urlencode(request()->fullUrl()) }}" >
                            <img src="https://flagicons.lipis.dev/flags/4x3/br.svg" alt="Português" style="width: 20px;">Português</a>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


