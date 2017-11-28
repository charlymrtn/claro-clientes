<?php

//TarjetaMarca model factories
$factory->define(App\Models\TarjetaMarca::class, function (Faker\Generator $faker){
   return [
       'nombre' => $faker->name,
       'rango' => $faker->creditCardType,
       'tamano' => $faker->creditCardNumber
   ];
});