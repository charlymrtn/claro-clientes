@extends('adminlte::page')

@section('title', 'Usuario - Cambia contraseña')

@section('content_header')
    <h1>Usuario <small> Cambio de contraseña</small></h1>
    @component('clientes/perfil/breadcrumbs')
        <li><i class="fa fa-key"></i> Cambia contraseña</li>
    @endcomponent
@stop

@section('adminlte_js')
    <link rel="stylesheet" type="text/css" href="/css/mix/forms.css">
    <script type="text/javascript" src="/js/mix/forms.js"></script>
@stop

@section('content')
    <form  id="perfil-edita-password" name="usuario-edita" method="post" action="{{ route('clientes.perfil.update') }}">
        {!! csrf_field() !!}
        @can('editar perfil')
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Cambio de contraseña</h3>
                    </div>
                    <div class="box-body box-edit">
                        <div id="password-assign">
                            <div class="form-group has-feedback">
                                <label for="last-password">Contraseña Anterior</label>
                                <input id="last-password" name="last-password" class="form-control" type="password" value="" placeholder="Contraseña anterior" maxlength="255" data-toggle="password" data-placement="before" data-message="Mostrar contraseña" required>
                                <span class="fa fa-asterisk form-control-feedback" aria-hidden="true"></span>
                            </div>
                            @include('clientes/usuario/partials/contrasena')
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-danger">
                    <div class="box-body table-responsive">
                        <button type="submit" id="cambiar" class="btn btn-success"><i class="fa fa-floppy-o"></i> Cambiar</button>
                        <a href="{{ route('clientes.perfil.index') }}" role="button" class="btn btn-danger"><i class="fa fa-ban"></i> Cancelar</a>
                        <button id="limpiar-datos" type="reset" class="btn btn-default"><i class="fa fa-refresh"></i> Limpiar datos</button>
                    </div>
                </div>
            </div>
        </div>
        @endcan
    </form>
    <script>
        $(document).ready(function() {
            $('form#perfil-edita-password').dirtyForms({dirtyClass: 'has-changes'});
            // Validaciones al enviar
            $('form#perfil-edita-password').submit(function(e) {
                // Valida que las contraseñas coincidan
                if(!CheckPasswords()){
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>

@stop