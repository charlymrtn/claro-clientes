@extends('adminlte::page')

@section('title', 'Tokens')

@section('content_header')
    @component('clientes/tokens/breadcrumbs')
    @endcomponent
@stop
@section('css')
    <link rel="stylesheet" href="http://clientes.claropay.local.com/vendor/adminlte/plugins/iCheck/square/blue.css">
@stop

@section('adminlte_js')
    <script src="http://clientes.claropay.local.com/vendor/adminlte/plugins/iCheck/icheck.min.js"></script>
    <script>
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue'
        });
    </script>
    <script>
        $('#generar').on('click', function (e) {
            e.preventDefault();

            var scope = new Array();
            var boxes = document.getElementsByTagName("input");
            for (var i=0;i<boxes.length;i++) {
                var box = boxes[i];
                if (box.type == "checkbox" && box.checked) {
                    scope[scope.length] = box.value;
                }
            }

            var name = $('#name').val();
            if(name == ''){
                $('#error').html("Campo obligatorio");
                $('#nombre').addClass('has-error');
            }
            var data = {
                name: name,
                scopes:scope
            };
            $.ajax({
                type: "POST",
                url:'/oauth/personal-access-tokens',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: data,
                success: function( msg ) {
                    $("#token").html(msg.accessToken);
                    jQuery("#getCodeModal").modal('show');
                },
                error: function (xhr, ajaxOptions, thrownError) {
            }
            });
        });
    </script>
    <script>
        $("#copy").click(function(){
            $("#token").select();
            document.execCommand('copy');
        });
    </script>

@stop

@section('content')
    @can('ver perfil clientes')
        <div class="content-header">
            <h1>Nuevo Token</h1>
            <br>
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Llena los campos</h3>

                    <div class="box-tools pull-right">
                    </div>
                </div>
                <form action="" name="success">
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="nombre" class="form-group">
                                <label>Nombre</label>
                                <input type="text" required class="form-control" id="name" placeholder="Ingresa el nombre" >
                                <span id="error" class="help-block"></span>
                            </div>
                            <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Permisos</label>
                            </div>
                            <div class="form-group">
                                <div class="icheckbox_square-blue">
                                    <input name="input[]" type="checkbox" id="listar" value="listar"><ins class="iCheck-helper"></ins>
                                </div>
                                <label>Leer</label>
                            </div>
                            <div class="form-group">
                                <div class="icheckbox_square-blue">
                                    <input name="input[]" type="checkbox" id="modificar" value="modificar" ><ins class="iCheck-helper"></ins>
                                </div>
                                <label>Modificar</label>
                            </div>
                            <div class="form-group">
                                <div class="icheckbox_square-blue">
                                    <input name="input[]" type="checkbox" id="eliminar" value="eliminar" ><ins class="iCheck-helper"></ins>
                                </div>
                                <label>Eliminar</label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-md-12">
                            <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                        <div class="row">
                            <div class="col-md-12"><br>
                                <div class="col-md-4">
                                    <a href="{{route('tokens.index')}}" type="button"  class="btn btn-block btn-danger">Cancelar</a>
                                </div>
                                <div class="col-md-4 col-md-offset-4">
                                    <button type="button" id="generar" class="btn btn-block btn-success pull-right">Generar</button>
                                </div>
                            </div>
                        </div><br>

                    </div>

                    <!-- /.row -->
                </div>
                </form>
            </div>
        </div>
        <div class="modal fade" id="getCodeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel"> TOKEN </h4>
                    </div>
                    <div class="modal-body" id="getCode">
                        <div class="form-group">
                            <label>Token</label>
                            <textarea id="token" class="form-control" rows="20" ></textarea> <br>
                            <button id="copy" type="button" class="btn btn-info pull-right">Copiar</button>
                            <a href="{{route('tokens.index')}}"  type="button" class="btn btn-danger pull-left">Cerrar</a>
                            <br>
                        </div>

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
