@extends('layouts.app')
@section('sidebar')
<div class="wrapper">
    <div class="sidebar">
        <h2>{{{$evento->nome}}}</h2>
        <ul>
            <li><a id="informacoes" onclick="habilitarPagina('informacoes')">
                <img src="{{asset('img/icons/info-circle-solid.svg')}}" alt=""> <h5> Informações</h5></a>
            </li>
            <li><a id="trabalhos" onclick="habilitarPagina('trabalhos')">
                <img src="{{asset('img/icons/file-alt-regular.svg')}}" alt=""><h5>Trabalhos</h5></a>
            </li>
            <li><a id="areas" onclick="habilitarPagina('areas');">
                <img src="{{asset('img/icons/file-alt-regular.svg')}}" alt=""> <h5> Áreas Tématicas</h5></a>
            </li>
            <li><a id="revisores"onclick="habilitarPagina('revisores')">
                <img src="{{asset('img/icons/user-tie-solid.svg')}}" alt=""><h5>Revisores</h5></a>
            </li>
            <li><a id="comissao" onclick="habilitarPagina('comissao')">
                <img src="{{asset('img/icons/user-tie-solid.svg')}}" alt=""><h5>Comissão</h5></a>
            </li>
            <li><a id="candidatos" onclick="habilitarPagina('candidatos')">
                <img src="{{asset('img/icons/user-solid.svg')}}" alt=""><h5>Candidatos</h5></a>
            </li>
            <li><a id="colocacao" onclick="habilitarPagina('colocacao')">
                <img src="{{asset('img/icons/trophy-solid.svg')}}" alt=""><h5>Colocação</h5></a>
            </li>
            <li><a id="atividades" onclick="habilitarPagina('atividades')">
                <img src="{{asset('img/icons/calendar-alt-solid.svg')}}" alt=""><h5>Atividades</h5></a>
            </li>
        </ul>
    </div>


</div>
@endsection
@section('content')

<div class="main_content">
    {{-- {{ $evento->id ?? '' }} --}}
    <div id="divInformacoes" class="informacoes">
        <div class="row">
            <div class="col-sm-12">
                <h1>Informações</h1>
            </div>
        </div>
    </div>
    <div id="divComissao" class="comissao">
        <div class="row">
            <div class="col-sm-10">
                <h1>Comissão</h1>
            </div>
            <div class="col-sm-2">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalComissao">
                    Adicionar Membro
                  </button>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="modalComissao" tabindex="-1" role="dialog" aria-labelledby="modalComissao" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Novo Membro da Comissão</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <form action="{{route('cadastrar.comissao')}}" method="POST">
                <div class="modal-body">

                        @csrf

                        {{-- Nome do Categoria --}}
                        <div class="form-group">
                            <input type="hidden" name="eventoId" value="{{ $evento->id ?? '' }}">
                            {{-- Div para validação --}}
                            <label for="emailMembroComissao" class="control-label">E-mail do novo membro</label>
                            <div class="input-group">
                                <input type="email" name="emailMembroComissao" class="form-control @error('emailMembroComissao') is-invalid @enderror" name="emailMembroComissao" value="{{ old('emailMembroComissao') }}" id="emailMembroComissao" placeholder="E-mail">
                                @error('emailMembroComissao')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="cancel" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary">Cadastrar</button>
                        </div>
                    </div>
                </form>
            </div>
            </div>
        </div>

        {{-- tabela membros comissão --}}
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-responsive-lg table-hover">
                    <thead>
                        <th>
                            <th>Nome</th>
                            <th>Especialidade</th>
                            <th>Celular</th>
                            <th>E-mail</th>
                        </th>
                    </thead>
                    @foreach ($users as $user)
                        <tbody>

                            <th>
                                <td>{{$user->name}}</td>
                                <td>{{$user->especProfissional}}</td>
                                <td>{{$user->celular}}</td>
                                <td>{{$user->email}}</td>
                            </th>
                        </tbody>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
    <div id="divCandidatos" class="candidatos">
        <h1>Candidatos</h1>
    </div>
    <div id="divColocacao" class="colocacao">
        <h1>Colocação</h1>
    </div>
    <div id="divAtividades" class="atividades">
        <h1>Atividades</h1>
    </div>
