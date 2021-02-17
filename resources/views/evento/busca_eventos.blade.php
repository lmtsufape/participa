@extends('layouts.app')

@section('content')
<div class="container" style="position: relative; top: 100px; padding-bottom: 200px;">
    <div class="row">
        <div class="col-sm-12 titulo-detalhes">
            <h1>Buscar eventos</h1>
        </div>
    </div>
    <br>
    <form id="form-buscar-eventos" action="">
        <div class="row form-group">
            <div class="col-sm-6" id="div-nome">
                <label for="nome">{{__('Nome')}}</label>
                <input id="nome" name="nome" class="form-control" type="text">
            </div>
            <div class="col-sm-4">
                <label for="tipo_busca">{{__('Filtrar por')}}</label>
                <select name="tipo_busca" id="tipo_busca" class="form-control" onchange="filtro()">
                    <option value="nome" selected>Nome</option>
                    <option value="tipo">Tipo</option>
                    <option value="data_inicio">Data de início</option>
                    <option value="data_fim">Data de fim</option>
                    <option value="nome_tipo">Nome e tipo</option>
                    <option value="nome_data_inicio">Nome e data de início</option>
                    <option value="nome_data_fim">Nome e data de fim</option>
                    <option value="tipo_data_inicio">Tipo e data de início</option>
                    <option value="tipo_data_fim">Tipo e data de fim</option>
                    <option value="datas">Data de início e data de fim</option>
                    <option value="nome_datas">Nome e datas de início e fim</option>
                    <option value="tipo_datas">Tipo e datas de início e fim</option>
                    <option value="todos">Nome, tipo e datas de início e fim</option>
                </select>
            </div>
            <div class="col-sm-2" style="top: 30px;">
                <button type="button" class="btn btn-buscar-evento" onclick="buscarEventosAjax()">Buscar</button>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4" id="div-tipo" style="display: none;">
                <label for="tipo">{{__('Tipo do evento')}}</label>
                <select name="tipo" id="tipo" class="form-control">
                    <option disabled selected hidden value="">-- Tipo --</option>
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
            <div class="col-sm-3" id="div-data-inicio" style="display: none;">
                <label for="data-inicio">{{__('Data de início')}}</label>
                <input id="data-inicio" type="date" name="data-inicio" class="form-control">
            </div>
            <div class="col-sm-3" id="div-data-fim" style="display: none;">
                <label for="tipo">{{__('Data de fim')}}</label>
                <input id="data-fim" type="date" name="data-fim" class="form-control">
            </div>
        </div>
    </form>
    <br>
    <div class="row">
        <div class="col-sm-12" id="resultados">

        </div>
    </div>
    <br>
</div>

@include('componentes.footer')

@endsection

