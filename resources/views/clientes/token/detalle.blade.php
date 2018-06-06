@extends('adminlte::page')

@section('title', 'Token - Detalle')

@section('content_header')
    <h1>Token <small> Detalles</small></h1>
    @component('clientes/token/breadcrumbs')
        <li><a href="{{ route('token.show', ['id' => $token->uuid]) }}"><i class="fa fa-eye"></i> Detalle</a></li>
    @endcomponent
@stop

@section('adminlte_js')
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
                                <b>Activo</b> <span class="pull-right">{{ $token->revoked ? 'No' : 'Si'}}</span>
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
                </div>

            </div>
        </div>
@stop