</div>
<!-- Área -->
<div id="divAreas" class="container" style="display: none">
    <div class="row titulo">
        <h1>Áreas Cadastradas</h1>
    </div>
    <div class="row">
      <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Nome</th>
            <th scope="col">Remover</th>
          </tr>
        </thead>
        <tbody>
          @foreach($areas as $area)
            <tr>
              <th scope="row">1</th>
              <td>{{$area->nome}}</td>
              <td>remover</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="row titulo">
        <h1>Cadastrar Nova</h1>
    </div>
    <form method="POST" action="{{route('area.store')}}">
      @csrf
      <input type="hidden" name="eventoId" value="{{$evento->id}}">
      <div class="row justify-content-center">
          <div class="col-sm-6">
              <label for="nome" class="col-form-label">{{ __('Nome da Área') }}</label>
              <input id="nome" type="text" class="form-control @error('nome') is-invalid @enderror" name="nome" value="{{ old('nome') }}" required autocomplete="nome" autofocus>

              @error('nome')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
              @enderror
          </div>
      </div>
      <div class="row justify-content-center">

          <div class="col-md-6">
              <button type="submit" class="btn btn-primary" style="width:100%">
                  {{ __('Finalizar') }}
              </button>
          </div>
      </div>
    </form>
</div>
<!-- Revisores -->
<div id="divRevisores" class="container" style="display: none">
    <div class="row titulo">
        <h1>Revisores</h1>
    </div>
    <div class="row">
      <table class="table">
        <thead>
          <tr>
            <th scope="col">Nome</th>
            <th scope="col">Área</th>
            <th scope="col">Em Andamento</th>
            <th scope="col">Finalizados</th>
            <th scope="col">Ultimo Prazo</th>
          </tr>
        </thead>
        <tbody>
          @foreach($revisores as $revisor)
            <tr>
              <td>{{$revisor->user->nome}}</td>
              <td>{{$revisor->area->nome}}</td>
              <td>{{$revisor->trabalhosCorrigidos}}</td>
              <td>{{$revisor->correcoesEmAndamento}}</td>
              <td>{{$revisor->prazo}}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="row titulo">
        <h1>Cadastrar Novo</h1>
    </div>
    <form method="POST" action="{{route('revisor.store')}}">
      @csrf
      <input type="hidden" name="eventoId" value="{{$evento->id}}">
      <div class="row justify-content-center">
          <div class="col-sm-6">
              <label for="emailRevisor" class="col-form-label">{{ __('Email do Revisor') }}</label>
              <input id="emailRevisor" type="text" class="form-control @error('emailRevisor') is-invalid @enderror" name="emailRevisor" value="{{ old('emailRevisor') }}" required autocomplete="emailRevisor" autofocus>

              @error('emailRevisor')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
              @enderror
          </div>
          <div class="col-sm-6">
              <label for="areaRevisor" class="col-form-label">{{ __('Área') }}</label>
              <select class="form-control @error('areaRevisor') is-invalid @enderror" id="areaRevisor" name="areaRevisor">
                  <option value="" disabled selected hidden>-- Área --</option>
                  @foreach($areas as $area)
                    <option value="{{$area->id}}">{{$area->nome}}</option>
                  @endforeach
              </select>

              @error('areaRevisor')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
              @enderror
          </div>
      </div>
      <div class="row justify-content-center">

          <div class="col-md-6">
              <button type="submit" class="btn btn-primary" style="width:100%">
                  {{ __('Finalizar') }}
              </button>
          </div>
      </div>
    </form>


