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

    <script>
        jQuery(function($){
            // Inicializa rango de puntos
            $("#puntos_rango").ionRangeSlider({
                min: 0, max: 0, from: 0, grid: true, prefix: "$", step: 0.05
            });
            var rangoPuntos = $("#puntos_rango").data("ionRangeSlider");
            // Activa pago con puntos
            $('#puntos_pago').change(function () {
                if ($(this).prop("checked")) {
                    $('#puntos_pago_activo').val(1);
                    $('#puntos_rango_div').show();
                } else {
                    $('#puntos_pago_activo').val(0);
                    $('#puntos_rango_div').hide();
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
            $('#p1_f1').card({
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
            var binesPuntos = [
                "410180", "410181", "441311", "455500", "455503", "455504", "455505", "455508", "455529", "455540", "455545", "493160", "493161", "493162", "477210", "477212", "477213", "477214", "477291", "477292", "481514", "481515",
                "542010", "542977", "544053", "544551", "547077", "547086", "547095", "547155", "547156", "554629", "535875", "542015", "542073"
            ];
            $("#number").keyup(function() {
                var bin = $("#number").val().split(' ').join('');
                if (bin.length >= 6) {
                    bin = bin.substring(0, 6);
                    if ($.inArray(bin, binesPuntos) >= 0) {
                        $('#bin_puntos_div').show();
                    } else {
                        $('#bin_puntos_div').hide();
                    }
                } else {
                    $('#bin_puntos_div').hide();
                }
            });

        });
        function generateOrderId(size) {
            var text = "";
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
            for (var i = 0; i < size; i++) {
                text += possible.charAt(Math.floor(Math.random() * possible.length));
            }
            return text;
        }
    </script>

@stop

@section('content')
    @can('listar vpos clientes')

        <div class="content-header">
            <h1>vPOS</h1>
            <br>

            <form id="p1_f1" name="p1_f1" action="{{ route('vpos.cargo') }}" method="POST" accept-charset="UTF-8">
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



    @else
        <div class="alert alert-danger alert-dismissible">
            <h4><i class="icon fa fa-ban"></i> Restringido</h4>
            No cuenta con los permisos necesarios para poder ver este recurso.
        </div>
    @endcan
@stop
