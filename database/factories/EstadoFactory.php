<?php

// Estado model factories
$factory->define(App\Models\Estado::class, function (Faker\Generator $faker) {
    return [
        'nombre' => $faker->state,
        'pais_id' => factory(App\Models\Pais::class)->create()->id,
        'iso_a3' => strtoupper($faker->lexify('???')),
        'created_at' => $faker->dateTimeBetween('-10 days', '-5 days')->format('Y-m-d H:i:s'),
        'updated_at' => $faker->dateTimeBetween('-5 days')->format('Y-m-d H:i:s'),

    ];
});
