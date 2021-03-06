@extends('adminlte::master')

@section('body')
    <div class="wrapper">
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="row">
                    <div class="col-md-8">
                        <div class="box box-danger">
                            <div class="box-body">
                                <h4><i class="fa fa-ban"></i> &nbsp; El sistema de clientes no cuenta con interfaz de administración.</h4>
                                <a href="{{ route('logout') }}" role="button" class="btn btn-default"><i class="fa fa-sign-out"></i> Salir</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
