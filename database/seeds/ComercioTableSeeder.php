<?php

use Illuminate\Database\Seeder;
use App\Models\Comercio;

class ComercioTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Inserta valores iniciales
        Comercio::create([
            'id' => 1,
            'uuid' => '176f76a8-2670-4288-9800-1dd5f031a57e',
            'comercio_nombre' => 'Claro Pagos',
            'comercio_correo' =>  'admin@claropagos.com',
            'comercio_contrasena' => Hash::make('123456'),
            'contacto_nombre' => 'Claro Pagos',
            'contacto_telefono_empresa' => '55 5555-5555',
            'contacto_correo' =>  'admin@claropagos.com',
            'contacto_telefono_comercial' => '55 5555-5555',
            'facturacion_razon_social' => 'Claro Pagos SA de CV',
            'facturacion_responsable_legal' => 'Claro Pagos',
            'facturacion_rfc' => 'CLP170101B2C',
            'facturacion_fecha_alta_legal' => '2017-01-01',
            'facturacion_direccion' => 'Lago Zurich 245',
            'facturacion_codigo_postal' => '11529',
            // -------------------------------------------------------------------------
            // Catálogos
            'actividad_comercial_id' => '10',
            'pais_id' => '1',
            'estado_id' => '9',
            // -------------------------------------------------------------------------
            // @todo: Cambiar a referencias de catálogos cuando estos esten disponibles.
            'facturacion_colonia' => 'Granada',
            'facturacion_municipio' => 'Miguel Hidalgo',
            'facturacion_ciudad' => 'Ciudad de México',
            // -------------------------------------------------------------------------
            'estatus' => 'activo',
        ]);
    }
}
