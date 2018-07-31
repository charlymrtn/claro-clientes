<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Prologue\Alerts\Facades\Alert;
// TMP Fix
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // TMP Aplica fix
        $this->fix();
        // Carga vista
        return view('admin/admin');
    }

    public function fix()
    {
        // Agrega nuevos permisos para tokens
        $aPermisos = [];

        $oPermiso = Permission::findByName('listar tokens clientes');
        if ($oPermiso == null) {
            $oPermiso = Permission::create(['id' => 160, 'name' => 'listar tokens clientes', 'guard_name' => 'web']);
        }
        $aPermisos[] = $oPermiso->id;

        $oPermiso = Permission::findByName('editar tokens clientes');
        if ($oPermiso == null) {
            $oPermiso = Permission::create(['id' => 161, 'name' => 'editar tokens clientes', 'guard_name' => 'web']);
        }
        $aPermisos[] = $oPermiso->id;

        $oPermiso = Permission::findByName('crear tokens clientes');
        if ($oPermiso == null) {
            $oPermiso = Permission::create(['id' => 162, 'name' => 'crear tokens clientes', 'guard_name' => 'web']);
        }
        $aPermisos[] = $oPermiso->id;

        $oPermiso = Permission::findByName('revocar tokens clientes');
        if ($oPermiso == null) {
            $oPermiso = Permission::create(['id' => 163, 'name' => 'revocar tokens clientes', 'guard_name' => 'web']);
        }
        $aPermisos[] = $oPermiso->id;

        $oPermiso = Permission::findByName('listar vpos clientes');
        if ($oPermiso == null) {
            $oPermiso = Permission::create(['id' => 170, 'name' => 'listar vpos clientes', 'guard_name' => 'web']);
        }
        $aPermisos[] = $oPermiso->id;

        // Asigna permisos a roles
        $oRole = Role::find(100); // Cliente
        if (!empty($oRole)) {
            $aPermisos = array_unique(array_merge($oRole->permissions()->get()->pluck('id')->toArray(), $aPermisos));
            $oRole->permissions()->sync($aPermisos);
            Alert::error('Fix aplicado')->flash();
        }

    }

}