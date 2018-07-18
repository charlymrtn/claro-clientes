<?php

namespace App\Http\Controllers\API\Admin;

use Log;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Transaccion;
use App\Models\TransaccionEstatus;
use App\Models\Pais;
use App\Models\Moneda;

class TransaccionController extends Controller
{

    /**
     * Transaccion instance.
     *
     * @var \App\Models\Transaccion
     */
    protected $mTransaccion;

    /**
     * TransaccionEstatus instance.
     *
     * @var \App\Models\TransaccionEstatus
     */
    protected $mTransaccionEstatus;

    /**
     * Pais instance.
     *
     * @var \App\Models\Pais
     */
    protected $mPais;

    /**
     * Moneda instance.
     *
     * @var \App\Models\Moneda
     */
    protected $mMoneda;

    /**
     * Create a client controller instance.
     *
     * @param App\Models\User $usuario
     * @param \Laravel\Passport\ClientRepository $client
     * @return void
     */
    public function __construct(Transaccion $transaccion, TransaccionEstatus $transaccion_estatus, Pais $pais, Moneda $moneda)
    {
        $this->mTransaccion = $transaccion;
        $this->mTransaccionEstatus = $transaccion_estatus;
        $this->mPais = $pais;
        $this->mMoneda = $moneda;
    }

