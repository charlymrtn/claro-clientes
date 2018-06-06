<?php

namespace App\Http\Controllers\Clientes;

use GuzzleHttp\Client;
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
        // Obtiene tokens del comercio
        $aApiTokens = $this->oToken->getApiTokens(auth()->user()->comercio_uuid);
        // Muestra vista con datos
        return view('clientes.token.index')->with(['tokens' => $aApiTokens]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Obtiene tokens del comercio
        $aApiTokens = $this->oToken->getToken(auth()->user()->comercio_uuid, $id);
        // Muestra vista con datos
        return view('clientes.token.detalle')->with(['token' => $aApiTokens->first()]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function nuevoToken()
    {
        return view('clientes.token.nuevo_token');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteToken($id)
    {   dd('eliminado');
        /*
        $uri = 'http://clientes.claropay.local.com/oauth/personal-access-tokens/';
        $client = new Client(['debug' => true,]);
        $response = $client->post($uri, [
            'json' => $id,
        ]);
        return redirect()->back();
        */
    }
}
