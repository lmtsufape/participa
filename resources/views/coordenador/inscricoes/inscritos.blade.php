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

@if ($errors->has('email_inscritos'))
    <div class="alert alert-danger">
        {{ $errors->first('email_inscritos') }}
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
                    <div class="col-md-2 d-flex gap-2 flex-column align-items-end">
                        <button
                            type="button"
                            class="btn btn-outline-primary my-1 ml-1"
                            data-bs-toggle="modal"
                            data-bs-target="#modal-enviar-email"
                        >
                            Enviar e-mail
                        </button>
                        <a href="{{route('evento.exportarInscritosXLSX', $evento)}}" class="btn btn-success">Exportar .xlsx</a>
                        <a href="{{route('evento.exportarInscritosApoioInfantilXLSX', $evento)}}" class="btn btn-warning" title="Exportar apenas inscritos com solicitação de apoio infantil">Exportar Apoio Infantil (.xlsx)</a>
                        <a href="{{route('evento.exportarInscritosNecessidadesEspeciaisXLSX', $evento)}}" class="btn btn-info text-white" title="Exportar apenas inscritos com necessidades especiais">Exportar Nec. Especiais (.xlsx)</a>
                        {{-- <a href="{{route('evento.downloadInscritos', $evento)}}" class="btn btn-primary">Exportar .csv</a>--}}
{{--                        <a href="{{route('evento.downloadInscritosCertifica', $evento)}}" class="btn btn-primary float-md-right mt-2">Exportar XLSX para o Certifica</a>--}}
                        <button type="button" class="button-prevent-multiple-submits btn btn-outline-success my-2 ml-1" data-bs-toggle="modal" data-bs-target="#modal-inscrever-participante">
                            Inscrever participante
                        </button>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <form method="GET" action="{{ route('inscricao.inscritos', $evento) }}" class="row g-3">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="nome" placeholder="Buscar por nome..." value="{{ request('nome') }}">
                                </div>
                                <div class="col-md-4">
                                    <input type="email" class="form-control" name="email" placeholder="Buscar por email..." value="{{ request('email') }}">
                                </div>
                                <div class="col-md-2">
                                    <select class="form-control" name="status">
                                        <option value="">Todos os status</option>
                                        <option value="finalizada" {{ request('status') === 'finalizada' ? 'selected' : '' }}>Inscrito (Finalizada)</option>
                                        <option value="pendente" {{ request('status') === 'pendente' ? 'selected' : '' }}>Pré-inscrito (Pendente)</option>
                                    </select>
                                </div>
                                <div class="col-md-2 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary w-100">Buscar</button>
                                    <a href="{{ route('inscricao.inscritos', $evento) }}" class="btn btn-outline-secondary w-100">Limpar</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <hr class="mt-0">

                    @include('coordenador.inscricoes.inscrever_participante')

                    <p class="card-text">
                    <table class="table table-hover table-responsive-lg table-sm" style="position: relative;">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="selecionar-todos-inscritos" class="form-check-input">
                                </th>
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
                                <th>Recibo</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($inscricoes as $inscricao)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input checkbox-inscricao" value="{{$inscricao->id}}">
                                    </td>
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
                                    <td> 
                                        @if($inscricao->finalizada)
                                         <a href="{{ route('inscricao.recibo', ['inscricao' => $inscricao->id]) }}" class="btn btn-sm btn-success" title="Gerar recibo">
                                             <img src="{{asset('img/icons/file-pdf-solid.svg')}}" alt="Gerar recibo" style="width: 14px;">
                                         </a>
                                        @else
                                          -
                                        @endif
                                    </td>
                                    <td data-bs-toggle="modal" data-bs-target="#modal-listar-campos-formulario-{{$inscricao->id}}"><img src="{{asset('img/icons/eye-regular.svg')}}" alt="" style="width: 14px; fill: #000 !important;"></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $evento->subeventos->count() > 0 ? 10 : 9 }}" class="text-center">
                                        Nenhum inscrito encontrado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center mt-4">
                        {{ $inscricoes->links() }}
                    </div>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-enviar-email" tabindex="-1" aria-labelledby="modal-enviar-email-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #114048ff; color: white;">
                <h5 class="modal-title" id="modal-enviar-email-label">Enviar e-mail para inscritos</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('inscricao.enviarEmail', $evento) }}" id="form-enviar-email-inscritos">
                @csrf
                <input type="hidden" name="selecao_total" id="input-selecao-total" value="0">
                <div class="modal-body">
                    <div class="form-check form-switch mb-3 p-3 border rounded bg-light">
                        <input class="form-check-input" type="checkbox" id="check-enviar-todos" name="selecao_total" value="1" style="margin-left: 0;">
                        <label class="form-check-label fw-bold" for="check-enviar-todos" style="margin-left: 10px;">
                            Enviar para TODOS os {{ $inscricoes->total() }} inscritos
                        </label>
                        <div class="text-muted small" style="margin-left: 10px;">
                            (Isso ignorará as seleções individuais feitas na lista)
                        </div>
                    </div>
                    <input type="hidden" name="inscricoes" id="inscricoes-email-input">
                    <p class="text-muted mb-3">Este e-mail será enviado para <span id="total-inscricoes-selecionadas">0</span> inscrito(s) selecionado(s).</p>
                    <div class="form-group">
                        <label for="assunto-email-inscritos">Título do e-mail</label>
                        <input type="text" class="form-control" id="assunto-email-inscritos" name="assunto" required maxlength="255">
                    </div>
                    <div class="form-group mt-3">
                        <label for="mensagem-email-inscritos">Corpo do e-mail</label>
                        <textarea class="form-control" id="mensagem-email-inscritos" name="mensagem" rows="6" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Enviar e-mail</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modalElement = document.getElementById('modal-enviar-email');
        const checkEnviarTodos = document.getElementById('check-enviar-todos');
        const inputSelecaoTotal = document.getElementById('input-selecao-total');
        const btnEnviarFinal = document.getElementById('btn-enviar-final');
        const hiddenInputIds = document.getElementById('inscricoes-email-input');
        const totalSelectedSpan = document.getElementById('total-inscricoes-selecionadas');
        const avisoSelecionados = document.getElementById('aviso-selecionados');

        function validarEstadoDoEnvio() {
            const selecionadosNaLista = document.querySelectorAll('.checkbox-inscricao:checked').length;
            const mandarParaTodos = checkEnviarTodos.checked;

            inputSelecaoTotal.value = mandarParaTodos ? "1" : "0";

            if (totalSelectedSpan) totalSelectedSpan.textContent = selecionadosNaLista;

            if (avisoSelecionados) avisoSelecionados.style.opacity = mandarParaTodos ? "0.3" : "1";

            if (btnEnviarFinal) {
                btnEnviarFinal.disabled = !(mandarParaTodos || selecionadosNaLista > 0);
            }
        }

        if (modalElement) {
            modalElement.addEventListener('show.bs.modal', function() {
                const checkboxes = document.querySelectorAll('.checkbox-inscricao:checked');
                const selectedIds = Array.from(checkboxes).map(cb => cb.value);
                hiddenInputIds.value = selectedIds.join(',');
                
                validarEstadoDoEnvio();
            });
        }

        if (checkEnviarTodos) {
            checkEnviarTodos.addEventListener('change', validarEstadoDoEnvio);
        }
    });
</script>

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
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="">Nome completo</label>
                        <input type="text" class="form-control" value="{{ $inscricao->user->name }}" disabled>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Nome social</label>
                        <input type="text" class="form-control" value="{{ $inscricao->user->nomeSocial ?? 'Não informado' }}" disabled>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Data de nascimento</label>
                        <input type="text" class="form-control"
                               value="{{ $inscricao->user->dataNascimento ? \Carbon\Carbon::parse($inscricao->user->dataNascimento)->format('d/m/Y') : 'não informado' }}"
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

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="">Apoio Infantil Solicito?</label>
                        <input type="text" 
                            class="form-control" 
                            value="{{ $inscricao->apoio_infantil ? 'Sim' : 'Não' }}" 
                            disabled>
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
