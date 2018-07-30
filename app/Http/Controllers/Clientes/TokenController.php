<?php

namespace App\Http\Controllers\Clientes;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Token;
use App\Models\Comercio;

class TokenController extends Controller
{

    protected $oComercio;
    protected $oToken;

    /**
     * Crea nueva instancia.
     *
     * @return void
     */
    public function __construct(Comercio $comercio, Token $token)
    {
        $this->oComercio = $comercio;
        $this->oToken = $token;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     */
    public function index()
    {
        // Muestra vista
        return view('clientes.token.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Obtiene parametros
        $cPermisos = $this->oToken->getPermisos();
        // Obtiene tokens del comercio
        $aApiTokens = $this->oToken->getToken(auth()->user()->comercio_uuid, $id);
        // Muestra vista con datos
        return view('clientes.token.detalle')->with(['token' => $aApiTokens->first(), 'permisos' => $cPermisos]);
    }

    /**
     * Muestra forma de creación de token
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        // Obtiene parametros
        $cPermisos = $this->oToken->getPermisos();
        // Muestra vista con datos
        return view('clientes.token.create')->with(['permisos' => $cPermisos]);
    }

    /**
     * Genera token
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function store(Request $oRequest)
    {
        // Verifica scopes
        $cScopesValidos = $this->oToken->getPermisos();
        $aTokenScopes = $oRequest->input('permisos', []);
        if (!empty(array_diff($aTokenScopes, $cScopesValidos->keys()->toArray()))) {
            Alert::error("Error en parámetros de entrada: Permisos inválidos.")->flash();
            return redirect()->back()->withInput();
        }
        // Genera token del comercio en API
        $sTokenName = $oRequest->input('name', 'API Token Claro Pagos');
        $oApiTokenId = $this->oToken->storeApiToken(auth()->user()->comercio_uuid, $sTokenName, $aTokenScopes);
        // Guarda token local (portal de clientes)
        if (!empty($oApiTokenId)) {
            // Muestra vista detalle del nuevo token
            return redirect()->route('clientes.token.show', ['id' => $oApiTokenId]);
        } else {
            Alert::error("Ocurrio un error al crear el token.")->flash();
            return redirect()->back()->withInput();
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function revoke(string $id)
    {
        $bResultado = $this->oToken->revokeToken(auth()->user()->comercio_uuid, $id);
        if (!$bResultado) {
            Alert::error("Ocurrio un error al revocar el token.")->flash();
        }
        return redirect()->back()->withInput();
    }
}
