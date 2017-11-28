@extends('adminlte::page')

@section('title', 'Perfil - Detalle')

@section('content_header')
    <h1>Perfil <small> Detalles</small></h1>
    @component('clientes/perfil/breadcrumbs')
    @endcomponent
@stop

@section('adminlte_js')
@stop

@section('content')
    @can('ver perfil clientes')
        <div class="row">
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-body box-profile">
                            @if($usuario->avatar)
                                <img src="{{ Auth::user()->avatar }}" class="profile-user-img img-responsive img-circle" alt="{{ $usuario->name }}">
                            @else
                                <img src="{{ Gravatar::src($usuario->email) }}" class="profile-user-img img-responsive img-circle" alt="{{ $usuario->name }}">
                            @endif
                            <h3 class="profile-username text-center">{{ $usuario->name }}</h3>
                            <p class="text-muted text-center">
                                {{ $usuario->apellido_paterno }} {{ $usuario->apellido_materno }}
                                @if ($usuario->activo)
                                    <br><span class="label label-success">Activo</span>
                                @else
                                    <br><span class="label label-danger">Suspendido</span>
                                @endif
                            </p>

                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>E-Mail</b> <span class="pull-right">{{ $usuario->email }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Creado</b> <span class="pull-right">{{ $usuario->created_at }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Actualizado</b> <span class="pull-right">{{ $usuario->updated_at }}</span>
                                </li>
                            </ul>

                        </div>
                    </div>
                </div>

            <div class="col-md-8">
                <div class="box box-success">
                    <div class="box-body table-responsive">
                        @can('editar perfil clientes')
                            <a href="{{ route('perfil.password') }}" role="button" class="btn btn-default"><i class="fa fa-key"></i> Cambiar contraseña</a>
                            <a href="{{route('perfil.avatar')}}" role="button" class="btn btn-default"><i class="fa fa-user-circle"></i> Cambiar Avatar</a>
                            <a href="{{ route('perfil.edit') }}" role="button" class="btn btn-primary"><i class="fa fa-pencil"></i> Editar</a>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="box box-default">
                    <div class="box-header">
                        <h3 class="box-title">Roles</h3>
                    </div>
                    <div class="box-body table-responsive">
                        @if ($usuario->roles->isEmpty())
                            No se encontraron roles asignados.
                        @else
                            @foreach($usuario->roles as $rol)
                                <ul>
                                    <li>{{ $rol->name }}</li>
                                </ul>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="box box-default">
                    <div class="box-header">
                        <h3 class="box-title">Permisos individuales</h3>
                    </div>
                    <div class="box-body">
                        @if (count($permissions)==0)
                            No se encontraron permisos individuales asignados.
                        @else
                            <ul>
                                @foreach($permissions as $permiso)
                                    <li>{{ $permiso['name'] }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title"> Actividad del usuario</h3>
                    </div>
                    <div id="actividad-usuarios-body" class="box-body box-limited">
                        @component('clientes/componentes/model_cambios', ['eventos' => $actividad])
                        @endcomponent
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
