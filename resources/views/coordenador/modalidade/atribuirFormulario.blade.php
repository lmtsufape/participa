@extends('coordenador.detalhesEvento')
@section('menu')
<div id="divListarCriterio" class="comissao">
    <div class="row">
        <div class="col-lg-12">
            <h3 class="titulo-detalhes">Adicionar formulário na modalidade:
                <p>
                    <strong>{{$modalidade->nome}}</strong>
                </p>
            </h3>
        </div>
    </div>
</div>
<form action="{{route('coord.salvar.form')}}" method="post">
    @csrf
    <input type="hidden" name="modalidade_id" value="{{$modalidade->id}}">
    <input type="hidden" name="evento_id" value="{{$evento->id}}">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <label> <strong> Titulo do Formulário*:</strong></label>
                    <input type="text" class="form-control mb-2" name="titulo" required>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <label for="instrucoes"> <strong> Orientações aos(as) avaliadores(as):</strong></label>
                    <textarea type="text" class="form-control mb-2" name="instrucoes" id="instrucoes">
                        {{old('instrucoes', '')}}
                    </textarea>
                </div>
            </div>
        </div>
        <div class="col-sm-12" x-data="handler()">
            <div class="flexContainer">
                <template x-for="(pergunta, index) in perguntas" :key="index">
                    <div class="item card">
                        <div class="row card-body">
                            <div class="form-group col-sm-12">
                                <label>Pergunta</label>
                                <textarea type="text" id="ckeditor-texto" class=" form-control " x-model="pergunta.titulo" name="perguntas[]" required></textarea>
                            </div>
                            <div class="form-group col-sm-12">
                                <label>Resposta</label>
                                <template x-if="pergunta.tipo == 'paragrafo'">
                                    <input type="text" disabled class="form-control">
                                </template>
                                <template x-if="pergunta.tipo == 'radio'">
                                    <template x-for="(opcao, j) in pergunta.opcoes" :key="j">
                                        <div class="row my-1">
                                            <div class="col-sm-8 col-md-10">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <input type="checkbox" value="1">
                                                        </div>
                                                    </div>
                                                    <input x-model="opcao.titulo" :name="'opcoes['+index+'][]'" type="text" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-md-2 d-flex justify-content-center">
                                                <button type="button" class="btn btn-link" @click="adicionaOpcao(index, j)"><i class="fas fa-plus"></i></button>
                                                <button type="button" class="btn btn-link" @click="removeOpcao(index, j)"><i class="fas fa-trash-alt"></i></button>
                                            </div>
                                        </div>
                                    </template>
                                </template>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" x-model="pergunta.visibilidade" :value="pergunta.visibilidade" :name="'visibilidades['+index+']'" type="checkbox"><small>Visível para o autor? (selecione se sim)</small>
                                    </label>
                                </div>
                            </div>
                            <div class="form-inline col-sm-12 col-md-6 col-lg-4">
                                <label class="mr-2" for="exampleFormControlSelect1">Tipo</label>
                                <select x-model="pergunta.tipo" class="form-control" name="tipos[]">
                                    <option value="paragrafo">Parágrafo</option>
                                    <option value="radio">Multipla escolha</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-4 d-flex justify-content-center">
                                <button type="button" class="btn btn-link" @click="removePergunta(index)">
                                    <i class="fas fa-trash-alt fa-2x"></i>
                                </button>
                                <button type="button" class="btn btn-link" @click="sobePergunta(index)">
                                    <i class="fas fa-arrow-up fa-2x" id="arrow-up"></i>
                                </button>
                                <button type="button" class="btn btn-link" @click="descePergunta(index)">
                                    <i class="fas fa-arrow-down fa-2x" id="arrow-down"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            <button type="button" @click="adicionaPergunta" class="btn btn-primary col-12 mt-1">Adicionar pergunta</button>
            <button type="submit" class="btn btn-success col-12 mt-1">
                Salvar
            </button>
        </div>
    </div>
</form>
@endsection
@section('javascript')
    @parent
    <script>
        function handler() {
            return {
                perguntas: [{
                    titulo: '',
                    tipo: 'radio',
                    opcoes: [{
                        titulo: ''
                    }, {
                        titulo: ''
                    }],
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
