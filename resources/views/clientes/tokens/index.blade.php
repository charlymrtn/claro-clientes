@extends('adminlte::page')

@section('title', 'Tokens')

@section('content_header')
    @component('clientes/tokens/breadcrumbs')
    @endcomponent
    <link rel="stylesheet" href="{{mix('/css/mix/datatables.css')}}">
@stop

@section('adminlte_js')
@stop

@section('content')
    @can('ver perfil clientes')
        <div class="content-header">
            <h1>Clientes y Tokens</h1>
            <br>
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Listado de clientes creados</h3>
                    <button type="button" class="btn btn-info pull-right" data-toggle="modal" data-target="#client">
                       Nuevo cliente
                    </button>
                </div>
                <!-- /.box-header -->
                <div class="box-body"  id="table1">
                    <div class="row">
                        <div class="col-md-12">
                            <table id="clients" class="table table-striped table-bordered responsive" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Usuario</th>
                                    <th class="center">Nombre</th>
                                    <th class="center">Secret</th>
                                    <th class="center">Redireccionamiento</th>
                                    <th class="center">Acceso personal de cliente</th>
                                    <th class="center">Revocado</th>
                                    <th data-priority="1">Acciones</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.box-body -->
            </div>
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Listado de tokens creados</h3>
                    <a href="{{route('tokens.nuevo.token')}}" class="btn btn-info pull-right">
                        Nuevo token
                    </a>
                </div>
                <!-- /.box-header -->
                <div class="box-body"  id="table2">
                    <div class="row">
                        <div class="col-md-12">
                            <table id="tokens" class="table table-striped table-bordered responsive" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th class="center">ID de cliente</th>
                                    <th class="center">Nombre</th>
                                    <th class="center">Scope</th>
                                    <th class="center">Revocado</th>
                                    <th class="center">Expiración</th>
                                    <th>Acciones</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.box-body -->
            </div>
            <!-- Modal client -->
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


        <script src="{{mix('/js/mix/datatables.js')}}"></script>
        <script>
            $('#clients').DataTable( {
                ajax: {
                    url: '/oauth/clients',
                    dataSrc: ''
                },
                columns: [
                    {data: 'id'},
                    {data: 'user_id'},
                    {data: 'name'},
                    {data: 'secret'},
                    {data:'redirect'},
                    {data: 'personal_access_client'},
                    {data: 'revoked' },

                    {data: null, orderable: false, render: function (d) {
                        return '<button  onclick="modificar(\''+d.id+'\',\''+d.name+'\',\''+d.redirect+'\');" data-toggle="modal" data-target="#modificar" class="btn btn-warning btn-xs" role="button"><i class="fa fa-pencil"></i> Modificar</button>'+'<br>'+'<button onclick=" eliminar('+d.id+');" data-toggle="modal" data-target="#confirmacion"  class="btn btn-danger btn-xs" type="button"><i class="fa fa-trash"></i> Eliminar</button>'; }
                    }
                ],
                "responsive": true,
                "searchDelay": 1500,
                "language": {
                    "emptyTable": "No se encontraron datos.",
                    "infoEmpty": "No hay registros",
                    "lengthMenu": "Mostrar _MENU_ registros",
                    "search": "Filtrar:",
                    "paginate": {
                        "first": "Primera",
                        "last": "Última",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                }
            } );
        </script>
        <script>
            $('#tokens').DataTable( {
                ajax: {
                    url: '/oauth/personal-access-tokens',
                    dataSrc: ''
                },
                columns: [
                    {data: 'id'},
                    {data: 'client_id'},
                    {data: 'name'},
                    {data:'scopes'},
                    {data: 'revoked'},
                    {data: 'expires_at' },
                    {data: null, orderable: false, render: function (d) {
                            return '<button onclick="eliminarToken(\''+d.id+'\');" data-toggle="modal" data-target="#confirmacion"  class="btn btn-danger btn-xs" type="button"><i class="fa fa-trash"></i> Eliminar</button>'; }
                    }
                ],
                "responsive": true,
                "searchDelay": 1500,
                "language": {
                    "emptyTable": "No se encontraron datos.",
                    "infoEmpty": "No hay registros",
                    "lengthMenu": "Mostrar _MENU_ registros",
                    "search": "Filtrar:",
                    "paginate": {
                        "first": "Primera",
                        "last": "Última",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                }
            } );
        </script>
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
