@extends('layouts.app')

@section('content')



@foreach ($trabalhos as $trabalho)
  <div class="modal fade" id="modalTrabalho_{{$trabalho->id}}" tabindex="-1" role="dialog" aria-labelledby="modalTrabalho" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #114048ff; color: white;">
          <h5 class="modal-title" id="exampleModalCenterTitle">Submeter nova versão</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="POST" action="{{route('trabalho.novaVersao')}}" enctype="multipart/form-data">
          @csrf
        <div class="modal-body">

          <div class="row justify-content-center">
            <div class="col-sm-12">
                @error('error')
                  <div class="alert alert-danger">
                    <p>{{$message}}</p>
                  </div>
                @enderror
                @error('tipoExtensao')
                <div class="alert alert-danger">
                  <p>{{$message}}</p>
                </div>
                @enderror
                <input type="hidden" name="trabalhoId" value="{{$trabalho->id}}" id="trabalhoNovaVersaoId">
                
                {{-- Arquivo  --}}
                <label for="nomeTrabalho" class="col-form-label">{{ __('Novo arquivo para ') }}{{$trabalho->titulo}}</label>

                <div class="custom-file">
                  <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="arquivo">
                </div>

                <small>Arquivos aceitos nos formatos 
                  @if($trabalho->modalidade->pdf == true)<span> - pdf</span>@endif
                  @if($trabalho->modalidade->jpg == true)<span> - jpg</span>@endif
                  @if($trabalho->modalidade->jpeg == true)<span> - jpeg</span>@endif
                  @if($trabalho->modalidade->png == true)<span> - png</span>@endif
                  @if($trabalho->modalidade->docx == true)<span> - docx</span>@endif
                  @if($trabalho->modalidade->odt == true)<span> - odt</span>@endif 
                  @if($trabalho->modalidade->zip == true)<span> - zip</span>@endif
                  @if($trabalho->modalidade->svg == true)<span> - svg</span>@endif.
                </small>
                @error('arquivo')
                <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
          <button type="submit" class="btn btn-primary">Salvar</button>
        </div>
      </form>
      </div>
    </div>
  </div>
@endforeach
  
<div class="container content" style="margin-top: 80px;">
    {{-- titulo da página --}}
    <div class="row justify-content-center titulo-detalhes">
      @if(session('mensagem'))
        <div class="col-md-12" style="margin-top: 5px;">
            <div class="alert alert-success">
              <p>{{session('mensagem')}}</p>
            </div>
        </div>
      @endif
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-12">
                    <h1>Meus Trabalhos</h1>
                </div>
               
            </div>
        </div>
    </div>
    <br>
    <div class="row margin">
        <div class="col-sm-12 info-evento">
            <h4>Como Autor</h4>
        </div>
    </div>

    <!-- Tabela de trabalhos -->

    <div class="row justify-content-center">
        <div class="col-sm-12">

        @if (count($trabalhos) > 0)
          <table class="table table-responsive-lg table-hover">
              <thead>
              <tr>
                  <th>Evento</th>
                  <th>ID</th>
                  <th>Título</th>
                  <th style="text-align:center">Baixar</th>
                  <th style="text-align:center">Nova Versão</th>
                  <th style="text-align:center">Excluir</th>
              </tr>
              </thead>
              <tbody>
              @foreach($trabalhos as $trabalho)
                  <tr>
                  <td>{{$trabalho->evento->nome}}</td>
                  <td>{{$trabalho->id}}</td>
                  <td>{{$trabalho->titulo}}</td>
                  <td style="text-align:center">
                      <a href="{{route('downloadTrabalho', ['id' => $trabalho->id])}}" target="_new" style="font-size: 20px; color: #114048ff;" >
                          <img class="" src="{{asset('img/icons/file-download-solid.svg')}}" style="width:20px">
                      </a>
                  </td>
                  <td style="text-align:center">
                      <a href="#" data-toggle="modal" data-target="#modalTrabalho_{{$trabalho->id}}" style="color:#114048ff">
                        <img class="" src="{{asset('img/icons/file-upload-solid.svg')}}" style="width:20px">
                      </a>
                  </td>
                  <td style="text-align:center">
                    <a href="#" @if($agora <= $trabalho->modalidade->fimSubmissao) data-toggle="modal" data-target="#modalExcluirTrabalho_{{$trabalho->id}}" style="color:#114048ff" @else data-toggle="popover" data-placement="bottom" title="Não permitido" data-content="A exclusão do trabalho só é permitida durante o periodo de" @endif>
                      <img class="" src="{{asset('img/icons/trash-alt-regular.svg')}}" style="width:20px">
                    </a>
                  </td>
                  </tr>
              @endforeach
              </tbody>
          </table>
        @else 
          Você não submeteu nenhum trabalho...
        @endif
        </div>
    </div>
    
    <br>

    <div class="row margin">
        <div class="col-sm-12 info-evento">
            <h4>Como Coautor</h4>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-sm-12">

        @if ($trabalhosCoautor != null && count($trabalhosCoautor) > 0)
          <table class="table table-responsive-lg table-hover">
              <thead>
              <tr>
                  <th>Evento</th>
                  <th>Título</th>
                  <th>Autor</th>
                  <th style="text-align:center">Baixar</th>
              </tr>
              </thead>
              <tbody>
              @foreach($trabalhosCoautor as $trabalho)
                  <tr>
                  <td>{{$trabalho->evento->nome}}</td>
                  <td>{{$trabalho->titulo}}</td>
                  <td>{{$trabalho->autor->name}}</td>
                  <td style="text-align:center">
                      <a href="{{route('downloadTrabalho', ['id' => $trabalho->id])}}" target="_new" style="font-size: 20px; color: #114048ff;" >
                          <img class="" src="{{asset('img/icons/file-download-solid.svg')}}" style="width:20px">
                      </a>
                  </td>                    
                  </tr>
              @endforeach
              </tbody>
          </table>
        @else
          Você não participa como coautor em nenhum trabalho...
        @endif
        </div>
    </div>

</div>


@foreach ($trabalhos as $trabalho)
  <!-- Modal  excluir trabalho -->
  <div class="modal fade" id="modalExcluirTrabalho_{{$trabalho->id}}" tabindex="-1" aria-labelledby="modalExcluirTrabalho_{{$trabalho->id}}Label" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #114048ff; color: white;">
          <h5 class="modal-title" id="exampleModalLabel">Excluir {{$trabalho->titulo}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="formExcluirTrab{{$trabalho->id}}" action="{{route('excluir.trabalho', ['id' => $trabalho->id])}}" method="POST">
            @csrf
            <p>
              Tem certeza que deseja excluir esse trabalho?
            </p>
            <small>Ninguém poderá ver seu trabalho após a exclusão.</small>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
          <button type="submit" class="btn btn-primary" form="formExcluirTrab{{$trabalho->id}}">Sim</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Fim Modal excluir trabalho -->
@endforeach



@endsection

@section('javascript')
@error('trabalhoId')
<script>
  $(document).ready(function() {
    $("#modalTrabalho_{{$message}}").modal('show');
  })
</script>
@enderror
@endsection
