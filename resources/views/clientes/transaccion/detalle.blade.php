@extends('adminlte::page')

@section('title', 'Transacción - Detalle')

@section('content_header')
    <h1>Transacción <small> Detalles</small></h1>
    @component('clientes/transaccion/breadcrumbs')
        <li><a href="{{ route('transaccion.show', ['id' => $transaccion->uuid]) }}"><i class="fa fa-eye"></i> Detalle</a></li>
    @endcomponent
@stop

@section('adminlte_js')
@stop
@section('content')
                                            <?php
                                            $oDatosPago = json_decode($transaccion->datos_pago);
                                            $oDatosAntifraude = json_decode($transaccion->datos_antifraude);
                                            $oDatosProcesador = json_decode($transaccion->datos_procesador);
                                            $oDatosComercio = json_decode($transaccion->datos_comercio);
                                            ?>
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
                                <li class="list-group-item" style="overflow:auto;">
                                    <b>Datos antifraude</b> <span class="pull-right">
                                        Respuesta: {{ $oDatosAntifraude->response_code }}
                                        <br>Mensaje: {{ $oDatosAntifraude->response_description }}
                                    </span>
                                </li>
                                <li class="list-group-item" style="overflow:auto;">
                                    <b>Datos Claro Pagos</b> <span class="pull-right">{{ $transaccion->datos_claropagos }}</span>
                                </li>
                                <li class="list-group-item" style="overflow:auto;">
                                    <b>Datos Procesador</b> <span class="pull-right">
                                        <?php
                                            if ($transaccion->transaccion_estatus_id == 1) {
                                                if (in_array($oDatosPago->marca, ['mastercard', 'visa'])) {
                                                    ?>
                                                    Respuesta: {{ $oDatosProcesador->data->response_code }} ({{ $oDatosProcesador->status }})
                                                    <br>Mensaje: {{ $oDatosProcesador->data->message }}
                                                    <br>Número de autorización: {{ $oDatosProcesador->data->importantData->authNum }}
                                                    <br>Número de orden: {{ $oDatosProcesador->data->importantData->orderId }}
                                                    <br>Número de transaccion: {{ $oDatosProcesador->data->importantData->transactionId }}
                                                    <?php
                                                } else if ($oDatosPago->marca == 'amex') {
                                                    ?>
                                                    Respuesta: {{ $oDatosProcesador->status_code }} ({{ $oDatosProcesador->status }})
                                                    <br>Mensaje: {{ $oDatosProcesador->status_message }}
                                                    <br>Número de autorización: {{ $oDatosProcesador->system_trace_num }}
                                                    <?php
                                                } else {
                                                    ?>
                                                    <pre>{{ print_r($oDatosProcesador, true) }}</pre>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                    No procesado
                                                <?php
                                            }
                                        ?>
                                    </span>
                                </li>
                                <!--li class="list-group-item">
                                    <b>Datos Destino</b> <span class="pull-right">{{ $transaccion->datos_destino }}</span>
                                </li-->
                                <li class="list-group-item">
                                    <b>Transaccion status id</b> <span class="pull-right">{{ $transaccion->transaccion_estatus_id }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Pais</b> <span class="pull-right">{{ $transaccion->pais->nombre }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Moneda</b> <span class="pull-right">{{ $transaccion->moneda->nombre }} ({{ $transaccion->moneda->iso_a3 }})</span>
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
                                    <b>Forma de pago</b> <span class="pull-right">
                                        {{ $transaccion->forma_pago }}
                                    </span>
                                </li>
                                <li class="list-group-item" style="overflow:auto;">
                                        <b>Datos de pago</b> <span class="pull-right">
                                        Tarjeta: {{ $oDatosPago->pan }}
                                        <br>Marca: {{ $oDatosPago->marca }}
                                        <br>Nombre: {{ $oDatosPago->nombre }}
                                        <br>Hash: {{ $oDatosPago->pan_hash }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="box box-default">
                    <div class="box-header">
                        <h3 class="box-title">Datos del cliente del comercio</h3>
                        <div class="box-body box-profile">
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>Cliente ID</b> <span class="pull-right">{{ $oDatosComercio->cliente->id }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Email</b> <span class="pull-right">{{ $oDatosComercio->cliente->email }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Nombre comlpeto</b> <span class="pull-right">{{ $oDatosComercio->cliente->nombre }} {{ $oDatosComercio->cliente->apellido_paterno }} {{ $oDatosComercio->cliente->apellido_materno }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Teléfono</b> <span class="pull-right">{{ $oDatosComercio->cliente->telefono->codigo_area }} {{ $oDatosComercio->cliente->telefono->numero }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Género</b> <span class="pull-right">{{ $oDatosComercio->cliente->genero }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Fecha de nacimiento</b> <span class="pull-right">{{ $oDatosComercio->cliente->nacimiento ?? 'Desconocido' }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="box box-default">
                    <div class="box-header">
                        <h3 class="box-title">Datos del pedido del comercio</h3>
                        <div class="box-body box-profile">
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>Pedido ID</b> <span class="pull-right">{{ $oDatosComercio->pedido->id }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Artículos</b> <span class="pull-right">{{ $oDatosComercio->pedido->articulos }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Total</b> <span class="pull-right">$ {{ $oDatosComercio->pedido->total }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Peso</b> <span class="pull-right">{{ $oDatosComercio->pedido->peso }} Kg.</span>
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
                @if($transaccion->transaccion_estatus_id == "1")
                    <div class="box box-danger">
                        <div class="box-header">
                            <h3 class="box-title">Cancelación</h3>
                            <p>La cancelación de una transacción estará sujeta al tiempo que ha transcurrido desde la operación, sepodrá reflejar como una cancelación o como reverso.</p>
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-cancelacion">Cancelar Transaccion</button>
                            <div class="modal modal-error fade" id="modal-cancelacion" style="display: none;">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-  header">
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
                <div class="box box-default">
                    <div class="box-header">
                        <h3 class="box-title">Reembolso</h3>
                        <div class="box-body box-profile">
                            <ul class="list-group list-group-unbordered">
                            @if($transaccion->operacion == "pago")
                                <div class="box box-danger">
                                    <div class="box-header">
                                        <div id="devolucion_solicitud" class="">
                                            <h3 class="box-title">Autorizacion</h3>
                                            <p>Para realizar una devolución se deberá hacer clic en iniciar devolución</p>
                                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-contracargo">Inciar Devolución</button>
                                        </div>
                                        <div id="devolucion_iniciada" class="hidden">
                                        </div>
                                        <div class="modal modal-warning fade" id="modal-contracargo" style="display: none;">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">×</span></button>
                                                        <h4 class="modal-title">Advertencia de devolución</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Estas seguro de iniciar la devolución del pago de la transacción <b>{{ $transaccion->uuid }}</b> realizada el <b>{{ $transaccion->created_at }}</b> con un monto de  <strong>$ {{ number_format($transaccion->monto, 2) }}</strong>?</p>
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
                        @component('clientes/componentes/model_cambios', ['eventos' => $historico])
                        @endcomponent
                    </div>
                </div>
            </div>
        </div>
@stop
