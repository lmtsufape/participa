@extends('layouts.app')

@section('sidebar')


@endsection
@section('content')
    <div class="container">

        <div class="row ">
            <div class="col-sm-6">
                <h1 class="">Trabalhos Validados</h1>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('coord.listarCorrecoes', ['eventoId' => $evento->id]) }}">
                    <div class="row">
                        <div class="col-md-10">
                            <label for="titulo" class="form-label">Buscar por Título</label>
                            <input type="text" class="form-control" name="titulo" value="{{ request('titulo') }}" placeholder="Digite o título do trabalho...">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" id="busca" class="btn btn-primary w-100">Buscar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @foreach ($modalidades as $modalidade)
            @if(count($modalidade->trabalho) > 0)
                <div class="row justify-content-center" style="width: 100%;">
                    <div class="col-sm-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="justify-content-between d-flex">
                                    <div>
                                        <h5 class="card-title">Modalidade: <span class="card-subtitle mb-2 text-muted" >{{$modalidade->nome}}</span></h5>
                                        @if ($modalidade->inicioValidacao && $modalidade->fimValidacao)
                                            <h5 class="card-title">Validação: <span class="card-subtitle mb-2 text-muted" >{{date("d/m/Y H:i", strtotime($modalidade->inicioValidacao))}} - {{date("d/m/Y H:i",strtotime($modalidade->fimValidacao))}}</span></h5>
                                        @else
                                            <h5 class="card-title">Validação: <span class="card-subtitle mb-2 text-muted" >não haverá</span></h5>
                                        @endif
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <input type="hidden" name="eventoId" value="{{$evento->id}}">
                                    <br>
                                    <table class="table table-hover table-responsive-lg table-sm table-striped">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" onchange="alterarSelecionados(this)"></th>
                                                <th scope="col">Trabalho inicial</th>
                                                <th scope="col">Trabalho revisado</th>
                                                <th scope="col">Autor</th>
                                                <th scope="col">
                                                    Data de Envio
                                                    <a href="{{ route('coord.listarCorrecoes', array_merge(request()->query(), ['eventoId' => $evento->id, 'column' => 'data', 'direction' => 'asc'])) }}">
                                                        <i class="fas fa-arrow-alt-circle-up"></i>
                                                    </a>
                                                    <a href="{{ route('coord.listarCorrecoes', array_merge(request()->query(), ['eventoId' => $evento->id, 'column' => 'data', 'direction' => 'desc'])) }}">
                                                        <i class="fas fa-arrow-alt-circle-down"></i>
                                                    </a>
                                                </th>
                                                <th scope="col">Parecer</th>
                                                <th scope="col" class="text-center">Validação</th>
                                                <th scope="col" style="text-align:center;">Ações</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($modalidade->trabalho as $trabalho)
                                            <tr>
                                                <td><input type="checkbox" name="trabalhosSelecionados[]" value="{{$trabalho->id}}"></td>
                                                <td>
                                                    @if ($trabalho->arquivo)
                                                        <a href="{{route('downloadTrabalho', ['id' => $trabalho->id])}}">
                                                            <span class="d-inline-block text-truncate" class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="{{$trabalho->titulo}}" style="max-width: 150px;">
                                                                {{$trabalho->titulo}}
                                                            </span>
                                                        </a>
                                                    @else
                                                        <span class="d-inline-block text-truncate" class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="{{$trabalho->titulo}}" style="max-width: 150px;">
                                                            {{$trabalho->titulo}}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($trabalho->arquivoCorrecao)
                                                        <a href="{{route('downloadCorrecao', ['id' => $trabalho->id])}}">
                                                            <span class="d-inline-block text-truncate" class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="{{$trabalho->titulo}}" style="max-width: 150px;">
                                                                {{$trabalho->titulo}}
                                                            </span>
                                                        </a>
                                                    @else
                                                        <span class="d-inline-block text-truncate" class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="Aguardando envio da correção" style="max-width: 150px;">
                                                            Aguardando envio
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>{{$trabalho->autor->name}}</td>

                                                <td>
                                                    @if ($trabalho->arquivoCorrecao)
                                                        {{ date("d/m/Y H:i", strtotime($trabalho->arquivoCorrecao->created_at) ) }}
                                                    @endif
                                                </td>

                                                <td style="text-align:center">
                                                    @foreach ($trabalho->atribuicoes as $revisor)
                                                        <a href="{{route('coord.visualizarRespostaFormulario', ['eventoId' => $evento->id, 'modalidadeId' => $trabalho->modalidadeId, 'trabalhoId' => $trabalho->id, 'revisorId' => $revisor->id])}}">
                                                            <img src="{{asset('img/icons/eye-regular.svg')}}" style="width:20px">
                                                        </a>
                                                        <br>
                                                    @endforeach
                                                </td>
                                                <td class="text-center">
                                                    @switch($trabalho->avaliado)
                                                        @case('corrigido')
                                                            Finalizado: aprovado
                                                            @break
                                                        @case('corrigido_parcialmente')
                                                            Finalizado: aprovado parcialmente
                                                            @break
                                                        @case('nao_corrigido')
                                                            Finalizado: reprovado
                                                            @break
                                                        @default
                                                            Em análise
                                                    @endswitch
                                                </td>
                                                <td style="text-align:center">
                                                    <div class="d-flex justify-content-center gap-3">
                                                        <button class="btn btn-success btn-sm" name="btn-avaliacao-aprovar-{{$trabalho->id}}"
                                                            data-bs-toggle="modal" data-bs-target="#avaliacao-aprovar-{{$trabalho->id}}" @disabled($trabalho->aprovado === true)>
                                                            Aprovar Trabalho
                                                        </button>

                                                        <button class="btn btn-danger btn-sm" name="btn-avaliacao-reprovar-{{$trabalho->id}}"
                                                            data-bs-toggle="modal" data-bs-target="#avaliacao-reprovar-{{$trabalho->id}}" @disabled($trabalho->aprovado === false)>
                                                            Reprovar Trabalho
                                                        </button>
                                                        @push('modais')
                                                            @include('coordenador.trabalhos.avaliacao-modal', ['trabalho' => $trabalho, 'valor' => 'true', 'descricao' => 'aprovar'])
                                                            @include('coordenador.trabalhos.avaliacao-modal', ['trabalho' => $trabalho, 'valor' => 'false', 'descricao' => 'reprovar'])
                                                        @endpush
                                                    </div>
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
        @endforeach

        @if($trabalhos->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $trabalhos->links() }}
            </div>
        @endif
    </div>
    @stack('modais')

    @endsection

@section('script')
<script>
    function alterarSelecionados(source) {
        let checkboxes = document.querySelectorAll('input[name="trabalhosSelecionados[]"]');
        checkboxes.forEach(checkbox => checkbox.checked = source.checked);
    }
</script>
@endsection