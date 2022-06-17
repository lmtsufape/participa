@extends('coordenador.detalhesEvento')

@section('menu')
    @error('excluirFormulario')
        @include('componentes.mensagens')
    @enderror

    <div id="divListarCriterio" class="comissao">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="titulo-detalhes">Formulário(s) da modalidade: <strong> {{$modalidade->nome}}</strong> </h3>
            </div>
        </div>
    </div>
    {{-- {{dd($modalidade->forms)}} --}}
    @foreach ($modalidade->forms as $form)
        <div class="card" style="width: 48rem;">
            <div class="card-body">
            <h5 class="card-title">{{$form->titulo}}</h5>

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
                                <a href="#" data-toggle="modal" data-target="#modalEditarForm{{$form->id}}"><img src="{{asset('img/icons/edit-regular.svg')}}" style="width:20px"></a>
                            </td>
                            <td style="text-align:center">
                                <a href="" data-toggle="modal" data-target="#modalExcluirForm{{$form->id}}"><img src="{{asset('img/icons/trash-alt-regular.svg')}}" class="icon-card" alt=""></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
              </p>

            <p class="card-text">

                @foreach ($form->perguntas as $pergunta)
                    <div class="card">
                        <div class="card-body">
                            <p>Pergunta: {{$pergunta->pergunta}}</p>
                            @if($pergunta->respostas->first()->opcoes->count())
                                <p>Resposta com Multipla escolha:</p>
                                @foreach ($pergunta->respostas->first()->opcoes as $opcao)
                                    <div class="col-md-10 itemRadio">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <input type="checkbox" disabled>
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

