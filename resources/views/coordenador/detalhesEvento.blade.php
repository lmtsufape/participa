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

            <a id="trabalhos">
                <li>
                    <img src="{{asset('img/icons/file-alt-regular.svg')}}" alt=""><h5>Trabalhos</h5><img class="arrow" src="{{asset('img/icons/arrow.svg')}}">
                </li>
                <div id="dropdownTrabalhos" style="background-color: gray">
                    <a id="submissoesTrabalhos" onclick="habilitarPagina('submissoesTrabalhos')">
                        <li>
                            <img src="{{asset('img/icons/plus-square-solid.svg')}}" alt=""><h5>Definir Submissões</h5>
                        </li>
                    </a>
                    <a id="listarTrabalhos" onclick="habilitarPagina('listarTrabalhos')">
                        <li>
                            <img src="{{asset('img/icons/list.svg')}}" alt=""><h5>Listar Trabalhos</h5>
                        </li>
                    </a>
                </div>
            </a>
            <a id="areas">
                <li>
                    <img src="{{asset('img/icons/area.svg')}}" alt=""><h5> Áreas</h5><img class="arrow" src="{{asset('img/icons/arrow.svg')}}">
                </li>
                <div id="dropdownAreas" style="background-color: gray">
                    <a id="cadastrarAreas" onclick="habilitarPagina('cadastrarAreas')">
                        <li>
                            <img src="{{asset('img/icons/plus-square-solid.svg')}}" alt=""><h5> Cadastrar Áreas</h5>
                        </li>
                    </a>
                    <a id="listarAreas" onclick="habilitarPagina('listarAreas')">
                        <li>
                            <img src="{{asset('img/icons/list.svg')}}" alt=""><h5> Listar Áreas</h5>
                        </li>
                    </a>
                </div>

            </a>
            <a id="revisores">
                <li>
                    <img src="{{asset('img/icons/glasses-solid.svg')}}" alt=""><h5>Revisores</h5><img class="arrow" src="{{asset('img/icons/arrow.svg')}}">
                </li>
                <div id="dropdownRevisores" style="background-color: gray">
                    <a id="cadastrarRevisores" onclick="habilitarPagina('cadastrarRevisores')">
                        <li>
                            <img src="{{asset('img/icons/user-plus-solid.svg')}}" alt=""><h5> Cadastrar Revisores</h5>
                        </li>
                    </a>
                    <a id="listarRevisores" onclick="habilitarPagina('listarRevisores')">
                        <li>
                            <img src="{{asset('img/icons/list.svg')}}" alt=""><h5> Listar Revisores</h5>
                        </li>
                    </a>
                </div>
            </a>
            <a id="comissao" onclick="habilitarPagina('comissao')">
                <li>
                    <img src="{{asset('img/icons/user-tie-solid.svg')}}" alt=""><h5>Comissão</h5><img class="arrow" src="{{asset('img/icons/arrow.svg')}}">
                </li>
                <div id="dropdownComissao" style="background-color: gray">
                    <a id="cadastrarComissao" onclick="habilitarPagina('cadastrarComissao')">
                        <li>
                            <img src="{{asset('img/icons/user-plus-solid.svg')}}" alt=""><h5> Cadastrar Comissão</h5>
                        </li>
                    </a>
                    <a id="definirCoordComissao" onclick="habilitarPagina('definirCoordComissao')">
                        <li>
                            <img src="{{asset('img/icons/crown-solid.svg')}}" alt=""><h5> Definir Coordenador</h5>
                        </li>
                    </a>
                    <a id="listarComissao" onclick="habilitarPagina('listarComissao')">
                        <li>
                            <img src="{{asset('img/icons/list.svg')}}" alt=""><h5> Listar Comissão</h5>
                        </li>
                    </a>
                </div>
            </a>
            <a id="modalidades">
                <li>
                    <img src="{{asset('img/icons/sitemap-solid.svg')}}" alt=""><h5>Modalidades</h5><img class="arrow" src="{{asset('img/icons/arrow.svg')}}">
                </li>
                <div id="dropdownModalidades" style="background-color: gray">
                    <a id="cadastrarModalidade" onclick="habilitarPagina('cadastrarModalidade')">
                        <li>
                            <img src="{{asset('img/icons/plus-square-solid.svg')}}" alt=""><h5> Cadastrar Modalidade</h5>
                        </li>
                    </a>
                    <a id="listarModalidade" onclick="habilitarPagina('listarModalidade')">
                        <li>
                            <img src="{{asset('img/icons/list.svg')}}" alt=""><h5> Listar Modalidades</h5>
                        </li>
                    </a>
                </div>
            </a>

            <a id="eventos">
              <li>
                  <img src="{{asset('img/icons/sitemap-solid.svg')}}" alt=""><h5>Evento</h5><img class="arrow" src="{{asset('img/icons/arrow.svg')}}">
              </li>
              <div id="dropdownEvento" style="background-color: gray">
                  <a id="editarEtiqueta" onclick="habilitarPagina('editarEtiqueta')">
                      <li>
                          <img src="{{asset('img/icons/plus-square-solid.svg')}}" alt=""><h5>Etiquetas Eventos</h5>
                      </li>
                  </a>
                  <a id="editarEtiquetaSubTrabalhos" onclick="habilitarPagina('editarEtiquetaSubTrabalhos')">
                    <li>
                        <img src="{{asset('img/icons/plus-square-solid.svg')}}" alt=""><h5>Etiquetas Trabalho</h5>
                    </li>
                  </a>
              </div>
            </a>
            <!-- <a id="colocacao" onclick="habilitarPagina('colocacao')">
                <li>
                    <img src="{{asset('img/icons/trophy-solid.svg')}}" alt=""><h5>Classificação</h5>
                </li>
            </a>
            <a id="atividades" onclick="habilitarPagina('atividades')">
                <li>
                    <img src="{{asset('img/icons/calendar-alt-solid.svg')}}" alt=""><h5>Atividades</h5>
                </li>
            </a> -->
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

        <!-- Row trabalhos -->
        <div class="row justify-content-center">
          <div class="col-sm-8">


            <div class="row justify-content-center">
              <div class="col-sm-12">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">Trabalhos</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Informações referente aos trabalhos enviados</h6>
                    <p class="card-text">
                      <div class="row justify-content-center">
                        <div class="col-sm-12">
                          <table class="table table-responsive-lg table-hover">
                            <thead>
                              <tr>
                                <th style="text-align:center">Enviados</th>
                                <th style="text-align:center">Avaliados</th>
                                <th style="text-align:center">Pendentes</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td style="text-align:center"> {{$trabalhosEnviados}} </td>
                                <td style="text-align:center"> {{$trabalhosAvaliados}} </td>
                                <td style="text-align:center"> {{$trabalhosPendentes}} </td>
                              </tr>
                            </tbody>
                          </table>

                        </div>

                      </div>
                    </p>
                  </div>
                </div>
              </div>
            </div>
            <div class="row justify-content-center">
              <div class="col-sm-12">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">Organização</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Informações referentes ao número de participantes na organização do evento</h6>
                    <p class="card-text">
                      <div class="row justify-content-center">
                        <div class="col-sm-12">
                          <table class="table table-responsive-lg table-hover">
                            <thead>
                              <tr>
                                <th style="text-align:center">Número de Revisores</th>
                                <th style="text-align:center">Número de Integrantes na Comissão</th>

                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td style="text-align:center"> {{$numeroRevisores}} </td>
                                <td style="text-align:center"> {{$numeroComissao}} </td>
                              </tr>
                            </tbody>
                          </table>

                        </div>

                      </div>
                    </p>
                  </div>
                </div>
              </div>

            </div>

          </div>



        </div><!-- end Row trabalhos -->

    </div>
    {{-- Comissão --}}
    <div id="divCadastrarComissao" class="comissao">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="titulo-detalhes">Cadastrar Comissão</h1>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-sm-5">
                <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">Novo Membro</h5>
                      <h6 class="card-subtitle mb-2 text-muted">Cadastre um membro para sua Comissão</h6>
                      <form action="{{route('cadastrar.comissao')}}" method="POST">
                        @csrf
                        <input type="hidden" name="eventoId" value="{{ $evento->id ?? '' }}">
                        <p class="card-text">
                            <div class="row justify-content-center">
                                <div class="col-sm-12">
                                    <label for="emailMembroComissao" class="control-label">E-mail do novo membro</label>
                                    <input type="email" name="emailMembroComissao" class="form-control @error('emailMembroComissao') is-invalid @enderror" name="emailMembroComissao" value="{{ old('emailMembroComissao') }}" id="emailMembroComissao" placeholder="E-mail">
                                    @error('emailMembroComissao')
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

        </div>

    </div>{{-- End cadastrar Comissão --}}

    <div id="divDefinirCoordComissao" class="comissao">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="titulo-detalhes">Definir Coordenador da Comissão</h1>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-sm-5">
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
                                                    <option value="{{$user->id}}" selected>{{$user->email}}</option>
                                                @else
                                                    <option value="{{$user->id}}">{{$user->email}}</option>
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
    </div>{{-- End Cord Comissão --}}

    <div id="divListarComissao" class="comissao">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="titulo-detalhes">Listar Comissão</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">Comissão</h5>
                      <h6 class="card-subtitle mb-2 text-muted">Usuários cadastrados na comissão do seu evento.</h6>
                      <p class="card-text">
                        <table class="table table-hover table-responsive-lg table-sm">
                            <thead>
                                <th>
                                    <th>Nome</th>
                                    <th>Especialidade</th>
                                    <th>Celular</th>
                                    <th>E-mail</th>
                                    <th>Direção</th>
                                </th>
                            </thead>
                                @foreach ($users as $user)
                                    <tbody>

                                        <th>
                                          @if (isset($user->name))
                                            <td>{{$user->name}}</td>
                                            <td>{{$user->especProfissional}}</td>
                                            <td>{{$user->celular}}</td>
                                            <td>{{$user->email}}</td>
                                            @if ($evento->coordComissaoId == $user->id)
                                              <td>Coordenador</td>
                                            @endif
                                          @else
                                            <td>Usuário temporário - Sem nome</td>
                                            <td>Usuário temporário - Sem Especialidade</td>
                                            <td>Usuário temporário - Sem Celular</td>
                                            <td>{{$user->email}}</td>
                                            @if ($evento->coordComissaoId == $user->id)
                                              <td>Coordenador</td>
                                            @endif
                                          @endif
                                        </th>
                                    </tbody>
                                @endforeach
                        </table>
                      </p>
                    </div>
                  </div>
            </div>
        </div>


        {{-- tabela membros comissão --}}
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-responsive-lg table-hover">

                </table>
            </div>
        </div>
    </div>{{-- End Listar Comissão --}}

    <!-- Trabalhos -->
    <div id="divListarTrabalhos" style="display: none">

        <div class="row titulo-detalhes">
            <div class="col-sm-10">
                <h1 class="">Trabalhos</h1>
            </div>

            <form method="GET" action="{{route('distribuicao')}}">
              <input type="hidden" name="eventoId" value="{{$evento->id}}">
              <div class="row justify-content-center">
                <div class="col-md-12">
                  <button onclick="event.preventDefault();" data-toggle="modal" data-target="#modalDistribuicaoAutomatica" class="btn btn-primary" style="width:100%">
                    {{ __('Distribuir Trabalhos') }}
                  </button>
                </div>
              </div>
            </form>

        </div>

    {{-- Tabela Trabalhos --}}
    <div class="row">
      <div class="col-sm-12">
        <table class="table table-hover table-responsive-lg table-sm">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Área</th>
              <th scope="col">Modalidade</th>
              <th scope="col">Revisores</th>
              <th scope="col" style="text-align:center">Baixar</th>
              <th scope="col" style="text-align:center">Visualizar</th>
            </tr>
          </thead>
          <tbody>
            @php $i = 0; @endphp
            @foreach($trabalhos as $trabalho)

            <tr>
              <td>{{$trabalho->id}}</td>
              <td>{{$trabalho->area->nome}}</td>
              <td>{{$trabalho->modalidade->nome}}</td>
              <td>
                @foreach($trabalho->atribuicao as $atribuicao)
                {{$atribuicao->revisor->user->email}},
                @endforeach
              </td>
              <td style="text-align:center">
                @php $arquivo = ""; $i++; @endphp
                @foreach($trabalho->arquivo as $key)
                @php
                if($key->versaoFinal == true){
                  $arquivo = $key->nome;
                }
                @endphp
                @endforeach
                <img onclick="document.getElementById('formDownload{{$i}}').submit();" class="" src="{{asset('img/icons/file-download-solid-black.svg')}}" style="width:20px" alt="">
                <form method="GET" action="{{ route('download') }}" target="_new" id="formDownload{{$i}}">
                  <input type="hidden" name="file" value="{{$arquivo}}">
                </form>
              </td>
              <td style="text-align:center">
                <a class="botaoAjax" href="#" data-toggle="modal" onclick="trabalhoId({{$trabalho->id}})" data-target="#modalTrabalho"><img src="{{asset('img/icons/eye-regular.svg')}}" style="width:20px"></a>
              </td>

            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

    </div>

