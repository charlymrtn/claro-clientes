<?php

namespace App\Http\Controllers\Clientes\API;

use Log;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Token;

class TokenController extends Controller
{
    protected $oToken;

    /**
     * Crea nueva instancia
     * @return void
     */

    public function __construct(Token $token)
    {
        $this->oToken = $token;
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
                // Datos de la paginación y filtros
                'per_page' => 'numeric|between:5,100',
                'order' => 'max:30|in:id,comercio_uuid,nombre,permisos,created_at',
                'search' => 'max:100',
                'sort' => 'in:asc,desc',
            ]);
            if ($oValidator->fails()) {
                return response()->json(["status" => "fail", "data" => ["errors" => $oValidator->errors()]]);
            }
            // Filtros
            $sFiltro = $oRequest->input('search', false);
            $sComercio = auth()->user()->comercio_uuid;
            // Filtro
            $aToken = $this->oToken
                ->orWhere('comercio_uuid', '=', $sComercio)
                ->where(
                    function ($q) use ($sFiltro) {
                        if ($sFiltro !== false) {
                            return $q
                                ->orWhere('id', 'like', "%$sFiltro%");
                        }
                    }
                )
                ->orderBy($oRequest->input('order', 'created_at'), $oRequest->input('sort', 'asc'))
                ->paginate((int) $oRequest->input('per_page', 25));
            // Obtiene tokens del API
            $cApiTokens = $this->oToken->getApiTokens($sComercio);
            // Une datos
            foreach($aToken->items() as $token) {
                $oApiToken = $cApiTokens->firstWhere('id', $token->id);
                $token->nombre = $oApiToken->name;
                $token->permisos = $oApiToken->scopes;
                $token->revocado = $oApiToken->revoked;
            }
            // Envía datos paginados
            return response()->json(["status" => "success", "data" => ["token" => $aToken]]);
        } catch (\Exception $e) {
            Log::error('Error on ' . __METHOD__ . ' line ' . __LINE__ . ':' . $e->getMessage());
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
        try {
            $oValidator = Validator::make($request->all(), [
                'uuid' => 'uuid',
                'comercio_uuid' => 'numeric',
                'prueba' => 'boolean',
                'operacion' => 'in:pago,preautorizacion,autorizacion,cancelacion',
                'transaccion_estatus_id' => 'numeric',
                'pais_id' => 'numeric',
                'moneda_id' => 'numeric',
                'monto' => 'numeric',
                'forma_pago' => 'min:2',
                'datos_pago' => 'min:2',
                'datos_comercio' => 'min:2',
                'datos_claropagos' => 'min:2',
                'datos_procesador' => 'min:2',
                'datos_destino' => 'min:2',
            ]);
            if ($oValidator->fails()) {
                return response()->json(["status" => "fail", "data" => ["errors" => $oValidator->errors()]]);
            }
            $oToken = Token::create($request->all());
            return response()->json(["status" => "success", "data" => ["id" => $oToken->id]]);
        } catch (\Exception $e) {
            Log::error('Error on ' . __METHOD__ . ' line ' . __LINE__ . ':' . $e->getMessage());
            return response()->json(["status" => "fail", "data" => ["message" => "No se puede guardar el recurso. Error: " . $e->getMessage()]]);
        }
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
