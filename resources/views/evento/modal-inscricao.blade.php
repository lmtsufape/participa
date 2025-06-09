<div class="modal fade" id="modalInscrever" tabindex="-1" role="dialog" aria-labelledby="#label" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header position-relative" style="background-color: #114048ff; color: white;">
                <h5 class="modal-title w-100 text-center m-0">
                    @if (auth()->check()) {{ __('Escolha uma das categorias abaixo e clique em "Confirmar" para realizar a sua pré-inscrição!') }}
                    @else {{ __('Atenção') }}! @endif</h5>
                <button type="button" class="btn-close btn-close-white position-absolute end-0 top-50 translate-middle-y me-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('inscricao.inscrever', ['evento_id' => $evento->id]) }}" x-data="{ categoria: '' }" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <h6>Atenção!</h6>
                    <ul>
                        <li>Para participar do 13º CBA, você precisa concluir a sua inscrição e realizar o pagamento.</li>
                        <li>Informações sobre isenções serão divulgadas em breve.</li>
                    </ul>
                    @if (!auth()->check())
                        @include('componentes.mensagens')
                        <p class="text-justify">{!! __(
                            'Para continuar com sua inscrição, é necessário que possua cadastro na plataforma e realize o seu acesso (login). <strong>Caso já possua uma conta</strong>, basta acessar com o seu login (e-mail) e senha.',
                        ) !!} <br><br>
                            {!! __(
                                '<strong>Se você ainda não tem</strong>, será necessário efetuar o cadastro, validar sua conta pelo link enviado para o e-mail e retornar a página do evento para realizar sua inscrição.',
                            ) !!} <br><br>
                            {!! __(
                                'Após realizar seu login ou cadastro, retorne a esta página, atualize-a (pressionando a tecla F5) e prossiga com sua inscrição no evento.',
                            ) !!}
                        </p>
                        <div class="modal-footer text-center">
                            <a href="{{ route('preRegistro') }}" target="_blank">
                                <button type="button" class="btn btn-secondary">{{ __('Cadastrar-se') }}</button>
                            </a>

                            <a href="{{ route('login') }}" target="_blank">
                                <button type="button"
                                    class="btn btn-primary button-prevent-multiple-submits">{{ __('Entrar') }}</button>
                            </a>
                        </div>
                    @elseif ($evento->categoriasParticipantes()->where('permite_inscricao', true)->exists())
                        <div id="formulario" class="carousel-categorias container">
                            <div>
                                <div x-show="categoria == ''">
                                    <div class="carousel slide" id="carouselCategorias" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            <input type="hidden" name="categoria" x-model="categoria" required>
                                            @if ($evento->categoriasPermitidasParaUsuario()->count() > 4)
                                            <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
                                                <a class="carousel-arrow-btn btn" id="categoriaAnterior"
                                                    href="#carouselCategorias" title="Previous" role="button"
                                                    data-bs-slide="prev">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="carousel-arrow-svg" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                                                    </svg>
                                                </a>
                                                <a class="carousel-arrow-btn btn" id="proximaCategoria"
                                                    href="#carouselCategorias" title="Next" role="button"
                                                    data-bs-slide="next">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="carousel-arrow-svg" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
                                                    </svg>
                                                </a>
                                            </div>
                                            @endif
                                            <div class="card-group">
                                                @foreach ($evento->categoriasPermitidasParaUsuario()->chunk(4) as $chunk)
                                                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                                        <div class="row">
                                                            @foreach ($chunk as $categoria)
                                                                <div class="col-md-6 mb-4">
                                                                    <div class="card shadow" style="">
                                                                        <div class="card-header" style="background-color: #114048ff; color: white;">
                                                                            <h4 class="my-0 font-weight-normal text-center">{{ $categoria->nome }}</h4>
                                                                        </div>
                                                                        <div class="card-body">
                                                                            @if ($categoria->descricao)
                                                                                <label for=""><strong>{{ __('Descrição') }}:</strong></label>
                                                                                <p> {!! $categoria->descricao !!}</p>
                                                                            @endif
                                                                            <p>
                                                                            <strong>{{ __('Valor da Inscrição:') }}</strong>
                                                                            @if($categoria->valor_total > 0)
                                                                                R$ {{ number_format($categoria->valor_total, 2, ',', '.') }}
                                                                            @else
                                                                                {{ __('Gratuita') }}
                                                                            @endif
                                                                            </p>
                                                                            @if($categoria->limite_inscricao)
                                                                            <p><small><strong>{{__('Inscrições até:')}}</strong> {{ \Carbon\Carbon::parse($categoria->limite_inscricao)->format('d/m/Y') }}</small></p>
                                                                            @endif
                                                                        </div>
                                                                        <div class="card-footer d-flex justify-content-center">
                                                                            <button type="button" class="btn btn-outline-primary btn-select-categoria" x-on:click="categoria = {{ $categoria->id }}">
                                                                                {{ __('Selecionar') }}
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div x-show="categoria != ''">
                                @foreach ($evento->categoriasParticipantes as $categoria)
                                    <div x-data="{ id: {{ $categoria->id }} }">
                                        <template x-if="categoria == id">
                                            <div class="campos-extras" id="campos-extras-{{ $categoria->id }}">
                                                <div>
                                                    <div class="form-group">
                                                        <label>{{ __('Categoria selecionada') }}</label>
                                                        <input type="text" readonly class="form-control"
                                                            value="{{ $categoria->nome }}">
                                                        <button type="button" x-on:click="categoria = ''"
                                                            class="btn btn-md btn-block btn-primary mt-2 col-sm-12 col-md-6 col-lg-4" style="background-color: #114048ff; border-color: #114048ff;">
                                                            {{ __('Alterar categoria') }}</button>
                                                    </div>

                                                    <!-- <div class="form-group">
                                                <label for="">Link</label>
                                                <input type="text" readonly class="form-control" value="teste">
                                            </div> -->

                                                    @foreach ($categoria->camposNecessarios()->distinct()->orderBy('tipo')->get() as $campo)
                                                        @if ($campo->tipo == 'endereco')
                                                            <div>
                                                                <div class="row">
                                                                    <div class="form-group col-sm-6">
                                                                        <label
                                                                            for="endereco-cep-{{ $campo->id }}">{{ __('CEP') }}</label>
                                                                        <input id="endereco-cep-{{ $campo->id }}"
                                                                            name="endereco-cep-{{ $campo->id }}"
                                                                            onblur="pesquisacep(this.value, '{{ $campo->id }}');"
                                                                            type="text"
                                                                            class="form-control cep @error('endereco-cep-' . $campo->id) is-invalid @enderror"
                                                                            placeholder="00000-000"
                                                                            @if ($campo->obrigatorio) required @endif
                                                                            value="@if (old('endereco-cep-' . $campo->id) != null) {{ old('endereco-cep-' . $campo->id) }} @endif">
                                                                        @error('endereco-cep-' . $campo->id)
                                                                            <span class="invalid-feedback" role="alert">
                                                                                <strong>{{ $message }}</strong>
                                                                            </span>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="form-group col-sm-6">
                                                                        <label
                                                                            for="endereco-bairro-{{ $campo->id }}">{{ __('Bairro') }}</label>
                                                                        <input type="text"
                                                                            class="form-control @error('endereco-bairro-' . $campo->id) is-invalid @enderror"
                                                                            id="endereco-bairro-{{ $campo->id }}"
                                                                            name="endereco-bairro-{{ $campo->id }}"
                                                                            placeholder=""
                                                                            @if ($campo->obrigatorio) required @endif
                                                                            value="@if (old('endereco-bairro-' . $campo->id) != null) {{ old('endereco-bairro-' . $campo->id) }} @endif">
                                                                        @error('endereco-bairro-' . $campo->id)
                                                                            <span class="invalid-feedback" role="alert">
                                                                                <strong>{{ $message }}</strong>
                                                                            </span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="form-group col-sm-9">
                                                                        <label
                                                                            for="endereco-rua-{{ $campo->id }}">{{ __('Rua') }}</label>
                                                                        <input type="text"
                                                                            class="form-control @error('endereco-rua-' . $campo->id) is-invalid @enderror"
                                                                            id="endereco-rua-{{ $campo->id }}"
                                                                            name="endereco-rua-{{ $campo->id }}"
                                                                            placeholder=""
                                                                            @if ($campo->obrigatorio) required @endif
                                                                            value="@if (old('endereco-rua-' . $campo->id) != null) {{ old('endereco-rua-' . $campo->id) }} @endif">
                                                                        @error('endereco-rua-' . $campo->id)
                                                                            <span class="invalid-feedback" role="alert">
                                                                                <strong>{{ $message }}</strong>
                                                                            </span>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="form-group col-sm-3">
                                                                        <label
                                                                            for="endereco-complemento-{{ $campo->id }}">{{ __('Complemento') }}</label>
                                                                        <input type="text"
                                                                            class="form-control @error('endereco-complemento-' . $campo->id) is-invalid @enderror"
                                                                            id="endereco-complemento-{{ $campo->id }}"
                                                                            name="endereco-complemento-{{ $campo->id }}"
                                                                            placeholder=""
                                                                            @if ($campo->obrigatorio) required @endif
                                                                            value="@if (old('endereco-complemento-' . $campo->id) != null) {{ old('endereco-complemento-' . $campo->id) }} @endif">
                                                                        @error('endereco-complemento-' . $campo->id)
                                                                            <span class="invalid-feedback" role="alert">
                                                                                <strong>{{ $message }}</strong>
                                                                            </span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="form-row">
                                                                    <div class="form-group col-sm-6">
                                                                        <label
                                                                            for="endereco-cidade-{{ $campo->id }}">{{ __('Cidade') }}</label>
                                                                        <input type="text"
                                                                            class="form-control @error('endereco-cidade-' . $campo->id) is-invalid @enderror"
                                                                            id="endereco-cidade-{{ $campo->id }}"
                                                                            name="endereco-cidade-{{ $campo->id }}"
                                                                            placeholder=""
                                                                            @if ($campo->obrigatorio) required @endif
                                                                            value="@if (old('endereco-cidade-' . $campo->id) != null) {{ old('endereco-cidade-' . $campo->id) }} @endif">
                                                                        @error('endereco-cidade-' . $campo->id)
                                                                            <span class="invalid-feedback" role="alert">
                                                                                <strong>{{ $message }}</strong>
                                                                            </span>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="form-group col-sm-3">
                                                                        <label
                                                                            for="endereco-uf-{{ $campo->id }}">UF</label>
                                                                        <select
                                                                            class="form-control @error('endereco-uf-' . $campo->id) is-invalid @enderror"
                                                                            id="endereco-uf-{{ $campo->id }}"
                                                                            name="endereco-uf-{{ $campo->id }}"
                                                                            @if ($campo->obrigatorio) required @endif>
                                                                            <option value="" disabled selected
                                                                                hidden>-- UF --</option>
                                                                            <option
                                                                                @if (old('endereco-uf-' . $campo->id) == 'AC') selected @endif
                                                                                value="AC">AC</option>
                                                                            <option
                                                                                @if (old('endereco-uf-' . $campo->id) == 'AL') selected @endif
                                                                                value="AL">AL</option>
                                                                            <option
                                                                                @if (old('endereco-uf-' . $campo->id) == 'AP') selected @endif
                                                                                value="AP">AP</option>
                                                                            <option
                                                                                @if (old('endereco-uf-' . $campo->id) == 'AM') selected @endif
                                                                                value="AM">AM</option>
                                                                            <option
                                                                                @if (old('endereco-uf-' . $campo->id) == 'BA') selected @endif
                                                                                value="BA">BA</option>
                                                                            <option
                                                                                @if (old('endereco-uf-' . $campo->id) == 'CE') selected @endif
                                                                                value="CE">CE</option>
                                                                            <option
                                                                                @if (old('endereco-uf-' . $campo->id) == 'DF') selected @endif
                                                                                value="DF">DF</option>
                                                                            <option
                                                                                @if (old('endereco-uf-' . $campo->id) == 'ES') selected @endif
                                                                                value="ES">ES</option>
                                                                            <option
                                                                                @if (old('endereco-uf-' . $campo->id) == 'GO') selected @endif
                                                                                value="GO">GO</option>
                                                                            <option
                                                                                @if (old('endereco-uf-' . $campo->id) == 'MA') selected @endif
                                                                                value="MA">MA</option>
                                                                            <option
                                                                                @if (old('endereco-uf-' . $campo->id) == 'MT') selected @endif
                                                                                value="MT">MT</option>
                                                                            <option
                                                                                @if (old('endereco-uf-' . $campo->id) == 'MS') selected @endif
                                                                                value="MS">MS</option>
                                                                            <option
                                                                                @if (old('endereco-uf-' . $campo->id) == 'MG') selected @endif
                                                                                value="MG">MG</option>
                                                                            <option
                                                                                @if (old('endereco-uf-' . $campo->id) == 'PA') selected @endif
                                                                                value="PA">PA</option>
                                                                            <option
                                                                                @if (old('endereco-uf-' . $campo->id) == 'PB') selected @endif
                                                                                value="PB">PB</option>
                                                                            <option
                                                                                @if (old('endereco-uf-' . $campo->id) == 'PR') selected @endif
                                                                                value="PR">PR</option>
                                                                            <option
                                                                                @if (old('endereco-uf-' . $campo->id) == 'PE') selected @endif
                                                                                value="PE">PE</option>
                                                                            <option
                                                                                @if (old('endereco-uf-' . $campo->id) == 'PI') selected @endif
                                                                                value="PI">PI</option>
                                                                            <option
                                                                                @if (old('endereco-uf-' . $campo->id) == 'RJ') selected @endif
                                                                                value="RJ">RJ</option>
                                                                            <option
                                                                                @if (old('endereco-uf-' . $campo->id) == 'RN') selected @endif
                                                                                value="RN">RN</option>
                                                                            <option
                                                                                @if (old('endereco-uf-' . $campo->id) == 'RS') selected @endif
                                                                                value="RS">RS</option>
                                                                            <option
                                                                                @if (old('endereco-uf-' . $campo->id) == 'RO') selected @endif
                                                                                value="RO">RO</option>
                                                                            <option
                                                                                @if (old('endereco-uf-' . $campo->id) == 'RR') selected @endif
                                                                                value="RR">RR</option>
                                                                            <option
                                                                                @if (old('endereco-uf-' . $campo->id) == 'SC') selected @endif
                                                                                value="SC">SC</option>
                                                                            <option
                                                                                @if (old('endereco-uf-' . $campo->id) == 'SP') selected @endif
                                                                                value="SP">SP</option>
                                                                            <option
                                                                                @if (old('endereco-uf-' . $campo->id) == 'SE') selected @endif
                                                                                value="SE">SE</option>
                                                                            <option
                                                                                @if (old('endereco-uf-' . $campo->id) == 'TO') selected @endif
                                                                                value="TO">TO</option>
                                                                        </select>
                                                                        @error('endereco-uf-' . $campo->id)
                                                                            <span class="invalid-feedback" role="alert">
                                                                                <strong>{{ $message }}</strong>
                                                                            </span>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="form-group col-sm-3">
                                                                        <label
                                                                            for="endereco-numero-{{ $campo->id }}">{{ __('Número') }}</label>
                                                                        <input type="number"
                                                                            class="form-control numero @error('endereco-numero-' . $campo->id) is-invalid @enderror"
                                                                            id="endereco-numero-{{ $campo->id }}"
                                                                            name="endereco-numero-{{ $campo->id }}"
                                                                            placeholder="10"
                                                                            @if ($campo->obrigatorio) required @endif
                                                                            value="@if (old('endereco-numero-' . $campo->id) != null) {{ old('endereco-numero-' . $campo->id) }} @endif"
                                                                            maxlength="10">
                                                                        @error('endereco-numero-' . $campo->id)
                                                                            <span class="invalid-feedback" role="alert">
                                                                                <strong>{{ $message }}</strong>
                                                                            </span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @elseif($campo->tipo == 'date')
                                                            <div class="form-group">
                                                                <label
                                                                    for="date-{{ $campo->id }}">{{ $campo->titulo }}
                                                                    @if ($campo->obrigatorio)
                                                                        *
                                                                    @endif
                                                                </label>
                                                                <input
                                                                    class="form-control @error('date-' . $campo->id) is-invalid @enderror"
                                                                    type="date" name="date-{{ $campo->id }}"
                                                                    id="date-{{ $campo->id }}"
                                                                    @if ($campo->obrigatorio) required @endif
                                                                    value="@if (old('date-' . $campo->id) != null) {{ old('date-' . $campo->id) }} @endif">
                                                                @error('date-' . $campo->id)
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        @elseif($campo->tipo == 'select')
                                                            <div class="form-group">
                                                                <label
                                                                    for="select{{ $campo->id }}">{{ $campo->titulo }}</label>
                                                                <select class="form-control"
                                                                    id="select{{ $campo->id }}"
                                                                    @if ($campo->obrigatorio) required @endif
                                                                    name="select-{{ $campo->id }}">
                                                                    <option
                                                                        @if ($campo->obrigatorio) disabled @endif
                                                                        selected>
                                                                        {{ __('Selecione uma opção') }}
                                                                    </option>
                                                                    @foreach ($campo->opcoes as $opcao)
                                                                        <option value="{{ $opcao->nome }}">
                                                                            {{ $opcao->nome }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        @elseif($campo->tipo == 'email')
                                                            <div class="form-group">
                                                                <label
                                                                    for="email-{{ $campo->id }}">{{ $campo->titulo }}
                                                                    @if ($campo->obrigatorio)
                                                                        *
                                                                    @endif
                                                                </label>
                                                                <input
                                                                    class="form-control @error('email-' . $campo->id) is-invalid @enderror"
                                                                    type="email" name="email-{{ $campo->id }}"
                                                                    id="email-{{ $campo->id }}"
                                                                    @if ($campo->obrigatorio) required @endif
                                                                    value="@if (old('email-' . $campo->id) != null) {{ old('email-' . $campo->id) }} @endif">
                                                                @error('email-' . $campo->id)
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        @elseif($campo->tipo == 'text')
                                                            <div class="form-group">
                                                                <label
                                                                    for="text-{{ $campo->id }}">{{ $campo->titulo }}
                                                                    @if ($campo->obrigatorio)
                                                                        *
                                                                    @endif
                                                                </label>
                                                                <input
                                                                    class="form-control @error('text-' . $campo->id) is-invalid @enderror"
                                                                    type="text" name="text-{{ $campo->id }}"
                                                                    id="text-{{ $campo->id }}"
                                                                    @if ($campo->obrigatorio) required @endif
                                                                    value="@if (old('text-' . $campo->id) != null) {{ old('text-' . $campo->id) }} @endif">
                                                                @error('text-' . $campo->id)
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        @elseif($campo->tipo == 'cpf')
                                                            <div class="form-group">
                                                                <label
                                                                    for="cpf-{{ $campo->id }}">{{ $campo->titulo }}
                                                                    @if ($campo->obrigatorio)
                                                                        *
                                                                    @endif
                                                                </label>
                                                                <input id="cpf-{{ $campo->id }}" type="text"
                                                                    class="form-control cpf @error('cpf-' . $campo->id) is-invalid @enderror"
                                                                    name="cpf-{{ $campo->id }}" autocomplete="cpf"
                                                                    autofocus
                                                                    @if ($campo->obrigatorio) required @endif
                                                                    value="@if (old('cpf-' . $campo->id) != null) {{ old('cpf-' . $campo->id) }} @endif">
                                                                @error('cpf-' . $campo->id)
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        @elseif($campo->tipo == 'contato')
                                                            <div class="form-group">
                                                                <label
                                                                    for="contato-{{ $campo->id }}">{{ $campo->titulo }}
                                                                    @if ($campo->obrigatorio)
                                                                        *
                                                                    @endif
                                                                </label>
                                                                <input id="contato-{{ $campo->id }}"
                                                                    type="text"
                                                                    class="form-control celular @error('contato-' . $campo->id) is-invalid @enderror"
                                                                    name="contato-{{ $campo->id }}"
                                                                    autocomplete="contato" autofocus
                                                                    @if ($campo->obrigatorio) required @endif
                                                                    value="@if (old('contato-' . $campo->id) != null) {{ old('contato-' . $campo->id) }} @endif">
                                                                @error('contato-' . $campo->id)
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        @elseif($campo->tipo == 'file')
                                                            <div class="form-group">
                                                                <label for="file-{{ $campo->id }}"
                                                                    class="">{{ $campo->titulo }}@if ($campo->obrigatorio)
                                                                        *
                                                                    @endif
                                                                </label><br>
                                                                <input type="file" id="file-{{ $campo->id }}"
                                                                    class="form-control-file  @error('file-' . $campo->id) is-invalid @enderror"
                                                                    name="file-{{ $campo->id }}"
                                                                    @if ($campo->obrigatorio) required @endif>
                                                                <br>
                                                                @error('file-' . $campo->id)
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                </div>
            @else
                @include('componentes.mensagens')
                <p>{{ __('Tem certeza que deseja se inscrever nesse evento?') }}</p>
                @endif


                @if (auth()->check())
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit"
                            class="btn btn-primary button-prevent-multiple-submits" style="background-color: #114048ff; border-color: #114048ff;" :disabled="!categoria || categoria === ''">Confirmar</button>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>

<style>
.carousel-arrow-btn {
    border: 1px solid #114048ff !important;
    background: #fff !important;
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s, border 0.2s;
    padding: 0;
    margin-bottom: 40px;
}
.carousel-arrow-svg {
    color: #114048ff;
    transition: color 0.2s;
}
.carousel-arrow-btn:hover, .carousel-arrow-btn:focus {
    background: #114048ff !important;

}
.carousel-arrow-btn:hover .carousel-arrow-svg, .carousel-arrow-btn:focus .carousel-arrow-svg {
    color: #fff;
}
.btn-select-categoria {
    border-color: #114048ff !important;
    color: black !important;
    min-width: 140px;
    transition: background 0.2s, color 0.2s;
}
.btn-select-categoria:hover, .btn-select-categoria:focus {
    background: #114048ff !important;
    color: #fff !important;
}
</style>
