@extends('adminlte::page')

@section('title', 'Token - Detalle')

@section('content_header')
    <h1>Token <small> Detalles</small></h1>
    @component('clientes/token/breadcrumbs')
        <li><a href="{{ route('clientes.token.show', ['id' => $token->uuid]) }}"><i class="fa fa-eye"></i> Detalle</a></li>
    @endcomponent
@stop

@section('adminlte_js')
    <link rel="stylesheet" type="text/css" href="{{ mix('/css/mix/forms.css') }}">
    <script type="text/javascript" src="{{ mix('/js/mix/forms.js') }}"></script>
@stop
@section('content')
        <div class="row">
            <div class="col-md-12">

                <div class="box box-widget widget-user-2">
                    <div class="widget-user-header bg-aqua-active">
                        <div class="pull-left"><i class="mdi mdi-fingerprint" style="font-size:50px !important;"></i></div>
                        <h3 class="widget-user-username">{{ $token->nombre }}</h3>
                    </div>
                    <div class="box-body box-profile">
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>ID</b> <span class="pull-right">{{ $token->id }}</span>
                            </li>
                            <li class="list-group-item">
                                <b>Permisos</b> <span class="pull-right">{{ implode(', ', $token->permisos) }}</span>
                            </li>
                            <li class="list-group-item">
                                <b>Activo</b>
                                <span class="pull-right">
                                    @if($token->revocado)
                                        <span class="label label-danger">Revocado</span>
                                    @else
                                        <span class="label label-success">Activo</span>
                                    @endif
                                </span>
                            </li>
                            <li class="list-group-item">
                                <b>Token</b>
                                <div id="token" class="box-body" style="word-wrap:break-word;">{{ $token->token }}</div>
                                <a id="copy-token" role="button" class="btn btn-default"><i class="fa fa-clone"></i> Copiar</a>
                                <script>
                                    $(document).ready(function() {
                                        // Copiar password
                                        $('#copy-token').click(function() {
                                            $('#token').text();
                                        });
                                        new Clipboard('#copy-token', {
                                            text: function(trigger) {
                                                return $('#token').text();
                                            }
                                        });
                                    });
                                </script>
                            </li>
                        </ul>
                    </div>
                    <div class="box-footer clearfix">
                        <div class="pull-right">
                            <!--a href="{{ route('clientes.token.edit', ['id' => $token->id]) }}" role="button" class="btn btn-primary"><i class="fa fa-pencil"></i> Editar</a-->
                        </div>
                        <div class="pull-left">
                            @if($token->revocado)
                            @else
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-danger"><i class="fa fa-trash"></i> Revocar</button>
                                <div class="modal modal-danger fade" id="modal-danger" style="display: none;">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span></button>
                                                <h4 class="modal-title">Advertencia</h4>
                                            </div>
                                            <div class="modal-body">
                                                <p>Estas seguro de revocar el token? Este cambio es <strong>irreversible</strong>.
                                                <br>Las aplicaciones o clientes que utilicen este token ya no podrán realizar operaciones.
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancelar</button>
                                                <a href="{{ route('clientes.token.revoke', ['id' => $token->id]) }}" role="button" class="btn btn-outline"><i class="fa fa-trash"></i> Revocar</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
@stop
