@extends('coordenador.detalhesEvento') @section('menu')
    <div id="divListarRegistros" style="display: block">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="titulo-detalhes">Listar Registros</h1>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">

                        <p class="card-text">
                        <table class="table table-hover table-responsive-lg table-sm">
                            <thead>
                            <tr>
                                <th scope="col" style="width:40px;"></th>
                                <th scope="col">Título</th>
                                <th scope="col">Link</th>
                                <th scope="col">Arquivo</th>
                                <th scope="col" style="text-align:center">Editar</th>
                                <th scope="col" style="text-align:center">Remover</th>
                            </tr>
                            </thead>
                            <tbody id="memoria-tbody">
                            @foreach ($registros as $registro)
                                <tr data-id="{{ $registro->id }}">
                                    <td class="handle text-center" style="cursor: grab;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="m7 2l6 3.9v2.272l-5-3.25v12.08H6V4.922l-5 3.25V5.9zm9 17.08V7h2v12.08l5-3.25v2.272l-6 3.9l-6-3.9V15.83z"/></svg>
                                    </td>
                                    <td>{{ $registro->titulo }}</td>
                                    <td>
                                        @if ($registro->link)
                                            <a href="{{ $registro->link }}" target="_blank">link</a>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($registro->arquivo)
                                            <a href="{{ asset('storage/' . $registro->arquivo) }}" target="_blank">arquivo</a>
                                        @endif
                                    </td>
                                    <td style="text-align:center">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditarRegistro{{ $registro->id }}">
                                            <img src="{{ asset('img/icons/edit-regular.svg') }}" style="width:20px">
                                        </a>
                                    </td>
                                    <td style="text-align:center">
                                        <form id="formExcluirRegistro{{ $registro->id }}" method="POST" action="{{ route('coord.memoria.destroy', $registro->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="evento" value="{{ $evento->id }}">
                                            <input type="hidden" name="memoria" value="{{ $registro->id }}">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalExcluirRegistro{{ $registro->id }}">
                                                <img src="{{ asset('img/icons/trash-alt-regular.svg') }}" style="width:20px" alt="">
                                            </a>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @foreach ($registros as $registro)
        <div class="modal fade"
            id="modalEditarRegistro{{ $registro->id }}"
            tabindex="-1"
            role="dialog"
            aria-labelledby="#label"
            aria-hidden="true">
            <div class="modal-dialog"
                role="document">
                <div class="modal-content">
                    <div class="modal-header"
                        style="background-color: #114048ff; color: white;">
                        <h5 class="modal-title"
                            id="#label">Editar registro {{ $registro->nome }}</h5>

                    </div>
                    <div class="modal-body">
                        <form method="POST"
                            id="formEditarRegistro{{ $registro->id }}"
                            action="{{ route('coord.memoria.update', ['evento' => $evento, 'memoria' => $registro]) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <p class="card-text">
                            <div class="row justify-content-center">
                                <div class="col-sm-12">
                                    <label for="titulo"
                                        class="col-form-label">{{ __('Título') }}</label>
                                    <input id="titulo"
                                        type="text"
                                        class="form-control @error('titulo') is-invalid @enderror"
                                        name="titulo"
                                        value="{{ old('titulo', $registro->titulo) }}"
                                        required
                                        autocomplete="titulo"
                                        autofocus>
                                    @error('titulo')
                                        <span class="invalid-feedback"
                                            role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-sm-12">
                                    <label for="link"
                                        class="col-form-label">{{ __('Link') }}</label>
                                    <input id="link"
                                        type="text"
                                        class="form-control @error('link') is-invalid @enderror"
                                        name="link"
                                        value="{{ old('link', $registro->link) }}"
                                        autocomplete="link"
                                        autofocus>
                                    @error('link')
                                        <span class="invalid-feedback"
                                            role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-sm-12">
                                    <label for="arquivo"
                                        class="col-form-label">{{ __('Arquivo') }}</label>
                                    <div class="custom-file">
                                        <input type="file"
                                            class="filestyle @error('arquivo') is-invalid @enderror"
                                            data-placeholder="Nenhum arquivo"
                                            data-text="Selecionar"
                                            data-btnClass="btn-primary-lmts"
                                            name="arquivo">
                                        @if ($registro->arquivo != null)
                                            <a href="/storage/{{ $registro->arquivo }}">arquivo</a>
                                        @endif
                                    </div>
                                    <small>O arquivo deve ter no máximo 2MB</small>
                                    @error('arquivo')
                                        <span class="invalid-feedback"
                                            role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            </p>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit"
                            class="btn btn-primary"
                            form="formEditarRegistro{{ $registro->id }}">Atualizar</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade"
            id="modalExcluirRegistro{{ $registro->id }}"
            tabindex="-1"
            role="dialog"
            aria-labelledby="#label"
            aria-hidden="true">
            <div class="modal-dialog"
                role="document">
                <div class="modal-content">
                    <div class="modal-header"
                        style="background-color: #114048ff; color: white;">
                        <h5 class="modal-title"
                            id="#label">Confirmação</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body"> Tem certeza que deseja excluir esse registro? </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
                        <button type="submit"
                            class="btn btn-primary"
                            form="formExcluirRegistro{{ $registro->id }}">Sim</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@section('javascript')
    @parent
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tbody = document.getElementById('memoria-tbody');

            if (tbody) {
                Sortable.create(tbody, {
                    handle: '.handle',
                    animation: 150,
                    onEnd: () => {


                        const order = Array.from(tbody.querySelectorAll('tr'))
                            .map((tr, idx) => ({
                                id: tr.dataset.id,
                                position: idx + 1
                            }));


                        fetch("{{ route('coord.memoria.reorder') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ order })
                        })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Falha na comunicação com o servidor.');
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.status !== 'ok') {
                                    console.error('O servidor retornou um erro:', data);
                                    alert('Ocorreu um erro ao salvar a nova ordem dos registros.');
                                }

                            })
                            .catch(error => {
                                console.error('Erro no fetch:', error);
                                alert('Ocorreu um erro de comunicação ao salvar a nova ordem.');
                            });
                    }
                });
            }
        });
    </script>
@endsection
