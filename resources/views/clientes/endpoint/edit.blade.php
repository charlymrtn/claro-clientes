@extends('adminlte::page')

@section('title', 'Tokens')

@section('content_header')
    @component('clientes/endpoint/breadcrumbs')
    @endcomponent
@stop
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ mix('/css/mix/forms.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ mix('/css/app.css') }}">
    <link rel="stylesheet" media="screen" type="text/css" href="{{ mix('/css/multi-select.css') }}">
@stop

@section('adminlte_js')
    <script type="text/javascript" src="{{ mix('/js/app.js') }}"></script>
    <script type="text/javascript" src="{{ mix('/js/mix/ui.js') }}"></script>

    <script type="text/javascript" src="{{ mix('/js/jquery.multi-select.js') }}"></script>
    <script type="text/javascript" src="{{ mix('/js/mix/forms.js') }}"></script>
    <script type="text/javascript" src="{{ mix('/js/bootstrap-confirmation.js') }}"></script>

    <script>
        $('#generar').on('click', function (e) {
            $('#generar').prop("disabled", true);
            $('#form-endpoint').submit();
        });

        $('#eventos').multiSelect();

        
    </script>
@stop

@section('content')
    @can('listar tokens clientes')
        <div class="content-header">
            <h1>Editar Endpoint</h1>
            <br>
            <div class="box box-default">
                <form id="form-endpoint" name="form-endpoint" action="{{route('clientes.endpoint.update',$endpoint->id)}}" method="POST">
                @method('PUT')
                @csrf
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="url" class="form-group">
                                <label>URL del Endpoint</label>
                                <input type="text" value="{{$endpoint->url}}" required class="form-control" id="url" name="url" placeholder="Ingresa el url del endpoint" >
                                <span id="error" class="help-block"></span>
                            </div>
                            <div id="max_intentos" class="form-group">
                                <label>Numero Maximo de Intentos</label>
                                <input type="text" value="{{$endpoint->max_intentos}}" required class="form-control" id="max_intentos" name="max_intentos" placeholder="Ingresa el maximo de intentos" >
                                <span id="error" class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label>Eventos</label>
                                <select id='eventos' name="eventos[]" multiple='multiple'>
                                    @foreach($eventos as $evento)
                                        @if ($evento->selected)
                                            <option value="{{ $evento->id }}" selected>{{ $evento->nombre }}</option>
                                        @else
                                            <option value="{{ $evento->id }}">{{ $evento->nombre }}</option>
                                        @endif                                        
                                    @endforeach
                                </select>
                            </div>
        
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-danger">
                            <div class="box-body table-responsive">
                                <button id="generar" type="submit" class="btn btn-success"><i class="fa fa-key"></i> Actualizar Endpoint</button>
                                <a type="button" href="{{route('clientes.endpoint.destroy',$endpoint->id)}}" class="btn btn-warning" data-toggle="modal" data-target="#myModal"><i class="fa fa-trash"></i> Borrar Endpoint</a>
                                <a href="{{route('clientes.endpoint.index')}}" type="button" class="btn btn-danger pull-right">Cancelar</a>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
                <!-- Modal -->
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Confimación</h4>
                        </div>
                        <div class="modal-body">
                            ¿Seguro que deseas eliminar el Endpoint?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#myModal"><i class="fa fa-trash"></i> Borrar Endpoint</a>
                        </div>
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
