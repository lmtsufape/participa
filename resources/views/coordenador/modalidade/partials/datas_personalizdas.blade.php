<x-forms.section
    title="Datas personalizadas"
    description="Adicione outros marcos importantes ao cronograma da modalidade."
    icon="bi-calendar-plus"
>
    <div id="datas">

        <template x-for="(data, index) in datas" :key="data.key">

            <div class="border rounded-3 p-3 mb-3">

                {{-- Cabeçalho --}}
                <div class="d-flex align-items-center justify-content-between mb-3">

                    <div>
                        <span class="fw-semibold">
                            Data personalizada
                        </span>

                        <small
                            class="text-muted ms-1"
                            x-text="'#' + (index + 1)"
                        ></small>
                    </div>

                    <button
                        type="button"
                        class="btn btn-sm btn-outline-danger"
                        title="Remover data"
                        @click="removeData(index)"
                    >
                        <i class="bi bi-trash"></i>
                    </button>

                </div>

                <div class="row g-3">

                    {{-- Nome --}}
                    <div class="col-lg-4">

                        <label
                            class="form-label fw-semibold"
                            :for="'data-extra-nome-' + data.key"
                        >
                            Nome da data <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            x-model="data.nome"
                            :id="'data-extra-nome-' + data.key"
                            :name="'nomeDataExtra[' + data.key + ']'"
                            placeholder="Ex.: Envio da versão final"
                            required
                        >

                    </div>

                    {{-- Data inicial --}}
                    <div class="col-md-6 col-lg-3">

                        <label
                            class="form-label fw-semibold"
                            :for="'data-extra-inicio-' + data.key"
                        >
                            Data inicial <span class="text-danger">*</span>
                        </label>

                        <input
                            type="datetime-local"
                            class="form-control"
                            x-model="data.inicio"
                            :id="'data-extra-inicio-' + data.key"
                            :name="'inicioDataExtra[' + data.key + ']'"
                            required
                        >

                    </div>

                    {{-- Data final --}}
                    <div class="col-md-6 col-lg-3">

                        <label
                            class="form-label fw-semibold"
                            :for="'data-extra-final-' + data.key"
                        >
                            Data final <span class="text-danger">*</span>
                        </label>

                        <input
                            type="datetime-local"
                            class="form-control"
                            x-model="data.fim"
                            :id="'data-extra-final-' + data.key"
                            :name="'finalDataExtra[' + data.key + ']'"
                            required
                        >

                    </div>

                    {{-- Submissão --}}
                    <div class="col-lg-2 d-flex align-items-end">

                        <div class="form-check form-switch mb-2">

                            <input
                                type="checkbox"
                                class="form-check-input"
                                value="on"
                                x-model="data.permitirSubmissao"
                                :id="'data-extra-submissao-' + data.key"
                                :name="'submissaoDataExtra[' + data.key + ']'"
                            >

                            <label
                                class="form-check-label"
                                :for="'data-extra-submissao-' + data.key"
                            >
                                Permitir submissão
                            </label>

                        </div>

                    </div>

                </div>

            </div>

        </template>


        {{-- Estado vazio --}}
        <div
            x-show="datas.length === 0"
            class="text-muted small mb-3"
        >
            Nenhuma data personalizada adicionada.
        </div>


        {{-- Adicionar --}}
        <button
            type="button"
            class="btn btn-outline-primary"
            @click="adicionaData"
        >
            <i class="bi bi-plus-lg me-1"></i>
            Adicionar data
        </button>

    </div>

</x-forms.section>


<script>
    function handler(datas = []) {

        const oldDatas = @json(old('datasExtras'));

        const origem = oldDatas ?? datas ?? [];

        const itens = Object.entries(origem).map(([key, data]) => ({
            key: data.id ?? Number(key),

            nome: data.nome ?? '',

            inicio: formatarData(data.inicio),

            fim: formatarData(data.fim),

            permitirSubmissao:
                data.permitirSubmissao ??
                data.permitir_submissao ??
                false
        }));

        const maiorId = itens.length
            ? Math.max(...itens.map(item => Number(item.key) || 0))
            : 0;

        return {

            datas: itens,

            maior: maiorId,

            adicionaData() {

                this.maior++;

                this.datas.push({
                    key: this.maior,
                    nome: '',
                    inicio: '',
                    fim: '',
                    permitirSubmissao: false
                });

            },

            removeData(index) {
                this.datas.splice(index, 1);
            }

        };
    }


    function formatarData(data) {

        if (!data) {
            return '';
        }

        return data
            .replace(' ', 'T')
            .substring(0, 16);
    }
</script>