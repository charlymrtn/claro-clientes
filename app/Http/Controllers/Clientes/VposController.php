<?php

namespace App\Http\Controllers\Clientes;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Token;
use App\Models\Comercio;
use App\Models\Transaccion;
use App\Classes\Sistema\Mensaje;

class VposController extends Controller
{

    protected $mUsuario;
    protected $mComercio;
    protected $mMensaje;

    /**
     * Crea nueva instancia.
     *
     * @return void
     */
    public function __construct(User $usuario, Comercio $comercio, Mensaje $mensaje)
    {
        $this->mUsuario = $usuario;
        $this->mComercio = $comercio;
        $this->mMensaje = $mensaje;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     */
    public function index()
    {
        // Obtiene usuario
        $oUsuario = $this->mUsuario->find(Auth::id());
        // Obtiene comercio
        $oComercio = $this->mComercio->find($oUsuario->comercio_uuid);
        // Muestra vista con datos
        return view('clientes.vpos.index')->with(['usuario' => $oUsuario, 'comercio' => $oComercio]);
    }

    public function cargo(Request $request)
    {
        // Obtiene el usuario logueado
        $oUsuario = Auth::user();
        if ($oUsuario == null) {
            Alert::error('Usuario no encontrado')->flash();
            return ejsend_error(['code' => 500, 'type' => 'Sistema', 'message' => 'Sesión expirada.'], 500);
        }

        // Prepara mensaje
        #dump($request->all());
        $aRequest = [
            'prueba' => 0,
            'comercio_uuid' => $request->input('comercio'),
            'descripcion' => $request->input('pedido_concepto'),
            'monto' => $request->input('monto'),
            'tarjeta' => [
                'pan' => preg_replace('/\D/', '', $request->input('number')),
                'nombre' => $request->input('name') ?? 'Claro Pagos',
                'cvv2' => $request->input('cvc') ?? '',
                'expiracion_mes' => substr($request->input('expiry'), 0, 2),
                'expiracion_anio' => substr($request->input('expiry'), 5),
            ],
            'pedido' => [
                'id' => $request->input('pedido_numero'),
                'total' => $request->input('monto'),
            ],
            'plan' => [
                'plan' => $request->input('promocion'),
                'puntos' => 0,
                'parcialidades' => 0,
                'diferido' => 0,
            ],
            'procesador' => $request->input('procesador'),
        ];
        if (in_array($request->input('promocion', 'normal'), ['msi', 'mci', 'diferido_msi', 'diferido_mci'])) {
            $aRequest['plan']['parcialidades'] = $request->input('promocion_meses');
        }
        if (in_array($request->input('promocion', 'normal'), ['diferido', 'diferido_msi', 'diferido_mci'])) {
            $aRequest['plan']['diferido'] = $request->input('promocion_diferimiento');
        }
        if (in_array($request->input('puntos_pago_activo', 0), ['1', 'on', true, 1])) {
            $aRequest['plan']['puntos'] = $request->input('puntos_rango');
        }
        #dump($aRequest);
        // Envía mensaje
        $oMensajeCargo = $this->mMensaje->envia('api', '/bbva/cargo', 'POST', json_encode($aRequest));
        //return ejsend_success($oMensajeCargo);

        if (!empty($oMensajeCargo) && !empty($oMensajeCargo->response)) {
            $oRespuesta = json_decode(trim($oMensajeCargo->response));
            //return print_r($oRespuesta, true);
        } else {
            return ejsend_error(['code' => 500, 'type' => 'Sistema', 'message' => 'El cobro no pudo ser realizado.'], 500);
        }
        // Busca transacción
        if (!isset($oMensajeCargo->status)) {
            return ejsend_error(['code' => 500, 'type' => 'Sistema', 'message' => 'Error en respuesta'], 500);
        } elseif($oMensajeCargo->status == 'error' || $oMensajeCargo->status == 'fail') {
            return ejsend_error(['code' => $oRespuesta->http_code, 'type' => 'Sistema', 'message' => $oRespuesta->error->message], $oRespuesta->http_code);
        }
        $oTrx = Transaccion::find($oRespuesta->id);
        $oRespuesta->fecha = $oTrx->created_at;
        $oRespuesta->estatus_color = $oTrx->estatus->color;
        $oRespuesta->transaccion = $oTrx;

        return ejsend_success($oRespuesta);
        // Muestra vista con datos
        #return view('clientes.vpos.resultado')->with(['usuario' => $oUsuario, 'respuesta' => $oRespuesta, 'transaccion' => $oTrx]);

    }

    public function devolucion($uuid)
    {
        // Obtiene el usuario logueado
        $oUsuario = Auth::user();
        if ($oUsuario == null) {
            Alert::error('Usuario no encontrado')->flash();
            return redirect()->route('logout');
        }
        // Envía mensaje
        $oMensajeCargo = $this->mMensaje->envia('api', '/bbva/devolucion/' . $uuid, 'GET');
        #dump($oMensajeCargo);
        if (!empty($oMensajeCargo->response)) {
            $oRespuesta = json_decode($oMensajeCargo->response);
        } else {
            $oRespuesta = json_decode('{"error":"La devolución no pudo ser realizada."}');
            return view('clientes.vpos.resultado')->with(['usuario' => $oUsuario, 'respuesta' => $oRespuesta]);
        }
        #dump($oRespuesta);
        // Procesa resultados
        $oTrx = Transaccion::find($uuid);

        return view('clientes.vpos.resultado')->with(['usuario' => $oUsuario, 'respuesta' => $oRespuesta, 'transaccion' => $oTrx]);
    }

    public function cancelacion($uuid)
    {
        // Obtiene el usuario logueado
        $oUsuario = Auth::user();
        if ($oUsuario == null) {
            Alert::error('Usuario no encontrado')->flash();
            return redirect()->route('logout');
        }
        // Envía mensaje
        $oMensajeCargo = $this->mMensaje->envia('api', '/bbva/cancelacion/' . $uuid, 'GET');
        #dd($oMensajeCargo);
        if (!empty($oMensajeCargo->response)) {
            $oRespuesta = json_decode($oMensajeCargo->response);
        } else {
            $oRespuesta = json_decode('{"error":"La cancelación no pudo ser realizada."}');
            return view('clientes.vpos.resultado')->with(['usuario' => $oUsuario, 'respuesta' => $oRespuesta]);
        }
        #dump($oRespuesta);
        // Procesa resultados
        $oTrx = Transaccion::find($uuid);

        return view('clientes.vpos.resultado')->with(['usuario' => $oUsuario, 'respuesta' => $oRespuesta, 'transaccion' => $oTrx]);
    }


}
