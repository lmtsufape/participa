@extends('layouts.app')
@section('sidebar')

@endsection
@section('content')

<div class="main_content position-relative">
    {{-- mensagem de confimação --}}
    @if(session('mensagem'))
    <div class="col-md-12" style="margin-top: 5px;">
        <div class="alert alert-success">
            <p>{{session('mensagem')}}</p>
        </div>
    </div>
    @endif
    {{-- {{ $evento->id ?? '' }} --}}
    <div>
        @error('comparacaocaracteres')
        @include('componentes.mensagens')
        @enderror
    </div>
    <div>
        @error('comparacaopalavras')
        @include('componentes.mensagens')
        @enderror
    </div>
    <div>
        @error('marcarextensao')
        @include('componentes.mensagens')
        @enderror
    </div>
    <div>
        @error('caracteresoupalavras')
        @include('componentes.mensagens')
        @enderror
    </div>
    <div>
        @error('semcaractere')
        @include('componentes.mensagens')
        @enderror
    </div>
    <div>
        @error('sempalavra')
        @include('componentes.mensagens')
        @enderror
    </div>

    <div style="position: relative; top: 15px;">
        @yield('menu')
    </div>

    @hasSection ('script')
    @yield('script')
    @endif

</div>
<input type="hidden" name="trabalhoIdAjax" value="1" id="trabalhoIdAjax">
<input type="hidden" name="modalidadeIdAjax" value="1" id="modalidadeIdAjax">
<input type="hidden" name="criterioIdAjax" value="1" id="criterioIdAjax">

