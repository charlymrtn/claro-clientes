<?php

namespace App\Http\Controllers\Clientes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

class TokenController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     */
    public function index()
    {
        return view('clientes.tokens.index');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteClient($id)
    {
        $uri = 'http://clientes.claropay.local.com/oauth/clients/';
        $client = new Client(['debug' => true,]);
                $response = $client->post($uri, [
                    'json' => $id,
                ]);
        return redirect()->back();

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function nuevoToken()
    {
        return view('clientes.tokens.nuevo_token');
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
