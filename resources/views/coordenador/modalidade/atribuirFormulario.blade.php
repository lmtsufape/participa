@extends('coordenador.detalhesEvento')

@section('menu')

    <div id="divListarCriterio" class="comissao">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="titulo-detalhes">Adicionar formulario na molidade: <br> <strong> {{$modalidade->nome}}</strong> </h3>
            </div>
        </div> 
    </div>
    <form action="{{route('coord.salvar.form')}}" method="get">
        <input type="hidden" name="modalidade_id" value="{{$modalidade->id}}">
        <input type="hidden" name="evento_id"     value="{{$evento->id}}">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <label> <strong> Titulo do Formulário*:</strong></label>
                <input type="text" syle="margin-bottom:10px"   class="form-control " name="tituloForm" required>
                </div>
            </div>            
        </div>
        <div class="col-md-12">                                
            
            <div id="coautores" class="flexContainer " >
                <div class="item card" style="order:1">
                    <div class="row card-body">
                        <div class="col-sm-12">
                            <label>Pergunta</label>
                            <input type="text" syle="margin-bottom:10px"   class="form-control " name="pergunta[]" required>
                        </div>
                        <div class="col-sm-8" >
                            <label>Resposta</label>
                            <div class="row" id="row1">
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
                </div>
            </div>
            <a href="#" onclick="addLinha(event)" class="btn btn-primary" id="addCoautor" style="width:100%;margin-top:10px">Adicionar pergunta</a>
            <button type="submit" class="btn btn-success" style="width:100%;margin-top:10px">
                Salvar 
            </button>
        </div>
        </div>
    </div>
    </form>
   

@endsection
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
            order += 1;
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
            $(this).closest('.item').remove();
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