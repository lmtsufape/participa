@extends('layouts.error')
@section('content')
    <span class="font-25">500 | {{__('Server Error')}}</span>
    @isset($exception)
        <p><span class="font-25">{{$exception->getMessage()}}</span></p>
    @endisset
@endsection