    /**
     * No implementado, regresa vacío
     *
     * @param \Illuminate\Http\Request $oRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $oRequest): JsonResponse
    {
        // Envía datos paginados
        return ejsend_success([]);
    }

    /**
     * Obtiene registro
     *
     * @param  uuid  $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($uuid): JsonResponse
    {
        // Muestra el recurso solicitado
        try {
            $oValidator = Validator::make(['uuid' => $uuid], [
                'uuid' => 'required|uuid|size:36',
            ]);
            if ($oValidator->fails()) {
                return ejsend_fail(['code' => 400, 'type' => 'Parámetros', 'message' => 'Error en parámetros de entrada.'], 400, ['errors' => $oValidator->errors()]);
            }
            // Busca registro
            $oTransaccion = $this->mTransaccion->find($uuid);
            if ($oTransaccion == null) {
            Log::error('Error on ' . __METHOD__ . ' line ' . __LINE__ . ': Transacción no encontrada:' . $uuid);
                return ejsend_fail(['code' => 404, 'type' => 'General', 'message' => 'Objeto no encontrado.'], 404);
            }
            // Regresa transaccion con usuarios
            return ejsend_success(['transaccion' => $oTransaccion]);
        } catch (\Exception $e) {
            // Registra error
            Log::error('Error en ' . __METHOD__ . ' línea ' . __LINE__ . ':' . $e->getMessage());
            return ejsend_error(['code' => 500, 'type' => 'Sistema', 'message' => 'Error al obtener el recurso: ' . $e->getMessage()]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $oRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $oRequest): JsonResponse
    {
        // Valida datos
        try {
            // Valida campos
            $oValidator = Validator::make($oRequest->all(), [
                'uuid' => 'required|uuid|size:36',
            ]);
            if ($oValidator->fails()) {
                return ejsend_fail(['code' => 400, 'type' => 'Parámetros', 'message' => 'Error en parámetros de entrada.'], 400, ['errors' => $oValidator->errors()]);
            }
            // Agrega valores
            $oRequest->merge([
                'comercio_uuid' => $oRequest->input('comercio_uuid'),
                'transaccion_estatus_id' => $this->mTransaccionEstatus->where('indice', $oRequest->input('estatus'))->value('id'),
                'pais_id' => $this->mPais->where('iso_a3', $oRequest->input('pais'))->value('id'),
                'moneda_id' => $this->mMoneda->where('iso_a3', $oRequest->input('moneda'))->value('id'),
            ]);
            // Crea objeto
            $oTransaccion = $this->mTransaccion->create($oRequest->all());
            // Regresa resultados
            return ejsend_success(['transaccion' => $oTransaccion]);
        } catch (\Exception $e) {
            Log::error('Error en ' . __METHOD__ . ' línea ' . __LINE__ . ':' . $e->getMessage());
            return ejsend_error(['code' => 500, 'type' => 'Sistema', 'message' => 'Error al crear el recurso: ' . $e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $oRequest
     * @param  int  $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $oRequest, $uuid): JsonResponse
    {
        // Valida id
        $oValidator = Validator::make(['uuid' => $uuid], [
            'uuid' => 'required|uuid|size:36',
        ]);
        if ($oValidator->fails()) {
            return ejsend_fail(['code' => 400, 'type' => 'Parámetros', 'message' => 'Error en parámetros de entrada.'], 400, ['errors' => $oValidator->errors()]);
        }
        // Busca transaccion
        $oTransaccion = $this->mTransaccion->find($uuid);
        if ($oTransaccion == null) {
            Log::error('Error on ' . __METHOD__ . ' line ' . __LINE__ . ': Transacción no encontrada:' . $uuid);
            return ejsend_fail(['code' => 404, 'type' => 'General', 'message' => 'Objeto no encontrada.'], 404);
        }
        // Agrega valores
        $oRequest->merge([
            'transaccion_estatus_id' => $this->mTransaccionEstatus->where('indice', $oRequest->input('estatus'))->value('id'),
            'pais_id' => $this->mPais->where('iso_a3', $oRequest->input('pais'))->value('id'),
            'moneda_id' => $this->mMoneda->where('iso_a3', $oRequest->input('moneda'))->value('id'),
        ]);
        // @todo: Validar datos de entrada
        // Actualiza transaccion
        $oTransaccion->update($oRequest->all());
        return ejsend_success(['transaccion' => $oTransaccion]);
    }

    /**
     * Borra el modelo.
     *
     * @param  int  $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($uuid): JsonResponse
    {
        // Valida id
        $oValidator = Validator::make(['uuid' => $uuid], [
            'uuid' => 'required|uuid|size:36',
        ]);
        if ($oValidator->fails()) {
            return ejsend_fail(['code' => 400, 'type' => 'Parámetros', 'message' => 'Error en parámetros de entrada.'], 400, ['errors' => $oValidator->errors()]);
        }
        // Busca transaccion
        $oTransaccion = $this->mTransaccion->find($uuid);
        if ($oTransaccion == null) {
            Log::error('Error on ' . __METHOD__ . ' line ' . __LINE__ . ': Transacción no encontrada:' . $uuid);
            return ejsend_fail(['code' => 404, 'type' => 'General', 'message' => 'Objeto no encontrado.'], 404);
        }
        // Borra usuario
        try {
            // Primero se actualiza en campo activo a 0
            $oTransaccion->update(['activo' => 0]);
            $oTransaccion->delete();
            // @todo: Inhabilita usuarios del transaccion
            // Regresa resultado
            return ejsend_success(['transaccion' => $oTransaccion]);
        } catch (\Exception $e) {
            Log::error('Error on ' . __METHOD__ . ' line ' . __LINE__ . ':' . $e->getMessage());
            return ejsend_error(['code' => 500, 'type' => 'Sistema', 'message' => 'Error al borrar el recurso: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($uuid): JsonResponse
    {
        // Valida id
        $oValidator = Validator::make(['uuid' => $uuid], [
            'uuid' => 'required|uuid|size:36',
        ]);
        if ($oValidator->fails()) {
            return ejsend_fail(['code' => 400, 'type' => 'Parámetros', 'message' => 'Error en parámetros de entrada.'], 400, ['errors' => $oValidator->errors()]);
        }
        // Busca transaccion
        $oTransaccion = $this->mTransaccion->find($uuid);
        if ($oTransaccion == null) {
            Log::error('Error on ' . __METHOD__ . ' line ' . __LINE__ . ': Transacción no encontrada:' . $uuid);
            return ejsend_fail(['code' => 404, 'type' => 'General', 'message' => 'Objeto no encontrado.'], 404);
        }
        // Borra usuario
        try {
            // Primero se actualiza en campo activo a 0
            $oTransaccion->forceDelete();
            // @todo: Borralita usuarios del transaccion
            // Regresa resultado
            return ejsend_success([], 204);
        } catch (\Exception $e) {
            Log::error('Error on ' . __METHOD__ . ' line ' . __LINE__ . ':' . $e->getMessage());
            return ejsend_error(['code' => 500, 'type' => 'Sistema', 'message' => 'Error al borrar el recurso: ' . $e->getMessage()]);
        }

    }
}
