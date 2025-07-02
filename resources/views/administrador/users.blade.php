@extends('layouts.app')

@section('content')

<div class="container position-relative">
    <div class="row justify-content-center">
        <div class="col-auto mr-auto">
            <h2>
                {{ __('Usuários') }}
            </h2>
        </div>
        <div class="col-auto">
            <form class="form-inline my-2 my-lg-0" method="POST" action="{{ route('admin.search') }}">
                @csrf
                <input class="form-control mr-sm-2" type="search" name="search" placeholder="Nome ou email" aria-label="Buscar">
                <button class="btn btn-outline-info my-2 my-sm-0 " type="submit">{{ __('Buscar') }}</button>
                <a href="{{ route('admin.users') }}" class="btn btn-outline-success my-2 my-sm-0 ml-1" type="button">Inicial</a>
            </form>
        </div>
    </div>

    <br>

    <div class="row justify-content-center mb-4">
        <div class="col-auto mr-auto"></div>

        <a href="{{ route('register', app()->getLocale()) }}" class="btn btn-outline-success my-2 my-sm-0 ml-1" type="button">{{ __('Cadastrar usuário') }}</a>
    </div>

    @if (session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
    @endif
    <div class="row justify-content-center d-flex align-items-center">
        @include('tables.table_users', ['users' => $users])
    </div>
</div>

@endsection