@extends('coordenador.detalhesEvento')
@section('menu')

@if (session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@elseif ( session('error_message'))
<div class="alert alert-danger">
        {{ session('error_message') }}
    </div>
@endif

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
                      <!--<h6 class="card-subtitle mb-2 text-muted">Obs.: ao exportar o arquivo csv, usar o delimitador , (vírgula) para abrir o arquivo</h6>-->
                    </div>
                    <div class="col-md-6 d-flex gap-2 flex-column align-items-end">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-acoes">
                           Ações
                        </button>
                    </div>
                </div>
                <!-- Formulário de Busca -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <form method="GET" action="{{ route('inscricao.inscritos', $evento) }}" class="row g-3">
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="nome" placeholder="Buscar por nome..." value="{{ request('nome') }}">
                            </div>
                            <div class="col-md-3">
                                <input type="email" class="form-control" name="email" placeholder="Buscar por email..." value="{{ request('email') }}">
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" name="status">
                                    <option value="">Todos os status</option>
                                    <option value="finalizada" {{ request('status') === 'finalizada' ? 'selected' : '' }}>Finalizada</option>
                                    <option value="pendente" {{ request('status') === 'pendente' ? 'selected' : '' }}>Pendente</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Buscar</button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('inscricao.inscritos', $evento) }}" class="btn btn-outline-secondary w-100">Limpar</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Informações dos resultados -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <strong>Total de inscritos encontrados:</strong> {{ $inscricoes->total() }}
                            @if(request('nome') || request('email') || request('status'))
                                <span class="text-muted">(com filtros aplicados)</span>
                            @endif
                            <span class="float-end">
                                <small class="text-muted">Página {{ $inscricoes->currentPage() }} de {{ $inscricoes->lastPage() }}</small>
                            </span>
                        </div>
                    </div>
                </div>

                    @include('coordenador.inscricoes.inscrever_participante')

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
                                <th>Categoria</th>
                                <th scope="col">Valor</th>
                                <th>Status</th>
                                <th>Aprovada</th>
                                <th>Alimentação</th>
                                <th>Recibo</th>
                                <th></th>
                            </th>
                        </thead>
                        @foreach ($inscricoes as $inscricao)
                            <tbody>
                                <th>
                                    <td data-bs-toggle="modal" data-bs-target="#modal-listar-campos-formulario-{{$inscricao->id}}">{{$loop->iteration}}</td>
                                    @if ($evento->subeventos->count() > 0)
                                        <td data-bs-toggle="modal" data-bs-target="#modal-listar-campos-formulario-{{$inscricao->id}}">{{$inscricao->evento->nome}}</td>
                                    @endif
                                    <td data-bs-toggle="modal" data-bs-target="#modal-listar-campos-formulario-{{$inscricao->id}}">{{$inscricao->user->name}}</td>
                                    <td data-bs-toggle="modal" data-bs-target="#modal-listar-campos-formulario-{{$inscricao->id}}">{{$inscricao->user->email}}</td>
                                    <td data-bs-toggle="modal" data-bs-target="#modal-listar-campos-formulario-{{$inscricao->id}}">{{$inscricao->categoria?->nome ?? 'N/A'}}</td>
                                    <td data-bs-toggle="modal" data-bs-target="#modal-listar-campos-formulario-{{$inscricao->id}}">R$ {{ $inscricao->categoria ? number_format($inscricao->categoria->valor_total, 2, ',', '.') : 'N/A' }}</td>
                                    <td data-bs-toggle="modal" data-bs-target="#modal-listar-campos-formulario-{{$inscricao->id}}">
                                        @if($inscricao->finalizada == true)
                                            Inscrito
                                        @else
                                            Pré-inscrito
                                        @endif
                                    </td>
                                    <td data-bs-toggle="modal" data-bs-target="#modal-listar-campos-formulario-{{$inscricao->id}}">{{$inscricao->finalizada ? 'Sim' : 'Não'}}</td>
                                    @if ($inscricao->alimentacao)
                                    <td data-bs-toggle="modal" data-bs-target="#modal-listar-campos-formulario-{{$inscricao->id}}">Sim</td>
                                    @else
                                    <td data-bs-toggle="modal" data-bs-target="#modal-listar-campos-formulario-{{$inscricao->id}}">Não</td>
                                    @endif
                                    <td data-bs-toggle="modal" data-bs-target="#modal-listar-campos-formulario-{{$inscricao->id}}"><img src="{{asset('img/icons/eye-regular.svg')}}" alt="" style="width: 14px; fill: #000 !important;"></td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            @if($inscricao->finalizada)
                                                <a href="{{ route('inscricao.recibo', ['inscricao' => $inscricao->id]) }}" class="btn btn-sm btn-success" title="Gerar recibo">
                                                    <img src="{{asset('img/icons/file-pdf-solid.svg')}}" alt="Gerar recibo" style="width: 14px;">
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </th>
                            </tbody>
                        @endforeach
                    </table>

                    <!-- Paginação -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $inscricoes->links() }}
                    </div>

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
            <div class="modal-header" style="background-color: #114048ff; color: white; display: flex; justify-content: space-between; align-items: center;">
                <h5 class="modal-title">Dados do inscrito</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if ($inscricao->categoria)
                <div class="form-group">
                    <label class="text-center">Categoria</label>
                    <input type="text" class="form-control" value="{{$inscricao->categoria?->nome ?? '–' }}" disabled>
                    <div class="col-md-4">
                        <label class="text-center">Valor da Inscrição</label>
                        <input type="text" class="form-control" value="R$ {{ number_format($inscricao->categoria->valor_total, 2, ',', '.') }}" disabled>
                    </div>
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
                    @php
                        $perfilIdentitario = \App\Models\PerfilIdentitario::query()
                            ->where('userId', $inscricao->user->id)
                            ->first();
                    @endphp
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="">Nome completo</label>
                        <input type="text" class="form-control" value="{{ $inscricao->user->name }}" disabled>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Nome social</label>
                        <input type="text" class="form-control" value="{{ $perfilIdentitario->nomeSocial ?? 'Não informado' }}" disabled>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Data de nascimento</label>
                        <input type="text" class="form-control"
                               value="{{ optional($perfilIdentitario)->dataNascimento ? \Carbon\Carbon::parse($perfilIdentitario->dataNascimento)->format('d/m/Y') : 'não informado' }}"
                               disabled>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Gênero</label>
                        <input type="text" class="form-control"
                               value="{{ isset($perfilIdentitario) && $perfilIdentitario->genero && $perfilIdentitario->genero !== 'outro' ? ucfirst($perfilIdentitario->genero) : (isset($perfilIdentitario) && $perfilIdentitario->outroGenero ? ucfirst($perfilIdentitario->outroGenero) : 'Não informado') }}"
                               disabled>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Raça</label>
                        <input type="text" class="form-control"
                            value="{{ isset($perfilIdentitario, $perfilIdentitario->raca) && is_array($perfilIdentitario->raca) && !empty($perfilIdentitario->raca) ?
                                        collect($perfilIdentitario->raca)->map(function($raca) use ($perfilIdentitario) {
                                            if ($raca === 'outra_raca' && !empty($perfilIdentitario->outraRaca)) {
                                                return 'Outra: ' . ucfirst($perfilIdentitario->outraRaca);
                                            }
                                            return ucfirst(str_replace('_raca', '', str_replace('_', ' ', $raca)));
                                        })->implode(', ')
                                        : 'Não informado' }}"
                            disabled>
                    </div>

                    @if(isset($perfilIdentitario) && ($perfilIdentitario->comunidadeTradicional === true || $perfilIdentitario->comunidadeTradicional === 'true'))
                        <div class="form-group col-md-6">
                            <label for="">Comunidade tradicional</label>
                            <input type="text" class="form-control"
                                   value="{{ $perfilIdentitario->nomeComunidadeTradicional ?? 'Não informado' }}"
                                   disabled>
                        </div>
                    @endif

                    <div class="form-group col-md-6">
                        <label for="">LGBTIA+</label>
                        <input type="text" class="form-control"
                               value="{{ isset($perfilIdentitario) && ($perfilIdentitario->lgbtqia === true || $perfilIdentitario->lgbtqia === 'true') ? 'Sim' : 'Não' }}"
                               disabled>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Pessoa idosa ou com deficiência</label>
                        <input type="text" class="form-control"
                               value="{{ isset($perfilIdentitario) && ($perfilIdentitario->deficienciaIdoso === true || $perfilIdentitario->deficienciaIdoso === 'true') ? 'Sim' : 'Não' }}"
                               disabled>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Necessidades Especiais</label>
                        <input type="text" class="form-control"
                               value="{{ isset($perfilIdentitario) && !empty($perfilIdentitario->necessidadesEspeciais) && is_array($perfilIdentitario->necessidadesEspeciais) ? collect($perfilIdentitario->necessidadesEspeciais)->map(fn($item) => ucfirst($item))->implode(', ') : 'Não informado' }}"
                               disabled>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Associado à ABA</label>
                        <input type="text" class="form-control"
                               value="{{ isset($perfilIdentitario) && ($perfilIdentitario->associadoAba === true || $perfilIdentitario->associadoAba === 'true') ? 'Sim' : 'Não' }}"
                               disabled>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">
                            @if ($inscricao->user->cpf) CPF
                            @elseif ($inscricao->user->cnpj) CNPJ
                            @elseif ($inscricao->user->passaporte) Passaporte
                            @endif
                        </label>
                        <input type="text" class="form-control" disabled value="@if($inscricao->user->cpf){{$inscricao->user->cpf}}@elseif ($inscricao->user->cnpj){{$inscricao->user->cnpj}}@elseif ($inscricao->user->passaporte){{$inscricao->user->passaporte}}@endif">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="">Instituição de Ensino</label>
                        <input type="text" class="form-control" value="{{ $inscricao->user->instituicao }}" disabled>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Celular</label>
                        <input type="text" class="form-control" disabled value="{{ $inscricao->user->celular }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="cep" >{{ __('CEP') }}</label>
                    <input id="cep" type="text" class="form-control" value="{{$inscricao->user->endereco?->cep}}" disabled>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="rua" >{{ __('Rua') }}</label>
                        <input id="rua" type="text" class="form-control " name="rua" value="{{$inscricao->user->endereco?->rua}}" disabled>
                    </div>

                    <div class="form-group col-md-2">
                        <label for="numero" >{{ __('Número') }}</label>
                        <input id="numero" type="number" class="form-control " name="numero" value="{{$inscricao->user->endereco?->numero}}"  disabled>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="bairro" >{{ __('Bairro') }}</label>
                        <input id="bairro" type="text" class="form-control " name="bairro" value="{{$inscricao->user->endereco?->bairro}}" disabled>
                    </div>
                </div>


                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="" >{{ __('Cidade') }}</label>
                        <input id="" type="text" class="form-control " value="{{$inscricao->user->endereco?->cidade}}" disabled>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="" >{{ __('Complemento') }}</label>
                        <input id="" type="text" class="form-control apenasLetras" value="{{$inscricao->user->endereco?->complemento}}" disabled>
                    </div>
                    <div class="form-group col-md-4" >
                        <label for="" >{{ __('UF') }}</label>
                        <select class="form-control"  disabled>
                            <option @if($inscricao->user->endereco?->uf == 'AC') selected @endif value="AC">Acre</option>
                            <option @if($inscricao->user->endereco?->uf == 'AL') selected @endif value="AL">Alagoas</option>
                            <option @if($inscricao->user->endereco?->uf == 'AP') selected @endif value="AP">Amapá</option>
                            <option @if($inscricao->user->endereco?->uf == 'AM') selected @endif value="AM">Amazonas</option>
                            <option @if($inscricao->user->endereco?->uf == 'BA') selected @endif value="BA">Bahia</option>
                            <option @if($inscricao->user->endereco?->uf == 'CE') selected @endif value="CE">Ceará</option>
                            <option @if($inscricao->user->endereco?->uf == 'DF') selected @endif value="DF">Distrito Federal</option>
                            <option @if($inscricao->user->endereco?->uf == 'ES') selected @endif value="ES">Espírito Santo</option>
                            <option @if($inscricao->user->endereco?->uf == 'GO') selected @endif value="GO">Goiás</option>
                            <option @if($inscricao->user->endereco?->uf == 'MA') selected @endif value="MA">Maranhão</option>
                            <option @if($inscricao->user->endereco?->uf == 'MT') selected @endif value="MT">Mato Grosso</option>
                            <option @if($inscricao->user->endereco?->uf == 'MS') selected @endif value="MS">Mato Grosso do Sul</option>
                            <option @if($inscricao->user->endereco?->uf == 'MG') selected @endif value="MG">Minas Gerais</option>
                            <option @if($inscricao->user->endereco?->uf == 'PA') selected @endif value="PA">Pará</option>
                            <option @if($inscricao->user->endereco?->uf == 'PB') selected @endif value="PB">Paraíba</option>
                            <option @if($inscricao->user->endereco?->uf == 'PR') selected @endif value="PR">Paraná</option>
                            <option @if($inscricao->user->endereco?->uf == 'PE') selected @endif value="PE">Pernambuco</option>
                            <option @if($inscricao->user->endereco?->uf == 'PI') selected @endif value="PI">Piauí</option>
                            <option @if($inscricao->user->endereco?->uf == 'RJ') selected @endif value="RJ">Rio de Janeiro</option>
                            <option @if($inscricao->user->endereco?->uf == 'RN') selected @endif value="RN">Rio Grande do Norte</option>
                            <option @if($inscricao->user->endereco?->uf == 'RS') selected @endif value="RS">Rio Grande do Sul</option>
                            <option @if($inscricao->user->endereco?->uf == 'RO') selected @endif value="RO">Rondônia</option>
                            <option @if($inscricao->user->endereco?->uf == 'RR') selected @endif value="RR">Roraima</option>
                            <option @if($inscricao->user->endereco?->uf == 'SC') selected @endif value="SC">Santa Catarina</option>
                            <option @if($inscricao->user->endereco?->uf == 'SP') selected @endif value="SP">São Paulo</option>
                            <option @if($inscricao->user->endereco?->uf == 'SE') selected @endif value="SE">Sergipe</option>
                            <option @if($inscricao->user->endereco?->uf == 'TO') selected @endif value="TO">Tocantins</option>
                        </select>
                    </div>
                </div>

            </div>
            <div class="modal-footer justify-content-between">
                <div>
                    <form action="{{route('inscricao.cancelar', ['inscricao' => $inscricao])}}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Cancelar inscrição</button>
                    </form>
                </div>
                <div>
                    @if ($evento->formEvento->modvalidarinscricao)
                    <form action="{{route('coord.inscricoes.aprovar', ['inscricao' => $inscricao])}}" method="post">
                        @csrf
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Aprovar inscrição</button>
                    </form>
                    @else
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endforeach


