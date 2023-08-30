@extends('coordenador.detalhesEvento')
@section('menu')
<div id="" style="display: block">
    <div class="row">
        <div class="col-md-12">
            <h1 class="titulo-detalhes">Listar Inscritos</h1>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                  <div class="row justify-content-between">
                    <div class="col-md-6">
                      <h5 class="card-title">Inscrições</h5>
                      <h6 class="card-subtitle mb-2 text-muted">Inscritos no evento {{$evento->nome}}</h6>
                      <h6 class="card-subtitle mb-2 text-muted">Obs.: ao exportar o arquivo csv, usar o delimitador , (vírgula) para abrir o arquivo</h6>
                    </div>
                    <div class="col-md-6 d-flex justify-content-sm-start justify-content-md-end align-items-center">
                        <a href="{{route('evento.downloadInscritos', $evento)}}" class="btn btn-primary float-md-right">Exportar .csv</a>
                    </div>

                  </div>
                  <p class="card-text">
                    <table class="table table-hover table-responsive-lg table-sm" style="position: relative;">
                        <thead>
                            <th>
                                <th>#</th>
                                @if ($evento->subeventos->count() > 0)
                                    <th>Evento/Subevento</th>
                                @endif
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Cidade</th>
                                <th>Estado</th>
                                <th>Aprovada</th>
                            </th>
                        </thead>
                        @foreach ($inscricoes as $inscricao)
                            <tbody>
                                <th>
                                    <td data-toggle="modal" data-target="#modal-listar-campos-formulario-{{$inscricao->id}}">{{$loop->iteration}}</td>
                                    @if ($evento->subeventos->count() > 0)
                                        <td data-toggle="modal" data-target="#modal-listar-campos-formulario-{{$inscricao->id}}">{{$inscricao->evento->nome}}</td>
                                    @endif
                                    <td data-toggle="modal" data-target="#modal-listar-campos-formulario-{{$inscricao->id}}">{{$inscricao->user->name}}</td>
                                    <td data-toggle="modal" data-target="#modal-listar-campos-formulario-{{$inscricao->id}}">{{$inscricao->user->email}}</td>
                                    <td data-toggle="modal" data-target="#modal-listar-campos-formulario-{{$inscricao->id}}">{{$inscricao->user->endereco ? $inscricao->user->endereco->cidade : 'Endereço não cadastrado'}}</td>
                                    <td data-toggle="modal" data-target="#modal-listar-campos-formulario-{{$inscricao->id}}">{{$inscricao->user->endereco ? $inscricao->user->endereco->uf : 'Endereço não cadastrado'}}</td>
                                    <td data-toggle="modal" data-target="#modal-listar-campos-formulario-{{$inscricao->id}}">{{$inscricao->finalizada ? 'Sim' : 'Não'}}</td>
                                </th>
                            </tbody>
                        @endforeach
                    </table>
                  </p>
                </div>
              </div>
        </div>
    </div>
</div>

@foreach ($inscricoes as $inscricao)
<div class="modal fade" id="modal-listar-campos-formulario-{{$inscricao->id}}" tabindex="-1" role="dialog" aria-labelledby="#label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #114048ff; color: white;">
                <h5 class="modal-title">Dados do inscrito</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if ($inscricao->categoria)
                    <div class="form-group">
                        <label class="text-center">Categoria</label>
                        <input type="text" class="form-control" value="{{$inscricao->categoria->nome}}" disabled>
                    </div>
                @endif
                @forelse ($inscricao->camposPreenchidos as $campo)
                    @if($campo->tipo == "endereco")
                        @php
                            $endereco = App\Models\Submissao\Endereco::find($campo->pivot->valor)
                        @endphp
                        <label>{{$campo->titulo}}</label>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="text-center">CEP</label>
                                    <input type="text" class="form-control" value="{{$endereco->cep}}" disabled>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label class="text-center">Bairro</label>
                                    <input type="text" class="form-control" value="{{$endereco->bairro}}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="text-center">Rua</label>
                                    <input type="text" class="form-control" value="{{$endereco->rua}}" disabled>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label class="text-center">Complemento</label>
                                    <input type="text" class="form-control" value="{{$endereco->complemento}}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="text-center">Cidade</label>
                                    <input type="text" class="form-control" value="{{$endereco->cidade}}" disabled>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label class="text-center">UF</label>
                                    <input type="text" class="form-control" value="{{$endereco->uf}}" disabled>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label class="text-center">Número</label>
                                    <input type="text" class="form-control" value="{{$endereco->numero}}" disabled>
                                </div>
                            </div>
                        </div>
                    @elseif($campo->tipo == "file")
                        <div class="form-group">
                            <a class="btn btn-primary baixar-campo-form" href="{{route('download.arquivo.inscricao', [$inscricao->id, $campo->id])}}" role="button">
                                <li>
                                    <img src="{{ asset('img/icons/file-download-solid.svg') }}" alt="Baixar arquivo">
                                    {{$campo->titulo}}
                                </li>
                            </a>
                        </div>
                    @else
                        <div class="form-group">
                            <label class="text-center">{{$campo->titulo}}</label>
                            <input type="text" class="form-control" value="{{$campo->pivot->valor}}" disabled>
                        </div>
                    @endif
                @empty
                @endforelse
            </div>
            <div class="modal-footer">
                @if ($evento->formEvento->modvalidarinscricao)
                    <form action="{{route('coord.inscricoes.aprovar', ['inscricao' => $inscricao])}}" method="post">
                        @csrf
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Aprovar inscrição</button>
                    </form>
                @else
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                @endif
            </div>
        </div>
    </div>
</div>

@endforeach
@endsection