</div><!-- End Trabalhos -->
<!-- Definir Submissões -->
<div id="divDefinirSubmissoes" style="display: none">

    <div class="row titulo-detalhes">
        <div class="col-sm-10">
            <h1 class="">Definir Submissões</h1>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Definir Submissões do Trabalho</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Informe o número de trabalhos que cada autor poderá enviar e o número de trabalhos em que um usuário poderá ser um coautor</h6>
                    <form method="POST" action="{{route('trabalho.numTrabalhos')}}">
                    @csrf
                    <p class="card-text">
                        <input type="hidden" name="eventoId" value="{{$evento->id}}">

                        <div class="row justify-content-center">
                            <div class="col-sm-12">
                                <label for="trabalhosPorAutor" class="col-form-label">{{ __('Número de trabalhos por Autor') }}</label>
                                <input id="trabalhosPorAutor" type="text" class="form-control @error('trabalhosPorAutor') is-invalid @enderror" name="trabalhosPorAutor" value="{{ old('trabalhosPorAutor') }}" required autocomplete="trabalhosPorAutor" autofocus>

                                @error('trabalhosPorAutor')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                        </div>{{-- end row--}}

                        <div class="row justify-content-center">
                            <div class="col-sm-12">
                                <label for="numCoautor" class="col-form-label">{{ __('Número de trabalhos como Coautor') }}</label>
                                <input id="numCoautor" type="text" class="form-control @error('numCoautor') is-invalid @enderror" name="numCoautor" value="{{ old('numCoautor') }}" required autocomplete="numCoautor" autofocus>

                                @error('numCoautor')
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
    </div>
    
    <div class="row justify-content-center">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Logo Evento</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Modifique a foto do evento aqui.</h6>
                    <form method="POST" action="{{route('evento.setFotoEvento')}}" enctype="multipart/form-data">
                    @csrf
                    <p class="card-text">
                        <input type="hidden" name="eventoId" value="{{$evento->id}}">

                        <div class="row justify-content-center">
                            <div class="col-sm-12">
                              <label for="fotoEvento">Logo</label>
                              <input type="file" class="form-control-file @error('fotoEvento') is-invalid @enderror" name="fotoEvento" value="{{ old('fotoEvento') }}" id="fotoEvento">
                              @error('fotoEvento')
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
    </div>
</div><!-- Definir Submissões -->
<div>
    @error('comparacaocaracteres')
      @include('componentes.mensagens')
    @enderror
</div>
<div>
    @error('comparacaopalavras')
      @include('componentes.mensagens')
    @enderror
</div>
<div>
    @error('marcarextensao')
      @include('componentes.mensagens')
    @enderror
</div>
<div>
    @error('caracteresoupalavras')
      @include('componentes.mensagens')
    @enderror
</div>
<div>
    @error('semcaractere')
      @include('componentes.mensagens')
    @enderror
</div>
<div>
    @error('sempalavra')
      @include('componentes.mensagens')
    @enderror
