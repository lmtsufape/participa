@extends('coordenador.detalhesEvento')

@section('menu')
    <div class="row justify-content-center titulo-detalhes">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-10">
                    <h1>Emitir certificados</h1>
                </div>
            </div>
        </div>
    </div>
    @if(session('success'))
        <div class="row">
            <div class="col-md-12" style="margin-top: 5px;">
                <div class="alert alert-success">
                    <p>{{session('success')}}</p>
                </div>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="row">
            <div class="col-md-12" style="margin-top: 5px;">
                <div class="alert alert-danger">
                    <p>{{session('error')}}</p>
                </div>
            </div>
        </div>
    @endif
    <div class="container" style="position: relative;" x-data="handler()">
        <form id="formEnviarCertificado" action="{{route('coord.enviarCertificado')}}" method="POST">
            @csrf
            <input type="hidden" name="eventoId" value="{{$evento->id}}">
            <div class="form-row">
                <div class="form-group col-md-12 mt-4 mb-4">
                    <label for="idSelecionarDestinatario" class="h4">Destinatários</label>
                    <select name="destinatario" class="form-control @error('destinatarios') is-invalid @enderror"
                            id="idSelecionarDestinatario" x-on:change="selecionarDestinatario({{$evento->id}})"
                            x-model="tipo">
                        <option value="">-- Selecione os destinatários --</option>
                        @foreach ($destinatarios as $key => $destinatario)
                            <option value="{{$key}}">{{$destinatario}}</option>
                        @endforeach
                    </select>
                    @error('destinatario')
                    <div id="validationServer03Feedback" class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="col-sm-12 form-group mt-3 mb-3" id="outrasComissoesDivSelect" style="display: none;">
                    <label for="tipo_comissao_id" class="h4">{{__('Comissão')}}</label>
                    <select name="tipo_comissao_id" id="tipo_comissao_id" x-model="comissao"
                            class="form-control @error('tipo_comissao_id') is-invalid @enderror"
                            x-on:change="selecionarDestinatario({{$evento->id}})">
                        <option value="" disabled selected>-- Selecione a comissão --</option>
                        @foreach ($evento->outrasComissoes as $comissao)
                            <option value="{{$comissao->id}}"> {{$comissao->nome}} </option>
                        @endforeach
                    </select>
                    @error('tipo_comissao_id')
                    <div id="validationServer03Feedback" class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="col-sm-12 form-group mt-3 mb-3" id="atividadeDivSelect" style="display: none;">
                    <label for="atividade_id" class="h4">{{__('Atividade')}}</label>
                    <select id="atividade_id" x-model="atividade"
                            class="form-control @error('atividade_id') is-invalid @enderror"
                            x-on:change="selecionarDestinatario({{$evento->id}})">
                        <option value="" disabled selected>-- Selecione uma atividade --</option>
                        <option value="0">Todas as atividades</option>
                        @foreach ($evento->atividade as $atividade)
                            <option value="{{$atividade->id}}"> {{$atividade->titulo}} </option>
                        @endforeach
                    </select>
                    @error('atividade_id')
                    <div id="validationServer03Feedback" class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="col-sm-12 mt-4 mb-3">
                    <h4>Lista de Destinatários</h4>
                </div>
                <div class="form-group col-md-12 mb-2">
                    <div class="form-check">
                        <input type="checkbox" id="selecionartodos" onclick="selecionarTodosDestinatarios(this)">
                        <label for="selecionartodos">Selecionar todos</label>
                    </div>
                </div>
                <div class="form-group col-md-12 mb-4">
                    <div style="width:100%; height:250px; display: inline-block; border: 2px solid #f2f2f2; border-radius: 2px; overflow:auto; padding: 15px;">
                        <table id="tabelaDestinatarios">
                            <tbody id="dentroTabelaDestinatarios">
                                <template x-for="(destinatario, index) in destinatarios" :key="index">
                                    <div class="d-flex justify-content-left">
                                        <template x-if="tipo == 1">
                                            <div class="form-check">
                                                <input class="checkbox_destinatario" type="checkbox" name="destinatarios[]" :value="destinatarios[index].id" :id="'destinatarios_'+index"  x-on:change="syncHidden('trabalhos_', index)">
                                                <input class="d-none" type="checkbox" name="trabalhos[]" :value="trabalhos[index].id" :id="'trabalhos_'+index">
                                                <label :for="'destinatarios_'+index"><strong x-text="destinatario.name + ' - ' + trabalhos[index].titulo"></strong><span x-text="' ('+destinatario.email+')'"></span> <span class="font-weight-bold" x-text="trabalhos[index].parecer_final == null ? '' : trabalhos[index].parecer_final == true ? 'Aprovado' : 'Reprovado'"></span> </label>
                                            </div>
                                        </template>
                                        <template x-if="tipo == 6">
                                            <div class="form-check">
                                                <input class="checkbox_destinatario" type="checkbox" name="destinatarios[]" :value="destinatarios[index].id" :id="'destinatarios_'+index" x-on:change="syncHidden('palestras_', index)">
                                                <input class="d-none" type="checkbox" name="palestras[]" :value="palestras[index].id" :id="'palestras_'+index">
                                                <label :for="'destinatarios_'+index"><strong x-text="destinatario.nome + ' - ' + palestras[index].titulo"></strong><span x-text="' ('+destinatario.email+')'"></span></label>
                                            </div>
                                        </template>
                                        <template x-if="tipo == 8">
                                            <div class="form-check">
                                                <input class="checkbox_destinatario" type="checkbox" name="destinatarios[]" :value="destinatarios[index].id" :id="'destinatarios_'+index">
                                                <label :for="'destinatarios_'+index"><strong x-text="destinatario.name"></strong><span x-text="' ('+destinatario.email+')'"></span></label>
                                            </div>
                                        </template>
                                        <template x-if="tipo == 9">
                                            <div class="form-check">
                                                <input class="checkbox_destinatario" type="checkbox" name="destinatarios[]" :value="destinatarios[index].id" :id="'destinatarios_'+index" x-on:change="syncHidden('atividades_', index)">
                                                <input class="d-none" type="checkbox" name="atividades[]" :value="atividades[index].id" :id="'atividades_'+index">
                                                <label :for="'destinatarios_'+index"><strong x-text="destinatario.name + ' - ' + atividades[index].titulo"></strong><span x-text="' ('+destinatario.email+')'"></span></label>
                                            </div>
                                        </template>
                                        <template x-if="outros()">
                                            <div class="form-check">
                                                <input class="checkbox_destinatario" type="checkbox" name="destinatarios[]" :value="destinatarios[index].id" :id="'destinatarios_'+index">
                                                <label :for="'destinatarios_'+index"><strong x-text="destinatario.name"></strong><span x-text="' ('+destinatario.email+')'"></span></label>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-sm-12 form-group mt-4 mb-4">
                    <h4>Certificados</h4>
                    <input type="hidden" class="checkbox_certificado @error('certificado') is-invalid @enderror">
                    <div id="listaCertificados" class="row cards-eventos-index mt-3">
                        <template x-for="(certificado, index) in certificados" :key="index">
                            <div class="card mt-0" style="height: 10rem; width: 10rem;">
                                <img :src="'/storage/'+certificado.caminho" class="card-img-top h-50" alt="...">
                                <div class="card-body pt-0">
                                    <div class="card-title">
                                        <div class="form-group form-check">
                                            <input class="form-check-input" type="radio" name="certificado" :value="certificado.id" :id="'certificado'+index">
                                            <label :for="'certificado'+index" x-text="certificado.nome" class="h5 form-check-label"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                    @error('certificado')
                        <div id="validationServer03Feedback" class="invalid-feedback">
                            <strong>{{$message}}</strong>
                        </div>
                    @enderror
                </div>
                <div class="form-group col-md-12 mt-4 mb-4">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="anexo-checkbox" name="sem_anexo" value="1">
                        <label class="custom-control-label" for="anexo-checkbox">Não enviar arquivo em anexo.</label>
                        <small class="d-block mt-2">Selecione esta opção se você precisar enviar uma grande quantidade de certificados.</small>
                      </div>
                </div>
            </div>
            <div class="row justify-content-center mt-4">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary  button-prevent-multiple-submits" style="width:100%">
                         {{ __('Enviar') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
<script src="{{ asset('js/checkbox_marcar_todos.js') }}" defer></script>
<script>
    function selecionarTodosDestinatarios(source) {
        const container = document.getElementById('dentroTabelaDestinatarios');
            const checkboxes = container.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = source.checked;
            });
    }
    function handler(){
        return {
            certificados: [],
            destinatarios: [],
            atividades: [],
            palestras: [],
            trabalhos: [],
            tipo: '',
            comissao: '',
            atividade: '',
            outros() {
                return ['2','3','4','5','7', '10','11'].includes(this.tipo)
            },
            selecionarDestinatario (eventoId) {
                this.destinatarios = []
                this.atividades = []
                this.certificados = []
                this.palestras = []
                this.trabalhos = []
                dados = {"destinatario":this.tipo, "eventoId":eventoId}
                $('#atividadeDivSelect').hide()
                $('#outrasComissoesDivSelect').hide()
                if (this.tipo == 8) {
                    $('#outrasComissoesDivSelect').show()
                    if (this.comissao == '') return
                    else dados.tipo_comissao_id = this.comissao
                }
                if (this.tipo == 9) {
                    $('#atividadeDivSelect').show()
                    if (this.atividade == '') return
                    else dados.atividade = this.atividade
                }
                $.ajax({
                    url: 'ajax-listar-destinatarios',
                    type: 'GET',
                    dataType: "json",
                    data: dados,
                    success: (data) => {
                        this.destinatarios = data.destinatarios;
                        this.certificados = data.certificados;
                        if (this.tipo == '1')
                            this.trabalhos = data.trabalhos;
                        else if (this.tipo == '6')
                            this.palestras = data.palestras;
                        else if (this.tipo == '8')
                            this.comissao = data.comissao.id;
                        else if (this.tipo == '9')
                            this.atividades = data.atividades;
                    }
                });
            },
        };
    }
    function syncHidden(tipo, index) {
        $('#'+tipo+index).prop( "checked", $('#destinatarios_'+index).is(":checked") );
    }
</script>

