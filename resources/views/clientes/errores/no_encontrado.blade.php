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
        <h4>{{ empty($subtitulo) ? "No existe" : $subtitulo }}</h4>
        <p>
            {{ empty($mensaje) ? "El recurso solicitado no existe." : $mensaje }}
        </p>
        {{ empty($extra) ? "" : $extra }}
    </div>

@stop
