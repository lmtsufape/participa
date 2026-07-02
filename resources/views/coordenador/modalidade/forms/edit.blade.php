@extends('layouts.app')
@section('sidebar')
@endsection
@section('content')

    <div class="container">
        <div class="comissao">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="titulo-detalhes">
                        Editar formulário
                    </h3>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-sm-12">
                <form method="POST" action="{{route('coord.update.form', $form->id)}}" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="row justify-content-center">
                        <div class="col-sm-12">
                            <label for="titulo" class="form-label">{{ __('Título do Formulário') }}</label>
                            <input id="titulo" type="text" class="form-control @error('titulo') is-invalid @enderror" name="titulo" value="{{old('titulo', $form->titulo)}}" required autocomplete="titulo" autofocus>
                            @error('titulo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-12 mt-3">
                            <label for="instrucoes" class="form-label">Orientações aos(as) avaliadores(as):</label>
                            <textarea type="text" class="form-control mb-2 ckeditorinput @error('instrucoes') is-invalid @enderror" name="instrucoes" id="instrucoes">{!!old('instrucoes', $form->instrucoes)!!}</textarea>
                            @error('instrucoes')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{$message}}</strong>
                                </span>
                            @enderror
                        </div>
                        @foreach ($form->perguntas as $index => $pergunta)

                            @php
                                $resposta = $pergunta->respostaPadrao;
                                $opcoes   = $resposta?->opcoes ?? collect();
                                $tipo     = old("tipos.$index", $opcoes->isNotEmpty() ? 'radio' : 'paragrafo');
                            @endphp
                            <div id="listagem_perguntas" class="d-flex flex-column">
                                <div class="item card w-100 mt-4" style="order:{{$index}}">
                                    <div class="card-header">
                                        <div class="form-group">
                                            <label for="perguntas_{{$index}}">Pergunta</label>
                                            <textarea name="perguntas[]" id="perguntas_{{$index}}" class="form-control @error("perguntas_$index") is-invalid @enderror ckeditorinput" required>{{old("perguntas_$index", $pergunta->pergunta)}}</textarea>
                                            <input type="hidden" name="pergunta_id[]" value="{{$pergunta->id}}">
                                            @error("perguntas_$index")
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{$message}}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="container row">
                                            <div class="col-md-4 form-check">
                                                <input class="form-check-input" type="checkbox" id="visibilidades_{{ $index }}" name="visibilidades[{{ $index }}]" value="on" @checked(old("visibilidades.$index", $pergunta->visibilidade))>
                                                <label class="form-check-label" for="visibilidades_{{ $index }}"><small>Visível para o autor? (selecione se sim)</small></label>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="mr-2" for="tipos_{{$index}}">Tipo</label>
                                                <select class="form-select @error("tipos.$index") is-invalid @enderror" name="tipos[]" id="tipos_{{$index}}">
                                                    <option value="paragrafo" @selected($tipo === 'paragrafo')>Parágrafo</option>
                                                    <option value="radio" @selected($tipo === 'radio')>Múltipla escolha</option>
                                                </select>
                                                @error("tipos.$index")
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-4 d-flex justify-content-start pt-3">
                                                <button type="button" class="btn btn-link" onclick="removerPergunta({{$index}})">
                                                    <i class="bi bi-trash fs-4 text-danger"></i>
                                                </button>
                                                <button type="button" class="btn btn-link" onclick="subirPergunta({{$index}})">
                                                    <i class="bi bi-arrow-up-circle text-success fs-4"></i>
                                                </button>
                                                <button type="button" class="btn btn-link" onclick="descerPergunta({{$index}})">
                                                    <i class="bi bi-arrow-down-circle text-success fs-4"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <label>Resposta</label>
                                        <div class="row" id="row{{$index}}">
                                            @if ($opcoes->isNotEmpty())
                                                <div class="col-sm-12 opcoes itemRadio">
                                                    @foreach ($opcoes->sortBy("id") as $indice => $opcao)
                                                        <div class="opcao col-sm-12">
                                                            <div class="row">
                                                                <div class="col-md-10">
                                                                    <div class="input-group align-items-center">
                                                                        <span class="input-group-text">
                                                                            <input class="form-check-input" name="checkbox[{{ $opcao->id }}]" value="on" type="checkbox" @checked($opcao->check)>
                                                                        </span>
                                                                        <input id="opcao_{{ $index }}_{{ $indice }}" type="text" name="opcoes[{{$index}}][]" value="{{old("opcoes.$index.$indice", $opcao->titulo)}}" class="form-control" required>
                                                                        <input type="hidden" name="opcao_id[{{ $index }}][]" value="{{ $opcao->id }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2 d-flex justify-content-start">
                                                                    <button type="button" class="btn btn-link" onclick="adicionarOpcao({{ $index }}, {{ $indice }})"><i class="bi bi-plus-circle text-success fa-lg"></i></button>
                                                                    <button type="button" class="btn btn-link" onclick="removerOpcao({{ $index }}, {{ $indice }})"><i class="bi bi-dash-circle text-danger fa-lg"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @elseif ($resposta?->paragrafo)
                                                <div class="col-md-12">
                                                    <input type="text" style="margin-bottom:10px" class="form-control " name="resposta[]">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="d-flex flex-column justify-content-center p-5 gap-3">
                            <button type="button" class="btn btn-primary" id="adicionar_pergunta">
                                Adicionar pergunta
                            </button>
                            <button type="submit" class="btn btn-success">
                                {{ __('Salvar') }}
                            </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    @parent
    <script>
