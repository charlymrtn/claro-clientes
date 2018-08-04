<?php

namespace App\Http\Controllers\Admin\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Activitylog\Models\Activity;
use Validator;
use Log;

class UsuarioController extends Controller
{
    protected $oUsuario;

    /**
     * Crea nueva instancia.
     *
     * @return void
     */
    public function __construct(User $usuario)
    {
        $this->oUsuario = $usuario;
    }

    /**
     * Muestra la página inicial de los recursos.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $oRequest)
    {
        try {
            // Verifica las variables para despliegue de datos
            $oValidator = Validator::make($oRequest->all(), [
                'per_page' => 'numeric|between:5,100',
                'order' => 'max:30|in:id,name,email,created_at,updated_at',
                'search' => 'max:100',
                'sort' => 'in:asc,desc',
            ]);
            if ($oValidator->fails()) {
                return response()->json(["status" => "fail", "data" => ["errors" => $oValidator->errors()]]);
            }
            // Filtro
            $filtro = $oRequest->input('search', false);
            if ($filtro === false) {
                $aUsuarios = $this->oUsuario
                    ->orderBy($oRequest->input('order', 'id'), $oRequest->input('sort', 'asc'))
                    ->paginate((int) $oRequest->input('per_page', 25));
            } else {
                $aUsuarios = $this->oUsuario
                    ->orWhere('name', 'like', "%$filtro%")
                    ->orWhere('email', 'like', "%$filtro%")
                    ->orderBy($oRequest->input('order', 'id'), $oRequest->input('sort', 'asc'))
                    ->paginate((int) $oRequest->input('per_page', 25));
            }
            // Envía datos paginados
            return response()->json(["status" => "success", "data" => ["usuarios" => $aUsuarios]]);
        } catch (\Exception $e) {
            Log::error('Error on ' . __METHOD__ . ' line ' . $e->getLine() . ':' . $e->getMessage());
            return response()->json(["status" => "fail", "data" => ["message" => "No se pueden mostrar los recurso. Error: " . $e->getMessage()]]);
        }

    }

    /**
     * Muestra el detalle del recurso solicitado.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Muestra el objeto solicitado
        try {
            $oValidator = Validator::make(['id' => $id], [
                'id' => 'required|numeric',
            ]);
            if ($oValidator->fails()) {
                return response()->json(["status" => "fail", "data" => ["errors" => $oValidator->errors()]]);
            }
            // Busca usuario
            $oUsuario = $this->oUsuario->find($id);
            if ($oUsuario == null) {
                return response()->json(["status" => "fail", "data" => ["message" => "Usuario no encontrado. "]]);
            } else {
                return response()->json(["status" => "success", "data" => ["usuarios" => $oUsuario]]);
                // Muestra plantilla
            }
        } catch (\Exception $e) {
            // Registra error
            Log::error('Error en ' . __METHOD__ . ' línea ' . $e->getLine() . ':' . $e->getMessage());
            // Muestra plantilla de error
            return response()->json(["status" => "fail", "data" => ["message" => "No se pueden mostrar los recurso. Error: " . $e->getMessage()]]);
        }
    }

    /**
     * Muestra la forma de creación del usuario.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Muestra la forma de edición del usuario.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Actualiza el modelo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Busca usuario
        $oUsuario = $this->oUsuario->find($id);
        if ($oUsuario == null) {
            return response()->json(["status" => "fail", "data" => ["message" => "Usuario no encontrado. "]]);
        }
        // Ajusta valores
        $aAjustes = array();
        if ($request->has('activo')) {
            if (in_array($request->input('activo'), [true, 1, '1', 'true', 'on', 'yes'])) {
                $aAjustes['activo'] = 1;
            } else {
                $aAjustes['activo'] = 0;
            }
        } else {
            $aAjustes['activo'] = 0;
        }
        if ($request->has('password-1') && $request->has('password-2') && $request->input('password-1') == $request->input('password-2')) {
            $aAjustes['password'] = $request->input('password-1');
        }
        $request->merge($aAjustes);
        // Actualiza usuario
        try {
            $oValidator = Validator::make(
                array_merge(['id' => $id], $request->all()),
                [
                    'id' => 'required|numeric',
                    'name' => 'max:255',
                    'email' => 'email|max:255',
                    'activo' => 'boolean',
                    'change-password' => 'boolean',
                    'password' => 'bail|required_if:change-password,true|min:6|max:255',
                    'apellido_paterno' => 'max:255',
                    'apellido_materno' => 'max:255',
                ]
            );
            if ($oValidator->fails()) {
                return response()->json(["status" => "fail", "data" => ["message" => "Valores Incompletos" , 'id' => $id] ]);
            }
            // Modifica valores
            $aCambios = array();
            if ($request->has('password')) {
                $aCambios['password'] = Hash::make($request->input('password'));
            }
            $request->merge($aCambios);
            // Actualiza usuario
            $oUsuario->update($request->all());
            // Envía datos paginados
            return response()->json(["status" => "success", "data" => ["id" => $id]]);
        } catch (\Exception $e) {
            Log::error('Error on ' . __METHOD__ . ' line ' . $e->getLine() . ':' . $e->getMessage());
            return response()->json(["status" => "fail", "data" => ["message" => "No se pueden mostrar los recurso. Error: " . $e->getMessage()]]);
        }
    }
    /**
     * Actualiza el modelo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Valida datos
        try {
            // Ajusta valores
            $aAjustes = array();
            if ($request->has('activo')) {
                if (in_array($request->input('activo'), [true, 1, '1', 'true', 'on', 'yes'])) {
                    $aAjustes['activo'] = 1;
                } else {
                    $aAjustes['activo'] = 0;
                }
            }
            if ($request->has('password-1') && $request->has('password-2') && $request->input('password-1') == $request->input('password-2')) {
                $aAjustes['password'] = $request->input('password-1');
            }
            $request->merge($aAjustes);
            // Valida campos
            $oValidator = Validator::make($request->all(), [
                'name' => 'max:255',
                'email' => 'email|max:255',
                'activo' => 'boolean',
                'password' => 'bail|required|min:6|max:255',
                'apellido_paterno' => 'max:255',
                'apellido_materno' => 'max:255',
            ]);
            if ($oValidator->fails()) {
                Alert::error($oValidator->errors())->flash();
                return redirect()->back()->withInput();
            }
            // Modifica valores
            $aCambios = array();
            if ($request->has('password')) {
                $aCambios['password'] = Hash::make($request->input('password'));
            }
            $request->merge($aCambios);
            // Crea usuario
            $oUsuario = User::create($request->all());
              return response()->json(["status" => "success", "data" => ["id" => $oUsuario->id]]);
        } catch (\Exception $e) {
            Log::error('Error on ' . __METHOD__ . ' line ' . $e->getLine() . ':' . $e->getMessage());
            return response()->json(["status" => "fail", "data" => ["message" => "No se pueden mostrar los recurso. Error: " . $e->getMessage()]]);
        }
    }

    /**
     * Borra el modelo.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Busca usuario
        $oUsuario = $this->oUsuario->find($id);
        if ($oUsuario == null) {
            Alert::error('Usuario no encontrado')->flash();
            return redirect()->route('clientes.usuario.index');
        }
        // Borra usuario
        try {
            $oUsuario->delete();
            return response()->json(["status" => "success", "data" => ["id" => $oUsuario->id]]);
        } catch (\Exception $e) {
            Alert::error("No se puede borrar el usuario. Error: " . $e->getMessage())->flash();
            Log::error('Error on ' . __METHOD__ . ' line ' . $e->getLine() . ':' . $e->getMessage());
            return response()->json(["status" => "fail", "data" => ["message" => "No se pueden mostrar los recurso. Error: " . $e->getMessage()]]);
        }
    }
}
