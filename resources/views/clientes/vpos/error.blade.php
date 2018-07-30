@extends('adminlte::page')

@section('title', 'vPOS - Resultado')

@section('content_header')
    @component('clientes/vpos/breadcrumbs')
    @endcomponent
@stop

@section('adminlte_css')
    <link rel="stylesheet" type="text/css" href="{{ mix('/css/mix/forms.css') }}">
@stop

@section('adminlte_js')

@stop

@section('content')
    @can('listar vpos clientes')
        <div class="content-header">
            <h1>vPOS - Resultado de cargo</h1>
            <br>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Procesador de pago</h3>
                    </div>
                    <div class="box-body box-profile">
                        Ocurrió un error al procesar la operación.
                    </div>
                </div>
            </div>
        </div>

    @else
        <div class="alert alert-danger alert-dismissible">
            <h4><i class="icon fa fa-ban"></i> Restringido</h4>
            No cuenta con los permisos necesarios para poder ver este recurso.
        </div>
    @endcan
@stop
