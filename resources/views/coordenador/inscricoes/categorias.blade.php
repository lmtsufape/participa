@extends('coordenador.detalhesEvento')
@section('menu')
<div id="divListarCategorias" style="display: block">
    <div class="row">
        <div class="col-md-12">
            <h1 class="titulo-detalhes">Listar Categorias</h1>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-between">
                        <div class="col-md-6">
                            <h5 class="card-title">Categorias</h5>
                            <h6 class="card-subtitle mb-2 text-muted">Categorias cadastradas no seu evento</h6>
                        </div>
                        <div class="col-md-6 d-flex justify-content-sm-start justify-content-md-end align-items-center">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                Cadastrar categoria
                            </button>
                            <!-- Modal criar categoria -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl ">
                                    <div class="modal-content">
                                        <div class="modal-header" style="background-color: #114048ff; color: white;">
                                            <h5 class="modal-title" id="exampleModalLabel">Cadastrar Categoria</h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row justify-content-center">
                                                <div class="col-sm-12">
                                                    <h6 class="card-subtitle mb-2 text-muted fw-bold">Cadastre uma nova categoria para o seu evento</h6>
                                                    <form method="POST" id="cadastrarmodalidade" action="{{ route('categoria.participante.store') }}">
                                                        @csrf
                                                        <input type="hidden" name="evento_id" value="{{ $evento->id }}">
                                                        <p class="card-text">
                                                        <div class="form-group">
                                                            <label for="nome" class="col-form-label fw-bold required-field">{{ __('Nome da Categoria') }}</label>
                                                            <input id="nome" type="text" class="form-control @error('nome') is-invalid @enderror" name="nome" value="{{ old('nome') }}" required autocomplete="nome" autofocus>
                                                            @error('nome')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                        <br>
                                                        <div class="form-group">
                                                            <label for="descricao" class="col-form-label fw-bold">{{ __('Descrição') }}</label>
                                                            <textarea name="descricao" class="ckeditor-texto">{{ old('descricao') }}</textarea>
                                                            @error('descricao')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                        <br>
                                                        <div class="form-group">
                                                            <label for="valor_total"
                                                                    class="col-form-label fw-bold">{{ __('Valor da inscrição') }}*</label>
                                                            <small>(0 para inscrição gratuita)</small>
                                                            <input id="valor_total" type="number" step="0.1" class="form-control @error('valor_total') is-invalid @enderror" name="valor_total" value="0" required autocomplete="valor_total" autofocus>
                                                            @error('valor_total')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                        <br>
                                                        <div class="form-group">
                                                            <label for="limite_inscricao" class="col-form-label fw-bold">{{ __('Data limite para inscrição') }}</label>
                                                            <input id="limite_inscricao" type="datetime-local" class="form-control @error('limite_inscricao') is-invalid @enderror" name="limite_inscricao" value="{{ old('limite_inscricao') }}" autocomplete="limite_inscricao" autofocus>
                                                            @error('limite_inscricao')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>

                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <label class="form-check-label">
                                                                    <input class="form-check-input @error('permite_submissao') is-invalid @enderror" type="checkbox" name="permite_submissao" checked value="1">
                                                                    Permitir submissão de trabalhos
                                                                </label>
                                                                @error('permite_submissao')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="form-group" style="display: none">
                                                            <div class="form-check">
                                                                <label class="form-check-label">
                                                                    <input class="form-check-input @error('permite_inscricao') is-invalid @enderror" type="checkbox" name="permite_inscricao" checked value="1">
                                                                    Permitir inscrição
                                                                </label>
                                                                @error('permite_inscricao')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        </p>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                            <button type="submit" form="cadastrarmodalidade" class="btn btn-primary">
                                                {{ __('Confirmar') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-text">
                        <table class="table table-hover table-responsive-md table-md">
                            <thead>
                                <tr>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Valor</th>
                                    <th scope="col">Permite submissão</th>
                                    <th scope="col">Permite inscrição</th>
                                    <th scope="col">Data limite de inscrição</th>
                                    <th scope="col" style="text-align:center">Editar</th>
                                    <th scope="col" style="text-align:center">Remover</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categorias as $categoria)
                                <tr>
                                    <td>{{ $categoria->nome }}</td>
                                    <td>{{ $categoria->valor_total }}</td>
                                    <td>{{ $categoria->permite_submissao ? "Sim" : "Não" }}</td>
                                    <td>{{ $categoria->permite_inscricao ? "Sim" : "Não" }}</td>
                                    <td>{{ $categoria->limite_inscricao?->format('d/m/Y H:i')}}</td>
                                    <td style="text-align:center">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditarCategoria{{ $categoria->id }}"><img src="{{ asset('img/icons/edit-regular.svg') }}" style="width:20px"></a>
                                    </td>
                                    <td style="text-align:center">
                                        <form id="formExcluirCategoria{{ $categoria->id }}" action="{{ route('categoria.participante.destroy', $categoria->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalExcluirCategoria{{ $categoria->id }}">
                                                <img src="{{ asset('img/icons/trash-alt-regular.svg') }}" class="icon-card" style="width:20px; height:auto;" alt="">
                                            </a>
                                        </form>
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
</div>
@foreach ($categorias as $categoria)
<!-- Modal de editar categoria -->
<div class="modal fade" id="modalEditarCategoria{{ $categoria->id }}"  tabindex="-1" role="dialog" aria-labelledby="#label" aria-hidden="true">
    <div class="modal-dialog  modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #114048ff; color: white;">
                <h5 class="modal-title" id="#label">Editar categoria {{ $categoria->nome }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEditarCategoria{{ $categoria->id }}" action="{{ route('categoria.participante.update', ['id' => $categoria->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="editarCategoria" value="{{ $categoria->id }}">
                    <div class="form-group" style="margin-top: 20px; margin-bottom: 20px;">
                        <label for="nome_">Nome*</label>
                        <input id="nome_" type="text" class="form-control @error('nome_') is-invalid @enderror" name="nome_{{ $categoria->id }}" value="{{ old('nome_'.$categoria->id, $categoria->nome) }}">
                        @error('nome_'.$categoria->id)
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="descricao" class="col-form-label">{{ __('Descrição') }}</label>
                        <textarea name="descricao" class="ckeditor-texto">{{ $categoria->descricao }}</textarea>
                        @error('descricao')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group" style="margin-top: 20px; margin-bottom: 20px;">
                        <label for="valor_total_edit_{{ $categoria->id }}">Valor*</label>
                        <input id="valor_total_edit_{{ $categoria->id }}" type="number" step="0.01" min="0" class="form-control @error('valor_total_'.$categoria->id) is-invalid @enderror" name="valor_total_{{ $categoria->id }}" value="{{ old('valor_total_'.$categoria->id, $categoria->valor_total) }}" required>
                        <small>(0 para inscrição gratuita)</small>
                        @error('valor_total_'.$categoria->id)
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group" style="margin-top: 20px; margin-bottom: 20px;">
                        <label for="limite_inscricao_edit_{{ $categoria->id }}">Data limite de inscrição</label>
                        <input id="limite_inscricao_edit_{{ $categoria->id }}" type="datetime-local" class="form-control @error('limite_inscricao_'.$categoria->id) is-invalid @enderror" name="limite_inscricao_{{ $categoria->id }}" value="{{ old('limite_inscricao_'.$categoria->id, $categoria->limite_inscricao ? \Carbon\Carbon::parse($categoria->limite_inscricao)->format('Y-m-d\TH:i') : '') }}">
                        @error('limite_inscricao_'.$categoria->id)
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input @error('permite_submissao') is-invalid @enderror" type="checkbox" name="permite_submissao_{{ $categoria->id }}" @checked(old('permite_submissao_'.$categoria->id, $categoria->permite_submissao)) value="1">
                                Permitir submissão de trabalhos
                            </label>
                            @error('permite_submissao')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input @error('permite_inscricao') is-invalid @enderror" type="checkbox" name="permite_inscricao_{{ $categoria->id }}" @checked(old('permite_inscricao_'.$categoria->id, $categoria->permite_inscricao)) value="1">
                                Permitir inscrição
                            </label>
                            @error('permite_inscricao')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary" form="formEditarCategoria{{ $categoria->id }}">Atualizar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de exclusão da categoria -->
<div class="modal fade" id="modalExcluirCategoria{{ $categoria->id }}" tabindex="-1" role="dialog" aria-labelledby="#label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #114048ff; color: white;">
                <h5 class="modal-title" id="#label">Confirmação</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Tem certeza que deseja excluir essa categoria?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                <button type="submit" class="btn btn-primary" form="formExcluirCategoria{{ $categoria->id }}">Sim</button>
            </div>
        </div>
    </div>
</div>


@endforeach
@endsection
@section('javascript')
<script type="text/javascript">
    CKEDITOR.replaceAll('ckeditor-texto');
    $.fn.modal.Constructor.prototype._enforceFocus = function() {};
</script>
@endsection
