@extends('adminlte::page')

@section('title', 'ClaroPay - Clientes')

@section('content_header')
    <h1>Dashboard <small> Panel de control</small></h1>
    @component('clientes/breadcrumbs')
    @endcomponent
@stop

@section('content')
<div class="row">

    <!-- Número y monto de transacciones en el mes -->
    @can('listar transacciones clientes')
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-green">
            <div class="inner">
                <h3>$ {{ number_format($aTotalTransaccionesDia['monto']) }}</h3>
                <p>Monto transaccionado en el día</p>
            </div>
            <div class="icon">
                <i class="fa fa-money"></i>
            </div>
            <a href="{{ route('transaccion.index') }}" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>{{ number_format($aTotalTransaccionesDia['total']) }}</h3>
                <p>Transacciones en el día</p>
            </div>
            <div class="icon">
                <i class="fa fa-exchange"></i>
            </div>
            <a href="{{ route('transaccion.index') }}" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    @endcan

    <!-- @todo: Número de tokens del usuario -->
    @can('listar tokens clientes')
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-red">
            <div class="inner">
                <h3>{{ number_format(App\Models\Comercio::count()) }}</h3>
                <p>Comercios</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="{{ route('comercio.index') }}" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    @endcan

    <!-- Número de usuarios en el sistema -->
    @can('listar usuarios')
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>{{ number_format(App\Models\User::count()) }}</h3>
                <p>Usuarios</p>
            </div>
            <div class="icon">
                <i class="ion ion-person"></i>
            </div>
            <a href="{{ route('clientes') }}" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    @endcan

</div>

@include('clientes/partials/charts')
<div class="row">
    <div class="col-md-8">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Transacciones del día</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <canvas id="chartValoresPorHoras" height="95"></canvas>
                <script>
                    <?php
                    // @todo: Obtener datos en tiempo real
                    ?>
                    var ctx = $("#chartValoresPorHoras");
                    var numberWithCommas = function(x) {
                       return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                     };
                    var myLineChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            "labels": <?php echo json_encode($cTransaccionesDiaXHora->pluck('label')->all()) ?>,
                            "datasets": [
                                {"label": "Transacciones", "data":<?php echo json_encode($cTransaccionesDiaXHora->pluck('total')->all()) ?>, "backgroundColor": Chart.helpers.color("#00c0ef").alpha(0.2).rgbString(), "borderColor": "#00c0ef", "borderWidth": 2, "yAxisID": 'Trx', },
                               {"label": "Monto", "data":<?php echo json_encode($cTransaccionesDiaXHora->pluck('monto')->all()) ?>, "backgroundColor": Chart.helpers.color("#f56954").alpha(0.5).rgbString(), "borderColor": "#f56954", "borderWidth": 2, "yAxisID" : 'Monto', },
                            ]
                        },
                        options: {
                            "responsive": true,
                            title: {
                              display: true,
                              text: 'Transacciones y monto de transacción'
                            },
                            "maintainAspectRatio": true,
                            "legend": { "display": true, "position": "bottom" },
                            "tooltips": {
                               mode: 'label',
                               callbacks: {
                                 label: function(tooltipItem, data) {
                                   return data.datasets[tooltipItem.datasetIndex].label + ": " + numberWithCommas(tooltipItem.yLabel);
                                 }
                               }
                            },
                            scales: {
                                xAxes: [{
                                    scaleLabel: {
                                      display: true,
                                      labelString: 'Fecha'
                                    },
                                    ticks: {
                                      maxTicksLimit: 10
                                    }
                                }],
                                yAxes: [{
                                    scaleLabel: {
                                      display: true,
                                      labelString: 'Transacciones'
                                    },
                                    ticks: {
                                      maxTicksLimit: 5,
                                    },
                                    id: 'Trx',
                                    type: 'linear',
                                    position: 'left',
                                }, {
                                scaleLabel: {
                                  display: true,
                                  labelString: 'Monto ($)'
                                },
                                ticks: {
                                    maxTicksLimit: 5,
                                    callback: function(value) {
                                        return numberWithCommas(value);
                                    },
                                },
                                id: 'Monto',
                                type: 'linear',
                                position: 'right'
                              }]
                            }
                        }
                    });
                </script>
            </div>
            <div class="box-footer text-center">
                <a href="{{ route('transaccion.index') }}" class="uppercase">Ver transacciones</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Aceptación de transacciones</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <canvas id="chartTransaccionEstatus" height="200"></canvas>
                <script>
                    // Genera script para gráfica de transaccion aceptada, rechazada y fraude de hoy
                    var ctx = $("#chartTransaccionEstatus");
                    var myLineChart = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            "labels": <?php echo json_encode([$aTrxDET['total']['aceptadas']['label'], $aTrxDET['total']['rechazadas']['label'], $aTrxDET['total']['fraude']['label'], $aTrxDET['total']['otros']['label']]); ?>,
                            "datasets": [{
                                    "data": <?php echo json_encode([$aTrxDET['total']['aceptadas']['cantidad'], $aTrxDET['total']['rechazadas']['cantidad'], $aTrxDET['total']['fraude']['cantidad'], $aTrxDET['total']['otros']['cantidad']]); ?>,
                                    "backgroundColor": ["#00a65a", "#f39c12", "#dd4b39", "#b8b894"]
                            }]
                        },
                        options: {
                            "responsive": true,
                            title: { display: true, text: 'Transacciones' },
                            "maintainAspectRatio": true,
                            "legend": {"display": true, "position": "bottom"}
                        }
                    });
                </script>
            </div>
            <div class="box-footer text-center">
                <a href="{{ route('transaccion.index') }}" class="uppercase">Ver transacciones</a>
            </div>
        </div>
    </div>
