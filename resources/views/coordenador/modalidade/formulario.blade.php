@extends('coordenador.detalhesEvento')

@section('menu')

    <div id="divListarCriterio" class="comissao">
        <div class="row">
            <div class="col-sm-12">
                <h1 >Formulários </h1>
                <h4 class="titulo-detalhes" >Evento: {{$evento->nome}}</h4>
            </div>
        </div> 
    </div>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <thead>
                  <tr>
                    <th scope="col">Nome</th>
                    <th scope="col">Opções</th>
                  </tr>
                </thead>
                <tbody>
                    @forelse ($modalidades as $modalidade)
                    <tr>
                        <td>{{$modalidade->nome}}</td>
                        <td>
                            <form action="{{route('coord.atribuir.form')}}" method="get">
                                <input type="hidden" name="evento_id" value="{{$evento->id}}">
                                <input type="hidden" name="modalidade_id" value="{{$modalidade->id}}">
                                <button class="btn btn-success">
                                    add form
                                </button>
                            </form>
                            @if($modalidade->forms->count())
                                <form action="{{route('coord.visualizar.form')}}" method="get">
                                    <input type="hidden" name="evento_id" value="{{$evento->id}}">
                                    <input type="hidden" name="modalidade_id" value="{{$modalidade->id}}">
                                    <button type="submit" class="btn btn-info">
                                        Ver form
                                    </button>
                                
                                </form>
                            @endif
                        </td>
                      </tr> 
                    @empty
                        <p>Sem modalidades</p>
                    @endforelse                  
                </tbody>
              </table>    
            
        </div>
    </div>

   

@endsection
