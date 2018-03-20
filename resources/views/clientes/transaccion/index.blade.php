@extends('adminlte::page')

@section('title', 'Transacciones')

@section('content_header')
    <h1>Transacciones <small> Panel de control</small></h1>
    @component('clientes/transaccion/breadcrumbs')
    @endcomponent
@stop

@section('adminlte_js')
@stop

@section('content')

    @can('listar transacciones clientes')
        <div class="row">
            <!-- Monto de transacciones en el día -->
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>$ {{ number_format($aTotalTransaccionesDia['monto']) }}</h3>
                        <p>Monto transaccionado en el día</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-money"></i>
                    </div>
                </div>
            </div>

            <!-- Número de transacciones en el día -->
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>{{ number_format($aTotalTransaccionesDia['total']) }}</h3>
                        <p>Transacciones en el día</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-exchange"></i>
                    </div>
                </div>
            </div>

            <!-- Número de transacciones en el mes -->
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>$ {{ number_format($aTotalTransaccionesMes['monto']) }}</h3>
                        <p>Monto transaccionado en el mes</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-money"></i>
                    </div>
                </div>
            </div>

            <!-- Número de transacciones en el mes -->
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>{{ number_format($aTotalTransaccionesMes['total']) }}</h3>
                        <p>Transacciones en el mes</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-exchange"></i>
                    </div>
                </div>
            </div>
        </div>

        @include('clientes/partials/datatables')
        <div class="row">
            <section class="col-lg-12 connectedSortable ui-sortable">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Lista de transacciones</h3>
                    </div>
                    <div class="box-body" id="table1">
                        <table id="example" class="table table-striped table-bordered responsive" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th data-priority="1">UUID</th>
                                <th data-priority="3">Comercio</th>
                                <th>Prueba</th>
                                <th>Operacion</th>
                                <th>Monto</th>
                                <th data-priority="2">Estatus</th>
                                <th>Creado</th>
                                <th> </th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </section>
        </div>
        <script>
            jQuery(function($){
                // Inicializa tabla de datos
                var dtable = $('#example').DataTable({
                    "ajax": {
                        "url": "/clientes/api/transaccion",
                        "dataSrc": "data.transaccion.data",
                        "data": function (d) {
                            console.log(d);
                            var request_data = {};
                            request_data.per_page = d.length;
                            request_data.page = Math.ceil(d.start / d.length) + 1;
                            request_data.order = d.columns[d.order[0].column].data;
                            request_data.sort = d.order[0].dir;
                            request_data.search = d.search.value;
                            return request_data;
                        },
                        "dataFilter": function(response_data){
                            //console.log(response_data);
                            var d = jQuery.parseJSON(response_data);
                            d.recordsTotal = d.data.transaccion.total;
                            d.recordsFiltered = d.data.transaccion.total;
                            return JSON.stringify(d);
                        }
                    },
                    columns: [
                        {data: 'uuid'},
                        {data: 'comercio_uuid'},
                        {data: 'prueba', render: function (d) { if (d===1) { return 'Si</span>'; } else {return 'No</span>';} } },
                        {data: 'operacion'},
                        {data: 'monto', render: $.fn.dataTable.render.number(',', '.', 2, '$')},
                        {data: 'estatus', render: function(d) { return '<i class="btn btn-primary btn-xs" style="border-color:' + d.color + '; background-color:' + d.color + '" role="button">' + d.nombre + '</i>'; }},
                        {data: 'created_at', render: function (d) { if(d) { return d.split(" ")[0] } else { return null } } },
                        {data: null, orderable: false, render: function (d) { return '<a href="{{ route('transaccion.index') }}/' + d.uuid + '" class="btn btn-primary btn-xs" role="button"><i class="fa fa-eye"></i> Detalles</a>'; } }
                    ],
                    // Opciones iguales en todas las tablas.
                    "order": [[6, "desc"]],
                    "responsive": true,
                    "pageLength": 25,
                    "serverSide": true,
                    "searchDelay": 1500,
                    @include('clientes/partials/datatables_lang')
                });
                // Solo envía el filtro al darle "enter".
                // Corrige error de dataTables que envía el primer caracter como búsqueda al servidor.
                $("#table1 div.dataTables_filter input").unbind();
                $("#table1 div.dataTables_filter input").keyup(function (e) {
                    if (e.keyCode == 13) {
                        dtable.search( this.value ).draw();
                    }
                });
            });
        </script>
    @else
        <div class="alert alert-danger alert-dismissible">
            <h4><i class="icon fa fa-ban"></i> Restringido</h4>
            No cuenta con los permisos necesarios para poder ver este recurso.
        </div>
    @endcan
@stop