@extends('coordenador.detalhesEvento')

@section('menu')
<div id="divListarResultados" style="display: block">
    
    <div class="row titulo-detalhes">
        <div class="col-sm-4">
            <h1 class="">Resultados</h1>
        </div>
        <div class="col-sm-8">
            <form class="form-inline">
                <div class="form-group mx-sm-1 mb-2">
                    <select class="form-control" name="area" id="area_trabalho_pesquisa">
                        @if (count($areas) > 0)
                            @foreach ($areas as $area)
                                <option value="{{$area->id}}">{{$area->nome}}</option>
                            @endforeach
                        @else
                            <option value="" selected disabled>-- Nenhuma área cadastrada --</option>
                        @endif
                    </select>
                </div>
                <div class="form-group mx-sm-3 mb-2">
                    <label for="pesquisaTexto" class="sr-only">Nome do trabalho</label>
                    <input type="text" class="form-control" id="pesquisaTexto" name="pesquisaTexto" placeholder="Nome do trabalho" @if (count($areas) == 0) disabled @endif>
                </div>
                <button type="button" class="btn btn-primary mb-2" onclick="pesquisaResultadoTrabalho()" @if (count($areas) == 0) disabled @endif>Pesquisar</button>
            </form>
        </div>
    </div>

    <div class="row resultado_card">
        @foreach($trabalhos as $trabalho)
            <!-- Modal que exibe os resultados -->
            <div class="modal fade bd-example-modal-lg" id="modalResultados{{$trabalho->id}}" tabindex="-1" role="dialog" aria-labelledby="labelmodalResultados{{$trabalho->id}}" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #114048ff; color: white;">
                            <h5 class="modal-title" id="labelmodalResultados{{$trabalho->id}}">Resultado de {{$trabalho->titulo}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;"> 
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @if ($trabalho->pareceres != null && count($trabalho->pareceres) > 0)
                                @for ($i = 0; $i < count($trabalho->pareceres); $i++)
                                    <div id="accordion">
                                        @if ($i == 0)
                                            <div class="card">
                                                <button class="btn" data-toggle="collapse" data-target="#collapse{{$trabalho->pareceres[$i]->id}}" aria-expanded="true" aria-controls="collapse{{$trabalho->pareceres[$i]->id}}">
                                                    <div class="card-header" id="heading{{$trabalho->pareceres[$i]->id}}">
                                                        <h5 class="mb-0">
                                                            Parecer de {{$trabalho->pareceres[$i]->revisor->user->name}}
                                                        </h5>
                                                    </div>
                                                </button>
                                                <div id="collapse{{$trabalho->pareceres[$i]->id}}" class="collapse show" aria-labelledby="heading{{$trabalho->pareceres[$i]->id}}" data-parent="#accordion">
                                                    <div class="card-body">
                                                        <div class="container">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <h4>Dados do revisor</h4>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-4">
                                                                    <label for="especialidade">Area:</label>
                                                                    <p id="especialidade">
                                                                        {{$trabalho->pareceres[$i]->revisor->area->nome}} 
                                                                    </p>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <label for="especialidade">Modalidade:</label>
                                                                    <p id="especialidade">
                                                                        {{$trabalho->pareceres[$i]->revisor->modalidade->nome}} 
                                                                    </p>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <label for="email">E-mail:</label>
                                                                    <p id="email">
                                                                        {{$trabalho->pareceres[$i]->revisor->user->email}}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <h4>Avaliação</h4>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                @foreach ($trabalho->avaliacoes()->where('revisor_id', $trabalho->pareceres[$i]->revisor->id)->get() as $avaliacao)
                                                                    <div class="col-sm-6">
                                                                        <label for="criterio_{{$avaliacao->opcaoCriterio->criterio->id}}">{{$avaliacao->opcaoCriterio->criterio->nome}}:</label>
                                                                        <p id="criterio_{{$avaliacao->opcaoCriterio->criterio->id}}">
                                                                            {{$avaliacao->opcaoCriterio->nome_opcao}}
                                                                        </p>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <label for="parecer_final">Parecer final:</label>
                                                                    <p id="parecer_final">
                                                                        {{$trabalho->pareceres[$i]->resultado}}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <label for="parecer">Parecer:</label>
                                                                    <p id="parecer">
                                                                        {{$trabalho->pareceres[$i]->justificativa}}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="card">
                                                <button class="btn" data-toggle="collapse" data-target="#collapse{{$trabalho->pareceres[$i]->id}}" aria-expanded="true" aria-controls="collapse{{$trabalho->pareceres[$i]->id}}">
                                                    <div class="card-header" id="heading{{$trabalho->pareceres[$i]->id}}">
                                                        <h5 class="mb-0">
                                                            Parecer de {{$trabalho->pareceres[$i]->revisor->user->name}}
                                                        </h5>
                                                    </div>
                                                </button>
                                                <div id="collapse{{$trabalho->pareceres[$i]->id}}" class="collapse" aria-labelledby="heading{{$trabalho->pareceres[$i]->id}}" data-parent="#accordion">
                                                    <div class="card-body">
                                                        <div class="container">
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <label for="especialidade">Especialidade:</label>
                                                                    <p id="especialidade">
                                                                        {{$trabalho->pareceres[$i]->revisor->user->especProfissional}} 
                                                                    </p>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <label for="email">E-mail:</label>
                                                                    <p id="email">
                                                                        {{$trabalho->pareceres[$i]->revisor->user->email}}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <label for="Parecer">Parecer:</label>
                                                                    <p id="Parecer">
                                                                        {{$trabalho->pareceres[$i]->resultado}}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endfor 
                            @else
                                <h4>Nenhum resultado</h4>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <div id="cards_com_trabalhos" class="row">
            @foreach ($trabalhos as $trabalho)
                <div class="card bg-light mb-3" style="width: 20rem;">
                    <div class="card-body">
                        <h5 class="card-title">{{$trabalho->titulo}}</h5>
                        <h6 class="card-subtitle mb-2 text-muted">{{$trabalho->autor->name}}</h6>
                        <label for="area">Área:</label>
                        <p id="area">{{$trabalho->area->nome}}</p>
                        <label for="modalidade">Modalidade:</label>
                        <p id="modalidade">{{$trabalho->modalidade->nome}}</p>
                        <a href="#" class="card-link" data-toggle="modal" data-target="#modalResultados{{$trabalho->id}}">Resultado</a>
                        @if (!(empty($trabalho->arquivo->nome)))
                            <a href="{{route('downloadTrabalho', ['id' => $trabalho->id])}}" class="card-link">Baixar</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div> 
    </div>
</div>
@endsection