@extends('layouts.app')

@section('content')

<div class="container"  style="position: relative; top: 100px;">

    <div class="row justify-content-center">
        <div class="col-auto">
            <h2 >
                Editar usuário: {{ $user->name }}
            </h2>
        </div>
    </div>
    <br>
    <div class="row justify-content-md-center">
        <div class="col-md-6">
            <form method="POST" action="{{ route('admin.updateUser', ['id' => $user->id]) }}">
                @csrf
                <div class="form-group">
                    <label for="exampleInputEmail1">Nome</label>
                    <input type="text" name="name" value="{{ $user->name }}" class="form-control"  aria-describedby="namelHelp">

                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Email</label>
                    <input type="email" name="email" value="{{ $user->email }}" class="form-control"  aria-describedby="emailHelp">

                </div>
                <div class="row">
                    <div class="col-auto mr-auto">
                        <button type="submit"  class="btn btn-primary">Atualizar</button>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('admin.users') }}"  class="btn btn-danger">Cancelar</a>
                    </div>
                </div>


            </form>
        </div>

    </div>
</div>

@endsection
