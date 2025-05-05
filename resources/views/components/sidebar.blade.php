<aside class="p-3 border-end vh-100 overflow-auto w-100" style="background-color: #white; color: black;">
    <div>
        <h2 class="py-2">
            @if ($evento->is_multilingual && Session::get('idiomaAtual') === 'en')
                {{ $evento->nome_en }}
            @else
                {{ $evento->nome }}
            @endif
        </h2>
        <div class="d-flex flex-column gap-3">
            @can('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento)
                <a href="{{ route('evento.editar', $evento->id) }}" class="edit-evento"
                    onmouseover="this.children[0].src='{{ asset('img/icons/edit-regular.svg') }}'"
                    onmouseout="this.children[0].src='{{ asset('img/icons/edit-regular-white.svg') }}'"><img
                        src="{{ asset('img/icons/edit-regular-white.svg') }}" alt="" width="20px;">Atualizar
                    Evento</a>
                @if ($evento->eventoPai == null)
                    <a href="{{ route('subevento.criar', $evento->id) }}" class=""
                        onmouseover="this.children[0].src='{{ asset('img/icons/plus-square-solid_black.svg') }}'"
                        onmouseout="this.children[0].src='{{ asset('img/icons/plus-square-solid.svg') }}'"><img
                            src="{{ asset('img/icons/plus-square-solid.svg') }}" alt="" width="20px;">Criar
                        Subevento</a>
                @endif
            @endcan
        </div>
    </div>
    <ul class="nav nav-pills flex-column">
        @can('isCoordenadorOrCoordenadorDasComissoes', $evento)
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center" id="informacoes"
                    href="{{ route('coord.informacoes', ['eventoId' => $evento->id]) }}">
                    <img src="{{ asset('img/icons/info-circle-solid.svg') }}" width="20px" alt="">
                    <h6>Informações</h6>
                </a>
            </li>
            @can('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento)
                <li id="programacao" class="nav-item">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" data-bs-toggle="collapse"
                        href="#collapseProgramacao" role="button" aria-expanded="false"
                        aria-controls="collapseProgramacao">
                        <img src="{{ asset('img/icons/slideshow.svg') }}" width="20px" alt="">
                        <h6>Programação</h6>
                    </a>
                    <ul class="collapse ps-4 list-unstyled" id="collapseProgramacao"
                        @if (request()->is('coord/evento/atividade*') ||
                                request()->routeIs('coord.arquivos-adicionais') ||
                                request()->is('coord/evento/palestrantes*')) style='background-color: gray;display: block;' @else style='background-color: gray' @endif>
                        <li>
                            <a class="nav-link" id="cadastrarModalidade"
                                href="{{ route('coord.atividades', ['id' => $evento->id]) }}">
                                <img src="{{ asset('img/icons/plus-square-solid.svg') }}" width="20px" alt="">
                                <h6> Atividades</h6>
                            </a>
                        </li>

                        <li>
                            <a class="nav-link" id="cadastrarModalidade"
                                href="{{ route('checkout.pagamentos', ['id' => $evento->id]) }}">
                                <img src="{{ asset('img/icons/edit-regular-white.svg') }}" width="20px" alt="">
                                <h6>Pagamentos</h6>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" id="pdfadicional" href="{{ route('coord.arquivos-adicionais', $evento) }}">
                                <img src="{{ asset('img/icons/file-alt-regular.svg') }}" width="20px" alt="">
                                <h6>Arquivos adicionais</h6>
                            </a>
                        </li>

                        <li>
                            <a id="palestrantes" class="nav-link dropdown-toggle d-flex align-items-center"
                                data-bs-toggle="collapse" href="#dropdownPalestrantes" aria-controls="dropdownPalestrantes"
                                role="button" aria-expanded="false">
                                <img src="{{ asset('img/icons/user-tie-solid.svg') }}" width="20px" alt="">
                                <h6>Palestrantes</h6>
                            </a>
                            <ul id="dropdownPalestrantes" class="collapse flex-column mt-2 list-unstyled"
                                style='background-color: #545b62'>
                                <li>
                                    <a id="cadastrarPalestrante" class="nav-link d-flex align-items-center"
                                        href="{{ route('coord.palestrantes.create', ['eventoId' => $evento->id]) }}"
                                        style="background-color: #545b62">
                                        <img src="{{ asset('img/icons/user-plus-solid.svg') }}" width="20px"
                                            alt="">
                                        <h6> Cadastrar palestra</h6>
                                    </a>
                                </li>
                                <li>
                                    <a id="listarPalestrantes" class="nav-link d-flex align-items-center"
                                        href="{{ route('coord.palestrantes.index', ['eventoId' => $evento->id]) }}"
                                        style="background-color: #545b62">
                                        <img src="{{ asset('img/icons/list.svg') }}" width="20px" alt="">
                                        <h6>Listar palestras</h6>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
            @endcan
            <li id="areas" class="nav-item">
                <a class="nav-link dropdown-toggle d-flex align-items-center" data-bs-toggle="collapse"
                    href="#collapseAreas" role="button" aria-expanded="false" aria-controls="collapseAreas">
                    <img src="{{ asset('img/icons/area.svg') }}" alt="" width="20px">
                    <h6>Áreas</h6>
                </a>
                <ul class="collapse ps-4 list-unstyled" id="collapseAreas"
                    @if (request()->is('coord/evento/areas*')) style='background-color: gray;display: block;' @else style='background-color: gray' @endif>
                    <li>
                        <a class="nav-link" id="listarAreas"
                            href="{{ route('coord.listarAreas', ['eventoId' => $evento->id]) }}">
                            <img src="{{ asset('img/icons/list.svg') }}" width="20px" alt="">
                            <h6> Listar Áreas</h6>
                        </a>
                    </li>
                </ul>
            </li>
            <li id="modalidades" class="nav-item">
                <a class="nav-link dropdown-toggle d-flex align-items-center" data-bs-toggle="collapse"
                    href="#collapseModalidades" role="button" aria-expanded="false"
                    aria-controls="collapseModalidades">
                    <img src="{{ asset('img/icons/sitemap-solid.svg') }}" alt="" width="20px">
                    <h6>Modalidades</h6>
                </a>
                <ul class="collapse ps-4 list-unstyled" id="collapseModalidades"
                    @if (request()->is('coord/evento/modalidade*')) style='background-color: gray;display: block;' @else style='background-color: gray' @endif>
                    <li>
                        <a class="nav-link" id="cadastrarModalidade"
                            href="{{ route('coord.cadastrarModalidade', ['eventoId' => $evento->id]) }}">
                            <img src="{{ asset('img/icons/plus-square-solid.svg') }}" alt="" width="20px">
                            <h6> Cadastrar Modalidade</h6>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link" id="listarModalidade"
                            href="{{ route('coord.listarModalidade', ['eventoId' => $evento->id]) }}">
                            <img src="{{ asset('img/icons/list.svg') }}" alt="" width="20px">
                            <h6> Listar Modalidades</h6>
                        </a>
                    </li>
                    @can('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento)
                        <li>
                            <a class="nav-link" id="cadastrarCriterio"
                                href="{{ route('coord.cadastrarCriterio', ['eventoId' => $evento->id]) }}">
                                <img src="{{ asset('img/icons/plus-square-solid.svg') }}" alt="" width="20px">
                                <h6> Cadastrar Critérios</h6>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" id="listarCriterios"
                                href="{{ route('coord.listarCriterios', ['eventoId' => $evento->id]) }}">
                                <img src="{{ asset('img/icons/list.svg') }}" alt="" width="20px">
                                <h6> Listar Criterios</h6>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" id="forms" href="{{ route('coord.forms', ['eventoId' => $evento->id]) }}">
                                <img src="{{ asset('img/icons/plus-square-solid.svg') }}" alt="" width="20px">
                                <h6> Formulário</h6>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
            <li id="comissao" class="nav-item">
                <a class="nav-link dropdown-toggle d-flex align-items-center" data-bs-toggle="collapse"
                    href="#collapseComissao" role="button" aria-expanded="false" aria-controls="collapseComissao">
                    <img src="{{ asset('img/icons/user-tie-solid.svg') }}" alt="" width="20px">
                    <h6>Comissão Científica</h6>
                </a>
                <ul class="collapse ps-4 list-unstyled" id="collapseComissao"
                    @if (request()->is('coord/evento/comissaoCientifica*')) style='background-color: gray;display: block;' @else style='background-color: gray' @endif>
                    <li>
                        <a class="nav-link" id="cadastrarComissao"
                            href="{{ route('coord.cadastrarComissao', ['eventoId' => $evento->id]) }}">
                            <img src="{{ asset('img/icons/user-plus-solid.svg') }}" alt="" width="20px">
                            <h6> Cadastrar membro</h6>
                        </a>
                    </li>
                    @can('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento)
                        <li>
                            <a class="nav-link" id="definirCoordComissao"
                                href="{{ route('coord.definirCoordComissao', ['eventoId' => $evento->id]) }}">
                                <img src="{{ asset('img/icons/crown-solid.svg') }}" alt="" width="20px">
                                <h6> Definir Coordenador</h6>
                            </a>
                        </li>
                    @endcan
                    <li>
                        <a class="nav-link" id="listarComissao" href="{{ route('coord.listarComissao', ['eventoId' => $evento->id]) }}">
                            <img src="{{ asset('img/icons/list.svg') }}" alt="" width="20px">
                            <h6> Listar Comissão</h6>
                        </a>
                    </li>
                </ul>
            </li>
            <li id="comissaoOrganizadora" class="nav-item">
                <a class="nav-link dropdown-toggle d-flex align-items-center" data-bs-toggle="collapse"
                    href="#collapseComissaoOrganizadora" role="button" aria-expanded="false"
                    aria-controls="collapseComissaoOrganizadora">
                    <img src="{{ asset('img/icons/user-tie-solid.svg') }}" alt="" width="20px">
                    <h6>Comissão Organizadora</h6>
                </a>
                <ul class="collapse ps-4 list-unstyled" id="collapseComissaoOrganizadora"
                    @if (request()->is('comissaoOrganizadora*')) style='background-color: gray;display: block;' @else style='background-color: gray' @endif>
                    @can('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento)
                        <li>
                            <a class="nav-link" id="cadastrarComissaoOrganizadora" href="{{ route('coord.comissao.organizadora.create', ['id' => $evento->id]) }}">
                                <img src="{{ asset('img/icons/user-plus-solid.svg') }}" alt="" width="20px">
                                <h6> Cadastrar membro</h6>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" id="definirCoordComissaoOrganizadora" href="{{ route('coord.definir.coordComissaoOrganizadora', ['id' => $evento]) }}">
                                <img src="{{ asset('img/icons/crown-solid.svg') }}" alt="" width="20px">
                                <h6> Definir Coordenador</h6>
                            </a>
                        </li>
                    @endcan
                    <li>
                        <a class="nav-link" id="listarComissaoOrganizadora"
                            href="{{ route('coord.listar.comissaoOrganizadora', ['id' => $evento]) }}">
                            <img src="{{ asset('img/icons/list.svg') }}" alt="" width="20px">
                            <h6> Listar Comissão</h6>
                        </a>
                    </li>
                </ul>
            </li>
            <li id="outrasComissoes" class="nav-item">
                <a class="nav-link dropdown-toggle d-flex align-items-center" data-bs-toggle="collapse"
                    href="#collapseOutrasComissoes" role="button" aria-expanded="false"
                    aria-controls="collapseOutrasComissoes">
                    <img src="{{ asset('img/icons/user-tie-solid.svg') }}" alt="" width="20px">
                    <h6>Outras comissões</h6>
                </a>
                <ul class="collapse ps-4 list-unstyled {{ request()->is('coord/evento/*/tipocomissao*') ? 'show' : '' }}"
                    id="collapseOutrasComissoes" style="background-color: gray;">
                    <li>
                        <a class="nav-link" id="cadastrarOutraComissao" href="{{ route('coord.tipocomissao.create', $evento) }}">
                            <img src="{{ asset('img/icons/user-plus-solid.svg') }}" alt="" width="20px">
                            <h6 style="font-size: 11px;">Cadastrar comissão</h6>
                        </a>
                    </li>
                    @foreach ($evento->outrasComissoes as $comissao)
                        <li>
                            <a class="nav-link" href="{{ route('coord.tipocomissao.show', ['evento' => $evento, 'comissao' => $comissao]) }}">
                                <img src="{{ asset('img/icons/list.svg') }}" alt="" width="20px">
                                <h6 style="font-size: 11px;"> {{ $comissao->nome }} </h6>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
            @can('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento)
                <li id="revisores" class="nav-item">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" data-bs-toggle="collapse"
                        href="#CollapseRevisores" role="button" aria-expanded="false"
                        aria-controls="CollapseRevisores">
                        <img src="{{ asset('img/icons/glasses-solid.svg') }}" alt="" width="20px">
                        <h6>Avaliadores</h6>
                    </a>
                    <ul class="collapse ps-4 list-unstyled" id="CollapseRevisores"
                        @if (request()->is('coord/evento/revisores*')) style='background-color: gray;display: block;' @else style='background-color: gray' @endif>
                        <li>
                            <a id="listarRevisores"
                                href="{{ route('coord.listarRevisores', ['eventoId' => $evento->id]) }}">
                                <img src="{{ asset('img/icons/list.svg') }}" alt="" width="20px">
                                <h6> Listar Avaliadores</h6>
                            </a>
                        </li>
                    </ul>
                </li>
            @endcan
            @can('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento)
                <li id="inscricoes" class="nav-item">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" data-bs-toggle="collapse"
                        href="#CollapseInscricoes" role="button" aria-expanded="false"
                        aria-controls="CollapseInscricoes">
                        <img src="{{ asset('img/icons/edit-regular-white.svg') }}" alt="" width="20px">
                        <h6>Inscrições</h6>
                    </a>
                    <ul class="collapse ps-4 list-unstyled" id="CollapseInscricoes"
                        @if (request()->is($evento->id . '/inscricoes*')) style='background-color: gray;display: block;' @else style='background-color: gray' @endif>
                        <li>
                            <a href="{{ route('inscricao.categorias', $evento) }}">
                                <img src="{{ asset('img/icons/list.svg') }}" alt="" width="20px">
                                <h6>Categorias</h6>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('inscricao.formulario', $evento) }}">
                                <img src="{{ asset('img/icons/list.svg') }}" alt="" width="20px">
                                <h6>Formulário</h6>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('inscricao.inscritos', $evento) }}">
                                <img src="{{ asset('img/icons/list.svg') }}" alt="" width="20px">
                                <h6>Inscritos</h6>
                            </a>
                        </li>
                    </ul>
                </li>
            @endcan
            @can('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento)
                <li id="trabalhos" class="nav-item">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" data-bs-toggle="collapse"
                        href="#collapseTrabalhos" role="button"
                        aria-expanded="false" aria-controls="collapseTrabalhos">
                        <img src="{{ asset('img/icons/file-alt-regular.svg') }}" alt="" width="20px">
                        <h6>Submissões</h6>
                    </a>

                    <ul class="collapse ps-4 list-unstyled" id="collapseTrabalhos"
                        @if (request()->is('coord/evento/trabalhos*')) style='background-color: gray;display: block;' @else style='background-color: gray' @endif>
                        <li>
                            <a id="submissoesTrabalhos" href="{{ route('coord.definirSubmissoes', ['eventoId' => $evento->id]) }}">
                                <img src="{{ asset('img/icons/plus-square-solid.svg') }}" alt="" width="20px">
                                <h6>Definir Submissões</h6>
                            </a>
                        </li>
                <a id="submeterTrabalho">
                    <li>
                        <img src="{{ asset('img/icons/plus-square-solid.svg') }}" alt="" width="20px">
                        <h6>Submeter Trabalho</h6>
                    </li>
                    <div id="dropdownSubmeterTrabalho"
                        @if (request()->is('coord/evento/submeterTrabalho*')) style='background-color: #545b62;display: block;' @else style='background-color: #545b62' @endif>
                        @foreach ($evento->modalidades()->get() as $modalidade)
                            <a id="submeterTrabalho{{ $modalidade->id }}"
                                href="{{ route('trabalho.index', ['id' => $evento->id, 'idModalidade' => $modalidade->id]) }}">
                                <li>
                                    <h6>{{ $modalidade->nome }}</h6>
                                </li>
                            </a>
                        @endforeach
                    </div>
                </a>
                <li>
                    <a id="resultadosTrabalhos" href="{{ route('coord.resultados', ['id' => $evento->id]) }}">
                        <img src="{{ asset('img/icons/plus-square-solid.svg') }}" alt="" width="20px">
                        <h6>Resultado</h6>
                    </a>
                </li>
                <a id="listarTrabalhos">
                    <li>
                        <img src="{{ asset('img/icons/list.svg') }}" alt="" width="20px">
                        <h6>Listar Trabalhos</h6>
                    </li>
                    <div id="dropdownListarTrabalhos" style='background-color: #545b62'>
                        <a href="{{ route('coord.listarTrabalhos', ['eventoId' => $evento->id, 'titulo', 'asc', 'rascunho']) }}">
                            <li>
                                <h6>Todos os Trabalhos</h6>
                            </li>
                        </a>
                        @foreach ($evento->modalidades()->get() as $modalidade)
                            <a id="listarTrabalhos{{ $modalidade->id }}"
                                href="{{ route('coord.listarTrabalhosModalidades', ['eventoId' => $evento->id, 'modalidadeId' => $modalidade->id, 'titulo', 'asc', 'rascunho']) }}">
                                <li>
                                    <h6>{{ $modalidade->nome }}</h6>
                                </li>
                            </a>
                        @endforeach
                    </div>
                </a>
                <a id="listarAvaliacoes">
                    <li>
                        <img src="{{ asset('img/icons/list.svg') }}" alt="" width="20px">
                        <h6>Listar Avaliações</h6>
                    </li>
                    <div id="dropdownListarAvaliacoes" style='background-color: #545b62'>
                        <a href="{{ route('coord.listarAvaliacoes', ['eventoId' => $evento->id, 'titulo', 'asc', 'rascunho']) }}">
                            <li>
                                <h6>Todas as Avaliações</h6>
                            </li>
                        </a>
                        @foreach ($evento->modalidades()->get() as $modalidade)
                            <a id="listarAvaliacoesModalidade{{ $modalidade->id }}"
                                href="{{ route('coord.respostasTrabalhos', ['eventoId' => $evento->id, 'modalidadeId' => $modalidade->id]) }}">
                                <li>
                                    <h6>{{ $modalidade->nome }}</h6>
                                </li>
                            </a>
                        @endforeach
                    </div>
                </a>
                <a id="correcoesTrabalhos"
                    href="{{ route('coord.listarCorrecoes', ['eventoId' => $evento->id, 'titulo', 'asc']) }}">
                    <li>
                        <img src="{{ asset('img/icons/list.svg') }}" alt="" width="20px">
                        <h6>Listar Correções</h6>
                    </li>
                </a>

                </ul>
                </li>
            @endcan
            @can('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento)
                <li id="certificados" class="nav-item">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" data-bs-toggle="collapse"
                        href="#CollapseCertificados" role="button" aria-expanded="false" aria-controls="CollapseCertificados">
                        <img src="{{ asset('img/icons/publish.svg') }}" alt="" width="20px">
                        <h6>Certificados</h6>
                    </a>
                    <ul class="collapse ps-4 list-unstyled" id="CollapseCertificados"
                        @if (request()->is('coord/evento/certificado*')) style='background-color: gray;display: block;' @else style='background-color: gray; display: none' @endif>
                        <li>
                            <a class="nav-link" id="cadastrarAssinatura" href="{{ route('coord.cadastrarAssinatura', ['eventoId' => $evento->id]) }}">
                                <img src="{{ asset('img/icons/plus-square-solid.svg') }}" alt="" width="20px">
                                <h6 style="font-size: 80%"> Cadastrar Assinatura</h6>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" id="listarAssinaturas" href="{{ route('coord.listarAssinaturas', ['eventoId' => $evento->id]) }}">
                                <img src="{{ asset('img/icons/list.svg') }}" alt="" width="20px">
                                <h6 style="font-size: 80%"> Listar Assinaturas</h6>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" id="cadastrarCertificado"
                                href="{{ route('coord.cadastrarCertificado', ['eventoId' => $evento->id]) }}">
                                <img src="{{ asset('img/icons/plus-square-solid.svg') }}" alt="" width="20px">
                                <h6 style="font-size: 80%"> Cadastrar Certificado</h6>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" id="listarCertificados"
                                href="{{ route('coord.listarCertificados', ['eventoId' => $evento->id]) }}">
                                <img src="{{ asset('img/icons/list.svg') }}" alt="" width="20px">
                                <h6 style="font-size: 80%"> Listar Certificados</h6>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" id="emitirCertificado"
                                href="{{ route('coord.emitirCertificado', ['eventoId' => $evento->id]) }}">
                                <img src="{{ asset('img/icons/plus-square-solid.svg') }}" alt="" width="20px">
                                <h6 style="font-size: 80%"> Emitir Certificado</h6>
                            </a>
                        </li>
                    </ul>
                </li>
            @endcan
            <li id="memorias" class="nav-item">
                <a class="nav-link dropdown-toggle d-flex align-items-center" data-bs-toggle="collapse"
                    href="#CollapseMemoria" role="button" aria-expanded="false" aria-controls="CollapseMemoria">
                    <img src="{{ asset('img/icons/slideshow.svg') }}" alt="" width="20px">
                    <h6>Memórias</h6>
                </a>
                <ul class="collapse ps-4 list-unstyled" id="CollapseMemoria"
                    @if (request()->is('coord/*/memoria*')) style='background-color: gray;display: block;' @else style='background-color: gray' @endif>
                    <li>
                        <a class="nav-link" id="cadastrarMemoria" href="{{ route('coord.memoria.create', $evento) }}">
                            <img src="{{ asset('img/icons/plus-square-solid.svg') }}" alt="" width="20px">
                            <h6>Adicionar registro</h6>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link" id="listarMemorias" href="{{ route('coord.memoria.index', $evento) }}">
                            <img src="{{ asset('img/icons/edit-regular-white.svg') }}" alt="" width="20px">
                            <h6>Listar registros</h6>
                        </a>
                    </li>
                </ul>
            </li>
            <li id="eventos" class="nav-item">
                <a class="nav-link dropdown-toggle d-flex align-items-center" data-bs-toggle="collapse"
                    href="#CollapseEvento" role="button" aria-expanded="false" aria-controls="CollapseEvento">
                    <img src="{{ asset('img/icons/palestrante.svg') }}" alt="" width="20px">
                    <h6>Outras configurações</h6>
                </a>
                <ul class="collapse ps-4 list-unstyled" id="CollapseEvento"
                    @if (request()->is('coord/evento/eventos*')) style='background-color: gray;display: block;' @else style='background-color: gray' @endif>
                    <li>
                        <a class="nav-link" id="editarEtiqueta" href="{{ route('coord.editarEtiqueta', ['eventoId' => $evento->id]) }}">
                            <img src="{{ asset('img/icons/edit-regular-white.svg') }}" alt="" width="20px">
                            <h6>Etiquetas dos Eventos</h6>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link" id="editarEtiquetaSubTrabalhos"
                            href="{{ route('coord.etiquetasTrabalhos', ['eventoId' => $evento->id]) }}">
                            <img src="{{ asset('img/icons/edit-regular-white.svg') }}" alt="" width="20px">
                            <h6>Etiquetas dos Trabalhos</h6>
                        </a>
                    </li>
                    @can('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento)
                        <li>
                            <a class="nav-link" id="modulos" href="{{ route('coord.modulos', ['id' => $evento->id]) }}">
                                <img src="{{ asset('img/icons/modulos.png') }}" alt="" width="20px">
                                <h6>Módulos</h6>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
            @can('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento)
                <li id="publicar" class="nav-item">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" data-bs-toggle="collapse"
                        href="#collapsePublicar" role="button" aria-expanded="false" aria-controls="collapsePublicar">
                        <img src="{{ asset('img/icons/publish.svg') }}" alt="" width="20px">
                        <h6>Publicar</h6>
                    </a>
                    <ul class="collapse ps-4 list-unstyled" id="collapsePublicar" style="background-color: gray">
                        <div style="display: none;">
                            <form id="habilitarEventoForm" method="GET"
                                action="{{ route('evento.habilitar', ['id' => $evento->id]) }}"></form>
                            <form id="desabilitarEventoForm" method="GET"
                                action="{{ route('evento.desabilitar', ['id' => $evento->id]) }}"></form>
                        </div>

                        <li>
                            <a id="publicarEvento" onclick="habilitarEvento()">
                                <img src="{{ asset('img/icons/alto-falante.svg') }}" alt="" width="20px">
                                <h6> Publicar Evento</h6>
                            </a>
                        </li>

                        <li>
                            <a id="desabilitarEventoPublicado" onclick="desabilitarEvento()">
                                    <img src="{{ asset('img/icons/alto-falante-nao.svg') }}" alt="" width="20px">
                                    <h6> Desfazer publicação</h6>
                            </a>
                        </li>
                    </ul>
                </li>
            @endcan
        @endcan
    </ul>
</aside>