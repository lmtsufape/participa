@extends('layouts.error')
@section('content')
    <span class="font-25">404 | {{ __('Not Found') }}</span>
    @isset($exception)
        <p><span class="font-25">{{ $exception->getMessage() }}</span></p>
    @endisset
@endsection
