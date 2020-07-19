@extends('layouts.app')

@section('content')
<div class="container content" style="margin-top:60px">
    <div class="row">
        <div class="col-sm-12">
            <h1 class="titulo-detalhes">Trabalhos</h1>
        </div>
    </div>
    {{-- Tabela Trabalhos --}}
    <div class="row">
        <div class="col-sm-12">
            <table class="table table-hover table-responsive-lg table-sm">
                <thead>
                  <tr>
                    <th scope="col">Código</th>
                    <th scope="col">Evento</th>
                    <th scope="col">Área</th>
                    <th scope="col">Modalidade</th>
                    <th scope="col">Status</th>
                    <th scope="col">Resumo</th>
                    <th scope="col">Baixar</th>
                    <th scope="col">Atribuir Nota</th>
                  </tr>
                </thead>
                @foreach($trabalhos as $trabalho)
                  @foreach ($areas as $area)
                    @foreach ($modalidades as $modalidade)
                      @foreach ($eventos as $evento)
                        @if ($area->id == $trabalho->areaId && $modalidade->id == $trabalho->modalidadeId && $evento->id == $trabalho->eventoId)
                          <tr>
                            <td>{{$trabalho->id}}</td>
                            <td>{{$evento->nome}}</td>
                            <td>{{$area->nome}}</td>
                            <td>{{$modalidade->nome}}</td>
                            @if ($trabalho->avaliado == "sim")
                              <td>Avaliado</td>
                            @else
                              <td>Pendente</td>    
                            @endif
                            <td>
                              <a class="resumoTrabalho" href="#" data-toggle="modal" onclick="resumoModal({{$trabalho->id}})" data-target="#exampleModalLong"><img src="{{asset('img/icons/resumo.png')}}" style="width:20px"></a>
                                <!-- Modal Resumo-->
                                <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                  <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLongTitle">Resumo</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>
                                      <div class="modal-body" id="resumoTrabalhoModal">
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                            </td>
                            <td>
                              @foreach ($arquivos as $arquivo)
                                @if ($arquivo->trabalhoId == $trabalho->id)
                                  <a href="{{route('download', ['file' => $arquivo->nome])}}"><img src="{{asset('img/icons/file-download-solid-black.svg')}}" style="width:20px"></a>
                                @endif
                              @endforeach
                            </td>
                            <td><a href="#"><img src="{{asset('img/icons/check-solid.svg')}}" style="width:20px" data-toggle="modal" data-target="#exampleModal"></a></td>                    
                          </tr>
                        @endif
                      @endforeach
                    @endforeach
                  @endforeach
                @endforeach
                {{-- <tbody>
                  <tr>
                    <td>1</td>
                    <td>Área</td>
                    <td>
                      <a href="#"><img src="{{asset('img/icons/file-download-solid-black.svg')}}" style="width:20px"></a>
                    </td>
                    <td>-</td>
                    
                  </tr>
                </tbody> --}}
              </table>
        </div>

    </div>


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
              $('#resumoTrabalhoModal').val(result.resumo);
          }});
        });
      });

  </script>
@endsection
