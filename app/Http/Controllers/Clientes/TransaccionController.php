<?php

namespace App\Http\Controllers\Clientes;

use Spatie\Activitylog\Models\Activity;
use view;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Prologue\Alerts\Facades\Alert;
use \App\Classes\Estadistica\Transacciones as EstadisticasTransacciones;
use App\Models\Transaccion;
use App\Models\TransaccionEstatus;
use Validator;

class TransaccionController extends Controller
{
    protected $mTransaccion;

    /**
     * Crea nueva instancia.
     *
     * @return void
     */
    public function __construct(Transaccion $transaccion)
    {
        $this->mTransaccion = $transaccion;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Obtiene datos del dashboard
        $oEstadisticasTransacciones = new EstadisticasTransacciones();
        $aTotalTransaccionesDia = $oEstadisticasTransacciones->total_dia();
        $aTotalTransaccionesMes = $oEstadisticasTransacciones->total_mes();
        // Carga vista
        return view('clientes/transaccion/index')->with([
            'aTotalTransaccionesDia' => $aTotalTransaccionesDia,
            'aTotalTransaccionesMes' => $aTotalTransaccionesMes,
            'alerts' => Alert::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Muestra el objeto solicitado
        try {
            $oValidator = Validator::make(['id' => $id], [
                // todo: agregar validacion
                'id' => 'required',
            ]);
            if ($oValidator->fails()) {
                return response()->json(["status" => "fail", "data" => ["errors" => $oValidator->errors()]]);
            }
            // Busca transaccion
            $oTransaction = $this->mTransaccion->where('uuid', $id)->get()->first();
            if ($oTransaction == null) {
                return view('admin/errores/no_encontrado')->with(['model' => 'Transaccion', 'id' => $id]);
            } else {
                //Obtiene histórico
                $historico = Activity::where([['subject_id', '=', $id], ['subject_type', '=', 'App\Models\Transaccion']])->get();
                // Muestra plantilla
                return view('admin/transaccion/detalle')->with(['transaccion' => $oTransaction, 'historico' => $historico, 'alerts' => Alert::all()]);
            }
        } catch (\Exception $e) {
            // Registra error
            Log::error('Error en ' . __METHOD__ . ' línea ' . __LINE__ . ':' . $e->getMessage());
            // Muestra plantilla de error
            return view('admin/errores/excepcion')->with(['exception' => $e]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
