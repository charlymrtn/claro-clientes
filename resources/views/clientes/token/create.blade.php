@extends('adminlte::page')

@section('title', 'Tokens')

@section('content_header')
    @component('clientes/token/breadcrumbs')
    @endcomponent
@stop
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ mix('/css/mix/forms.css') }}">
@stop

@section('adminlte_js')
    <script type="text/javascript" src="{{ mix('/js/mix/forms.js') }}"></script>
    <script>
        $('#generar').on('click', function (e) {
            $('#generar').prop("disabled", true);
            $('#form-token').submit();
        });
    </script>
@stop

@section('content')
    @can('listar tokens clientes')
        <div class="content-header">
            <h1>Nuevo Token</h1>
            <br>
            <div class="box box-default">
                <form id="form-token" name="form-token" action="{{route('clientes.token.store')}}" method="POST">
                @csrf
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="nombre" class="form-group">
                                <label>Nombre del token</label>
                                <input type="text" required class="form-control" id="name" name="name" placeholder="Ingresa el nombre o descripción del token" >
                                <span id="error" class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label>Permisos</label>
                                <ul>
                                    @foreach($permisos as $permiso)
                                    <input id="permiso_{{ $permiso->id }}" type="checkbox" name="permisos[]" value="{{ $permiso->id }}">
                                    <label for="permiso_{{ $permiso->id }}">{{ $permiso->descripcion }}</label>
                                    <br>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-danger">
                            <div class="box-body table-responsive">
                                <button id="generar" type="submit" class="btn btn-success"><i class="fa fa-key"></i> Generar token</button>
                                <a href="{{route('clientes.token.index')}}" type="button" class="btn btn-danger pull-right">Cancelar</a>
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
