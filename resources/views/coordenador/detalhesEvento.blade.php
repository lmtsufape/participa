@extends('layouts.app')
@section('sidebar')
<div class="wrapper">
    <div class="sidebar">
        <h2>{{{$evento->nome}}}</h2>
        <ul>
            <a id="informacoes" onclick="habilitarPagina('informacoes')">
                <li>
                    <img src="{{asset('img/icons/info-circle-solid.svg')}}" alt=""> <h5> Informações</h5>
                </li>
            </a>
            <a id="trabalhos" onclick="habilitarPagina('trabalhos')">
                <li>
                    <img src="{{asset('img/icons/file-alt-regular.svg')}}" alt=""><h5>Trabalhos</h5>
                </li>
            </a>
            <a id="areas" onclick="habilitarPagina('areas');">
                <li>
                    <img src="{{asset('img/icons/file-alt-regular.svg')}}" alt=""> <h5> Áreas Tématicas</h5>
                </li>
            </a>
            <a id="revisores"onclick="habilitarPagina('revisores')">
                <li>
                    <img src="{{asset('img/icons/glasses-solid.svg')}}" alt=""><h5>Revisores</h5>
                </li>
            </a>
            <a id="comissao" onclick="habilitarPagina('comissao')">
                <li>
                    <img src="{{asset('img/icons/user-tie-solid.svg')}}" alt=""><h5>Comissão</h5>
                </li>
            </a>
            <a id="candidatos" onclick="habilitarPagina('modalidades')">
                <li>
                    <img src="{{asset('img/icons/user-solid.svg')}}" alt=""><h5>Modalidades</h5>
                </li>
            </a>
            <a id="colocacao" onclick="habilitarPagina('colocacao')">
                <li>
                    <img src="{{asset('img/icons/trophy-solid.svg')}}" alt=""><h5>Colocação</h5>
                </li>
            </a>
            <a id="atividades" onclick="habilitarPagina('atividades')">
                <li>
                    <img src="{{asset('img/icons/calendar-alt-solid.svg')}}" alt=""><h5>Atividades</h5>
                </li>
            </a>
        </ul>
    </div>


</div>
@endsection
@section('content')

