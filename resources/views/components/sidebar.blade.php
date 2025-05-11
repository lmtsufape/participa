<aside class="sidebar p-3 border-end h-100 overflow-auto w-100" style="background-color: white; color: black;">
    <div class="mb-4">
        <h2 class="py-2">
            @if ($evento->is_multilingual && Session::get('idiomaAtual') === 'en')
                {{ $evento->nome_en }}
            @elseif ($evento->is_multilingual && Session::get('idiomaAtual') === 'es')
                {{ $evento->nome_es }}
            @else
                {{ $evento->nome }}
            @endif
        </h2>
        <div class="d-flex flex-column gap-3">
            @can('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento)
                <a href="{{ route('evento.editar', $evento->id) }}"
                    class="d-flex align-items-center gap-2 text-decoration-none text-dark">
                    <img src="{{ asset('img/icons/edit-regular-white.svg') }}" alt="" width="20px" class="edit-icon">
                    Editar evento
                </a>
                @if ($evento->eventoPai == null)
                    <a href="{{ route('subevento.criar', $evento->id) }}"
                        class="d-flex align-items-center gap-2 text-decoration-none text-dark">
                        <img src="{{ asset('img/icons/plus-square-solid.svg') }}" alt="" width="20px" class="add-icon">
                        Criar Subevento
                    </a>
                    <hr class="my-2">
                @endif
            @endcan
        </div>
    </div>
    <ul class="nav nav-pills flex-column">
        @can('isCoordenadorOrCoordenadorDasComissoes', $evento)
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-2" id="informacoes"
                    href="{{ route('coord.informacoes', ['eventoId' => $evento->id]) }}">
                    <img src="{{ asset('img/icons/info-circle-solid.svg') }}" width="20px" alt="">
                    <span>Informações</span>
                </a>
            </li>
            @can('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento)
                <li id="programacao" class="nav-item">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="collapse"
                        href="#collapseProgramacao" role="button" aria-expanded="false" aria-controls="collapseProgramacao">
                        <img src="{{ asset('img/icons/slideshow.svg') }}" width="20px" alt="">
                        <span>Programação</span>
                    </a>
                    <div class="collapse" id="collapseProgramacao" @if (
                            request()->is('coord/evento/atividade*') ||
                            request()->routeIs('coord.arquivos-adicionais') ||
                            request()->is('coord/evento/palestrantes*')
                        )
                    style='display: block;' @endif>
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2" id="cadastrarModalidade"
                                    href="{{ route('coord.atividades', ['id' => $evento->id]) }}">
                                    <img src="{{ asset('img/icons/plus-square-solid.svg') }}" width="20px" alt="">
                                    <span>Atividades</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2" id="cadastrarModalidade"
                                    href="{{ route('checkout.pagamentos', ['id' => $evento->id]) }}">
                                    <img src="{{ asset('img/icons/edit-regular-white.svg') }}" width="20px" alt="">
                                    <span>Pagamentos</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2" id="pdfadicional"
                                    href="{{ route('coord.arquivos-adicionais', $evento) }}">
                                    <img src="{{ asset('img/icons/file-alt-regular.svg') }}" width="20px" alt="">
                                    <span>Arquivos adicionais</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" id="palestrantes"
                                    data-bs-toggle="collapse" href="#dropdownPalestrantes" aria-controls="dropdownPalestrantes"
                                    role="button" aria-expanded="false">
                                    <img src="{{ asset('img/icons/user-tie-solid.svg') }}" width="20px" alt="">
                                    <span>Palestrantes</span>
                                </a>
                                <div class="collapse" id="dropdownPalestrantes">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link d-flex align-items-center gap-2" id="cadastrarPalestrante"
                                                href="{{ route('coord.palestrantes.create', ['eventoId' => $evento->id]) }}">
                                                <img src="{{ asset('img/icons/user-plus-solid.svg') }}" width="20px" alt="">
                                                <span>Cadastrar palestra</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link d-flex align-items-center gap-2" id="listarPalestrantes"
                                                href="{{ route('coord.palestrantes.index', ['eventoId' => $evento->id]) }}">
                                                <img src="{{ asset('img/icons/list.svg') }}" width="20px" alt="">
                                                <span>Listar palestras</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcan
            <li id="areas" class="nav-item">
                <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="collapse"
                    href="#collapseAreas" role="button" aria-expanded="false" aria-controls="collapseAreas">
                    <img src="{{ asset('img/icons/area.svg') }}" alt="" width="20px">
                    <span>Áreas</span>
                </a>
                <div class="collapse" id="collapseAreas" @if (request()->is('coord/evento/areas*')) style='display: block;'
                @endif>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center gap-2" id="listarAreas"
                                href="{{ route('coord.listarAreas', ['eventoId' => $evento->id]) }}">
                                <img src="{{ asset('img/icons/list.svg') }}" width="20px" alt="">
                                <span>Listar Áreas</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li id="modalidades" class="nav-item">
                <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="collapse"
                    href="#collapseModalidades" role="button" aria-expanded="false" aria-controls="collapseModalidades">
                    <img src="{{ asset('img/icons/sitemap-solid.svg') }}" alt="" width="20px">
                    <span>Modalidades</span>
                </a>
                <div class="collapse" id="collapseModalidades" @if (request()->is('coord/evento/modalidade*'))
                style='display: block;' @endif>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center gap-2" id="cadastrarModalidade"
                                href="{{ route('coord.cadastrarModalidade', ['eventoId' => $evento->id]) }}">
                                <img src="{{ asset('img/icons/plus-square-solid.svg') }}" alt="" width="20px">
                                <span>Cadastrar Modalidade</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center gap-2" id="listarModalidade"
                                href="{{ route('coord.listarModalidade', ['eventoId' => $evento->id]) }}">
                                <img src="{{ asset('img/icons/list.svg') }}" alt="" width="20px">
                                <span>Listar Modalidades</span>
                            </a>
                        </li>
                        @can('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento)
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2" id="cadastrarCriterio"
                                    href="{{ route('coord.cadastrarCriterio', ['eventoId' => $evento->id]) }}">
                                    <img src="{{ asset('img/icons/plus-square-solid.svg') }}" alt="" width="20px">
                                    <span>Cadastrar Critérios</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2" id="listarCriterios"
                                    href="{{ route('coord.listarCriterios', ['eventoId' => $evento->id]) }}">
                                    <img src="{{ asset('img/icons/list.svg') }}" alt="" width="20px">
                                    <span>Listar Critérios</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2" id="forms"
                                    href="{{ route('coord.forms', ['eventoId' => $evento->id]) }}">
                                    <img src="{{ asset('img/icons/plus-square-solid.svg') }}" alt="" width="20px">
                                    <span>Formulário</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </div>
            </li>
            <li id="comissao" class="nav-item">
                <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="collapse"
                    href="#collapseComissao" role="button" aria-expanded="false" aria-controls="collapseComissao">
                    <img src="{{ asset('img/icons/user-tie-solid.svg') }}" alt="" width="20px">
                    <span>Comissão Científica</span>
                </a>
                <div class="collapse" id="collapseComissao" @if (request()->is('coord/evento/comissaoCientifica*'))
                style='display: block;' @endif>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center gap-2" id="cadastrarComissao"
                                href="{{ route('coord.cadastrarComissao', ['eventoId' => $evento->id]) }}">
                                <img src="{{ asset('img/icons/user-plus-solid.svg') }}" alt="" width="20px">
                                <span>Cadastrar membro</span>
                            </a>
                        </li>
                        @can('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento)
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2" id="definirCoordComissao"
                                    href="{{ route('coord.definirCoordComissao', ['eventoId' => $evento->id]) }}">
                                    <img src="{{ asset('img/icons/crown-solid.svg') }}" alt="" width="20px">
                                    <span>Definir Coordenador</span>
                                </a>
                            </li>
                        @endcan
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center gap-2" id="listarComissao"
                                href="{{ route('coord.listarComissao', ['eventoId' => $evento->id]) }}">
                                <img src="{{ asset('img/icons/list.svg') }}" alt="" width="20px">
                                <span>Listar Comissão</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li id="comissaoOrganizadora" class="nav-item">
                <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="collapse"
                    href="#collapseComissaoOrganizadora" role="button" aria-expanded="false"
                    aria-controls="collapseComissaoOrganizadora">
                    <img src="{{ asset('img/icons/user-tie-solid.svg') }}" alt="" width="20px">
                    <span>Comissão Organizadora</span>
                </a>
                <div class="collapse" id="collapseComissaoOrganizadora" @if (request()->is('comissaoOrganizadora*'))
                style='display: block;' @endif>
                    <ul class="nav flex-column">
                        @can('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento)
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2" id="cadastrarComissaoOrganizadora"
                                    href="{{ route('coord.comissao.organizadora.create', ['id' => $evento->id]) }}">
                                    <img src="{{ asset('img/icons/user-plus-solid.svg') }}" alt="" width="20px">
                                    <span>Cadastrar membro</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2" id="definirCoordComissaoOrganizadora"
                                    href="{{ route('coord.definir.coordComissaoOrganizadora', ['id' => $evento]) }}">
                                    <img src="{{ asset('img/icons/crown-solid.svg') }}" alt="" width="20px">
                                    <span>Definir Coordenador</span>
                                </a>
                            </li>
                        @endcan
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center gap-2" id="listarComissaoOrganizadora"
                                href="{{ route('coord.listar.comissaoOrganizadora', ['id' => $evento]) }}">
                                <img src="{{ asset('img/icons/list.svg') }}" alt="" width="20px">
                                <span>Listar Comissão</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li id="outrasComissoes" class="nav-item">
                <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="collapse"
                    href="#collapseOutrasComissoes" role="button" aria-expanded="false"
                    aria-controls="collapseOutrasComissoes">
                    <img src="{{ asset('img/icons/user-tie-solid.svg') }}" alt="" width="20px">
                    <span>Outras comissões</span>
                </a>
                <div class="collapse" id="collapseOutrasComissoes" @if (request()->is('coord/evento/*/tipocomissao*'))
                style='display: block;' @endif>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center gap-2" id="cadastrarOutraComissao"
                                href="{{ route('coord.tipocomissao.create', $evento) }}">
                                <img src="{{ asset('img/icons/user-plus-solid.svg') }}" alt="" width="20px">
                                <span>Cadastrar comissão</span>
                            </a>
                        </li>
                        @foreach ($evento->outrasComissoes as $comissao)
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2"
                                    href="{{ route('coord.tipocomissao.show', ['evento' => $evento, 'comissao' => $comissao]) }}">
                                    <img src="{{ asset('img/icons/list.svg') }}" alt="" width="20px">
                                    <span>{{ $comissao->nome }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </li>
            @can('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento)
                <li id="revisores" class="nav-item">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="collapse"
                        href="#CollapseRevisores" role="button" aria-expanded="false" aria-controls="CollapseRevisores">
                        <img src="{{ asset('img/icons/glasses-solid.svg') }}" alt="" width="20px">
                        <span>Avaliadores</span>
                    </a>
                    <div class="collapse" id="CollapseRevisores" @if (request()->is('coord/evento/revisores*'))
                    style='display: block;' @endif>
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2" id="listarRevisores"
                                    href="{{ route('coord.listarRevisores', ['eventoId' => $evento->id]) }}">
                                    <img src="{{ asset('img/icons/list.svg') }}" alt="" width="20px">
                                    <span>Listar Avaliadores</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcan
            @can('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento)
                <li id="inscricoes" class="nav-item">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="collapse"
                        href="#CollapseInscricoes" role="button" aria-expanded="false" aria-controls="CollapseInscricoes">
                        <img src="{{ asset('img/icons/edit-regular-white.svg') }}" alt="" width="20px">
                        <span>Inscrições</span>
                    </a>
                    <div class="collapse" id="CollapseInscricoes" @if (request()->is($evento->id . '/inscricoes*'))
                    style='display: block;' @endif>
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2"
                                    href="{{ route('inscricao.categorias', $evento) }}">
                                    <img src="{{ asset('img/icons/list.svg') }}" alt="" width="20px">
                                    <span>Categorias</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2"
                                    href="{{ route('inscricao.formulario', $evento) }}">
                                    <img src="{{ asset('img/icons/list.svg') }}" alt="" width="20px">
                                    <span>Formulário</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2"
                                    href="{{ route('inscricao.inscritos', $evento) }}">
                                    <img src="{{ asset('img/icons/list.svg') }}" alt="" width="20px">
                                    <span>Inscritos</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcan
            @can('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento)
                <li id="trabalhos" class="nav-item">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="collapse"
                        href="#collapseTrabalhos" role="button" aria-expanded="false" aria-controls="collapseTrabalhos">
                        <img src="{{ asset('img/icons/file-alt-regular.svg') }}" alt="" width="20px">
                        <span>Submissões</span>
                    </a>
                    <div class="collapse" id="collapseTrabalhos" @if (request()->is('coord/evento/trabalhos*'))
                    style='display: block;' @endif>
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2" id="submissoesTrabalhos"
                                    href="{{ route('coord.definirSubmissoes', ['eventoId' => $evento->id]) }}">
                                    <img src="{{ asset('img/icons/plus-square-solid.svg') }}" alt="" width="20px">
                                    <span>Definir Submissões</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2" id="submeterTrabalho" href="#"
                                    data-bs-toggle="collapse" data-bs-target="#dropdownSubmeterTrabalho" aria-expanded="false"
                                    aria-controls="dropdownSubmeterTrabalho">
                                    <img src="{{ asset('img/icons/plus-square-solid.svg') }}" alt="" width="20px">
                                    <span>Submeter Trabalho</span>
                                </a>
                                <div class="collapse" id="dropdownSubmeterTrabalho">
                                    @foreach ($evento->modalidades()->get() as $modalidade)
                                        <a class="nav-link d-flex align-items-center gap-2"
                                            id="submeterTrabalho{{ $modalidade->id }}"
                                            href="{{ route('trabalho.index', ['id' => $evento->id, 'idModalidade' => $modalidade->id]) }}">
                                            <span>{{ $modalidade->nome }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2" id="resultadosTrabalhos"
                                    href="{{ route('coord.resultados', ['id' => $evento->id]) }}">
                                    <img src="{{ asset('img/icons/plus-square-solid.svg') }}" alt="" width="20px">
                                    <span>Resultado</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2" id="listarTrabalhos" href="#"
                                    data-bs-toggle="collapse" data-bs-target="#dropdownListarTrabalhos" aria-expanded="false"
                                    aria-controls="dropdownListarTrabalhos">
                                    <img src="{{ asset('img/icons/list.svg') }}" alt="" width="20px">
                                    <span>Listar Trabalhos</span>
                                </a>
                                <div class="collapse" id="dropdownListarTrabalhos">
                                    <a class="nav-link d-flex align-items-center gap-2"
                                        href="{{ route('coord.listarTrabalhos', ['eventoId' => $evento->id, 'titulo', 'asc', 'rascunho']) }}">
                                        <span>Todos os Trabalhos</span>
                                    </a>
                                    @foreach ($evento->modalidades()->get() as $modalidade)
                                        <a class="nav-link d-flex align-items-center gap-2"
                                            id="listarTrabalhos{{ $modalidade->id }}"
                                            href="{{ route('coord.listarTrabalhosModalidades', ['eventoId' => $evento->id, 'modalidadeId' => $modalidade->id, 'titulo', 'asc', 'rascunho']) }}">
                                            <span>{{ $modalidade->nome }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2" id="listarAvaliacoes" href="#"
                                    data-bs-toggle="collapse" data-bs-target="#dropdownListarAvaliacoes" aria-expanded="false"
                                    aria-controls="dropdownListarAvaliacoes">
                                    <img src="{{ asset('img/icons/list.svg') }}" alt="" width="20px">
                                    <span>Listar Avaliações</span>
                                </a>
                                <div class="collapse" id="dropdownListarAvaliacoes">
                                    <a class="nav-link d-flex align-items-center gap-2"
                                        href="{{ route('coord.listarAvaliacoes', ['eventoId' => $evento->id, 'titulo', 'asc', 'rascunho']) }}">
                                        <span>Todas as Avaliações</span>
                                    </a>
                                    @foreach ($evento->modalidades()->get() as $modalidade)
                                        <a class="nav-link d-flex align-items-center gap-2"
                                            id="listarAvaliacoesModalidade{{ $modalidade->id }}"
                                            href="{{ route('coord.respostasTrabalhos', ['eventoId' => $evento->id, 'modalidadeId' => $modalidade->id]) }}">
                                            <span>{{ $modalidade->nome }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2" id="correcoesTrabalhos"
                                    href="{{ route('coord.listarCorrecoes', ['eventoId' => $evento->id, 'titulo', 'asc']) }}">
                                    <img src="{{ asset('img/icons/list.svg') }}" alt="" width="20px">
                                    <span>Listar Correções</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcan
            @can('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento)
                <li id="certificados" class="nav-item">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="collapse"
                        href="#CollapseCertificados" role="button" aria-expanded="false" aria-controls="CollapseCertificados">
                        <img src="{{ asset('img/icons/publish.svg') }}" alt="" width="20px">
                        <span>Certificados</span>
                    </a>
                    <div class="collapse" id="CollapseCertificados" @if (request()->is('coord/evento/certificado*'))
                    style='display: block;' @endif>
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2" id="cadastrarAssinatura"
                                    href="{{ route('coord.cadastrarAssinatura', ['eventoId' => $evento->id]) }}">
                                    <img src="{{ asset('img/icons/plus-square-solid.svg') }}" alt="" width="20px">
                                    <span>Cadastrar Assinatura</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2" id="listarAssinaturas"
                                    href="{{ route('coord.listarAssinaturas', ['eventoId' => $evento->id]) }}">
                                    <img src="{{ asset('img/icons/list.svg') }}" alt="" width="20px">
                                    <span>Listar Assinaturas</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2" id="cadastrarCertificado"
                                    href="{{ route('coord.cadastrarCertificado', ['eventoId' => $evento->id]) }}">
                                    <img src="{{ asset('img/icons/plus-square-solid.svg') }}" alt="" width="20px">
                                    <span>Cadastrar Certificado</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2" id="listarCertificados"
                                    href="{{ route('coord.listarCertificados', ['eventoId' => $evento->id]) }}">
                                    <img src="{{ asset('img/icons/list.svg') }}" alt="" width="20px">
                                    <span>Listar Certificados</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2" id="emitirCertificado"
                                    href="{{ route('coord.emitirCertificado', ['eventoId' => $evento->id]) }}">
                                    <img src="{{ asset('img/icons/plus-square-solid.svg') }}" alt="" width="20px">
                                    <span>Emitir Certificado</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcan
            <li id="memorias" class="nav-item">
                <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="collapse"
                    href="#CollapseMemoria" role="button" aria-expanded="false" aria-controls="CollapseMemoria">
                    <img src="{{ asset('img/icons/slideshow.svg') }}" alt="" width="20px">
                    <span>Memórias</span>
                </a>
                <div class="collapse" id="CollapseMemoria" @if (request()->is('coord/*/memoria*')) style='display: block;'
                @endif>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center gap-2" id="cadastrarMemoria"
                                href="{{ route('coord.memoria.create', $evento) }}">
                                <img src="{{ asset('img/icons/plus-square-solid.svg') }}" alt="" width="20px">
                                <span>Adicionar registro</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center gap-2" id="listarMemorias"
                                href="{{ route('coord.memoria.index', $evento) }}">
                                <img src="{{ asset('img/icons/edit-regular-white.svg') }}" alt="" width="20px">
                                <span>Listar registros</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li id="eventos" class="nav-item">
                <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="collapse"
                    href="#CollapseEvento" role="button" aria-expanded="false" aria-controls="CollapseEvento">
                    <img src="{{ asset('img/icons/palestrante.svg') }}" alt="" width="20px">
                    <span>Outras configurações</span>
                </a>
                <div class="collapse" id="CollapseEvento" @if (request()->is('coord/evento/eventos*'))
                style='display: block;' @endif>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center gap-2" id="editarEtiqueta"
                                href="{{ route('coord.editarEtiqueta', ['eventoId' => $evento->id]) }}">
                                <img src="{{ asset('img/icons/edit-regular-white.svg') }}" alt="" width="20px">
                                <span>Etiquetas dos Eventos</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center gap-2" id="editarEtiquetaSubTrabalhos"
                                href="{{ route('coord.etiquetasTrabalhos', ['eventoId' => $evento->id]) }}">
                                <img src="{{ asset('img/icons/edit-regular-white.svg') }}" alt="" width="20px">
                                <span>Etiquetas dos Trabalhos</span>
                            </a>
                        </li>
                        @can('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento)
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2" id="modulos"
                                    href="{{ route('coord.modulos', ['id' => $evento->id]) }}">
                                    <img src="{{ asset('img/icons/modulos.png') }}" alt="" width="20px">
                                    <span>Módulos</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </div>
            </li>
            @can('isCoordenadorOrCoordenadorDaComissaoOrganizadora', $evento)
                <li id="publicar" class="nav-item">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="collapse"
                        href="#collapsePublicar" role="button" aria-expanded="false" aria-controls="collapsePublicar">
                        <img src="{{ asset('img/icons/publish.svg') }}" alt="" width="20px">
                        <span>Publicar</span>
                    </a>
                    <div class="collapse" id="collapsePublicar">
                        <div style="display: none;">
                            <form id="habilitarEventoForm" method="GET"
                                action="{{ route('evento.habilitar', ['id' => $evento->id]) }}"></form>
                            <form id="desabilitarEventoForm" method="GET"
                                action="{{ route('evento.desabilitar', ['id' => $evento->id]) }}"></form>
                        </div>
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2" id="publicarEvento"
                                    onclick="habilitarEvento()">
                                    <img src="{{ asset('img/icons/alto-falante.svg') }}" alt="" width="20px">
                                    <span>Publicar Evento</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2" id="desabilitarEventoPublicado"
                                    onclick="desabilitarEvento()">
                                    <img src="{{ asset('img/icons/alto-falante-nao.svg') }}" alt="" width="20px">
                                    <span>Desfazer publicação</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcan
        @endcan
    </ul>
</aside>