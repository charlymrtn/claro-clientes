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
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>Transacción ID</b> <span class="pull-right"><a href="{{ route('transaccion.index') }}/{{ $transaccion->uuid }}" class="btn btn-primary btn-sm" role="button">{{ $transaccion->uuid }}</a></span>
                            </li>
                            <li class="list-group-item">
                                <b>Autorización</b> <span class="pull-right">{{ $respuesta->autorizacion ?? 'NA'}}</span>
                            </li>
                            @if ($transaccion->prueba)
                                <li class="list-group-item">
                                    <b>Prueba</b> <span class="pull-right">{{ $transaccion->prueba ? 'Si' : 'No'}}</span>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="box" style="border-top-color:{{ $transaccion->estatus->color }};">
                    <div class="box-header">
                        <h3 class="box-title">Resumen</h3>
                    </div>
                    <div class="box-body box-profile">
                        <span class="pull-right">
                            <h3 class="no-margin-top"><span class="label" style="background-color:{{ $transaccion->estatus->color }};">{{ $transaccion->estatus->nombre }}</span></h3>
                        </span>
                        <h1 class="no-margin-top">$ {{ number_format($transaccion->monto, 2) }} <small>{{ $transaccion->moneda->iso_a3 }}</small></h1>
                        <i class="fa fa-calendar"></i> &nbsp; {{ $transaccion->created_at }}
                        <br>&nbsp;
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">Forma de pago</h3>
                    </div>
                    <div class="box-body box-profile">
                        @if ($transaccion->forma_pago == 'tarjeta')
                            @if (!empty($transaccion->datos_pago['marca']))
                                <span class="pull-right">
                                    <h3 class="no-margin-top"><img src="/img/forma_pago/tarjeta/marca-{{ $transaccion->datos_pago['marca'] }}.png" width="75" height=""></h3>
                                </span>
                            @endif
                            <h1 class="no-margin-top">  {{ ucfirst($transaccion->forma_pago ?? 'Desconocida') }}</h1>
                            <i class="fa fa-credit-card"></i> &nbsp; {!! str_replace('*', ' &bull; ', $transaccion->datos_pago['pan'] ?? '-') !!}
                            <br><i class="fa fa-user"></i> &nbsp; {{ $transaccion->datos_pago['nombre'] ?? '-' }}
                        @else
                            {{ ucfirst($transaccion->forma_pago) }}
                        @endif
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
