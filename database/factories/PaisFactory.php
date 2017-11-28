<?php

// País model factories
$factory->define(App\Models\Pais::class, function (Faker\Generator $faker) {
    return [
        'nombre' => $faker->country,
        'iso_a2' => strtoupper($faker->lexify('??')),
        'iso_a3' => strtoupper($faker->lexify('???')),
        'iso_n3' => $faker->numberBetween($min = 100, $max = 900),
    ];
});
