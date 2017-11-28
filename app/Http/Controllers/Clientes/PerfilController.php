<?php

namespace App\Http\Controllers\Clientes;

use Auth;
use Session;
use Validator;
use View;
use Hash;
use Log;
use Image;
use Gravatar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;
use Prologue\Alerts\Facades\Alert;

class PerfilController extends Controller
{
    protected $oUsuario;

    protected $sAvatarPath = 'avatars/users';

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
     * Muestra el detalle del recurso solicitado (usuario).
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Obtiene el usuario Logueado
        $usuario = Auth::user();
        // Muestra el objeto solicitado
        try {
            // Busca usuario (borrados y no borrados)
            $oUsuario = $this->oUsuario->find($usuario->id);
            if ($oUsuario == null) {
                return view('clientes/errores/no_encontrado')->with(['model' => 'User', 'id' => $usuario->id]);
            } else {
                // Obtiene actividad e histórico
                $permissions = $oUsuario->getDirectPermissions();
                $actividad = Activity::where([['causer_id', '=', $usuario->id], ['causer_type', '=', 'App\Models\User']])->get();
                $historico = Activity::where([['subject_id', '=', $usuario->id]])->get();
                // Muestra plantilla
                return view('clientes/perfil/index')->with(['permissions' => $permissions, 'usuario' => $oUsuario, 'actividad' => $actividad, 'historico' => $historico, 'alerts' => Alert::all()]);
            }
        } catch (\Exception $e) {
            // Registra error
            Log::error('Error en ' . __METHOD__ . ' línea ' . __LINE__ . ':' . $e->getMessage());
            // Muestra plantilla de error
            return view('clientes/errores/excepcion')->with(['exception' => $e]);
        }
    }

    /**
     * Muestra la forma de edición del usuario.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        //Obtiene el usuario Logueado
        $oUsuario = Auth::user();
        if ($oUsuario == null) {
            Alert::error('El usuario no se puede editar')->flash();
            return redirect()->route('logout');
        }
        // Muestra plantilla
        return view('clientes/perfil/edita')->with(['usuario' => $oUsuario, 'alerts' => Alert::all()]);
    }

    /**
    * Actualiza el modelo.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request)
    {
        // Obtiene el usuario logueado
        $oUsuario = Auth::user();
        if ($oUsuario == null) {
            Alert::error('Usuario no encontrado')->flash();
            return redirect()->route('logout');
        }
        // Ajusta valores
        $aAjustes = array();
        if ($request->has('last-password')) {
            if (!Hash::check($request->input('last-password'), $oUsuario->password)) {
                Alert::error('La contraseña anterior es incorrecta')->flash();
                return redirect()->route('perfil.password');
            }
        }
        $request->merge($aAjustes);
        // Actualiza usuario
        try {
            $oValidator = Validator::make(array_merge(['id' => $oUsuario->id], $request->all()), [
                'id' => 'required|numeric',
                'name' => 'max:255',
                'email' => 'email|max:255',
                'change-password' => 'boolean',
                'password' => 'required_if:change-password,true|min:6|max:255|confirmed',
                'password_confirmation' => 'required_if:change-password,true|min:6|max:255',
                'apellido_paterno' => 'max:255',
                'apellido_materno' => 'max:255',
            ]);
            if ($oValidator->fails()) {
                Alert::error($oValidator->errors())->flash();
                if ($request->has('last-password')) {
                    return redirect()->route('perfil.password');
                } else {
                    return redirect()->route('perfil.edit');
                }
            }
            // Modifica valores
            $aCambios = array();
            if ($request->has('password')) {
                $aCambios['password'] = Hash::make($request->input('password'));
            }
            $request->merge($aCambios);
            // Actualiza usuario
            $oUsuario->update($request->all());
            Alert::success('Datos actualizados correctamente')->flash();
            return redirect()->route('perfil.index');
        } catch (\Exception $e) {
            Alert::error("No se puede actualizar el registro. Error: " . $e->getMessage())->flash();
            Log::error('Error on ' . __METHOD__ . ' line ' . __LINE__ . ':' . $e->getMessage());
            return redirect()->route('perfil.index');
        }
    }

     /**
    * Muestra la forma de edición de contraseña.
    *
    * @return \Illuminate\Http\Response
    */
    public function password()
    {
        // Obtiene el usuario logueado
        $oUsuario = Auth::user();
        if ($oUsuario == null) {
            Alert::error('No se pudo editar la contraseña')->flash();
            return redirect()->route('logout');
        }
        // Muestra plantilla
        return view('clientes/perfil/contrasena')->with(['usuario' => $oUsuario, 'alerts' => Alert::all()]);
    }

     /**
    * Muestra la forma de edición de avatar.
    *
    * @return \Illuminate\Http\Response
    */
    public function avatar()
    {
        // Obtiene el usuario logueado
        $oUsuario = Auth::user();
        if ($oUsuario == null) {
            Alert::error('No se pudo editar el avatar')->flash();
            return redirect()->route('logout');
        }
        // Muestra plantilla
        return view('clientes/perfil/avatar')->with(['usuario' => $oUsuario, 'alerts' => Alert::all()]);
    }

     /**
    * Actualiza el modelo.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function editAvatar(Request $request)
    {
        try {
            // Obtiene el usuario logueado
            $oUsuario = Auth::user();
            $sAvatarAnterior = $oUsuario->avatar;
            // Recibe avatar
            if ($request->hasFile('avatar')) {
                // Datos de la imagen
                $rawimage = $request->file('avatar');
                $filename = uniqid() . '_' . $oUsuario->id . '.jpg'; // Agrega nombre único para evitar que otros vean las imágenes con solo poner el id de usuario.
                $filepath = $this->sAvatarPath;
                $oUsuario->avatar = '/' . $filepath . '/' . $filename;
                // Revisa directorio de avatars
                \File::makeDirectory($this->sAvatarPath, $mode = 0777, true, true);
                // Revisar si la imagen debe ser recortada y guarda
                if ($request->avatar_cropped) {
                    $aCropData = json_decode($request->get('avatar_cropped'), true);
                    Image::make($rawimage)->crop(
                        intval($aCropData['height']),
                        intval($aCropData['width']),
                        intval($aCropData['x']),
                        intval($aCropData['y'])
                    )->resize(200, 200)->save(public_path($oUsuario->avatar));
                } else {
                    Image::make($rawimage)->resize(200, 200)->save(public_path($oUsuario->avatar));
                }
            } else {
                $oUsuario->avatar = null;
                Alert::warning('La disponibilidad de la imagen está sujeta a la conexión de internet y el funcionamiento del servicio de Gravatar')->flash();
            }
            // Actualiza usuario
            $oUsuario->update();
            // Si todo salió bién, borra avatar anterior para evitar que se acumule basura en el directorio.
            if (!empty($sAvatarAnterior)) {
                \File::delete(public_path($sAvatarAnterior));
            }
        } catch (\Exception $e) {
            Alert::error("No se pudo cambiar el avatar. Error: " . $e->getMessage())->flash();
            Log::error('Error on ' . __METHOD__ . ' line ' . __LINE__ . ':' . $e->getMessage());
            return redirect()->route('perfil.avatar');
        }
        // Regresa al index de usuario
        Alert::success('El avatar fue editado correctamente')->flash();
        return redirect()->route('perfil.index');
    }
}
