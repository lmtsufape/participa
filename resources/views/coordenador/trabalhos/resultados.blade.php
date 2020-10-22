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
                    <select class="form-control" name="area" id="area">
                        @foreach ($areas as $area)
                            <option value="{{$area->id}}">{{$area->nome}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mx-sm-3 mb-2">
                    <label for="pesquisaTexto" class="sr-only">Nome do trabalho</label>
                    <input type="text" class="form-control" id="pesquisaTexto" name="pesquisaTexto" placeholder="Nome do trabalho">
                </div>
                <button type="submit" class="btn btn-primary mb-2">Pesquisar</button>
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
                            <div id="accordion">
                                <div class="card">
                                    <button class="btn" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        <div class="card-header" id="headingOne">
                                            <h5 class="mb-0">
                                                Parecer de Antonio
                                            </h5>
                                        </div>
                                    </button>
                                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body">
                                            <div class="container">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <label for="especialidade">Especialidade:</label>
                                                        <p id="especialidade">
                                                            Bacharel em ciência da computação  
                                                        </p>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label for="email">E-mail:</label>
                                                        <p id="email">
                                                            antonio.alvarez@gmail.com 
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label for="Parecer">Parecer:</label>
                                                        <p id="Parecer" style="text-align: justify;">
                                                            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                            @endif
                        </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
                </div>
            </div>
            <div class="card bg-light mb-3" style="width: 20rem;">
                <div class="card-body">
                    <h5 class="card-title">{{$trabalho->titulo}}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">{{$trabalho->autor->name}}</h6>
                    <label for="area">Área:</label>
                    <p id="area">{{$trabalho->area->nome}}</p>
                    <label for="modalidade">Modalidade:</label>
                    <p id="modalidade">{{$trabalho->modalidade->nome}}</p>
                    {{-- @if ($trabalho->resumo != null && $trabalho->resumo != "") 
                        <label for="resumo">Resumo:</label>
                        <p id="resumo" style="text-align: justify;">
                            {{$trabalho->resumo}}
                        </p>
                    @endif --}}
                    {{-- @if ($trabalho->avaliado != "nao") --}}
                        <a href="#" class="card-link" data-toggle="modal" data-target="#modalResultados{{$trabalho->id}}">Resultado</a>
                    {{-- @endif --}}
                    <a href="#" class="card-link">Baixar</a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection