<?php

use Illuminate\Database\Seeder;
use App\Models\Moneda;

class MonedaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Inserta valores iniciales
        Moneda::create(['id' => 1, 'nombre' => 'Peso Mexicano', 'iso_a3' => 'MXN', 'pais_id'=> 1]);

    }


}
