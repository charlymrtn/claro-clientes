<?php

// Estado model factories
$factory->define(App\Models\ActividadComercial::class, function (Faker\Generator $faker) {
    return [
        'nombre' => $faker->name,
    ];
});