$(document).ready(function() {
    CKEDITOR.replaceAll('ckeditorinput');
    $('#adicionar_pergunta').on('click', adicionarPergunta);

        function adicionarPergunta() {
            let index = $('#listagem_perguntas .item.card').length + 1;
            let card =
            $(`<div class="item card w-100 mt-4" style="order:${index}">
                    <div class="card-header">
                        <div class="form-group">
                            <label for="perguntas_${index}">Pergunta</label>
                            <textarea name="perguntas[]" id="perguntas_${index}" class="form-control ckeditorinput" required></textarea>
                            <input type="hidden" name="pergunta_id[]" value="{{$pergunta->id}}">

                        </div>
                        <div class="container row">
                            <div class="col-md-4 form-check">
                                <input class="form-check-input" type="checkbox" id="visibilidades_${index}" name="visibilidades[${index}]" value="on" @checked(old("visibilidades.$index", $pergunta->visibilidade))>
                                <label class="form-check-label" for="visibilidades_${index}"><small>Visível para o autor? (selecione se sim)</small></label>
                            </div>
                            <div class="col-md-4">
                                <label class="mr-2" for="tipos_${index}">Tipo</label>
                                <select class="form-select name="tipos[]" id="tipos_${index}">
                                    <option value="paragrafo">Parágrafo</option>
                                    <option value="radio" selected>Múltipla escolha</option>
                                </select>

                            </div>
                            <div class="col-md-4 d-flex justify-content-start pt-3">
                                <button type="button" class="btn btn-link" onclick="removerPergunta(${index})">
                                    <i class="bi bi-trash fs-4 text-danger"></i>
                                </button>
                                <button type="button" class="btn btn-link" onclick="subirPergunta(${index})">
                                    <i class="bi bi-arrow-up-circle text-success fs-4"></i>
                                </button>
                                <button type="button" class="btn btn-link" onclick="descerPergunta(${index})">
                                    <i class="bi bi-arrow-down-circle text-success fs-4"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <label>Resposta</label>
                        <div class="row" id="row${index}">
                            <div class="col-sm-12 opcoes itemRadio">
                                <div class="opcao col-sm-12">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <div class="input-group align-items-center">
                                                <span class="input-group-text">
                                                    <input class="form-check-input" name="checkbox[{{ $opcao->id }}]" value="on" type="checkbox" @checked($opcao->check)>
                                                </span>
                                                <input id="opcao_{{ $index }}_{{ $indice }}" type="text" name="opcoes[{{$index}}][]" class="form-control" required>
                                                <input type="hidden" name="opcao_id[{{ $index }}][]" value="{{ $opcao->id }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2 d-flex justify-content-start">
                                            <button type="button" class="btn btn-link" onclick="adicionarOpcao({{ $index }}, {{ $indice }})"><i class="bi bi-plus-circle text-success fa-lg"></i></button>
                                            <button type="button" class="btn btn-link" onclick="removerOpcao({{ $index }}, {{ $indice }})"><i class="bi bi-dash-circle text-danger fa-lg"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`);


            $('#listagem_perguntas').append(card)
            if (window.CKEDITOR) CKEDITOR.replace(`perguntas_${index}`);
        }


    function escolha(select) {
        if ('paragrafo' == select) {
            console.log('paragrafo')
            console.log(event)
            event.path[3].children[1].children[1].innerHTML = addParagrafo();

        } else if ('checkbox' == select) {
            console.log('checkbox')
            console.log(event.path[3].children[1].children[1].id)
            let id = event.path[3].children[1].children[1].id;

            event.path[3].children[1].children[1].innerHTML = montarOpcao(id);

        } else if ('radio' == select) {

        }
    }



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



    function adicionarOpcao(check) {
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
                        <a href="#"  onclick="addCheckbox(event)"><i class="fas fa-plus"></i></a>
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
                         <a type="button" class="removeRow" ><i class="fas fa-trash-alt"></i></a>
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


    </script>
@endsection
