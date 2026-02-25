<footer class="w-100 bg-white shadow">
    <div class="pt-1 text-white" style="background-color: #3d93a9">
        <div class="container mx-auto">
            <div class="d-flex flex-wrap justify-content-between text-center p-2">
                <div class=" mb-md-0 d-flex flex-column flex-md-row align-items-center justify-content-center gap-3" style="flex: 0 0 auto; width: auto;">
                    <a href="http://ufape.edu.br/" target="_blank" style="display: inline-block; line-height: 0;">
                        <img src="{{asset('img/logo_ufape.png')}}" style="width: 100px;">
                    </a>
                    <div class="text-center text-md-start list-unstyled small mt-3 " style="font-size: 11px;">
                        <p class="m-0">©2025 | UFAPE - Universidade Federal do Agreste de Pernambuco</p>
                        <p class="m-0">Av. Bom Pastor, s/n - Boa Vista</p>
                        <p class="m-0">- CEP 55292-270, Garanhuns - PE</p>
                    </div>
                </div>

                <div class="d-flex flex-md-row align-items-center" style="gap: 20px; margin-right: 20px;">
                    <div class=" mt-2 mt-mb-md-0 text-center" style="flex: 0 0 auto; width: auto;">
                        <div class="text-center">
                            <h6 class="fw-bold">{{ __('Desenvolvido por:') }}</h6>
                        </div>
                        <a href="http://www.lmts.ufape.edu.br/" target="_blank" style="display: inline-block; line-height: 0;" name="lmts">
                            <img src="{{asset('img/lmts.png')}}" style="margin:20px 0 20px 0" width="200px;" >
                        </a>
                    </div>
                </div>

                <div class="d-flex flex-column flex-md-row align-items-start">
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
                            <li><a href="{{ route('validarCertificado') }}" class="text-white text-decoration-none">{{ __('Acessar / validar documentos') }} <br> {{ __('ou certificados') }}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
