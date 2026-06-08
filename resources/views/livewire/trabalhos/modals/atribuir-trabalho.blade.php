<div class="container">
    @include('componentes.mensagens')

    <div>
        <h5>Título</h5>
        <p>{{ $trabalho->titulo }}</p>
    </div>
    @if ($trabalho->resumo)
        <div class="col-12">
            <h5>Resumo</h5>
            <p class="mb-0">{{ $trabalho->resumo }}</p>
        </div>
    @endif
    <div class="row mb-5">
        <div class="col-md-7">
            <h5 class="mb-2">Adicionar Avaliador</h5>
            <form wire:submit.prevent="addRevisor">
                @csrf
                <div class="input-group flex-nowrap">
                    <select class="form-control" wire:model="revisorId">
                        <option value="" hidden>-- Selecione o avaliador --</option>
                        @foreach ($opcoes as $r)
                            @php
                                $get = $r->user->revisorWithCounts()->where('evento_id', $evento->id)->get();
                                $proc = $get->sum('processando_count');
                                $aval = $get->sum('avaliados_count') + $proc;
                            @endphp
                            <option value="{{ $r->id }}">
                                {{ $r->user->name }} ({{ $r->user->email }})
                                ({{ trans_choice('messages.qtd_revisores', $proc, ['value' => $proc]) }})
                                ({{ trans_choice('messages.qtd_trabalhos_atribuidos', $aval, ['value' => $aval]) }})
                            </option>
                        @endforeach
                    </select>
                    @error('revisorId')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{$message}}</strong>
                        </span>
                    @enderror

                    <button class="btn btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove>Adicionar</span>
                        <span wire:loading class="spinner-border spinner-border-sm"></span>
                    </button>
                </div>
            </form>
            <div class="mt-3">
                <h5 class="mb-2">Avaliadores atribuídos</h5>

                @if ($trabalho->revisores->count())
                    <section class="card rounded shadow">
                        <div class="table-responsive p-1 px-3">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Identificação</th>
                                        <th>Status</th>
                                        <th>Atribuido em:</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($trabalho->revisores as $rev)
                                        @php
                                            $pivot = $trabalho->revisores->where('id', $rev->id)->first()->pivot;
                                            $status = $pivot->confirmacao
                                                ? 'Convite aceito'
                                                : ($pivot->justificativa_recusa
                                                    ? 'Convite recusado'
                                                    : '');
                                            $classe = $pivot->confirmacao
                                                ? 'text-success'
                                                : ($pivot->justificativa_recusa
                                                    ? 'text-danger'
                                                    : '');
                                        @endphp
                                        <tr>
                                            <td>{{ $rev->user->name }}
                                            {{ $rev->user->email }}</td>
                                            <td><strong class="{{ $classe }}">{{ $status }}</td>
                                            <td>
                                                @if ($pivot->created_at)
                                                        {{ $pivot->created_at->format('d/m/Y H:i') }}
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-danger btn-sm" wire:click="removerRevisor({{ $rev->id }})"
                                                    wire:loading.attr="disabled">
                                                    Remover Avaliador
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </section>
                @endif
            </div>


        </div>
        <div class="col-md-5">
            <section>
                <h5 class="mb-2">Autores</h5>
                <div class="card shadow rounded">
                    <table class="table m-0">
                        <thead>
                            <tr>
                                <th>E-mail</th>
                                <th>Nome</th>
                                <th>Vinculação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $trabalho->autor->email }}</td>
                                <td>{{ $trabalho->autor->name }}</td>
                                <td>Autor</td>
                            </tr>
                            @foreach ($trabalho->coautors as $c)
                                @if ($c->user->id != $trabalho->autorId)
                                    <tr>
                                        <td>{{ $c->user->email }}</td>
                                        <td>{{ $c->user->name }}</td>
                                        <td>Coautor</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

    </div>
</div>