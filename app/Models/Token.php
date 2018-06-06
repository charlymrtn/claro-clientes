<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

use App\Classes\Sistema\Mensaje;

class Token extends Model
{
    use Notifiable, SoftDeletes;
    //use Notifiable, SoftDeletes, LogsActivity; // LogsActivity no funciona con columnas con id de diferente tipo

    //Nombre de la tabla
    protected $table = 'token';

    //Atributos
    protected $fillable = [
        'id', // Id del token
        'comercio_uuid', // Id del comercio
        'token', // Token generado
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'token'
    ];

    /**
     * Se elimina autoincrementable
     * @var string
     */
    public $incrementing = 'false';
    protected $primaryKey = 'id';
    protected $keyType = 'string';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Relaciones
     */

    /**
     * Comercio de la transaccion
     */
    public function comercio()
    {
        return $this->belongsTo('App\Models\Comercio');
    }

    /*
     * Otros métodos
     */

    /**
     * Obtiene token local y de API del comercio
     *
     * @param string $comercio_uuid UUID del comercio
     * @param string $token_id Token id
     *
     * @return object Collection con tokens del comercio
     */
    public function getToken(string $comercio_uuid, string $token_id)
    {
        // Obtiene token local
        $cLocalTokens = Token::where([
            ['comercio_uuid', $comercio_uuid],
            ['id', $token_id],
        ])->get();
        // Obtiene token del API
        $cApiTokens = $this->getApiTokens($comercio_uuid);
        // Une datos
        foreach($cLocalTokens as $token) {
            $oApiToken = $cApiTokens->firstWhere('id', $token->id);
            $token->nombre = $oApiToken->name;
            $token->permisos = $oApiToken->scopes;
            $token->revocado = $oApiToken->revoked;
        }
        // Regresa collection con tokens completos
        return $cLocalTokens;
    }

    /**
     * Obtiene tokens locales y de API del comercio
     *
     * @param string $comercio_uuid UUID del comercio
     * @return object Collection con tokens del comercio
     */
    public function getTokens(string $comercio_uuid): Collection
    {
        // Obtiene tokens locales
        $cLocalTokens = Token::where('comercio_uuid', $comercio_uuid)->get();
        // Obtiene tokens del API
        $cApiTokens = $this->getApiTokens($comercio_uuid);
        // Une datos
        foreach($cLocalTokens as $token) {
            $oApiToken = $cApiTokens->firstWhere('id', $token->id);
            $token->nombre = $oApiToken->name;
            $token->permisos = $oApiToken->scopes;
            $token->revocado = $oApiToken->revoked;
        }
        // Regresa collection con tokens completos
        return $cLocalTokens;
    }

    /**
     * Obtiene tokens en API del comercio
     *
     * @param string $comercio_uuid UUID del comercio
     * @param string $token_id Token id
     *
     * @return object Collection con tokens del comercio
     */
    public function getApiToken(string $comercio_uuid, string $token_id): Collection
    {
        // API
        $aTokensApi = [];
        $oMensaje = new Mensaje();
        $oMensajeRespuesta = $oMensaje->envia('api', '/admin/comercio/' . $comercio_uuid . '/token/' . $token_id, 'GET');
        if ($oMensajeRespuesta->status == 'success') {
            $oMensajeData = json_decode($oMensajeRespuesta->response);
            if (isset($oMensajeData->data->tokens->data)) {
                $aTokensApi = $oMensajeData->data->tokens->data;
            }
        }
        // Regresa arreglo con tokens
        return collect($aTokensApi);
    }

    /**
     * Obtiene tokens en API del comercio
     *
     * @param string $comercio_uuid UUID del comercio
     * @return object Collection con tokens del comercio
     */
    public function getApiTokens(string $comercio_uuid): Collection
    {
        // API
        $aTokensApi = [];
        $oMensaje = new Mensaje();
        $oMensajeRespuesta = $oMensaje->envia('api', '/admin/comercio/' . $comercio_uuid . '/token', 'GET');
        if ($oMensajeRespuesta->status == 'success') {
            $oMensajeData = json_decode($oMensajeRespuesta->response);
            if (isset($oMensajeData->data->tokens->data)) {
                $aTokensApi = $oMensajeData->data->tokens->data;
            }
        }
        // Regresa arreglo con tokens
        return collect($aTokensApi);
    }

}
