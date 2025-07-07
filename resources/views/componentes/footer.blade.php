<footer class="w-100 bg-white shadow">
    <div class="pt-1 text-white" style="background-color: #114048">
        <div class="container mx-auto">
            <div class="d-flex flex-wrap justify-content-between text-center p-2">
                <div class=" mb-md-0 d-flex flex-column flex-md-row align-items-center justify-content-center gap-3" style="flex: 0 0 auto; width: auto;">
                    <a class="navbar-brand d-inline-block" href="https://aba-agroecologia.org.br/" target="_blank">
                        <img src="{{ asset('/img/logoatualizadabranca.png') }}" alt="" style="width: 150px; max-width: 100%;">
                    </a>

                <!--<div class="text-center text-md-start list-unstyled small mt-3">
                        <p class="m-0">©2025 | ABA - Associação Brasileira de Agroecologia</p>
                        <p class="m-0">Rua das Palmeiras 90 - Bairro Botafogo</p>
                        <p class="m-0">- CEP 22270-070, Rio de Janeiro</p>
                    </div>-->
                </div>

                <div class="d-flex flex-md-row align-items-center" style="gap: 20px; margin-right: 20px;">
                    <div class=" mt-2 mt-mb-md-0 text-center" style="flex: 0 0 auto; width: auto;">
                        <div class="text-center">
                            <!--<h6 class="fw-bold">{{ __('Desenvolvido por:') }}</h6>-->
                        </div>
                        <a href="http://ufape.edu.br/" target="_blank" style="display: inline-block; line-height: 0;">
                            <img src="{{asset('img/logo_ufape.png')}}" style="width: 100px;">
                        </a>
                        <a href="http://www.lmts.ufape.edu.br/" target="_blank" style="display: inline-block; line-height: 0;" name="lmts">
                            <img src="{{asset('img/lmts.png')}}" style="margin:5px 0 5px 0" width="60px;" >
                        </a>
                    </div>
                </div>

                <!--<div class="d-flex flex-column flex-md-row align-items-start">
                    <div class=" mt-2 mt-mb-md-0" style="flex: 0 0 auto; width: auto; ">
                        <h6 class="fw-bold">{{ __('Módulos') }}</h6>
                        <ul class="list-unstyled small">
                            <li><a href="{{ route('evento.visualizar', ['id' => 2]) }}" class="text-white text-decoration-none">{{ __('Inscrições') }}</a></li>
                            <li><a href="{{ route('evento.visualizar', ['id' => 2]) }}" class="text-white text-decoration-none">{{ __('Submissões') }}</a></li>
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
                </div>-->

                <div class="d-flex flex-column flex-md-row align-items-center justify-content-center">
                    <div class="mt-2 text-center">
                        <h6 class="fw-bold mb-3">{{ __('Redes Sociais') }}</h6>
                        <div class="d-flex gap-3 justify-content-center align-items-center">
                            <!-- Email -->
                            <a href="mailto:contato@aba-agroecologia.org.br"
                            target="_blank"
                            title="Email">
                                <img src="{{ asset('img/email-icon.png') }}"
                                    alt="Email"
                                    style="width: 30px; height: 30px;">
                            </a>

                            <!-- Facebook -->
                            <a href="https://www.facebook.com/aba.agroecologia"
                            target="_blank"
                            title="Facebook">
                                <img src="{{ asset('img/facebook-icon.png') }}"
                                    alt="Facebook"
                                    style="width: 30px; height: 30px;">
                            </a>

                            <!-- Instagram -->
                            <a href="https://www.instagram.com/aba.agroecologia"
                            target="_blank"
                            title="Instagram">
                                <img src="{{ asset('img/instagram-icon.png') }}"
                                    alt="Instagram"
                                    style="width: 30px; height: 30px;">
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</footer>
