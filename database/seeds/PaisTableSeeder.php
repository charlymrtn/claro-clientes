<?php

use Illuminate\Database\Seeder;
use App\Models\Pais;

class PaisTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Inserta valores iniciales
        Pais::create(['id' => 1, 'nombre' => 'Mexico', 'iso_a2' => 'MX', 'iso_a3' => 'MEX', 'iso_n3' => 484]);
        Pais::create(['id' => 2, 'nombre' => 'Argentina', 'iso_a2' => 'AR', 'iso_a3' => 'ARG', 'iso_n3' => 32]);
        Pais::create(['id' => 3, 'nombre' => 'Paraguay', 'iso_a2' => 'PY', 'iso_a3' => 'PRY', 'iso_n3' => 600]);
        Pais::create(['id' => 4, 'nombre' => 'Uruguay', 'iso_a2' => 'UY', 'iso_a3' => 'URY', 'iso_n3' => 858]);
        Pais::create(['id' => 5, 'nombre' => 'Chile', 'iso_a2' => 'CL', 'iso_a3' => 'CHL', 'iso_n3' => 152]);
        Pais::create(['id' => 6, 'nombre' => 'Perú', 'iso_a2' => 'PE', 'iso_a3' => 'PER', 'iso_n3' => 604]);
        Pais::create(['id' => 7, 'nombre' => 'Ecuador', 'iso_a2' => 'EC', 'iso_a3' => 'ECU', 'iso_n3' => 218]);
        Pais::create(['id' => 8, 'nombre' => 'Colombia', 'iso_a2' => 'CO', 'iso_a3' => 'COL', 'iso_n3' => 170]);
        Pais::create(['id' => 9, 'nombre' => 'Guatemala', 'iso_a2' => 'GT', 'iso_a3' => 'GTM', 'iso_n3' => 320]);
        Pais::create(['id' => 10, 'nombre' => 'El Salvador', 'iso_a2' => 'SV', 'iso_a3' => 'SLV', 'iso_n3' => 222]);
        Pais::create(['id' => 11, 'nombre' => 'Honduras', 'iso_a2' => 'HN', 'iso_a3' => 'HND', 'iso_n3' => 340]);
        Pais::create(['id' => 12, 'nombre' => 'Nicaragua', 'iso_a2' => 'NI', 'iso_a3' => 'NIC', 'iso_n3' => 558]);
        Pais::create(['id' => 13, 'nombre' => 'Costa Rica', 'iso_a2' => 'CR', 'iso_a3' => 'CRI', 'iso_n3' => 188]);
        Pais::create(['id' => 14, 'nombre' => 'Panamá', 'iso_a2' => 'PA', 'iso_a3' => 'PAN', 'iso_n3' => 591]);
        Pais::create(['id' => 15, 'nombre' => 'Puerto Rico', 'iso_a2' => 'PR', 'iso_a3' => 'PRI', 'iso_n3' => 630]);
        Pais::create(['id' => 16, 'nombre' => 'República Dominicana', 'iso_a2' => 'DO', 'iso_a3' => 'DOM', 'iso_n3' => 214]);
    }
}
