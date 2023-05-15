@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>IPCA</h1>
@stop

@section('content')
    <p>Ainda em desenvolvimento.</p>
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