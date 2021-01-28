@extends('coordenador.detalhesEvento')

@section('menu')

<div id="divListarRevisores" style="display: block">

  <div class="container div-listar-usuarios">
    <div class="row">
        <div class="col-sm-12">
            <h1 class="titulo-detalhes">Lista de Usuários</h1>
        </div>
{{--         <div class="col-sm-2">
          
          <!-- Button trigger modal -->
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
            Adicionar Usuário
          </button>

          <!-- Modal -->
          <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Enviar E-mail</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form action="{{ route('cientifica.novoUsuario') }}" method="POST">
                <div class="modal-body">                 
                    @csrf 
                    <input type="hidden" name="evento_id" value="{{ $evento->id }}">                 
                    <div class="form-group">
                      <label for="exampleInputEmail1">Nome Completo</label>
                      <input type="text" class="form-control" name="nomeUsuario" id="exampleInputNome1">            
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Email</label>
                      <input type="email" class="form-control" name="emailUsuario" id="exampleInputEmail1">            
                    </div>
                    
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                  <button type="submit" class="btn btn-success">Enviar</button>
                </div>
                </form>
              </div>
            </div>
          </div>
        </div> --}}
    </div>
    <div class="container" >
      <div class="row" >
        <div class="col-sm-10">
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
                    <div class="modal-header" style="background-color: #114048ff; color: white;">
                      <h5 class="modal-title" id="exampleModalLabel">{{ $usuario->name }}</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">

                      <form action="{{ route('cientifica.permissoes') }}" method="POST" id="form{{ $usuario->id }}">
                        @csrf
                        
                        <input type="hidden" name="user_id" value="{{ $usuario->id }}">

                        <div class="custom-control custom-switch">
                          <input type="checkbox" name="revisor" value="revisor" class="custom-control-input" id="customSwitch{{ $usuario->id }}" @if($usuario->revisor)
                          checked="" @endif  >
                          <label class="custom-control-label" for="customSwitch{{ $usuario->id }}">Revisor</label>
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
    {{-- <div class="row" >
      <div class="col-sm-12 d-flex justify-content-center" >
        {{ $usuarios->links() }}
      </div>
    </div> --}}
  </div>



</div>
@endsection

