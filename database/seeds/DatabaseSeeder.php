<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Usuarios iniciales
        $this->call(UserTableSeeder::class);
        $this->call(UserPermissionsSeeder::class);
        $this->call(ComercioTableSeeder::class);
        // Catálogos iniciales
        $this->call(PaisTableSeeder::class);
        $this->call(EstadoTableSeeder::class);
        $this->call(ActividadComercialTableSeeder::class);
        $this->call(MonedaTableSeeder::class);
        $this->call(TarjetaMarcaTableSeeder::class);
        $this->call(TransaccionEstatusTableSeeder::class);
        $this->call(TokenTableSeeder::class);

        //cat eventos
        $this->call(CatEventosTableSeeder::class);
    }
}
