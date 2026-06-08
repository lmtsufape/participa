<footer class="w-100 shadow text-white pt-4" style="background-color: #034652">
    <div class="container mx-auto">
        <div class="d-flex flex-column flex-md-row flex-wrap justify-content-between text-center gap-5 my-3">
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-3">
                <a class="navbar-brand d-inline-block" href="https://ufape.edu.br/" target="_blank">
                    <img src="{{ asset('/img/logo_ufape.png') }}" alt="" style="width: 100px; max-width: 100%;">
                </a>

                <div class="text-center text-md-start small list-unstyled" style="max-width: 300px; font-size: 0.8rem">
                    <p class="m-0">&copy;{{ date('Y') }} | UFAPE - Universidade Federal do Agreste de Pernambuco</p>
                    <address class="m-0 not-italic">Av. Bom Pastor, s/n - Boa Vista - CEP 55292-270, Garanhuns - PE</address>
                </div>
            </div>

            <div class="d-flex flex-column flex-md-row">
                <div class="text-center">
                    <h6 class="fw-bold">{{ __('Desenvolvido por:') }}</h6>
                    <a href="http://www.lmts.ufape.edu.br/" target="_blank" name="lmts">
                        <img src="{{asset('img/lmts.png')}}" width="125px;" >
                    </a>
                </div>

            </div>

            <div class="d-flex flex-column gap-2 gap-md-5 gap-lg-2 flex-md-row">
                <div class="">
                    <h6 class="fw-bold">{{ __('Módulos') }}</h6>
                    <ul class="list-unstyled small">
                        <li><a href="{{ route('evento.visualizar', ['id' => 2]) }}" class="link-light link-underline link-underline-light link-underline-opacity-0 link-underline-opacity-100-hover">{{ __('Inscrições') }}</a></li>
                        <li><a href="{{ route('evento.visualizar', ['id' => 2]) }}" class="link-light link-underline link-underline-light link-underline-opacity-0 link-underline-opacity-100-hover">{{ __('Submissões') }}</a></li>
                        <li><a href="{{ route('meusCertificados') }}" class="link-light link-underline link-underline-light link-underline-opacity-0 link-underline-opacity-100-hover">{{ __('Certificados') }}</a></li>
                    </ul>
                </div>
                <div class="">
                    <h6 class="fw-bold">{{ __('Plataforma') }}</h6>
                    <ul class="list-unstyled small">
                        <li><a href="mailto:lmts@ufape.edu.br" target="_blank" class="link-light link-underline link-underline-light link-underline-opacity-0 link-underline-opacity-100-hover">{{ __('Ajuda') }}</a></li>
                        <li class=""><a href="{{ route('validarCertificado') }}" class="link-light link-underline link-underline-light link-underline-opacity-0 link-underline-opacity-100-hover d-inline-block lh-sm" style="max-width: 150px; font-size: 0.8rem">{{ __('Acessar / validar documentos ou certificados') }} </a></li>
                    </ul>
                </div>

                <div class="">
                    <h6 class="fw-bold mb-3">{{ __('Redes Sociais') }}</h6>
                    <div class="d-flex gap-3 justify-content-center align-items-center">
                        <!-- Email -->
                        <a href="mailto:lmts@ufape.edu.br" target="_blank" title="Email">
                            <img src="{{ asset('img/email-icon.png') }}" alt="Email" class="hover-scale" width="25px">
                        </a>
                        <!-- Facebook -->
                        <a href="https://www.facebook.com/LMTSUFAPE/" target="_blank" class="hover-scale" title="Facebook">
                            <img src="{{ asset('img/facebook-icon.png') }}" alt="Facebook" width="25px">
                        </a>
                        <!-- Instagram -->
                        <a href="https://www.instagram.com/lmts_ufape/" target="_blank" class="hover-scale" title="Instagram">
                            <img src="{{ asset('img/instagram-icon.png') }}" alt="Instagram" width="25px">
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </div>
</footer>