<div class="main_content">
    {{-- {{ $evento->id ?? '' }} --}}
    {{-- Informações --}}
    <div id="divInformacoes" class="informacoes">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="titulo-detalhes">Informações</h1>
            </div>
        </div>
    </div>
    {{-- Comissão --}}
    <div id="divComissao" class="comissao">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="titulo-detalhes">Comissão</h1>
            </div>            
        </div>

        <div class="row justify-content-center">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">Novo Membro</h5>
                      <h6 class="card-subtitle mb-2 text-muted">Cadastre um membro para sua Comissão</h6>
                      <form action="{{route('cadastrar.comissao')}}" method="POST">
                        @csrf
                        <input type="hidden" name="eventoId" value="{{ $evento->id ?? '' }}">
                        <p class="card-text">
                            <div class="row justify-content-center">
                                <div class="col-sm-6">
                                    <label for="emailMembroComissao" class="control-label">E-mail do novo membro</label>
                                    <input type="email" name="emailMembroComissao" class="form-control @error('emailMembroComissao') is-invalid @enderror" name="emailMembroComissao" value="{{ old('emailMembroComissao') }}" id="emailMembroComissao" placeholder="E-mail">
                                    @error('emailMembroComissao')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label for="especProfissional" class="control-label">Experiência Profissional</label>
                                    <input type="text" name="especProfissional" class="form-control @error('especProfissional') is-invalid @enderror" name="especProfissional" value="{{ old('especProfissional') }}" id="especProfissional" placeholder="Esperiência Profissional">
                                    @error('especProfissional')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>                            
                        </div>
                        </p>
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" style="width:100%">
                                    {{ __('Finalizar') }}
                                </button>
                            </div>
                        </div>
                        
                        </form>
                    </div>
                  </div>{{-- end card--}}
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Coordenador da Comissão</h5>
                        <h6 class="card-subtitle mb-2 text-muted">Selecione um coordenador para comissão do seu evento</h6>
                        <form id="formCoordComissao" action="{{route('cadastrar.coordComissao')}}" method="POST">
                            @csrf
                            <p class="card-text">
                                    <input type="hidden" name="eventoId" value="{{ $evento->id ?? '' }}">
                    
                                    <div class="form-group">
                                        <label for="coodComissaoId">Coordenador Comissão</label>
                                        <select class="form-control" name="coordComissaoId" id="coodComissaoId">
                                            @foreach ($users as $user)
                                                @if($evento->coordComissaoId == $user->id)
                                                    <option value="{{$user->id}}" selected>{{$user->name}}</option>
                                                @else
                                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </p>
                                <div class="row justify-content-center">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary" style="width:100%">
                                            {{ __('Definir Coordenador') }}
                                        </button>
                                        
                                    </div>
                                </div>
                            </form>
                      
                    </div>
                  </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">Coordenadores</h5>
                      <h6 class="card-subtitle mb-2 text-muted">Coordenadores do seu evento cadastrados</h6>
                      <p class="card-text">
                        <table class="table table-hover table-responsive-lg table-sm">
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
                      </p>
                    </div>
                  </div>
            </div>
        </div>
        
        <div class="row">
            
        </div>

        {{-- tabela membros comissão --}}
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-responsive-lg table-hover">
                    
                </table>
            </div>
        </div>
    </div>{{-- End Comissão --}}
    <!-- Trabalhos -->
    <div id="divTrabalhos" class="container" style="display: none">
        
        <div class="row">
            <div class="col-sm-12">
                <h1 class="titulo-detalhes">Trabalhos</h1>
            </div>            
        </div>

        <div class="row subtitulo-detalhes">
            <div class="col-sm-12">
                <p>Cadastrar Novo</p>
            </div>
        </div>
        <form method="POST" action="{{route('trabalho.store')}}">
          @csrf
          <input type="hidden" name="eventoId" value="{{$evento->id}}">
          <div class="row justify-content-center">
              {{-- Nome Trabalho  --}}
            <div class="col-sm-4">
                  <label for="nomeTrabalho" class="col-form-label">{{ __('Nome do Trabalho') }}</label>
                  <input id="nomeTrabalho" type="text" class="form-control @error('nomeTrabalho') is-invalid @enderror" name="nomeTrabalho" value="{{ old('nomeTrabalho') }}" required autocomplete="nomeTrabalho" autofocus>
      
                  @error('nomeTrabalho')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                  @enderror
              </div>
              <div class="col-sm-4">
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
              <div class="col-sm-4">
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

    {{-- Tabela Trabalhos --}}
    <div class="row">
        <div class="col-sm-12">
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
      
    </div>

    
  {{-- Modalidade --}}
    
    
  </div><!-- End Trabalhos -->
    <div id="divModalidades" class="modalidades">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="titulo-detalhes">Modalidades</h1>
            </div>            
        </div>
        {{-- row card --}}
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">Nova Modalidade</h5>
                      <h6 class="card-subtitle mb-2 text-muted">Cadastre uma nova modalidade para o seu evento</h6>
                      <form method="POST" action="{{route('modalidade.store')}}">
                        @csrf
                        <p class="card-text">
                            <input type="hidden" name="eventoId" value="{{$evento->id}}">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label for="nomeModalidade" class="col-form-label">{{ __('Nome') }}</label>

                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-sm-12">
                                    <input id="nomeModalidade" type="text" class="form-control @error('nomeModalidade') is-invalid @enderror" name="nomeModalidade" value="{{ old('nomeModalidade') }}" required autocomplete="nomeModalidade" autofocus>
                        
                                    @error('nomeModalidade')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                
                            </div>{{-- end row--}}
                                                            
                        </p>
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" style="width:100%">
                                    {{ __('Finalizar') }}
                                </button>
                            </div>
                        </div>
                        </form>
                    </div>
                  </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">Áreas por Modalidade</h5>
                      <h6 class="card-subtitle mb-2 text-muted">Vincule as Áreas de acordo com cada modalidade</h6>
                      <form method="POST" action="{{route('areaModalidade.store')}}">
                        @csrf
                        <p class="card-text">
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
                        </p>
                        
                        <div class="row justify-content-center">
                    
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" style="width:100%">
                                    {{ __('Finalizar') }}
                                </button>
                            </div>
                        </div>
                      </form>
                      
                    </div>
                  </div>{{-- End card--}}
            </div>
        </div>{{-- end row card --}}
        

        <div class="row justify-content-center">
            {{-- table modalidades --}}
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">Modalidades</h5>
                      <h6 class="card-subtitle mb-2 text-muted">Modalidades cadastradas no seu evento</h6>
                      <p class="card-text">
                        <table class="table table-hover table-responsive-lg table-sm">
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
                      </p>
                    </div>
                  </div>
                
            </div>{{-- end table--}}

            {{-- table modalidades Área--}}
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">Áreas por Modalidade</h5>
                      <h6 class="card-subtitle mb-2 text-muted">Áreas correspondentes à cada modalidade do seu evento</h6>
                      <p class="card-text">
                        <table class="table table-hover table-responsive-lg table-sm">
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
                      </p>
                    </div>
                  </div>
                
            </div>{{-- end table área--}}
        </div>
        
        <div class="row">
          
        </div>
        
    </div>
    <div id="divColocacao" class="colocacao">
        <h1>Colocação</h1>
    </div>
    <div id="divAtividades" class="atividades">
        <h1>Atividades</h1>
    </div>

