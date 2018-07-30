@extends('adminlte::page')

@section('title', 'Tokens')

@section('content_header')
    @component('clientes/token/breadcrumbs')
    @endcomponent
@stop

@section('adminlte_js')
@stop

@section('content')
    @can('ver perfil clientes')

        @include('clientes/partials/datatables')
        <div class="content-header">
            <h1>Tokens de acceso</h1>
            <br>
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Listado de tokens</h3>
                    <div class="box-tools">
                        <a href="{{ route('clientes.token.create') }}" role="button" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Crear token</a>
                    </div>
                </div>
                <div class="box-body"  id="table2">
                    <div class="row">
                        <div class="col-md-12">
                            <table id="tokens" class="table table-striped table-bordered responsive" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th data-priority="1">Nombre</th>
                                    <th data-priority="2">Permisos</th>
                                    <th data-priority="2">Activo</th>
                                    <th> </th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                jQuery(function($){
                    // Inicializa tabla de datos
                    var dtable = $('#tokens').DataTable({
                        "ajax": {
                            "url": "/clientes/api/token",
                            "dataSrc": "data.token.data",
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
                                d.recordsTotal = d.data.token.total;
                                d.recordsFiltered = d.data.token.total;
                                return JSON.stringify(d);
                            }
                        },
                        columns: [
                            {data: 'id', visible: false},
                            {data: 'nombre'},
                            {data: 'permisos'},
                            {data: 'revocado', render: function (d) { if(d) { return '<span class="label label-danger">Revocado</span>' } else { return '<span class="label label-success">Activo</span>'} } },
                            {data: null, orderable: false, render: function (d) { return '<a href="{{ route('clientes.token.index') }}/' + d.id + '" class="btn btn-primary btn-xs" role="button"><i class="fa fa-eye"></i> Detalles</a>'; } }
                        ],
                        // Opciones iguales en todas las tablas.
                        "order": [[0, "desc"]],
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

            <div class="modal fade bd-example-modal-lg" id="client">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                            <h4 class="modal-title">Nuevo cliente</h4>
                        </div>
                        <div class="modal-body">
                            <div class="box-body">
                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-2 control-label">Nombre</label>

                                        <div class="col-sm-10" id="nombre">
                                            <input type="text" required class="form-control" id="name" placeholder="Nombre">
                                            <span id="error" class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group" >
                                        <label for="inputPassword3" required class="col-sm-2 control-label">Redireccionamiento</label>

                                        <div class="col-sm-10" id="url">
                                            <input type="text" class="form-control" id="redirect" placeholder="url">
                                            <span id="error1" class="help-block"></span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cerrar</button>
                            <button type="button" id="guardarCliente" class="btn btn-primary">Guardar</button>
                        </div>

                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal-client -->
            <!-- Modal modificar cliente -->
            <div class="modal fade bd-example-modal-lg" id="modificar">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                            <h4 class="modal-title">Modificar cliente</h4>
                        </div>
                        <div class="modal-body">
                            <div class="box-body">
                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-2 control-label">Nombre</label>

                                        <div class="col-sm-10" id="newnombre">
                                            <input type="text" class="form-control" id="name_mod" placeholder="Nombre" value="">
                                            <span id="error2" class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword3" class="col-sm-2 control-label">Redireccionamiento</label>

                                        <div class="col-sm-10" id="newurl">
                                            <input type="text" class="form-control" id="redirect_mod" placeholder="url" value="">
                                            <span id="error3" class="help-block"></span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cerrar</button>
                            <button type="button" id="guardar_mod" class="btn btn-primary">Guardar</button>
                        </div>

                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal-modificar-cliente -->

            <!-- MODAL confirmacion -->
            <div class="modal modal modal-danger fade in" id="confirmacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4>Eliminar</h4>
                        </div>
                        <div class="modal-body">
                            <h4>¿Realmente desea eliminar?</h4>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">No</button>
                            <button type="button" id="si" class="btn btn-outline">Si</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.modal confirmacion -->
        </div>
        <script>
            $(document).ready(function() {
                $('#guardarCliente').on('click', function (e) {
                    e.preventDefault();
                    var name = $('#name').val();
                    var redirect = $('#redirect').val();
                    if(name == ''){
                        $('#error').html("Campo obligatorio");
                        $('#nombre').addClass('has-error');
                    }
                    if(redirect == ''){
                        $('#error1').html("Campo obligatorio");
                        $('#url').addClass('has-error');
                    }
                    $.ajax({
                        type: "POST",
                        url:'/oauth/clients',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {name: name, redirect: redirect, personal_access_client: true},
                        success: function( msg ) {
                            location.reload();
                        }
                    });
                });
            });
        </script>
        <script>
            function eliminar(id) {
                $('#si').on('click', function () {
                    $.ajax({
                        type: "DELETE",
                        url:'/oauth/clients/' + id,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function( msg ) {
                            location.reload();
                        }
                    });
                });
            }
        </script>
        <script>
            function modificar(id,name,redirect) {
                //console.log(name);
                $('#name_mod').val(name);
                $('#redirect_mod').val(redirect);
                $('#guardar_mod').on('click', function () {
                    var newname= $('#name_mod').val();
                    var newredirect= $('#redirect_mod').val();

                    if(newname == ''){
                        $('#error2').html("Campo obligatorio");
                        $('#newnombre').addClass('has-error');
                    }
                    if(newredirect == ''){
                        $('#error3').html("Campo obligatorio");
                        $('#newurl').addClass('has-error');
                    }
                    $.ajax({
                        type: "PUT",
                        url:'/oauth/clients/'+id,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {name: newname, redirect: newredirect},
                        success: function( msg ) {
                            location.reload();
                        }
                    });
                });

            }
        </script>
        <script>
            $(document).ready(function() {
                $('#generaToken').on('click', function (e) {
                    e.preventDefault();
                    var name = 'Nuevo Token';
                    var scope = 'listar modificar';
                    var user = 3;
                    var  cliente = 11;
                    $.ajax({
                        type: "POST",
                        url:'/oauth/personal-access-tokens',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {name: name,user_id : user,client_id : cliente, scopes: [scope]},
                        success: function( msg ) {
                           console.log(msg)
                        }
                    });
                });
            });
        </script>
        <script>
            function eliminarToken(id) {
                $('#si').on('click', function () {
                    $.ajax({
                        type: "DELETE",
                        url: '/oauth/personal-access-tokens/' + id,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (msg) {
                            location.reload();
                        }
                    });
                });
            }
        </script>

    @else
        <div class="alert alert-danger alert-dismissible">
            <h4><i class="icon fa fa-ban"></i> Restringido</h4>
            No cuenta con los permisos necesarios para poder ver este recurso.
        </div>
    @endcan
@stop
