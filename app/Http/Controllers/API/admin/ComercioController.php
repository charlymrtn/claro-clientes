<?php

namespace App\Http\Controllers\API\Admin;

use Log;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Comercio;

class ComercioController extends Controller
{

    /**
     * Comercio instance.
     *
     * @var \App\Models\Comercio
     */
    protected $mComercio;

    /**
     * Create a client controller instance.
     *
     * @param App\Models\User $usuario
     * @param \Laravel\Passport\ClientRepository $client
     * @return void
     */
    public function __construct(Comercio $comercio)
    {
        $this->mComercio = $comercio;
    }

    /**
     * Regresa lista de comercios.
     *
     * @param \Illuminate\Http\Request $oRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $oRequest): JsonResponse
    {
        // Regresa todos los usuarios paginados
        try {
            // Verifica las variables para despliegue de datos
            $oValidator = Validator::make($oRequest->all(), [
                'uuid' => 'uuid|size:36',
                // Datos de la paginación y filtros
                'per_page' => 'numeric|between:5,100',
                'order' => 'max:30|in:id,comercio_nombre,comercio_correo,activo,created_at,updated_at,deleted_at',
                'search' => 'max:100',
                'deleted' => 'in:no,yes,only',
                'sort' => 'in:asc,desc',
            ]);
            if ($oValidator->fails()) {
                return ejsend_fail(['code' => 400, 'type' => 'Parámetros', 'message' => 'Error en parámetros de entrada.'], 400, ['errors' => $oValidator->errors()]);
            }
            // Filtros
            $sFiltro = $oRequest->input('search', false);
            $sDeleted = $oRequest->input('deleted', 'no');
            $sComercio = $oRequest->input('uuid', false);
            $cComercios = $this->mComercio
                ->withTrashed()
                ->withCount('usuarios')
                ->where(
                    function ($q) use ($sComercio) {
                        if (!empty($sComercio)) {
                            return $q
                                ->orWhere('uuid', '=', $sComercio);
                        }
                    }
                )
                ->where(
                    function ($q) use ($sFiltro) {
                        if ($sFiltro !== false) {
                            return $q
                                ->orWhere('comercio_nombre', 'like', "%$sFiltro%")
                                ->orWhere('comercio_correo', 'like', "%$sFiltro%");
                        }
                    }
                )
                ->where(
                    function ($q) use ($sDeleted) {
                        if ($sDeleted == 'no') {
                            return $q->whereNull('deleted_at');
                        } elseif ($sDeleted == 'yes') {
                            return $q;
                        } elseif ($sDeleted == 'only') {
                            return $q->whereNotNull('deleted_at');
                        }
                    }
                )
                ->orderBy($oRequest->input('order', 'uuid'), $oRequest->input('sort', 'asc'))
                ->paginate((int) $oRequest->input('per_page', 25));

            // Envía datos paginados
            return ejsend_success(['comercios' => $cComercios]);
        } catch (\Exception $e) {
            Log::error('Error on ' . __METHOD__ . ' line ' . __LINE__ . ':' . $e->getMessage());
            return ejsend_error(['code' => 500, 'type' => 'Sistema', 'message' => 'Error al obtener el recurso: ' . $e->getMessage()]);
        }
    }

    /**
     * Obtiene registro del usuario del comercio con su uuid.
     *
     * @param  uuid  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): JsonResponse
    {
        // Muestra el recurso solicitado
        try {
            $oValidator = Validator::make(['id' => $id], [
                'id' => 'required|uuid|size:36',
            ]);
            if ($oValidator->fails()) {
                return ejsend_fail(['code' => 400, 'type' => 'Parámetros', 'message' => 'Error en parámetros de entrada.'], 400, ['errors' => $oValidator->errors()]);
            }
            // Busca comercio
            $oComercio = $this->mComercio->with('usuarios')->find($id);
            if ($oComercio == null) {
                Log::error('Error on ' . __METHOD__ . ' line ' . __LINE__ . ': Comercio no encontrado');
                return ejsend_fail(['code' => 404, 'type' => 'General', 'message' => 'Objeto no encontrado.'], 404);
            }
            // Regresa comercio con usuarios
            return ejsend_success(['comercio' => $oComercio]);
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
                'comercio_nombre' => 'max:255',
                'comercio_correo' => 'max:255',
                //'comercio_contrasena' => 'max:255',
                'contacto_nombre' => 'max:255',
                'contacto_telefono_empresa' => 'max:255',
                'contacto_correo' => 'max:255',
                'contacto_telefono_comercial' => 'max:255',
                'facturacion_razon_social' => 'max:255',
                'facturacion_responsable_legal' => 'max:255',
                'facturacion_rfc' => 'max:255',
                'facturacion_fecha_alta_legal' => 'max:255',
                'facturacion_direccion' => 'max:255',
                'facturacion_codigo_postal' => 'max:255',
                'facturacion_colonia' => 'max:255',
                'facturacion_municipio' => 'max:255',
                'facturacion_ciudad' => 'max:255',
                'actividad_comercial_id' => 'numeric|between:1,999',
                'pais_id' => 'numeric|between:1,999',
                'estado_id' => 'numeric|between:1,99999',
                'estatus' => 'in:nuevo,activo,inhabilitado,',
                'activo' => 'boolean',
            ]);
            if ($oValidator->fails()) {
                return ejsend_fail(['code' => 400, 'type' => 'Parámetros', 'message' => 'Error en parámetros de entrada.'], 400, ['errors' => $oValidator->errors()]);
            }
            // Agrega valores
            $oRequest->merge([
                // 'password' => Hash::make(str_random(24))
            ]);
            // Crea usuario
            $oComercio = $this->mComercio->create($oRequest->all());
            // Regresa resultados
            return ejsend_success(['comercio' => $oComercio]);
        } catch (\Exception $e) {
            Log::error('Error en ' . __METHOD__ . ' línea ' . __LINE__ . ':' . $e->getMessage());
            return ejsend_error(['code' => 500, 'type' => 'Sistema', 'message' => 'Error al crear el recurso: ' . $e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $oRequest
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $oRequest, $id): JsonResponse
    {
        // Valida id
        $oValidator = Validator::make(['id' => $id], [
            'id' => 'required|uuid|size:36',
        ]);
        if ($oValidator->fails()) {
            return ejsend_fail(['code' => 400, 'type' => 'Parámetros', 'message' => 'Error en parámetros de entrada.'], 400, ['errors' => $oValidator->errors()]);
        }
        // Busca comercio
        $oComercio = $this->mComercio->with('usuarios')->find($id);
        if ($oComercio == null) {
            Log::error('Error on ' . __METHOD__ . ' line ' . __LINE__ . ': Comercio no encontrado');
            return ejsend_fail(['code' => 404, 'type' => 'General', 'message' => 'Objeto no encontrado.'], 404);
        }
        // @todo: Validar datos de entrada
        // Actualiza usuario
        $oComercio->update($oRequest->all());
        return ejsend_success(['comercio' => $oComercio]);
    }

    /**
     * Borra el modelo.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id): JsonResponse
    {
        // Valida id
        $oValidator = Validator::make(['id' => $id], [
            'id' => 'required|uuid|size:36',
        ]);
        if ($oValidator->fails()) {
            return ejsend_fail(['code' => 400, 'type' => 'Parámetros', 'message' => 'Error en parámetros de entrada.'], 400, ['errors' => $oValidator->errors()]);
        }
        // Busca comercio
        $oComercio = $this->mComercio->with('usuarios')->find($id);
        if ($oComercio == null) {
            Log::error('Error on ' . __METHOD__ . ' line ' . __LINE__ . ': Comercio no encontrado');
            return ejsend_fail(['code' => 404, 'type' => 'General', 'message' => 'Objeto no encontrado.'], 404);
        }
        // Borra usuario
        try {
            // Primero se actualiza en campo activo a 0
            $oComercio->update(['activo' => 0]);
            $oComercio->delete();
            // @todo: Inhabilita usuarios del comercio
            // Regresa resultado
            return ejsend_success(['comercio' => $oComercio]);
        } catch (\Exception $e) {
            Log::error('Error on ' . __METHOD__ . ' line ' . __LINE__ . ':' . $e->getMessage());
            return ejsend_error(['code' => 500, 'type' => 'Sistema', 'message' => 'Error al borrar el recurso: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        // Valida id
        $oValidator = Validator::make(['id' => $id], [
            'id' => 'required|uuid|size:36',
        ]);
        if ($oValidator->fails()) {
            return ejsend_fail(['code' => 400, 'type' => 'Parámetros', 'message' => 'Error en parámetros de entrada.'], 400, ['errors' => $oValidator->errors()]);
        }
        // Busca comercio
        $oComercio = $this->mComercio->with('usuarios')->find($id);
        if ($oComercio == null) {
            Log::error('Error on ' . __METHOD__ . ' line ' . __LINE__ . ': Comercio no encontrado');
            return ejsend_fail(['code' => 404, 'type' => 'General', 'message' => 'Objeto no encontrado.'], 404);
        }
        // Borra usuario
        try {
            // Primero se actualiza en campo activo a 0
            $oComercio->forceDelete();
            // @todo: Borralita usuarios del comercio
            // Regresa resultado
            return ejsend_success([], 204);
        } catch (\Exception $e) {
            Log::error('Error on ' . __METHOD__ . ' line ' . __LINE__ . ':' . $e->getMessage());
            return ejsend_error(['code' => 500, 'type' => 'Sistema', 'message' => 'Error al borrar el recurso: ' . $e->getMessage()]);
        }

    }
}