</div>
<!-- Trabalhos -->
<div id="divTrabalhos" class="container" style="display: none">
  <div class="row titulo">
      <h1>Trabalhos</h1>
  </div>
  <div class="row">
    <table class="table">
      <thead>
        <tr>
          <th scope="col">Titulo</th>
          <th scope="col">Área</th>
          <th scope="col">Modalidade</th>
          <th scope="col">Autor</th>
          <th scope="col">Coautores</th>
          <th scope="col">Atribuido para</th>
        </tr>
      </thead>
      <tbody>
        @foreach($trabalhos as $trabalho)
          <tr>
            <td>{{$trabalho->titulo}}</td>
            <td>{{$trabalho->area->nome}}</td>
            <td>{{$trabalho->modalidade->nome}}</td>
            <td>{{$trabalho->autor->nome}}</td>
            <td>@foreach($trabalho->coautor as $coautor)
                  {{$coautor->user->name}},
                @endforeach
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="row titulo">
      <h1>Cadastrar Novo</h1>
  </div>
  <form method="POST" action="{{route('trabalho.store')}}">
    @csrf
    <input type="hidden" name="eventoId" value="{{$evento->id}}">
    <div class="row justify-content-center">
        <div class="col-sm-6">
            <label for="nomeTrabalho" class="col-form-label">{{ __('Nome') }}</label>
            <input id="nomeTrabalho" type="text" class="form-control @error('nomeTrabalho') is-invalid @enderror" name="nomeTrabalho" value="{{ old('nomeTrabalho') }}" required autocomplete="nomeTrabalho" autofocus>

            @error('nomeTrabalho')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="col-sm-6">
            <label for="emailCoautor" class="col-form-label">{{ __('Email dos coautores(separado por virgula)') }}</label>
            <input id="emailCoautor" type="text" class="form-control @error('emailCoautor') is-invalid @enderror" name="emailCoautor" value="{{ old('emailCoautor') }}" required autocomplete="emailCoautor" autofocus>

            @error('emailCoautor')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror

            @error('emailNaoEncontrado')
            {{$message}}
            @enderror

        </div>
        <div class="col-sm-6">
            <label for="areaModalidadeId" class="col-form-label">{{ __('Área - Modalidade') }}</label>
            <select class="form-control @error('areaModalidadeId') is-invalid @enderror" id="areaModalidadeId" name="areaModalidadeId">
                <option value="" disabled selected hidden>-- Área - Modalidade --</option>
                @foreach($areaModalidades as $areaModalidade)
                  <option value="{{$areaModalidade->id}}">{{$areaModalidade->area->nome}} - {{$areaModalidade->modalidade->nome}}</option>
                @endforeach
            </select>

            @error('areaModalidadeId')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

    </div>
    <div class="row justify-content-center">

        <div class="col-md-6">
            <button type="submit" class="btn btn-primary" style="width:100%">
                {{ __('Finalizar') }}
            </button>
        </div>
    </div>
  </form>

  <div class="row titulo">
      <h1>---------Modalidade---------</h1>
  </div>
  <div class="row">
    <table class="table">
      <thead>
        <tr>
          <th scope="col">Nome</th>
        </tr>
      </thead>
      <tbody>
          @foreach($modalidades as $modalidade)
            <tr>
              <td>{{$modalidade->nome}}</td>
            </tr>
          @endforeach


      </tbody>
    </table>
  </div>
  <div class="row titulo">
      <h1>Cadastrar Novo</h1>
  </div>
  <form method="POST" action="{{route('modalidade.store')}}">
    @csrf
    <input type="hidden" name="eventoId" value="{{$evento->id}}">
    <div class="row justify-content-center">
        <div class="col-sm-6">
            <label for="nomeModalidade" class="col-form-label">{{ __('Nome') }}</label>
            <input id="nomeModalidade" type="text" class="form-control @error('nomeModalidade') is-invalid @enderror" name="nomeModalidade" value="{{ old('nomeModalidade') }}" required autocomplete="nomeModalidade" autofocus>

            @error('nomeModalidade')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <!-- <div class="col-sm-6">
            <label for="areaModalidade" class="col-form-label">{{ __('Área') }}</label>
            <select class="form-control @error('areaModalidade') is-invalid @enderror" id="areaModalidade" name="areaModalidade">
                <option value="" disabled selected hidden> Área </option>
                @foreach($areas as $area)
                  <option value="{{$area->id}}">{{$area->nome}}</option>
                @endforeach
            </select>

            @error('areaModalidade')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div> -->
    </div>
    <div class="row justify-content-center">

        <div class="col-md-6">
            <button type="submit" class="btn btn-primary" style="width:100%">
                {{ __('Finalizar') }}
            </button>
        </div>
    </div>
  </form>

  <div class="row titulo">
      <h1>---------Modalidades no seu evento---------</h1>
  </div>
  <div class="row">
    <table class="table">
      <thead>
        <tr>
          <th scope="col">Modalidade</th>
          <th scope="col">Área</th>
        </tr>
      </thead>
      <tbody>
          @foreach($areaModalidades as $areaModalidade)
            <tr>
              <td>{{$areaModalidade->modalidade->nome}}</td>
              <td>{{$areaModalidade->area->nome}}</td>
            </tr>
          @endforeach


      </tbody>
    </table>
  </div>
  <div class="row titulo">
      <h1>Cadastrar Novo</h1>
  </div>
  <form method="POST" action="{{route('areaModalidade.store')}}">
    @csrf
    <input type="hidden" name="eventoId" value="{{$evento->id}}">
    <div class="row justify-content-center">
      <div class="col-sm-6">
          <label for="modalidadeId" class="col-form-label">{{ __('Modalidade') }}</label>
          <select class="form-control @error('modalidadeId') is-invalid @enderror" id="modalidadeId" name="modalidadeId">
              <option value="" disabled selected hidden> Modalidade </option>
              @foreach($modalidades as $modalidade)
                <option value="{{$modalidade->id}}">{{$modalidade->nome}}</option>
              @endforeach
          </select>

          @error('modalidadeId')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
          @enderror
      </div>
        <div class="col-sm-6">
            <label for="areaId" class="col-form-label">{{ __('Área') }}</label>
            <select class="form-control @error('areaId') is-invalid @enderror" id="areaId" name="areaId">
                <option value="" disabled selected hidden> Área </option>
                @foreach($areas as $area)
                  <option value="{{$area->id}}">{{$area->nome}}</option>
                @endforeach
            </select>

            @error('areaId')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>
    <div class="row justify-content-center">

        <div class="col-md-6">
            <button type="submit" class="btn btn-primary" style="width:100%">
                {{ __('Finalizar') }}
            </button>
        </div>
    </div>
  </form>



