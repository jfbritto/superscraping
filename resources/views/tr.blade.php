@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>TR</h1>
@stop

@section('content')
    @foreach($resultados as $item)
        {{ $item }}<br>
    @endforeach
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop