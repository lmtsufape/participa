@extends('coordenador.detalhesEvento')


@section('menu')
    <div>
      @error('excluirAtividade')
        @include('componentes.mensagens')
      @enderror
    </div>
    <div id="divListarAreas" style="display: block">
        <div class="row">
            <div class="col-md-12">
                <h1 class="titulo-detalhes">Listar Áreas</h1>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-body">
                      <div class="row justify-content-between">
                        <div class="col-md-6">
                          <h5 class="card-title">Áreas</h5>
                          <h6 class="card-subtitle mb-2 text-muted">Áreas cadastradas no seu evento</h6>
                        </div>
                        <div class="col-md-6 d-flex justify-content-sm-start justify-content-md-end align-items-center">

                          @component('componentes.modal-area', ['evento' => $evento])

                          @endcomponent
                        </div>

                      </div>
                      <p class="card-text">
                        <table class="table table-hover table-responsive-md table-md">
                            <thead>
                            <tr>
                                <th scope="col" style="width:40px;"></th> {{-- coluna vazia para o drag handle --}}
                                <th scope="col">Nome</th>
                                <th scope="col" style="text-align:center">Editar</th>
                                <th scope="col" style="text-align:center">Remover</th>
                            </tr>
                            </thead>
                            <tbody id="areas-tbody">
                            @foreach($areas as $area)
                                <tr data-id="{{ $area->id }}">
                                    {{-- handle de arrastar --}}
                                    <td class="handle text-center" style="cursor: grab;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="m7 2l6 3.9v2.272l-5-3.25v12.08H6V4.922l-5 3.25V5.9zm9 17.08V7h2v12.08l5-3.25v2.272l-6 3.9l-6-3.9V15.83z"/></svg>
                                    </td>

                                    {{-- nome --}}
                                    <td>{{ $area->nome }}</td>

                                    {{-- editar --}}
                                    <td style="text-align:center">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditarArea{{ $area->id }}">
                                            <img src="{{ asset('img/icons/edit-regular.svg') }}" style="width:20px">
                                        </a>
                                    </td>

                                    {{-- remover --}}
                                    <td style="text-align:center">
                                        <form id="formExcluirArea{{ $area->id }}" method="POST" action="{{ route('area.destroy', $area->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalExcluirArea{{ $area->id }}">
                                                <img src="{{ asset('img/icons/trash-alt-regular.svg') }}" style="width:20px; height:auto;" alt="Remover">
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

    @foreach($areas as $area)
      <!-- Modal de editar área -->
      <div class="modal fade" id="modalEditarArea{{$area->id}}" tabindex="-1" role="dialog" aria-labelledby="#label" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header" style="background-color: #114048ff; color: white;">
              <h5 class="modal-title" id="#label">Editar área {{$area->nome}}</h5>
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="color: white;">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form id="formEditarArea{{$area->id}}" action="{{route('area.update', ['id' => $area->id])}}" method="POST">
                @csrf
                <input type="hidden" name="editarAreaId" value="{{$area->id}}">
                <div class="container">
                  <div class="row form-group">
                    <div class="col-md-12" style="margin-top: 20px; margin-bottom: 20px;">
                      <label for="nome_da_área">Nome*</label>
                      <input id="nome_da_área" type="text" class="form-control @error('nome_da_área') is-invalid @enderror" name="nome_da_área" value="@if(old('nome_da_área') != null){{old('nome_da_área')}}@else{{$area->nome}}@endif">

                      @error('nome_da_área')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                      @enderror
                    </div>
                    <div class="col-md-12" style="margin-top: 20px; margin-bottom: 20px;">
                        <label for="resumo">Resumo</label>
                        <textarea id="resumo" class="form-control @error('resumo') is-invalid @enderror" name="resumo" rows="3">@if(old('resumo') != null){{old('resumo')}}@else{{$area->resumo}}@endif</textarea>
                        @error('resumo')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    @if ($evento->is_multilingual)
                      <div class="col-md-12" style="margin-top: 20px; margin-bottom: 20px;">
                        <label for="nome_da_área_en">Nome da Área (Inglês)*</label>
                        <input id="nome_da_área_en" type="text" class="form-control @error('nome_da_área_en') is-invalid @enderror" name="nome_da_área_en" value="@if(old('nome_da_área_en') != null){{old('nome_da_área_en')}}@else{{$area->nome_en}}@endif">
                      </div>
                      <div class="col-md-12" style="margin-top: 20px; margin-bottom: 20px;">
                        <label for="resumo_en">Resumo (Inglês)</label>
                        <textarea id="resumo_en" class="form-control @error('resumo_en') is-invalid @enderror" name="resumo_en" rows="3">@if(old('resumo_en') != null){{old('resumo_en')}}@else{{$area->resumo_en}}@endif</textarea>

                      </div>
                      <div class="col-md-12" style="margin-top: 20px; margin-bottom: 20px;">
                        <label for="nome_da_área_es">Nome da Área (Espanhol)*</label>
                        <input id="nome_da_área_es" type="text" class="form-control @error('nome_da_área_es') is-invalid @enderror" name="nome_da_área_es" value="@if(old('nome_da_área_es') != null){{old('nome_da_área_es')}}@else{{$area->nome_es}}@endif">
                      </div>
                        <div class="col-md-12" style="margin-top: 20px; margin-bottom: 20px;">
                            <label for="resumo_es">Resumo (Espanhol)</label>
                            <textarea id="resumo_es" class="form-control @error('resumo_es') is-invalid @enderror" name="resumo_es" rows="3">@if(old('resumo_es') != null){{old('resumo_es')}}@else{{$area->resumo_es}}@endif</textarea>
                        </div>
                    @endif
                  </div>
                </div>
            </form>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-primary" form="formEditarArea{{$area->id}}">Atualizar</button>
            </div>
          </div>
        </div>
      </div>
      <!-- Modal de exclusão da área -->
      <div class="modal fade" id="modalExcluirArea{{$area->id}}" tabindex="-1" role="dialog" aria-labelledby="#label" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header" style="background-color: #114048ff; color: white;">
              <h5 class="modal-title" id="#label">Confirmação</h5>
              <button type="button" class="close" data-bs-dimdiss="modal" aria-label="Close" style="color: white;">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              Tem certeza que deseja excluir essa área?
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dimdiss="modal">Não</button>
              <button type="submit" class="btn btn-primary" form="formExcluirArea{{$area->id}}">Sim</button>
            </div>
          </div>
        </div>
      </div>
    @endforeach

@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tbody = document.getElementById('areas-tbody');
            new Sortable(tbody, {
                handle: '.handle',
                animation: 150,
                onEnd: function () {
                    // montar array de {id, position}
                    const order = Array.from(tbody.querySelectorAll('tr'))
                        .map((tr, idx) => ({
                            id: tr.dataset.id,
                            position: idx + 1
                        }));
                    // enviar AJAX para a rota
                    fetch("{{ route('areas.reorder') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ order })
                    }).then(res => res.json())
                        .then(json => {
                            if (json.status !== 'ok') {
                                alert('Erro ao salvar ordem');
                            }
                        });
                }
            });
        });
    </script>
@endsection
