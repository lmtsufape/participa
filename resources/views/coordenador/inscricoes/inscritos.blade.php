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
                        <a href="{{route('evento.exportarInscritosXLSX', $evento)}}" class="btn btn-success">Exportar .xlsx</a>
                        {{-- <a href="{{route('evento.downloadInscritos', $evento)}}" class="btn btn-primary">Exportar .csv</a>--}}
{{--                        <a href="{{route('evento.downloadInscritosCertifica', $evento)}}" class="btn btn-primary float-md-right mt-2">Exportar XLSX para o Certifica</a>--}}
                        <button type="button" class="button-prevent-multiple-submits btn btn-outline-success my-2 ml-1" data-bs-toggle="modal" data-bs-target="#modal-inscrever-participante">
                            Inscrever participante
                        </button>
                        </button>
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
                                    <td data-bs-toggle="modal" data-bs-target="#modal-listar-campos-formulario-{{$inscricao->id}}">{{$inscricao->categoria->nome}}</td>
                                    <td data-bs-toggle="modal" data-bs-target="#modal-listar-campos-formulario-{{$inscricao->id}}">R$ {{ $inscricao->categoria ? number_format($inscricao->categoria->valor_total, 2, ',', '.') : 'N/A' }}</td>
                                    <td data-bs-toggle="modal" data-bs-target="#modal-listar-campos-formulario-{{$inscricao->id}}">
                                        @if($inscricao->finalizada == true)
                                            Inscrito
                                        @else
                                            Pré-inscrito
                                        @endif
                                    </td>
                                    <td data-bs-toggle="modal" data-bs-target="#modal-listar-campos-formulario-{{$inscricao->id}}">{{$inscricao->finalizada ? 'Sim' : 'Não'}}</td>
                                    <td data-bs-toggle="modal" data-bs-target="#modal-listar-campos-formulario-{{$inscricao->id}}"><img src="{{asset('img/icons/eye-regular.svg')}}" alt="" style="width: 14px; fill: #000 !important;"></td>
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
            <div class="modal-header" style="background-color: #114048ff; color: white; display: flex; justify-content: space-between; align-items: center;">
                <h5 class="modal-title">Dados do inscrito</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if ($inscricao->categoria)
                <div class="form-group">
                    <label class="text-center">Categoria</label>
                    <input type="text" class="form-control" value="{{$inscricao->categoria->nome}}" disabled>
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
@endsection
