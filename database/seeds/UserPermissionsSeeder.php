<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Inserta valores iniciales

        // Permisos
        Permission::create(['id' => 1, 'name' => 'accesar backend', 'guard_name' => 'web']);
        Permission::create(['id' => 2, 'name' => 'listar sistema', 'guard_name' => 'web']);
        Permission::create(['id' => 3, 'name' => 'editar sistema', 'guard_name' => 'web']);
        Permission::create(['id' => 4, 'name' => 'ver perfil', 'guard_name' => 'web']);
        Permission::create(['id' => 5, 'name' => 'editar perfil', 'guard_name' => 'web']);
        Permission::create(['id' => 6, 'name' => 'listar catalogos', 'guard_name' => 'web']);
        Permission::create(['id' => 7, 'name' => 'editar catalogos', 'guard_name' => 'web']);
        Permission::create(['id' => 8, 'name' => 'listar actividad', 'guard_name' => 'web']);

        Permission::create(['id' => 10, 'name' => 'listar usuarios', 'guard_name' => 'web']);
        Permission::create(['id' => 11, 'name' => 'editar usuario', 'guard_name' => 'web']);
        Permission::create(['id' => 12, 'name' => 'crear usuario', 'guard_name' => 'web']);
        Permission::create(['id' => 13, 'name' => 'borrar usuario', 'guard_name' => 'web']);
        Permission::create(['id' => 14, 'name' => 'recuperar usuario', 'guard_name' => 'web']);
        Permission::create(['id' => 15, 'name' => 'eliminar usuario', 'guard_name' => 'web']);

        Permission::create(['id' => 20, 'name' => 'listar permisos', 'guard_name' => 'web']);
        Permission::create(['id' => 21, 'name' => 'editar permiso', 'guard_name' => 'web']);
        Permission::create(['id' => 22, 'name' => 'crear permiso', 'guard_name' => 'web']);
        Permission::create(['id' => 23, 'name' => 'borrar permiso', 'guard_name' => 'web']);

        Permission::create(['id' => 40, 'name' => 'listar comercios', 'guard_name' => 'web']);
        Permission::create(['id' => 41, 'name' => 'editar comercio', 'guard_name' => 'web']);
        Permission::create(['id' => 42, 'name' => 'crear comercio', 'guard_name' => 'web']);
        Permission::create(['id' => 43, 'name' => 'borrar comercio', 'guard_name' => 'web']);
        Permission::create(['id' => 44, 'name' => 'recuperar comercio', 'guard_name' => 'web']);
        Permission::create(['id' => 45, 'name' => 'eliminar comercio', 'guard_name' => 'web']);

        Permission::create(['id' => 50, 'name' => 'listar transacciones', 'guard_name' => 'web']);
        Permission::create(['id' => 51, 'name' => 'editar transacciones', 'guard_name' => 'web']);

        // Permisos Clientes
        Permission::create(['id' => 101, 'name' => 'accesar backend clientes', 'guard_name' => 'web']);
        Permission::create(['id' => 104, 'name' => 'ver perfil clientes', 'guard_name' => 'web']);
        Permission::create(['id' => 105, 'name' => 'editar perfil clientes', 'guard_name' => 'web']);
        Permission::create(['id' => 106, 'name' => 'listar catalogos clientes', 'guard_name' => 'web']);
        Permission::create(['id' => 108, 'name' => 'listar actividad clientes', 'guard_name' => 'web']);

        Permission::create(['id' => 150, 'name' => 'listar transacciones clientes', 'guard_name' => 'web']);
        Permission::create(['id' => 151, 'name' => 'editar transacciones clientes', 'guard_name' => 'web']);

        Permission::create(['id' => 160, 'name' => 'listar tokens clientes', 'guard_name' => 'web']);
        Permission::create(['id' => 161, 'name' => 'editar tokens clientes', 'guard_name' => 'web']);
        Permission::create(['id' => 162, 'name' => 'crear tokens clientes', 'guard_name' => 'web']);
        Permission::create(['id' => 163, 'name' => 'revocar tokens clientes', 'guard_name' => 'web']);

        Permission::create(['id' => 170, 'name' => 'listar vpos clientes', 'guard_name' => 'web']);

        // Roles y permisos

        // Superadmin
        $rol = Role::create(['id' => 11, 'name' => 'superadmin', 'guard_name' => 'web']);
        $rol->syncPermissions(Permission::all());
        // Admin
        $rol = Role::create(['id' => 10, 'name' => 'admin', 'guard_name' => 'web']);
        $rol->permissions()->sync([1, 2, 4, 5, 6, 7, 8, 10, 11, 12, 13, 20, 40, 41, 42, 43, 50, 51]);
        // Cliente
        $rol = Role::create(['id' => 100, 'name' => 'cliente', 'guard_name' => 'web']);
        $rol->permissions()->sync([101, 104, 105, 106, 108, 150, 151, 160, 161, 162, 163]);

        // Usuarios <-> Roles
        DB::table('model_has_roles')->insert([
            ['role_id' => 11, 'model_id' => 1, 'model_type' => 'App\Models\User'],
            ['role_id' => 10, 'model_id' => 2, 'model_type' => 'App\Models\User'],
            ['role_id' => 100, 'model_id' => 3, 'model_type' => 'App\Models\User'],
        ]);
        DB::table('model_has_permissions')->insert([
            ['permission_id' => 170, 'model_id' => 3, 'model_type' => 'App\Models\User'],
        ]);


        // Permisos API
        Permission::create(['id' => 201, 'name' => 'accesar api', 'guard_name' => 'api']);
        // Roles y permisos API
        $rol = Role::create(['id' => 211, 'name' => 'superadmin', 'guard_name' => 'api']);
        $rol->permissions()->sync([201]);
        // Usuarios <-> Roles
        DB::table('model_has_roles')->insert([
            ['role_id' => 211, 'model_id' => 1, 'model_type' => 'App\Models\User'],
        ]);

        // Clientes API
        DB::table('oauth_clients')->insert([
            ['id' => 1, 'user_id' => 1, 'name' => 'API Personal Access Client - Superadmin', 'secret' => 'xYZk4PMqcR0hGhsroNfjnUmTUGpqlSFsL3CzNDvo', 'redirect' => '/auth/callback', 'personal_access_client' => 1, 'password_client' => 0, 'revoked' => 0],
        ]);
        DB::table('oauth_access_tokens')->insert([
            ['id' => 'fe2853f9ce1981cf45c80f48440593a232cc88fb7eb3bebce683663b1a27b2327eb5b5f90ba0ab7f', 'user_id' => 1, 'client_id' => 1, 'name' => 'API Personal Access Token - Superadmin', 'scopes' => '["superadmin"]', 'revoked' => 0, 'expires_at' => '2020-01-13 02:10:09'],
        ]);
        DB::table('oauth_personal_access_clients')->insert([
            ['id' => 1, 'client_id' => 1]
        ]);

    }
}