<!-- Modal Gerenciar Alimentação -->
<div class="modal fade" id="modal-gerenciar-alimentacao" tabindex="-1" role="dialog" aria-labelledby="modal-gerenciar-alimentacao-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #114048ff; color: white;">
                <h5 class="modal-title" id="modal-gerenciar-alimentacao-label">Adicionar Alimentação - {{ $evento->nome }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('inscricao.gerenciarAlimentacao', ['evento_id' => $evento->id]) }}" method="POST">
                @csrf
                <div class="modal-body" x-data="{tipo: 'email', email: '', cpf: ''}">
                    <div class="mb-3">
                        <label class="form-label">Tipo de identificação:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="identificador" id="alimentacao-email-radio" value="email" x-model="tipo">
                            <label class="form-check-label" for="alimentacao-email-radio">
                                E-mail
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="identificador" id="alimentacao-cpf-radio" value="cpf" x-model="tipo">
                            <label class="form-check-label" for="alimentacao-cpf-radio">
                                CPF
                            </label>
                        </div>
                    </div>

                    <div class="mb-3" x-show="tipo === 'email'">
                        <label for="alimentacao-email" class="form-label">E-mail do participante:</label>
                        <input type="email" class="form-control" id="alimentacao-email" name="email" x-model="email" placeholder="Digite o e-mail do participante" x-bind:required="tipo === 'email'">
                    </div>
                    <div class="mb-3" x-show="tipo === 'cpf'">
                        <label for="alimentacao-cpf" class="form-label">CPF do participante:</label>
                        <input type="text" class="form-control cpf-mask" id="alimentacao-cpf" name="cpf" x-model="cpf" placeholder="Digite o CPF do participante" x-bind:required="tipo === 'cpf'">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning button-prevent-multiple-submits">
                        Adicionar Alimentação
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Ações -->
<div class="modal fade" id="modal-acoes" tabindex="-1" role="dialog" aria-labelledby="modalAcoesLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #114048ff; color: white;">
                <h5 class="modal-title" id="modalAcoesLabel">Ações Disponíveis</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <img src="{{asset('img/icons/user-plus-black-solid.svg')}}" alt="Cadastro Individual" style="width: 48px; height: 48px;">
                                </div>
                                <h5 class="card-title">Cadastro Individual</h5>
                                <p class="card-text">Cadastrar um usuário individualmente</p>
                                <a href="{{ route('admin.cadastrarUsuario') }}" class="btn btn-outline-primary w-100">
                                    <img src="{{asset('img/icons/user-plus-solid.svg')}}" alt="Cadastro Individual" style="width: 16px; height: 16px;" class="me-2">Cadastro individual de usuário
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <img src="{{asset('img/icons/users-solid-full.svg')}}" alt="Cadastro em Massa" style="width: 48px; height: 48px;">
                                </div>
                                <h5 class="card-title">Cadastro em Massa</h5>
                                <p class="card-text">Cadastrar usuários através de planilha Excel</p>
                                <a href="{{ route('cadastro-automatica.index') }}" class="btn btn-outline-primary w-100">
                                    <img src="{{asset('img/icons/file-alt-regular.svg')}}" alt="Planilha" style="width: 16px; height: 16px;" class="me-2">Cadastro de usuários (Planilha)
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <img src="{{asset('img/icons/user-plus-black-solid.svg')}}" alt="Inscrever" style="width: 48px; height: 48px;">
                                </div>
                                <h5 class="card-title">Inscrever Participante</h5>
                                <p class="card-text">Inscrever um participante manualmente no evento</p>
                                <button type="button" class="btn btn-outline-success w-100" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modal-inscrever-participante">
                                    <img src="{{asset('img/icons/user-plus-solid.svg')}}" alt="Inscrever" style="width: 16px; height: 16px;" class="me-2">Inscrever Participante
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <img src="{{asset('img/icons/users-solid-full.svg')}}" alt="Usuários" style="width: 48px; height: 48px;">
                                </div>
                                <h5 class="card-title">Inscrição Automática</h5>
                                <p class="card-text">Inscrever participantes automaticamente via planilha</p>
                                <a href="{{ route('inscricao-automatica.index', ['evento_id' => $evento->id]) }}" class="btn btn-outline-success w-100">
                                    <img src="{{asset('img/icons/user-plus-solid.svg')}}" alt="Usuários" style="width: 16px; height: 16px;" class="me-2">Inscrição Automática
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <img src="{{asset('img/icons/food-dinner.svg')}}" alt="Alimentação" style="width: 48px; height: 48px;">
                                </div>
                                <h5 class="card-title">Gerenciar Alimentação</h5>
                                <p class="card-text">Liberação individual para alimentação dos participantes</p>
                                <button type="button" class="btn btn-outline-warning w-100" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modal-gerenciar-alimentacao">
                                    <img src="{{asset('img/icons/file-alt-regular.svg')}}" alt="Alimentação" style="width: 16px; height: 16px;" class="me-2">Gerenciar Alimentação
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <img src="{{asset('img/icons/file-alt-regular-black.svg')}}" alt="Alimentação" style="width: 48px; height: 48px;">
                                </div>
                                <h5 class="card-title">Planilha de Alimentação</h5>
                                <p class="card-text">Processar planilha para gerenciar alimentação dos participantes</p>
                                <a href="{{ route('processar-planilha.index') }}" class="btn btn-outline-warning w-100">
                                    <img src="{{asset('img/icons/file-alt-regular.svg')}}" alt="Alimentação" style="width: 16px; height: 16px;" class="me-2">Processar Alimentação
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <img src="{{asset('img/icons/file-download-solid.svg')}}" alt="Exportar" style="width: 48px; height: 48px;">
                                </div>
                                <h5 class="card-title">Exportar Dados</h5>
                                <p class="card-text">Exportar lista de inscritos em formato Excel (.xlsx)</p>
                                <a href="{{route('evento.exportarInscritosXLSX', array_merge([$evento], request()->only(['nome', 'email', 'status'])))}}" class="btn btn-success w-100">
                                    <img src="{{asset('img/icons/file-download-solid.svg')}}" alt="Exportar" style="width: 16px; height: 16px;" class="me-2">Exportar .xlsx
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <img src="{{asset('img/icons/file-download-solid.svg')}}" alt="Confirmar presença" style="width: 48px; height: 48px;">
                                </div>
                                <h5 class="card-title">Confirmar presença</h5>
                                <p class="card-text">Importação da listagem de presença dos inscritos</p>
                                <a href="{{route('evento.importListaPresenca', $evento)}}" class="btn btn-success w-100">
                                    Importar lista de presença
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <img src="{{asset('img/icons/file-download-solid.svg')}}" alt="Importar Apresentações" style="width: 48px; height: 48px;">
                                </div>
                                <h5 class="card-title">Importar Apresentações</h5>
                                <p class="card-text">Importar planilha com trabalhos apresentados</p>
                                <button type="button" class="btn btn-success w-100" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modal-importar-apresentacoes">
                                    <img src="{{asset('img/icons/file-download-solid.svg')}}" alt="Importar" style="width: 16px; height: 16px;" class="me-2">Importar Apresentações
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Importar Apresentações -->
<div class="modal fade" id="modal-importar-apresentacoes" tabindex="-1" role="dialog" aria-labelledby="modal-importar-apresentacoes-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #114048ff; color: white;">
                <h5 class="modal-title" id="modal-importar-apresentacoes-label">Importar Apresentações - {{ $evento->nome }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('trabalho.importar.apresentacoes', ['eventoId' => $evento->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="planilha_apresentacoes" class="form-label">Selecione a planilha (.xlsx):</label>
                        <input type="file" class="form-control" id="planilha_apresentacoes" name="planilha_apresentacoes" accept=".xlsx,.xls" required>
                        <div class="form-text">
                            A planilha deve conter 3 colunas na seguinte ordem: <strong>ID do trabalho</strong>, <strong>Título do trabalho</strong> e <strong>Autor</strong>.
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <h6>Instruções:</h6>
                        <ul class="mb-0">
                            <li>A planilha deve estar no formato .xlsx ou .xls</li>
                            <li><strong>Ordem das colunas é importante:</strong></li>
                            <li><strong>1ª coluna:</strong> ID do trabalho</li>
                            <li><strong>2ª coluna:</strong> Título do trabalho apresentado</li>
                            <li><strong>3ª coluna:</strong> Nome do autor do trabalho</li>
                            <li>A primeira linha deve conter os cabeçalhos das colunas</li>
                        </ul>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-info button-prevent-multiple-submits">
                        <img src="{{asset('img/icons/file-upload-solid.svg')}}" alt="Importar" style="width: 16px; height: 16px;" class="me-2">Importar Apresentações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
