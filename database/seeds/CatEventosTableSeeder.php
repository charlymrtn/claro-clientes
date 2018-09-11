<?php

use Illuminate\Database\Seeder;
use App\Models\cat__evento as Evento;

class CatEventosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Evento::create(['id'=>'1', 'nombre'=>'cargo.exitoso', 'tipo_evento'=>'Exitoso', 'metodo'=>'Cargo']);
        Evento::create(['id'=>'2', 'nombre'=>'cargo.fallido', 'tipo_evento'=>'Fallido', 'metodo'=>'Cargo']);
        Evento::create(['id'=>'3', 'nombre'=>'cargo.cancelado', 'tipo_evento'=>'Cancelado', 'metodo'=>'Cargo']);
        Evento::create(['id'=>'4', 'nombre'=>'cargo.reembolsado', 'tipo_evento'=>'Reembolsado', 'metodo'=>'Cargo']);

        Evento::create(['id'=>'5', 'nombre'=>'suscripcion.creada', 'tipo_evento'=>'Creada', 'metodo'=>'Suscripción']);
        Evento::create(['id'=>'6', 'nombre'=>'suscripcion.actualizada', 'tipo_evento'=>'Actualizada', 'metodo'=>'Suscripción']);
        Evento::create(['id'=>'7', 'nombre'=>'suscripcion.cancelada', 'tipo_evento'=>'Cancelada', 'metodo'=>'Suscripción']);
        Evento::create(['id'=>'8', 'nombre'=>'suscripcion.pago.exitoso', 'tipo_evento'=>'Pago.exitoso', 'metodo'=>'Suscripción']);
        Evento::create(['id'=>'9', 'nombre'=>'suscripcion.pago.fallido', 'tipo_evento'=>'Pago.fallido', 'metodo'=>'Suscripción']);
    }
}
