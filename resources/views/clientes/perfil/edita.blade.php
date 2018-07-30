@extends('adminlte::page')

@section('title', 'Perfil - Edita')

@section('content_header')
    <h1>Usuario <small> Detalles</small></h1>
    @component('clientes/perfil/breadcrumbs')
        <li><i class="fa fa-pencil"></i> Edita</a></li>
    @endcomponent
@stop

@section('adminlte_js')
    <link rel="stylesheet" type="text/css" href="/css/mix/forms.css">
    <script type="text/javascript" src="/js/mix/forms.js"></script>
@stop
@section('content')
    <form id="usuario-edita" name="usuario-edita" method="post" action="{{ route('clientes.perfil.update', ['id' => $usuario->id]) }}">
        {!! csrf_field() !!}
        @can('editar perfil clientes')
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Datos de usuario</h3>
                    </div>
                    <div class="box-body box-edit">
                        <div class="form-group has-feedback">
                            <label for="name">Nombre</label>
                            <input name="name" class="form-control" type="text" value="{{ $usuario->name }}" placeholder="Nombre del usuario" required maxlength="255">
                            <span class="fa fa-asterisk form-control-feedback" aria-hidden="true"></span>
                        </div>
                        <div class="form-group">
                            <label for="apellido_paterno">Apellido paterno</label>
                            <input name="apellido_paterno" class="form-control" type="text" value="{{ $usuario->apellido_paterno }}" placeholder="Apellido paterno del usuario" maxlength="255">
                        </div>
                        <div class="form-group">
                            <label for="apellido_materno">Apellido materno</label>
                            <input name="apellido_materno" class="form-control" type="text" value="{{ $usuario->apellido_materno }}" placeholder="Apellido materno del usuario" maxlength="255">
                        </div>
                        <div class="form-group has-feedback">
                            <label for="apellido_materno">E-Mail</label>
                            <input name="email" class="form-control" type="text" value="{{ $usuario->email }}" placeholder="E-Mail del usuario" maxlength="255" required>
                            <span class="fa fa-asterisk form-control-feedback" aria-hidden="true"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-danger">
                    <div class="box-body table-responsive">
                        <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Guardar</button>
                        <a href="{{ route('clientes.perfil.index') }}" role="button" class="btn btn-danger"><i class="fa fa-ban"></i> Cancelar</a>
                        <button type="reset" class="btn btn-default"><i class="fa fa-refresh"></i> Restaurar datos</button>

                    </div>
                </div>
            </div>
        </div>
        @endcan
    </form>
    <script>
        $(document).ready(function() {
            $('form#usuario-edita').dirtyForms({dirtyClass: 'has-changes'});
        });
    </script>

@stop
