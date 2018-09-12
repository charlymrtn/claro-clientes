@extends('adminlte::page')

@section('title', 'Tokens')

@section('content_header')
    @component('clientes/endpoint/breadcrumbs')
    @endcomponent
@stop
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ mix('/css/mix/forms.css') }}">
    <link rel="stylesheet" media="screen" type="text/css" href="{{ mix('/css/multi-select.css') }}">
@stop

@section('adminlte_js')
    <script type="text/javascript" src="{{ mix('/js/jquery.multi-select.js') }}"></script>
    <script type="text/javascript" src="{{ mix('/js/mix/forms.js') }}"></script>
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
            <h1>Nuevo Endpoint</h1>
            <br>
            <div class="box box-default">
                <form id="form-endpoint" name="form-endpoint" action="{{route('clientes.endpoint.store')}}" method="POST">
                @csrf
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="url" class="form-group">
                                <label>URL del Endpoint</label>
                                <input type="text" required class="form-control" id="url" name="url" placeholder="Ingresa el url del endpoint" >
                                <span id="error" class="help-block"></span>
                            </div>
                            <div id="max_intentos" class="form-group">
                                <label>Numero Maximo de Intentos</label>
                                <input type="text" required class="form-control" id="max_intentos" name="max_intentos" placeholder="Ingresa el maximo de intentos" >
                                <span id="error" class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label>Eventos</label>
                                <select id='eventos' name="eventos[]" multiple='multiple'>
                                    @foreach($eventos as $evento)
                                        <option value="{{ $evento->id }}">{{ $evento->nombre }}</option>
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
                                <button id="generar" type="submit" class="btn btn-success"><i class="fa fa-key"></i> Generar Endpoint</button>
                                <a href="{{route('clientes.endpoint.index')}}" type="button" class="btn btn-danger pull-right">Cancelar</a>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>

    @else
        <div class="alert alert-danger alert-dismissible">
            <h4><i class="icon fa fa-ban"></i> Restringido</h4>
            No cuenta con los permisos necesarios para poder ver este recurso.
        </div>
    @endcan
@stop
