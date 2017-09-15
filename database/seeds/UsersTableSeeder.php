<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Inserta valores iniciales
        User::create([
            'id' => 1,
            'name' => 'Super Administrador',
            'email' => 'ahferriz@gmail.com',
            'apellido_paterno' => 'Claro',
            'apellido_materno' => 'Pagos',
            'password' => '$2y$10$wxS/Nr5p8B./LXTlXbot.u7CVXIT4JA4EKW/unxfg2Lk7e1h/fb8a',
            'remember_token' => 'ttWOtfqmekYfsaGyyXtIDc2iQ0wWzMOslHP9xAeEXjsHfoV9py7nl8NFPTz8',
        ]);
        // Inserta valores iniciales usuario
        User::create([
            'id' => 2,
            'email' => 'cliente@claropagos.com',
            'name' => 'Cliente',
            'apellido_paterno' => 'Paterno',
            'apellido_materno' => 'Materno',
            'password' => '$2y$10$wxS/Nr5p8B./LXTlXbot.u7CVXIT4JA4EKW/unxfg2Lk7e1h/fb8a',
            'remember_token' => 'ttWOtfqmekYfsaGyyXtIDc2iQ0wWzMOslHP9xAeEXjsHfoV9py7nl8NFPTz8',
        ]);
        // Inserta valores iniciales admin
        User::create([
            'id' => 3,
            'email' => 'admin@claropagos.com',
            'name' => 'Admin',
            'apellido_paterno' => 'Paterno',
            'apellido_materno' => 'Materno',
            'password' => '$2y$10$wxS/Nr5p8B./LXTlXbot.u7CVXIT4JA4EKW/unxfg2Lk7e1h/fb8a',
            'remember_token' => 'ttWOtfqmekYfsaGyyXtIDc2iQ0wWzMOslHP9xAeEXjsHfoV9py7nl8NFPTz8',
        ]);
    }
}