</div>

<div class="row">

    <!-- Lista de última actividad en el sistema -->
    @can('listar actividad')
    <div class="col-md-6">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Última actividad</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <table id="example" class="table table-striped" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Descripción</th>
                            <th>Fecha y hora</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (Spatie\Activitylog\Models\Activity::orderBy('created_at', 'desc')->take(7)->get() as $actividad)
                        <tr>
                            <td>{{ __('activity.' . $actividad->description) }}</td>
                            <td>{{ $actividad->created_at }}</td>
                            <td><a href="{{ route('actividad.show', ['id' => $actividad->id]) }}" class="btn btn-primary btn-xs" role="button"><i class="fa fa-eye"></i> Ver</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="box-footer text-center">
                <a href="{{ route('actividad.index') }}" class="uppercase">Ver toda la actividad</a>
            </div>
        </div>
    </div>
    @endcan

    <!-- Lista de últimos usuarios en el sistema -->
    @can('listar usuarios')
    <div class="col-md-6">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Últimos usuarios</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body no-padding">
                <ul class="users-list clearfix">
                    @foreach (App\Models\User::orderBy('created_at', 'desc')->take(8)->get() as $usuario)
                        <li>
                        @if($usuario->avatar)
                            <img src="{{ Auth::user()->avatar }}" alt="{{ $usuario->avatar }}" width="90" height="90" class="clearfix">
                        @else
                            <img src="{{ Gravatar::src($usuario->email) }}" alt="{{ $usuario->avatar }}"  width="90" height="90" class="clearfix"  onerror="this.src='/avatars/users/default.jpg'">
                        @endif
                            <a class="users-list-name" href="{{ route('usuario.show', ['id' => $usuario->id]) }}">{{ $usuario->name }}</a>
                            <span class="users-list-date">{{ $usuario->created_at->diffForHumans() }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="box-footer text-center">
                <a href="{{ route('clientes') }}" class="uppercase">Ver todos los usuarios</a>
            </div>
        </div>
    </div>
    @endcan

</div>

@stop
