@extends('adminlte::page')

@section('title', 'Error')

@section('content_header')
    <h1>{{ empty($titulo) ? "Error" : $titulo }} <small> </small></h1>
    @component('clientes/breadcrumbs')
        <li><i class="fa fa-exclamation-triangle"></i> Error</li>
    @endcomponent
@stop

@section('content')

    <div class="callout callout-danger">
        <h4>{{ empty($subtitulo) ? "Excepción" : $subtitulo }}</h4>
        <p>
            {{ empty($mensaje) ? "Error de excepción." : $mensaje }}
        </p>
        {{ empty($extra) ? "" : $extra }}
    </div>

@stop
