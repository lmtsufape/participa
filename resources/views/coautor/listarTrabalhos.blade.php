@extends('layouts.app')

@section('content')

<div class="container position-relative">

  <h2>{{ Auth()->user()->name }} - {{ __('Perfil: Coautor') }}</h2>

  @if ($trabalhos != null && count($trabalhos) > 0)
  <table class="table table-responsive-lg table-hover">
    <thead>
      <tr>
        <th>{{ __('Evento') }}</th>
        <th>{{ __('Título') }}</th>
        <th>{{ __('Autor') }}</th>
        <th style="text-align:center">{{ __('Baixar') }}</th>
      </tr>
    </thead>
    <tbody>
      @foreach($trabalhos as $trabalho)
      <tr>
        <td>{{$trabalho->evento->nome}}</td>
        <td>{{$trabalho->titulo}}</td>
        <td>{{$trabalho->autor->name}}</td>
        <td style="text-align:center">
          @if($trabalho->arquivo()->where('versaoFinal', true)->first() != null && Storage::disk()->exists($trabalho->arquivo()->where('versaoFinal', true)->first()->nome))
          <a href="{{route('downloadTrabalho', ['id' => $trabalho->id])}}" target="_new" style="font-size: 20px; color: #114048ff;">
            <img class="" src="{{asset('img/icons/file-download-solid.svg')}}" style="width:20px">
          </a>
          @else
          <a href="#" onclick="return false;" data-bs-toggle="popover" data-trigger="focus" data-trigger="focus" title="Download não disponível" data-content="Não foi enviado arquivo para este trabalho" style="font-size: 20px; color: #114048ff;">
            <img class="" src="{{asset('img/icons/file-download-solid.svg')}}" style="width:20px">
          </a>
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  @else
  {{ __('Você não participa como coautor em nenhum trabalho...') }}
  @endif

</div>

@endsection
