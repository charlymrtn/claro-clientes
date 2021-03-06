<?php

namespace App\Http\Controllers\Clientes\API;

use Log;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Transaccion;

class TransaccionController extends Controller
{
    protected $mTransaccion;

    /**
     * Crea nueva instancia
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
    public function index(Request $oRequest)
    {
        // Regresa todos los registros borrados paginados
        try {
            // Verifica las variables para despliegue de datos
            $oValidator = Validator::make($oRequest->all(), [
                'comercio_uuid' => 'uuid|size:36',
                // Datos de la paginación y filtros
                'per_page' => 'numeric|between:5,100',
                'order' => 'max:30|in:uuid,comercio_uuid,prueba,operacion,transaccion_estatus_id,monto,pais_id,moneda_id,forma_pago,datos_pago,datos_antifraude,datos_comercio,datos_claropagos,datos_procesador,datos_destino,created_at',
                'search' => 'max:100',
                'sort' => 'in:asc,desc',
            ]);
            if ($oValidator->fails()) {
                return response()->json(["status" => "fail", "data" => ["errors" => $oValidator->errors()]]);
            }
            // Filtros
            $sFiltro = $oRequest->input('search', false);
            //$sComercio = $oRequest->input('comercio_uuid', auth()->user()->comercio_uuid);
            $sComercio = auth()->user()->comercio_uuid;
            // Filtro
            $aTransaccion = $this->mTransaccion
                ->with('estatus')
                ->where('comercio_uuid', $sComercio)
                ->where(
                    function ($q) use ($sFiltro) {
                        if ($sFiltro !== false) {
                            return $q
                                ->orWhere('prueba', 'like', "%$sFiltro%")
                                ->orWhere('operacion', 'like', "%$sFiltro%")
                                ->orWhere('transaccion_estatus_id', 'like', "%$sFiltro%")
                                ->orWhere('monto', 'like', "%$sFiltro%");
                        }
                    }
                )
                ->orderBy($oRequest->input('order', 'created_at'), $oRequest->input('sort', 'asc'))
                ->paginate((int) $oRequest->input('per_page', 25));
            // Envía datos paginados
            return response()->json(["status" => "success", "data" => ["transaccion" => $aTransaccion]]);
        } catch (\Exception $e) {
            Log::error('Error on ' . __METHOD__ . ' line ' . $e->getLine() . ':' . $e->getMessage());
            return response()->json(["status" => "fail", "data" => ["message" => "No se pueden mostrar los recurso. Error: " . $e->getMessage()]]);
        }
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
        //
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
