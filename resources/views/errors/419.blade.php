@extends('layouts.error')
@section('content')
    <span class="font-25">419 | {{__('Page Expired')}}</span>
    @isset($exception)
        <p><span class="font-25">{{$exception->getMessage()}}</span></p>
    @endisset
@endsection