@section('javascript')
    <script>
        function filtro() {
            var tipo_busca = document.getElementById("tipo_busca");
            // alert(tipo_busca.value);

            switch (tipo_busca.value) {
                case "nome":
                    setar_display("block", "none", "none", "none");
                    break;
                case "tipo":
                    setar_display("none", "block", "none", "none");
                    break;
                case "data_inicio": 
                    setar_display("none", "none", "block", "none");
                    break;
                case "data_fim": 
                    setar_display("none", "none", "none", "block");
                    break;
                case "nome_tipo": 
                    setar_display("block", "block", "none", "none");
                    break;
                case "nome_data_inicio": 
                    setar_display("block", "none", "block", "none");
                    break;
                case "nome_data_fim": 
                    setar_display("block", "none", "none", "block");
                    break;
                case "tipo_data_inicio": 
                    setar_display("none", "block", "block", "none");
                    break;
                case "tipo_data_fim": 
                    setar_display("none", "block", "none", "block");
                    break;
                case "nome_datas": 
                    setar_display("block", "none", "block", "block");
                    break;
                case "tipo_datas":
                    setar_display("none", "block", "block", "block");
                    break;
                case "datas": 
                    setar_display("none", "none", "block", "block");
                    break;
                case "todos": 
                    setar_display("block", "block", "block", "block");
                    break;
                default:
                    setar_display("block", "none", "none", "none");
                    break;
            }
            
            limparInputs();
        }

        function setar_display(nome_set, tipo_set, data_inicio_set, data_fim_set) {
            var nome = document.getElementById("div-nome");
            var tipo = document.getElementById("div-tipo");
            var data_inicio = document.getElementById("div-data-inicio");
            var data_fim = document.getElementById("div-data-fim");

            nome.style.display = nome_set;
            tipo.style.display = tipo_set;
            data_inicio.style.display = data_inicio_set;
            data_fim.style.display = data_fim_set;
        }

        function limparInputs() {
            document.getElementById("nome").value = "";
            document.getElementById("tipo").value = "";
            document.getElementById("data-inicio").value = "";
            document.getElementById("data-fim").value = "";
        }

        function buscarEventosAjax() {
            $.ajaxSetup({
            headers: {
                // 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
            });
            jQuery.ajax({
                url: "{{ route('busca.livre.ajax') }}",
                method: 'get',
                data: {
                    tipo_busca: document.getElementById("tipo_busca").value,
                    nome: document.getElementById("nome").value,
                    tipo: document.getElementById("tipo").value,
                    data_inicio: document.getElementById("data-inicio").value,
                    data_fim:  document.getElementById("data-fim").value,
                },
                success: function(result){
                    // console.log(result);
                    if (result != null) {
                        $('#resultados').html("");
                        var divs = "";
                        $.each(result, function(i, obj) {
                            var data = new Date(obj.dataInicio); 
                            if (result[i].fotoEvento != null && result[i].recolhimento == "pago") {
                                divs += "<div class='card card-busca-livre-evento'>" +
                                            "<div class='row no-gutters'>" +
                                               "<div class='col-md-5'>" +
                                                    "<img src='{{url('/')}}/storage/eventos/"+obj.id_evento+"/logo.png' alt=''>"+
                                                "</div>" +
                                                "<div class='col-md-7'>" +
                                                    "<div class='card-body'>" +
                                                        "<div class='container'>" +
                                                            "<div class='row'>" +
                                                                "<div class='col-sm-12'>" +
                                                                    "<div class='row'>" +
                                                                        "<div class='titulo-detalhes'>" +
                                                                            "<a href='{{url('/')}}/evento/visualizar/"+obj.id_evento+"' style='color: black;'><h3>"+obj.nome+"</h3></a>" +
                                                                        "</div>" +
                                                                    "</div>" +
                                                                    "<div class='row' style='color: white;'>" +
                                                                        "<a class='btn cor-aleatoria' style='pointer-events: none; margin: 10px; margin-left: 0;'>#"+obj.tipo+"</a>"+
                                                                        "<a class='btn pago' style='pointer-events: none; margin: 10px; margin-left: 0;'>Pago</a>"+
                                                                    "</div>" +
                                                                    "<div class='row'>" +
                                                                        "<span class='calendario-img'>" +
                                                                            "<img src='{{ asset('/img/icons/calendar.png') }}' alt=''> "+data.toLocaleDateString()+
                                                                        "</span>" +
                                                                        "<span class='relogio-img'>" +
                                                                            "<img src='{{ asset('/img/icons/clock.png') }}' alt=''> Colocar a hora aqui " +
                                                                        "</span>" +
                                                                   "</div>" +
                                                                    "<div class='row ponto-de-localizacao-img'>" +
                                                                        "<img src='{{ asset('/img/icons/location_pointer.png') }}' alt=''>"+obj.rua+", "+obj.numero+", "+obj.bairro+" - "+obj.cidade+"/"+obj.uf+"." +
                                                                    "</div>" +
                                                                "</div>" +
                                                            "</div>" +
                                                        "</div>" +
                                                    "</div>" +
                                                "</div>" +
                                            "</div>" +
                                        "</div>";
                                    
                            } else if (result[i].fotoEvento == null && result[i].recolhimento == "pago") {
                                divs += "<div class='card card-busca-livre-evento'>" +
                                            "<div class='row no-gutters'>" +
                                               "<div class='col-md-5'>" +
                                                    "<img src='{{url('/')}}/img/colorscheme.png' alt=''>" +
                                                "</div>" +
                                                "<div class='col-md-7'>" +
                                                    "<div class='card-body'>" +
                                                        "<div class='container'>" +
                                                            "<div class='row'>" +
                                                                "<div class='col-sm-12'>" +
                                                                    "<div class='row'>" +
                                                                        "<div class='titulo-detalhes'>" +
                                                                            "<a href='{{url('/')}}/evento/visualizar/"+obj.id_evento+"' style='color: black;'><h3>"+obj.nome+"</h3></a>" +
                                                                        "</div>" +
                                                                    "</div>" +
                                                                    "<div class='row' style='color: white;'>" +
                                                                        "<a class='btn cor-aleatoria' style='pointer-events: none; margin: 10px; margin-left: 0;'>#"+obj.tipo+"</a>"+
                                                                        "<a class='btn pago' style='pointer-events: none; margin: 10px; margin-left: 0;'>Pago</a>"+
                                                                    "</div>" +
                                                                    "<div class='row'>" +
                                                                        "<span class='calendario-img'>" +
                                                                            "<img src='{{ asset('/img/icons/calendar.png') }}' alt=''> "+data.toLocaleDateString()+
                                                                        "</span>" +
                                                                        "<span class='relogio-img'>" +
                                                                            "<img src='{{ asset('/img/icons/clock.png') }}' alt=''> Colocar a hora aqui " +
                                                                        "</span>" +
                                                                   "</div>" +
                                                                    "<div class='row ponto-de-localizacao-img'>" +
                                                                        "<img src='{{ asset('/img/icons/location_pointer.png') }}' alt=''>"+obj.rua+", "+obj.numero+", "+obj.bairro+" - "+obj.cidade+"/"+obj.uf+"." +
                                                                    "</div>" +
                                                                "</div>" +
                                                            "</div>" +
                                                        "</div>" +
                                                    "</div>" +
                                                "</div>" +
                                            "</div>" +
                                        "</div>";
                            } else if (result[i].fotoEvento != null) {
                                divs += "<div class='card card-busca-livre-evento'>" +
                                            "<div class='row no-gutters'>" +
                                               "<div class='col-md-5'>" +
                                                    "<img src='{{url('/')}}/storage/eventos/"+obj.id_evento+"/logo.png' alt=''>"+
                                                "</div>" +
                                                "<div class='col-md-7'>" +
                                                    "<div class='card-body'>" +
                                                        "<div class='container'>" +
                                                            "<div class='row'>" +
                                                                "<div class='col-sm-12'>" +
                                                                    "<div class='row'>" +
                                                                        "<div class='titulo-detalhes'>" +
                                                                            "<a href='{{url('/')}}/evento/visualizar/"+obj.id_evento+"' style='color: black;'><h3>"+obj.nome+"</h3></a>" +
                                                                        "</div>" +
                                                                    "</div>" +
                                                                    "<div class='row' style='color: white;'>" +
                                                                        "<a class='btn cor-aleatoria' style='pointer-events: none; margin: 10px; margin-left: 0;'>#"+obj.tipo+"</a>"+
                                                                        "<a class='btn gratuito' style='pointer-events: none; margin: 10px; margin-left: 0;'>Gratuito</a>"+
                                                                    "</div>" +
                                                                    "<div class='row'>" +
                                                                        "<span class='calendario-img'>" +
                                                                            "<img src='{{ asset('/img/icons/calendar.png') }}' alt=''> "+data.toLocaleDateString()+
                                                                        "</span>" +
                                                                        "<span class='relogio-img'>" +
                                                                            "<img src='{{ asset('/img/icons/clock.png') }}' alt=''> Colocar a hora aqui " +
                                                                        "</span>" +
                                                                   "</div>" +
                                                                    "<div class='row ponto-de-localizacao-img'>" +
                                                                        "<img src='{{ asset('/img/icons/location_pointer.png') }}' alt=''>"+obj.rua+", "+obj.numero+", "+obj.bairro+" - "+obj.cidade+"/"+obj.uf+"." +
                                                                    "</div>" +
                                                                "</div>" +
                                                            "</div>" +
                                                        "</div>" +
                                                    "</div>" +
                                                "</div>" +
                                            "</div>" +
                                        "</div>";
                            } else if (result[i].fotoEvento == null) {
                                divs += "<div class='card card-busca-livre-evento'>" +
                                            "<div class='row no-gutters'>" +
                                               "<div class='col-md-5'>" +
                                                    "<img src='{{url('/')}}/img/colorscheme.png' alt=''>" +
                                                "</div>" +
                                                "<div class='col-md-7'>" +
                                                    "<div class='card-body'>" +
                                                        "<div class='container'>" +
                                                            "<div class='row'>" +
                                                                "<div class='col-sm-12'>" +
                                                                    "<div class='row'>" +
                                                                        "<div class='titulo-detalhes'>" +
                                                                            "<a href='{{url('/')}}/evento/visualizar/"+obj.id_evento+"' style='color: black;'><h3>"+obj.nome+"</h3></a>" +
                                                                        "</div>" +
                                                                    "</div>" +
                                                                    "<div class='row' style='color: white;'>" +
                                                                        "<a class='btn cor-aleatoria' style='pointer-events: none; margin: 10px; margin-left: 0;'>#"+obj.tipo+"</a>"+
                                                                        "<a class='btn gratuito' style='pointer-events: none; margin: 10px; margin-left: 0;'>Gratuito</a>"+
                                                                    "</div>" +
                                                                    "<div class='row'>" +
                                                                        "<span class='calendario-img'>" +
                                                                            "<img src='{{ asset('/img/icons/calendar.png') }}' alt=''> "+data.toLocaleDateString()+
                                                                        "</span>" +
                                                                        "<span class='relogio-img'>" +
                                                                            "<img src='{{ asset('/img/icons/clock.png') }}' alt=''> Colocar a hora aqui " +
                                                                        "</span>" +
                                                                   "</div>" +
                                                                    "<div class='row ponto-de-localizacao-img'>" +
                                                                        "<img src='{{ asset('/img/icons/location_pointer.png') }}' alt=''>"+obj.rua+", "+obj.numero+", "+obj.bairro+" - "+obj.cidade+"/"+obj.uf+"." +
                                                                    "</div>" +
                                                                "</div>" +
                                                            "</div>" +
                                                        "</div>" +
                                                    "</div>" +
                                                "</div>" +
                                            "</div>" +
                                        "</div>";
                            }                 
                        })
                        $('#resultados').html(divs);
                        colorir();
                    }
                }
            })
        }

        function colorir(){
            var botoes = document.getElementsByClassName('cor-aleatoria');
            for (var i = 0; i < botoes.length; i++) {
                botoes[i].style.backgroundColor = '#'+Math.floor(Math.random()*16777215).toString(16);
            }
        }
    </script>
@endsection