<!-- Área -->
<div id="divAreas" class="container" style="display: none">
    <div class="row">
        <div class="col-sm-12">
            <h1 class="titulo-detalhes">Áreas</h1>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Nova Área</h5>
                  <h6 class="card-subtitle mb-2 text-muted">Cadastre uma nova área para o seu evento</h6>
                  <form method="POST" action="{{route('area.store')}}">
                      @csrf
                    <p class="card-text">
                            <input type="hidden" name="eventoId" value="{{$evento->id}}">
                            <div class="row justify-content-center">
                                <div class="col-sm-12">
                                    <label for="nome" class="col-form-label">{{ __('Nome da Área') }}</label>
                                    <input id="nome" type="text" class="form-control @error('nome') is-invalid @enderror" name="nome" value="{{ old('nome') }}" required autocomplete="nome" autofocus>
                    
                                    @error('nome')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </p>
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" style="width:100%">
                                    {{ __('Finalizar') }}
                                </button>
                            </div>
                        </div>
                    </form>
                  
                </div>
              </div>{{-- End card--}}
            
        </div>
        
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
    
</div>

<!-- Revisores -->
<div id="divRevisores" class="container" style="display: none">
    
    <div class="row">
        <div class="col-sm-12">
            <h1 class="titulo-detalhes">Revisores</h1>
        </div>
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
</div>

@endsection
@section('javascript')
  <script type="text/javascript" >

    function cadastrarCoodComissao(){
        
            document.getElementById("formCoordComissao").submit();
            console.log('foi')
    }

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
        modalidades = document.getElementById('divModalidades');
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
            modalidades.style.display = "none";
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
            modalidades.style.display = "none";
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
            modalidades.style.display = "none";
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
            modalidades.style.display = "none";
            colocacao.style.display = "none";
            atividades.style.display = "none";
            areas.style.display = "none";
        }
        if(id == 'modalidades'){
            // console.log('modalidades');
            informacoes.style.display = "none";
            trabalhos.style.display = "none";
            revisores.style.display = "none";
            comissao.style.display = "none";
            modalidades.style.display = "block";
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
            modalidades.style.display = "none";
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
            modalidades.style.display = "none";
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
            modalidades.style.display = "none";
            colocacao.style.display = "none";
            atividades.style.display = "none";
            areas.style.display = "block";
        }

    }

  </script>

@endsection
