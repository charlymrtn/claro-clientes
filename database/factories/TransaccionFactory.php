<?php

// Transacciones factory
$factory->define(App\Models\Transaccion::class, function (Faker\Generator $faker) {

    // Obtiene datos para no generar objetos extra

    // Comercios
    $aComercioIds = \App\Models\Comercio::select('id')->get()->toArray();
    if (empty($aComercioIds)) {
        $iComercioId = factory(App\Models\Comercio::class)->create()->id;
    } else {
        $iComercioId = $faker->randomElement($aComercioIds)["id"];
    }

    // Monedas
    $aMonedas = \App\Models\Moneda::select('id', 'pais_id')->get()->toArray();
    if (empty($aMonedas)) {
        $aMoneda = factory(App\Models\Moneda::class)->create();
    } else {
        $aMoneda = $faker->randomElement($aMonedas);
    }

    // Estatus transacción
    $aTransaccionEstatusIds = \App\Models\TransaccionEstatus::select('id')->get()->toArray();
    if (empty($aTransaccionEstatusIds)) {
        $iTransaccionEstatusId = factory(App\Models\TransaccionEstatus::class)->create()->id;
    } else {
        $iTransaccionEstatusId = $faker->randomElement($aTransaccionEstatusIds)["id"];
    }

    // Datos procesador
    // Bancomer - ISO
    $jDatosProcesador = json_encode(['response_code' => '00', 'response_description' => 'Aprobada', 'error' => false]);
    $jDatosAntifraude = json_encode(['response_code' => '100', 'response_description' => 'Successful transaction', 'error' => false]);
    if ($iTransaccionEstatusId == 7) {
        $jDatosProcesador = $faker->randomElement([
            json_encode(['error' => true, 'response_code' => '41', 'response_description' => 'Tarjeta perdida']),
            json_encode(['error' => true, 'response_code' => '43', 'response_description' => 'Tarjeta robada']),
            json_encode(['error' => true, 'response_code' => '47', 'response_description' => 'Límite excedido']),
            json_encode(['error' => true, 'response_code' => '49', 'response_description' => 'CV2 inválido']),
            json_encode(['error' => true, 'response_code' => '50', 'response_description' => 'Límite de transacciones rechazadas']),
            json_encode(['error' => true, 'response_code' => '61', 'response_description' => 'Excede límite de monto']),
            json_encode(['error' => true, 'response_code' => '62', 'response_description' => 'Tarjeta restringida']),
            json_encode(['error' => true, 'response_code' => '76', 'response_description' => 'Cuenta bloqueada']),
            json_encode(['error' => true, 'response_code' => '82', 'response_description' => 'CVV2 incorrecto']),
            json_encode(['error' => true, 'response_code' => '83', 'response_description' => 'Rechazada']),
            json_encode(['error' => true, 'response_code' => 'C5', 'response_description' => 'Sin autorización de venta']),
        ]);
    } else if ($iTransaccionEstatusId == 13) {
        $jDatosProcesador = $faker->randomElement([
            json_encode(['error' => true, 'response_code' => '05', 'response_description' => 'Declinada']),
            json_encode(['error' => true, 'response_code' => '05', 'response_description' => 'Declinada']),
            json_encode(['error' => true, 'response_code' => '05', 'response_description' => 'Declinada']),
            json_encode(['error' => true, 'response_code' => '05', 'response_description' => 'Declinada']),
            json_encode(['error' => true, 'response_code' => '05', 'response_description' => 'Declinada']),
            json_encode(['error' => true, 'response_code' => '30', 'response_description' => 'Declinada - Error de formato']),
        ]);
    } else if ($iTransaccionEstatusId == 8) {
        // Datos antifraude
        // Cybersource - https://support.cybersource.com/cybskb/index?page=content&id=C156
        $jDatosProcesador = '{}';
        $jDatosAntifraude = $faker->randomElement([
            json_encode(['error' => true, 'response_code' => '102', 'response_description' => 'Declined - One or more fields in the request contains invalid data.']),
            json_encode(['error' => true, 'response_code' => '150', 'response_description' => 'Error - General system failure.']),
            json_encode(['error' => true, 'response_code' => '152', 'response_description' => 'Error: The request was received, but a service did not finish running in time.
']),
            json_encode(['error' => true, 'response_code' => '200', 'response_description' => 'Soft Decline - Approved by the issuing bank but declined by CyberSource because it did not pass the Address Verification Service (AVS) check.']),
            json_encode(['error' => true, 'response_code' => '202', 'response_description' => 'Decline - Expired card or expiration date does not match the date the issuing bank has on file.']),
            json_encode(['error' => true, 'response_code' => '205', 'response_description' => 'Decline - Stolen or lost card.']),
            json_encode(['error' => true, 'response_code' => '209', 'response_description' => 'Decline - card verification number (CVN) did not match.']),
            json_encode(['error' => true, 'response_code' => '210', 'response_description' => 'Decline - The card has reached the credit limit.']),
            json_encode(['error' => true, 'response_code' => '211', 'response_description' => 'Decline - Invalid Card Verification Number (CVN).']),
            json_encode(['error' => true, 'response_code' => '220', 'response_description' => 'Decline - Generic Decline.']),
            json_encode(['error' => true, 'response_code' => '222', 'response_description' => 'Decline - Customer account is frozen.']),
        ]);
    }

    // Genera modelo
    return [
        'uuid' => $faker->uuid,
        'prueba' => false,
        // Datos de operacion
        'operacion' => $faker->randomElement([
            'pago', 'pago', 'pago', 'pago', 'pago', 'pago', 'pago',// Mayor probabilidad
            'preautorizacion', 'autorizacion', 'cancelacion'
        ]),
        'monto' => $faker->randomFloat(2, 10, 350000),
        'forma_pago' => $faker->randomElement([
            'tarjeta-credito', 'tarjeta-credito', 'tarjeta-credito', 'tarjeta-credito', 'tarjeta-credito', 'tarjeta-credito', 'tarjeta-credito', // 50% de probabilidad
            'tarjeta-debito', // Todavía no existen estos tipos de pagos: 'telmex-recibo', 'paypal', 'applepay', 'androidpay', 'visa-checkout', 'masterpass',
        ]),
        'datos_pago' => $faker->randomElement(['{}']),
        'datos_antifraude' => $jDatosAntifraude,
        'datos_comercio' => $faker->randomElement(['{}']),
        'datos_claropagos' => $faker->randomElement(['{}']),
        'datos_procesador' => $jDatosProcesador,
        'datos_destino' => $faker->randomElement(['{}']),
        // Catálogos
        'comercio_id' => $iComercioId,
        'transaccion_estatus_id' => $iTransaccionEstatusId,
        'pais_id' => $aMoneda['pais_id'],
        'moneda_id' => $aMoneda['id'],
        // Fechas
        'created_at' => $faker->dateTimeBetween('-1 month')->format('Y-m-d H:i:s'),
        'updated_at' => $faker->dateTimeBetween('-5 days')->format('Y-m-d H:i:s'),
    ];
});

$factory->state(App\Models\Transaccion::class, 'pago', function (Faker\Generator $faker) {
    return [
        'operacion' => 'pago',
    ];
});

$factory->state(App\Models\Transaccion::class, 'preautorizacion', function (Faker\Generator $faker) {
    return [
        'operacion' => 'preautorizacion',
    ];
});

$factory->state(App\Models\Transaccion::class, 'autorizacion', function (Faker\Generator $faker) {
    return [
        'operacion' => 'autorizacion',
    ];
});

$factory->state(App\Models\Transaccion::class, 'cancelacion', function (Faker\Generator $faker) {
    return [
        'operacion' => 'cancelacion',
    ];
});
