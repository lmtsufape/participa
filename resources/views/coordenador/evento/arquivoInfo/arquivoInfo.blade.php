@extends('coordenador.detalhesEvento')

@section('menu')
    <div id="divListarAreas" style="display: block">
        <div class="row">
            <div class="col-md-12">
                <h1 class="titulo-detalhes">Arquivos adicionais</h1>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-between">
                            <div class="col-md-6">
                                <h5 class="card-title">Arquivos</h5>
                                <h6 class="card-subtitle mb-2 text-muted">Arquivo adicionados</h6>
                            </div>
                            <div class="col-md-6 d-flex justify-content-sm-start justify-content-md-end align-items-center">
                                @component('coordenador.evento.arquivoInfo.modal-arquivoInfo', ['evento' => $evento])@endcomponent
                            </div>
                        </div>
                        <p class="card-text">
                        <table class="table table-hover table-responsive-md table-md">
                            <thead>
                            <tr>
                                {{-- NOVO: Coluna para o handle de arrastar --}}
                                <th scope="col" style="width:40px;"></th>
                                <th scope="col">Nome</th>
                                <th scope="col" style="text-align:center">Editar</th>
                                <th scope="col" style="text-align:center">Remover</th>
                            </tr>
                            </thead>
                            {{-- NOVO: Adicionado um ID ao tbody para ser selecionado pelo JavaScript --}}
                            <tbody id="arquivos-tbody">
{{--                            @php dd($evento->arquivoInfos)@endphp--}}

                            @foreach ($evento->arquivoInfos as $arquivo)
                                {{-- NOVO: Adicionado data-id na linha para identificar o arquivo --}}
                                <tr data-id="{{ $arquivo->id }}">
                                    {{-- NOVO: Célula com o ícone de arrastar (handle) --}}
                                    <td class="handle text-center" style="cursor: grab;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="m7 2l6 3.9v2.272l-5-3.25v12.08H6V4.922l-5 3.25V5.9zm9 17.08V7h2v12.08l5-3.25v2.272l-6 3.9l-6-3.9V15.83z"/></svg>
                                    </td>
                                    <td>{{ $arquivo->nome }}</td>
                                    <td style="text-align:center">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditarArea{{ $arquivo->id }}">
                                            <img src="{{ asset('img/icons/edit-regular.svg') }}" style="width:20px">
                                        </a>
                                    </td>
                                    <td style="text-align:center">
                                        <form id="formExcluirArea{{ $arquivo->id }}" method="POST" action="{{ route('coord.arquivos-adicionais.delete', $arquivo) }}">
                                            @csrf
                                            @method('DELETE')
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalExcluirArea{{ $arquivo->id }}">
                                                <img src="{{ asset('img/icons/trash-alt-regular.svg') }}" style="width: 20px" alt="">
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

    @foreach ($evento->arquivoInfos as $arquivo)
        @component('coordenador.evento.arquivoInfo.modal-editar-arquivoInfo', ['arquivo' => $arquivo])@endcomponent
        @component('coordenador.evento.arquivoInfo.modal-excluir-arquivoInfo', ['arquivo' => $arquivo])@endcomponent
    @endforeach
@endsection

{{-- NOVO: Seção inteira de JavaScript adicionada --}}
@section('javascript')
    @parent
    {{-- 1. Importa a biblioteca SortableJS --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <script>
        {{-- 2. Espera o documento carregar --}}
        document.addEventListener('DOMContentLoaded', () => {
            const tbody = document.getElementById('arquivos-tbody');

            {{-- 3. Inicializa o Sortable na tabela --}}
            Sortable.create(tbody, {
                handle: '.handle',      // Define que o arrastar só funciona ao clicar no ícone
                animation: 150,         // Animação suave
                onEnd: () => {          // Função chamada ao soltar um item

                    // 4. Monta um array com a nova ordem: [{id, position}, ...]
                    // ADAPTADO: a variável foi renomeada para 'order'
                    const order = Array.from(tbody.querySelectorAll('tr'))
                        .map((tr, idx) => ({
                            id: tr.dataset.id,
                            position: idx + 1
                        }));

                    fetch("{{ route('coord.arquivos-adicionais.reorder') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ order }) // A variável 'order' é enviada no corpo
                    })
                        .then(r => {
                            if (!r.ok) { // Verifica se a resposta do servidor foi um erro
                                throw new Error('Erro na resposta do servidor');
                            }
                            return r.json();
                        })
                        .then(json => {
                            if (json.status !== 'ok') {
                                alert('Ocorreu um erro ao salvar a nova ordem dos arquivos.');
                            }
                            // Opcional: mostrar uma mensagem de sucesso
                        })
                        .catch(() => alert('Ocorreu um erro de comunicação ao salvar a nova ordem.'));
                }
            });
        });
    </script>
@endsection
