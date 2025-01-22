@extends('color::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('color.name') !!}</p>
@endsection
