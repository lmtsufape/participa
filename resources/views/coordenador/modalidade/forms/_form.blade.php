
<div class="row">
    <div class="col-md-12 mb-3">
        <div class="card border-0 shadow-sm border-start border-4 border-my-primary">

            <div class="card-body">

                <div class="mb-3 pb-3 border-bottom">
                    <h5 class="mb-1 fw-bold text-my-primary">
                        Informações do Formulário
                    </h5>

                    <small class="text-muted">
                        Preencha os dados principais do formulário de avaliação.
                    </small>
                </div>

                <div class="mb-3">
                    <label for="titulo" class="form-label">
                        Título do Formulário
                    </label>

                    <input type="text" class="form-control" id="titulo" value="{{ old('titulo', $form->titulo ?? '') }}"
                        name="titulo" placeholder="Ex: Formulário de avaliação dos trabalhos" required>
                </div>

                <div class="mb-0">
                    <label for="instrucoes" class="form-label">
                        Orientações aos(as) avaliadores(as)
                    </label>

                    <textarea class="form-control" name="instrucoes" id="instrucoes" rows="4"
                        placeholder="Digite as orientações que serão exibidas aos avaliadores...">{{ old('instrucoes', $form->instrucoes ?? '') }}</textarea>
                </div>

            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div x-data="handler({
                perguntas: @js(old('perguntas', $form->perguntas ?? []))
            })">
            <div class="d-flex flex-column gap-3">
                <template x-for="(pergunta, index) in perguntas" :key="pergunta.id ?? index">
                    <div class="card border-0 shadow-sm mb-3 border-start border-4 border-my-primary">

                        <div class="card-body">
                            <!-- Cabeçalho -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0 fw-bold">
                                    Pergunta <span x-text="index + 1"></span>
                                </h6>

                                <input
                                    type="hidden"
                                    :name="'perguntas[' + index + '][id]'"
                                    :value="pergunta.id ?? ''"
                                >

                                <input type="hidden" :name="'perguntas[' + index + '][ordem]'"
                                    :value="index + 1">

                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                        @click="sobePergunta(index)" :disabled="index === 0"
                                        title="Mover para cima">
                                        <i class="bi bi-arrow-up"></i>
                                    </button>

                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                        @click="descePergunta(index)" :disabled="index === perguntas.length - 1"
                                        title="Mover para baixo">
                                        <i class="bi bi-arrow-down"></i>
                                    </button>

                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                        @click="removePergunta(index)" title="Remover pergunta">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Linha de configuração -->
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Tipo
                                    </label>

                                    <select x-model="pergunta.tipo" class="form-control" :id="'tipo-' + index"
                                        :name="'perguntas[' + index + '][tipo]'">
                                        <option value="paragrafo">Parágrafo</option>
                                        <option value="radio">Múltipla escolha</option>
                                    </select>
                                </div>

                                <div class="col-md-6 d-flex align-items-end">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" x-model="pergunta.visibilidade"
                                            :name="'perguntas[' + index + '][visibilidade]'" type="checkbox"
                                            :id="'visibilidade-' + index" value="1">

                                        <label class="form-check-label" :for="'visibilidade-' + index">
                                            Visível para o autor
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Pergunta -->
                            <div class="form-group mb-3">
                                <label class="form-label">
                                    Pergunta
                                </label>

                                <textarea :id="'ckeditor-texto-' + index" class="form-control" rows="3" x-model="pergunta.titulo"
                                    :name="'perguntas[' + index + '][titulo]'" placeholder="Digite a pergunta" required></textarea>
                            </div>

                            <!-- Resposta -->
                            <div class="form-group mb-0">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label mb-0">
                                        Resposta
                                    </label>

                                    <template x-if="pergunta.tipo == 'radio'">
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                            @click="adicionaOpcao(index)">
                                            <i class="bi bi-plus-lg"></i>
                                            Adicionar alternativa
                                        </button>
                                    </template>
                                </div>

                                <!-- Resposta parágrafo -->
                                <template x-if="pergunta.tipo == 'paragrafo'">
                                    <input type="text" disabled class="form-control bg-light"
                                        placeholder="Resposta em texto livre">
                                </template>

                                <!-- Resposta múltipla escolha -->
                                <template x-if="pergunta.tipo == 'radio'">
                                    <div>

                                        <template x-for="(opcao, j) in pergunta.opcoes" :key="opcao.id ?? j">
                                            <div class="input-group mb-2">

                                                <input
                                                    type="hidden"
                                                    :name="'perguntas[' + index + '][opcoes][' + j + '][id]'"
                                                    :value="opcao.id ?? ''"
                                                >

                                                <input type="hidden"
                                                    :name="'perguntas[' + index + '][opcoes][' + j + '][ordem]'"
                                                    :value="j + 1">

                                                <span class="input-group-text bg-white">
                                                    <input class="form-check-input mt-0" type="checkbox"
                                                        :name="'perguntas[' + index + '][opcoes_marcadas][]'"
                                                        :value="j">
                                                </span>

                                                <input x-model="opcao.titulo"
                                                    :name="'perguntas[' + index + '][opcoes][' + j + '][titulo]'"
                                                    type="text" class="form-control"
                                                    placeholder="Alternativa">

                                                <button type="button" class="btn btn-outline-danger"
                                                    @click="removeOpcao(index, j)"
                                                    title="Remover alternativa">
                                                    <i class="bi bi-trash"></i>
                                                </button>

                                            </div>
                                        </template>

                                    </div>
                                </template>
                            </div>

                        </div>
                    </div>
                </template>
            </div>

            <button type="button" @click="adicionaPergunta" class="btn btn-primary w-100 mt-2 py-2">
                <i class="bi bi-plus-lg"></i>
                Adicionar pergunta
            </button>

        </div>
    </div>
</div>


@section('javascript')
    @parent
    <script>
        function handler(config = {}) {
            return {
                perguntas: config.perguntas && config.perguntas.length ? config.perguntas : [{
                    titulo: '',
                    tipo: 'radio',
                    opcoes: [{
                        titulo: '',
                        tipo: 'radio',
                        ordem: 1,
                    }, {
                        titulo: '',
                        tipo: 'radio',
                        ordem: 2,
                    }],
                    ordem: 1,
                    visibilidade: true
                }],
                adicionaPergunta() {
                    this.perguntas.push({
                        titulo: '',
                        tipo: 'radio',
                        opcoes: [{
                            titulo: ''
                        }, {
                            titulo: ''
                        }],
                        visibilidade: true
                    });
                },
                removePergunta(index) {
                    this.perguntas.splice(index, 1);
                },
                sobePergunta(index) {
                    if (index > 0) {
                        temp = this.perguntas[index - 1];
                        this.perguntas[index - 1] = this.perguntas[index];
                        this.perguntas[index] = temp;
                    }
                },
                descePergunta(index) {
                    if (index >= 0 && (index + 1) < this.perguntas.length) {
                        temp = this.perguntas[index + 1];
                        this.perguntas[index + 1] = this.perguntas[index];
                        this.perguntas[index] = temp;
                    }
                },
                adicionaOpcao(i, j) {
                    this.perguntas[i].opcoes.splice(j + 1, 0, {
                        titulo: ''
                    });
                },
                removeOpcao(i, j) {
                    this.perguntas[i].opcoes.splice(j, 1);
                }
            }
        }
        $(document).ready(function() {
            CKEDITOR.replace('ckeditor-texto');
            CKEDITOR.replace('instrucoes');
        });
    </script>
@endsection
