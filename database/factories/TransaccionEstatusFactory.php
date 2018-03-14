<?php

// Transaccion estatus model factories
$factory->define(App\Models\TransaccionEstatus::class, function (Faker\Generator $faker) {
    return [
        'nombre' => $faker->userName,
        'indice' => $faker->userName,
        'descripcion' => $faker->sentence(4),
        'color'=> $faker->colorName,
    ];
});
