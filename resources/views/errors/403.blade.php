@extends('layouts.error')
@section('content')
    <span class="font-25">403 | {{__('This action is unauthorized.')}}</span>
    @isset($exception)
        <p><span class="font-25">{{$exception->getMessage()}}</span></p>
    @endisset
@endsection
