@props(['trabalho', 'evento'])
<div class="modal fade" id="modalTrabalho{{ $trabalho->id }}" tabindex="-1" role="dialog"
    aria-labelledby="labelModalTrabalho{{ $trabalho->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #114048ff; color: white;">
                <h5 class="modal-title" id="exampleModalCenterTitle">Trabalho</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="color: white;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    @if (session('success'))
                        <div class="col-sm-12">
                            <div class="alert alert-success">
                                <p>{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="col-sm-12">
                            <div class="alert alert-danger">
                                <p>{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif
                    <div class="col-sm-6">
                        <h5>{{ __('Título') }}</h5>
                        <p id="tituloTrabalho">{{ $trabalho->titulo }}</p>
                    </div>
                    <div class="col-sm-6">
                        <h5>{{ __('Autores') }}</h5>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">E-mail</th>
                                    <th scope="col">{{ __('Nome') }}</th>
                                    <th scope="col">{{ __('Vinculação') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $trabalho->autor->email }}</td>
                                    <td>{{ $trabalho->autor->name }}</td>
                                    <td>{{ __('Autor') }}</td>
                                </tr>
                                @foreach ($trabalho->coautors as $coautor)
                                    @if ($coautor->user->id != $trabalho->autorId)
                                        <tr>
                                            <td>{{ $coautor->user->email }}</td>
                                            <td>{{ $coautor->user->name }}</td>
                                            <td>
                                                {{ __('Coautor') }}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($trabalho->resumo != '')
                    <div class="row justify-content-center">
                        <div class="col-sm-12">
                            <h5>{{ ('Resumo') }}</h5>
                            <p id="resumoTrabalho">{{ $trabalho->resumo }}</p>
                        </div>
                    </div>
                @endif
                @if (count($trabalho->atribuicoes) > 0)
                    <div class="row justify-content-start">
                        <div class="col-sm-12">
                            <h5>{{ __('Avaliadores atribuídos ao trabalho') }}</h5>
                        </div>
                        @foreach ($trabalho->atribuicoes as $i => $revisor)
                            <div class="col-sm-4">
                                <div class="card" style="width: 13.5rem; text-align: center;">
                                    <img class="" src="{{ asset('img/icons/user.png') }}" width="100px" alt="Revisor"
                                        style="position: relative; left: 30%; top: 10px;">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $revisor->user->name }}</h6>
                                        <strong>E-mail</strong>
                                        <p class="card-text">{{ $revisor->user->email }}</p>

                                        @php
                                            $pivot = $trabalho->atribuicoes->where('id', $revisor->id)->first()->pivot;
                                            $status = '';
                                            $statusClass = '';

                                            if ($pivot->confirmacao === true) {
                                                $status = 'Convite aceito';
                                                $statusClass = 'text-success';
                                            } elseif ($pivot->justificativa_recusa) {
                                                $status = 'Convite recusado';
                                                $statusClass = 'text-danger';
                                            } 
                                        @endphp

                                        <p class="card-text">
                                            <strong class="{{ $statusClass }}">{{ $status }}</strong>
                                        </p>

                                        <form action="{{ route('atribuicao.delete', ['id' => $revisor->id]) }}" method="post">
                                            @csrf
                                            <input type="hidden" name="eventoId" value="{{ $evento->id }}">
                                            <input type="hidden" name="trabalhoId" value="{{ $trabalho->id }}">
                                            <button type="submit" class="btn btn-primary button-prevent-multiple-submits"
                                                id="removerRevisorTrabalho">
                                                {{ ('Remover Avaliador') }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                <div class="row">
                    <div class="col-sm-12">
                        <h5>{{ __('Adicionar Avaliador') }}</h5>
                    </div>
                </div>
                <form action="{{ route('distribuicaoManual') }}" method="post">
                    @csrf
                    <input type="hidden" name="trabalhoId" value="{{ $trabalho->id }}">
                    <input type="hidden" name="eventoId" value="{{ $evento->id }}">
                    <div class="row">
                        <div class="col-sm-9">
                            <div class="form-group">
                                <select name="revisorId" class="form-control" id="selectRevisorTrabalho">
                                    <option value="" disabled selected>-- {{ __('E-mail do avaliador') }} --</option>
                                    @foreach ($evento->revisors()->where([['modalidadeId', $trabalho->modalidade->id], ['areaId', $trabalho->area->id]])->get() as $revisor)
                                        @if (
                                            !$trabalho->atribuicoes->contains($revisor) &&
                                                is_null($trabalho->coautors->where('autorId', $revisor->user_id)->first()) &&
                                                $trabalho->autorId != $revisor->user_id)
                                                @php
                                                    $get = $revisor->user->revisorWithCounts()->where('evento_id', $evento->id)->get();
                                                    $processando = $get->sum('processando_count');
                                                    $avaliados = $get->sum('avaliados_count') + $processando;
                                                @endphp
                                            <option value="{{ $revisor->id }}">{{ $revisor->user->name }}
                                                ({{ $revisor->user->email }})
                                                ({{ trans_choice('messages.qtd_revisores', $processando, ['value' => $processando]) }})
                                                ({{ trans_choice('messages.qtd_trabalhos_atribuidos', $avaliados, ['value' => $avaliados]) }})
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <button type="submit" class="btn btn-primary button-prevent-multiple-submits"
                                id="addRevisorTrabalho">
                                <i class="spinner fa fa-spinner fa-spin" style="display: none;"></i>{{ __('Adicionar Avaliador') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
