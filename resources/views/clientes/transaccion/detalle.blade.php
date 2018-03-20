@extends('adminlte::page')

@section('title', 'Moneda - Detalle')

@section('content_header')
    <h1>Transacción <small> Detalles</small></h1>
    @component('admin/transaccion/breadcrumbs')
        <li><a href="{{ route('transaccion.show', ['id' => $transaccion->uuid]) }}"><i class="fa fa-eye"></i> Detalle</a></li>
    @endcomponent
@stop

@section('adminlte_js')
@stop
@section('content')
    @can('listar transacciones')
        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-header">
                        <h3 class="box-title">Cobro</h3>
                        <div class="box-body box-profile">
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>UUID</b> <span class="pull-right">{{ $transaccion->uuid }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Monto</b> <span class="pull-right">$ {{ number_format($transaccion->monto, 2) }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Prueba</b> <span class="pull-right">{{ $transaccion->prueba }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Operación</b> <span class="pull-right">{{ $transaccion->operacion }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Datos antifraude</b> <span class="pull-right">{{ $transaccion->datos_antifraude }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Datos Claro Pagos</b> <span class="pull-right">{{ $transaccion->datos_claropagos }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Datos Procesador</b> <span class="pull-right">{{ $transaccion->datos_procesador }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Datos Destino</b> <span class="pull-right">{{ $transaccion->datos_destino }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Transaccion status id</b> <span class="pull-right">{{ $transaccion->transaccion_estatus_id }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Pais</b> <span class="pull-right">{{ $transaccion->pais }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Moneda</b> <span class="pull-right">{{ $transaccion->estatus->descripcion }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Creado</b> <span class="pull-right">{{ $transaccion->created_at }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Actualizado</b> <span class="pull-right">{{ $transaccion->updated_at }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="box box-default">
                    <div class="box-header">
                        <h3 class="box-title">Tarjeta</h3>
                        <div class="box-body box-profile">
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>Forma de pago</b> <span class="pull-right">{{ $transaccion->forma_pago }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Datos de pago</b> <span class="pull-right">{{ $transaccion->datos_pago }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="box box-default">
                    <div class="box-header">
                        <h3 class="box-title">Cliente</h3>
                        <div class="box-body box-profile">
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>Comercio</b> <span class="pull-right">{{ $transaccion->comercio }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="box box-default">
                    <div class="box-header">
                        <h3 class="box-title">Comisiones</h3>
                        <div class="box-body box-profile">
                            <ul class="list-group list-group-unbordered">

                            </ul>
                        </div>
                    </div>
                </div>
                <div class="box box-default">
                    <div class="box-header">
                        <h3 class="box-title">Logs</h3>
                        <div class="box-body box-profile">
                            <ul class="list-group list-group-unbordered">

                            </ul>
                        </div>
                    </div>
                </div>
                <div class="box box-default">
                    <div class="box-header">
                        <h3 class="box-title">Eventos</h3>
                        <div class="box-body box-profile">
                            <ul class="list-group list-group-unbordered">

                            </ul>
                        </div>
                    </div>
                </div>
                <div class="box box-default">
                    <div class="box-header">
                        <h3 class="box-title">Reembolso</h3>
                        <div class="box-body box-profile">
                            <ul class="list-group list-group-unbordered">

                            </ul>
                        </div>
                    </div>
                </div>
                @if($transaccion->operacion == "preautorizacion")
                    <div class="box box-warning">
                        <div class="box-header">
                            <h3 class="box-title">Autorizacion</h3>
                            <p>El cargo ha sido preautorizado, para realizar el cargo por favor confirme</p>
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-danger">Cancelar autorizacion</button>
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-success">Hacer cargo</button>
                            <div class="modal modal-danger fade" id="modal-danger" style="display: none;">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span></button>
                                            <h4 class="modal-title">Advertencia</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>Estas seguro?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Yes</button>
                                            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">No</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal modal-info fade" id="modal-success" style="display: none;">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span></button>
                                            <h4 class="modal-title">Advertencia</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>Estas seguro?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Yes</button>
                                            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">No</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if($transaccion->operacion == "pago")
                    <div class="box box-danger">
                        <div class="box-header">
                            <h3 class="box-title">Autorizacion</h3>
                            <p>Para realizar la disputa de un cargo (contracargo) se debera iniciar un proceso de disputa con el comercio</p>
                            <p>Para continuar con el proceso por favor de clic en inciar contracargo</p>
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-contracargo">Inciar Contracargo</button>
                            <div class="modal modal-success fade" id="modal-contracargo" style="display: none;">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span></button>
                                            <h4 class="modal-title">Advertencia</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>Estas seguro de iniciar la disputa de contracargo?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Yes</button>
                                            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">No</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <br><br>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Historico de cambios</h3>
                    </div>
                    <div id="historico-cambios-body" class="box-body box-limited">
                        @component('admin/componentes/model_cambios', ['eventos' => $historico])
                        @endcomponent
                    </div>
                </div>
            </div>
        </div>
    @endcan
@stop
