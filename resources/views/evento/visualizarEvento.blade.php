@extends('layouts.app')

@section('content')


<div class="modal fade" id="modalTrabalho" tabindex="-1" role="dialog" aria-labelledby="modalTrabalho" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Submeter nova versão</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" action="{{route('trabalho.novaVersao')}}" enctype="multipart/form-data">
        @csrf
      <div class="modal-body">

        <div class="row justify-content-center">
          <div class="col-sm-12">
              @if($hasFile)
                <input type="hidden" name="trabalhoId" value="" id="trabalhoNovaVersaoId">
              @endif
              <input type="hidden" name="eventoId" value="{{$evento->id}}">

              {{-- Arquivo  --}}
              <label for="nomeTrabalho" class="col-form-label">{{ __('Arquivo') }}</label>

              <div class="custom-file">
                <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="arquivo">
              </div>
              <small>O arquivo Selecionado deve ser no formato PDF de até 2mb.</small>
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









<div class="container-fluid content">
    <div class="row">
        @if(isset($evento->fotoEvento))
        <div class="banner-evento">
            <img src="{{asset('storage/eventos/'.$evento->id.'/logo.png')}}" alt="">
        </div>
        <img class="front-image-evento" src="{{asset('storage/eventos/'.$evento->id.'/logo.png')}}" alt="">
        @else
        <div class="banner-evento">
            <img src="{{asset('img/colorscheme.png')}}" alt="">
        </div>
        <img class="front-image-evento" src="{{asset('img/colorscheme.png')}}" alt="">
        @endif
    </div>
