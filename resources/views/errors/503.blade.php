@extends('layouts.error')
@section('content')
    <span class="font-25">503 | {{ __('Service Unavailable') }}</span>
    @isset($exception)
        <p><span class="font-25">{{ $exception->getMessage() }}</span></p>
    @endisset
@endsection