</div>
@endsection
@section('javascript')
  <script>
    // var newOptions = {
    //                   "Option 1": "value1",
    //                   "Option 2": "value2",
    //                   "Option 3": "value3"
    //                  };
    // var $el = $("#testeId");
    // // $("#areaRevisorTrabalhos").change(function(){
    // //   alert("The text has been changed.");
    // //   $el.empty(); // remove old options
    // //   $.each(newOptions, function(key,value) {
    // //     $el.append($("<option></option>")
    // //     .attr("value", value).text(key));
    // //   });
    // // });
    // $("#testeId").change(function(){
    //   alert("The text has been changed.");
    // });

    function habilitarPagina(id){
        informacoes = document.getElementById('divInformacoes');
        trabalhos = document.getElementById('divTrabalhos');
        revisores = document.getElementById('divRevisores');
        comissao = document.getElementById('divComissao')
        candidatos = document.getElementById('divCandidatos');
        colocacao = document.getElementById('divColocacao');
        atividades = document.getElementById('divAtividades');
        areas = document.getElementById('divAreas');
        // habilita divInformacoes
        if(id == 'informacoes'){
            // console.log('informacoes');
            informacoes.style.display = "block";
            trabalhos.style.display = "none";
            revisores.style.display = "none";
            comissao.style.display = "none";
            candidatos.style.display = "none";
            colocacao.style.display = "none";
            atividades.style.display = "none";
            areas.style.display = "none";


        }
        if(id == 'trabalhos'){
            // console.log('trabalhos');
            informacoes.style.display = "none";
            trabalhos.style.display = "block";
            revisores.style.display = "none";
            comissao.style.display = "none";
            candidatos.style.display = "none";
            colocacao.style.display = "none";
            atividades.style.display = "none";
            areas.style.display = "none";
        }
        if(id == 'revisores'){
            // console.log('revisores');
            informacoes.style.display = "none";
            trabalhos.style.display = "none";
            revisores.style.display = "block";
            comissao.style.display = "none";
            candidatos.style.display = "none";
            colocacao.style.display = "none";
            atividades.style.display = "none";
            areas.style.display = "none";
        }
        if(id == 'comissao'){
            // console.log('comissao');
            informacoes.style.display = "none";
            trabalhos.style.display = "none";
            revisores.style.display = "none";
            comissao.style.display = "block";
            candidatos.style.display = "none";
            colocacao.style.display = "none";
            atividades.style.display = "none";
            areas.style.display = "none";
        }
        if(id == 'candidatos'){
            // console.log('candidatos');
            informacoes.style.display = "none";
            trabalhos.style.display = "none";
            revisores.style.display = "none";
            comissao.style.display = "none";
            candidatos.style.display = "block";
            colocacao.style.display = "none";
            atividades.style.display = "none";
            areas.style.display = "none";
        }
        if(id == 'colocacao'){
            // console.log('colocacao');
            informacoes.style.display = "none";
            trabalhos.style.display = "none";
            revisores.style.display = "none";
            comissao.style.display = "none";
            candidatos.style.display = "none";
            colocacao.style.display = "block";
            atividades.style.display = "none";
            areas.style.display = "none";
        }
        if(id == 'atividades'){
            // console.log('atividades');
            informacoes.style.display = "none";
            trabalhos.style.display = "none";
            revisores.style.display = "none";
            comissao.style.display = "none";
            candidatos.style.display = "none";
            colocacao.style.display = "none";
            atividades.style.display = "block";
            areas.style.display = "none";
        }
        if(id == 'areas'){
            // console.log('atividades');
            informacoes.style.display = "none";
            trabalhos.style.display = "none";
            revisores.style.display = "none";
            comissao.style.display = "none";
            candidatos.style.display = "none";
            colocacao.style.display = "none";
            atividades.style.display = "none";
            areas.style.display = "block";
        }

    }

  </script>

@endsection