</div>
<div class="container" style="margin-top:20px">
    @if(!Auth::check())
      <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong> A submissão de um trabalho é possível apenas quando cadastrado no sistema. </strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    <div class="row margin">
        <div class="col-sm-12">
            <h4>{{$etiquetas->etiquetanomeevento}}:</h4>
        </div>
    </div>
    <div class="row margin">
        <div class="col-sm-12">
            <h1>
                {{$evento->nome}}
            </h1>
        </div>
    </div>

    <div class="row margin">
        <div class="col-sm-12">
            <h4>{{$etiquetas->etiquetadescricaoevento}}:</h4>
        </div>
    </div>
    <div class="row margin">
        <div class="col-sm-12">
            <p>{{$evento->descricao}}</p>
        </div>
    </div>

    <div class="row margin">
      <div class="col-sm-12">
          <h4>{{$etiquetas->etiquetatipoevento}}:</h4>
      </div>
    </div>
    <div class="row margin">
        <div class="col-sm-12">
            <p>{{$evento->tipo}}</p>
        </div>
    </div>

    <div class="row margin">
        <div class="col-sm-12 info-evento">
            <h4>{{$etiquetas->etiquetadatas}}:</h4>
            <p>
                <img class="" src="{{asset('img/icons/calendar-evento.svg')}}" alt="">
                {{date('d/m/Y',strtotime($evento->dataInicio))}} - {{date('d/m/Y',strtotime($evento->dataFim))}}
            </p>
        </div>
    </div>

    {{-- @if ($etiquetas->modsubmissao == true) --}}
      <div class="row margin">
        <div class="col-sm-12 info-evento">
            <h4>{{$etiquetas->etiquetasubmissoes}}:</h4>
            @foreach ($modalidades as $modalidade)
              <h6>Modalidade: {{$modalidade->nome}}</h6>
              @if (isset($modalidade->inicioSubmissao))
                <p>
                  <img class="" src="{{asset('img/icons/calendar-evento.svg')}}" alt="">
                  Submissão: {{date('d/m/Y',strtotime($modalidade->inicioSubmissao))}} - {{date('d/m/Y',strtotime($modalidade->fimSubmissao))}}
                </p>
              @endif

              @if (isset($modalidade->inicioRevisao))
              <p>
                <img class="" src="{{asset('img/icons/calendar-evento.svg')}}" alt="">
                Revisão: {{date('d/m/Y',strtotime($modalidade->inicioRevisao))}} - {{date('d/m/Y',strtotime($modalidade->fimRevisao))}}
              </p>
              @endif

              @if (isset($modalidade->inicioResultado))
              <p>
                <img class="" src="{{asset('img/icons/calendar-evento.svg')}}" alt="">
                Resultado: {{date('d/m/Y',strtotime($modalidade->inicioResultado))}}
              </p>
              @endif

              @if($modalidade->inicioSubmissao <= $mytime)
                @if($mytime < $modalidade->fimSubmissao)
                  @if ($modalidade->arquivo == true)
                    @if(isset($modalidade->regra))
                      <div style="margin-top: 20px; margin-bottom: 10px;">
                        <a href="{{route('download.regra', ['file' => $modalidade->regra])}}" target="_new" style="font-size: 20px; color: #114048ff;" >
                          <img class="" src="{{asset('img/icons/file-download-solid.svg')}}" style="width:20px">
                        </a>
                        <label for="nomeTrabalho" class="col-form-label">Regra</label>
                      </div>
                    @endif
                    @if (isset($modalidade->template))
                      <div style="margin-top: 20px; margin-bottom: 10px;">
                        <a href="{{route('download.template', ['file' => $modalidade->template])}}" target="_new" style="font-size: 20px; color: #114048ff;" >
                          <img class="" src="{{asset('img/icons/file-download-solid.svg')}}" style="width:20px">
                        </a>
                        <label for="nomeTrabalho" class="col-form-label">Template</label>
                      </div>  
                    @endif
                  @else
                    @if(isset($modalidade->regra))
                      <div style="margin-top: 20px; margin-bottom: 10px;">
                        <a href="{{route('download.regra', ['file' => $modalidade->regra])}}" target="_new" style="font-size: 20px; color: #114048ff;" >
                          <img class="" src="{{asset('img/icons/file-download-solid.svg')}}" style="width:20px">
                        </a>
                        <label for="nomeTrabalho" class="col-form-label">Regras</label>
                      </div>  
                    @endif
                  @endif
                  <div class="col-md-6 botao-form-left" style="">
                    <a class="btn btn-secondary" href="{{route('trabalho.index',['id'=>$evento->id, 'idModalidade' => $modalidade->id])}}">Submeter Trabalho</a>
                  </div>
                @endif
              @endif
            <br>
            @endforeach
        </div>
      </div>
    {{-- @else
    
    @endif --}}

    <div class="row margin">
        <div class="col-sm-12 info-evento">
            <h4>{{$etiquetas->etiquetaenderecoevento}}:</h4>
            <p>
                <img class="" src="{{asset('img/icons/map-marker-alt-solid.svg')}}" alt="">
                {{$evento->endereco->rua}}, {{$evento->endereco->numero}} - {{$evento->endereco->cidade}} / {{$evento->endereco->uf}}.
            </p>
        </div>
    </div>

    {{-- Modulo de inscrição --}}
    @if ($etiquetas->modinscricao == true)
      <div class="row margin">
        <div class="col-sm-12 info-evento">
            <h4>{{$etiquetas->etiquetamoduloinscricao}}:</h4>
            <p>
                LOCAL DA INSCRIÇÃO!!!
            </p>
        </div>
      </div>
    @endif

    {{-- Modulo Programação --}}
    @if ($etiquetas->modprogramacao == true)
      <div class="row margin">
        <div class="col-sm-12 info-evento">
            <h4>{{$etiquetas->etiquetamoduloprogramacao}}:</h4>
            <p>
                LOCAL DA PROGRAMAÇÃO
            </p>
        </div>
      </div>        
    @endif

    {{-- Modulo Organização --}}
    @if ($etiquetas->modorganizacao == true)
      <div class="row margin">
        <div class="col-sm-12 info-evento">
            <h4>{{$etiquetas->etiquetamoduloorganizacao}}:</h4>
            <p>
                LOCAL DA ORGANIZAÇÃO
            </p>
        </div>
      </div>    
    @endif
    
    {{-- @if($hasFile == true)
      <div class="row margin">
          <div class="col-sm-12">
              <h1>
                  Meus Trabalhos
              </h1>
          </div>
      </div>
      @if($hasTrabalho)
        <div class="row margin">
            <div class="col-sm-12 info-evento">
                <h4>Como Autor</h4>
            </div>
        </div>

        <!-- Tabela de trabalhos -->

        <div class="row justify-content-center">
          <div class="col-sm-12">

            <table class="table table-responsive-lg table-hover">
              <thead>
                <tr>
                  <th>Título</th>
                  <th style="text-align:center">Baixar</th>
                  <th style="text-align:center">Nova Versão</th>
                </tr>
              </thead>
              <tbody>
                @foreach($trabalhos as $trabalho)
                  <tr>
                    <td>{{$trabalho->titulo}}</td>
                    <td style="text-align:center">
                      @php $arquivo = ""; @endphp
                      @foreach($trabalho->arquivo as $key)
                        @php
                          if($key->versaoFinal == true){
                            $arquivo = $key->nome;
                          }
                        @endphp
                      @endforeach
                      <a href="{{route('download', ['file' => $arquivo])}}" target="_new" style="font-size: 20px; color: #114048ff;" >
                          <img class="" src="{{asset('img/icons/file-download-solid.svg')}}" style="width:20px">
                      </a>
                    </td>
                    <td style="text-align:center">
                      @if($evento->inicioSubmissao <= $mytime)
                        @if($mytime < $evento->fimSubmissao)
                          <a href="#" onclick="changeTrabalho({{$trabalho->id}})" data-toggle="modal" data-target="#modalTrabalho" style="color:#114048ff">
                            <img class="" src="{{asset('img/icons/file-upload-solid.svg')}}" style="width:20px">
                          </a>
                        @endif
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      @endif

      @if($hasTrabalhoCoautor)
        <div class="row margin">
            <div class="col-sm-12 info-evento">
                <h4>Como Coautor</h4>
            </div>
        </div>

        <div class="row justify-content-center">
          <div class="col-sm-12">

            <table class="table table-responsive-lg table-hover">
              <thead>
                <tr>
                  <th>Título</th>
                  <th  style="text-align:center">Baixar</th>
                </tr>
              </thead>
              <tbody>
                @foreach($trabalhosCoautor as $trabalho)
                  <tr>
                    <td>{{$trabalho->titulo}}</td>
                    <td style="text-align:center">
                      @php $arquivo = ""; @endphp
                      @foreach($trabalho->arquivo as $key)
                        @php
                          if($key->versaoFinal == true){
                            $arquivo = $key->nome;
                          }
                        @endphp
                      @endforeach
                      <a href="{{route('download', ['file' => $arquivo])}}" target="_new" style="font-size: 20px; color: #114048ff;" >
                          <img class="" src="{{asset('img/icons/file-download-solid.svg')}}" style="width:20px">
                      </a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      @endif
    @endif --}}

    <div class="row justify-content-center" style="margin: 20px 0 20px 0">

        <div class="col-md-6 botao-form-left" style="">
            <a class="btn btn-secondary botao-form" href="{{route('cancelarCadastro')}}" style="width:100%">Voltar</a>
        </div>

        @if($evento->inicioSubmissao <= $mytime)
          @if($mytime < $evento->fimSubmissao)
            <div class="col-md-6 botao-form-right" style="">
              <a class="btn btn-primary botao-form" href="{{route('trabalho.index',['id'=>$evento->id])}}" style="width:100%">Submeter Trabalho</a>
            </div>
          @endif
        @endif

    </div>
</div>


@endsection

@section('javascript')
<script>
  function changeTrabalho(x){
    document.getElementById('trabalhoNovaVersaoId').value = x;
  }
</script>
@endsection
