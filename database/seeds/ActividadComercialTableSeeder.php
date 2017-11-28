<?php

use Illuminate\Database\Seeder;
use App\Models\ActividadComercial;

class ActividadComercialTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ActividadComercial::create(['id'=> '1','nombre'=> 'Actividades recreativas y de entretenimiento']);
        ActividadComercial::create(['id'=> '2','nombre'=> 'Albañilería/Construcción']);
        ActividadComercial::create(['id'=> '3','nombre'=> 'Alimentos preparados']);
        ActividadComercial::create(['id'=> '4','nombre'=> 'Arte/Diseño/Manualidades']);
        ActividadComercial::create(['id'=> '5','nombre'=> 'Artículos deportivos']);
        ActividadComercial::create(['id'=> '6','nombre'=> 'Asociaciones/Clubes']);
        ActividadComercial::create(['id'=> '7','nombre'=> 'Automotrices']);
        ActividadComercial::create(['id'=> '8','nombre'=> 'Bares/Clubes']);
        ActividadComercial::create(['id'=> '9','nombre'=> 'Catering/Banquetes']);
        ActividadComercial::create(['id'=> '10','nombre'=> 'Contables/Financieros/Legales']);
        ActividadComercial::create(['id'=> '11','nombre'=> 'Decoración']);
        ActividadComercial::create(['id'=> '12','nombre'=> 'Dentistas']);
        ActividadComercial::create(['id'=> '13','nombre'=> 'Educación']);
        ActividadComercial::create(['id'=> '14','nombre'=> 'Electrónicos y videojuegos']);
        ActividadComercial::create(['id'=> '15','nombre'=> 'Equipos de cómputo/Consumibles de oficina']);
        ActividadComercial::create(['id'=> '16','nombre'=> 'Galerías de arte/Antigüedades']);
        ActividadComercial::create(['id'=> '17','nombre'=> 'Gimnasios deportivos']);
        ActividadComercial::create(['id'=> '18','nombre'=> 'Hoteles']);
        ActividadComercial::create(['id'=> '19','nombre'=> 'Juguetes/Pasatiempos']);
        ActividadComercial::create(['id'=> '20','nombre'=> 'Limpieza']);
        ActividadComercial::create(['id'=> '21','nombre'=> 'Mercados']);
        ActividadComercial::create(['id'=> '22','nombre'=> 'Mudanza/Mensajería']);
        ActividadComercial::create(['id'=> '23','nombre'=> 'Mueblerías']);
        ActividadComercial::create(['id'=> '24','nombre'=> 'Música/Cine']);
        ActividadComercial::create(['id'=> '25','nombre'=> 'Organizaciones benéficas']);
        ActividadComercial::create(['id'=> '26','nombre'=> 'Otro tipo de venta al detalle']);
        ActividadComercial::create(['id'=> '27','nombre'=> 'Peluquerías/Estéticas/Spas']);
        ActividadComercial::create(['id'=> '28','nombre'=> 'Periódicos/Revistas/Librerías']);
        ActividadComercial::create(['id'=> '29','nombre'=> 'Personales']);
        ActividadComercial::create(['id'=> '30','nombre'=> 'Regalos/Florería']);
        ActividadComercial::create(['id'=> '31','nombre'=> 'Restaurantes/Cafeterías']);
        ActividadComercial::create(['id'=> '32','nombre'=> 'Ropa y accesorios']);
        ActividadComercial::create(['id'=> '33','nombre'=> 'Servicios Médicos']);
        ActividadComercial::create(['id'=> '34','nombre'=> 'Taxis/Limusinas']);
        ActividadComercial::create(['id'=> '35','nombre'=> 'Tecnologías de información servicios y consultorías']);
        ActividadComercial::create(['id'=> '36','nombre'=> 'Tiendas de abarrotes']);
        ActividadComercial::create(['id'=> '37','nombre'=> 'Turismo']);
        ActividadComercial::create(['id'=> '38','nombre'=> 'Veterinarios']);

       }
}
