<?php

use Illuminate\Database\Seeder;
use App\Models\Estado;

class EstadoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Inserta valores iniciales
        Estado::create(['id'=>'1', 'pais_id'=>'1', 'nombre'=>'Aguascalientes', 'iso_a3'=>'AGU']);
        Estado::create(['id'=>'2', 'pais_id'=>'1', 'nombre'=>'Baja California', 'iso_a3'=>'BCN']);
        Estado::create(['id'=>'3', 'pais_id'=>'1', 'nombre'=>'Baja California Sur', 'iso_a3'=>'BCS']);
        Estado::create(['id'=>'4', 'pais_id'=>'1', 'nombre'=>'Campeche', 'iso_a3'=>'CAM']);
        Estado::create(['id'=>'5', 'pais_id'=>'1', 'nombre'=>'Chiapas', 'iso_a3'=>'CHP']);
        Estado::create(['id'=>'6', 'pais_id'=>'1', 'nombre'=>'Chihuahua', 'iso_a3'=>'CHH']);
        Estado::create(['id'=>'7', 'pais_id'=>'1', 'nombre'=>'Coahuila de Zaragoza', 'iso_a3'=>'COA']);
        Estado::create(['id'=>'8', 'pais_id'=>'1', 'nombre'=>'Colima', 'iso_a3'=>'COL']);
        Estado::create(['id'=>'9', 'pais_id'=>'1', 'nombre'=>'Distrito Federal', 'iso_a3'=>'CMX']);
        Estado::create(['id'=>'10', 'pais_id'=>'1', 'nombre'=>'Durango', 'iso_a3'=>'DUR']);
        Estado::create(['id'=>'11', 'pais_id'=>'1', 'nombre'=>'Estado Mexico', 'iso_a3'=>'MEX']);
        Estado::create(['id'=>'12', 'pais_id'=>'1', 'nombre'=>'Guanajuato', 'iso_a3'=>'GUA']);
        Estado::create(['id'=>'13', 'pais_id'=>'1', 'nombre'=>'Guerrero', 'iso_a3'=>'GRO']);
        Estado::create(['id'=>'14', 'pais_id'=>'1', 'nombre'=>'Hidalgo', 'iso_a3'=>'HID']);
        Estado::create(['id'=>'15', 'pais_id'=>'1', 'nombre'=>'Jalisco', 'iso_a3'=>'JAL']);
        Estado::create(['id'=>'17', 'pais_id'=>'1', 'nombre'=>'Michoacan', 'iso_a3'=>'MIC']);
        Estado::create(['id'=>'18', 'pais_id'=>'1', 'nombre'=>'Morelos', 'iso_a3'=>'MOR']);
        Estado::create(['id'=>'19', 'pais_id'=>'1', 'nombre'=>'Nayarit', 'iso_a3'=>'NAY']);
        Estado::create(['id'=>'20', 'pais_id'=>'1', 'nombre'=>'Nuevo Leon', 'iso_a3'=>'NLE']);
        Estado::create(['id'=>'21', 'pais_id'=>'1', 'nombre'=>'Oaxaca', 'iso_a3'=>'OAX']);
        Estado::create(['id'=>'22', 'pais_id'=>'1', 'nombre'=>'Puebla', 'iso_a3'=>'PUE']);
        Estado::create(['id'=>'23', 'pais_id'=>'1', 'nombre'=>'Queretaro', 'iso_a3'=>'QUE']);
        Estado::create(['id'=>'24', 'pais_id'=>'1', 'nombre'=>'Quintana Roo', 'iso_a3'=>'ROO']);
        Estado::create(['id'=>'25', 'pais_id'=>'1', 'nombre'=>'San Luis Potosi', 'iso_a3'=>'SLP']);
        Estado::create(['id'=>'26', 'pais_id'=>'1', 'nombre'=>'Sinaloa', 'iso_a3'=>'SIN']);
        Estado::create(['id'=>'27', 'pais_id'=>'1', 'nombre'=>'Sonora', 'iso_a3'=>'SON']);
        Estado::create(['id'=>'28', 'pais_id'=>'1', 'nombre'=>'Tabasco', 'iso_a3'=>'TAB']);
        Estado::create(['id'=>'29', 'pais_id'=>'1', 'nombre'=>'Tamaulipas', 'iso_a3'=>'TAM']);
        Estado::create(['id'=>'30', 'pais_id'=>'1', 'nombre'=>'Tlaxcala', 'iso_a3'=>'TLA']);
        Estado::create(['id'=>'31', 'pais_id'=>'1', 'nombre'=>'Veracruz Llave', 'iso_a3'=>'VER']);
        Estado::create(['id'=>'32', 'pais_id'=>'1', 'nombre'=>'Yucatan', 'iso_a3'=>'YUC']);
        Estado::create(['id'=>'33', 'pais_id'=>'1', 'nombre'=>'Zacatecas', 'iso_a3'=>'ZAC']);
    }
}
