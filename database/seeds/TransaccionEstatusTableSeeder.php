<?php

use Illuminate\Database\Seeder;
use App\Models\TransaccionEstatus;

class TransaccionEstatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Inserta valores iniciales
        TransaccionEstatus::create(['id'=>'1', 'nombre'=>'Completada', 'descripcion'=>'Transacción completada con éxito', 'color'=>'#00a65a']);
        TransaccionEstatus::create(['id'=>'2', 'nombre'=>'Reembolsada', 'descripcion'=>'Transacción reembolsada exitosamente al cliente', 'color'=>'#f39c12']);
        TransaccionEstatus::create(['id'=>'3', 'nombre'=>'Reembolso Parcial', 'descripcion'=>'Transacción parcial reembolsada exitosamente al cliente', 'color'=>'#f39c12']);
        TransaccionEstatus::create(['id'=>'4', 'nombre'=>'Pendiente', 'descripcion'=>'Transacción pendiente', 'color'=>'#f39c12']);
        TransaccionEstatus::create(['id'=>'5', 'nombre'=>'Autorizada', 'descripcion'=>'Transacción autorizada en espera de aplicarse o cancelarse', 'color'=>'#f39c12']);
        TransaccionEstatus::create(['id'=>'6', 'nombre'=>'Cancelada', 'descripcion'=>'Transacción cancelada', 'color'=>'#dd4b39']);
        TransaccionEstatus::create(['id'=>'7', 'nombre'=>'Rechazada por banco', 'descripcion'=>'Transacción rechazada por procesador de pago', 'color'=>'#dd4b39']);
        TransaccionEstatus::create(['id'=>'8', 'nombre'=>'Rechazada por antifraude', 'descripcion'=>'Transacción rechazada por antifraude', 'color'=>'#dd4b39']);
        TransaccionEstatus::create(['id'=>'9', 'nombre'=>'Contracargo Pendiente', 'descripcion'=>'Transacción en proceso de contracargo', 'color'=>'#f39c12']);
        TransaccionEstatus::create(['id'=>'10', 'nombre'=>'Contracargo Ganado', 'descripcion'=>'Contracargo ganado, transacción completada con éxito', 'color'=>'#00a65a']);
        TransaccionEstatus::create(['id'=>'11', 'nombre'=>'Contracargo Perdido', 'descripcion'=>'Contracargo perdido, transacción reembolsada', 'color'=>'#dd4b39']);
        TransaccionEstatus::create(['id'=>'12', 'nombre'=>'Fallida', 'descripcion'=>'Transacción con error', 'color'=>'#dd4b39']);
        TransaccionEstatus::create(['id'=>'13', 'nombre'=>'Declinada', 'descripcion'=>'Transacción declinada por el banco', 'color'=>'#dd4b39']);
    }
}
