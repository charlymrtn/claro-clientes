<?php

// Comercio Model Factories
$factory->define(App\Models\Comercio::class, function (Faker\Generator $faker) {
    return [
        'uuid' => Webpatser\Uuid\Uuid::generate(4)->string,
        'comercio_nombre' => $faker->company,
        'comercio_correo' =>  $faker->unique()->safeEmail,
        //'comercio_contrasena' => $faker->password,
        'contacto_nombre' => $faker->name,
        'contacto_telefono_empresa' => $faker->tollFreePhoneNumber,
        'contacto_correo' =>  $faker->unique()->safeEmail,
        'contacto_telefono_comercial' => $faker->phoneNumber,
        'facturacion_razon_social' => $faker->company . ' ' . $faker->companySuffix,
        'facturacion_responsable_legal' => $faker->name,
        'facturacion_rfc' => $faker->lexify('???') . $faker->date('ymd', 'last year') . $faker->bothify('#?#'),
        'facturacion_fecha_alta_legal' => $faker->dateTimeBetween('-20 days', '-5 days')->format('Y-m-d'),
        'facturacion_direccion' => $faker->streetAddress,
        'facturacion_codigo_postal' => $faker->postcode,
        // -------------------------------------------------------------------------
        // Catálogos
        'actividad_comercial_id' => factory(App\Models\ActividadComercial::class)->create()->id,
        'pais_id' => factory(App\Models\Pais::class)->create()->id,
        'estado_id' => factory(App\Models\Estado::class)->create()->id,
        // -------------------------------------------------------------------------
        // @todo: Cambiar a referencias de catálogos cuando estos esten disponibles.
        'facturacion_colonia' => $faker->city,
        'facturacion_municipio' => $faker->city,
        'facturacion_ciudad' => $faker->city,
        // -------------------------------------------------------------------------
        'estatus' => $faker->randomElement(['nuevo', 'activo', 'inhabilitado']),
        // Timestamps
        'created_at' => $faker->dateTimeBetween('-10 days', '-5 days')->format('Y-m-d H:i:s'),
        'updated_at' => $faker->dateTimeBetween('-5 days')->format('Y-m-d H:i:s'),
    ];
});

$factory->state(App\Models\Comercio::class, 'nuevo', function (Faker\Generator $faker) {
    return [
        'estatus' => 'nuevo',
    ];
});

$factory->state(App\Models\Comercio::class, 'activo', function (Faker\Generator $faker) {
    return [
        'estatus' => 'activo',
    ];
});

$factory->state(App\Models\Comercio::class, 'inhabilitado', function (Faker\Generator $faker) {
    return [
        'estatus' => 'inhabilitado',
    ];
});