</div>
{{-- Modalidade --}}
<div id="divCadastrarModalidades" class="modalidades">
    <div class="row">
        <div class="col-sm-12">
            <h1 class="titulo-detalhes">Cadastrar Modalidade</h1>
        </div>
    </div>
    <input id="input"/>
    <p id="demo"></p>
    {{-- row card --}}
    <div class="row justify-content-center">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Nova Modalidade</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Cadastre uma nova modalidade para o seu evento</h6>
                    <form method="POST" action="{{route('modalidade.store')}}" enctype="multipart/form-data">
                    @csrf
                    <p class="card-text">
                        <input type="hidden" name="eventoId" value="{{$evento->id}}">
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="nomeModalidade" class="col-form-label">*{{ __('Nome') }}</label>

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

                    {{-- Data: inicioSubmissao | fimSubmissao --}}
                    <div class="row justify-content-center">

                        <div class="col-sm-6">
                            <label for="inicioSubmissao" class="col-form-label">{{ __('Início da Submissão') }}</label>
                            <input id="inicioSubmissao" type="date" class="form-control @error('inicioSubmissao') is-invalid @enderror" name="inicioSubmissao" value="{{ old('inicioSubmissao') }}" autocomplete="inicioSubmissao" autofocus>

                            @error('inicioSubmissao')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-sm-6">
                            <label for="fimSubmissao" class="col-form-label">{{ __('Fim da Submissão') }}</label>
                            <input id="fimSubmissao" type="date" class="form-control @error('fimSubmissao') is-invalid @enderror" name="fimSubmissao" value="{{ old('fimSubmissao') }}" autocomplete="fimSubmissao" autofocus>

                            @error('fimSubmissao')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    {{-- end Data: inicioSubmissao | fimSubmissao --}}

                    {{-- Data: inicioRevisao | fimRevisao --}}
                    <div class="row justify-content-center">

                        <div class="col-sm-6">
                            <label for="inicioRevisao" class="col-form-label">{{ __('Início da Revisão') }}</label>
                            <input id="inicioRevisao" type="date" class="form-control @error('inicioRevisao') is-invalid @enderror" name="inicioRevisao" value="{{ old('inicioRevisao') }}" autocomplete="inicioRevisao" autofocus>

                            @error('inicioRevisao')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-sm-6">
                            <label for="fimRevisao" class="col-form-label">{{ __('Fim da Revisão') }}</label>
                            <input id="fimRevisao" type="date" class="form-control @error('fimRevisao') is-invalid @enderror" name="fimRevisao" value="{{ old('fimRevisao') }}" autocomplete="fimRevisao" autofocus>

                            @error('fimRevisao')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    {{-- end Data: inicioRevisão | fimRevisao --}}

                    {{-- Data: resultado --}}
                    <div class="row">

                        <div class="col-sm-6">
                            <label for="inicioResultado" class="col-form-label">{{ __('Início do Resultado') }}</label>
                            <input id="inicioResultado" type="date" class="form-control @error('inicioResultado') is-invalid @enderror" name="inicioResultado" value="{{ old('inicioResultado') }}" autocomplete="inicioResultado" autofocus>

                            @error('inicioResultado')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    {{-- end Data: resultado --}}

                    {{-- Inicio - Tipo de submissão --}}
                    <div class="row">

                        <div class="col-sm-6">
                            <label class="col-form-label">{{ __('Restrições de resumo:') }}</label>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="limit" id="id-limit-custom_field-account-1-1" value="limit-option1" required>
                                <label class="form-check-label" for="texto">
                                    Quantidade de caracteres 
                                </label>
                                </div>
                                <div class="form-check">
                                <input class="form-check-input" type="radio" name="limit" id="id-limit-custom_field-account-1-2" value="limit-option2" required>
                                <label class="form-check-label" for="arquivo">
                                    Quantidade de palavras 
                                </label>
                            </div>

                            <div class="row">
                                <div class="col-sm-6" id="min-max-caracteres" style="display: none">
                                    <div class="form-group">
                                        <label class="col-form-label">{{ __('Mínimo') }}</label>
                                        <div>
                                          <input class="form-control" type="number" id="min_caracteres" name="mincaracteres">
                                        </div>
                                    </div>
        
                                    <div class="form-group">
                                        <label class="col-form-label">{{ __('Máximo') }}</label>
                                        <div>
                                          <input class="form-control" type="number" id="max_caracteres" name="maxcaracteres">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6" id="min-max-palavras" style="display: none">
                                    <div class="form-group">
                                        <label class="col-form-label">{{ __('Mínimo') }}</label>
                                        <div>
                                          <input class="form-control" type="number" id="min_palavras" name="minpalavras">
                                        </div>
                                    </div>
        
                                    <div class="form-group">
                                        <label class="col-form-label">{{ __('Máximo') }}</label>
                                        <div>
                                          <input class="form-control" type="number" id="max_palavras" name="maxpalavras">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-check" style="margin-top: 10px">
                                <input class="form-check-input incluirarquivo" type="checkbox" name="arquivo" id="id-custom_field-account-1-2">
                                <label class="form-check-label" for="arquivo">
                                    Incluir submissão por arquivo 
                                </label>
                                @error('arquivo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6" id="tipo-arquivo" style="display: none">

                            <div class="titulo-detalhes" style="margin-top: 10px"></div>
                            <label class="col-form-label">{{ __('Tipos de extensão aceitas') }}</label>

                            <div class="form-check" style="margin-top: 10px">
                                <input class="form-check-input" type="checkbox" id="defaultCheck1" name="pdf">
                                <label class="form-check-label" for="defaultCheck1">
                                    .pdf
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="defaultCheck1" name="jpg">
                                <label class="form-check-label" for="defaultCheck1">
                                    .jpg
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="defaultCheck1" name="jpeg">
                                <label class="form-check-label" for="defaultCheck1">
                                    .jpeg
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="defaultCheck1" name="png">
                                <label class="form-check-label" for="defaultCheck1">
                                    .png
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="defaultCheck1" name="docx">
                                <label class="form-check-label" for="defaultCheck1">
                                    .docx
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="defaultCheck1" name="odt">
                                <label class="form-check-label" for="defaultCheck1">
                                    .odt
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        {{-- Arquivo de Regras  --}}
                        <div class="col-sm-12" style="margin-top: 20px;">
                          <label for="arquivoRegras" class="col-form-label">{{ __('Enviar regras:') }}</label>

                          <div class="custom-file">
                            <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="arquivoRegras">
                          </div>
                          <small>O arquivo Selecionado deve ser no formato PDF de até 2mb.</small>
                          @error('arquivoRegras')
                          <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                            <strong>{{ $message }}</strong>
                          </span>
                          @enderror
                        </div>
                        {{-- Arquivo de Templates --}}
                        <div class="col-sm-12" id="area-template" style="margin-top: 20px; display:none">
                            <label for="nomeTrabalho" class="col-form-label">{{ __('Enviar template:') }}</label>
  
                            <div class="custom-file">
                              <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="arquivoTemplates">
                            </div>
                            <small>O arquivo Selecionado deve ser no formato PDF de até 2mb.</small>
                            @error('arquivoTemplates')
                            <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                              <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <br>
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
</div>
<div id="divListarModalidades" class="modalidades">
    <div class="row">
        <div class="col-sm-12">
            <h1 class="titulo-detalhes">Listar Modalidades</h1>
        </div>
    </div>

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
                            <th scope="col">Editar</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($modalidades as $modalidade)
                            <tr>
                                <td>{{$modalidade->nome}}</td>
                                <td style="text-align:center">
                                    <a class="botaoAjax" href="#" data-toggle="modal" onclick="modalidadeId({{$modalidade->id}})" data-target="#modalEditarModalidade"><img src="{{asset('img/icons/edit-regular.svg')}}" style="width:20px"></a>
                                </td>
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
</div>
    <div id="divClassificacao" class="classificacao">
        <h1>Classificação</h1>
    </div>
    <div id="divAtividades" class="atividades">
        <h1>Atividades</h1>
    </div>

<!-- Área -->
<div id="divCadastrarAreas" style="display: none">
    <div class="row">
        <div class="col-sm-12">
            <h1 class="titulo-detalhes">Cadastrar Áreas</h1>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-sm-5">
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
</div>

<div id="divListarAreas" style="display: none">
    <div class="row">
        <div class="col-sm-12">
            <h1 class="titulo-detalhes">Listar Áreas</h1>
        </div>
    </div>

    <div class="row justify-content-center">

        <div class="col-sm-8">
            <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Áreas</h5>
                  <h6 class="card-subtitle mb-2 text-muted">Áreas cadastradas no seu evento</h6>
                  <p class="card-text">
                    <table class="table table-hover table-responsive-lg table-sm">
                        <thead>
                          <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nome</th>
                            <th scope="col" style="text-align:center">Remover</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($areas as $area)
                            <tr>
                              <th scope="row">{{$area->id}}</th>
                              <td>{{$area->nome}}</td>
                              <td style="text-align:center">
                                <img src="{{asset('img/icons/trash-alt-regular.svg')}}" style="width:15px">
                              </td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                  </p>
                </div>
              </div>
        </div>
    </div>
</div>
<!-- Revisores -->
<div id="divCadastrarRevisores" style="display: none">

    <div class="row">
        <div class="col-sm-12">
            <h1 class="titulo-detalhes">Cadastrar Revisores</h1>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-sm-10">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Revisores</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Cadastre um novo revisor no seu evento</h6>
                    <form method="POST" action="{{route('revisor.store')}}">
                        @csrf
                        <p class="card-text">
                            <input type="hidden" name="eventoId" value="{{$evento->id}}">
                            <div class="row justify-content-center">
                                <div class="col-sm-4">
                                    <label for="nomeRevisor" class="col-form-label">{{ __('Nome do Revisor') }}</label>
                                    <input id="nomeRevisor" type="text" class="form-control @error('nomeRevisor') is-invalid @enderror" name="nomeRevisor" value="{{ old('nomeRevisor') }}" required autocomplete="nomeRevisor" autofocus>

                                    @error('nomeRevisor')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-sm-4">
                                    <label for="emailRevisor" class="col-form-label">{{ __('Email do Revisor') }}</label>
                                    <input id="emailRevisor" type="text" class="form-control @error('emailRevisor') is-invalid @enderror" name="emailRevisor" value="{{ old('emailRevisor') }}" required autocomplete="emailRevisor" autofocus>

                                    @error('emailRevisor')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-sm-4">
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
    </div>




</div>
<div id="divListarRevisores" style="display: none">

    <div class="row">
        <div class="col-sm-12">
            <h1 class="titulo-detalhes">Listar Revisores</h1>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Revisores</h5>
                  <h6 class="card-subtitle mb-2 text-muted">Revisores cadastrados no seu evento</h6>
                  <p class="card-text">
                    <table class="table table-hover table-responsive-lg table-sm">
                        <thead>
                          <tr>
                            <th scope="col">Nome</th>
                            <th scope="col">Área</th>
                            <th scope="col" style="text-align:center">Em Andamento</th>
                            <th scope="col" style="text-align:center">Finalizados</th>
                            <th scope="col" style="text-align:center">Visualizar</th>
                            <th scope="col" style="text-align:center">Lembrar</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($revisores as $revisor)
                            <tr>
                              <td>{{$revisor->user->email}}</td>
                              <td>{{$revisor->area->nome}}</td>
                              <td style="text-align:center">{{$revisor->correcoesEmAndamento}}</td>
                              <td style="text-align:center">{{$revisor->trabalhosCorrigidos}}</td>
                              <td style="text-align:center">
                                <a href="#" data-toggle="modal" data-target="#modalRevisor">
                                  <img src="{{asset('img/icons/eye-regular.svg')}}" style="width:20px">
                                </a>
                              </td>
                              <td style="text-align:center">
                                  <form action="{{route('revisor.email')}}" method="POST" >
                                    @csrf
                                      <input type="hidden" name="user" value= '@json($revisor->user)'>
                                      <button class="btn btn-primary btn-sm" type="submit">
                                          Enviar e-mail
                                      </button>
                                  </form>
                              </td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                      @if(count($revs) > 0 && isset($revs))
                        <form action="{{route('revisor.emailTodos')}}" method="POST" >
                            @csrf
                              <input type="hidden" name="revisores" value='@json($revs)'>
                              <button class="btn btn-primary btn-sm" type="submit">
                                  Lembrar todos
                              </button>
                          </form>
                      @endif
                      
                  </p>

                </div>
              </div>
        </div>
    </div>
</div>

{{-- Evento --}}
<div id="divEditarEtiquetas" class="eventos" style="display: none">
    <div class="row">
        <div class="col-sm-12">
            <h1 class="titulo-detalhes">Editar Etiquetas</h1>
        </div>
    </div>
    {{-- row card - Edição de Etiquetas --}}

    <div class="row justify-content-center">
        <div class="col-sm-10">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Modelo Atual - Card de Eventos</h5>
                    <p class="card-text">
                    <form method="POST" action="{{route('etiquetas.update', $evento->id)}}">
                        @csrf

                        <div class="row justify-content-left">
                            
                            <div class="col-sm-auto">
                                <h4 id="classeh4"></h4>
                            </div>
                            {{-- <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-nome" style="width:20px"></a> --}}
                            {{-- <button type="button" id="botao-editar-nome" class="btn btn-outline-dark">Editar</button> --}}
                            <div class="col-sm-3" id="etiqueta-nome-evento">
                                <input type="text" class="form-control etiquetanomeevento" id="etiquetanomeevento" name="etiquetanomeevento" placeholder="Editar Etiqueta">
                            </div>

                        </div>
                        <div class="row justify-content-left">
                            <div class="col-sm-12">
                                <p>{{$evento->nome}}</p>
                            </div>
                        </div>
                        
                        
                        <div class="row justify-content-left">
            
                            <div class="col-sm-auto">
                                <h4>{{$etiquetas->etiquetadescricaoevento}}:</h4>
                            </div>
                            <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-descricao" style="width:20px"></a>
                            {{-- <button type="button" id="botao-editar-descricao" class="btn btn-outline-dark">Editar</button> --}}
                            <div class="col-sm-3" id="etiqueta-descricao-evento" style="display: none">
                                <input type="text" class="form-control" id="etiquetadescricaoevento" name="etiquetadescricaoevento" placeholder="Editar Etiqueta">
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-sm-12">
                                <p>{{$evento->descricao}}</p>
                            </div>
                        </div>

                        <div class="row justify-content-left">
                            <div class="col-sm-auto">
                                <h4>{{$etiquetas->etiquetatipoevento}}:</h4>
                            </div>
                            <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-tipo" style="width:20px"></a>
                            {{-- <button type="button" id="botao-editar-tipo" class="btn btn-outline-dark">Editar</button> --}}
                            <div class="col-sm-3" id="etiqueta-tipo-evento" style="display: none">
                                <input type="text" class="form-control" id="etiquetatipoevento" name="etiquetatipoevento" placeholder="Editar Etiqueta">
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-sm-12">
                                <p>{{$evento->tipo}}</p>
                            </div>
                        </div>
                        
                        <div class="row justify-content-left">
                            <div class="col-sm-auto info-evento">
                                <h4>{{$etiquetas->etiquetadatas}}:</h4>
                            </div>
                            <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-datas" style="width:20px"></a>
                            {{-- <button type="button" id="botao-editar-datas" class="btn btn-outline-dark">Editar</button> --}}
                            <div class="col-sm-3" id="etiqueta-data-evento" style="display: none">
                                <input type="text" class="form-control" id="etiquetadatas" name="etiquetadatas" placeholder="Editar Etiqueta">
                            </div>
                        </div>
                        <div class="row justify-content-left">
                            <div class="col-sm-12">
                                <p>
                                    <img class="" alt="">
                                    Data: --/--/-- * --/--/--
                                </p>
                            </div>
                        </div>
                        
                        <div class="row justify-content-left">
                            <div class="col-sm-auto info-evento">
                                <h4>{{$etiquetas->etiquetasubmissoes}}:</h4>
                            </div>
                            <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-submissoes" style="width:20px"></a>
                            {{-- <button type="button" id="botao-editar-submissoes" class="btn btn-outline-dark">Editar</button> --}}
                            <div class="col-sm-3" id="etiqueta-submissoes-evento" style="display: none">
                                <input type="text" class="form-control" id="etiquetasubmissoes" name="etiquetasubmissoes" placeholder="Editar Etiqueta">
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-sm-12">
                                <h6>Modalidade: Nome da modalidade aqui</h6>
                                <p>
                                    <img class="" alt="">
                                    Submissão datas: --/--/-- * --/--/--
                                </p>
                                <p>
                                    <img class="" alt="">
                                    Revisão datas: --/--/-- * --/--/--
                                </p>
                                <p>
                                    <img class="" alt="">
                                    Resultado data: --/--/--
                                </p>
                            </div>
                        </div>

                        <div class="row justify-content-left">
                            <div class="col-sm-auto">
                                <a>
                                    <img class="" src="{{asset('img/icons/file-download-solid.svg')}}" style="width:20px">
                                </a>
                                <label for="nomeTrabalho" class="col-form-label">{{$etiquetas->etiquetabaixarregra}}:</label>
                                <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-etiqueta-regra" style="width:20px"></a>
                                {{-- <button type="button" id="botao-editar-etiqueta-regra" class="btn btn-outline-dark">Editar</button> --}}
                            </div>
                            <div class="col-sm-auto" id="etiqueta-baixar-regra-evento" style="display: none">
                                <input type="text" class="form-control" id="etiquetabaixarregra" name="etiquetabaixarregra" placeholder="Editar Etiqueta">
                            </div>
                        </div>

                        <div class="row justify-content-left">
                            <div class="col-sm-auto">
                                <a>
                                    <img class="" src="{{asset('img/icons/file-download-solid.svg')}}" style="width:20px">
                                </a>
                                <label for="nomeTrabalho" class="col-form-label">{{$etiquetas->etiquetabaixartemplate}}:</label>
                                <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-etiqueta-template" style="width:20px"></a>
                                {{-- <button type="button" id="botao-editar-etiqueta-template" class="btn btn-outline-dark">Editar</button> --}}
                            </div>
                            <div class="col-sm-auto" id="etiqueta-baixar-template-evento" style="display: none">
                                <input type="text" class="form-control" id="etiquetabaixartemplate" name="etiquetabaixartemplate" placeholder="Editar Etiqueta">
                            </div>
                        </div>

                        <div class="row justify-content-left" style="margin-top: 20px">
                            <div class="col-sm-auto info-evento">
                                <h4>{{$etiquetas->etiquetaenderecoevento}}:</h4>
                            </div>
                            <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-endereco" style="width:20px"></a>
                            {{-- <button type="button" id="botao-editar-endereco" class="btn btn-outline-dark">Editar</button> --}}
                            <div class="col-sm-3" id="etiqueta-endereco-evento" style="display: none">
                                <input type="text" class="form-control" id="etiquetaenderecoevento" name="etiquetaenderecoevento" placeholder="Editar Etiqueta">
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-sm-12"  style="margin-top: 10px">
                                Local do evento aqui: {{$evento->endereco->rua}}, {{$evento->endereco->numero}} - {{$evento->endereco->cidade}} / {{$evento->endereco->uf}}.
                            </div>
                        </div>

                        <div class="row justify-content-left" style="margin-top: 10px">
                            <div class="col-sm-auto info-evento">
                                <h4>{{$etiquetas->etiquetamoduloinscricao}}:</h4>
                            </div>
                            <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-modulo-inscricao" style="width:20px"></a>
                            {{-- <button type="button" id="botao-editar-modulo-inscricao" class="btn btn-outline-dark">Editar</button> --}}
                            <div class="col-sm-3" id="etiqueta-modulo-inscricao-evento" style="display: none">
                                <input type="text" class="form-control" id="etiquetamoduloinscricao" name="etiquetamoduloinscricao" placeholder="Editar Etiqueta">
                            </div>
                        </div>
                        <p style="margin-top: 10px">
                            Informações sobre inscrições
                        </p>

                        <div class="row justify-content-left">
                            <div class="col-sm-auto info-evento">
                                <h4>{{$etiquetas->etiquetamoduloprogramacao}}:</h4>
                            </div>
                            <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-modulo-programacao" style="width:20px"></a>
                            {{-- <button type="button" id="botao-editar-modulo-programacao" class="btn btn-outline-dark">Editar</button> --}}
                            <div class="col-sm-3" id="etiqueta-modulo-programacao-evento" style="display: none">
                                <input type="text" class="form-control" id="etiquetamoduloprogramacao" name="etiquetamoduloprogramacao" placeholder="Editar Etiqueta">
                            </div>
                        </div>
                        <p style="margin-top: 10px">
                            Informações sobre programação
                        </p>

                        <div class="row justify-content-left">
                            <div class="col-sm-auto info-evento">
                                <h4>{{$etiquetas->etiquetamoduloorganizacao}}:</h4>
                            </div>
                            <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-modulo-organizacao" style="width:20px"></a>
                            {{-- <button type="button" id="botao-editar-modulo-organizacao" class="btn btn-outline-dark">Editar</button> --}}
                            <div class="col-sm-3" id="etiqueta-modulo-organizacao-evento" style="display: none">
                                <input type="text" class="form-control" id="etiquetamoduloorganizacao" name="etiquetamoduloorganizacao" placeholder="Editar Etiqueta">
                            </div>
                        </div>
                        <p>
                            Informações sobre a organização
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
    </div>{{-- end row card --}}

    {{-- Habilitar Modulos --}}
    <div class="row justify-content-center">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Módulos</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Escolha quais módulos serão exibidos</h6>
                    <form method="POST" action="{{route('exibir.modulo', $evento->id)}}">
                    @csrf
                    
                    <p class="card-text">
                        <input type="hidden" name="modinscricao" value="false" id="modinscricao">
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="modinscricao" class="col-form-label">{{ __('Inscrições') }}</label>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-sm-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" @if($etiquetas->modinscricao) checked @endif name="modinscricao" id="modinscricao">
                                    <label class="form-check-label" for="modinscricao">
                                      Habilitar
                                    </label>
                                </div>
    
                                @error('modinscricao')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>{{-- end row--}}
                    </p>
    
                    <p class="card-text">
                      <input type="hidden" name="modprogramacao" value="false" id="modprogramacao">
                      <div class="row">
                          <div class="col-sm-12">
                              <label for="modprogramacao" class="col-form-label">{{ __('Programação') }}</label>
                          </div>
                      </div>
                      <div class="row justify-content-center">
                          <div class="col-sm-12">
                              <div class="form-check">
                                  <input class="form-check-input" type="checkbox" @if($etiquetas->modprogramacao) checked @endif name="modprogramacao" id="modprogramacao">
                                  <label class="form-check-label" for="modprogramacao">
                                    Habilitar
                                  </label>
                              </div>
    
                              @error('modprogramacao')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                              @enderror
                          </div>
    
                      </div>{{-- end row--}}
    
                    </p>
    
                    <p class="card-text">
                      <input type="hidden" name="modorganizacao" value="false" id="modorganizacao">
                      <div class="row">
                          <div class="col-sm-12">
                              <label for="modorganizacao" class="col-form-label">{{ __('Organização e Apoio') }}</label>
                          </div>
                      </div>
                      <div class="row justify-content-center">
                          <div class="col-sm-12">
                              
                              <div class="form-check">
                                  <input class="form-check-input" type="checkbox" @if($etiquetas->modorganizacao) checked @endif name="modorganizacao" id="modorganizacao">
                                  <label class="form-check-label" for="modorganizacao">
                                    Habilitar
                                  </label>
                              </div>
    
                              @error('modorganizacao')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                              @enderror
                          </div>
    
                      </div>{{-- end row--}}
    
                    </p>
    
                    <p class="card-text">
                      <input type="hidden" name="modsubmissao" value="false" id="modsubmissao">
                      <div class="row">
                          <div class="col-sm-12">
                              <label for="modsubmissao" class="col-form-label">{{ __('Submissões de Trabalhos') }}</label>
                          </div>
                      </div>
                      <div class="row justify-content-center">
                          <div class="col-sm-12">
                              
                              <div class="form-check">
                                  <input class="form-check-input" type="checkbox" @if($etiquetas->modsubmissao) checked @endif name="modsubmissao" id="modsubmissao">
                                  <label class="form-check-label" for="modsubmissao">
                                    Habilitar
                                  </label>
                              </div>
    
                              @error('modsubmissao')
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
    </div>
    {{-- Fim --}}
  
</div>


{{-- Submissão de Trabalhos - edição de etiquetas --}}
<div id="divEditarEtiquetasSubTrabalho" class="eventos" style="display: none">
    <div class="row">
        <div class="col-sm-12">
            <h1 class="titulo-detalhes">Editar Etiquetas</h1>
        </div>
    </div>
    
    <div class="row justify-content-center">
        <div class="col-sm-10">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Modelo Atual - Form de Submissão de Trabalhos</h5>
                    <p class="card-text">
                    <form method="POST" action="{{route('etiquetas_sub_trabalho.update', $evento->id)}}">
                    @csrf

                    <div class="card" id="bisavo">
                        <div class="card-body">
                            <div class="row" id="1" value="1">
                                <div class="col-sm-auto">
                                    <label for="nomeTrabalho" class="col-form-label">{{$etiquetasSubTrab->etiquetatitulotrabalho}}:</label>
                                </div>
                                <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-titulo" style="width:20px"></a>
                                {{-- <button type="button" id="botao-editar-titulo" class="btn btn-outline-dark">Editar</button> --}}
                                <div class="col-sm-3" id="etiqueta-titulo-trabalho" style="display: none">
                                    <input type="text" class="form-control" id="inputEmail3" name="etiquetatitulotrabalho" placeholder="Editar Etiqueta">
                                </div>
                                <a class="move-up"><img src="{{asset('img/icons/sobe.png')}}" id="antesTitulo" style="width:20px; margin-left:10px"></a>
                                <a class="move-down"><img src="{{asset('img/icons/desce.png')}}" id="depoisTitulo" style="width:20px"></a>
                                <input id="nomeTrabalho" type="text" class="form-control" style="margin-top: 10px" disabled><br/>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card" id="bisavo">
                        <div class="card-body">
                            <div class="row justify-content-left" id="2" style="margin-top: 10px">
                                <div class="col-sm-auto">
                                    <label for="nomeTrabalho" class="col-form-label">{{$etiquetasSubTrab->etiquetaautortrabalho}}:</label>
                                </div>
                                <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-autor" style="width:20px"></a>
                                {{-- <button type="button" id="botao-editar-autor" class="btn btn-outline-dark">Editar</button> --}}
                                <div class="col-sm-3" id="etiqueta-autor-trabalho" style="display: none">
                                    <input type="text" class="form-control" style="margin-top: 10px" id="inputEmail3" name="etiquetaautortrabalho" placeholder="Editar Etiqueta">
                                </div>
                                <a class="move-up"><img src="{{asset('img/icons/sobe.png')}}" id="antesAutor" style="width:20px; margin-left:10px"></a>
                                <a class="move-down"><img src="{{asset('img/icons/desce.png')}}" id="depoisAutor" style="width:20px"></a>
                                <input class="form-control" type="text" style="margin-top: 10px" disabled><br/>
                            </div>
                        </div>
                    </div>

                    


                    <div class="row justify-content-left" id="3" style="margin-top: 10px">
                        <div class="col-sm-auto">
                        <a href="#" class="btn btn-primary" id="addCoautor" style="width:100%;margin-top:10px" disabled>{{$etiquetasSubTrab->etiquetacoautortrabalho}}:</a>
                        </div>
                        <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-coautor" style="width:20px"></a>
                        {{-- <button type="button" id="botao-editar-coautor" class="btn btn-outline-dark">Editar</button> --}}
                        <div class="col-sm-3" id="etiqueta-coautor-trabalho" style="display: none">
                            <input type="text" class="form-control" id="inputEmail3" name="etiquetacoautortrabalho" placeholder="Editar Etiqueta">
                        </div>
                    </div>


                    <div class="row justify-content-left">
                        <div class="col-sm-auto">
                            <label for="resumo" class="col-form-label">{{$etiquetasSubTrab->etiquetaresumotrabalho}}:</label>
                        </div>
                        <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-resumo" style="width:20px"></a>
                        {{-- <button type="button" id="botao-editar-resumo" class="btn btn-outline-dark">Editar</button> --}}
                        <div class="col-sm-auto" id="etiqueta-resumo-trabalho" style="display: none">
                            <input type="text" class="form-control" id="inputEmail3" name="etiquetaresumotrabalho" placeholder="Editar Etiqueta">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Selecionar posição</label>
                                <select class="form-control" id="exampleFormControlSelect1" name="resumoposicao">
                                  <option value="1">1</option>
                                  <option value="2">2</option>
                                  <option value="3">3</option>
                                </select>
                            </div>
                        </div>
                        <textarea id="resumo" class="char-count form-control @error('resumo') is-invalid @enderror" data-ls-module="charCounter" style="margin-top: 10px" disabled></textarea>
                        <p class="text-muted"><small><span name="resumo">0</span></small></p>
                    </div>   


                    <!-- Areas -->
                    <div class="row justify-content-left">
                        <div class="col-auto">
                            <label for="area" class="col-form-label">{{$etiquetasSubTrab->etiquetaareatrabalho}}:</label>
                        </div>
                        <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-area" style="width:20px"></a>
                        {{-- <button type="button" id="botao-editar-area" class="btn btn-outline-dark">Editar</button> --}}
                        <div class="col-sm-auto" id="etiqueta-area-trabalho" style="display: none">
                            <input type="text" class="form-control" id="inputEmail3" name="etiquetaareatrabalho" placeholder="Editar Etiqueta">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Selecionar posição</label>
                                <select class="form-control" id="exampleFormControlSelect1" name="areaposicao">
                                  <option value="1">1</option>
                                  <option value="2">2</option>
                                  <option value="3">3</option>
                                </select>
                            </div>
                        </div>
                        <select class="form-control @error('area') is-invalid @enderror" id="area" name="areaId" style="margin-top: 10px" disabled>
                            <option value="" disabled selected hidden>-- Área --</option>
                        </select>
                    </div>

                    <div class="row justify-content-left">
                        {{-- Arquivo --}}
                        <div class="col-sm-auto">
                            <label for="nomeTrabalho" class="col-form-label">{{$etiquetasSubTrab->etiquetauploadtrabalho}}:</label>
                        </div>
                        <a><img src="{{asset('img/icons/edit-regular.svg')}}" class="botaoAjax" id="botao-editar-upload" style="width:20px"></a>
                        {{-- <button type="button" id="botao-editar-upload" class="btn btn-outline-dark">Editar</button> --}}
                        <div class="col-sm-auto" id="etiqueta-upload-trabalho" style="display: none">
                            <input type="text" class="form-control" id="inputEmail3" name="etiquetauploadtrabalho" placeholder="Editar Etiqueta">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Selecionar posição</label>
                                <select class="form-control" id="exampleFormControlSelect1" name="uploadposicao">
                                  <option value="1">1</option>
                                  <option value="2">2</option>
                                  <option value="3">3</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="custom-file" style="margin-top: 10px">
                        <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" disabled>
                        </div>
                        <small>Arquivos aceitos nos formatos a seguir</small>
                    <br>

                    <br>
                    <div class="titulo-detalhes"></div>
                    <br>
                    <h4 class="card-title">Campos Adicionais:</h4>
                    <div class="form-group row">
                        <input type="hidden" name="checkcampoextra1" value="false" id="checkcampoextra1">
                        <label for="inputEmail3" class="col-sm-auto col-form-label">{{$etiquetasSubTrab->etiquetacampoextra1}}:</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" id="inputEmail3" name="etiquetacampoextra1" placeholder="Editar Etiqueta">
                        </div>
                        <div class="form-group col-sm-3">
                            <select class="form-control" id="exampleFormControlSelect1" name="select_campo1">
                              @if ($etiquetasSubTrab->tipocampo1 == "textosimples")
                                <option value="textosimples" selected>Texto Simples</option>
                                <option value="textogrande">Texto grande</option>
                                <option value="upload">Upload</option>
                              @elseif ($etiquetasSubTrab->tipocampo1 == "textogrande")
                                <option value="textosimples">Texto Simples</option>
                                <option value="textogrande" selected>Texto grande</option>
                                <option value="upload">Upload</option>
                              @elseif ($etiquetasSubTrab->tipocampo1 == "upload")
                                <option value="textosimples">Texto Simples</option>
                                <option value="textogrande">Texto grande</option>
                                <option value="upload" selected>Upload</option>
                              @else
                                <option value="textosimples" selected>Texto Simples</option>
                                <option value="textogrande">Texto grande</option>
                                <option value="upload" selected>Upload</option>
                              @endif
                            </select>
                        </div>
                        <div class="form-group col-sm-2">
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" id="gridCheck" @if($etiquetasSubTrab->checkcampoextra1) checked @endif name="checkcampoextra1">
                              <label class="form-check-label" for="gridCheck">
                                Exibir
                              </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="checkcampoextra2" value="false" id="checkcampoextra2">
                        <label for="inputEmail3" class="col-sm-auto col-form-label">{{$etiquetasSubTrab->etiquetacampoextra2}}:</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" id="inputEmail3" name="etiquetacampoextra2" placeholder="Editar Etiqueta">
                        </div>
                        <div class="form-group col-sm-3">
                            <select class="form-control" id="exampleFormControlSelect1" name="select_campo2">
                                @if ($etiquetasSubTrab->tipocampo2 == "textosimples")
                                <option value="textosimples" selected>Texto Simples</option>
                                <option value="textogrande">Texto grande</option>
                                <option value="upload">Upload</option>
                                @elseif ($etiquetasSubTrab->tipocampo2 == "textogrande")
                                <option value="textosimples">Texto Simples</option>
                                <option value="textogrande" selected>Texto grande</option>
                                <option value="upload">Upload</option>
                                @elseif ($etiquetasSubTrab->tipocampo2 == "upload")
                                <option value="textosimples">Texto Simples</option>
                                <option value="textogrande">Texto grande</option>
                                <option value="upload" selected>Upload</option>
                                @else
                                <option value="textosimples" selected>Texto Simples</option>
                                <option value="textogrande">Texto grande</option>
                                <option value="upload" selected>Upload</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group col-sm-2">
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" id="gridCheck" @if($etiquetasSubTrab->checkcampoextra2) checked @endif name="checkcampoextra2">
                              <label class="form-check-label" for="gridCheck">
                                Exibir
                              </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="checkcampoextra3" value="false" id="checkcampoextra3">
                        <label for="inputEmail3" class="col-sm-auto col-form-label">{{$etiquetasSubTrab->etiquetacampoextra3}}:</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" id="inputEmail3" name="etiquetacampoextra3" placeholder="Editar Etiqueta">
                        </div>
                        <div class="form-group col-sm-3">
                            <select class="form-control" id="exampleFormControlSelect1" name="select_campo3">
                                @if ($etiquetasSubTrab->tipocampo3 == "textosimples")
                                <option value="textosimples" selected>Texto Simples</option>
                                <option value="textogrande">Texto grande</option>
                                <option value="upload">Upload</option>
                                @elseif ($etiquetasSubTrab->tipocampo3 == "textogrande")
                                <option value="textosimples">Texto Simples</option>
                                <option value="textogrande" selected>Texto grande</option>
                                <option value="upload">Upload</option>
                                @elseif ($etiquetasSubTrab->tipocampo3 == "upload")
                                <option value="textosimples">Texto Simples</option>
                                <option value="textogrande">Texto grande</option>
                                <option value="upload" selected>Upload</option>
                                @else
                                <option value="textosimples" selected>Texto Simples</option>
                                <option value="textogrande">Texto grande</option>
                                <option value="upload" selected>Upload</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group col-sm-2">
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" id="gridCheck" @if($etiquetasSubTrab->checkcampoextra3) checked @endif name="checkcampoextra3">
                              <label class="form-check-label" for="gridCheck">
                                Exibir
                              </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="checkcampoextra4" value="false" id="checkcampoextra4">
                        <label for="inputEmail3" class="col-sm-auto col-form-label">{{$etiquetasSubTrab->etiquetacampoextra4}}:</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" id="inputEmail3" name="etiquetacampoextra4" placeholder="Editar Etiqueta">
                        </div>
                        <div class="form-group col-sm-3">
                            <select class="form-control" id="exampleFormControlSelect1" name="select_campo4">
                                @if ($etiquetasSubTrab->tipocampo4 == "textosimples")
                                <option value="textosimples" selected>Texto Simples</option>
                                <option value="textogrande">Texto grande</option>
                                <option value="upload">Upload</option>
                                @elseif ($etiquetasSubTrab->tipocampo4 == "textogrande")
                                <option value="textosimples">Texto Simples</option>
                                <option value="textogrande" selected>Texto grande</option>
                                <option value="upload">Upload</option>
                                @elseif ($etiquetasSubTrab->tipocampo4 == "upload")
                                <option value="textosimples">Texto Simples</option>
                                <option value="textogrande">Texto grande</option>
                                <option value="upload" selected>Upload</option>
                                @else
                                <option value="textosimples" selected>Texto Simples</option>
                                <option value="textogrande">Texto grande</option>
                                <option value="upload" selected>Upload</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group col-sm-2">
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" id="gridCheck" @if($etiquetasSubTrab->checkcampoextra4) checked @endif name="checkcampoextra4">
                              <label class="form-check-label" for="gridCheck">
                                Exibir
                              </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="checkcampoextra5" value="false" id="checkcampoextra5">
                        <label for="inputEmail3" class="col-sm-auto col-form-label">{{$etiquetasSubTrab->etiquetacampoextra5}}:</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" id="inputEmail3" name="etiquetacampoextra5" placeholder="Editar Etiqueta">
                        </div>
                        <div class="form-group col-sm-3">
                            <select class="form-control" id="exampleFormControlSelect1" name="select_campo5">
                                @if ($etiquetasSubTrab->tipocampo5 == "textosimples")
                                <option value="textosimples" selected>Texto Simples</option>
                                <option value="textogrande">Texto grande</option>
                                <option value="upload">Upload</option>
                                @elseif ($etiquetasSubTrab->tipocampo5 == "textogrande")
                                <option value="textosimples">Texto Simples</option>
                                <option value="textogrande" selected>Texto grande</option>
                                <option value="upload">Upload</option>
                                @elseif ($etiquetasSubTrab->tipocampo5 == "upload")
                                <option value="textosimples">Texto Simples</option>
                                <option value="textogrande">Texto grande</option>
                                <option value="upload" selected>Upload</option>
                                @else
                                <option value="textosimples" selected>Texto Simples</option>
                                <option value="textogrande">Texto grande</option>
                                <option value="upload" selected>Upload</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group col-sm-2">
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" id="gridCheck" @if($etiquetasSubTrab->checkcampoextra5) checked @endif name="checkcampoextra5">
                              <label class="form-check-label" for="gridCheck">
                                Exibir
                              </label>
                            </div>
                        </div>
                    </div>
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
    </div>{{-- end row card --}}



    
</div>
{{-- Template 2 - edição de etiquetas --}}




<!-- Modal Revisor -->
<div class="modal fade" id="modalRevisor" tabindex="-1" role="dialog" aria-labelledby="modalRevisor" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Revisor</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row justify-content-center">
          <div class="col-sm-6">
            <label for="">Nome</label>
            <h5>Nome do Revisor</h5>
          </div>
          <div class="col-sm-6">
            <label for="">E-mail</label>
            <h5>E-mail do Revisor</h5>
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col-sm-6">
            <label for="">Área</label>
            <h5>Área do Revisor</h5>
          </div>
          <div class="col-sm-6">
            <label for="">Instituição</label>
            <h5>Instituição do Revisor</h5>
          </div>
        </div>

        <div class="row justify-content-center" style="margin-top:20px">
          <div class="col-sm-12">
            <h4>Trabalhos</h4>
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col-sm-12">
            <table class="table table-hover table-responsive-lg table-sm">
                <thead>
                  <tr>
                    <th scope="col">Título</th>
                    <th scope="col">Status</th>

                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Título do trabalho</td>
                    <td>Status do trabalho</td>

                  </tr>
                </tbody>
              </table>
          </div>
        </div>
        </div>
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-primary">Salvar</button>
      </div> -->
    </div>
  </div>
</div>

<!-- Modal Trabalho -->
<div class="modal fade" id="modalTrabalho" tabindex="-1" role="dialog" aria-labelledby="modalTrabalho" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Trabalho</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row justify-content-center">
          <div class="col-sm-12">
            <h5>Título</h5>
            <p id="tituloTrabalhoAjax"></p>
          </div>

        </div>
        <div class="row justify-content-center">
          <div class="col-sm-12">
            <h5>Resumo</h5>
            <p id="resumoTrabalhoAjax"></p>
          </div>
        </div>

        <div class="row justify-content-center" style="margin-top:20px">
          <div class="col-sm-12">
            <h5>Remover Revisor</h5>
          </div>
        </div>
        <form action="{{ route('atribuicao.delete') }}" method="post">
          @csrf
          <input type="hidden" name="eventoId" value="{{$evento->id}}">
          <input type="hidden" name="trabalhoId" value="" id="removerRevisorTrabalhoId">
        <div class="row justify-content-center">
          <div class="col-sm-9">
              <div id="revisoresAjax" class="revisoresTrabalho" style="padding-left:20px">
                <div id="cblist">

                </div>
              </div>
          </div>
          <div class="col-sm-3">
            <button type="submit" class="btn btn-primary" id="removerRevisorTrabalho">Remover Revisor</button>
          </div>
        </div>
      </form>
        <div class="row">
          <div class="col-sm-12">
            <h5>Adicionar Revisor</h5>
          </div>
        </div>
        <form action="{{ route('distribuicaoManual') }}" method="post">
          @csrf
          <input type="hidden" name="trabalhoId" value="" id="distribuicaoManualTrabalhoId">
          <input type="hidden" name="eventoId" value="{{$evento->id}}">
          <div class="row" >
            <div class="col-sm-9">
              <div class="form-group">
                <select name="revisorId" class="form-control" id="selectRevisorTrabalho">


                </select>
              </div>
            </div>
            <div class="col-sm-3">
              <button type="submit" class="btn btn-primary" id="addRevisorTrabalho">Adicionar Revisor</button>
            </div>
        </form>
        </div>
        </div>
      <div class="modal-footer">


      </div>
    </div>
  </div>
</div>

<!-- Modal Editar Modalidade/FormTipoSubm -->

<!-- Modal -->
<div class="modal fade" id="modalEditarModalidade" tabindex="-1" role="dialog" aria-labelledby="modalEditarModalidade" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Editar Modalidade</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row justify-content-center">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div>
                                @error('marcarextensao')
                                  @include('componentes.mensagens')
                                @enderror
                            </div>
                            <form method="POST" action="{{route('modalidade.update')}}" enctype="multipart/form-data">
                            @csrf
                            <p class="card-text">
                                <input type="hidden" name="modalidadeEditId" id="modalidadeEditId" value="">
                                <input type="hidden" name="eventoId" value="{{$evento->id}}">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label for="nomeModalidadeEdit" class="col-form-label">*{{ __('Nome') }}</label>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-sm-12">
                                        <input id="nomeModalidadeEdit" type="text" class="form-control @error('nomeModalidadeEdit') is-invalid @enderror" name="nomeModalidadeEdit" value="" required autocomplete="nomeModalidadeEdit" autofocus>
            
                                        @error('nomeModalidadeEdit')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
            
                                </div>{{-- end row--}}
            
                            </p>
            
                            {{-- Data: inicioSubmissao | fimSubmissao --}}
                            <div class="row justify-content-center">
            
                                <div class="col-sm-6">
                                    <label for="inicioSubmissaoEdit" class="col-form-label">{{ __('Início da Submissão') }}</label>
                                    <input id="inicioSubmissaoEdit" type="date" class="form-control @error('inicioSubmissaoEdit') is-invalid @enderror" name="inicioSubmissaoEdit" value="{{ old('inicioSubmissaoEdit') }}" autocomplete="inicioSubmissaoEdit" autofocus>
            
                                    @error('inicioSubmissaoEdit')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label for="fimSubmissaoEdit" class="col-form-label">{{ __('Fim da Submissão') }}</label>
                                    <input id="fimSubmissaoEdit" type="date" class="form-control @error('fimSubmissaoEdit') is-invalid @enderror" name="fimSubmissaoEdit" value="{{ old('fimSubmissaoEdit') }}" autocomplete="fimSubmissaoEdit" autofocus>
            
                                    @error('fimSubmissaoEdit')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            {{-- end Data: inicioSubmissao | fimSubmissao --}}
            
                            {{-- Data: inicioRevisao | fimRevisao --}}
                            <div class="row justify-content-center">
            
                                <div class="col-sm-6">
                                    <label for="inicioRevisaoEdit" class="col-form-label">{{ __('Início da Revisão') }}</label>
                                    <input id="inicioRevisaoEdit" type="date" class="form-control @error('inicioRevisaoEdit') is-invalid @enderror" name="inicioRevisaoEdit" value="{{ old('inicioRevisaoEdit') }}" autocomplete="inicioRevisaoEdit" autofocus>
            
                                    @error('inicioRevisaoEdit')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label for="fimRevisaoEdit" class="col-form-label">{{ __('Fim da Revisão') }}</label>
                                    <input id="fimRevisaoEdit" type="date" class="form-control @error('fimRevisaoEdit') is-invalid @enderror" name="fimRevisaoEdit" value="{{ old('fimRevisaoEdit') }}" autocomplete="fimRevisaoEdit" autofocus>
            
                                    @error('fimRevisaoEdit')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            {{-- end Data: inicioRevisão | fimRevisao --}}
            
                            {{-- Data: resultado --}}
                            <div class="row">
            
                                <div class="col-sm-6">
                                    <label for="inicioResultadoEdit" class="col-form-label">{{ __('Início do Resultado') }}</label>
                                    <input id="inicioResultadoEdit" type="date" class="form-control @error('inicioResultadoEdit') is-invalid @enderror" name="inicioResultadoEdit" value="{{ old('inicioResultadoEdit') }}" autocomplete="inicioResultadoEdit" autofocus>
            
                                    @error('inicioResultadoEdit')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            {{-- end Data: resultado --}}
            
                            {{-- Inicio - Tipo de submissão --}}
                            <div class="row">
            
                                <div class="col-sm-6">
                                    <label class="col-form-label">*{{ __('Restrições de resumo:') }}</label>
            
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="limitEdit" id="id-limit-custom_field-accountEdit-1-1" value="limit-option1Edit">
                                        <label class="form-check-label" for="texto">
                                            Quantidade de caracteres 
                                        </label>
                                        </div>
                                        <div class="form-check">
                                        <input class="form-check-input" type="radio" name="limitEdit" id="id-limit-custom_field-accountEdit-1-2" value="limit-option2Edit">
                                        <label class="form-check-label" for="arquivo">
                                            Quantidade de palavras 
                                        </label>
                                    </div>
            
                                    <div class="row">
                                        <div class="col-sm-6" id="min-max-caracteresEdit" style="display: none">
                                            <div class="form-group">
                                                <label class="col-form-label">{{ __('MínimoC') }}</label>
                                                <div>
                                                  <input class="form-control" type="number" id="mincaracteresEdit" name="mincaracteresEdit">
                                                </div>
                                            </div>
                
                                            <div class="form-group">
                                                <label class="col-form-label">{{ __('MáximoC') }}</label>
                                                <div>
                                                  <input class="form-control" type="number" id="maxcaracteresEdit" name="maxcaracteresEdit">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
            
                                    <div class="row">
                                        <div class="col-sm-6" id="min-max-palavrasEdit" style="display: none">
                                            <div class="form-group">
                                                <label class="col-form-label">{{ __('MínimoP') }}</label>
                                                <div>
                                                  <input class="form-control" type="number" id="minpalavrasEdit" name="minpalavrasEdit">
                                                </div>
                                            </div>
                
                                            <div class="form-group">
                                                <label class="col-form-label">{{ __('MáximoP') }}</label>
                                                <div>
                                                  <input class="form-control" type="number" id="maxpalavrasEdit" name="maxpalavrasEdit">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-check" style="margin-top: 10px">
                                        <input class="form-check-input incluirarquivoEdit" type="checkbox" name="arquivoEdit" id="id-custom_field-accountEdit-1-2">
                                        <label class="form-check-label" for="arquivoEdit">
                                            Incluir submissão por arquivo 
                                        </label>
                                        @error('arquivoEdit')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
            
                            <div class="row">
                                <div class="col-sm-6" id="tipo-arquivoEdit" style="display: none">
            
                                    <div class="titulo-detalhes" style="margin-top: 10px"></div>
                                    <label class="col-form-label">{{ __('Tipos de extensão aceitas') }}</label>
            
                                    <div class="form-check" style="margin-top: 10px">
                                        <input class="form-check-input" type="checkbox" id="pdfEdit" name="pdfEdit">
                                        <label class="form-check-label" for="pdfEdit">
                                            .pdf
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="jpgEdit" name="jpgEdit">
                                        <label class="form-check-label" for="jpgEdit">
                                            .jpg
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="jpegEdit" name="jpegEdit">
                                        <label class="form-check-label" for="jpegEdit">
                                            .jpeg
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="pngEdit" name="pngEdit">
                                        <label class="form-check-label" for="pngEdit">
                                            .png
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="docxEdit" name="docxEdit">
                                        <label class="form-check-label" for="docxEdit">
                                            .docx
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="odtEdit" name="odtEdit">
                                        <label class="form-check-label" for="odtEdit">
                                            .odt
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                {{-- Arquivo de Regras  --}}
                                <div class="col-sm-12" style="margin-top: 20px;">
                                  <label for="arquivoRegras" class="col-form-label">{{ __('Enviar regras:') }}</label>
            
                                  <div class="custom-file">
                                    <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="arquivoRegrasEdit">
                                  </div>
                                  <small>O arquivo Selecionado deve ser no formato PDF de até 2mb.</small>
                                  @error('arquivoRegras')
                                  <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                                    <strong>{{ $message }}</strong>
                                  </span>
                                  @enderror
                                </div>
                                {{-- Arquivo de Templates --}}
                                <div class="col-sm-12" id="area-templateEdit" style="margin-top: 20px; display:none">
                                    <label for="nomeTrabalho" class="col-form-label">{{ __('Enviar template:') }}</label>
            
                                    <div class="custom-file">
                                      <input type="file" class="filestyle" data-placeholder="Nenhum arquivo" data-text="Selecionar" data-btnClass="btn-primary-lmts" name="arquivoTemplatesEdit">
                                    </div>
                                    <small>O arquivo Selecionado deve ser no formato PDF de até 2mb.</small>
                                    @error('arquivoTemplates')
                                    <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                                      <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <br>
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
            </div>{{-- end row card --}}
            
            
            </div>
        </div>
        {{-- <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div> --}}
      </div>
    </div>
</div>
{{-- Fim Modal --}}


<!-- Modal Trabalho -->
<div class="modal fade" id="modalDistribuicaoAutomatica" tabindex="-1" role="dialog" aria-labelledby="modalDistribuicaoAutomatica" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Trabalho</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="GET" action="{{ route('distribuicaoAutomaticaPorArea') }}" id="formDistribuicaoPorArea">
        <div class="modal-body">
          <input type="hidden" name="eventoId" value="{{$evento->id}}">
          <div class="row">
            <div class="col-sm-12">
                <label for="areaId" class="col-form-label">{{ __('Área') }}</label>
                <select class="form-control @error('areaId') is-invalid @enderror" id="areaIdformDistribuicaoPorArea" name="areaId">
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
          <div class="row">
              <div class="col-sm-12">
                  <label for="numeroDeRevisoresPorTrabalho" class="col-form-label">{{ __('Número de revisores por trabalho') }}</label>
              </div>
          </div>
          <div class="row justify-content-center">
              <div class="col-sm-12">
                  <input id="numeroDeRevisoresPorTrabalhoInput" type="number" min="1" class="form-control @error('numeroDeRevisoresPorTrabalho') is-invalid @enderror" name="numeroDeRevisoresPorTrabalho" value="{{ old('numeroDeRevisoresPorTrabalho') }}" required autocomplete="numeroDeRevisoresPorTrabalho" autofocus>

                  @error('numeroDeRevisoresPorTrabalho')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                  @enderror
              </div>

          </div>{{-- end row--}}
        </div>
      </form>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button id="numeroDeRevisoresPorTrabalhoButton" disabled onclick="document.getElementById('formDistribuicaoPorArea').submit();" type="button" class="btn btn-primary">Distribuir</button>
      </div>
    </div>
  </div>
</div>




</div>
<input type="hidden" name="trabalhoIdAjax" value="1" id="trabalhoIdAjax">
<input type="hidden" name="modalidadeIdAjax" value="1" id="modalidadeIdAjax">

@endsection
@section('javascript')
  <script type="text/javascript" >
    var depoisTituloCont = 1;
    $(document).ready(function(){
        $('.move-down').click(function(){
            if ($(this).next()) {
                // console.log("NEXT");
                var t = $(this);
                t.parents("#bisavo").animate({top: '20px'}, 500, function(){
                    t.parents("#bisavo").next().animate({top: '-20px'}, 500, function(){
                        t.parents("#bisavo").css('top', '0px');
                        t.parents("#bisavo").next().css('top', '0px');
                        t.parents("#bisavo").insertAfter(t.parents("#bisavo").next());
                    });
                });
                // $(this).parents("#bisavo").insertAfter($(this).parents("#bisavo").next());
            }
        });
        $('.move-up').click(function(){
            if ($(this).prev()) {
                // console.log("PREV");
                var t = $(this);
                t.parents("#bisavo").animate({top: '-20px'}, 500, function(){
                    t.parents("#bisavo").prev().animate({top: '20px'}, 500, function(){
                        t.parents("#bisavo").css('top', '0px');
                        t.parents("#bisavo").prev().css('top', '0px');
                        t.parents("#bisavo").insertBefore(t.parents("#bisavo").prev());
                    });
                });
                // $(this).parents("#bisavo").insertBefore($(this).parents("#bisavo").prev());
            }
        });
        $("#depoisTitulo").click(function(){
            // // var temp = $(this).closest('[id]');
            // var atual = 1;
            // // console.log("#"+atual);
            // var proximo = atual + depoisTituloCont;
            // // var atual = $("#1").attr("id");
            // $("#"+atual).insertAfter("#"+proximo);
            // depoisTituloCont++;
            // // $("#"+atual).attr("id", toString(proximo));
            // // $("#"+proximo).attr("id", atual);
            // // $("#1").attr(attribute, value)

        });
    });

    $(document).ready(function(){
        $("#antesTitulo").click(function(){
            // var temp = $(this).closest('[id]');
            var atual = 1;
            // console.log("#"+atual);
            var anterior = depoisTituloCont - atual;
            // var atual = $("#1").attr("id");
            $("#"+atual).insertBefore("#"+anterior);
            depoisTituloCont++;
            // $("#"+atual).attr("id", toString(proximo));
            // $("#"+proximo).attr("id", atual);
            // $("#1").attr(attribute, value)
        });
    });

    // Exibir ou ocultar opções de Texto na criação de modalidade - com checkbox
    $(document).ready(function() {
        $('input:checkbox[class="form-check-input incluirarquivo"]').on("change", function() {
            if (this.checked) {
                $("#area-template").show();
                $("#tipo-arquivo").show();
            } else {
                $("#area-template").hide();
                $("#tipo-arquivo").hide();
            }
        });
    });

    $(document).ready(function() {
        $('input:checkbox[class="form-check-input incluirarquivoEdit"]').on("change", function() {
            if (this.checked) {
                $("#area-templateEdit").show();
                $("#tipo-arquivoEdit").show();
            } else {
                $("#area-templateEdit").hide();
                $("#tipo-arquivoEdit").hide();
            }
        });
    });

    // Exibir ou ocultar opções de texto ou arquivo, em cadastro de modalidade
    // $(document).ready(function() {
    //     $('input:radio[name="custom_field"]').on("change", function() {
    //         if (this.checked && this.value == 'option1') {
    //             $("#limite-caracteres").show();
    //             $("#tipo-arquivo").hide();
    //             $("#area-template").hide();
    //         } else {
    //             $("#tipo-arquivo").show();
    //             $("#limite-caracteres").hide();
    //             $("#area-template").show();
    //         }
    //     });
    // });

    // Exibir ou ocultar opções de texto ou arquivo, em edição de modalidade
    // $(document).ready(function() {
    //     $('input:radio[name="custom_fieldEdit"]').on("change", function() {
    //         if (this.checked && this.value == 'option1Edit') {
    //             $("#limite-caracteresEdit").show();
    //             $("#tipo-arquivoEdit").hide();
    //             $("#area-templateEdit").hide();
    //         } else {
    //             $("#tipo-arquivoEdit").show();
    //             $("#limite-caracteresEdit").hide();
    //             $("#area-templateEdit").show();
    //         }
    //     });
    // });

    $(document).ready(function() {
        $('input:radio[name="limit"]').on("change", function() {
            if (this.checked && this.value == 'limit-option1') {
                $("#min-max-caracteres").show();
                $("#min-max-palavras").hide();
            } else {
                $("#min-max-palavras").show();
                $("#min-max-caracteres").hide();
            }
        });
    });

    $(document).ready(function() {
        $('input:radio[name="limitEdit"]').on("change", function() {
            if (this.checked && this.value == 'limit-option1Edit') {
                $("#min-max-caracteresEdit").show();
                $("#min-max-palavrasEdit").hide();
            } else {
                $("#min-max-palavrasEdit").show();
                $("#min-max-caracteresEdit").hide();
            }
        });
    });

    // Exibir ou ocultar campos de edição de etiquetas de eventos
    $(document).ready(function() {
        $('#botao-editar-nome').on("click", function() {
            $("#etiqueta-nome-evento").toggle(500);
        });
    });

    $(document).ready(function() {
        $('#botao-editar-descricao').on("click", function() {
            $("#etiqueta-descricao-evento").toggle(500);
        });
    });

    $(document).ready(function() {
        $('#botao-editar-tipo').on("click", function() {
            $("#etiqueta-tipo-evento").toggle(500);
        });
    });

    $(document).ready(function() {
        $('#botao-editar-datas').on("click", function() {
            $("#etiqueta-data-evento").toggle(500);
        });
    });

    $(document).ready(function() {
        $('#botao-editar-submissoes').on("click", function() {
            $("#etiqueta-submissoes-evento").toggle(500);
        });
    });

    $(document).ready(function() {
        $('#botao-editar-endereco').on("click", function() {
            $("#etiqueta-endereco-evento").toggle(500);
        });
    });

    $(document).ready(function() {
        $('#botao-editar-modulo-inscricao').on("click", function() {
            $("#etiqueta-modulo-inscricao-evento").toggle(500);
        });
    });

    $(document).ready(function() {
        $('#botao-editar-modulo-programacao').on("click", function() {
            $("#etiqueta-modulo-programacao-evento").toggle(500);
        });
    });

    $(document).ready(function() {
        $('#botao-editar-modulo-organizacao').on("click", function() {
            $("#etiqueta-modulo-organizacao-evento").toggle(500);
        });
    });

    $(document).ready(function() {
        $('#botao-editar-etiqueta-regra').on("click", function() {
            $("#etiqueta-baixar-regra-evento").toggle(500);
        });
    });

    $(document).ready(function() {
        $('#botao-editar-etiqueta-template').on("click", function() {
            $("#etiqueta-baixar-template-evento").toggle(500);
        });
    });
    // Fim

    // Exibir ou ocultar campos de edição de etiquetas de trabalhos
    $(document).ready(function() {
        $('#botao-editar-titulo').on("click", function() {
            $("#etiqueta-titulo-trabalho").toggle(500);
        });
    });

    $(document).ready(function() {
        $('#botao-editar-autor').on("click", function() {
            $("#etiqueta-autor-trabalho").toggle(500);
        });
    });

    $(document).ready(function() {
        $('#botao-editar-coautor').on("click", function() {
            $("#etiqueta-coautor-trabalho").toggle(500);
        });
    });

    $(document).ready(function() {
        $('#botao-editar-resumo').on("click", function() {
            $("#etiqueta-resumo-trabalho").toggle(500);
        });
    });

    $(document).ready(function() {
        $('#botao-editar-area').on("click", function() {
            $("#etiqueta-area-trabalho").toggle(500);
        });
    });

    $(document).ready(function() {
        $('#botao-editar-upload').on("click", function() {
            $("#etiqueta-upload-trabalho").toggle(500);
        });
    });

    $(document).ready(function() {
        $('#botao-editar-regras').on("click", function() {
            $("#etiqueta-regras-trabalho").toggle(500);
        });
    });

    $(document).ready(function() {
        $('#botao-editar-templates').on("click", function() {
            $("#etiqueta-templates-trabalho").toggle(500);
        });
    });
    // Fim

  function trabalhoId(x){
    document.getElementById('trabalhoIdAjax').value = x;
  }

  function modalidadeId(x){
    document.getElementById('modalidadeIdAjax').value = x;
  }

  $(function(){
    $('#areas').click(function(){
        $('#dropdownAreas').slideToggle(200);
    });
    $('#revisores').click(function(){
            $('#dropdownRevisores').slideToggle(200);
    });
    $('#comissao').click(function(){
            $('#dropdownComissao').slideToggle(200);
    });
    $('#modalidades').click(function(){
            $('#dropdownModalidades').slideToggle(200);
    });
    $('#eventos').click(function(){
            $('#dropdownEvento').slideToggle(200);
    });
    $('#trabalhos').click(function(){
            $('#dropdownTrabalhos').slideToggle(200);
    });
    $('.botaoAjax').click(function(e){
       e.preventDefault();
       $.ajaxSetup({
          headers: {
              // 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
              'Content-Type': 'application/json',
              'X-Requested-With': 'XMLHttpRequest'
          }
      });
       jQuery.ajax({
          url: "{{ route('detalhesTrabalho') }}",
          method: 'get',
          data: {
             // name: jQuery('#name').val(),
             // type: jQuery('#type').val(),
             // price: jQuery('#price').val()
             trabalhoId: $('#trabalhoIdAjax').val()
          },
          success: function(result){
            console.log(result);
            // result = JSON.parse(result[0]);
            // console.log(result.titulo);
            $('#tituloTrabalhoAjax').html(result.titulo);
            $('#resumoTrabalhoAjax').html(result.resumo);
            $('#distribuicaoManualTrabalhoId').val($('#trabalhoIdAjax').val());
            $('#removerRevisorTrabalhoId').val($('#trabalhoIdAjax').val());
            // console.log(result.revisores);
            var container = $('#cblist');
            container.empty();
            result.revisores.forEach(addCheckbox);
            var container = $('#selectRevisorTrabalho');
            container.empty();
            addDisabledOptionToSelect();
            result.revisoresDisponiveis.forEach(addOptionToSelect);

          }});
          jQuery.ajax({
          url: "{{ route('findModalidade') }}",
          method: 'get',
          data: {
             modalidadeId: $('#modalidadeIdAjax').val()
          },
          success: function(result){
            console.log(result);
            // document.getElementById('nomeModalidadeEdit').value = result.nome;
            $('#modalidadeEditId').val(result.id);
            $('#nomeModalidadeEdit').val(result.nome);
            $('#inicioSubmissaoEdit').val(result.inicioSubmissao);
            $('#fimSubmissaoEdit').val(result.fimSubmissao);
            $('#inicioRevisaoEdit').val(result.inicioRevisao);
            $('#fimRevisaoEdit').val(result.fimRevisao);
            $('#inicioResultadoEdit').val(result.inicioResultado);
            

            if(result.caracteres == true){
                $('#id-limit-custom_field-accountEdit-1-1').prop('checked', true);
                $("#min-max-caracteresEdit").show();
                $("#min-max-palavrasEdit").hide();
            }   
            if(result.palavras == true){
                $('#id-limit-custom_field-accountEdit-1-2').prop('checked', true);
                $("#min-max-caracteresEdit").hide();
                $("#min-max-palavrasEdit").show();
            }            
            $('#maxcaracteresEdit').val(result.maxcaracteres);
            $('#mincaracteresEdit').val(result.mincaracteres);
            $('#maxpalavrasEdit').val(result.maxpalavras);
            $('#minpalavrasEdit').val(result.minpalavras);


            if(result.arquivo == true){

                $('#id-custom_field-accountEdit-1-2').prop('checked', true);
                $("#area-templateEdit").show();
                $("#tipo-arquivoEdit").show();
            }

            if(result.pdf == true){

                $('#pdfEdit').prop('checked', true);
            }
            if(result.jpg == true){

                $('#jpgEdit').prop('checked', true);
            }
            if(result.jpeg == true){

                $('#jpegEdit').prop('checked', true);
            }
            if(result.png == true){

                $('#pngEdit').prop('checked', true);
            }
            if(result.docx == true){

                $('#docxEdit').prop('checked', true);
            }
            if(result.odt == true){

                $('#odtEdit').prop('checked', true);
            }
          }});
       });

    $('#areaIdformDistribuicaoPorArea').change(function () {
      $.ajaxSetup({
         headers: {
             // 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
             'Content-Type': 'application/json',
             'X-Requested-With': 'XMLHttpRequest'
         }
      });
      jQuery.ajax({
         url: "{{ route('numeroDeRevisoresAjax') }}",
         method: 'get',
         data: {
            // name: jQuery('#name').val(),
            // type: jQuery('#type').val(),
            // price: jQuery('#price').val()
            areaId: $('#areaIdformDistribuicaoPorArea').val()
         },
         success: function(result){
           if(result == 0){
             $('#numeroDeRevisoresPorTrabalhoButton').prop('disabled', true);
             alert("Não existem revisores nessa área.");
           }
           else{
             if($('#numeroDeRevisoresPorTrabalhoInput').val() < 1){
               $('#numeroDeRevisoresPorTrabalhoButton').prop('disabled', true);
             }
             else{
               $('#numeroDeRevisoresPorTrabalhoButton').prop('disabled', false);
             }
           }
           // $('#tituloTrabalhoAjax').html(result.titulo);
           // $('#resumoTrabalhoAjax').html(result.resumo);
           // $("h1, h2, p").toggleClass("blue");
         }});
    });
    $('#numeroDeRevisoresPorTrabalhoInput').on("input", function (){
      if($('#numeroDeRevisoresPorTrabalhoInput').val() < 1){
        $('#numeroDeRevisoresPorTrabalhoButton').prop('disabled', true);
      }
      else{
        $('#numeroDeRevisoresPorTrabalhoButton').prop('disabled', false);
      }
    });
  });




    function myFunction(item, index) {
      // document.getElementById("demo").innerHTML += index + ":" + item + "<br>";
      console.log(index);
      console.log(item.id);
    }

    function addCheckbox(item) {
       var container = $('#cblist');
       var inputs = container.find('input');
       var id = inputs.length+1;

       var linha = "<div class="+"row"+">"+
                    "<div class="+"col-sm-12"+">"+
                    "<input type="+"checkbox"+" id="+"cb"+id+" name="+"revisores[]"+" value="+item.id+">"+
                    "<label for="+"cb"+id+" style="+"margin-left:10px"+">"+item.nomeOuEmail+"</label>"+
                    "</div>"+
                    "</div>";
       $('#cblist').append(linha);
    }
    function addOptionToSelect(item) {
       var container = $('#selectRevisorTrabalho');
       var inputs = container.find('option');
       var id = inputs.length+1;

       var linha = "<option value="+item.id+">"+item.nomeOuEmail+"</option>";
       $('#selectRevisorTrabalho').append(linha);
    }
    function addDisabledOptionToSelect() {
       var container = $('#selectRevisorTrabalho');
       var inputs = container.find('option');

       var linha = "<option value='' disabled selected hidden> Novo Revisor </option>";
       $('#selectRevisorTrabalho').append(linha);
    }

    function cadastrarCoodComissao(){

            document.getElementById("formCoordComissao").submit();
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

        listarTrabalhos = document.getElementById('divListarTrabalhos');
        submissoesTrabalhos = document.getElementById('divDefinirSubmissoes');

        classificacao = document.getElementById('divClassificacao');
        atividades = document.getElementById('divAtividades');
        cadastrarAreas = document.getElementById('divCadastrarAreas');
        listarAreas = document.getElementById('divListarAreas');
        cadastrarRevisores = document.getElementById('divCadastrarRevisores');
        listarRevisores = document.getElementById('divListarRevisores');

        cadastrarComissao = document.getElementById('divCadastrarComissao');
        definirCoordComissao = document.getElementById('divDefinirCoordComissao');
        listarComissao = document.getElementById('divListarComissao');

        cadastrarModalidade = document.getElementById('divCadastrarModalidades');
        listarModalidade = document.getElementById('divListarModalidades');

        editarEtiqueta = document.getElementById('divEditarEtiquetas'); //Etiquetas do card de eventos
        editarEtiquetaSubTrabalhos = document.getElementById('divEditarEtiquetasSubTrabalho');

        // habilita divInformacoes
        if(id == 'informacoes'){
            console.log('informacoes');
            informacoes.style.display = "block";
            listarTrabalhos.style.display = "none";
            submissoesTrabalhos.style.display = "none";
            classificacao.style.display = "none";
            atividades.style.display = "none";
            cadastrarAreas.style.display = "none";
            listarAreas.style.display = "none";
            cadastrarRevisores.style.display = "none";
            listarRevisores.style.display = "none";
            cadastrarComissao.style.display = "none";
            definirCoordComissao.style.display = "none";
            listarComissao.style.display = "none";
            cadastrarModalidade.style.display = "none";
            listarModalidade.style.display = "none";
            editarEtiqueta.style.display = "none";
            editarEtiquetaSubTrabalhos.style.display = "none";
        }
        if(id == 'listarTrabalhos'){
            console.log('listarTrabalhos');
            informacoes.style.display = "none";
            listarTrabalhos.style.display = "block";
            submissoesTrabalhos.style.display = "none";
            classificacao.style.display = "none";
            atividades.style.display = "none";
            cadastrarAreas.style.display = "none";
            listarAreas.style.display = "none";
            cadastrarRevisores.style.display = "none";
            listarRevisores.style.display = "none";
            cadastrarComissao.style.display = "none";
            definirCoordComissao.style.display = "none";
            listarComissao.style.display = "none";
            cadastrarModalidade.style.display = "none";
            listarModalidade.style.display = "none";
            editarEtiqueta.style.display = "none";
            editarEtiquetaSubTrabalhos.style.display = "none";
        }

        if(id == 'modalidades'){
            console.log('modalidades');
            informacoes.style.display = "none";
            listarTrabalhos.style.display = "none";
            submissoesTrabalhos.style.display = "none";
            classificacao.style.display = "none";
            atividades.style.display = "none";
            cadastrarAreas.style.display = "none";
            listarAreas.style.display = "none";
            cadastrarRevisores.style.display = "none";
            listarRevisores.style.display = "none";
            cadastrarComissao.style.display = "none";
            definirCoordComissao.style.display = "none";
            listarComissao.style.display = "none";
            cadastrarModalidade.style.display = "none";
            listarModalidade.style.display = "none";
            editarEtiqueta.style.display = "none";
            editarEtiquetaSubTrabalhos.style.display = "none";

        }
        if(id == 'colocacao'){
            console.log('colocacao');
            informacoes.style.display = "none";
            listarTrabalhos.style.display = "none";
            submissoesTrabalhos.style.display = "none";
            classificacao.style.display = "block";
            atividades.style.display = "none";
            cadastrarAreas.style.display = "none";
            listarAreas.style.display = "none";
            cadastrarRevisores.style.display = "none";
            listarRevisores.style.display = "none";
            cadastrarComissao.style.display = "none";
            definirCoordComissao.style.display = "none";
            listarComissao.style.display = "none";
            cadastrarModalidade.style.display = "none";
            listarModalidade.style.display = "none";
            editarEtiqueta.style.display = "none";
            editarEtiquetaSubTrabalhos.style.display = "none";

        }
        if(id == 'atividades'){
            console.log('atividades');
            informacoes.style.display = "none";
            listarTrabalhos.style.display = "none";
            submissoesTrabalhos.style.display = "none";
            classificacao.style.display = "none";
            atividades.style.display = "block";
            cadastrarAreas.style.display = "none";
            listarAreas.style.display = "none";
            cadastrarRevisores.style.display = "none";
            listarRevisores.style.display = "none";
            cadastrarComissao.style.display = "none";
            definirCoordComissao.style.display = "none";
            listarComissao.style.display = "none";
            cadastrarModalidade.style.display = "none";
            listarModalidade.style.display = "none";
            editarEtiqueta.style.display = "none";
            editarEtiquetaSubTrabalhos.style.display = "none";

        }
        if(id == 'cadastrarAreas'){
            console.log('cadastrarAreas');
            informacoes.style.display = "none";
            listarTrabalhos.style.display = "none";
            submissoesTrabalhos.style.display = "none";
            classificacao.style.display = "none";
            atividades.style.display = "none";
            cadastrarAreas.style.display = "block";
            listarAreas.style.display = "none";
            cadastrarRevisores.style.display = "none";
            listarRevisores.style.display = "none";
            cadastrarComissao.style.display = "none";
            definirCoordComissao.style.display = "none";
            listarComissao.style.display = "none";
            cadastrarModalidade.style.display = "none";
            listarModalidade.style.display = "none";
            editarEtiqueta.style.display = "none";
            editarEtiquetaSubTrabalhos.style.display = "none";

        }
        if(id == 'listarAreas'){
            console.log('listarAreas');
            informacoes.style.display = "none";
            listarTrabalhos.style.display = "none";
            submissoesTrabalhos.style.display = "none";
            classificacao.style.display = "none";
            atividades.style.display = "none";
            cadastrarAreas.style.display = "none";
            listarAreas.style.display = "block";
            cadastrarRevisores.style.display = "none";
            listarRevisores.style.display = "none";
            cadastrarComissao.style.display = "none";
            definirCoordComissao.style.display = "none";
            listarComissao.style.display = "none";
            cadastrarModalidade.style.display = "none";
            listarModalidade.style.display = "none";
            editarEtiqueta.style.display = "none";
            editarEtiquetaSubTrabalhos.style.display = "none";

        }

        if(id == 'cadastrarRevisores'){
            console.log('cadastrarRevisores');
            informacoes.style.display = "none";
            listarTrabalhos.style.display = "none";
            submissoesTrabalhos.style.display = "none";
            classificacao.style.display = "none";
            atividades.style.display = "none";
            cadastrarAreas.style.display = "none";
            listarAreas.style.display = "none";
            cadastrarRevisores.style.display = "block";
            listarRevisores.style.display = "none";
            cadastrarComissao.style.display = "none";
            definirCoordComissao.style.display = "none";
            listarComissao.style.display = "none";
            cadastrarModalidade.style.display = "none";
            listarModalidade.style.display = "none";
            editarEtiqueta.style.display = "none";
            editarEtiquetaSubTrabalhos.style.display = "none";

        }
        if(id == 'listarRevisores'){
            console.log('listarRevisores');
            informacoes.style.display = "none";
            listarTrabalhos.style.display = "none";
            submissoesTrabalhos.style.display = "none";
            classificacao.style.display = "none";
            atividades.style.display = "none";
            cadastrarAreas.style.display = "none";
            listarAreas.style.display = "none";
            cadastrarRevisores.style.display = "none";
            listarRevisores.style.display = "block";
            cadastrarComissao.style.display = "none";
            definirCoordComissao.style.display = "none";
            listarComissao.style.display = "none";
            cadastrarModalidade.style.display = "none";
            listarModalidade.style.display = "none";
            editarEtiqueta.style.display = "none";
            editarEtiquetaSubTrabalhos.style.display = "none";

        }
        if(id == 'cadastrarComissao'){
            console.log('cadastrarComissao');
            informacoes.style.display = "none";
            listarTrabalhos.style.display = "none";
            submissoesTrabalhos.style.display = "none";
            classificacao.style.display = "none";
            atividades.style.display = "none";
            cadastrarAreas.style.display = "none";
            listarAreas.style.display = "none";
            cadastrarRevisores.style.display = "none";
            listarRevisores.style.display = "none";
            cadastrarComissao.style.display = "block";
            definirCoordComissao.style.display = "none";
            listarComissao.style.display = "none";
            cadastrarModalidade.style.display = "none";
            listarModalidade.style.display = "none";
            editarEtiqueta.style.display = "none";
            editarEtiquetaSubTrabalhos.style.display = "none";

        }
        if(id == 'definirCoordComissao'){
            console.log('definirCoordComissao');
            informacoes.style.display = "none";
            listarTrabalhos.style.display = "none";
            submissoesTrabalhos.style.display = "none";
            classificacao.style.display = "none";
            atividades.style.display = "none";
            cadastrarAreas.style.display = "none";
            listarAreas.style.display = "none";
            cadastrarRevisores.style.display = "none";
            listarRevisores.style.display = "none";
            cadastrarComissao.style.display = "none";
            definirCoordComissao.style.display = "block";
            listarComissao.style.display = "none";
            cadastrarModalidade.style.display = "none";
            listarModalidade.style.display = "none";
            editarEtiqueta.style.display = "none";
            editarEtiquetaSubTrabalhos.style.display = "none";
        }
        if(id == 'listarComissao'){
            console.log('listarComissao');
            informacoes.style.display = "none";
            listarTrabalhos.style.display = "none";
            submissoesTrabalhos.style.display = "none";
            classificacao.style.display = "none";
            atividades.style.display = "none";
            cadastrarAreas.style.display = "none";
            listarAreas.style.display = "none";
            cadastrarRevisores.style.display = "none";
            listarRevisores.style.display = "none";
            cadastrarComissao.style.display = "none";
            definirCoordComissao.style.display = "none";
            listarComissao.style.display = "block";
            cadastrarModalidade.style.display = "none";
            listarModalidade.style.display = "none";
            editarEtiqueta.style.display = "none";
            editarEtiquetaSubTrabalhos.style.display = "none";
        }
        if(id == 'cadastrarModalidade'){
            console.log('cadastrarModalidade');
            informacoes.style.display = "none";
            listarTrabalhos.style.display = "none";
            submissoesTrabalhos.style.display = "none";
            classificacao.style.display = "none";
            atividades.style.display = "none";
            cadastrarAreas.style.display = "none";
            listarAreas.style.display = "none";
            cadastrarRevisores.style.display = "none";
            listarRevisores.style.display = "none";
            cadastrarComissao.style.display = "none";
            definirCoordComissao.style.display = "none";
            listarComissao.style.display = "none";
            cadastrarModalidade.style.display = "block";
            listarModalidade.style.display = "none";
            editarEtiqueta.style.display = "none";
            editarEtiquetaSubTrabalhos.style.display = "none";
        }
        if(id == 'listarModalidade'){
            console.log('listarModalidade');
            informacoes.style.display = "none";
            listarTrabalhos.style.display = "none";
            submissoesTrabalhos.style.display = "none";
            classificacao.style.display = "none";
            atividades.style.display = "none";
            cadastrarAreas.style.display = "none";
            listarAreas.style.display = "none";
            cadastrarRevisores.style.display = "none";
            listarRevisores.style.display = "none";
            cadastrarComissao.style.display = "none";
            definirCoordComissao.style.display = "none";
            listarComissao.style.display = "none";
            cadastrarModalidade.style.display = "none";
            listarModalidade.style.display = "block";
            editarEtiqueta.style.display = "none";
            editarEtiquetaSubTrabalhos.style.display = "none";
        }
        if(id == 'submissoesTrabalhos'){
          informacoes.style.display = "none";
          listarTrabalhos.style.display = "none";
          submissoesTrabalhos.style.display = "block";
          classificacao.style.display = "none";
          atividades.style.display = "none";
          cadastrarAreas.style.display = "none";
          listarAreas.style.display = "none";
          cadastrarRevisores.style.display = "none";
          listarRevisores.style.display = "none";
          cadastrarComissao.style.display = "none";
          definirCoordComissao.style.display = "none";
          listarComissao.style.display = "none";
          cadastrarModalidade.style.display = "none";
          listarModalidade.style.display = "none";
          editarEtiqueta.style.display = "none";
          editarEtiquetaSubTrabalhos.style.display = "none";
        }

        if(id == 'editarEtiqueta'){
            console.log('editarEtiqueta');
            informacoes.style.display = "none";
            listarTrabalhos.style.display = "none";
            submissoesTrabalhos.style.display = "none";
            classificacao.style.display = "none";
            atividades.style.display = "none";
            cadastrarAreas.style.display = "none";
            listarAreas.style.display = "none";
            cadastrarRevisores.style.display = "none";
            listarRevisores.style.display = "none";
            cadastrarComissao.style.display = "none";
            definirCoordComissao.style.display = "none";
            listarComissao.style.display = "none";
            cadastrarModalidade.style.display = "none";
            listarModalidade.style.display = "none";
            editarEtiqueta.style.display = "block";
            editarEtiquetaSubTrabalhos.style.display = "none";
        }

        if(id == 'editarEtiquetaSubTrabalhos'){
            informacoes.style.display = "none";
            listarTrabalhos.style.display = "none";
            submissoesTrabalhos.style.display = "none";
            classificacao.style.display = "none";
            atividades.style.display = "none";
            cadastrarAreas.style.display = "none";
            listarAreas.style.display = "none";
            cadastrarRevisores.style.display = "none";
            listarRevisores.style.display = "none";
            cadastrarComissao.style.display = "none";
            definirCoordComissao.style.display = "none";
            listarComissao.style.display = "none";
            cadastrarModalidade.style.display = "none";
            listarModalidade.style.display = "none";
            editarEtiqueta.style.display = "none";
            editarEtiquetaSubTrabalhos.style.display = "block";
        }

    }



  </script>

@endsection
