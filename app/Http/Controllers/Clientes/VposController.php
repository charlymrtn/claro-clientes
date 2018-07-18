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
            return redirect()->route('logout');
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
        // "_token" => "CaZqfmlVIWMDADIMaH3AsEgjLXPeQWR4JSUJZBgj"
        // "procesador" => "bbva"
        // "afiliacion" => "5462742"
        #dump($aRequest);
        // Envía mensaje
        $oMensajeCargo = $this->mMensaje->envia('api', '/bbva/cargo', 'POST', json_encode($aRequest));
        #dump($oMensajeCargo);

        if (!empty($oMensajeCargo->response)) {
            $oRespuesta = json_decode($oMensajeCargo->response);
        } else {
            $oRespuesta = json_decode('{"error":"El cobro no pudo ser realizado."}');
            return view('clientes.vpos.resultado')->with(['usuario' => $oUsuario, 'respuesta' => $oRespuesta]);
        }
        #dump($oRespuesta);

        // Procesa resultados
        $oTrx = Transaccion::find($oRespuesta->id);

        // Muestra vista con datos
        return view('clientes.vpos.resultado')->with(['usuario' => $oUsuario, 'respuesta' => $oRespuesta, 'transaccion' => $oTrx]);


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
