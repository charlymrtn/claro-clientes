<?php

use Illuminate\Database\Seeder;
use App\Models\TarjetaMarca;

class TarjetaMarcaTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Inserta valores iniciales
        TarjetaMarca::create([
            'id' => 1,
            'nombre' => 'Visa',
            'rango' => '4026, 4506, 4913',
            'tamano' => '13 - 16',
        ]);
        // Inserta valores iniciales usuario
        TarjetaMarca::create([
            'id' => 2,
            'nombre' => 'MasterCard',
            'rango' => '51, 52, 53',
            'tamano' => '16 - 19',
        ]);
        // Inserta valores iniciales usuario
        TarjetaMarca::create([
            'id' => 3,
            'nombre' => 'American Express',
            'rango' => '34, 37',
            'tamano' => '15',
        ]);
    }
}
