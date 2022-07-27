@extends('layouts.app')

@section('content')

<div class="container position-relative">

    <h2>{{ Auth()->user()->name }} - Perfil: Coautor</h2>

    <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">First</th>
            <th scope="col">Last</th>
            <th scope="col">Handle</th>
          </tr>
        </thead>
        <tbody>
            @forelse ($trabalhos as $trabalho)
                <tr>
                <th scope="row">{{$trabalho}}</th>
                <td>Mark</td>
                <td>Otto</td>
                <td>@mdo</td>
                </tr>

            @empty

            @endforelse

        </tbody>
      </table>

</div>

@endsection
