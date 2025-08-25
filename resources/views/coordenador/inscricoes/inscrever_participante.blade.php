<div class="modal fade" id="modal-inscrever-participante" tabindex="-1" role="dialog" aria-labelledby="modal-inscrever-participante-label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #114048ff; color: white;">
                <h5 class="modal-title" id="modalLabelAtividadeShow{{ $evento->id }}">Inscrição Coletiva - {{ $evento->titulo }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('inscricao.inscreverParticipante', ['evento_id' => $evento->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body" x-data="{campo: 'email',participantes: [{identificador: 'email',email: '',cpf: '',categoria: 0}]}">
                    <div class="mb-3">
                        <label class="form-label">Tipo de identificação:</label>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="emailradio" class="custom-control-input" x-model="campo" value="email">
                            <label class="custom-control-label" for="emailradio">E-mail</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="cpfradio" class="custom-control-input" x-model="campo" value="cpf">
                            <label class="custom-control-label" for="cpfradio">CPF</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">Participantes</h6>
                            <button type="button" class="btn btn-sm btn-success" @click="participantes.push({identificador: campo,email: '',cpf: '',categoria: 0})">
                                <img src="{{asset('img/icons/plus-square-solid.svg')}}" alt="Adicionar"
                                    style="width: 14px; margin-right: 5px;"> Adicionar participante
                            </button>
                        </div>

                        <div class="participantes-container">
                            <template x-for="(participante, index) in participantes" :key="index">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title mb-0">Participante <span x-text="index + 1"></span></h6>
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                @click="participantes.splice(index, 1)" x-show="participantes.length > 1">
                                                <img src="{{asset('img/icons/trash-alt-regular.svg')}}" alt="Remover"
                                                    style="width: 14px;">
                                            </button>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <input type="hidden" :name="'participantes[' + index + '][identificador]'"
                                                    x-model="campo">
                                                <div x-show="campo == 'email'">
                                                    <label class="form-label">E-mail</label>
                                                    <div class="input-group">
                                                        <input type="email" class="form-control"
                                                            :name="'participantes[' + index + '][email]'" x-model="participante.email"
                                                            :id="'email-' + index" @blur="verificarEmail(index)"
                                                            @input="limparStatusEmail(index)" placeholder="exemplo@exemplo.com">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text" :id="'email-status-' + index">
                                                                <img src="{{asset('img/icons/question-circle-solid.svg')}}" alt="Status"
                                                                    style="width: 14px; opacity: 0.6;">
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <small class="form-text" :id="'email-message-' + index"></small>
                                                </div>
                                                <div x-show="campo == 'cpf'">
                                                    <label class="form-label">CPF</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control cpf-mask"
                                                            :name="'participantes[' + index + '][cpf]'" x-model="participante.cpf"
                                                            :id="'cpf-' + index" @blur="verificarCpf(index)"
                                                            @input="limparStatusCpf(index)" placeholder="999.999.999-99">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text" :id="'cpf-status-' + index">
                                                                <img src="{{asset('img/icons/question-circle-solid.svg')}}" alt="Status"
                                                                    style="width: 14px; opacity: 0.6;">
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <small class="form-text" :id="'cpf-message-' + index"></small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Categoria</label>
                                                <select class="form-control" :name="'participantes[' + index + '][categoria]'"
                                                    x-model="participante.categoria">
                                                    <option value="0" disabled>-- Escolha a categoria --</option>
                                                    @foreach ($evento->categoriasParticipantes()->where('permite_inscricao',
                                                    true)->get() as $categoria)
                                                    <option value="{{$categoria->id}}">{{$categoria->nome}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                        @if ($evento->categoriasParticipantes()->where('permite_inscricao', true)->exists())
                        <div class="campos-extras-container">
                        @foreach($evento->categoriasParticipantes as $categoria)
                        <div x-data="{id: {{$categoria->id}}}">
                                                <template x-if="participante.categoria == id">
                                                    <div class="campos-extras" :id="'campos-extras-{{$categoria->id}}-' + index">
                                    <div>
                                        @foreach ($categoria->camposNecessarios()->distinct()->orderBy('tipo')->get() as $campo)
                                        @if($campo->tipo == "endereco")
                                        <div>
                                            <div class="form-row">
                                                <div class="form-group col-sm-6">
                                                    <label :for="'endereco-cep-{{$campo->id}}-' + index">CEP</label>
                                                    <input :id="'endereco-cep-{{$campo->id}}-' + index"
                                                            :name="'participantes[' + index + '][endereco-cep-{{$campo->id}}]'"
                                                            :onblur="'pesquisacep(this.value, \'{{$campo->id}}-\' + index);'"
                                                            type="text"
                                                            class="form-control cep @error('endereco-cep-'.$campo->id) is-invalid @enderror"
                                                            placeholder="00000-000"
                                                            @if($campo->obrigatorio) required @endif>
                                                    @error('endereco-cep-'.$campo->id)
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label :for="'endereco-bairro-{{$campo->id}}-' + index">Bairro</label>
                                                    <input type="text"
                                                            class="form-control @error('endereco-bairro-'.$campo->id) is-invalid @enderror"
                                                            :id="'endereco-bairro-{{$campo->id}}-' + index"
                                                            :name="'participantes[' + index + '][endereco-bairro-{{$campo->id}}]'"
                                                            placeholder=""
                                                            @if($campo->obrigatorio) required @endif>
                                                    @error('endereco-bairro-'.$campo->id)
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-sm-9">
                                                    <label :for="'endereco-rua-{{$campo->id}}-' + index">Rua</label>
                                                    <input type="text"
                                                            class="form-control @error('endereco-rua-'.$campo->id) is-invalid @enderror"
                                                            :id="'endereco-rua-{{$campo->id}}-' + index"
                                                            :name="'participantes[' + index + '][endereco-rua-{{$campo->id}}]'"
                                                            placeholder=""
                                                            @if($campo->obrigatorio) required @endif>
                                                    @error('endereco-rua-'.$campo->id)
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-sm-3">
                                                    <label :for="'endereco-complemento-{{$campo->id}}-' + index">Complemento</label>
                                                    <input type="text"
                                                        class="form-control @error('endereco-complemento-'.$campo->id) is-invalid @enderror"
                                                        :id="'endereco-complemento-{{$campo->id}}-' + index"
                                                        :name="'participantes[' + index + '][endereco-complemento-{{$campo->id}}]'"
                                                        placeholder=""
                                                        @if($campo->obrigatorio) required @endif>
                                                    @error('endereco-complemento-'.$campo->id)
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-sm-6">
                                                        <label :for="'endereco-cidade-{{$campo->id}}-' + index">Cidade</label>
                                                        <input type="text"
                                                                class="form-control @error('endereco-cidade-'.$campo->id) is-invalid @enderror"
                                                                :id="'endereco-cidade-{{$campo->id}}-' + index"
                                                                :name="'participantes[' + index + '][endereco-cidade-{{$campo->id}}]'"
                                                                placeholder=""
                                                                @if($campo->obrigatorio) required @endif>
                                                    @error('endereco-cidade-'.$campo->id)
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-sm-3">
                                                    <label for="endereco-uf-{{$campo->id}}">UF</label>
                                                    <select class="form-control @error('endereco-uf-'.$campo->id) is-invalid @enderror" id="endereco-uf-{{$campo->id}}" name="endereco-uf-{{$campo->id}}" @if($campo->obrigatorio) required @endif>
                                                        <option value="" disabled selected hidden>
                                                            -- UF --
                                                        </option>
                                                        <option @if(old('endereco-uf-'.$campo->id) == "AC") selected
                                                            @endif value="AC">AC
                                                        </option>
                                                        <option @if(old('endereco-uf-'.$campo->id) == "AL") selected
                                                            @endif value="AL">AL
                                                        </option>
                                                        <option @if(old('endereco-uf-'.$campo->id) == "AP") selected
                                                            @endif value="AP">AP
                                                        </option>
                                                        <option @if(old('endereco-uf-'.$campo->id) == "AM") selected
                                                            @endif value="AM">AM
                                                        </option>
                                                        <option @if(old('endereco-uf-'.$campo->id) == "BA") selected
                                                            @endif value="BA">BA
                                                        </option>
                                                        <option @if(old('endereco-uf-'.$campo->id) == "CE") selected
                                                            @endif value="CE">CE
                                                        </option>
                                                        <option @if(old('endereco-uf-'.$campo->id) == "DF") selected
                                                            @endif value="DF">DF
                                                        </option>
                                                        <option @if(old('endereco-uf-'.$campo->id) == "ES") selected
                                                            @endif value="ES">ES
                                                        </option>
                                                        <option @if(old('endereco-uf-'.$campo->id) == "GO") selected
                                                            @endif value="GO">GO
                                                        </option>
                                                        <option @if(old('endereco-uf-'.$campo->id) == "MA") selected
                                                            @endif value="MA">MA
                                                        </option>
                                                        <option @if(old('endereco-uf-'.$campo->id) == "MT") selected
                                                            @endif value="MT">MT
                                                        </option>
                                                        <option @if(old('endereco-uf-'.$campo->id) == "MS") selected
                                                            @endif value="MS">MS
                                                        </option>
                                                        <option @if(old('endereco-uf-'.$campo->id) == "MG") selected
                                                            @endif value="MG">MG
                                                        </option>
                                                        <option @if(old('endereco-uf-'.$campo->id) == "PA") selected
                                                            @endif value="PA">PA
                                                        </option>
                                                        <option @if(old('endereco-uf-'.$campo->id) == "PB") selected
                                                            @endif value="PB">PB
                                                        </option>
                                                        <option @if(old('endereco-uf-'.$campo->id) == "PR") selected
                                                            @endif value="PR">PR
                                                        </option>
                                                        <option @if(old('endereco-uf-'.$campo->id) == "PE") selected
                                                            @endif value="PE">PE
                                                        </option>
                                                        <option @if(old('endereco-uf-'.$campo->id) == "PI") selected
                                                            @endif value="PI">PI
                                                        </option>
                                                        <option @if(old('endereco-uf-'.$campo->id) == "RJ") selected
                                                            @endif value="RJ">RJ
                                                        </option>
                                                        <option @if(old('endereco-uf-'.$campo->id) == "RN") selected
                                                            @endif value="RN">RN
                                                        </option>
                                                        <option @if(old('endereco-uf-'.$campo->id) == "RS") selected
                                                            @endif value="RS">RS
                                                        </option>
                                                        <option @if(old('endereco-uf-'.$campo->id) == "RO") selected
                                                            @endif value="RO">RO
                                                        </option>
                                                        <option @if(old('endereco-uf-'.$campo->id) == "RR") selected
                                                            @endif value="RR">RR
                                                        </option>
                                                        <option @if(old('endereco-uf-'.$campo->id) == "SC") selected
                                                            @endif value="SC">SC
                                                        </option>
                                                        <option @if(old('endereco-uf-'.$campo->id) == "SP") selected
                                                            @endif value="SP">SP
                                                        </option>
                                                        <option @if(old('endereco-uf-'.$campo->id) == "SE") selected
                                                            @endif value="SE">SE
                                                        </option>
                                                        <option @if(old('endereco-uf-'.$campo->id) == "TO") selected
                                                            @endif value="TO">TO
                                                        </option>
                                                    </select>
                                                    @error('endereco-uf-'.$campo->id)
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-sm-3">
                                                    <label :for="'endereco-numero-{{$campo->id}}-' + index">Número</label>
                                                    <input type="number"
                                                            class="form-control numero @error('endereco-numero-'.$campo->id) is-invalid @enderror"
                                                            :id="'endereco-numero-{{$campo->id}}-' + index"
                                                            :name="'participantes[' + index + '][endereco-numero-{{$campo->id}}]'"
                                                            placeholder="10"
                                                            @if($campo->obrigatorio) required @endif
                                                            maxlength="10">
                                                    @error('endereco-numero-'.$campo->id)
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        @elseif($campo->tipo == "date")
                                        <div class="form-group">
                                            <label :for="'date-{{$campo->id}}-' + index">{{$campo->titulo}}@if($campo->obrigatorio) * @endif</label>
                                            <input class="form-control @error('date-'.$campo->id) is-invalid @enderror"
                                                    type="date"
                                                    :name="'participantes[' + index + '][date-{{$campo->id}}]'"
                                                    :id="'date-{{$campo->id}}-' + index"
                                                    @if($campo->obrigatorio) required @endif>
                                            @error('date-'.$campo->id)
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        @elseif($campo->tipo == "select")
                                        <div class="form-group">
                                                <label :for="'select{{ $campo->id }}-' + index">{{ $campo->titulo }}</label>
                                                <select class="form-control"
                                                        :id="'select{{ $campo->id }}-' + index"
                                                        @if ($campo->obrigatorio) required @endif
                                                        :name="'participantes[' + index + '][select-{{$campo->id}}]'">
                                                <option @if ($campo->obrigatorio) disabled @endif selected>Selecione uma opção</option>
                                                @foreach ($campo->opcoes as $opcao)
                                                <option value="{{ $opcao->nome }}">{{ $opcao->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @elseif($campo->tipo == "email")
                                        <div class="form-group">
                                            <label :for="'email-{{$campo->id}}-' + index">{{$campo->titulo}}@if($campo->obrigatorio) * @endif</label>
                                            <input class="form-control @error('email-'.$campo->id) is-invalid @enderror"
                                                    type="email"
                                                    :name="'participantes[' + index + '][email-{{$campo->id}}]'"
                                                    :id="'email-{{$campo->id}}-' + index"
                                                    @if($campo->obrigatorio) required @endif>
                                            @error('email-'.$campo->id)
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        @elseif($campo->tipo == "text")
                                        <div class="form-group">
                                            <label :for="'text-{{$campo->id}}-' + index">{{$campo->titulo}}@if($campo->obrigatorio) * @endif</label>
                                            <input class="form-control @error('text-'.$campo->id) is-invalid @enderror"
                                                type="text"
                                                :name="'participantes[' + index + '][text-{{$campo->id}}]'"
                                                :id="'text-{{$campo->id}}-' + index"
                                                                       @if($campo->obrigatorio) required @endif>
                                            @error('text-'.$campo->id)
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        @elseif($campo->tipo == "cpf")
                                        <div class="form-group">
                                            <label :for="'cpf-{{$campo->id}}-' + index">{{$campo->titulo}}@if($campo->obrigatorio) * @endif</label>
                                            <input :id="'cpf-{{$campo->id}}-' + index"
                                                    type="text"
                                                    class="form-control cpf @error('cpf-'.$campo->id) is-invalid @enderror"
                                                    :name="'participantes[' + index + '][cpf-{{$campo->id}}]'"
                                                    autocomplete="cpf"
                                                    autofocus
                                                    @if($campo->obrigatorio) required @endif>
                                            @error('cpf-'.$campo->id)
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        @elseif($campo->tipo == "contato")
                                        <div class="form-group">
                                            <label :for="'contato-{{$campo->id}}-' + index">{{$campo->titulo}}@if($campo->obrigatorio) * @endif</label>
                                            <input :id="'contato-{{$campo->id}}-' + index"
                                                    type="text"
                                                    class="form-control celular @error('contato-'.$campo->id) is-invalid @enderror"
                                                    :name="'participantes[' + index + '][contato-{{$campo->id}}]'"
                                                    autocomplete="contato"
                                                    autofocus
                                                    @if($campo->obrigatorio) required @endif>
                                            @error('contato-'.$campo->id)
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        @elseif($campo->tipo == "file")
                                        <div class="form-group">
                                            <label :for="'file-{{$campo->id}}-' + index" class="">{{$campo->titulo}}@if($campo->obrigatorio) * @endif</label><br>
                                            <input type="file"
                                                    :id="'file-{{$campo->id}}-' + index"
                                                    class="form-control-file @error('file-'.$campo->id) is-invalid @enderror"
                                                    :name="'participantes[' + index + '][file-{{$campo->id}}]'"
                                                    @if($campo->obrigatorio) required @endif>
                                            <br>
                                            @error('file-'.$campo->id)
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
                    @else
                    @include('componentes.mensagens')

                    @endif
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                @if(auth()->check())
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary button-prevent-multiple-submits">
                        Inscrever participante
                    </button>
                </div>
                @endif
            </form>
        </div>
    </div>
</div>

@section('javascript')
@parent
    <script type="text/javascript" >
        $(document).ready(function($){
            $(document).on('input', '.cpf-mask', function() {
                $(this).mask('000.000.000-00');
            });
            $(document).on('change', 'input[name="identificador"]', function() {
                var campo = $(this).val();
                $('.participante-identificador').val(campo);
            });
        });

        function verificarEmail(index) {
            const email = document.querySelector(`#email-${index}`).value;
            const statusElement = document.querySelector(`#email-status-${index}`);
            const messageElement = document.querySelector(`#email-message-${index}`);
            const inputElement = document.querySelector(`#email-${index}`);

            $.ajax({
                type: 'GET',
                url: '{{ route("search.user") }}',
                data: {
                    email: email,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(res) {
                    if (res.user[0] != null) {
                        statusElement.innerHTML = '<img src="{{asset("img/icons/check-solid.svg")}}" alt="Encontrado" style="width: 14px; filter: brightness(0) saturate(100%) invert(48%) sepia(79%) saturate(2476%) hue-rotate(86deg) brightness(118%) contrast(119%);">';
                        messageElement.textContent = `Usuário encontrado: ${res.user[0].name}`;
                        messageElement.className = 'form-text text-success';
                        inputElement.classList.add('is-valid');
                        inputElement.classList.remove('is-invalid');
                    } else {
                        statusElement.innerHTML = '<img src="{{asset("img/icons/question-circle-solid.svg")}}" alt="Não encontrado" style="width: 14px; filter: brightness(0) saturate(100%) invert(83%) sepia(31%) saturate(638%) hue-rotate(359deg) brightness(103%) contrast(107%);">';
                        messageElement.textContent = 'Usuário não encontrado no sistema';
                        messageElement.className = 'form-text text-warning';
                        inputElement.classList.add('is-invalid');
                        inputElement.classList.remove('is-valid');
                    }
                },
                error: function(err) {
                    statusElement.innerHTML = '<img src="{{asset("img/icons/user-times-solid.svg")}}" alt="Erro" style="width: 14px; filter: brightness(0) saturate(100%) invert(27%) sepia(51%) saturate(2878%) hue-rotate(346deg) brightness(104%) contrast(97%);">';
                    messageElement.textContent = 'Erro ao verificar email';
                    messageElement.className = 'form-text text-danger';
                    inputElement.classList.add('is-invalid');
                    inputElement.classList.remove('is-valid');
                }
            });
        }

        function verificarCpf(index) {
            const cpf = document.querySelector(`#cpf-${index}`).value;
            const statusElement = document.querySelector(`#cpf-status-${index}`);
            const messageElement = document.querySelector(`#cpf-message-${index}`);
            const inputElement = document.querySelector(`#cpf-${index}`);


            $.ajax({
                type: 'GET',
                url: '{{ route("search.user") }}',
                data: {
                    cpf: cpf,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(res) {
                    if (res.user[0] != null) {
                        statusElement.innerHTML = '<img src="{{asset("img/icons/check-solid.svg")}}" alt="Encontrado" style="width: 14px; filter: brightness(0) saturate(100%) invert(48%) sepia(79%) saturate(2476%) hue-rotate(86deg) brightness(118%) contrast(119%);">';
                        messageElement.textContent = `Usuário encontrado: ${res.user[0].name}`;
                        messageElement.className = 'form-text text-success';
                        inputElement.classList.add('is-valid');
                        inputElement.classList.remove('is-invalid');
                    } else {
                        statusElement.innerHTML = '<img src="{{asset("img/icons/question-circle-solid.svg")}}" alt="Não encontrado" style="width: 14px; filter: brightness(0) saturate(100%) invert(83%) sepia(31%) saturate(638%) hue-rotate(359deg) brightness(103%) contrast(107%);">';
                        messageElement.textContent = 'Usuário não encontrado no sistema';
                        messageElement.className = 'form-text text-warning';
                        inputElement.classList.add('is-invalid');
                        inputElement.classList.remove('is-valid');
                    }
                },
                error: function(err) {
                    statusElement.innerHTML = '<img src="{{asset("img/icons/user-times-solid.svg")}}" alt="Erro" style="width: 14px; filter: brightness(0) saturate(100%) invert(27%) sepia(51%) saturate(2878%) hue-rotate(346deg) brightness(104%) contrast(97%);">';
                    messageElement.textContent = 'Erro ao verificar CPF';
                    messageElement.className = 'form-text text-danger';
                    inputElement.classList.add('is-invalid');
                    inputElement.classList.remove('is-valid');
                }
            });
        }

        function limparStatusEmail(index) {
            const statusElement = document.querySelector(`#email-status-${index}`);
            const messageElement = document.querySelector(`#email-message-${index}`);
            const inputElement = document.querySelector(`#email-${index}`);

            statusElement.innerHTML = '<img src="{{asset("img/icons/question-circle-solid.svg")}}" alt="Status" style="width: 14px; opacity: 0.6;">';
            messageElement.textContent = '';
            inputElement.classList.remove('is-valid', 'is-invalid');
        }

        function limparStatusCpf(index) {
            const statusElement = document.querySelector(`#cpf-status-${index}`);
            const messageElement = document.querySelector(`#cpf-message-${index}`);
            const inputElement = document.querySelector(`#cpf-${index}`);

            statusElement.innerHTML = '<img src="{{asset("img/icons/question-circle-solid.svg")}}" alt="Status" style="width: 14px; opacity: 0.6;">';
            messageElement.textContent = '';
            inputElement.classList.remove('is-valid', 'is-invalid');
        }


    </script>
@endsection
