@extends('layouts.error')
@section('content')
    <span class="font-25">429 | {{__('Too Many Requests')}}</span>
    @isset($exception)
        <p><span class="font-25">{{$exception->getMessage()}}</span></p>
    @endisset
@endsection