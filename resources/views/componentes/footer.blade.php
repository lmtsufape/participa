<footer class="w-100 bg-white shadow">
    <div class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-5 p-4">
        <div class="text-center">
            <a href="http://ufape.edu.br/">
                <img src="{{asset('img/logo-ufape-colorida.png')}}" style="width: 100px;">
            </a>
            <a href="http://www.lmts.ufape.edu.br/" name="lmts">
                <img src="{{asset('img/logo-lmts-colorida.png')}}" style="margin:20px 0 20px 0" width="200px;" >
            </a>
        </div>
        <div>
            <p class="m-0 text-secondary">
                {{ __('Desenvolvido por: LMTS | Laboratório Multidisciplinar de Tecnologias Sociais') }}
            </p>
            <address class="m-0 text-secondary">Avenida Bom Pastor, s/n.º - Bairro Boa Vista<br>CEP: 55292-270 - Garanhuns - PE</address>

        </div>
        <div class="d-flex justify-content-center gap-3">
            <a href="mailto:contato@lmts.ufape.edu.br" target="_blank">
                <svg width="40" height="35" viewBox="0 0 26 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2.6 21.5H23.4C24.83 21.5 26 20.3188 26 18.875V3.125C26 1.68125 24.83 0.5 23.4 0.5H2.6C1.17 0.5 0 1.68125 0 3.125V18.875C0 20.3188 1.17 21.5 2.6 21.5ZM23.4 3.125L13 9.67437L2.6 3.125H23.4ZM2.6 5.75L13 12.3125L23.4 5.75V18.875H2.6V5.75Z" fill="#034652"/>
                </svg>
            </a>
            <a href="https://www.instagram.com/lmts_ufape/" target="_blank">
                <svg width="35" height="35" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6.66667 0C2.99067 0 0 2.99067 0 6.66667V17.3333C0 21.0093 2.99067 24 6.66667 24H17.3333C21.0093 24 24 21.0093 24 17.3333V6.66667C24 2.99067 21.0093 0 17.3333 0H6.66667ZM6.66667 2.66667H17.3333C19.5387 2.66667 21.3333 4.46133 21.3333 6.66667V17.3333C21.3333 19.5387 19.5387 21.3333 17.3333 21.3333H6.66667C4.46133 21.3333 2.66667 19.5387 2.66667 17.3333V6.66667C2.66667 4.46133 4.46133 2.66667 6.66667 2.66667ZM18.6667 4C18.313 4 17.9739 4.14048 17.7239 4.39052C17.4738 4.64057 17.3333 4.97971 17.3333 5.33333C17.3333 5.68696 17.4738 6.02609 17.7239 6.27614C17.9739 6.52619 18.313 6.66667 18.6667 6.66667C19.0203 6.66667 19.3594 6.52619 19.6095 6.27614C19.8595 6.02609 20 5.68696 20 5.33333C20 4.97971 19.8595 4.64057 19.6095 4.39052C19.3594 4.14048 19.0203 4 18.6667 4ZM12 5.33333C8.324 5.33333 5.33333 8.324 5.33333 12C5.33333 15.676 8.324 18.6667 12 18.6667C15.676 18.6667 18.6667 15.676 18.6667 12C18.6667 8.324 15.676 5.33333 12 5.33333ZM12 8C14.2053 8 16 9.79467 16 12C16 14.2053 14.2053 16 12 16C9.79467 16 8 14.2053 8 12C8 9.79467 9.79467 8 12 8Z" fill="#034652"/>
                </svg>
            </a>

        </div>
    </div>

    <div class="p-4 text-white" style="background-color: #DA2E38">
        <div class="container mx-auto"> 
            <div class="d-flex flex-wrap justify-content-between text-center">  
                <div class="mb-3 mb-md-0 d-flex flex-column flex-md-row align-items-center gap-3" style="flex: 0 0 auto; width: auto;"> 
                    <a class="navbar-brand d-inline-block" href="{{route('index')}}">
                        <img src="{{ asset('/img/LOGO-RODAPE.png') }}" alt="" style="width: 150px; max-width: 100%;">
                    </a>

                    <div class="text-center text-md-start list-unstyled small mt-5"> 
                        <p class="m-0">@2025 | ABA - Associação Brasileira de Agroecologia</p>
                        <p class="m-0">Rua das Palmeiras 90 - Bairro Botafogo</p>
                        <p class="m-0">- CEP 22270-070, Rio de Janeiro</p>
                    </div>
                </div>
                
                <div class="d-flex flex-column flex-md-row align-items-start">
                    <div class="mb-3 mt-4 mt-mb-md-0" style="flex: 0 0 auto; width: auto;">
                    <h6 class="fw-bold">{{ __('Módulos') }}</h6>
                    <ul class="list-unstyled small">
                        <li><a href="{{ route('evento.visualizar', ['id' => 3]) }}" class="text-white text-decoration-none">{{ __('Inscrições') }}</a></li>
                        <li><a href="{{ route('evento.visualizar', ['id' => 3]) }}" class="text-white text-decoration-none">{{ __('Submissões') }}</a></li>
                        <li><a href="{{ route('evento.visualizar', ['id' => 3]) }}" class="text-white text-decoration-none">{{ __('Certificados') }}</a></li>
                    </ul>
                    </div>
                    <div class="mb-3 mt-4 mt-mb-md-0" style="flex: 0 0 auto; width: auto; margin-left: 90px;">
                        <h6 class="fw-bold">{{ __('Plataforma') }}</h6>
                        <ul class="list-unstyled small">
                            <li><a href="{{ route('evento.visualizar', ['id' => 3]) }}" class="text-white text-decoration-none">{{ __('Ajuda') }}</a></li>
                            <li><a href="{{ route('validarCertificado') }}" class="text-white text-decoration-none">{{ __('Validar Certificado') }}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
