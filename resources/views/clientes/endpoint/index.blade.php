@extends('adminlte::page')

@section('title', 'Endpoints')

@section('content_header')
    <h1>Endpoints <small> Panel de control</small></h1>
    @component('clientes/endpoint/breadcrumbs')
    @endcomponent
@stop

@section('adminlte_js')
@stop

@section('content')

    @can('listar transacciones clientes')
        

        @include('clientes/partials/datatables')
        <div class="row">
            <section class="col-lg-12 connectedSortable ui-sortable">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Lista de Endpoints</h3>
                        <div class="box-tools">
                            <a href="{{ route('clientes.endpoint.create') }}" role="button" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Crear endpoint</a>
                        </div>
                    </div>
                    <div class="box-body" id="table1">
                        <table id="example" class="table table-striped table-bordered responsive" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th data-priority="1">URL</th>
                                <th>Es Activo</th>
                                <th>Es Valido</th>
                                <th data-priority="3">Intentos</th>
                                <th data-priority="2">UUID</th>
                                <th>Eventos</th>
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
                        "url": "/clientes/list",
                        "dataSrc": "data.endpoint.data",
                        "data": function (d) {
                            //console.log(d);
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
                            d.recordsTotal = d.data.endpoint.total;
                            d.recordsFiltered = d.data.endpoint.total;
                            return JSON.stringify(d);
                        }
                    },
                    columns: [
                        {data: 'url'},
                        {data: 'es_activo', render: function (d) { if (d===1) { return 'Si</span>'; } else {return 'No</span>';} } },
                        {data: 'es_valido', render: function (d) { if (d===1) { return 'Si</span>'; } else {return 'No</span>';} } },
                        {data: 'max_intentos'},
                        {data: 'comercio_uuid'},
                        {data: 'num_eventos'},
                        {data: 'created_at', render: function (d) { if(d) { return d.split(" ")[0] } else { return null } } },
                        {data: null, orderable: false, render: function (d) { return '<a href="{{ route('clientes.endpoint.index') }}/' + d.id + '" class="btn btn-primary btn-xs" role="button"><i class="fa fa-eye"></i> Detalles</a>'; } }
                    ],
                    // Opciones iguales en todas las tablas.
                    "order": [[5, "desc"]],
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