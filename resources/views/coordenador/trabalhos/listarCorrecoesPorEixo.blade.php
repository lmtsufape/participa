@extends('coordenador.detalhesEvento')

@section('menu')
    <div id="divListarTrabalhos" style="display: block">

        <div class="row">
            <div class="col-sm-6">
                <h1 class="">Correções por Eixo</h1>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success" role="alert" align="center">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                {{ session('success') }}
            </div>
        @endif

        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Filtrar por Eixo</h5>
                <form method="GET" action="{{ route('coord.listarCorrecoesPorEixo') }}">
                    <input type="hidden" name="eventoId" value="{{ $evento->id }}">
                    <div class="row">
                        <div class="col-md-8">
                            <label for="eixo_id" class="form-label">Selecione o eixo:</label>
                            <select class="form-control" id="eixo_id" name="eixo_id">
                                <option value="">-- Selecione um eixo --</option>
                                @foreach($areas as $area)
                                    <option value="{{ $area->id }}" @if($eixoSelecionado == $area->id) selected @endif>{{ $area->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if($eixoSelecionado)
            <div class="card mb-3">
                <div class="card-body">
                    <form method="GET" action="{{ route('coord.listarCorrecoesPorEixo') }}">
                        <input type="hidden" name="eventoId" value="{{ $evento->id }}">
                        <input type="hidden" name="eixo_id" value="{{ $eixoSelecionado }}">
                        <div class="row">
                            <div class="col-md-2">
                                <label for="id" class="form-label">Buscar por ID</label>
                                <input type="number" class="form-control" name="id" value="{{ request('id') }}" placeholder="Digite o ID...">
                            </div>
                            <div class="col-md-8">
                                <label for="titulo" class="form-label">Buscar por Título</label>
                                <input type="text" class="form-control" name="titulo" value="{{ request('titulo') }}" placeholder="Digite o título do trabalho...">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">Buscar</button>
                            </div>
                        </div>
                        @if(request('titulo') || request('id'))
                            <div class="row mt-2">
                                <div class="col-12">
                                    <a href="{{ route('coord.listarCorrecoesPorEixo', ['eventoId' => $evento->id, 'eixo_id' => $eixoSelecionado]) }}" class="btn btn-outline-success btn-sm">Limpar filtros</a>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <form action="{{ route('coord.evento.avisoCorrecao', $evento->id) }}" method="POST" id="avisoCorrecao">
                @csrf
                <input type="hidden" name="eventoId" value="{{ $evento->id }}">

                @forelse ($trabalhosPorModalidade as $modalidade)
                    @if($modalidade->trabalhos_da_modalidade->isNotEmpty())
                        <div class="row justify-content-center" style="width: 100%;">
                            <div class="col-sm-12">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <div class="justify-content-between d-flex">
                                            <div>
                                                <h5 class="card-title">Modalidade: <span class="card-subtitle mb-2 text-muted">{{ $modalidade->nome }}</span></h5>
                                                @if ($modalidade->inicioCorrecao && $modalidade->fimCorrecao)
                                                    <h5 class="card-title">Correção: <span class="card-subtitle mb-2 text-muted">{{ date('d/m/Y H:i', strtotime($modalidade->inicioCorrecao)) }} - {{ date('d/m/Y H:i', strtotime($modalidade->fimCorrecao)) }}</span></h5>
                                                @else
                                                    <h5 class="card-title">Correção: <span class="card-subtitle mb-2 text-muted">não haverá</span></h5>
                                                @endif
                                            </div>
                                            <div>
                                                <button class="btn btn-primary" type="submit" form="avisoCorrecao">Lembrete de envio de versão corrigida</button>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <br>
                                            <table class="table table-hover table-responsive-lg table-sm table-striped">
                                                <thead>
                                                    <tr>
                                                        <th><input type="checkbox" onchange="alterarSelecionados(this)"></th>
                                                        <th scope="col">ID</th>
                                                        <th scope="col">Trabalho inicial</th>
                                                        <th scope="col">Trabalho revisado</th>
                                                        <th scope="col">Autor</th>
                                                        <th scope="col">
                                                            Data de Envio
                                                            <a href="{{ route('coord.listarCorrecoesPorEixo', array_merge(request()->query(), ['column' => 'data', 'direction' => 'asc'])) }}">
                                                                <i class="fas fa-arrow-alt-circle-up"></i>
                                                            </a>
                                                            <a href="{{ route('coord.listarCorrecoesPorEixo', array_merge(request()->query(), ['column' => 'data', 'direction' => 'desc'])) }}">
                                                                <i class="fas fa-arrow-alt-circle-down"></i>
                                                            </a>
                                                        </th>
                                                        <th scope="col">Parecer</th>
                                                        <th scope="col" class="text-center">Lembrete enviado</th>
                                                        <th scope="col" style="text-align:center;">Editar</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($modalidade->trabalhos_da_modalidade as $trabalho)
                                                        <tr>
                                                            <td><input type="checkbox" name="trabalhosSelecionados[]" value="{{ $trabalho->id }}"></td>
                                                            <td>{{ $trabalho->id }}</td>
                                                            <td>
                                                                @if ($trabalho->arquivo)
                                                                    <a href="{{ route('downloadTrabalho', ['id' => $trabalho->id]) }}">
                                                                        <span class="d-inline-block text-truncate" class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="{{ $trabalho->titulo }}" style="max-width: 150px;">
                                                                            {{ $trabalho->titulo }}
                                                                        </span>
                                                                    </a>
                                                                @else
                                                                    <span class="d-inline-block text-truncate" class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="{{ $trabalho->titulo }}" style="max-width: 150px;">
                                                                        {{ $trabalho->titulo }}
                                                                    </span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($trabalho->arquivoCorrecao)
                                                                    <a href="{{ route('downloadCorrecao', ['id' => $trabalho->id]) }}">
                                                                        <span class="d-inline-block text-truncate" class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="{{ $trabalho->titulo }}" style="max-width: 150px;">
                                                                            {{ $trabalho->titulo }}
                                                                        </span>
                                                                    </a>
                                                                @else
                                                                    <span class="d-inline-block text-truncate" class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="Aguardando envio da correção" style="max-width: 150px;">
                                                                        Aguardando envio
                                                                    </span>
                                                                @endif
                                                            </td>
                                                            <td>{{ $trabalho->autor->name }}</td>
                                                            <td>
                                                                @if ($trabalho->arquivoCorrecao)
                                                                    {{ date('d/m/Y H:i', strtotime($trabalho->arquivoCorrecao->created_at)) }}
                                                                @endif
                                                            </td>
                                                            <td style="text-align:center">
                                                                @foreach ($trabalho->atribuicoes as $revisor)
                                                                    @if($trabalho->avaliado($revisor->user))
                                                                        <a href="{{ route('coord.visualizarRespostaFormulario', ['eventoId' => $evento->id, 'modalidadeId' => $trabalho->modalidadeId, 'trabalhoId' => $trabalho->id, 'revisorId' => $revisor->id]) }}">
                                                                            <img src="{{ asset('img/icons/eye-regular.svg') }}" style="width:20px">
                                                                        </a>
                                                                    @endif
                                                                    <br>
                                                                @endforeach
                                                            </td>
                                                            <td class="text-center">{{ $trabalho->lembrete_enviado ? 'Sim' : 'Não' }}</td>
                                                            <td style="text-align:center">
                                                                <a href="#" data-bs-toggle="modal" data-bs-target="#modalCorrecaoTrabalho_{{ $trabalho->id }}" style="color:#114048ff">
                                                                    <img src="{{ asset('img/icons/edit-regular.svg') }}" width="20" alt="Editar">
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="alert alert-info" role="alert">Nenhum trabalho encontrado para este eixo.</div>
                @endforelse
            </form>

            @if($trabalhosPaginados && $trabalhosPaginados->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $trabalhosPaginados->links() }}
                </div>
            @endif

            @foreach ($trabalhosPaginados ?? [] as $trabalho)
                <div class="modal fade" id="modalCorrecaoTrabalho_{{ $trabalho->id }}" tabindex="-1" aria-labelledby="modalCorrecaoTrabalho_{{ $trabalho->id }}Label" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color: #114048ff; color: white;">
                                <h5 class="modal-title" id="modalCorrecaoTrabalho_{{ $trabalho->id }}Label">Correção do trabalho {{ $trabalho->titulo }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="formCorrecaoTrabalho{{ $trabalho->id }}" action="{{ route('trabalho.correcao', ['id' => $trabalho->id]) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @php
                                        $formSubTraba = $trabalho->evento->formSubTrab;
                                        $ordem = explode(",", $formSubTraba->ordemCampos);
                                        $modalidadeTrabalho = $trabalho->modalidade;
                                    @endphp
                                    <input type="hidden" name="trabalhoCorrecaoId" value="{{ $trabalho->id }}">
                                    @error('numeroMax' . $trabalho->id)
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="alert alert-danger" role="alert">
                                                    {{ $message }}
                                                </div>
                                            </div>
                                        </div>
                                    @enderror
                                    @foreach ($ordem as $indice)
                                        @if ($indice == "etiquetatitulotrabalho")
                                            <div class="row justify-content-center">
                                                <div class="col-sm-12">
                                                    <label for="nomeTrabalho_{{ $trabalho->id }}" class="col-form-label">{{ $formSubTraba->etiquetatitulotrabalho }}</label>
                                                    <input id="nomeTrabalho_{{ $trabalho->id }}" type="text" class="form-control @error('nomeTrabalho' . $trabalho->id) is-invalid @enderror" name="nomeTrabalho{{ $trabalho->id }}" value="@if(old('nomeTrabalho' . $trabalho->id) != null){{ old('nomeTrabalho' . $trabalho->id) }}@else{{ $trabalho->titulo }}@endif" autocomplete="nomeTrabalho" autofocus disabled>
                                                </div>
                                            </div>
                                            <div class="row justify-content-center">
                                                <div class="col-sm-12">
                                                    <label for="autorTrabalho_{{ $trabalho->autor->id }}" class="col-form-label">Autor</label>
                                                    <input id="autorTrabalho_{{ $trabalho->autor->id }}" type="text" class="form-control @error('autorTrabalho' . $trabalho->autor->id) is-invalid @enderror" name="autorTrabalho{{ $trabalho->autor->id }}" value="@if(old('autorTrabalho' . $trabalho->autor->id) != null){{ old('autorTrabalho' . $trabalho->autor->id) }}@else{{ $trabalho->autor->name }}@endif" autocomplete="autorTrabalho" autofocus disabled>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($indice == "etiquetacoautortrabalho")
                                            <div class="flexContainer" style="margin-top:20px">
                                                <div id="coautores_{{ $trabalho->id }}" class="flexContainer">
                                                    @if($trabalho->coautors->first() != null)
                                                        <h4>Co-autores</h4>
                                                        @foreach ($trabalho->coautors as $coautor)
                                                            <div class="item card">
                                                                <div class="row card-body">
                                                                    <div class="col-sm-4">
                                                                        <label>E-mail</label>
                                                                        <input type="email" style="margin-bottom:10px" value="{{ $coautor->user->email }}" oninput="buscarEmail(this)" class="form-control emailCoautor" name="emailCoautor_{{ $trabalho->id }}[]" placeholder="E-mail" disabled>
                                                                    </div>
                                                                    <div class="col-sm-5">
                                                                        <label>Nome Completo</label>
                                                                        <input type="text" style="margin-bottom:10px" value="{{ $coautor->user->name }}" class="form-control emailCoautor" name="nomeCoautor_{{ $trabalho->id }}[]" placeholder="Nome" disabled>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                        @if ($indice == "etiquetaareatrabalho")
                                            <div class="row justify-content-center">
                                                <div class="col-sm-12">
                                                    <label for="area_{{ $trabalho->id }}" class="col-form-label">{{ $formSubTraba->etiquetaareatrabalho }}</label>
                                                    <select id="area_{{ $trabalho->id }}" class="form-control @error('area' . $trabalho->id) is-invalid @enderror" name="area{{ $trabalho->id }}" required>
                                                        <option value="{{ $trabalho->area->nome }}" selected disabled>{{ $trabalho->area->nome }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($indice == "etiquetauploadtrabalho")
                                            <div class="row justify-content-center">
                                                @if ($modalidadeTrabalho->arquivo == true)
                                                    <div class="col-sm-12" style="margin-top: 20px;">
                                                        @if($trabalho->arquivoCorrecao()->first() != null)
                                                            <label for="nomeTrabalho" class="col-form-label">Upload de Correção do Trabalho:</label>
                                                            <a href="{{ route('downloadCorrecao', ['id' => $trabalho->id]) }}">Arquivo atual</a>
                                                            <br>
                                                            <small>Para trocar o arquivo envie um novo.</small>
                                                        @endif
                                                        <div class="custom-file">
                                                            <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="arquivoCorrecao" required>
                                                        </div>
                                                        <small>Arquivos aceitos nos formatos
                                                            @if($modalidadeTrabalho->pdf == true)<span> - pdf</span>@endif
                                                            @if($modalidadeTrabalho->jpg == true)<span> - jpg</span>@endif
                                                            @if($modalidadeTrabalho->jpeg == true)<span> - jpeg</span>@endif
                                                            @if($modalidadeTrabalho->png == true)<span> - png</span>@endif
                                                            @if($modalidadeTrabalho->docx == true)<span> - docx</span>@endif
                                                            @if($modalidadeTrabalho->odt == true)<span> - odt</span>@endif
                                                            @if($modalidadeTrabalho->zip == true)<span> - zip</span>@endif
                                                            @if($modalidadeTrabalho->svg == true)<span> - svg</span>@endif.
                                                        </small>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary" form="formCorrecaoTrabalho{{ $trabalho->id }}">Enviar correção</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        @else
            <div class="alert alert-info" role="alert">Selecione um eixo para visualizar as correções.</div>
        @endif
    </div>
@endsection

@section('script')
    <script>
        function alterarSelecionados(source) {
            let checkboxes = document.querySelectorAll('input[name="trabalhosSelecionados[]"]');
            checkboxes.forEach(checkbox => checkbox.checked = source.checked);
        }
    </script>
@endsection