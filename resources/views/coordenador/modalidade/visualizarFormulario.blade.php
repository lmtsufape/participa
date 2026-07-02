@extends('layouts.app')
@section('sidebar')
@endsection
@section('content')

    <style>
        select[readonly] {
            background: #eee;
            /*Simular campo inativo - Sugestão @GabrielRodrigues*/
            pointer-events: none;
            touch-action: none;
        }
    </style>

    <div id="divListarCriterio" class="comissao">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="titulo-detalhes">Formulário(s) da modalidade: <strong> {{$modalidade->nome}}</strong> </h3>
            </div>
        </div>
    </div>
    {{-- {{dd($modalidade->forms)}} --}}
    @foreach ($modalidade->forms->sortBy("created_at") as $form)
        <div class="card" style="width: 48rem;">
            <div class="card-body">
                <h5 class="card-title">{{$form->titulo}}</h5>
                <h5 class="card-title">Orientações aos(as) avaliadores(as):</h5>
                {!! $form->instrucoes !!}

                <p class="card-text">
                <table class="table table-hover table-responsive-lg table-sm">
                    <thead>
                        <tr>
                            <th scope="col" style="text-align:center">Editar</th>
                            <th scope="col" style="text-align:center">Excluir</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="text-align:center">
                                <a href="{{route('coord.modalidades.form.edit', compact('evento', 'form'))}}"><img src="{{asset('img/icons/edit-regular.svg')}}" style="width:20px"></a>
                            </td>
                            <td style="text-align:center">
                                <a href="" data-bs-toggle="modal" data-bs-target="#modalExcluirForm{{$form->id}}"><i class="bi bi-trash text-danger fa-lg"></i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
                </p>

                <p class="card-text">

                    @foreach ($form->perguntas->sortBy("id") as $pergunta)
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <p>{!! $pergunta->pergunta !!}</p>
                            </div>

                        </div>


                @if($pergunta->respostas->first()->opcoes->count())
                        <p>Resposta com Múltipla escolha:</p>
                        @foreach ($pergunta->respostas->first()->opcoes->sortBy("id") as $opcao)
                        <div class="col-md-10 itemRadio">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <input type="checkbox" disabled @if($opcao->check) checked @endif>
                                    </div>
                            </div>
                            <input type="text" class="form-control" value=" {{$opcao->titulo}}" disabled>
                        </div>
                    </div>
                                        @endforeach
                        @elseif($pergunta->respostas->first()->paragrafo)
                        <p>Resposta com parágrafo: </p>
                        <div class="col-md-10">
                            <input type="text" style="margin-bottom:10px" disabled='true' class="form-control">
                        </div>
                        @endif
                        <div class="col-md-5">
                            <div class="col-form-label text-md-left">
                                <small>Visível para o autor? </small><input disabled type="checkbox" @if ($pergunta->visibilidade) checked @endif>
                            </div>
                        </div>
                    </div>
                </div>


                @endforeach
                </p>

            </div>
        </div>
    @endforeach

@endsection


@foreach ($modalidade->forms as $form)
    <!-- Modal de exclusão do form -->
    <div class="modal fade" id="modalExcluirForm{{$form->id}}" tabindex="-1" role="dialog" aria-labelledby="#label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #114048ff; color: white;">
                    <h5 class="modal-title" id="#label">Confirmação</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('coord.deletar.form', ['id' => $form->id])}}" method="get">
                    @csrf
                    <div class="modal-body">
                        Tem certeza que deseja excluir esse formulário?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                        <button type="submit" class="btn btn-primary">Sim</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
{{-- Fim Modal --}}



