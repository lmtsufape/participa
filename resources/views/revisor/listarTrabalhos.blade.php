@extends('layouts.app')

@section('content')
<div class="container content" style="margin-top:60px">
    <div class="row">
        <div class="col-sm-12">
          <h1 class="titulo-detalhes">Trabalhos de {{$evento->nome}}</h1>
        </div>
    </div>
    {{-- Tabela Trabalhos --}}
    @foreach ($trabalhosPorArea as $trabalhosDaArea)
      @if ($trabalhosDaArea != null && count($trabalhosDaArea) > 0)
        <div class="row justify-content-center" style="width: 100%;">
          <div class="col-sm-12">
              <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">Trabalhos da área de {{$trabalhosDaArea[0]->area->nome}}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Que tem como modalidade {{$trabalhosDaArea[0]->modalidade->nome}}</h6>
                    <p class="card-text">
                      <div class="col-sm-12">
                      <table class="table table-hover table-responsive-lg table-sm">
                        <thead>
                          <tr>
                            <th scope="col">Código</th>
                            <th scope="col">Título</th>
                            <th scope="col">Status</th>
                            <th scope="col">Resumo</th>
                            <th scope="col">Baixar</th>
                            <th scope="col">Avaliar</th>
                          </tr>
                        </thead>
                        @foreach($trabalhosDaArea as $trabalho)
                            <tr>
                              <td>{{$trabalho->id}}</td>
                              <td>{{$trabalho->titulo}}</td>
                              @if ($trabalho->avaliado == "sim")
                                <td>Avaliado</td>
                              @else
                                <td>Pendente</td>    
                              @endif
                              <td>
                                <a class="resumoTrabalho" href="#" data-toggle="modal" onclick="resumoModal({{$trabalho->id}})" data-target="#exampleModalLong"><img src="{{asset('img/icons/resumo.png')}}" style="width:20px"></a>
                              </td>
                              <td>
                                @if (!(empty($trabalho->arquivo->items)))
                                  <a href="{{route('downloadTrabalho', ['id' => $trabalho->id])}}"><img src="{{asset('img/icons/file-download-solid-black.svg')}}" style="width:20px"></a>
                                @endif
                              </td>
                              <td><a href="#"><img src="{{asset('img/icons/check-solid.svg')}}" style="width:20px" data-toggle="modal" data-target="#exampleModal"></a></td>                    
                            </tr>
                        @endforeach
                      </table>
                    </p>
                  </div>
              </div>
          </div>
        </div>
      @endif
    @endforeach


    <!-- Modal Nota -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Avaliar Trabalho</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form class="" action="index.html" method="post">
              <div class="form-group">
                <div class="row ">
                  <div class="col-sm-12">
                    <label for="nota">Nota do Trabalho</label>
                    <input class="form-control" id="nota" type="number" name="notaTrabalho" value="" step=0.01 autofocus>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary">Atribuir Nota</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Resumo-->
    <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="resumoModalLongTitle"></h5>
          </div>
          <div class="modal-body" name="resumoTrabalhoModal" id="resumoTrabalhoModal">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
          </div>
        </div>
      </div>
    </div>

</div>
<input type="hidden" name="resumoModalAjax" value="1" id="resumoModalAjax">
@endsection

@section('javascript')
  <script type="text/javascript" >
      function resumoModal(x){
        console.log(x);
        document.getElementById('resumoModalAjax').value = x;
      }

      $(function(){
        $('.resumoTrabalho').click(function(e){
          e.preventDefault();
          $.ajaxSetup({
              headers: {
                  // 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                  'Content-Type': 'application/json',
                  'X-Requested-With': 'XMLHttpRequest'
              }
          });

          jQuery.ajax({
            url: "{{ route('trabalhoResumo') }}",
            method: 'get',
            data: {
              trabalhoId: $('#resumoModalAjax').val()
            },
            success: function(result){
              console.log(result.resumo);
              $('#resumoModalLongTitle').html('Resumo de ' + result.titulo);
              $('#resumoTrabalhoModal').html(result.resumo);
          }});
        });
      });

  </script>
@endsection
