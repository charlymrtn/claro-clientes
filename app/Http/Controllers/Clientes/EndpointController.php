<?php

namespace App\Http\Controllers\Clientes;

use App\Models\Endpoint;
use Illuminate\Http\Request;
use Validator;

use App\Http\Controllers\Controller;

use Prologue\Alerts\Facades\Alert;

use App\Models\cat__evento as CATEvento;

class EndpointController extends Controller
{
    protected $mEndpoint;

    protected $oEventos;

    /**
     * Crea nueva instancia
     * @return void
     */

    public function __construct(Endpoint $endpoint, CATEvento $eventos)
    {
        $this->mEndpoint = $endpoint;

        $this->oEventos = $eventos;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        // Carga vista
        return view('clientes/endpoint/index')->with([
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
        $cEventos = $this->oEventos->getEventos();
        // Muestra vista con datos
        //return $cEventos;
        return view('clientes.endpoint.create')->with(['eventos' => $cEventos]);
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
        if (!empty($request->input('url')) && count($request->input('eventos'))>0) {
            
            //return $request->all();
            
        } else {
            Alert::error("Ocurrio un error al crear el endpoint.")->flash();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Endpoint  $endpoint
     * @return \Illuminate\Http\Response
     */
    public function show(Endpoint $endpoint)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Endpoint  $endpoint
     * @return \Illuminate\Http\Response
     */
    public function edit(Endpoint $endpoint)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Endpoint  $endpoint
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Endpoint $endpoint)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Endpoint  $endpoint
     * @return \Illuminate\Http\Response
     */
    public function destroy(Endpoint $endpoint)
    {
        //
    }

    /**
     * Show the list of resources.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $oRequest)
    {
        // Regresa todos los registros borrados paginados
        try {
            // Verifica las variables para despliegue de datos
            $oValidator = Validator::make($oRequest->all(), [
                'comercio_uuid' => 'uuid|size:36',
                // Datos de la paginación y filtros
                'per_page' => 'numeric|between:5,100',
                'order' => 'max:30|in:url,es_activo,es_valido,max_intentos,comercio_uuid,created_at',
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
            $endpoints = $this->mEndpoint
                ->where('comercio_uuid', $sComercio)
                ->where(
                    function ($q) use ($sFiltro) {
                        if ($sFiltro !== false) {
                            return $q
                                ->orWhere('url', 'like', "%$sFiltro%");
                        }
                    }
                )
                ->orderBy($oRequest->input('order', 'created_at'), $oRequest->input('sort', 'asc'))
                ->paginate((int) $oRequest->input('per_page', 25));
            // Envía datos paginados
            return response()->json(["status" => "success", "data" => ["endpoint" => $endpoints]]);
        } catch (\Exception $e) {
            Log::error('Error on ' . __METHOD__ . ' line ' . $e->getLine() . ':' . $e->getMessage());
            return response()->json(["status" => "fail", "data" => ["message" => "No se pueden mostrar los recurso. Error: " . $e->getMessage()]]);
        }
    }
}
