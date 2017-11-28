<?php

namespace App\Http\Controllers\Clientes;

use Auth;
use Session;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Prologue\Alerts\Facades\Alert;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use \App\Classes\Estadistica\Transacciones as EstadisticasTransacciones;

class ClientesController extends Controller
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
        // Obtiene datos del dashboard
        $oEstadisticasTransacciones = new EstadisticasTransacciones();
        $aTotalTransaccionesDia = $oEstadisticasTransacciones->total_dia();
        $cTransaccionesDiaXHora = $oEstadisticasTransacciones->dia_xhora();
        $aTrxDET = $oEstadisticasTransacciones->total_dia_xestatus_xtipo(['operacion' => ['pago', 'autorizacion', 'preautorizacion']]);
        // Carga vista
        return view('clientes/index')->with([
            'aTotalTransaccionesDia' => $aTotalTransaccionesDia,
            'cTransaccionesDiaXHora' => $cTransaccionesDiaXHora,
            'aTrxDET' => $aTrxDET,
            'alerts' => Alert::all()]);
    }

}
