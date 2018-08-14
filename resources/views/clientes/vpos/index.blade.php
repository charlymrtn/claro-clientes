@extends('adminlte::page')

@section('title', 'vPOS')

@section('content_header')
    @component('clientes/vpos/breadcrumbs')
    @endcomponent
@stop

@section('adminlte_css')
    <link rel="stylesheet" type="text/css" href="{{ mix('/css/mix/forms.css') }}">
@stop

@section('adminlte_js')
    <script type="text/javascript" src="{{ asset('vendor/jessepollak/card/jquery.card.js') }}"></script>
    <script type="text/javascript" src="{{ mix('/js/mix/forms.js') }}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>

    <script>
        jQuery(function($){
            // Inicializa rango de puntos
            $("#puntos_rango").ionRangeSlider({
                min: 0, max: 0, from: 0, grid: true, prefix: "$", step: 0.05
            });
            var rangoPuntos = $("#puntos_rango").data("ionRangeSlider");
            // Funciones para desactivar puntos y promociones
            function resetPromo() {
                $('#promocion').children().eq(0).prop("selected", true);
                $('#promocion_meses_div').hide();
                $('#promocion_diferido_div').hide();
            }
            function togglePuntos(activar) {
                if (activar) {
                    $('#puntos_pago').prop('checked', true);
                    $('#puntos_pago_activo').val(1);
                    $('#puntos_rango_div').show();
                    var monto = Number($('#monto').val());
                    rangoPuntos.update({from:(monto)});
                    resetPromo();
                } else {
                    $('#puntos_pago').prop('checked', false);
                    $('#puntos_pago_activo').val(0);
                    $('#puntos_rango_div').hide();
                    rangoPuntos.update({from:(0)});
                }
            }
            // Otras funciones
            function number_format(m, n, x) {
                var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
                return m.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
            };
            function generateOrderId(size) {
                var text = "";
                var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                for (var i = 0; i < size; i++) {
                    text += possible.charAt(Math.floor(Math.random() * possible.length));
                }
                return text;
            }
            function puedeUsarPuntos(bin) {
                // Detecta tarjeta con puntos
                var binesPuntos = [
                    "410180", "410181", "441311", "455500", "455503", "455504", "455505", "455508", "455529", "455540", "455545", "493160", "493161", "493162", "477210", "477212", "477213", "477214", "477291", "477292", "481514", "481515",
                    "542010", "542977", "544053", "544551", "547077", "547086", "547095", "547155", "547156", "554629", "535875", "542015", "542073"
                ];
                if ($.inArray(bin.substring(0, 6), binesPuntos) >= 0) {
                    return true;
                } else {
                    return false;
                }
            }
            function fillTrxResult(data) {
                $('#transaccion-id').html("<a href=\"{{ route('clientes.transaccion.index') }}/" + data.id + "\" class=\"btn btn-primary btn-sm\" role=\"button\">" + data.id + "</a>");
                $('#transaccion-autorizacion').html(data.autorizacion);
                if (!data.autorizacion) {
                    $('#transaccion-autorizacion').html(data.respuesta[38]);
                }
                $('#transaccion-nombre').html("<span class=\"label\" style=\"background-color:" + data.estatus_color + ";\">" + data.estatus + "</span>");
                $('#transaccion-monto').html(numeral(data.monto).format('$0,0.00'));
                $('#transaccion-fecha').html(data.transaccion.created_at);

                $('#transaccion-estatus').html(data.estatus);
                $('#transaccion-tipo').html(data.tipo[0].toUpperCase() + data.tipo.slice(1));

                $('#transaccion-forma_pago-tarjeta-marca').html(data.transaccion.datos_pago.marca);
                $('#transaccion-forma_pago').html(data.transaccion.forma_pago[0].toUpperCase() + data.transaccion.forma_pago.slice(1));
                $('#transaccion-pan').html(data.transaccion.datos_pago.pan);
                $('#transaccion-descripcion').html(data.resultado_descripcion);

            }
            function fillErrorResult(data) {
                //$('#error-transaccion-id').html("<a href=\"{{ route('clientes.transaccion.index') }}/" + data.id + "\" class=\"btn btn-primary btn-sm\" role=\"button\">" + data.id + "</a>");
                $('#error-transaccion-descripcion').html(data.resultado_descripcion);
                //$('#error-transaccion-nombre').html("<span class=\"label\" style=\"background-color:" + data.estatus_color + ";\">" + data.estatus + "</span>");
            }
            // Activa pago con puntos
            $('#puntos_pago').change(function () {
                if ($(this).prop("checked")) {
                    togglePuntos(true);
                } else {
                    togglePuntos(false);
                }
            });
            // Activa promoción
            $('#promocion').change(function () {
                var promo = $(this).val();
                if (promo == 'msi' || promo == 'mci') {
                    $('#promocion_meses_div').show();
                    $('#promocion_diferido_div').hide();
                } else if (promo == 'diferido') {
                    $('#promocion_meses_div').hide();
                    $('#promocion_diferido_div').show();
                } else if (promo == 'diferido_msi' || promo == 'diferido_mci') {
                    $('#promocion_meses_div').show();
                    $('#promocion_diferido_div').show();
                } else {
                    $('#promocion_meses_div').hide();
                    $('#promocion_diferido_div').hide();
                }
                // Desactiva puntos
                togglePuntos(false);
            });
            // Generar número de orden
            $('#generate-order').click(function() {
                var order = generateOrderId(20);
                $('#pedido_numero').val(order);
            });
            // Formatea monto
            $('#monto').change(function() {
                var monto = Number($('#monto').val());
                $('#monto').val(monto.toFixed(2));
                rangoPuntos.update({max:monto, from:(0)});
                //rangoPuntos.update({max:monto, from:(monto/2)});
            });
            // Inicializa tarjeta
            $('#vpos').card({
                container: '#card-wrapper', // *required*
                placeholders: {
                    name: 'Nombre completo'
                },
                messages: {
                  validDate: 'VALIDA\nHASTA',
                  monthYear: 'MES/AÑO'
                },
                debug: true
            });
            // Detecta tarjeta con puntos
            $("#number").keyup(function() {
                var bin = $("#number").val().split(' ').join('');
                if (bin.length >= 6) {
                    if (puedeUsarPuntos(bin)) {
                        $('#bin_puntos_div').show();
                    } else {
                        $('#bin_puntos_div').hide();
                    }
                } else {
                    $('#bin_puntos_div').hide();
                }
            });
            // Envío de datos
            $( "#vpos" ).submit(function( event ) {
                $('#ventanaSpinnerModal').modal('show');
                event.preventDefault();
                // Envía datos
                $.post(
                    '{{ route('clientes.vpos.cargo') }}',
                    $("#vpos").serialize(),
                    function(response) {
                        //$('#result').html(response);
                        console.log( "POST Successfull" );
                    },
                    'json'
                )
                .done(function(response) {
                    console.log("Response Successfull");
                    console.log(response);
                    $('#ventanaSpinnerModal').modal('hide');

                    if (response.data.estatus == "completada") {
                        fillTrxResult(response.data);
                        $('#ventanaExitoModal').modal('show');
                    } else if (response.data.estatus == "rechazada") {
                        fillTrxResult(response.data);
                        $('#ventanaExitoModal').modal('show');
                    } else if (response.data.estatus == "pendiente") {
                        fillTrxResult(response.data);
                        $('#ventanaExitoModal').modal('show');
                    } else {
                        fillErrorResult(response.data);
                        $('#ventanaErrorModal_Titulo').html(response.data.estatus);
                        $('#ventanaErrorModal').modal('show');
                    }
                })
                .fail(function(response) {
                    console.log(response);
                    //$('#result').html(data);
                    $('#ventanaSpinnerModal').modal('hide');
                    console.log("error");
                    $('#ventanaErrorModal').modal('show');
                })
                .always(function(response) {
                    //$('#result').html(response.data);
                    console.log("POST Complete");
                    $('#ventanaSpinnerModal').modal('hide');
                });
            });
        });
    </script>

