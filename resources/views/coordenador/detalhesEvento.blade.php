@extends('layouts.app')
@section('sidebar')
<div class="wrapper">
    <div class="sidebar">
        <h2>{{{$evento->nome}}}</h2>
        <ul>
            <li><a id="informacoes" onclick="showInformacoes();">
                <img src="{{asset('img/icons/info-circle-solid.svg')}}" alt=""> <h5> Informações</h5></a>
            </li>
            <li><a id="areas" onclick="showAreas();">
                <img src="{{asset('img/icons/file-alt-regular.svg')}}" alt=""> <h5> Áreas Tématicas</h5></a>
            </li>
            <li><a id="trabalhos" href="">
                <img src="{{asset('img/icons/file-alt-regular.svg')}}" alt=""><h5>Trabalhos</h5></a>
            </li>
            <li><a id="revisores" onclick="showRevisores();">
                <img src="{{asset('img/icons/user-tie-solid.svg')}}" alt=""><h5>Revisores</h5></a>
            </li>
            <li><a id="candidatos" href="">
                <img src="{{asset('img/icons/user-solid.svg')}}" alt=""><h5>Candidatos</h5></a>
            </li>
            <li><a id="colocacao" href="">
                <img src="{{asset('img/icons/trophy-solid.svg')}}" alt=""><h5>Colocação</h5></a>
            </li>
            <li><a id="atividades" href="">
                <img src="{{asset('img/icons/calendar-alt-solid.svg')}}" alt=""><h5>Atividades</h5></a>
            </li>
        </ul>
    </div>


</div>
@endsection
@section('content')
<!-- Informações -->
<div class="main_content" id="informacoesDetalhes" style="display: none">
    <div class="informacoes">
        <h1>Informações</h1>
    </div>
</div>
<!-- Área -->
<div class="container" id="areasDetalhes" style="display: none">
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
<div class="container" id="revisoresDetalhes" style="display: none">
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

@endsection
@section('javascript')
  <script type="text/javascript" >
    function showInformacoes(){
      document.getElementById('informacoesDetalhes').style.display = "block";
      document.getElementById('areasDetalhes').style.display = "none";
      document.getElementById('revisoresDetalhes').style.display = "none";
    }
    function showAreas(){
      document.getElementById('informacoesDetalhes').style.display = "none";
      document.getElementById('areasDetalhes').style.display = "block";
      document.getElementById('revisoresDetalhes').style.display = "none";
    }
    function showRevisores(){
      document.getElementById('informacoesDetalhes').style.display = "none";
      document.getElementById('areasDetalhes').style.display = "none";
      document.getElementById('revisoresDetalhes').style.display = "block";
    }
  </script>

@endsection
