@extends('coordenador.detalhesEvento')

@section('menu')
    <!-- Trabalhos -->
    <div id="divListarTrabalhos" style="display: block">

      <div class="row ">
        <div class="col-sm-12">
            <h1 class="">Atribuir Trabalhos</h1>
            <hr>
        </div>
        <div class="col-sm-6">
          @forelse ($areasTrabalhos as $area)
            <div class="card" style="width: 30rem;">
              <div class="card-header">
                {{$area->nome}}
              </div>
              <form action="{{route('atribuir.revisor.lote')}}" method="post" id="form{{$area->id}}">
              @csrf
              <input type="hidden" name="evento_id" value="{{$evento->id}}">
              <ul class="list-group list-group-flush">
                @foreach ($trabalhos as $trabalho)
                  @if($trabalho->areaId == $area->id)
                    <li class="list-group-item">{{$trabalho->titulo}}</li>
                    <input type="hidden" name="trabalho[]" value="{{$trabalho->id}}">
                  @endif
                @endforeach
              </ul>
              <div class="card-footer">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <button @if($revisores->contains($area->id)) disabled="true"  @endif type="submit" id="{{$area->id}}" class="btn btn-primary"
                      >Atribuir</a>

                  </div>
                  <select name="revisor_id" class="custom-select" >
                    @if (!$revisores->contains($area->id))
                        @foreach ($revisores as $revisor)
                            <option value="{{$revisor->id}}" >{{$revisor->user->email}}</option>
                        @endforeach
                    @else
                        <option>Não há Avaliadores</option>
                    @endif
                  </select>
                </div>
                </form>
              </div>
            </div>
          @empty

          @endforelse
        </div>
      </div>

@endsection

@push('javascript')
  <script type="application/javascript">


    $('button').on('click', (e) => {
      e.preventDefault();
      console.log(e.target.id);
      let id = e.target.id;
      let dados = $('#form'+id).serialize();

      console.log(dados);

      $.ajax({
          type: 'POST',
          url: '{{ route("atribuir.revisor.lote") }}',
          data: dados,
          dataType: 'json',
          success: function(res){
            // console.log(res.data.revisor)
            console.log(e)
            e.target.disabled = 'true';
            console.log(e.target.offsetParent.childNodes[2][0].firstChild.data);
            e.target.offsetParent.childNodes[2][0].firstChild.data =  res.data.revisor;
            e.currentTarget.offsetParent.childNodes[2].disabled = 'true';


          },
          error: function(err){
              console.log('err')
              console.log(err)
          }
      });

    })

    // function atribuir(button){
    //   console.log(button)
    // }

    // let atribuir = button => {



    //   let data = {
    //     email: email.value,

    //     _token: '{{csrf_token()}}'
    //   };

    //   $.ajax({
    //       type: 'POST',
    //       url: '{{ route("search.user") }}',
    //       data: data,
    //       dataType: 'json',
    //       success: function(res){
    //         console.log(event)
    //         if(res.user[0] != null){
    //           event.path[2].childNodes[3].childNodes[3].attributes[2].value = res.user[0]['name'];
    //         }

    //       },
    //       error: function(err){
    //           console.log('err')
    //           console.log(err)
    //       }
    //   });

    // });


  </script>
@endpush
