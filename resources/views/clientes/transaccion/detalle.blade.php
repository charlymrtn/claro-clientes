@extends('adminlte::page')

@section('title', 'Transacción - Detalle')

@section('content_header')
    <h1>Transacción <small> Detalles</small></h1>
    @component('clientes/transaccion/breadcrumbs')
        <li><a href="{{ route('clientes.transaccion.show', ['id' => $transaccion->uuid]) }}"><i class="fa fa-eye"></i> Detalle</a></li>
    @endcomponent
@stop

@section('adminlte_js')
    <script>
        jQuery(function($){
            // Inicia devolución
            $('#devolucion').click(function() {
                window.location = "{{ route('clientes.vpos.devolucion', ['uuid' => $transaccion->uuid]) }}";
            });
            // Inicia cancelación
            $('#cancelacion').click(function() {
                window.location = "{{ route('clientes.vpos.cancelacion', ['uuid' => $transaccion->uuid]) }}";
            });
        });
    </script>


@stop
@section('content')
        <div class="row">
            <div class="col-md-6">
                <div class="box" style="border-top-color:{{ $transaccion->estatus->color }};">
                    <div class="box-header">
                        <h3 class="box-title">Resumen</h3>
                    </div>
                    <div class="box-body box-profile">
                        <span class="pull-right">
                            <h3 class="no-margin-top"><span class="label" style="background-color:{{ $transaccion->estatus->color }};">{{ $transaccion->estatus->nombre }}</span></h3>
                        </span>
                        <h1 class="no-margin-top">$ {{ number_format($transaccion->monto, 2) }} <small>{{ $transaccion->moneda->iso_a3 }}</small></h1>
                        <i class="fa fa-calendar"></i> &nbsp; {{ $transaccion->created_at }}
                        <br>&nbsp;
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">Forma de pago</h3>
                    </div>
                    <div class="box-body box-profile">
                        @if ($transaccion->forma_pago == 'tarjeta')
                            @if (!empty($transaccion->datos_pago['marca']))
                                <span class="pull-right">
                                    <h3 class="no-margin-top"><img src="/img/forma_pago/tarjeta/marca-{{ $transaccion->datos_pago['marca'] }}.png" width="75" height=""></h3>
                                </span>
                            @endif
                            <h1 class="no-margin-top">  {{ ucfirst($transaccion->forma_pago ?? 'Desconocida') }}</h1>
                            <i class="fa fa-credit-card"></i> &nbsp; {!! str_replace('*', ' &bull; ', $transaccion->datos_pago['pan'] ?? '-') !!}
                            <br><i class="fa fa-user"></i> &nbsp; {{ $transaccion->datos_pago['nombre'] ?? '-' }}
                        @else
                            {{ ucfirst($transaccion->forma_pago) }}
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-header">
                        <h3 class="box-title">Transacción</h3>
                        <div class="box-body box-profile">
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>ID</b> <span class="pull-right">{{ $transaccion->uuid }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Operación</b> <span class="pull-right">{{ ucfirst($transaccion->operacion) }}</span>
                                </li>
                                @if ($transaccion->prueba)
                                    <li class="list-group-item">
                                        <b>Prueba</b> <span class="pull-right">{{ $transaccion->prueba ? 'Si' : 'No'}}</span>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-header">
                        <h3 class="box-title">Cobro</h3>
                       <div class="box-body box-profile">
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>Creado</b> <span class="pull-right">{{ $transaccion->created_at }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Monto</b> <span class="pull-right">$ {{ number_format($transaccion->monto, 2) }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Moneda</b> <span class="pull-right">{{ $transaccion->moneda->nombre }} ({{ $transaccion->moneda->iso_a3 }})</span>
                                </li>
                                <li class="list-group-item" style="overflow:auto;">
                                    <b>Datos antifraude</b> <span class="pull-right">
                                        Respuesta: {{ $transaccion->datos_antifraude->codigo ?? 'Sin información' }}
                                        <br>Mensaje: {{ $transaccion->datos_antifraude->descripcion  ?? 'Sin información' }}
                                    </span>
                                </li>
                                <li class="list-group-item" style="overflow:auto;">
                                    <b>Datos Claro Pagos</b> <span class="pull-right">
                                    @empty($transaccion->datos_claropagos)
                                        Sin datos
                                    @else
                                        {{ $transaccion->datos_claropagos }}
                                    @endempty
                                    </span>
                                </li>
                                <li class="list-group-item" style="overflow:auto;">
                                    <b>Datos Procesador</b> <span class="pull-right">
                                        @empty($records)
                                            No procesado
                                        @else
                                            <?php
                                                if (in_array($transaccion->datos_pago['marca'], ['mastercard', 'visa'])) {
                                                    ?>
                                                    Respuesta: {{ $transaccion->datos_procesador->data->response_code }} ({{ $transaccion->datos_procesador->status }})
                                                    <br>Mensaje: {{ $transaccion->datos_procesador->data->message }}
                                                    <br>Número de autorización: {{ $transaccion->datos_procesador->data->importantData->authNum }}
                                                    <br>Número de orden: {{ $transaccion->datos_procesador->data->importantData->orderId }}
                                                    <br>Número de transaccion: {{ $transaccion->datos_procesador->data->importantData->transactionId }}
                                                    <?php
                                                } else if ($transaccion->datos_pago['marca'] == 'amex') {
                                                    ?>
                                                    Respuesta: {{ $transaccion->datos_procesador->status_code }} ({{ $transaccion->datos_procesador->status }})
                                                    <br>Mensaje: {{ $transaccion->datos_procesador->status_message }}
                                                    <br>Número de autorización: {{ $transaccion->datos_procesador->system_trace_num }}
                                                    <?php
                                                } else {
                                                    ?>
                                                    <pre>{{ print_r($transaccion->datos_procesador, true) }}</pre>
                                                    <?php
                                                }
                                            ?>
                                        @endempty
                                    </span>
                                </li>
                                <li class="list-group-item">
                                    <b>Transaccion status id</b> <span class="pull-right">{{ $transaccion->transaccion_estatus_id }}</span>
                                </li>
                                <li class="list-group-item" style="overflow:auto;">
                                    <b>Pais</b> <span class="pull-right">{{ $transaccion->pais->nombre }}</span>
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
                            @if ($transaccion->forma_pago == 'tarjeta')
                                <ul class="list-group list-group-unbordered">
                                    <li class="list-group-item">
                                        <b>Tarjeta</b> <span class="pull-right">{!! str_replace('*', ' &bull; ', $transaccion->datos_pago['pan'] ?? '-') !!}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Marca</b> <span class="pull-right">{{ ucfirst($transaccion->datos_pago['marca'] ?? '-') }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Nombre</b> <span class="pull-right">{{ $transaccion->datos_pago['nombre'] ?? '-' }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Huella digital</b> <span class="pull-right">{{ $transaccion->datos_pago['pan_hash'] ?? '-' }}</span>
                                    </li>
                                </ul>
                            @endif

                        </div>
                    </div>
                </div>
                <div class="box box-default">
                    <div class="box-header">
                        <h3 class="box-title">Datos del cliente del comercio</h3>
                        <div class="box-body box-profile">
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>Cliente ID</b> <span class="pull-right">{{ $transaccion->datos_comercio['cliente']['id'] ?? 'Desconocido' }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Email</b> <span class="pull-right">{{ $transaccion->datos_comercio['cliente']['email'] ?? 'Desconocido' }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Nombre comlpeto</b> <span class="pull-right">{{ $transaccion->datos_comercio['cliente']['nombre'] ?? '-' }} {{ $transaccion->datos_comercio['cliente']['apellido_paterno'] ?? '-' }} {{ $transaccion->datos_comercio['cliente']['apellido_materno'] ?? '-' }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Teléfono</b> <span class="pull-right">{{ $transaccion->datos_comercio['cliente']['telefono']['codigo_area'] ?? '-' }} {{ $transaccion->datos_comercio['cliente']['telefono']['numero'] ?? '-' }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Género</b> <span class="pull-right">{{ $transaccion->datos_comercio['cliente']['genero'] ?? 'Desconocido' }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Fecha de nacimiento</b> <span class="pull-right">{{ $transaccion->datos_comercio['cliente']['nacimiento'] ?? 'Desconocido' }}</span>
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
                                    <b>Pedido ID</b> <span class="pull-right">{{ $transaccion->datos_comercio['pedido']['id'] ?? '-' }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Artículos</b> <span class="pull-right">{{ $transaccion->datos_comercio['pedido']['articulos'] ?? '-' }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Total</b> <span class="pull-right">$ {{ $transaccion->datos_comercio['pedido']['total'] ?? '-' }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Peso</b> <span class="pull-right">{{ $transaccion->datos_comercio['pedido']['peso'] ?? '-' }} Kg.</span>
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
                @if($transaccion->operacion == "pago" && $transaccion->transaccion_estatus_id == "1")
                    <div class="box box-danger">
                        <div class="box-header">
                            <h3 class="box-title">Reembolso</h3>
                            <p>Para realizar una devolución se deberá hacer clic en iniciar devolución</p>
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-contracargo">Inciar Devolución</button>
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
                                            <p>Esta seguro de iniciar la devolución del pago de la transacción <b>{{ $transaccion->uuid }}</b> realizada el <b>{{ $transaccion->created_at }}</b> con un monto de  <strong>$ {{ number_format($transaccion->monto, 2) }}</strong>?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button id="devolucion" type="button" class="btn btn-outline pull-left" data-dismiss="modal">Si</button>
                                            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">No</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if($transaccion->transaccion_estatus_id == "1")
                    <div class="box box-danger">
                        <div class="box-header">
                            <h3 class="box-title">Cancelación</h3>
                            <p>La cancelación de una transacción estará sujeta al tiempo que ha transcurrido desde la operación, sepodrá reflejar como una cancelación o como reverso.</p>
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-cancelacion">Cancelar Transaccion</button>
                            <div class="modal modal-warning fade" id="modal-cancelacion" style="display: none;">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span></button>
                                            <h4 class="modal-title">Advertencia</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>Esta seguro de iniciar la cancelación del pago de la transacción <b>{{ $transaccion->uuid }}</b> realizada el <b>{{ $transaccion->created_at }}</b> con un monto de  <strong>$ {{ number_format($transaccion->monto, 2) }}</strong>?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button id="cancelacion" type="button" class="btn btn-outline pull-left" data-dismiss="modal">Si</button>
                                            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">No</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

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
                                            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Si</button>
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
                    <!--div class="box box-danger">
                        <div class="box-header">
                            <h3 class="box-title">Contracargo</h3>
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
                    </div-->
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