@endsection
@section('javascript')
<script type="text/javascript">
    // Adicionar novo criterio
    var contadorOpcoes = 0;
    $(document).ready(function($) {
        $('#addCriterio').click(function() {
            if ($('#modalidade').val() != null) {
                linha = montarLinhaInput();
                $('#criterios').append(linha);
                contadorOpcoes++;
            } else {
                alert("Escolha uma modalidade");
            }
        });

        $('#cep').mask('00000-000');
        $('#campoExemploCpf').mask('000.000.000-00');

        var SPMaskBehavior = function(val) {
                return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
            },
            spOptions = {
                onKeyPress: function(val, e, field, options) {
                    field.mask(SPMaskBehavior.apply({}, arguments), options);
                }
            };

        $('#campoExemploNumero').mask(SPMaskBehavior, spOptions);

        @if(old('criarCupom') != null || old('editarCupom') != null)
        $('#li_cuponsDeDesconto').click();
        @elseif(old('criarCategoria') != null || old('editarCategoria') != null)
        $('#li_categoria_participante').click();
        @elseif(old('criarCampo') != null)
        $('#li_formulario_inscricao').click();
        @elseif(old('campo_id') != null)
        $('#li_formulario_inscricao').click();
        @elseif(old('novaPromocao') != null || old('editarPromocao') != null)
        $('#li_promocoes').click();
        @else
        $('#li_categoria_participante').click();
        @endif

    });

    function exibirLimite(id, input) {
        alert(input.value == "caracteres");
        var caracteres = document.getElementById('caracteres' + id);
        var palavras = document.getElementById('palavras' + id);
        alert(caracteres.style.display);
        if (input.value == "caracteres") {
            caracteres.style.display = "";
            palavras.style.display = "none";
        } else {
            caracteres.style.display = "none";
            palavras.style.display = "";
        }
    }

    function exibirTiposArquivo(id, input) {
        var tiposDeArquivo = document.getElementsByClassName('tiposDeArquivos' + id);
        // console.log(tiposDeArquivo);
        if (input.checked) {
            tiposDeArquivo[0].style.display = "block";
            tiposDeArquivo[1].style.display = "block";
        } else {
            tiposDeArquivo[0].style.display = "none";
            tiposDeArquivo[1].style.display = "none";
        }
    }

    $(document).ready(function($) {
        $('.cep').mask('00000-000');
        $(".apenasLetras").mask("#", {
            maxlength: false,
            translation: {
                '#': {
                    pattern: /[A-zÀ-ÿ ]/,
                    recursive: true
                }
            }
        });
        //$('.numero').mask('0000000');
    });

    // Remover Criterio
    $(document).on('click', '.delete', function() {
        $(this).closest('.row').remove();
        return false;
    });

    // Montar div para novo criterio
    function montarLinhaInput() {
        return "<div class=" + "row" + " style='position:relative; top:10px;'>" +
            "<div class=" + "col-sm-6" + ">" +
            "<label>Nome</label>" +
            "<input" + " type=" + 'text' + " style=" + "margin-bottom:10px" + " class=" + 'form-control' + " name=" + 'nomeCriterio' + contadorOpcoes + " placeholder=" + "Nome" + " required>" +
            "</div>" +
            "<div class=" + "col-sm-5" + ">" +
            "<label>Peso</label>" +
            "<input" + " type=" + 'number' + " style=" + "margin-bottom:10px" + " class=" + 'form-control' + " name=" + 'pesoCriterio' + contadorOpcoes + " placeholder=" + "Peso" + " required>" +
            "</div>" +
            "<div class=" + "col-sm-1" + ">" +
            "<a href=" + "#" + " class=" + "delete" + ">" +
            "<img src=" + "{{asset('img/icons/lixo.png')}}" + " style=" + "width:25px;margin-top:35px" + ">" +
            "</a>" +
            "</div>" +
            "<div class='container'>" +
            "<div class='row'>" +
            "<div class='col-sm-12'>" +
            "<h6>Opções para avaliar<img src='{{asset('/img/icons/interrogacao.png')}}' width='15px' style='position:relative; left:5px; border: solid 1px; border-radius:50px; padding: 2px;' title='Essas opções serão exibidas ao avaliador na hora da avaliação do trabalho'></h6>" +
            "</div>" +
            "<div class='col-sm-7'>" +
            "<input" + " type=" + 'text' + " style=" + "margin-bottom:10px" + " class=" + 'form-control' + " name=" + 'opcaoCriterio_' + contadorOpcoes + '[]' + " placeholder=" + "Opção" + " required>" +
            "</div>" +
            "<div class='col-sm-4'>" +
            "<input" + " type=" + 'number' + " style=" + "margin-bottom:10px" + " class=" + 'form-control' + " name=" + 'valor_real_opcao_' + contadorOpcoes + '[]' + " placeholder=" + "Valor entre 0 a 10" + " required min='0.00' onchange='validandoValorReal(this)'>" +
            "</div>" +
            "<div class='col-sm-1'>" +
            "<a href=" + "#" + " onclick=" + "addOpcaoCriterio(this," + contadorOpcoes + ")" + ">" +
            "<img src=" + "{{ asset('img/icons/plus-square-solid_black.svg')}}" + " style=" + "width:25px;margin-top:5px" + ">" +
            "</a>" +
            "</div>" +
            "</div>" +
            "<hr>" +
            "</div>" +
            "</div>";
    }

    function validandoValorReal(input) {
        // console.log(input);
        if (input.value > 10 || input.value < 0) {
            alert("O valor da opção deve estar entre 0 e 10");
            input.value = 0;
        }
    }

    function montarLinhaOpcaoCriterio(idName) {
        return "<div class='col-sm-7'>" +
            "<input" + " type=" + 'text' + " style=" + "margin-bottom:10px" + " class=" + 'form-control' + " name=" + 'opcaoCriterio_' + idName + '[]' + " placeholder=" + "Opção" + " required>" +
            "</div>" +
            "<div class='col-sm-4'>" +
            "<input" + " type=" + 'number' + " style=" + "margin-bottom:10px" + " class=" + 'form-control' + " name=" + 'valor_real_opcao_' + idName + '[]' + " placeholder=" + "Valor entre 0 a 10" + " required min='0.0' onchange='validandoValorReal(this)'>" +
            "</div>";
    }

    function addOpcaoCriterio(elem, idName) {
        linhaDeOpcaoCriterio = montarLinhaOpcaoCriterio(idName);
        $(elem).closest('.row').append(linhaDeOpcaoCriterio);
    }

    // Função para retornar campos de edição de etiquetas para submissão de trabalhos ao default.
    function default_formsubmtraba() {
        return confirm('Tem certeza que deseja voltar a ordem e valores padrões dos campos?');
    }

    // Função para retornar campos de edição card de evento ao default.
    function default_edicaoCardEvento() {
        return confirm('Tem certeza que deseja restaurar os valores padrões dos campos?');
    }



    // Ordenar campos de submissão de trabalhos
    $(document).ready(function() {
        $('.move-down').click(function() {
            if (($(this).next()) && ($(this).parents("#bisavo").next().attr('id') == "bisavo")) {
                console.log("IF MOVE-DOWN");
                var t = $(this);
                t.parents("#bisavo").animate({
                    top: '20px'
                }, 500, function() {
                    t.parents("#bisavo").next().animate({
                        top: '-20px'
                    }, 500, function() {
                        t.parents("#bisavo").css('top', '0px');
                        t.parents("#bisavo").next().css('top', '0px');
                        t.parents("#bisavo").insertAfter(t.parents("#bisavo").next());
                    });
                });
                // $(this).parents("#bisavo").insertAfter($(this).parents("#bisavo").next());
            } else {
                console.log("ELSE MOVE-DOWN");
            }
        });
        $('.move-up').click(function() {
            if (($(this).prev()) && ($(this).parents("#bisavo").prev().attr('id') == "bisavo")) {
                console.log("IF MOVE-UP");
                var t = $(this);
                t.parents("#bisavo").animate({
                    top: '-20px'
                }, 500, function() {
                    t.parents("#bisavo").prev().animate({
                        top: '20px'
                    }, 500, function() {
                        t.parents("#bisavo").css('top', '0px');
                        t.parents("#bisavo").prev().css('top', '0px');
                        t.parents("#bisavo").insertBefore(t.parents("#bisavo").prev());
                    });
                });
                // $(this).parents("#bisavo").insertBefore($(this).parents("#bisavo").prev());
            } else {
                console.log("ELSE MOVE-UP");
            }
        });
    });

    // Exibir ou ocultar opções de Texto na criação de modalidade - com checkbox
    $(document).ready(function() {
        $('input:checkbox[class="form-check-input incluirarquivo"]').on("change", function() {
            if (this.checked) {
                $("#area-template").show();
                $("#tipo-arquivo").show();
            } else {
                $("#area-template").hide();
                $("#tipo-arquivo").hide();
            }
        });
        $('.incluir-resumo').on("change", function() {
            if (this.checked) {
                $("#restricoes-resumo-texto").show();
            } else {
                $("#restricoes-resumo-texto").hide();
            }
        });
        $('.incluir-resumo-edit').on("change", function() {
            if (this.checked) {
                this.parentElement.parentElement.children[1].style.display = "block";
            } else {
                this.parentElement.parentElement.children[1].style.display = "none";
            }
        })
        $('.apresentacao').on("change", function() {
            if (this.checked) {
                this.parentElement.parentElement.parentElement.children[1].style.display = "block";
            } else {
                this.parentElement.parentElement.parentElement.children[1].style.display = "none";
            }
        });
    });

    $(document).ready(function() {
        $('input:checkbox[class="form-check-input incluirarquivoEdit"]').on("change", function() {
            if (this.checked) {
                $("#area-templateEdit").show();
                $("#tipo-arquivoEdit").show();
            } else {
                $("#area-templateEdit").hide();
                $("#tipo-arquivoEdit").hide();
            }
        });
    });


    $(document).ready(function() {
        $('input:radio[name="limit"]').on("change", function() {
            if (this.checked && this.value == 'limit-option1') {
                $("#min-max-caracteres").show();
                $("#min-max-palavras").hide();
            } else {
                $("#min-max-palavras").show();
                $("#min-max-caracteres").hide();
            }
        });
    });

    $(document).ready(function() {
        $('.limit').on("change", function() {
            if (this.checked && this.value == 'limit-option1') {
                this.parentElement.parentElement.children[3].children[0].style.display = "block";
                this.parentElement.parentElement.children[4].children[0].style.display = "none";
            } else {
                this.parentElement.parentElement.children[3].children[0].style.display = "none";
                this.parentElement.parentElement.children[4].children[0].style.display = "block";
            }
        });
    });

    // Exibir ou ocultar campos de edição de etiquetas de eventos
    $(document).ready(function() {
        $('#botao-editar-nome').on("click", function() {
            $("#etiqueta-nome-evento").toggle(500);
        });
    });

    $(document).ready(function() {
        $('#botao-editar-descricao').on("click", function() {
            $("#etiqueta-descricao-evento").toggle(500);
        });
    });

    $(document).ready(function() {
        $('#botao-editar-tipo').on("click", function() {
            $("#etiqueta-tipo-evento").toggle(500);
        });
    });

    $(document).ready(function() {
        $('#botao-editar-datas').on("click", function() {
            $("#etiqueta-data-evento").toggle(500);
        });
    });

    $(document).ready(function() {
        $('#botao-editar-submissoes').on("click", function() {
            $("#etiqueta-submissoes-evento").toggle(500);
        });
    });

    $(document).ready(function() {
        $('#botao-editar-endereco').on("click", function() {
            $("#etiqueta-endereco-evento").toggle(500);
        });
    });

    $(document).ready(function() {
        $('#botao-editar-modulo-inscricao').on("click", function() {
            $("#etiqueta-modulo-inscricao-evento").toggle(500);
        });
    });

    $(document).ready(function() {
        $('#botao-editar-modulo-programacao').on("click", function() {
            $("#etiqueta-modulo-programacao-evento").toggle(500);
        });
    });

    $(document).ready(function() {
        $('#botao-editar-arquivo').on("click", function() {
            $("#etiqueta-arquivo-evento").toggle(500);
        });
    });

    $(document).ready(function() {
        $('#botao-editar-modulo-organizacao').on("click", function() {
            $("#etiqueta-modulo-organizacao-evento").toggle(500);
        });
    });

    $(document).ready(function() {
        $('#botao-editar-etiqueta-regra').on("click", function() {
            $("#etiqueta-baixar-regra-evento").toggle(500);
        });
    });

    $(document).ready(function() {
        $('#botao-editar-etiqueta-apresentacao').on("click", function() {
            $("#etiqueta-baixar-apresentacao-evento").toggle(500);
        });
    });

    $(document).ready(function() {
        $('#botao-editar-etiqueta-template').on("click", function() {
            $("#etiqueta-baixar-template-evento").toggle(500);
        });
    });
    // Fim

    // Exibir ou ocultar campos de edição de etiquetas de trabalhos
    $(document).ready(function() {
        $('#botao-editar-titulo').on("click", function() {
            $("#etiqueta-titulo-trabalho").toggle(500);
        });
    });

    $(document).ready(function() {
        $('#botao-editar-autor').on("click", function() {
            $("#etiqueta-autor-trabalho").toggle(500);
        });
    });

    $(document).ready(function() {
        $('#botao-editar-coautor').on("click", function() {
            $("#etiqueta-coautor-trabalho").toggle(500);
        });
    });

    $(document).ready(function() {
        $('#botao-editar-resumo').on("click", function() {
            $("#etiqueta-resumo-trabalho").toggle(500);
        });
    });

    $(document).ready(function() {
        $('#botao-editar-area').on("click", function() {
            $("#etiqueta-area-trabalho").toggle(500);
        });
    });

    $(document).ready(function() {
        $('#botao-editar-upload').on("click", function() {
            $("#etiqueta-upload-trabalho").toggle(500);
        });
    });

    $(document).ready(function() {
        $('#botao-editar-regras').on("click", function() {
            $("#etiqueta-regras-trabalho").toggle(500);
        });
    });

    $(document).ready(function() {
        $('#botao-editar-templates').on("click", function() {
            $("#etiqueta-templates-trabalho").toggle(500);
        });
    });

    $(document).ready(function() {
        $('#dropdownSubmeterTrabalho').hide();
        if (!$(location).attr('pathname').includes('coord/evento/trabalhos/listarTrabalhos'))
            $('#dropdownListarTrabalhos').hide();
        if (!$(location).attr('pathname').includes('coord/evento/palestrantes'))
            $('#dropdownPalestrantes').hide();
        if (!$(location).attr('pathname').includes('coord/evento/trabalhos/form/listarRepostasTrabalhos') && !$(location).attr('pathname').includes('coord/evento/trabalhos/listarAvaliacoes'))
            $('#dropdownListarAvaliacoes').hide();
    });
    // Fim

    function habilitarEvento() {
        var form = document.getElementById("habilitarEventoForm");
        form.submit();
    }

    function desabilitarEvento() {
        var form = document.getElementById("desabilitarEventoForm");
        form.submit();
    }

    function trabalhoId(x) {
        document.getElementById('trabalhoIdAjax').value = x;
    }

    function modalidadeId(x) {
        document.getElementById('modalidadeIdAjax').value = x;
    }

    function criterioId(x) {
        document.getElementById('criterioIdAjax').value = x;
    }

    $(function() {
        $('#areas').click(function() {
            $('#dropdownAreas').slideToggle(200);
        });
        $('#outrasComissoes').click(function() {
            $('#dropdownOutrasComissoes').slideToggle(200);
        });
        $('#revisores').click(function() {
            $('#dropdownRevisores').slideToggle(200);
        });
        $('#comissao').click(function() {
            $('#dropdownComissao').slideToggle(200);
        });
        $('#comissaoOrganizadora').click(function() {
            $('#dropdownComissaoOrganizadora').slideToggle(200);
        });
        $('#palestrantes').click(function() {
            $('#dropdownPalestrantes').slideToggle(200);
        });
        $('#modalidades').click(function() {
            $('#dropdownAvaliacoesModalidades').hide();
            $('#dropdownModalidades').slideToggle(200);
        });
        $('#certificados').click(function() {
            $('#dropdownCertificados').slideToggle(200);
        });
        $('#avaliacoesModalidades').click(function() {
            $('#dropdownAvaliacoesModalidades').slideToggle(100);
        });
        $('#eventos').click(function() {
            $('#dropdownEvento').slideToggle(200);
        });
        $('#programacao').click(function() {
            $('#dropdownProgramacao').slideToggle(200);
        });
        $('#memorias').click(function() {
            $('#dropdownMemoria').slideToggle(200);
        });
        $('#inscricoes').click(function() {
            $('#dropdownInscricoes').slideToggle(200);
        });
        $('#trabalhos').click(function() {
            $('#dropdownTrabalhosModalidades').hide();
            $('#dropdownSubmeterTrabalho').hide();
            $('#dropdownListarTrabalhos').hide();
            $('#dropdownListarAvaliacoes').hide();
            $('#dropdownPalestrantes').hide();
            $('#dropdownTrabalhos').slideToggle(200);
        });
        $('#trabalhosModalidades').click(function() {
            $('#dropdownTrabalhosModalidades').slideToggle(100);
        });
        $('#submeterTrabalho').click(function() {
            $('#dropdownSubmeterTrabalho').slideToggle(100);
        });
        $('#listarTrabalhos').click(function() {
            $('#dropdownListarTrabalhos').slideToggle(100);
        });
        $('#listarAvaliacoes').click(function() {
            $('#dropdownListarAvaliacoes').slideToggle(100);
        });
        $('#dropdownPalestrantes').click(function() {
            $('#dropdownListarAvaliacoes').slideToggle(100);
        });
        $('#publicar').click(function() {
            $('#dropdownPublicar').slideToggle(200);
        });
        $('.botaoAjax').click(function(e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    // 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            jQuery.ajax({
                url: "{{ route('detalhesTrabalho') }}",
                method: 'get',
                data: {
                    // name: jQuery('#name').val(),
                    // type: jQuery('#type').val(),
                    // price: jQuery('#price').val()
                    trabalhoId: $('#trabalhoIdAjax').val()
                },
                success: function(result) {
                    console.log(result);
                    // result = JSON.parse(result[0]);
                    // console.log(result.titulo);
                    $('#tituloTrabalhoAjax').html(result.titulo);
                    $('#resumoTrabalhoAjax').html(result.resumo);
                    $('#distribuicaoManualTrabalhoId').val($('#trabalhoIdAjax').val());
                    $('#removerRevisorTrabalhoId').val($('#trabalhoIdAjax').val());
                    // console.log(result.revisores);
                    var container = $('#cblist');
                    container.empty();
                    result.revisores.forEach(addCheckbox);
                    var container = $('#selectRevisorTrabalho');
                    container.empty();
                    addDisabledOptionToSelect();
                    result.revisoresDisponiveis.forEach(addOptionToSelect);

                }
            });
            jQuery.ajax({
                url: "{{ route('findModalidade') }}",
                method: 'get',
                data: {
                    modalidadeId: $('#modalidadeIdAjax').val()
                },
                success: function(result) {
                    console.log(result);
                    // document.getElementById('nomeModalidadeEdit').value = result.nome;
                    $('#modalidadeEditId').val(result.id);
                    $('#nomeModalidadeEdit').val(result.nome);
                    $('#inicioSubmissaoEdit').val(result.inicioSubmissao);
                    $('#fimSubmissaoEdit').val(result.fimSubmissao);
                    $('#inicioRevisaoEdit').val(result.inicioRevisao);
                    $('#fimRevisaoEdit').val(result.fimRevisao);
                    $('#inicioResultadoEdit').val(result.inicioResultado);


                    if (result.caracteres == true) {
                        $('#id-limit-custom_field-accountEdit-1-1').prop('checked', true);
                        $("#min-max-caracteresEdit").show();
                        $("#min-max-palavrasEdit").hide();
                    }
                    if (result.palavras == true) {
                        $('#id-limit-custom_field-accountEdit-1-2').prop('checked', true);
                        $("#min-max-caracteresEdit").hide();
                        $("#min-max-palavrasEdit").show();
                    }
                    $('#maxcaracteresEdit').val(result.maxcaracteres);
                    $('#mincaracteresEdit').val(result.mincaracteres);
                    $('#maxpalavrasEdit').val(result.maxpalavras);
                    $('#minpalavrasEdit').val(result.minpalavras);


                    if (result.arquivo == true) {

                        $('#id-custom_field-accountEdit-1-2').prop('checked', true);
                        $("#area-templateEdit").show();
                        $("#tipo-arquivoEdit").show();
                    }

                    if (result.pdf == true) {

                        $('#pdfEdit').prop('checked', true);
                    }
                    if (result.jpg == true) {

                        $('#jpgEdit').prop('checked', true);
                    }
                    if (result.jpeg == true) {

                        $('#jpegEdit').prop('checked', true);
                    }
                    if (result.png == true) {

                        $('#pngEdit').prop('checked', true);
                    }
                    if (result.docx == true) {

                        $('#docxEdit').prop('checked', true);
                    }
                    if (result.odt == true) {

                        $('#odtEdit').prop('checked', true);
                    }
                }
            });

            jQuery.ajax({
                url: "{{ route('encontrar.criterio') }}",
                method: 'get',
                data: {
                    criterioId: $('#criterioIdAjax').val()
                },
                success: function(result) {
                    console.log(result);
                    $('#nomeCriterioUpdate').val(result.nome);
                    $('#pesoCriterioUpdate').val(result.peso);
                    $('#modalidadeIdCriterioUpdate').val(result.id);
                }
            });
        });

        $('#areaIdformDistribuicaoPorArea').change(function() {
            $.ajaxSetup({
                headers: {
                    // 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            jQuery.ajax({
                url: "{{ route('numeroDeRevisoresAjax') }}",
                method: 'get',
                data: {
                    // name: jQuery('#name').val(),
                    // type: jQuery('#type').val(),
                    // price: jQuery('#price').val()
                    areaId: $('#areaIdformDistribuicaoPorArea').val()
                },
                success: function(result) {
                    if (result == 0) {
                        $('#numeroDeRevisoresPorTrabalhoButton').prop('disabled', true);
                        alert("Não existem revisores nessa área.");
                    } else {
                        if ($('#numeroDeRevisoresPorTrabalhoInput').val() < 1) {
                            $('#numeroDeRevisoresPorTrabalhoButton').prop('disabled', true);
                        } else {
                            $('#numeroDeRevisoresPorTrabalhoButton').prop('disabled', false);
                        }
                    }
                    // $('#tituloTrabalhoAjax').html(result.titulo);
                    // $('#resumoTrabalhoAjax').html(result.resumo);
                    // $("h1, h2, p").toggleClass("blue");
                }
            });
        });
        $('#numeroDeRevisoresPorTrabalhoInput').on("input", function() {
            if ($('#numeroDeRevisoresPorTrabalhoInput').val() < 1) {
                $('#numeroDeRevisoresPorTrabalhoButton').prop('disabled', true);
            } else {
                $('#numeroDeRevisoresPorTrabalhoButton').prop('disabled', false);
            }
        });
    });

    // function cadastrarCriterio() {
    //     var form = document.getElementById('formCadastrarCriterio');
    //     var modalidade = document.getElementById('modalidade');

    //     if (modalidade.value != "") {
    //         form.submit();
    //     }
    // }

    function myFunction(item, index) {
        // document.getElementById("demo").innerHTML += index + ":" + item + "<br>";
        console.log(index);
        console.log(item.id);
    }

    function addCheckbox(item) {
        var container = $('#cblist');
        var inputs = container.find('input');
        var id = inputs.length + 1;

        var linha = "<div class=" + "row" + ">" +
            "<div class=" + "col-sm-12" + ">" +
            "<input type=" + "checkbox" + " id=" + "cb" + id + " name=" + "revisores[]" + " value=" + item.id + ">" +
            "<label for=" + "cb" + id + " style=" + "margin-left:10px" + ">" + item.nomeOuEmail + "</label>" +
            "</div>" +
            "</div>";
        $('#cblist').append(linha);
    }

    function addOptionToSelect(item) {
        var container = $('#selectRevisorTrabalho');
        var inputs = container.find('option');
        var id = inputs.length + 1;

        var linha = "<option value=" + item.id + ">" + item.nomeOuEmail + "</option>";
        $('#selectRevisorTrabalho').append(linha);
    }

    function addDisabledOptionToSelect() {
        var container = $('#selectRevisorTrabalho');
        var inputs = container.find('option');

        var linha = "<option value='' disabled selected hidden> Novo Revisor </option>";
        $('#selectRevisorTrabalho').append(linha);
    }

    function cadastrarCoodComissao() {

        document.getElementById("formCoordComissao").submit();
    }

    var newOptions = {
        "Option 1": "value1",
        "Option 2": "value2",
        "Option 3": "value3"
    };
    var $el = $("#testeId");

    $("#testeId").change(function() {
        alert("The text has been changed.");
    });


    // Marcar a visibilidade da atividade para participantes
    // Estudar como fazer
    function setVisibilidadeAtv(id) {
        var checkbox = document.getElementById('checkbox_' + id);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            }
        });
        jQuery.ajax({
            url: "/coord/evento/atividades/" + id + "/visibilidade",
            method: 'post',
        });
    }

    function salvarTipoAtividadeAjax(id) {
        if (id == 0) {
            $.ajax({
                url: "{{route('coord.tipo.store.ajax')}}",
                method: 'get',
                type: 'get',
                data: {
                    _token: '{{csrf_token()}}',
                    name: $('#nomeTipo').val(),
                    evento_id: "{{$evento->id}}",
                },
                statusCode: {
                    404: function() {
                        alert("O nome é obrigatório");
                    }
                },
                success: function(data) {
                    // var data = JSON.parse(result);
                    if (data != null) {
                        if (data.length > 0) {
                            if ($('#tipo').val() == null || $('#tipo').val() == "") {
                                var option = '<option selected disabled>-- Tipo --</option>';
                            }
                            $.each(data, function(i, obj) {
                                if ($('#tipo').val() != null && $('#tipo').val() == obj.id && i > 0) {
                                    option += '<option value="' + obj.id + '">' + obj.descricao + '</option>';
                                } else if (i == 0) {
                                    option = '<option selected disabled>-- Tipo --</option>';
                                } else {
                                    option += '<option value="' + obj.id + '">' + obj.descricao + '</option>';
                                }
                            })
                        } else {
                            var option = "<option selected disabled>-- Tipo --</option>";
                        }
                        $('#tipo').html(option).show();
                        if (data.length > 0) {
                            for (var i = 0; i < data.length; i++) {
                                // console.log('---------------------------------'+i+'------------------------');
                                // console.log(data[i].descricao);
                                // console.log(document.getElementById('nomeTipo').value);
                                // console.log(data[i].descricao === document.getElementById('nomeTipo').value);
                                if (data[i].descricao === document.getElementById('nomeTipo').value) {
                                    document.getElementById('tipo').selectedIndex = i;
                                }
                            }
                        }
                        document.getElementById('nomeTipo').value = "";
                        $('#buttomFormNovoTipoAtividade').click();
                    }
                }
            });
        } else {
            $.ajax({
                url: "{{route('coord.tipo.store.ajax')}}",
                method: 'get',
                type: 'get',
                data: {
                    _token: '{{csrf_token()}}',
                    name: $('#nomeTipo' + id).val(),
                    evento_id: "{{$evento->id}}",
                },
                statusCode: {
                    404: function() {
                        alert("O nome é obrigatório");
                    }
                },
                success: function(data) {
                    if (data != null) {
                        if (data.length > 0) {
                            if ($('#tipo' + id).val() == null || $('#tipo' + id).val() == "") {
                                var option = '<option selected disabled>-- Tipo --</option>';
                            }
                            $.each(data, function(i, obj) {
                                if ($('#tipo' + id).val() != null && $('#tipo' + id).val() == obj.id && i > 0) {
                                    option += '<option value="' + obj.id + '">' + obj.descricao + '</option>';
                                } else if (i == 0) {
                                    option = '<option selected disabled>-- Tipo --</option>';
                                } else {
                                    option += '<option value="' + obj.id + '">' + obj.descricao + '</option>';
                                }
                            })
                        } else {
                            var option = "<option selected disabled>-- Tipo --</option>";
                        }
                        $('#tipo' + id).html(option).show();
                        if (data.length > 0) {
                            for (var i = 0; i < data.length; i++) {
                                // console.log('---------------------------------'+i+'------------------------');
                                // console.log(data[i].descricao);
                                // console.log(document.getElementById('nomeTipo').value);
                                // console.log(data[i].descricao === document.getElementById('nomeTipo').value);
                                if (data[i].descricao === document.getElementById('nomeTipo' + id).value) {
                                    document.getElementById('tipo' + id).selectedIndex = i;
                                }
                            }
                        }
                        document.getElementById('nomeTipo' + id).value = "";
                        $('#buttomFormNovoTipoAtividade' + id).click();
                    }
                }
            });
        }
    }

    //Funções do form de atividades da programação
    function exibirDias(id) {
        if (id != 0) {
            document.getElementById('divDuracaoAtividade' + id).style.display = "block";
            var select = document.getElementById('duracaoAtividade' + id);
            switch (select.value) {
                case '1':
                    document.getElementById('dia1' + id).style.display = "block";
                    document.getElementById('dia2' + id).style.display = "none";
                    document.getElementById('dia3' + id).style.display = "none";
                    document.getElementById('dia4' + id).style.display = "none";
                    document.getElementById('dia5' + id).style.display = "none";
                    document.getElementById('dia6' + id).style.display = "none";
                    document.getElementById('dia7' + id).style.display = "none";
                    break;
                case '2':
                    document.getElementById('dia1' + id).style.display = "block";
                    document.getElementById('dia2' + id).style.display = "block";
                    document.getElementById('dia3' + id).style.display = "none";
                    document.getElementById('dia4' + id).style.display = "none";
                    document.getElementById('dia5' + id).style.display = "none";
                    document.getElementById('dia6' + id).style.display = "none";
                    document.getElementById('dia7' + id).style.display = "none";
                    break;
                case '3':
                    document.getElementById('dia1' + id).style.display = "block";
                    document.getElementById('dia2' + id).style.display = "block";
                    document.getElementById('dia3' + id).style.display = "block";
                    document.getElementById('dia4' + id).style.display = "none";
                    document.getElementById('dia5' + id).style.display = "none";
                    document.getElementById('dia6' + id).style.display = "none";
                    document.getElementById('dia7' + id).style.display = "none";
                    break;
                case '4':
                    document.getElementById('dia1' + id).style.display = "block";
                    document.getElementById('dia2' + id).style.display = "block";
                    document.getElementById('dia3' + id).style.display = "block";
                    document.getElementById('dia4' + id).style.display = "block";
                    document.getElementById('dia5' + id).style.display = "none";
                    document.getElementById('dia6' + id).style.display = "none";
                    document.getElementById('dia7' + id).style.display = "none";
                    break;
                case '5':
                    document.getElementById('dia1' + id).style.display = "block";
                    document.getElementById('dia2' + id).style.display = "block";
                    document.getElementById('dia3' + id).style.display = "block";
                    document.getElementById('dia4' + id).style.display = "block";
                    document.getElementById('dia5' + id).style.display = "block";
                    document.getElementById('dia6' + id).style.display = "none";
                    document.getElementById('dia7' + id).style.display = "none";
                    break;
                case '6':
                    document.getElementById('dia1' + id).style.display = "block";
                    document.getElementById('dia2' + id).style.display = "block";
                    document.getElementById('dia3' + id).style.display = "block";
                    document.getElementById('dia4' + id).style.display = "block";
                    document.getElementById('dia5' + id).style.display = "block";
                    document.getElementById('dia6' + id).style.display = "block";
                    document.getElementById('dia7' + id).style.display = "none";
                    break;
                case '7':
                    document.getElementById('dia1' + id).style.display = "block";
                    document.getElementById('dia2' + id).style.display = "block";
                    document.getElementById('dia3' + id).style.display = "block";
                    document.getElementById('dia4' + id).style.display = "block";
                    document.getElementById('dia5' + id).style.display = "block";
                    document.getElementById('dia6' + id).style.display = "block";
                    document.getElementById('dia7' + id).style.display = "block";
                    break;
            }
        } else {
            document.getElementById('divDuracaoAtividade').style.display = "block";
            var select = document.getElementById('duracaoAtividade');
            switch (select.value) {
                case '1':
                    document.getElementById('dia1').style.display = "block";
                    document.getElementById('dia2').style.display = "none";
                    document.getElementById('dia3').style.display = "none";
                    document.getElementById('dia4').style.display = "none";
                    document.getElementById('dia5').style.display = "none";
                    document.getElementById('dia6').style.display = "none";
                    document.getElementById('dia7').style.display = "none";
                    break;
                case '2':
                    document.getElementById('dia1').style.display = "block";
                    document.getElementById('dia2').style.display = "block";
                    document.getElementById('dia3').style.display = "none";
                    document.getElementById('dia4').style.display = "none";
                    document.getElementById('dia5').style.display = "none";
                    document.getElementById('dia6').style.display = "none";
                    document.getElementById('dia7').style.display = "none";
                    break;
                case '3':
                    document.getElementById('dia1').style.display = "block";
                    document.getElementById('dia2').style.display = "block";
                    document.getElementById('dia3').style.display = "block";
                    document.getElementById('dia4').style.display = "none";
                    document.getElementById('dia5').style.display = "none";
                    document.getElementById('dia6').style.display = "none";
                    document.getElementById('dia7').style.display = "none";
                    break;
                case '4':
                    document.getElementById('dia1').style.display = "block";
                    document.getElementById('dia2').style.display = "block";
                    document.getElementById('dia3').style.display = "block";
                    document.getElementById('dia4').style.display = "block";
                    document.getElementById('dia5').style.display = "none";
                    document.getElementById('dia6').style.display = "none";
                    document.getElementById('dia7').style.display = "none";
                    break;
                case '5':
                    document.getElementById('dia1').style.display = "block";
                    document.getElementById('dia2').style.display = "block";
                    document.getElementById('dia3').style.display = "block";
                    document.getElementById('dia4').style.display = "block";
                    document.getElementById('dia5').style.display = "block";
                    document.getElementById('dia6').style.display = "none";
                    document.getElementById('dia7').style.display = "none";
                    break;
                case '6':
                    document.getElementById('dia1').style.display = "block";
                    document.getElementById('dia2').style.display = "block";
                    document.getElementById('dia3').style.display = "block";
                    document.getElementById('dia4').style.display = "block";
                    document.getElementById('dia5').style.display = "block";
                    document.getElementById('dia6').style.display = "block";
                    document.getElementById('dia7').style.display = "none";
                    break;
                case '7':
                    document.getElementById('dia1').style.display = "block";
                    document.getElementById('dia2').style.display = "block";
                    document.getElementById('dia3').style.display = "block";
                    document.getElementById('dia4').style.display = "block";
                    document.getElementById('dia5').style.display = "block";
                    document.getElementById('dia6').style.display = "block";
                    document.getElementById('dia7').style.display = "block";
                    break;
            }
        }

    }

    // Variavel para definios os ids das divs dos cantidatos
    var contadorConvidados = 1;


    $(document).ready(function() {
        //Função que submete o form uma nova atividade
        $('#submitNovaAtividade').click(function() {
            var form = document.getElementById('formNovaAtividade');
            form.submit();
        });

        //Função para controlar a exibição da div para cadastro de um novo tipo de função de convidado
        $('#buttonformNovaFuncaoDeConvidado').click(function() {
            if (document.getElementById('formNovaFuncaoDeConvidado').style.display == "block") {
                document.getElementById('formNovaFuncaoDeConvidado').style.display = "none";
            } else {
                document.getElementById('formNovaFuncaoDeConvidado').style.display = "block";
            }
        });


    });



    $(document).ready(function($) {
        $(".apenasLetras").mask("#", {
            maxlength: false,
            translation: {
                '#': {
                    pattern: /[A-zÀ-ÿ ]/,
                    recursive: true
                }
            }
        });
    });

    //Função para adicionar o conteudo de um novo convidado
    function adicionarConvidado(id) {
        contadorConvidados++;
        if (id == 0) {
            $('#convidadosDeUmaAtividade').append(
                "<div id='novoConvidadoAtividade" + contadorConvidados + "' class='row form-group'>" +
                "<div class='container'>" +
                "<h5>Convidado</h5>" +
                "<div class='row'>" +
                "<div class='col-sm-6'>" +
                "<label for='nome'>Nome:</label>" +
                "<input class='form-control apenasLetras' type='text' name='nomeDoConvidado[]' id='nome'  value='{{ old('nomeConvidado') }}' placeholder='Nome do convidado'>" +
                "</div>" +
                "<div class='col-sm-6'>" +
                "<label for='email'>E-mail:</label>" +
                "<input class='form-control' type='email' name='emailDoConvidado[]' id='email' value='{{ old('emailConvidado') }}' placeholder='E-mail do convidado'>" +
                "</div>" +
                "</div>" +
                "<div class='row'>" +
                "<div class='col-sm-4'>" +
                "<label for='funcao'>Função:</label>" +
                "<select class='form-control' name='funçãoDoConvidado[]' id='funcao' onchange='outraFuncaoConvidado(0, this," + contadorConvidados + ")'>" +
                "<option value='' selected disabled>-- Função --</option>" +
                "<option value='Palestrante'>Palestrante</option>" +
                "<option value='Avaliador'>Avaliador</option>" +
                "<option value='Ouvinte'>Ouvinte</option>" +
                "<option value='Outra'>Outra</option>" +
                "</select>" +
                "</div>" +
                "<div id='divOutraFuncao" + contadorConvidados + "' class='col-sm-4' style='display: none;'>" +
                "<label for='Outra'>Qual?</label>" +
                "<input type='text' class='form-control apenasLetras' name='outra[]' id='outraFuncao'>" +
                "</div>" +
                "<div class='col-sm-4'>" +
                "<button type='button' onclick='removerConvidadoNovaAtividade(" + contadorConvidados + ")' style='border:none; background-color: rgba(0,0,0,0);'><img src='{{ asset('/img/icons/user-times-solid.svg') }}' width='50px' height='auto'  alt='remover convidade' style='padding-top: 28px;'></button>" +
                "</div>" +
                "</div>" +
                "</div>" +
                "</div>"
            )
        } else if (id > 0) {
            $('#convidadosDeUmaAtividade' + id).append(
                "<div id='novoConvidadoAtividade" + contadorConvidados + "' class='row form-group'>" +
                "<div class='container'>" +
                "<h5>Convidado</h5>" +
                "<div class='row'>" +
                "<input type='hidden' name='idConvidado[]' value='0'>" +
                "<div class='col-sm-6'>" +
                "<label for='nome'>Nome:</label>" +
                "<input class='form-control apenasLetras' type='text' name='nomeDoConvidado[]' id='nome'  value='{{ old('nomeConvidado') }}' placeholder='Nome do convidado'>" +
                "</div>" +
                "<div class='col-sm-6'>" +
                "<label for='email'>E-mail:</label>" +
                "<input class='form-control' type='email' name='emailDoConvidado[]' id='email' value='{{ old('emailConvidado') }}' placeholder='E-mail do convidado'>" +
                "</div>" +
                "</div>" +
                "<div class='row'>" +
                "<div class='col-sm-4'>" +
                "<label for='funcao'>Função:</label>" +
                "<select class='form-control' name='funçãoDoConvidado[]' id='funcao' onchange='outraFuncaoConvidado(" + contadorConvidados + ", this," + contadorConvidados + ")'>" +
                "<option value='' selected disabled>-- Função --</option>" +
                "<option value='Palestrante'>Palestrante</option>" +
                "<option value='Avaliador'>Avaliador</option>" +
                "<option value='Ouvinte'>Ouvinte</option>" +
                "<option value='Outra'>Outra</option>" +
                "</select>" +
                "</div>" +
                "<div id='divOutraFuncao" + contadorConvidados + "' class='col-sm-4' style='display: none;'>" +
                "<label for='Outra'>Qual?</label>" +
                "<input type='text' class='form-control apenasLetras' name='outra[]' id='outraFuncao'>" +
                "</div>" +
                "<div class='col-sm-4'>" +
                "<button type='button' onclick='removerConvidadoNovaAtividade(" + contadorConvidados + ")' style='border:none; background-color: rgba(0,0,0,0);'><img src='{{ asset('/img/icons/user-times-solid.svg') }}' width='50px' height='auto'  alt='remover convidade' style='padding-top: 28px;'></button>" +
                "</div>" +
                "</div>" +
                "</div>" +
                "</div>"
            )
        }
    }

    //Função que remove o convidado
    function removerConvidadoNovaAtividade(id) {
        contadorConvidados--;
        $("#novoConvidadoAtividade" + id).remove();
    }

    //Função que subemete o form de edição de uma atividade
    function editarAtividade(id) {
        var form = document.getElementById('formEdidarAtividade' + id);
        form.submit();
    }

    //Função que abre a exibição dos botões dos dados opcionais e a div em para uma nova e a edição de uma atividade
    function abrirDadosAdicionais(id) {
        if (id == 0) {
            var divDadosAdicionais = document.getElementById("dadosAdicionaisNovaAtividade");
            var buttonAbrir = document.getElementById("buttonAbrirDadosAdicionais");
            var buttonFechar = document.getElementById("buttonFecharDadosAdicionais");
            divDadosAdicionais.style.display = "block";
            buttonAbrir.style.display = "none";
            buttonFechar.style.display = "block";
        } else if (id > 0) {
            var divDadosAdicionais = document.getElementById("dadosAdicionaisNovaAtividade" + id);
            var buttonAbrir = document.getElementById("buttonAbrirDadosAdicionais" + id);
            var buttonFechar = document.getElementById("buttonFecharDadosAdicionais" + id);
            divDadosAdicionais.style.display = "block";
            buttonAbrir.style.display = "none";
            buttonFechar.style.display = "block";
        }
    }

    //Função que oculta a exibição dos botões dos dados opcionais e a em para uma nova e a edição de uma atividade
    function fecharDadosAdicionais(id) {
        if (id == 0) {
            var divDadosAdicionais = document.getElementById("dadosAdicionaisNovaAtividade");
            var buttonAbrir = document.getElementById("buttonAbrirDadosAdicionais");
            var buttonFechar = document.getElementById("buttonFecharDadosAdicionais");
            divDadosAdicionais.style.display = "none";
            buttonAbrir.style.display = "block";
            buttonFechar.style.display = "none";
        } else if (id > 0) {
            var divDadosAdicionais = document.getElementById("dadosAdicionaisNovaAtividade" + id);
            var buttonAbrir = document.getElementById("buttonAbrirDadosAdicionais" + id);
            var buttonFechar = document.getElementById("buttonFecharDadosAdicionais" + id);
            divDadosAdicionais.style.display = "none";
            buttonAbrir.style.display = "block";
            buttonFechar.style.display = "none";
        }
    }

    //Remover convidado existente de editar atividade
    function removerConvidadoAtividade(id) {
        $("#convidadoAtividade" + id).remove();
    }

    //Função que exibe a caixa de outra função do convidado
    function outraFuncaoConvidado(id, funcaoSelect, contador) {
        if (id == 0 && contador == 0) {
            var div = document.getElementById('divOutraFuncao');
            if (funcaoSelect.value == "Outra") {
                div.style.display = "block";
            } else {
                div.style.display = "none";
            }
        } else if (id == 0 && contador > 0) {
            var div = document.getElementById('divOutraFuncao' + contador);
            if (funcaoSelect.value == "Outra") {
                div.style.display = "block";
            } else {
                div.style.display = "none";
            }
        } else if (id > 0 && contador == 0) {
            var div = document.getElementById('divOutraFuncao' + id);
            if (funcaoSelect.value == "Outra") {
                div.style.display = "block";
            } else {
                div.style.display = "none";
            }
        } else if (id > 0 && contador > 0) {
            var div = document.getElementById('divOutraFuncao' + id);
            if (funcaoSelect.value == "Outra") {
                div.style.display = "block";
            } else {
                div.style.display = "none";
            }
        }
        // if (contador != 0) {
        //     var div = document.getElementById('divOutraFuncao'+contador);
        //     if (funcaoSelect.value == "Outra") {
        //         div.style.display = "block";
        //     } else {
        //         div.style.display = "none";
        //     }
        // } else {
        //     var div = document.getElementById('divOutraFuncao');
        //     if (funcaoSelect.value == "Outra") {
        //         div.style.display = "block";
        //     } else {
        //         div.style.display = "none";
        //     }
        // }
    }

    // Funções da aba de etiquetas
    function revisoresPorArea() {
        var idArea = document.getElementById('area_revisores').value;
        $.ajaxSetup({
            headers: {
                // 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        jQuery.ajax({
            url: "/revisores-por-area/" + idArea,
            method: 'get',
            success: function(result) {
                if (result != null) {
                    $('#revisores_cadastrados').html("");
                    var table = "<thead>" +
                        "<tr>" +
                        "<th scope='col'>Nome</th>" +
                        "<th scope='col'>Área</th>" +
                        "<th scope='col' style='text-align:center'>Em Andamento</th>" +
                        "<th scope='col' style='text-align:center'>Finalizados</th>" +
                        "<th scope='col' style='text-align:center'>Visualizar</th>" +
                        "<th scope='col' style='text-align:center'>Lembrar</th>" +
                        "</tr>" +
                        "</thead>" +
                        "<tbody>";
                    $.each(result, function(i, obj) {
                        table += "<tr>" +
                            "<td>" + obj.email + "</td>" +
                            "<td>" + obj.area + "</td>" +
                            "<td style='text-align:center'>" + obj.emAndamento + "</td>" +
                            "<td style='text-align:center'>" + obj.concluido + "</td>" +
                            "<td style='text-align:center'>" +
                            "<a href='#' data-bs-toggle='modal' data-bs-target='#modalRevisor'" + obj.id + ">" +
                            "<img src='{{asset('img/icons/eye-regular.svg')}}' style='width:20px'>" +
                            "</a>" +
                            "</td>" +
                            "<td style='text-align:center'>" +
                            "<form action='{{route('revisor.convite.evento', ['id' => $evento->id])}}' method='get' >" +
                            "<input type='hidden' name='id' value='" + obj.id + "'>" +
                            "<button class='btn btn-primary btn-sm' type='submit'>" +
                            "Enviar convite" +
                            "</button>" +
                            "</form>" +
                            "</td>" +
                            "</tr>";
                    })
                    table += "</tbody>"
                    $('#revisores_cadastrados').html(table);
                }
            }
        });
    }

    function pesquisaResultadoTrabalho() {
        var idArea = document.getElementById('area_trabalho_pesquisa').value;
        var pesquisaTexto = document.getElementById('pesquisaTexto').value;
        $.ajaxSetup({
            headers: {
                // 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        jQuery.ajax({
            url: "{{ route('trabalho.pesquisa.ajax') }}",
            method: 'get',
            data: {
                areaId: idArea,
                texto: pesquisaTexto,
            },
            success: function(result) {
                console.log(result);
                if (result != null) {
                    $('#cards_com_trabalhos').html("");
                    var cards = "";
                    $.each(result, function(i, obj) {
                        if (obj.rota_download != '#') {
                            cards += "<div class='card bg-light mb-3' style='width: 20rem;'>" +
                                "<div class='card-body'>" +
                                "<h5 class='card-title'>" + obj.titulo + "</h5>" +
                                "<h6 class='card-subtitle mb-2 text-muted'>" + obj.nome + "</h6>" +
                                "<label for='area'>Área:</label>" +
                                "<p id='area'>" + obj.area + "</p>" +
                                "<label for='modalidade'>Modalidade:</label>" +
                                "<p id='modalidade'>" + obj.modalidade + "</p>" +
                                "<a href='#' class='card-link' data-bs-toggle='modal' data-bs-target='#modalResultados" + obj.id + "'>Resultado</a>" +
                                "<a href='" + obj.rota_download + "' class='card-link'>Baixar</a>" +
                                "</div>" +
                                "</div>";
                        } else {
                            cards += "<div class='card bg-light mb-3' style='width: 20rem;'>" +
                                "<div class='card-body'>" +
                                "<h5 class='card-title'>" + obj.titulo + "</h5>" +
                                "<h6 class='card-subtitle mb-2 text-muted'>" + obj.nome + "</h6>" +
                                "<label for='area'>Área:</label>" +
                                "<p id='area'>" + obj.area + "</p>" +
                                "<label for='modalidade'>Modalidade:</label>" +
                                "<p id='modalidade'>" + obj.modalidade + "</p>" +
                                "<a href='#' class='card-link' data-bs-toggle='modal' data-bs-target='#modalResultados" + obj.id + "'>Resultado</a>" +
                                "</div>" +
                                "</div>";
                        }

                    })
                    $('#cards_com_trabalhos').html(cards);
                }
            }
        })
    }

    // Funções de inscrições
    function ativarLink(elemento) {
        elemento.children[0].click();

        if (elemento == document.getElementById("li_promocoes")) {
            elemento.className = "aba ativado";
            document.getElementById("li_cuponsDeDesconto").className = "aba aba-tab";
            document.getElementById("li_categoria_participante").className = "aba aba-tab";
            document.getElementById('li_formulario_inscricao').className = "aba aba-tab";
            document.getElementById('li_inscritos').className = "aba aba-tab";
        } else if (elemento == document.getElementById("li_cuponsDeDesconto")) {
            elemento.className = "aba ativado";
            document.getElementById("li_promocoes").className = "aba aba-tab";
            document.getElementById("li_categoria_participante").className = "aba aba-tab";
            document.getElementById('li_formulario_inscricao').className = "aba aba-tab";
            document.getElementById('li_inscritos').className = "aba aba-tab";
        } else if (elemento == document.getElementById("li_categoria_participante")) {
            elemento.className = "aba ativado";
            document.getElementById("li_promocoes").className = "aba aba-tab";
            document.getElementById("li_cuponsDeDesconto").className = "aba aba-tab";
            document.getElementById('li_formulario_inscricao').className = "aba aba-tab";
            document.getElementById('li_inscritos').className = "aba aba-tab";
        } else if (elemento == document.getElementById('li_formulario_inscricao')) {
            elemento.className = "aba ativado";
            document.getElementById("li_promocoes").className = "aba aba-tab";
            document.getElementById("li_cuponsDeDesconto").className = "aba aba-tab";
            document.getElementById("li_categoria_participante").className = "aba aba-tab";
            document.getElementById("li_inscritos").className = "aba aba-tab";
        } else if (elemento == document.getElementById('li_inscritos')) {
            elemento.className = "aba ativado";
            document.getElementById("li_promocoes").className = "aba aba-tab";
            document.getElementById("li_cuponsDeDesconto").className = "aba aba-tab";
            document.getElementById("li_categoria_participante").className = "aba aba-tab";
            document.getElementById('li_formulario_inscricao').className = "aba aba-tab";
        }
    }

    function adicionarLoteAhPromocao(id) {
        if (id == 0) {
            $('#lotes').append(
                "<div class='row'>" +
                "<div class='col-sm-4'>" +
                "<label for='dataDeInicio'>Data de início</label>" +
                "<input id='dataDeInicio' name='dataDeInício[]' class='form-control' type='date'>" +
                "</div>" +
                "<div class='col-sm-4'>" +
                "<label for='dataDeFim'>Data de fim</label>" +
                "<input id='dataDeFim' name='dataDeFim[]' class='form-control' type='date'>" +
                "</div>" +
                "<div class='col-sm-3'>" +
                "<label for='quantidade'>Disponibilidade</label>" +
                "<input id='quantidade' name='disponibilidade[]' class='form-control' type='number' placeholder='10'>" +
                "</div>" +
                "<div class='col-sm-1'>" +
                "<a href='#' title='Remover lote' onclick='removerLoteDaPromocao(this)'><img src='{{asset('img/icons/lixo.png')}}' width='35px' style='position: relative; top: 32px;'></a>" +
                "</div>" +
                "</div>"
            );
        } else {
            $('#lotes' + id).append(
                "<div class='row'>" +
                "<div class='col-sm-4'>" +
                "<label for='dataDeInicio'>Data de início</label>" +
                "<input id='dataDeInicio' name='dataDeInício_" + id + "[]' class='form-control' type='date'>" +
                "</div>" +
                "<div class='col-sm-4'>" +
                "<label for='dataDeFim'>Data de fim</label>" +
                "<input id='dataDeFim' name='dataDeFim_" + id + "[]' class='form-control' type='date'>" +
                "</div>" +
                "<div class='col-sm-3'>" +
                "<label for='quantidade'>Disponibilidade</label>" +
                "<input id='quantidade' name='disponibilidade_" + id + "[]' class='form-control' type='number' placeholder='10'>" +
                "</div>" +
                "<div class='col-sm-1'>" +
                "<a href='#' title='Remover lote' onclick='removerLoteDaPromocao(this)'><img src='{{asset('img/icons/lixo.png')}}' width='35px' style='position: relative; top: 32px;'></a>" +
                "</div>" +
                "</div>"
            );
        }
    }

    function removerLoteDaPromocao(elemento) {
        elemento.parentElement.parentElement.remove();
    }

    function mostrarCategorias(input, id) {
        if (id == 0) {
            if (input.checked) {
                document.getElementById('categoriasPromocao').style.display = "none";
            } else {
                document.getElementById('categoriasPromocao').style.display = "block";
            }
        } else {
            if (input.checked) {
                document.getElementById('categoriasPromocao' + id).style.display = "none";
            } else {
                document.getElementById('categoriasPromocao' + id).style.display = "block";
            }
        }
    }

    function alterarPlaceHolderDoNumero(elemento, id) {
        var input = null;
        if (id == 0) {
            input = document.getElementById('valorCupom');
        } else {
            input = document.getElementById('valorCupom' + id);
        }
        if (elemento.value == "real") {
            input.placeholder = "R$ 10,00"
        } else if (elemento.value == "porcentagem") {
            input.placeholder = "10%"
        }
    }

    function deixarMaiusculo(e) {
        var inicioCursor = e.target.selectionStart;
        var fimCursor = e.target.selectionEnd;
        e.target.value = e.target.value.toUpperCase();
        e.target.selectionStart = inicioCursor;
        e.target.selectionEnd = fimCursor;
    }

    function adicionarPeriodoCategoria(id) {
        var html = "";
        var quantidadeDePeriodos = 0;

        if (id == 0) {
            quantidadeDePeriodos = document.getElementById('periodosCategoria').children.length;
        } else {
            quantidadeDePeriodos = document.getElementById('periodosCategoria' + id).children.length;
        }

        if (quantidadeDePeriodos == 0) {
            if (id == 0) {
                html += "<div id='tituloDePeriodo' class='row form-group'>" +
                    "<div class='col-sm-12'>" +
                    "<hr>" +
                    "<h4>Periodos de desconto</h4>" +
                    "</div>" +
                    "</div>";
            } else {
                html += "<div id='tituloDePeriodo" + id + "' class='row form-group'>" +
                    "<div class='col-sm-12'>" +
                    "<hr>" +
                    "<h4>Periodos de desconto</h4>" +
                    "</div>" +
                    "</div>";
            }
        }

        if (id == 0) {
            html += "<div class='peridodoDesconto'>" +
                "<div class='row form-group'>" +
                "<div class='col-sm-4'>" +
                "<label for='tipo_valor'>Valor do desconto*</label>" +
                "<br>" +
                "<select class='form-control' name='tipo_valor[]' required>" +
                "<option value='' disabled selected>-- Escolha o tipo de valor --</option>" +
                "<option value='porcentagem'>Porcentagem</option>" +
                "<option value='real'>Real</option>" +
                "</select>" +
                "</div>" +
                "<div class='col-sm-6'>" +
                "<label for='valorDesconto'>Valor</label>" +
                "<input id='valorDesconto' name='valorDesconto[]' type='number' class='form-control real @error('number') is-invalid @enderror' placeholder='' value='' required>" +
                "</div>" +
                "</div>" +
                "<div class='row form-group'>" +
                "<div class='col-sm-5'> " +
                "<label for='inicio'>Data de início*</label>" +
                "<input id='inicio' name='inícioDesconto[]' class='form-control' type='date' value='' required>" +
                "</div>" +
                "<div class='col-sm-5'>" +
                "<label for='fim'>Data de fim*</label>" +
                "<input id='fim' name='fimDesconto[]' class='form-control' type='date' value='' required>" +
                "</div>" +
                "<div class='col-sm-2' style='position: relative; top: 35px;'>" +
                "<a type='button' onclick='removerPeriodoDesconto(this," + id + ")'><img src='{{asset('img/icons/trash-alt-regular.svg')}}' class='icon-card' alt=''></a>" +
                "</div>" +
                "</div>" +
                "</div>";
        } else {
            html += "<div class='peridodoDesconto'>" +
                "<div class='row form-group'>" +
                "<div class='col-sm-4'>" +
                "<label for='tipo_valor'>Valor do desconto*</label>" +
                "<br>" +
                "<select class='form-control' name='tipo_valor_" + id + "[]' required>" +
                "<option value='' disabled selected>-- Escolha o tipo de valor --</option>" +
                "<option value='porcentagem'>Porcentagem</option>" +
                "<option value='real'>Real</option>" +
                "</select>" +
                "</div>" +
                "<div class='col-sm-6'>" +
                "<label for='valorDesconto'>Valor</label>" +
                "<input id='valorDesconto' name='valorDesconto_" + id + "[]' type='number' class='form-control real @error('number') is-invalid @enderror' placeholder='' value='' required>" +
                "</div>" +
                "</div>" +
                "<div class='row form-group'>" +
                "<div class='col-sm-5'> " +
                "<label for='inicio'>Data de início*</label>" +
                "<input id='inicio' name='inícioDesconto_" + id + "[]' class='form-control' type='date' value='' required>" +
                "</div>" +
                "<div class='col-sm-5'>" +
                "<label for='fim'>Data de fim*</label>" +
                "<input id='fim' name='fimDesconto_" + id + "[]' class='form-control' type='date' value='' required>" +
                "</div>" +
                "<div class='col-sm-2' style='position: relative; top: 35px;'>" +
                "<a type='button' onclick='removerPeriodoDesconto(this," + id + ")'><img src='{{asset('img/icons/trash-alt-regular.svg')}}' class='icon-card' alt=''></a>" +
                "</div>" +
                "</div>" +
                "</div>";
        }

        if (id == 0) {
            $('#periodosCategoria').append(html);
        } else {
            $('#periodosCategoria' + id).append(html);
        }
    }

    function removerPeriodoDesconto(button, id) {
        var quantidadeDePeriodos = 0;
        if (id == 0) {
            quantidadeDePeriodos = document.getElementById('periodosCategoria').children.length;
        } else {
            quantidadeDePeriodos = document.getElementById('periodosCategoria' + id).children.length;
        }
        button.parentElement.parentElement.parentElement.remove();
        if (quantidadeDePeriodos == 2) {
            if (id == 0) {
                document.getElementById('tituloDePeriodo').remove();
            } else {
                document.getElementById('tituloDePeriodo' + id).remove();
            }
        }
    }

    function limpa_formulário_cep() {
        //Limpa valores do formulário de cep.
        document.getElementById('rua').value = ("");
        document.getElementById('bairro').value = ("");
        document.getElementById('cidade').value = ("");
        document.getElementById('uf').value = ("");
    }

    function meu_callback(conteudo) {
        if (!("erro" in conteudo)) {
            //Atualiza os campos com os valores.
            document.getElementById('rua').value = (conteudo.logradouro);
            document.getElementById('bairro').value = (conteudo.bairro);
            document.getElementById('cidade').value = (conteudo.localidade);
            document.getElementById('uf').value = (conteudo.uf);
        } //end if.
        else {
            //CEP não Encontrado.
            limpa_formulário_cep();
            alert("CEP não encontrado.");
        }
    }

    function pesquisacep(valor) {
        //Nova variável "cep" somente com dígitos.
        var cep = valor.replace(/\D/g, '');
        //Verifica se campo cep possui valor informado.
        if (cep != "") {
            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;
            //Valida o formato do CEP.
            if (validacep.test(cep)) {
                //Preenche os campos com "..." enquanto consulta webservice.
                document.getElementById('rua').value = "...";
                document.getElementById('bairro').value = "...";
                document.getElementById('cidade').value = "...";
                document.getElementById('uf').value = "...";
                //Cria um elemento javascript.
                var script = document.createElement('script');
                //Sincroniza com o callback.
                script.src = 'https://viacep.com.br/ws/' + cep + '/json/?callback=meu_callback';
                //Insere script no documento e carrega o conteúdo.
                document.body.appendChild(script);
            } //end if.
            else {
                //cep é inválido.
                limpa_formulário_cep();
                alert("Formato de CEP inválido.");
            }
        } //end if.
        else {
            //cep sem valor, limpa formulário.
            limpa_formulário_cep();
        }
    }

    function selectsFormulario() {
        return {
            opcoes: [''],
            adicionaOpcao() {
                this.opcoes.push('');
            },
            removeOpcao(index) {
                if (this.opcoes.length > 1) {
                    this.opcoes.splice(index, 1);
                }
            },
        }
    }

    function mostrarCampos(tipoCampo) {
        var botoes = document.getElementById('escolherInput');
        var inputs = document.getElementById('preencherDados');
        var selects = document.getElementById('selects');
        var inputtextform = document.getElementById('input-text-form');
        var botoesSubmissao = document.getElementById('botoesDeSubmissao');
        var inputTipoCampo = document.getElementById('tipo_campo');
        var campoExemplo = document.getElementById('campoExemplo');
        var tituloDoCampo = document.getElementById('titulo_do_campo');
        var campoExemploCpf = document.getElementById('campoExemploCpf');
        var campoExemploNumero = document.getElementById('campoExemploNumero');
        var divEnderecoExemplo = document.getElementById('divEnderecoExemplo');
        var divTituloExemplo = document.getElementById('tituloExemplo');

        botoes.style.display = "none";
        selects.style.display = "none";
        inputs.style.display = "block";
        inputtextform.style.display = "block";
        botoesSubmissao.style.display = "block";

        switch (tipoCampo) {
            case 'file':
                inputTipoCampo.value = tipoCampo;
                $('#labelCampoExemplo').html("");
                $('#labelCampoExemplo').append("Comprovante de matrícula");
                campoExemplo.type = tipoCampo;
                campoExemplo.className = "";
                campoExemploCpf.style.display = "none";
                campoExemplo.style.display = "block";
                campoExemploNumero.style.display = "none";
                divEnderecoExemplo.style.display = "none";
                tituloDoCampo.placeholder = "Comprovante de matrícula";
                tituloDoCampo.value = "";
                divTituloExemplo.className = "campo-exemplo";
                $('#titulo_do_campo').on('keyup', function(e) {
                    if ($(this).val() != "") {
                        $('#labelCampoExemplo').html("");
                        $('#labelCampoExemplo').append($(this).val());
                    } else {
                        $('#labelCampoExemplo').html("");
                        $('#labelCampoExemplo').append("Comprovante de matrícula");
                    }
                });
                break;
            case 'date':
                inputTipoCampo.value = tipoCampo;
                $('#labelCampoExemplo').html("");
                $('#labelCampoExemplo').append("Data de nascimento");
                campoExemplo.type = tipoCampo;
                campoExemplo.style.display = "block";
                campoExemplo.className = "form-control";
                campoExemploCpf.style.display = "none";
                campoExemploNumero.style.display = "none";
                divEnderecoExemplo.style.display = "none";
                tituloDoCampo.placeholder = "Data de nascimento";
                tituloDoCampo.value = "";
                divTituloExemplo.className = "campo-exemplo";
                $('#titulo_do_campo').on('keyup', function(e) {
                    if ($(this).val() != "") {
                        $('#labelCampoExemplo').html("");
                        $('#labelCampoExemplo').append($(this).val());
                    } else {
                        $('#labelCampoExemplo').html("");
                        $('#labelCampoExemplo').append("Data de nascimento");
                    }
                });
                break;
            case 'select':
                tituloDoCampo.placeholder = "Nome do select"
                inputTipoCampo.value = tipoCampo;
                botoes.style.display = "none";
                inputs.style.display = "block";
                inputtextform.style.display = "none";
                selects.style.display = "block";
                botoesSubmissao.style.display = "block";
                break;
            case 'email':
                inputTipoCampo.value = tipoCampo;
                $('#labelCampoExemplo').html("");
                $('#labelCampoExemplo').append("E-mail para contato");
                campoExemplo.type = tipoCampo;
                campoExemplo.style.display = "block";
                campoExemploCpf.style.display = "none";
                campoExemplo.className = "form-control";
                campoExemploNumero.style.display = "none";
                divEnderecoExemplo.style.display = "none";
                tituloDoCampo.placeholder = "E-mail para contato";
                tituloDoCampo.value = "";
                divTituloExemplo.className = "campo-exemplo";
                $('#titulo_do_campo').on('keyup', function(e) {
                    if ($(this).val() != "") {
                        $('#labelCampoExemplo').html("");
                        $('#labelCampoExemplo').append($(this).val());
                    } else {
                        $('#labelCampoExemplo').html("");
                        $('#labelCampoExemplo').append("E-mail para contato");
                    }
                });
                break;
            case 'text':
                inputTipoCampo.value = tipoCampo;
                $('#labelCampoExemplo').html("");
                $('#labelCampoExemplo').append("Por que quer participar?");
                campoExemplo.style.display = "block";
                campoExemplo.type = tipoCampo;
                campoExemploCpf.style.display = "none";
                campoExemplo.className = "form-control";
                campoExemploNumero.style.display = "none";
                divEnderecoExemplo.style.display = "none";
                tituloDoCampo.placeholder = "Por que quer participar?";
                tituloDoCampo.value = "";
                divTituloExemplo.className = "campo-exemplo";
                $('#titulo_do_campo').on('keyup', function(e) {
                    if ($(this).val() != "") {
                        $('#labelCampoExemplo').html("");
                        $('#labelCampoExemplo').append($(this).val());
                    } else {
                        $('#labelCampoExemplo').html("");
                        $('#labelCampoExemplo').append("Por que quer participar?");
                    }
                });
                break;
            case 'cpf':
                inputTipoCampo.value = tipoCampo;
                $('#labelCampoExemplo').html("");
                $('#labelCampoExemplo').append("Seu CPF");
                campoExemplo.style.display = "none";
                campoExemploCpf.style.display = "block";
                tituloDoCampo.placeholder = "Seu CPF";
                campoExemploNumero.style.display = "none";
                divEnderecoExemplo.style.display = "none";
                tituloDoCampo.value = "";
                divTituloExemplo.className = "campo-exemplo";
                $('#titulo_do_campo').on('keyup', function(e) {
                    if ($(this).val() != "") {
                        $('#labelCampoExemplo').html("");
                        $('#labelCampoExemplo').append($(this).val());
                    } else {
                        $('#labelCampoExemplo').html("");
                        $('#labelCampoExemplo').append("Seu CPF");
                    }
                });
                break;
            case 'contato':
                inputTipoCampo.value = tipoCampo;
                $('#labelCampoExemplo').html("");
                $('#labelCampoExemplo').append("Seu celular");
                campoExemplo.style.display = "none";
                campoExemploCpf.style.display = "none";
                tituloDoCampo.placeholder = "Seu celular";
                campoExemploNumero.style.display = "block";
                divEnderecoExemplo.style.display = "none";
                tituloDoCampo.value = "";
                divTituloExemplo.className = "campo-exemplo";
                $('#titulo_do_campo').on('keyup', function(e) {
                    if ($(this).val() != "") {
                        $('#labelCampoExemplo').html("");
                        $('#labelCampoExemplo').append($(this).val());
                    } else {
                        $('#labelCampoExemplo').html("");
                        $('#labelCampoExemplo').append("Seu celular");
                    }
                });
                break;
            case 'endereco':
                inputTipoCampo.value = tipoCampo;
                $('#labelCampoExemplo').html("");
                $('#labelCampoExemplo').append("Endereço residencial");
                campoExemplo.style.display = "none";
                campoExemploCpf.style.display = "none";
                tituloDoCampo.placeholder = "Endereço residencial";
                campoExemploNumero.style.display = "none";
                divEnderecoExemplo.style.display = "block";
                tituloDoCampo.value = "";
                divTituloExemplo.className = "campo-exemplo-head";
                divEnderecoExemplo.className = "campo-exemplo-body";
                $('#titulo_do_campo').on('keyup', function(e) {
                    if ($(this).val() != "") {
                        $('#labelCampoExemplo').html("");
                        $('#labelCampoExemplo').append($(this).val());
                    } else {
                        $('#labelCampoExemplo').html("");
                        $('#labelCampoExemplo').append("Endereço residencial");
                    }
                });
                break;
        }
    }

    function voltarBotoes() {
        var botoes = document.getElementById('escolherInput');
        var inputs = document.getElementById('preencherDados');
        var botoesSubmissao = document.getElementById('botoesDeSubmissao');

        botoes.style.display = "block";
        inputs.style.display = "none";
        botoesSubmissao.style.display = "none";
    }

    function mostrarCheckBoxCategoria(input, id) {
        if (id == 0) {
            if (input.checked) {
                document.getElementById('checkboxCategoria').style.display = "none";
            } else {
                document.getElementById('checkboxCategoria').style.display = "block";
            }
        } else {
            if (input.checked) {
                document.getElementById('checkboxCategoria' + id).style.display = "none";
            } else {
                document.getElementById('checkboxCategoria' + id).style.display = "block";
            }
        }
    }
</script>
@if (old('campo_id') != null)
<script>
    $(document).ready(function() {
        $("#modalCampoEdit" + "{{old('campo_id')}}").modal('show');
    });
</script>
@endif
@if (old('criarCampo') != null)
<script>
    $(document).ready(function() {
        $("#modalCriarCampo").modal('show');

        switch ("{{old('tipo_campo')}}") {
            case 'email':
                $("#btn-tipo-email").click();
                break;
            case 'text':
                $("#btn-tipo-text").click();
                break;
            case 'file':
                $("#btn-tipo-file").click();
                break;
            case 'date':
                $("#btn-tipo-date").click();
                break;
            case 'endereco':
                $("#btn-tipo-endereco").click();
                break;
            case 'cpf':
                $("#btn-tipo-cpf").click();
                break;
            case 'contato':
                $("#btn-tipo-contato").click();
                break;
        }
    })
</script>
@endif
@if (old('editarCupom') != null)
<script>
    $(document).ready(function() {
        $("#modalEditarCupom" + "{{old('editarCupom')}}").modal('show');
    })
</script>
@endif
@if (old('editarPromocao') != null)
<script>
    $(document).ready(function() {
        $("#modalPromocaoEdit" + "{{old('editarPromocao')}}").modal('show');
    })
</script>
@endif
@if (old('editarCategoria') != null)
<script>
    $(document).ready(function() {
        $("#modalEditarCategoria" + "{{old('editarCategoria')}}").modal('show');
    })
</script>
@endif
@if (old('criarCategoria') != null)
<script>
    $(document).ready(function() {
        $("#modalCriarCategoria").modal('show');
    })
</script>
@endif
@if (old('criarCupom') != null)
<script>
    $(document).ready(function() {
        $("#modalCriarCupom").modal('show');
    })
</script>
@endif
@if (old('novaPromocao') != null)
<script>
    $(document).ready(function() {
        $("#modalCriarPromocao").modal('show');
    })
</script>
@endif
@if(old('editarAreaId') != null)
<script>
    $(document).ready(function() {
        $("#modalEditarArea{{old('editarAreaId')}}").modal('show');
    })
</script>
@endif
@if(old('modalidadeEditId') != null)
<script>
    $(document).ready(function() {
        $("#modalEditarModalidade{{old('modalidadeEditId')}}").modal('show');
    });
</script>
@endif
@if(old('idAtividade') != null)
<script>
    $(document).ready(function() {
        $('#modalAtividadeEdit{{old('
            idAtividade ')}}').modal('show');
    });
</script>
@endif
@if (old('distribuirTrabalhosAutomaticamente') != null)
<script>
    $(document).ready(function() {
        $('#modalDistribuicaoAutomatica').modal('show');
    });
</script>
@endif
@if(old('cadastrarRevisor') != null)
<script>
    $(document).ready(function() {
        $('#modalCadastrarRevisor').modal('show');
    });
</script>
@endif
@if(old('editarRevisor') != null)
<script>
    $(document).ready(function() {
        $('#modalEditarRevisor{{old('
            editarRevisor ')}}').modal('show');
    });
</script>
@endif
@if(old('idNovaAtividade') == 2)
<script>
    $(document).ready(function() {
        $('#modalCriarAtividade').modal('show');
    });
    $(document).ready(function() {
        document.getElementById('divDuracaoAtividade').style.display = "block";
        switch ($('#duracaoAtividade').val()) {
            case '1':
                document.getElementById('dia1').style.display = "block";
                document.getElementById('dia2').style.display = "none";
                document.getElementById('dia3').style.display = "none";
                document.getElementById('dia4').style.display = "none";
                document.getElementById('dia5').style.display = "none";
                document.getElementById('dia6').style.display = "none";
                document.getElementById('dia7').style.display = "none";
                break;
            case '2':
                document.getElementById('dia1').style.display = "block";
                document.getElementById('dia2').style.display = "block";
                document.getElementById('dia3').style.display = "none";
                document.getElementById('dia4').style.display = "none";
                document.getElementById('dia5').style.display = "none";
                document.getElementById('dia6').style.display = "none";
                document.getElementById('dia7').style.display = "none";
                break;
            case '3':
                document.getElementById('dia1').style.display = "block";
                document.getElementById('dia2').style.display = "block";
                document.getElementById('dia3').style.display = "block";
                document.getElementById('dia4').style.display = "none";
                document.getElementById('dia5').style.display = "none";
                document.getElementById('dia6').style.display = "none";
                document.getElementById('dia7').style.display = "none";
                break;
            case '4':
                document.getElementById('dia1').style.display = "block";
                document.getElementById('dia2').style.display = "block";
                document.getElementById('dia3').style.display = "block";
                document.getElementById('dia4').style.display = "block";
                document.getElementById('dia5').style.display = "none";
                document.getElementById('dia6').style.display = "none";
                document.getElementById('dia7').style.display = "none";
                break;
            case '5':
                document.getElementById('dia1').style.display = "block";
                document.getElementById('dia2').style.display = "block";
                document.getElementById('dia3').style.display = "block";
                document.getElementById('dia4').style.display = "block";
                document.getElementById('dia5').style.display = "block";
                document.getElementById('dia6').style.display = "none";
                document.getElementById('dia7').style.display = "none";
                break;
            case '6':
                document.getElementById('dia1').style.display = "block";
                document.getElementById('dia2').style.display = "block";
                document.getElementById('dia3').style.display = "block";
                document.getElementById('dia4').style.display = "block";
                document.getElementById('dia5').style.display = "block";
                document.getElementById('dia6').style.display = "block";
                document.getElementById('dia7').style.display = "none";
                break;
            case '7':
                document.getElementById('dia1').style.display = "block";
                document.getElementById('dia2').style.display = "block";
                document.getElementById('dia3').style.display = "block";
                document.getElementById('dia4').style.display = "block";
                document.getElementById('dia5').style.display = "block";
                document.getElementById('dia6').style.display = "block";
                document.getElementById('dia7').style.display = "block";
                break;
        }
    });
</script>
@endif
@endsection