@stop

@section('content')
    @can('listar vpos clientes')

        <div class="content-header">
            <h1>vPOS</h1>
            <br>

            <form id="vpos" name="vpos" action="" method="POST" accept-charset="UTF-8">
                {!! csrf_field() !!}

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="form-group">
                                <label>Comercio</label>
                                <select name="comercio" class="form-control" tabindex="21" >
                                  <option value="{{ $comercio->uuid }}">{{ $comercio->comercio_nombre }} - {{ $comercio->uuid }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Procesador de pago</label>
                                <select name="procesador" class="form-control" tabindex="11">
                                  <option value="bbva">BBVA - EGlobal</option>
                                  <option value="bbva_reversos_comercio">BBVA - EGlobal - Reversos por comercio</option>
                                  <!--option value="bbva_reversos_eglobal">BBVA - EGlobal - Reversos EGlobal</option-->
                                  <option value="prosa" disabled>Prosa</option>
                                  <option value="amex" disabled>AMEX</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Afiliación</label>
                                <select name="afiliacion" class="form-control" tabindex="12" >
                                  <option value="5462742">5462742 - Radio Móvil Dipsa</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">Datos de compra</h3>
                        </div>
                        <div class="box-body">

                                <div class="form-group has-feedback">
                                    <label>Número de orden</label>
                                    <div class="input-group">
                                        <span id="generate-order" tabindex="100" title="Generar número de orden" class="add-on input-group-addon" style="cursor: pointer;"><i class="fa fa-magic"></i></span>
                                        <input id="pedido_numero" name="pedido_numero" type="text" class="form-control" placeholder="Número de orden del comercio" tabindex="22" >
                                    </div>
                                </div>
                                <div class="form-group has-feedback">
                                  <label>Concepto</label>
                                  <input name="pedido_concepto" type="text" class="form-control" placeholder="Concepto del cargo" tabindex="23">
                                </div>
                                <div class="form-group">
                                  <label>Monto</label>
                                    <div class="input-group has-feedback">
                                        <div class="input-group-addon">$</div>
                                        <input id="monto" name="monto" type="text" class="form-control" placeholder="Monto total" required autofocus tabindex="24">
                                        <span id="numero_orden_error" class="help-block hidden">Error</span>
                                        <span class="fa fa-asterisk form-control-feedback" aria-hidden="true"></span>
                                    </div>
                                </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">Datos de promoción</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="form-group">
                                        <label>Promoción de pago</label>
                                        <select id="promocion" name="promocion" class="form-control" tabindex="31">
                                          <option value="normal">Pago normal en una sola exhibición</option>
                                          <option value="msi">Pago a meses sin intereses</option>
                                          <option value="mci">Pago a meses CON intereses</option>
                                          <option value="diferido">Pago diferido en una sola exhibición</option>
                                          <option value="diferido_msi">Pago diferido a meses sin intereses</option>
                                          <option value="diferido_mci">Pago diferido a meses con intereses</option>
                                        </select>
                                    </div>

                                    <div id="promocion_meses_div" class="form-group collapse">
                                        <label>Parcialidades</label>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>
                                                    <input type="radio" name="promocion_meses" value="6" class="minimal" checked tabindex="32"> 6 meses
                                                </label>
                                            </div>
                                            <div class="col-md-4">
                                                <label>
                                                    <input type="radio" name="promocion_meses" value="12" class="minimal" tabindex="32"> 12 meses
                                                </label>
                                            </div>
                                            <div class="col-md-4">
                                                <label>
                                                    <input type="radio" name="promocion_meses" value="18" class="minimal" tabindex="32"> 18 meses
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="promocion_diferido_div" class="form-group collapse">
                                        <label>Diferimiento</label>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>
                                                    <input type="radio" name="promocion_diferimiento" value="1" class="minimal" checked tabindex="33"> 1 mes
                                                </label>
                                            </div>
                                            <div class="col-md-4">
                                                <label>
                                                    <input type="radio" name="promocion_diferimiento" value="3" class="minimal" tabindex="33"> 3 meses
                                                </label>
                                            </div>
                                            <div class="col-md-4">
                                                <label>
                                                    <input type="radio" name="promocion_diferimiento" value="6" class="minimal" tabindex="33"> 6 meses
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="bin_puntos_div" class="form-group collapse">
                                        <div class="form-group">
                                            <label>Pago con puntos</label>
                                            <div class="checkbox checkbox-slider--b-flat checkbox-slider-md">
                                                <label>
                                                    <input id="puntos_pago" name="puntos_pago" type="checkbox" tabindex="34"><span id="puntos_pago_label">Pago con puntos</span>
                                                    <input id="puntos_pago_activo" name="puntos_pago_activo" type="hidden" value="0">
                                                </label>
                                            </div>
                                        </div>
                                        <div id="puntos_rango_div" class="form-group collapse">
                                            <input type="text" id="puntos_rango" name="puntos_rango" value=""  tabindex="35" />
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">Datos de tarjeta</h3>
                        </div>
                        <div class="box-body">

                            <div id="error_result"></div>

                            <div class="row">
                                <div class="col-md-6">

                                    <div class="form-group has-feedback">
                                        <label>Número de tarjeta</label>
                                        <input id="number" name="number" type="text" size="30" tabindex="41" class="form-control" placeholder="Número de tarjeta" required />
                                        <span class="fa fa-asterisk form-control-feedback" aria-hidden="true"></span>
                                    </div>

                                    <div class="form-group has-feedback">
                                        <label>Nombre</label>
                                        <input type="text" id="name" name="name" size="30" tabindex="42" class="form-control" placeholder="Nombre en la tarjeta" />
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="col-3 text-right">
                                                <label for="expiry">Válida hasta</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" name="expiry" size="5" tabindex="43" class="form-control" placeholder="MM/AA" required />
                                        </div>
                                        <div class="col-md-2">
                                            <div class="col-3 text-right">
                                                <label for="expiry">CVV</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" name="cvc" size="5" tabindex="44" class="form-control" placeholder="CVV" />
                                        </div>
                                    </div>


                                </div>
                                <div class="col-md-6">
                                    <div id="card-wrapper"></div>
                                </div>

                            </div>


                        </div>
                    </div>
                </div>
            </div>



            <div class="row">
                <div class="col-md-12">
                    <div class="box box-danger">
                        <div class="box-body table-responsive">
                            <button type="submit" class="btn btn-success"><i class="fa fa-bank"></i> Cargo</button>
                        </div>
                    </div>
                </div>
            </div>

            </form>









	<!-- Ventana spinner -->
	<div class="modal fade" id="ventanaSpinnerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<div id="p1_title">
						<h5 class="modal-title" id="exampleModalLabel">Realizando pago</h5>
					</div>
				</div>
				<div class="modal-body text-center">
					<br>&nbsp;<br>
					Espere por favor...
					<br>&nbsp;<br>
					<br>&nbsp;<br>
					<span class="fa fa-spinner fa-spin fa-3x"></span>
					<br>&nbsp;<br>
					<br>&nbsp;<br>
				</div>
			</div>
		</div>
	</div>

    <!-- Ventana resultado exitoso -->
	<div class="modal fade" id="ventanaExitoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<div id="p1_title">
						<h5 class="modal-title" id="exampleModalLabel">vPOS - Resultado de cargo</h5>
					</div>
				</div>
				<div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Procesador de pago</h3>
                                </div>
                                <div class="box-body box-profile">
                                    <ul class="list-group list-group-unbordered">
                                        <li class="list-group-item">
                                            <b>Transacción ID</b> <span id="transaccion-id" class="pull-right"><a href="" class="btn btn-primary btn-sm" role="button">id</a></span>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Autorización</b> <span id="transaccion-autorizacion" class="pull-right"></span>
                                        </li>
                                        <span id="transaccion-prueba" class="label"></span>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Resumen</h3>
                                </div>
                                <div class="box-body box-profile">
                                    <span class="pull-right">
                                        <h3 id="transaccion-nombre" class="no-margin-top"></h3>
                                    </span>
                                    <h1 class="no-margin-top"><span id="transaccion-monto"></span> <small id="transaccion-moneda-iso_a3">MXP</small></h1>
                                    <i class="fa fa-calendar"></i> &nbsp; <span id="transaccion-fecha"></span>
                                    <br><h3 id="transaccion-descripcion"></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="box box-info">
                                <div class="box-header">
                                    <h3 class="box-title">Forma de pago</h3>
                                </div>
                                <div class="box-body box-profile">
                                    <span id="transaccion-forma_pago-tarjeta-marca" class="label"></span>
                                    <h1 id="transaccion-forma_pago" class="no-margin-top">Desconocida</h1>
                                    <i class="fa fa-credit-card"></i> &nbsp; <span id="transaccion-pan"></span>
                                    <br><i class="fa fa-user"></i> &nbsp; <span id="transaccion-datos_pago-nombre"></span>
                                </div>
                            </div>
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</div>

    <!-- Ventana resultado exitoso -->
	<div class="modal fade" id="ventanaErrorModal" tabindex="-1" role="dialog" aria-labelledby="ventanaErrorModal_Titulo" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<div id="p1_title">
						<h5 class="modal-title" id="ventanaErrorModal_Titulo">Error</h5>
					</div>
				</div>
				<div class="modal-body text-center">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title" id="ventanaErrorModal_Header">Error</h3>
                                </div>
                                <div class="box-body box-profile">
                                    <span id="error-transaccion-descripcion">Ocurrió un error al comunicarse con el banco.</span>
                                </div>
                            </div>
                        </div>
                    </div>
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
