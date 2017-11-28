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
        <h4>{{ empty($subtitulo) ? "Error" : $subtitulo }}</h4>
        <p>
            {{ empty($mensaje) ? "Se encontró un error en la aplicación." : $mensaje }}
        </p>
        {{ empty($extra) ? "" : $extra }}
    </div>

@stop
