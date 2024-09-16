@extends('coordenador.detalhesEvento')
@section('menu')
    <div id="divListarAvaliacoes" style="display: block">
        <div class="row ">
            <div class="col-sm-6">
                <h1 class="">Avaliações</h1>
            </div>
        </div>
        <div class="btn-group mb-2" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    Opções
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <a class="dropdown-item"
                        href="{{ route('coord.listarAvaliacoes', ['eventoId' => $evento->id, 'titulo', 'asc', 'rascunho']) }}">
                        Todos
                    </a>
                    <a class="dropdown-item"
                        href="{{ route('coord.listarAvaliacoes', ['eventoId' => $evento->id, 'titulo', 'asc', 'arquivado']) }}">
                        Arquivados
                    </a>
                    <a class="dropdown-item disabled" href="#">
                        Submetidos
                    </a>
                    <a class="dropdown-item disabled" href="#">
                        Aprovados
                    </a>
                    <a class="dropdown-item disabled" href="#">
                        Corrigidos
                    </a>
                    <a class="dropdown-item disabled" href="#">
                        Rascunhos
                    </a>
                </div>
            </div>
        </div>
        <form action="{{route('coord.evento.avisoCorrecao', $evento->id)}}" method="POST" id="avisoCorrecao">
        @csrf
        @foreach ($trabalhosPorModalidade as $trabalhos)
            <div class="row justify-content-center" style="width: 100%;">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            @if (!is_null($trabalhos->first()))
                            <div class="justify-content-between d-flex">
                                <h5 class="card-title">Modalidade: <span
                                        class="card-subtitle mb-2 text-muted">{{ $trabalhos[0]->modalidade->nome }}</span></h5>
                                <button class="btn btn-primary" type="submit" form="avisoCorrecao">Lembrete de envio de versão corrigida do texto</button>
                            </div>
                            @endif
                            @if($errors->any())
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <p>{{$error}}</p>
                                @endforeach
                                </div>
                            @endif

                            @if(session('success'))
                            <div class="alert alert-success" role="alert" align="center">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                                {{session('success')}}
                            </div>
                            @endif

                            @if(session('error'))
                            <div class="alert alert-danger"  role="alert" align="center">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                                {{session('error')}}
                            </div>
                            @endif
                            <div class="row table-trabalhos">
                                <div class="col-sm-12">
                                    <br/>
                                    <table class="table table-hover table-responsive-lg table-sm table-striped">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" onchange="alterarSelecionados(this)"></th>
                                                <th scope="col">
                                                    Trabalho
                                                    <a
                                                        href="{{ route('coord.listarAvaliacoes', ['eventoId' => $evento->id, 'titulo', 'asc']) }}">
                                                        <i class="fas fa-arrow-alt-circle-up"></i>
                                                    </a>
                                                    <a
                                                        href="{{ route('coord.listarAvaliacoes', ['eventoId' => $evento->id, 'titulo', 'desc']) }}">
                                                        <i class="fas fa-arrow-alt-circle-down"></i>
                                                    </a>
                                                </th>
                                                <th scope="col">
                                                    Autor
                                                    <a
                                                        href="{{ route('coord.listarAvaliacoes', ['eventoId' => $evento->id, 'autor', 'asc']) }}">
                                                        <i class="fas fa-arrow-alt-circle-up"></i>
                                                    </a>
                                                    <a
                                                        href="{{ route('coord.listarAvaliacoes', ['eventoId' => $evento->id, 'autor', 'desc']) }}">
                                                        <i class="fas fa-arrow-alt-circle-down"></i>
                                                    </a>
                                                </th>
                                                <th scope="col">
                                                    Área
                                                    <a
                                                        href="{{ route('coord.listarAvaliacoes', ['eventoId' => $evento->id, 'area', 'asc']) }}">
                                                        <i class="fas fa-arrow-alt-circle-up"></i>
                                                    </a>
                                                    <a
                                                        href="{{ route('coord.listarAvaliacoes', ['eventoId' => $evento->id, 'area', 'desc']) }}">
                                                        <i class="fas fa-arrow-alt-circle-down"></i>
                                                    </a>
                                                </th>
                                                <th scope="col">Avaliador(es)</th>
                                                <th scope="col">Status</th>
                                                <th scope="col" style="text-align:center">Parecer</th>
                                                <th scope="col" class="text-center">Encaminhado para o autor</th>
                                                <th scope="col" class="text-center">Lembrete de correção enviado</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($trabalhos as $trabalho)
                                                <td><input type="checkbox" name="trabalhosSelecionados[]" value="{{$trabalho->id}}"></td>
                                                <td>
                                                    @if ($trabalho->arquivo && count($trabalho->arquivo) > 0)
                                                        <a
                                                            href="{{ route('downloadTrabalho', ['id' => $trabalho->id]) }}">
                                                            <span class="d-inline-block"
                                                                class="d-inline-block" tabindex="0" data-toggle="tooltip"
                                                                title="{{ $trabalho->titulo }}">
                                                                {{ $trabalho->titulo }}
                                                            </span>
                                                        </a>
                                                    @else
                                                        <span class="d-inline-block" class="d-inline-block"
                                                            tabindex="0" data-toggle="tooltip"
                                                            title="{{ $trabalho->titulo }}">
                                                            {{ $trabalho->titulo }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>{{ $trabalho->autor->name }}</td>
                                                <td> {{$trabalho->area->nome}} </td>
                                                <td>
                                                    @foreach ($trabalho->atribuicoes as $revisor)
                                                        {{ $revisor->user->name }}
                                                        <br>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @forelse ($trabalho->atribuicoes as $revisor)
                                                        @if($trabalho->avaliado($revisor->user))
                                                            Avaliado
                                                        @else
                                                            Processando
                                                        @endif
                                                        <br>
                                                    @empty
                                                        Sem avaliador
                                                    @endforelse
                                                </td>
                                                <td style="text-align:center">
                                                    @foreach ($trabalho->atribuicoes as $revisor)
                                                        @if($trabalho->avaliado($revisor->user))
                                                            <a
                                                                href="{{ route('coord.visualizarRespostaFormulario', ['eventoId' => $evento->id, 'modalidadeId' => $trabalho->modalidadeId, 'trabalhoId' => $trabalho->id, 'revisorId' => $revisor->id]) }}">
                                                                <img src="{{ asset('img/icons/eye-regular.svg') }}"
                                                                    style="width:20px">
                                                            </a>
                                                            @endif
                                                        <br>
                                                    @endforeach
                                                </td>
                                                <td class="text-center">
                                                    @foreach($trabalho->atribuicoes as $revisor)
                                                        @if($trabalho->avaliado($revisor->user))
                                                            @if ($trabalho->getParecerAtribuicao($revisor->user) != "encaminhado")
                                                                Não
                                                            @else
                                                                Sim
                                                            @endif
                                                        @else
                                                            Processando
                                                        @endif
                                                        <br>
                                                    @endforeach
                                                </td>
                                                <td class="text-center">{{$trabalho->lembrete_enviado ? 'Sim' : 'Não'}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        @endforeach
        </form>
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
