@extends('layouts.app')

@section('content')

<div class="container" style="margin-top: 100px;">

  <div class="container" >
    <div class="row" >
      <div class="col-sm-10">
        <h3>Usuarios</h3> 
      </div>
      <div class="col-sm-2">
        
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
          Adicionar Usuário
        </button>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form action="">
              <div class="modal-body">
                 <!-- <form action="" method="POST">
                  @csrf
                  <input type="hidden" name="evento_id" value="" >
                  <div class="form-group">
                    <label for="exampleInputEmail1">Nome Completo</label>
                    <input type="text" class="form-control" name="nomeAvaliador" id="exampleInputNome1">            
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Email</label>
                    <input type="email" class="form-control" name="emailAvaliador" id="exampleInputEmail1">            
                  </div>
                  <div class="form-group">
                    <label for="exampleFormControlSelect1">Tipo</label>
                    <select class="form-control" name="tipo" id="exampleFormControlSelect1">
                      <option value="avaliador" >Avaliador</option>
                    </select>
                  </div>

                  <div class="mx-auto" >
                    <button type="submit" class="btn btn-success mx-auto">Enviar</button>
                  </div>             
                </form>
                 --> 
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
              </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <hr>
  @if(session('error'))
  <div class="alert alert-danger">{{ session('error') }}</div>
  @endif
  @if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  <table class="table table-bordered">
    <thead>
      <tr>   
        <th scope="col">Nome do Usuário</th>
        <th scope="col">E-mail</th>
        <th scope="col">Função</th>
        <th scope="col">Opção</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($usuarios as $usuario)
        <tr>
          <td>{{ $usuario->name }}</td>
          <td>{{ $usuario->email }}</td>
          <td>
            @if($usuario->coordComissaoCientifica)
              {{ "Cood. Comissão Cientifica " }}
            @endif
            @if($usuario->coordComissaoOrganizadora)
              {{ "Cood. Comissão Organizadora " }}
            @endif
            @if($usuario->revisor)
              {{ "Revisor " }}
            @endif
            @if($usuario->coautor)
              {{ "Coutor " }}
            @endif
            @if($usuario->participante)
              {{ "Participante " }}
            @endif
            @if($usuario->coordEvento)
              {{ "Coordenador de Evento " }}
            @endif

          </td>
          <td>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal{{ $usuario->id }}">
              Permissões
            </button>
            <!--inicio modal --> 
            <div class="modal fade" id="modal{{ $usuario->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">{{ $usuario->name }}</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">

                      <form action="{{ route('cientifica.permissoes') }}" method="POST" id="form{{ $usuario->id }}">
                        @csrf
                        
                        <input type="hidden" name="user_id" value="{{ $usuario->id }}">

                        <div class="custom-control custom-switch">
                          <input type="checkbox" name="coordComissaoCientifica" value="coordComissaoCientifica" class="custom-control-input" id="customSwitch1" @if($usuario->coordComissaoCientifica)
                          checked=""
                        @endif  >
                          <label class="custom-control-label" for="customSwitch1">Coord. da Comissão Cientifica</label>
                        </div><br>

                        <div class="custom-control custom-switch">
                          <input type="checkbox" name="revisor" value="revisor" class="custom-control-input" id="customSwitch3" @if($usuario->revisor)
                          checked=""
                        @endif  >
                          <label class="custom-control-label" for="customSwitch3">Revisor</label>
                        </div><br>

                        <div class="custom-control custom-switch">
                          <input type="checkbox" name="coautor" value="coautor" class="custom-control-input" id="customSwitch4" @if($usuario->coautor)
                          checked=""
                        @endif  >
                          <label class="custom-control-label" for="customSwitch4">Coautor</label>
                        </div><br> 

                        <div class="custom-control custom-switch">
                          <input type="checkbox" name="participante" value="participante" class="custom-control-input" id="customSwitch5" @if($usuario->participante)
                          checked=""
                        @endif  >
                          <label class="custom-control-label" for="customSwitch5">Participante</label>
                        </div><br>  

                        <div class="custom-control custom-switch">
                          <input type="checkbox" name="coordEvento" value="coordEvento" class="custom-control-input" id="customSwitch6" @if($usuario->coordEvento)
                          checked=""
                        @endif  >
                          <label class="custom-control-label" for="customSwitch6">Coordenador de Evento</label>
                        </div><br>                       

                        
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary"   >Save changes</button>
                        </div>
                      </form>
                  </div>
                </div>
              </div>    
            </div> 
            <!--fim modal -->     
              
          </td>
        </tr>
          
      @endforeach
      <!-- Modal -->
              
    </tbody>
  </table>
  
</div>
  <div class="container">
    <div class="row" >
      <div class="col-sm-12 d-flex justify-content-center" >
        {{ $usuarios->links() }}
      </div>
    </div>
  </div>




@endsection

@section('javascript')
<script>
  
  
    // $('.buttonId').on('click', function(event) {
    //   event.preventDefault();
    //   /* Act on the event */
    // });



    // function permissao(id) {
    //       $('#botao1' + id).on('click', (e) => {
    //           e.preventDefault()
              
    //           $.ajaxSetup({
    //             headers: {
    //               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //             }
    //           });
    //           console.log(event)
              
    //           //console.log(#form)

    //           let dados = $('#form' + id).serialize()
    //           console.log(dados)

    //           //ajax
    //           $.ajax({
    //             type: 'post',
    //             url: '{{ route('cientifica.permissoes') }}',
    //             data: dados, //x-www-form-urlencoded
    //             dataType: 'json',
    //             success: dados => { console.log(dados) },
    //             error: erro => { console.log(erro) }
    //           })
    //       })
    // }
    $(document).ready(() => {
    
  })

  
</script>
@endsection