{{-- <div class="row">
    <div class="col-md-12">
        <div id="coautores" class="flexContainer " >
            <div class="item card" style="order:1">
                <div class="row card-body">
                    <div class="col-sm-12">
                        <label>Pergunta</label>
                        <input type="text" syle="margin-bottom:10px"   class="form-control " name="pergunta[]" value="{{$pergunta}}" required>
                    </div>
                    <div class="col-sm-8" >
                        <label>Resposta</label>
                        <div class="row" id="row1">
                            <div class="col-md-12">
                                <input type="text" style="margin-bottom:10px"  class="form-control " name="resposta[]" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">Tipo</label>
                            <select onchange="escolha(this.value)" name="tipo[]" class="form-control" id="FormControlSelect">
                                <option value="paragrafo">Parágrafo</option>
                                <option value="checkbox">Multipla escolha</option>

                            </select>
                        </div>
                    </div>
                    <div class="col-md-5"></div>
                    <div class="col-sm-7">
                        <a href="#" class="delete pr-2 mr-2">
                            <i class="fas fa-trash-alt fa-2x"></i>
                        </a>
                        <a href="#" onclick="myFunction(event)">
                        <i class="fas fa-arrow-up fa-2x" id="arrow-up" style=""></i>
                        </a>
                        <a href="#" onclick="myFunction(event)">
                        <i class="fas fa-arrow-down fa-2x" id="arrow-down" style="margin-top:35px"></i>
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}


@foreach ($modalidade->forms as $form)
    <!-- Modal editar modalidade -->
    <div class="modal fade" id="modalEditarForm{{$form->id}}" tabindex="-1" role="dialog" aria-labelledby="modalEditarForm" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #114048ff; color: white;">
                <h5 class="modal-title" id="exampleModalLongTitle">Editar {{$form->titulo}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div>
                                        @error('marcarextensao')
                                            @include('componentes.mensagens')
                                        @enderror
                                    </div>
                                    <form method="get" action="{{route('coord.update.form')}}" enctype="multipart/form-data">
                                        @csrf
                                        <p class="card-text">
                                            <input type="hidden" name="formEditId" value="{{$form->id}}">
                                            {{-- <input type="hidden" name="eventoId" value="{{$evento->id}}">--}}
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <label for="tituloFormEdit" class="col-form-label">*{{ __('Título do Formulário') }}</label>
                                                </div>
                                            </div>
                                            <div class="row justify-content-center">
                                                <div class="col-sm-12">
                                                    {{-- {{dd($form->perguntas)}} --}}
                                                    <input id="tituloFormEdit" type="text" class="form-control @error('titulo'.$form->id) is-invalid @enderror" name="titulo{{$form->id}}" value="@if(old('titulo'.$form->id)!=null){{old('titulo'.$form->id)}}@else{{$form->titulo}}@endif" required autocomplete="titulos" autofocus>
                                                    @error('titulo'.$form->id)
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>

                                                @foreach ($form->perguntas as $index => $pergunta)
                                                <div class="col-md-12">
                                                    <div id="coautores" class="flexContainer">
                                                        <div class="item card" style="order:{{$index}}">
                                                            <div class="row card-body">
                                                                <div class="col-sm-12">
                                                                    <label>Pergunta</label>
                                                                    <input type="text" syle="margin-bottom:10px" value="{{old('pergunta['.$index.']', $pergunta->pergunta)}}"  class="form-control " name="pergunta[]" required>
                                                                    <input type="hidden" name="pergunta_id[]" value="{{$pergunta->id}}">
                                                                </div>
                                                                <div class="col-sm-12" >
                                                                    <label>Resposta</label>
                                                                    <div class="row" id="row{{$index}}">
                                                                        @if ($pergunta->respostas->first()->opcoes->count())
                                                                        <div class="col-sm-12 opcoes itemRadio">
                                                                            @foreach ($pergunta->respostas->first()->opcoes as $indice => $opcao)
                                                                            <div class="opcao col-sm-12">
                                                                                <div class="row">
                                                                                    <div class="input-group pl-0 col-sm-10 mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <div class="input-group-text">
                                                                                                <input name="checkbox" type="checkbox" aria-label="Checkbox for following text input">
                                                                                            </div>
                                                                                        </div>
                                                                                        <input id="inrow{{$index}}" type="text" name="tituloRadio[row{{$index}}][]" value="{{old('tituloRadio[row'.$index.']['.$indice.']', $opcao->titulo)}}" class="form-control">
                                                                                    </div>
                                                                                    <div class="col-sm-1 mt-2">
                                                                                        <a href="#" onclick="addRadioToResposta(event)"><i class="fas fa-plus"></i></a>
                                                                                    </div>
                                                                                    <div class="col-sm-1 mt-2">
                                                                                        <a href="#" class="radioDelete" onclick="removerOpcao(event)"><i class="fas fa-trash-alt"></i></a>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @endforeach
                                                                        </div>
                                                                        @elseif ($pergunta->respostas->first()->paragrafo)
                                                                            <div class="col-md-12">
                                                                                <input type="text" style="margin-bottom:10px" disabled='true' class="form-control " name="resposta[]">
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <div class="col-form-label text-md-left">
                                                                        <small>Visível para o autor? (selecione se sim) </small><input type="checkbox" name="checkboxVisibilidade_{{$pergunta->id}}" @if($pergunta->visibilidade) checked @endif>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label for="exampleFormControlSelect1">Tipo</label>
                                                                        <select onchange="escolha(this.value, event)" name="tipo[]" class="form-control" id="FormControlSelect">
                                                                            <option @if($pergunta->respostas->first()->opcoes->count()) selected @endif value="radio">Multipla escolha</option>
                                                                            <option @if($pergunta->respostas->first()->paragrafo) selected @endif value="paragrafo">Parágrafo</option>
                                                                            {{-- <option value="radio">Seleção</option> --}}
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-5"></div>
                                                                <div class="col-sm-7">
                                                                    <a href="#" class="delete pr-2 mr-2">
                                                                        <i class="fas fa-trash-alt fa-2x"></i>
                                                                    </a>
                                                                    <a href="#" onclick="moverElementoAF(event)">
                                                                        <i class="fas fa-arrow-up fa-2x" id="arrow-up" style=""></i>
                                                                    </a>
                                                                    <a href="#" onclick="moverElementoAF(event)">
                                                                        <i class="fas fa-arrow-down fa-2x" id="arrow-down" style="margin-top:35px"></i>
                                                                    </a>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>{{-- end row--}}

                                                <div class="col-md-12">
                                                    <div id="coautores" class="flexContainer " >
                                                    </div>
                                                    <a href="#" onclick="addLinha(event)" class="btn btn-primary" id="addCoautor" style="width:100%;margin-top:10px">Adicionar pergunta</a>
                                                </div>
                                        </p>

                                        <br>
                                        <div class="row justify-content-center">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-success" style="width:100%;margin-top:10px">
                                                    {{ __('Salvar') }}
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>{{-- end row card --}}
                </div>
            </div>
        </div>
    </div>

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
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script type="text/javascript">

            let order = 1;
            let pergunta = 1;

        function escolha(select){
            if('paragrafo' == select){
                console.log('paragrafo')
                console.log(event)
                event.path[3].children[1].children[1].innerHTML = addParagrafo();

            }else if('checkbox' == select){
                console.log('checkbox')
                console.log(event.path[3].children[1].children[1].id)
                let id = event.path[3].children[1].children[1].id;

                event.path[3].children[1].children[1].innerHTML = montarOpcao(id);

            }else if('radio' == select){

            }
        }



        function myFunction(event) {
            event.preventDefault();
            el = event.srcElement.id
            // console.log( event.path['5'].childNodes)
            arr = event.path['5'].childNodes;

            if(el == "arrow-up"){
            number = event.path['4'].style.order;
            if(number == 1) return

            for (var i = 0; i < arr.length; i++) {
                if(event.path['5'].childNodes[i].style['order'] == parseInt(event.path['4'].style.order, 10) - 1 ){
                event.path['5'].childNodes[i].style['order'] = parseInt(event.path['5'].childNodes[i].style['order'], 10) + parseInt(1, 10);

                event.path['4'].style.order =  parseInt(event.path['4'].style.order, 10) - parseInt(1, 10);

                break;
                }
            }



            }else if(el == "arrow-down"){
            number = event.path['4'].style.order;
            if(number == order) return

            for (var i = 0; i < arr.length; i++) {
                if(event.path['5'].childNodes[i].style['order'] == parseInt(event.path['4'].style.order, 10) + 1 ){
                event.path['5'].childNodes[i].style['order'] = parseInt(event.path['5'].childNodes[i].style['order'], 10) - parseInt(1, 10);

                event.path['4'].style.order =  parseInt(event.path['4'].style.order, 10) + parseInt(1, 10);

                break;
                }
            }

            }
        }


        function addLinha(event){
            event.preventDefault();
            order += 2;
            linha = montarLinhaInput(order);
            $('#coautores').append(linha);
        }
        $(document).ready(function(){

        });
        function addCheckbox(event){
            event.preventDefault();
            console.log(event.path[3].childNodes[3].parentElement.id);
            let id = event.path[3].childNodes[3].parentElement.id;

            let div = document.createElement('div');
            console.log(div)
            div.classList.add("col-md-10");

            div.innerHTML = addCheckboxInput(id);
            let find = document.querySelector("#"+id);
            find.appendChild(div);
        }

        $(document).on('click','.radioDelete',function(){
            $(this).closest('.itemRadio').remove();
                return false;
        });

        // Remover Coautor
        $(document).on('click','.delete',function(){
            $(this).closest('.item').slideUp("normal", function (){
                $(this).remove();
            });

            //$(this).closest('.item').remove();
                return false;
        });

        function montarLinhaInput(order){

        return `<div class="item card" style="order:${order}">
                    <div class="row card-body">
                        <div class="col-sm-12">
                            <label>Pergunta</label>
                            <input type="text" syle="margin-bottom:10px"  class="form-control " name="pergunta[]" required>
                        </div>
                        <div class="col-sm-8" >
                            <label>Resposta</label>
                            <div class="row" id="row${order}">
                                <div class="col-md-12">
                                    <input type="text" style="margin-bottom:10px" disabled='true' class="form-control " name="resposta[]">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Tipo</label>
                                <select onchange="escolha(this.value)" name="tipo[]" class="form-control" id="FormControlSelect">
                                    <option value="paragrafo">Parágrafo</option>
                                    <option value="checkbox">Multipla escolha</option>
                                    {{-- <option value="radio">Seleção</option> --}}
                                </select>
                              </div>
                        </div>
                        <div class="col-md-5"></div>
                        <div class="col-sm-7">
                            <a href="#" class="delete pr-2 mr-2">
                                <i class="fas fa-trash-alt fa-2x"></i>
                            </a>
                            <a href="#" onclick="myFunction(event)">
                            <i class="fas fa-arrow-up fa-2x" id="arrow-up" style=""></i>
                            </a>
                            <a href="#" onclick="myFunction(event)">
                            <i class="fas fa-arrow-down fa-2x" id="arrow-down" style="margin-top:35px"></i>
                            </a>

                        </div>
                    </div>
                </div>`;
        }

        function montarOpcao(check){

            return `<div  class="col-md-10 itemRadio">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                <input name="checkbox" type="checkbox" aria-label="Checkbox for following text input">
                                </div>
                            </div>
                            <input type="text" name="tituloCheckox[${check}][]" class="form-control" aria-label="Text input with checkbox">
                        </div>
                    </div>
                    <div class="col-md-1 mt-2">
                        <a href="#"  onclick="addCheckbox(event)"><i class="fas fa-plus"></i></a>
                    </div>
                    <div class="col-md-1 mt-2">
                        <a href="#" class="radioDelete" onclick="myFunction(event)"><i class="fas fa-trash-alt"></i></a>
                    </div>`;
        }

        function addCheckboxInput(check){
            return `<div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                            <input name="checkbox" type="checkbox" aria-label="Checkbox for following text input">
                            </div>
                        </div>
                        <input type="text" name="tituloCheckox[${check}][]" class="form-control" aria-label="Text input with checkbox">
                    </div>`;
        }

        function addParagrafo(){
            return `<div class="col-md-12">
                        <input type="text" style="margin-bottom:10px" disabled='true' class="form-control" name="resposta[]" required>
                    </div>`;
        }
    </script>
@endsection
