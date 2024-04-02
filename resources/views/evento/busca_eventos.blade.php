@extends('layouts.app')

@section('content')
<div class="container" x-data="eventosSearch()">
    <div class="titulo-detalhes mb-4">
        <h1>Buscar eventos</h1>
    </div>
    <div class="form-row">
        <div class="form-group col-sm-6">
            <label for="nome">{{__('Nome')}}</label>
            <input id="nome" x-model="nome" class="form-control" type="text">
        </div>
        <div class="col-sm-6 form-group">
            <label for="tipo">{{__('Tipo do evento')}}</label>
            <select x-model="tipo" id="tipo" class="form-control">
                <option selected disabled value="">Selecione um tipo</option>
                <option value="Congresso">Congresso</option>
                <option value="Encontro">Encontro</option>
                <option value="Seminário">Seminário</option>
                <option value="Mesa redonda">Mesa redonda</option>
                <option value="Simpósio">Simpósio</option>
                <option value="Painel">Painel</option>
                <option value="Fórum">Fórum</option>
                <option value="Conferência">Conferência</option>
                <option value="Jornada">Jornada</option>
                <option value="Cursos">Cursos</option>
                <option value="Colóquio">Colóquio</option>
                <option value="Semana">Semana</option>
                <option value="Workshop">Workshop</option>
                <option value="outro">Outro</option>
            </select>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-sm-6">
            <label for="data-inicio">{{__('Data de início')}}</label>
            <input id="data-inicio" type="date" x-model="dataInicio" class="form-control">
        </div>
        <div class="form-group col-sm-6">
            <label for="tipo">{{__('Data de fim')}}</label>
            <input id="data-fim" type="date" x-model="dataFim" class="form-control">
        </div>
    </div>
    <div class="justify-content-end d-flex">
        <button type="button" class="btn btn-primary" x-on:click="fetchEventos()">Buscar</button>
    </div>
    <div class="row">
        <template x-if="eventos">
            <template x-for="evento in eventos">
                <div class="card card-busca-livre-evento">
                    <div class="row no-gutters">
                        <div class="col-md-5">
                            <img x-bind:src="'/storage/eventos/' + evento.id + '/logo.png'" alt=""/>
                        </div>
                        <div class="col-md-7">
                            <div class="card-body">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="titulo-detalhes">
                                                    <a x-bind:href="rotaVisualizar(evento.id)" class="text-dark"><h3 x-text="evento.nome"></h3></a>
                                                </div>
                                            </div>
                                            <div class="row my-2">
                                                <span class="btn btn-secondary mr-2" x-text="evento.tipo"></span>
                                                <span class="btn btn-info" x-text="evento.recolhimento"></span>
                                            </div>
                                            <div class="row">
                                                <span class="calendario-img">
                                                    <img src="{{ asset("/img/icons/calendar.png") }}" alt=""> <span x-text="formatarData(evento.dataInicio)"></span>
                                                </span>
                                            </div>
                                            <div class="row ponto-de-localizacao-img">
                                                <img src="{{ asset("/img/icons/location_pointer.png") }}" alt="">
                                                <p>
                                                    <span x-text="evento.endereco.rua"></span>, <span x-text="evento.endereco.numero"></span>, <span x-text="evento.endereco.bairro"></span> - <span x-text="evento.endereco.cidade"></span>/<span x-text="evento.endereco.uf"></span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </template>
    </template>
    </div>
</div>
@endsection

@section('javascript')
    <script>
        function rotaVisualizar(id) {
            return `{{ route('evento.visualizar', ':id') }}`.replace(':id', id);
        }
        function formatarData(data) {
            return new Date(data).toLocaleDateString();
        }
        function eventosSearch() {
            return {
                nome: '',
                dataInicio: '',
                dataFim: '',
                tipo: '',
                eventos: null,
                fetchEventos() {
                    const params = new URLSearchParams({
                        nome: this.nome,
                        tipo: this.tipo,
                        dataInicio: this.dataInicio,
                        dataFim: this.dataFim,
                    });
                    fetch("{{ route('busca.livre.ajax') }}" + "?" + params.toString())
                        .then(res => res.json())
                        .then(data => this.eventos = data);
                }
            }
        }
    </script>
@endsection
