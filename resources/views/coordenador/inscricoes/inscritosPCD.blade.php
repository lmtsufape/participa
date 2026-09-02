@extends('coordenador.detalhesEvento')
@section('menu')

@if (session('message'))
    <div class="alert alert-{{ session('class', 'success') }} mt-3">{{ session('message') }}</div>
@endif

<div id="div-listar-pcd" style="display: block">
    <div class="row">
        <div class="col-md-12">
            <h1 class="titulo-detalhes">Listar Inscrições PCD</h1>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                  <div class="row justify-content-between">
                    <div class="col-sm-6">
                      <h5 class="card-title">Solicitações PCD</h5>
                      <h6 class="card-subtitle mb-2 text-muted">Solicitações de inscrição PCD para o evento {{$evento->nome}}</h6>
                    </div>
                  </div>

                    <div class="table-responsive mt-3">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nome</th>
                                    <th scope="col">E-mail</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" class="text-center">Comprovante</th>
                                    <th scope="col" class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($solicitacoes as $solicitacao)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$solicitacao->user->name}}</td>
                                        <td>{{$solicitacao->user->email}}</td>
                                        <td>
                                            @if($solicitacao->status == 'pendente')
                                                <span class="badge bg-warning text-dark">Pendente</span>
                                            @elseif($solicitacao->status == 'aprovado')
                                                <span class="badge bg-success">Aprovado</span>
                                            @else
                                                <span class="badge bg-danger">Rejeitado</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('coord.inscricao.pcd.download', $solicitacao) }}" class="btn btn-sm btn-light" title="Baixar Comprovante">
                                                <img src="{{asset('img/icons/file-download-solid.svg')}}" style="width:15px">
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            @if($solicitacao->status === 'pendente')
                                                {{-- Formulário de Aprovação SEM confirmação --}}
                                                <form action="{{ route('coord.inscricao.pcd.aprovar', $solicitacao) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" title="Aprovar Solicitação">Aprovar</button>
                                                </form>
                                                {{-- Formulário de Rejeição SEM confirmação --}}
                                                <form action="{{ route('coord.inscricao.pcd.rejeitar', $solicitacao) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Rejeitar Solicitação">Rejeitar</button>
                                                </form>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Nenhuma solicitação PCD encontrada.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
              </div>
        </div>
    </div>
</div>
@endsection
