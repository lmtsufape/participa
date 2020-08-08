@extends('coordenador.detalhesEvento')

@section('menu')
    <div id="divListarComissao" class="comissao" style="display: block">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="titulo-detalhes">Listar Comissão</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">Comissão</h5>
                      <h6 class="card-subtitle mb-2 text-muted">Usuários cadastrados na comissão do seu evento.</h6>
                      <p class="card-text">
                        <table class="table table-hover table-responsive-lg table-sm">
                            <thead>
                                <th>
                                    <th>Nome</th>
                                    <th>Especialidade</th>
                                    <th>Celular</th>
                                    <th>E-mail</th>
                                    <th>Direção</th>
                                </th>
                            </thead>
                                @foreach ($users as $user)
                                    <tbody>

                                        <th>
                                          @if (isset($user->name))
                                            <td>{{$user->name}}</td>
                                            <td>{{$user->especProfissional}}</td>
                                            <td>{{$user->celular}}</td>
                                            <td>{{$user->email}}</td>
                                            @if ($evento->coordComissaoId == $user->id)
                                              <td>Coordenador</td>
                                            @endif
                                          @else
                                            <td>Usuário temporário - Sem nome</td>
                                            <td>Usuário temporário - Sem Especialidade</td>
                                            <td>Usuário temporário - Sem Celular</td>
                                            <td>{{$user->email}}</td>
                                            @if ($evento->coordComissaoId == $user->id)
                                              <td>Coordenador</td>
                                            @endif
                                          @endif
                                        </th>
                                    </tbody>
                                @endforeach
                        </table>
                      </p>
                    </div>
                  </div>
            </div>
        </div>


        {{-- tabela membros comissão --}}
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-responsive-lg table-hover">

                </table>
            </div>
        </div>
    </div>{{-- End Listar Comissão --}}
@endsection
