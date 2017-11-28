<?php

// País model factories
$factory->define(App\Models\Moneda::class, function (Faker\Generator $faker) {
    return [
        'nombre' => $faker->country,
        'iso_a3' => strtoupper($faker->lexify('???')),
        'pais_id'=> factory(App\Models\Pais::class)->create()->id,
    ];
});
