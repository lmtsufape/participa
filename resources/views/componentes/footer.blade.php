<footer class="w-100 bg-white shadow">
    <div class="pt-1 text-white" style="background-color: #114048">
        <div class="container mx-auto">
            <div class="d-flex flex-wrap text-center p-2" style="gap: 10px;">
                <div class="mb-md-0 d-flex flex-column flex-md-row align-items-center justify-content-start gap-3" style="flex: 1; text-align: left;">
                    <!--<a class="navbar-brand d-inline-block" href="https://aba-agroecologia.org.br/" target="_blank">
                        <img src="{{ asset('/img/logo.png') }}" alt="" style="width: 200px; max-width: 100%;">
                    </a>-->
                    <a href="http://ufape.edu.br/" target="_blank" style="display: inline-block; line-height: 0;">
                            <img src="{{asset('img/logo_ufape.png')}}" style="width: 100px;">
                    </a>

                    <div class="text-center text-md-start list-unstyled small mt-3 " style="font-size: 11px;">
                        <p class="m-0">©2025 | UFAPE - Universidade Federal do Agreste de Pernambuco</p>
                        <p class="m-0">Av. Bom Pastor, s/n - Boa Vista</p>
                        <p class="m-0">- CEP 55292-270, Garanhuns - PE</p>
                    </div>
                </div>

                <div class="d-flex flex-md-row align-items-center justify-content-center" style="flex: 0.2;">
                    <div class=" mt-2 mt-mb-md-0 text-center" style="flex: 0 0 auto; width: auto;">
                        <div class="text-center">
                            <h6 class="fw-bold">{{ __('Desenvolvido por:') }}</h6>
                        </div>
                        <!--<a href="http://ufape.edu.br/" target="_blank" style="display: inline-block; line-height: 0;">
                            <img src="{{asset('img/logo_ufape.png')}}" style="width: 100px;">
                        </a>-->
                        <a href="http://www.lmts.ufape.edu.br/" target="_blank" style="display: inline-block; line-height: 0;" name="lmts">
                            <img src="{{asset('img/lmts.png')}}" style="margin:5px 0 5px 0" width="125px;" >
                        </a>
                    </div>
                </div>

                <div class="d-flex flex-column flex-md-row align-items-center justify-content-center" style="flex: 1;">
                    <div class=" mt-2 mt-mb-md-0" style="flex: 0 0 auto; width: auto; ">
                        <h6 class="fw-bold">{{ __('Módulos') }}</h6>
                        <ul class="list-unstyled small">
                            <li><a href="{{ route('home')}}" class="text-white text-decoration-none">{{ __('Inscrições') }}</a></li>
                            <li><a href="{{ route('participante')}}" class="text-white text-decoration-none">{{ __('Submissões') }}</a></li>
                            <li><a href="{{ route('meusCertificados') }}" class="text-white text-decoration-none">{{ __('Certificados') }}</a></li>
                        </ul>
                    </div>
                    <div class=" mt-2 mt-mb-md-0" style="flex: 0 0 auto; width: auto; margin-left: 90px;">
                        <h6 class="fw-bold">{{ __('Plataforma') }}</h6>
                        <ul class="list-unstyled small">
                            <li><a href="mailto:lmts@ufape.edu.br" target="_blank" class="text-white text-decoration-none">{{ __('Ajuda') }}</a></li>
                            <li><a href="{{ route('validarCertificado') }}" class="text-white text-decoration-none">{{ __('Validar Certificado') }}</a></li>
                        </ul>
                    </div>
                </div>

                <div class="d-flex flex-column flex-md-row align-items-center justify-content" style="flex: 0 0 auto; width: auto; margin-top: -15px; margin-left: -20px">
                    <div class="mt-2 mt-md-0 text-center" style="flex: 0 0 auto; width: auto;">
                        <h6 class="fw-bold mb-3">{{ __('Redes Sociais') }}</h6>
                        <div class="d-flex gap-3 justify-content-center align-items-center">
                            <!-- Email -->
                            <a href="mailto:lmts@ufape.edu.br"
                            target="_blank"
                            title="Email">
                                <img src="{{ asset('img/email-icon.png') }}"
                                    alt="Email"
                                    style="width: 25px; height: 25px;">
                            </a>

                            <!-- Facebook -->
                            <a href="https://www.facebook.com/LMTSUFAPE/"
                            target="_blank"
                            title="Facebook">
                                <img src="{{ asset('img/facebook-icon.png') }}"
                                    alt="Facebook"
                                    style="width: 25px; height: 25px;">
                            </a>

                            <!-- Instagram -->
                            <a href="https://www.instagram.com/lmts_ufape/"
                            target="_blank"
                            title="Instagram">
                                <img src="{{ asset('img/instagram-icon.png') }}"
                                    alt="Instagram"
                                    style="width: 25px; height: 25px;">
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</footer>