@section('javascript')
@parent
<script>
    CKEDITOR.replaceAll('ckeditorinput');
    let rep = 0;
    let order = 1;
    let pergunta = 1;

    // variavel p/ perguntas novas
    let novaPerguntaIndex = 0;

    function gerarOpcoesMultiplaEscolhaNovaPerguntaHtml(idx) {
        return `
            <div class="optionResposta col-md-12 p-0 m-0 row">
                <div class="input-group mb-3 col-md-10">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <input type="checkbox" disabled>
                        </div>
                    </div>
                    <input type="text" name="tituloCheckox[${idx}][]" class="form-control" required>
                </div>
                <div class="col-md-1 mt-2 d-flex align-items-center" style="gap: 4px;">
                    <a href="#" class="addOpcaoNova" data-novoid="${idx}"><img src="{{ asset('img/icons/plus-square-solid_black.svg') }}" style="width:20px" alt="Adicionar"></a>
                    <a href="#" class="removeOpcao"><img src="{{ asset('img/icons/trash-alt-regular.svg') }}" style="width:20px" alt="Excluir"></a>
                </div>
            </div>
        `;
    }

    function gerarOpcoesMultiplaEscolhaExistenteHtml(rowId) {
        return `
            <div class="optionResposta col-md-12 p-0 m-0 row">
                <div class="input-group mb-3 col-md-10">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <input type="checkbox" disabled>
                        </div>
                    </div>
                    <input type="text" name="tituloRadio[${rowId}][]" class="form-control" required>
                </div>
                <div class="col-md-1 mt-2">
                    <a href="#" class="addOpcaoExistente" data-rowid="${rowId}"><img src="{{ asset('img/icons/plus-square-solid.svg') }}" style="width:20px" alt="Adicionar"></a>
                    <a href="#" class="removeOpcao"><img src="{{ asset('img/icons/trash-alt-regular.svg') }}" style="width:20px" alt="Excluir"></a>
                </div>
            </div>
        `;
    }

    // Função para atualizar o campo de resposta conforme o tipo
    function escolha(selectElement, event) {
        var tipo = selectElement.value;
        var respostaContainer = $(selectElement).closest('.row.card-body').find('.row[id^="row"]');
        // Verifica se é uma pergunta nova (sem data-rowid)
        let rowId = $(selectElement).data('rowid');
        let novoId = $(selectElement).data('novoid');
        if (typeof rowId === 'undefined' && typeof novoId !== 'undefined') {
            if (tipo === 'paragrafo') {
                respostaContainer.html(`
                    <div class="col-md-12">
                        <input type="text" style="margin-bottom:10px" disabled='true' class="form-control" name="resposta[]">
                    </div>
                `);
            } else if (tipo === 'checkbox') {
                respostaContainer.html(gerarOpcoesMultiplaEscolhaNovaPerguntaHtml(novoId));
            }
        } else if (typeof rowId !== 'undefined') {
            // Pergunta existente
            if (tipo === 'paragrafo') {
                respostaContainer.html(`
                    <div class="col-md-12">
                        <input type="text" style="margin-bottom:10px" disabled='true' class="form-control" name="resposta[]">
                    </div>
                `);
            } else if (tipo === 'radio') {
                respostaContainer.html(gerarOpcoesMultiplaEscolhaExistenteHtml('row' + rowId));
            }
        }
    }

    // Delegar eventos para adicionar/remover opções de múltipla escolha para perguntas novas
    $(document).on('click', '.addOpcaoNova', function(e) {
        e.preventDefault();
        var idx = $(this).data('novoid');
        var novaOpcao = `
            <div class="optionResposta col-md-12 p-0 m-0 row">
                <div class="input-group mb-3 col-md-10">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <input type="checkbox" disabled>
                        </div>
                    </div>
                    <input type="text" name="tituloCheckox[${idx}][]" class="form-control" required>
                </div>
                <div class="col-md-1 mt-2 d-flex align-items-center" style="gap: 4px;">
                    <a href="#" class="addOpcaoNova" data-novoid="${idx}"><img src="{{ asset('img/icons/plus-square-solid_black.svg') }}" style="width:20px" alt="Adicionar"></a>
                    <a href="#" class="removeOpcao"><img src="{{ asset('img/icons/trash-alt-regular.svg') }}" style="width:20px" alt="Excluir"></a>
                </div>
            </div>
        `;
        $(this).closest('.row').append(novaOpcao);
    });

    // Delegar eventos para adicionar/remover opções de múltipla escolha para perguntas existentes
    $(document).on('click', '.addOpcaoExistente', function(e) {
        e.preventDefault();
        var rowId = $(this).data('rowid');
        var novaOpcao = `
            <div class="optionResposta col-md-12 p-0 m-0 row">
                <div class="input-group mb-3 col-md-10">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <input type="checkbox" disabled>
                        </div>
                    </div>
                    <input type="text" name="tituloRadio[${rowId}][]" class="form-control" required>
                </div>
                <div class="col-md-1 mt-2">
                    <a href="#" class="addOpcaoExistente" data-rowid="${rowId}"><img src="{{ asset('img/icons/plus-square-solid.svg') }}" style="width:20px" alt="Adicionar"></a>
                    <a href="#" class="removeOpcao"><img src="{{ asset('img/icons/trash-alt-regular.svg') }}" style="width:20px" alt="Excluir"></a>
                </div>
            </div>
        `;
        $(this).closest('.row').append(novaOpcao);
    });

    $(document).on('click', '.removeOpcao', function(e) {
        e.preventDefault();
        $(this).closest('.optionResposta').remove();
    });


    $(document).on('change', 'select[name="tipo[]"]', function(e) {
        escolha(this, e);
    });



    function myFunction(event) {
        event.preventDefault();
        el = event.srcElement.id
        // console.log( event.path['5'].childNodes)
        arr = event.path['5'].childNodes;

        if (el == "arrow-up") {
            number = event.path['4'].style.order;
            if (number == 1) return

            for (var i = 0; i < arr.length; i++) {
                if (event.path['5'].childNodes[i].style['order'] == parseInt(event.path['4'].style.order, 10) - 1) {
                    event.path['5'].childNodes[i].style['order'] = parseInt(event.path['5'].childNodes[i].style['order'], 10) + parseInt(1, 10);

                    event.path['4'].style.order = parseInt(event.path['4'].style.order, 10) - parseInt(1, 10);

                    break;
                }
            }



        } else if (el == "arrow-down") {
            number = event.path['4'].style.order;
            if (number == order) return

            for (var i = 0; i < arr.length; i++) {
                if (event.path['5'].childNodes[i].style['order'] == parseInt(event.path['4'].style.order, 10) + 1) {
                    event.path['5'].childNodes[i].style['order'] = parseInt(event.path['5'].childNodes[i].style['order'], 10) - parseInt(1, 10);

                    event.path['4'].style.order = parseInt(event.path['4'].style.order, 10) + parseInt(1, 10);

                    break;
                }
            }

        }
    }


    function addLinha(event) {
        event.preventDefault();
        order += 1;
        linha = montarLinhaInput(order);
        $('#coautores2').append(linha);
    }
    $(document).ready(function() {

    });

    function addCheckbox(event) {
        event.preventDefault();
        console.log(event.path[3].childNodes[3].parentElement.id);
        let id = event.path[3].childNodes[3].parentElement.id;

        let div = document.createElement('div');
        console.log(div)
        div.classList.add("col-md-12");
        div.classList.add("row");
        div.classList.add("p-0");
        div.classList.add("ml-0");

        div.innerHTML = addCheckboxInput(id);
        let find = document.querySelector("#" + id);
        find.appendChild(div);
    }

    $(document).on('click', '.radioDelete', function() {
        $(this).closest('.itemRadio').remove();
        return false;
    });

    // Remover Coautor
    $(document).on('click', '.delete2', function() {
        $(this).closest('.item').slideUp("normal", function() {
            $(this).remove();
        });

        //$(this).closest('.item').remove();
        return false;
    });

    function montarLinhaInput(order) {

        let idx = novaPerguntaIndex;
        novaPerguntaIndex++;
        return `<div class="item card" style="order:${order}">
                    <div class="row card-body">
                        <div class="col-sm-12">
                            <label>Pergunta</label>
                            <input type="text" syle="margin-bottom:10px"  class="form-control " name="pergunta[]" required>
                        </div>
                        <div class="col-sm-8" >
                            <label>Resposta</label>
                            <div class="row" id="rowNew${order}">
                                <div class="col-md-12">
                                    <input type="text" style="margin-bottom:10px" disabled='true' class="form-control " name="resposta[]">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Tipo</label>
                                <select onchange="escolha(this, event)" name="tipo[]" class="form-control" id="FormControlSelect" data-novoid="${idx}">
                                    <option value="paragrafo">Parágrafo</option>
                                    <option value="checkbox">Múltipla escolha</option>
                                </select>
                              </div>
                        </div>
                        <div class="col-md-5"></div>
                        <div class="col-sm-7">
                            <a href="#" class=" 2 pr-2 mr-2">
                                <i class="bi bi-trash3 fs-4 icon-card text-danger"></i>
                            </a>

                        </div>
                    </div>
                </div>`;
    }

    function montarOpcao(check) {
        rep += 1;
        return `<div  class="col-md-10 itemRadio">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                <input id="${rep}" name="checkbox" type="checkbox" aria-label="Checkbox for following text input" onclick="changeResposta(${rep});">
                                 <input hidden id="checked[${rep}]" name="tituloCheckoxMarc[${check}][]"  type="text" value="false" >
                                </div>
                            </div>
                            <input type="text" name="tituloCheckox[${check}][]" class="form-control" aria-label="Text input with checkbox" required>
                        </div>
                    </div>
                    <div class="col-md-1 mt-2">
                        <a href="#"  onclick="addCheckbox(event)"><img src="{{ asset('img/icons/plus-square-solid.svg') }}" style="width:20px" alt="Adicionar"></a>
                    </div>
                    `;
    }

    function addCheckboxInput(check) {
        rep += 1;
        return `
                <div class="optionResposta col-md-12 p-0 m-0 row">
                    <div class="input-group mb-3 col-md-10">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                            <input id="${rep}" name="checkbox" type="checkbox" aria-label="Checkbox for following text input" onclick="changeResposta(${rep});">
                            <input hidden id="checked[${rep}]" name="tituloCheckoxMarc[${check}][]"  type="text" value="false" >
                            </div>
                        </div>
                        <input type="text" name="tituloCheckox[${check}][]" class="form-control" aria-label="Text input with checkbox">
                    </div>
                    <div class="col-md-1 mt-2">
                         <a type="button" class="removeRow" ><img src="{{ asset('img/icons/trash-alt-regular.svg') }}" style="width:20px" alt="Excluir"></a>
                    </div>
               </div>
                   `;
    }

    function addParagrafo() {
        return `<div class="col-md-12">
                        <input type="text" style="margin-bottom:10px" disabled='true' class="form-control" name="resposta[]" required>
                    </div>`;
    }

    function changeResposta(marc) {
        if (document.getElementById(marc).checked) {
            document.getElementById('checked[' + marc + ']').value = 'true';
        } else {
            document.getElementById('checked[' + marc + ']').value = 'false';
        }
    }

    $(document).on("click", ".removeRow", function() {
        $(this).parents(".optionResposta").remove();
    });


    // $(document).ready(function(){
    //     $('.move-down').click(function(){
    //         if (($(this).next()) && ($(this).parents("#bisavo").next().attr('id') == "bisavo")) {
    //             console.log("IF MOVE-DOWN");
    //             var t = $(this);
    //             t.parents("#bisavo").animate({top: '20px'}, 500, function(){
    //                 t.parents("#bisavo").next().animate({top: '-20px'}, 500, function(){
    //                     t.parents("#bisavo").css('top', '0px');
    //                     t.parents("#bisavo").next().css('top', '0px');
    //                     t.parents("#bisavo").insertAfter(t.parents("#bisavo").next());
    //                 });
    //             });
    //             // $(this).parents("#bisavo").insertAfter($(this).parents("#bisavo").next());
    //         }
    //         else {
    //             console.log("ELSE MOVE-DOWN");
    //         }
    //     });
    //     $('.move-up').click(function(){
    //         if (($(this).prev()) && ($(this).parents("#bisavo").prev().attr('id') == "bisavo")) {
    //             console.log("IF MOVE-UP");
    //             var t = $(this);
    //             t.parents("#bisavo").animate({top: '-20px'}, 500, function(){
    //                 t.parents("#bisavo").prev().animate({top: '20px'}, 500, function(){
    //                     t.parents("#bisavo").css('top', '0px');
    //                     t.parents("#bisavo").prev().css('top', '0px');
    //                     t.parents("#bisavo").insertBefore(t.parents("#bisavo").prev());
    //                 });
    //             });
    //             // $(this).parents("#bisavo").insertBefore($(this).parents("#bisavo").prev());
    //         }
    //         else {
    //             console.log("ELSE MOVE-UP");
    //         }
    //     });
    // });
</script>
@endsection
