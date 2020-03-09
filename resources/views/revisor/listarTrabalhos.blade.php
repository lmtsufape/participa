@extends('layouts.app')

@section('content')
<div class="container content" style="margin-top:60px">
    <div class="row">
        <div class="col-sm-12">
            <h1 class="titulo-detalhes">Trabalhos</h1>
        </div>
    </div>
    {{-- Tabela Trabalhos --}}
    <div class="row">
        <div class="col-sm-12">
            <table class="table table-hover table-responsive-lg table-sm">
                <thead>
                  <tr>
                    <th scope="col">Código</th>
                    <th scope="col">Área</th>
                    <th scope="col">Baixar</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>1</td>
                    <td>Área</td>
                    <td>
                      <a href="#"><img src="{{asset('img/icons/file-download-solid-black.svg')}}" style="width:20px"></a>
                    </td>
                    

                  </tr>
                </tbody>
              </table>
        </div>

    </div>
</div>
@endsection
