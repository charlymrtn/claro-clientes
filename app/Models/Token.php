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
     * Obtiene token local y de Core API del comercio
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
        // Obtiene permisos
        $cPermisos = $this->getPermisos();
        $oDefaultPermisos = new \stdClass();
        $oDefaultPermisos->label = null;
        // Une datos
        foreach($cLocalTokens as $token) {
            $oApiToken = $cApiTokens->firstWhere('id', $token->id);
            $token->nombre = $oApiToken->name;
            $aTokenPermisos = [];
            foreach($oApiToken->scopes as $sScope) {
                $aTokenPermisos[] = $cPermisos->get($sScope, $oDefaultPermisos)->label ?? ucfirst($sScope);
            }
            $token->permisos = $aTokenPermisos;
            $token->revocado = $oApiToken->revoked;
        }
        // Regresa collection con tokens completos
        return $cLocalTokens;
    }

    /**
     * Obtiene tokens locales y de Core API del comercio
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
        // Obtiene permisos
        $cPermisos = $this->getPermisos();
        $oDefaultPermisos = new \stdClass();
        $oDefaultPermisos->label = null;
        // Une datos
        foreach($cLocalTokens as $token) {
            $oApiToken = $cApiTokens->firstWhere('id', $token->id);
            $token->nombre = $oApiToken->name;
            $aTokenPermisos = [];
            foreach($oApiToken->scopes as $sScope) {
                $aTokenPermisos[] = $cPermisos->get($sScope, $oDefaultPermisos)->label ?? ucfirst($sScope);
            }
            $token->permisos = $aTokenPermisos;
            $token->revocado = $oApiToken->revoked;
        }
        // Regresa collection con tokens completos
        return $cLocalTokens;
    }

    /**
     * Obtiene tokens en Core API del comercio
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
     * Obtiene tokens en Core API del comercio
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

    /**
     * Crea token en Core API
     *
     * @param string $comercio_uuid UUID del comercio
     * @param string $sName
     * @param array $aScopes
     *
     * @return array Array con token creado
     */
    public function storeApiToken(string $comercio_uuid, string $sName, array $aScopes): ?string
    {
        // Prepara parámetros
        $aRequestParams = json_encode([
            'name' => $sName,
            'scopes' => $aScopes,
        ]);
        // API
        $oTokenApi = null;
        $oMensaje = new Mensaje();
        $oMensajeRespuesta = $oMensaje->envia('api', '/admin/comercio/' . $comercio_uuid . '/token/', 'POST', $aRequestParams);
        if ($oMensajeRespuesta->status == 'success') {
            $oMensajeData = json_decode($oMensajeRespuesta->response);
            if (isset($oMensajeData->data->token)) {
                $oTokenApi = $oMensajeData->data->token;
            }
        }
        // Guarda token localmente
        $result = $this->create([
            'id' => $oTokenApi->token->id, // Id del token
            'comercio_uuid' => $comercio_uuid, // Id del comercio
            'token' => $oTokenApi->accessToken, // Token generado
        ]);
        // Regresa id si fue creado exitosamente
        if (!empty($result)) {
            return $oTokenApi->token->id;
        } else {
            return null;
        }
    }

    /**
     * Revoca roken
     *
     * @param string $comercio_uuid UUID del comercio
     * @param string $sName
     * @param array $aScopes
     *
     * @return array Array con token creado
     */
    public function revokeToken(string $comercio_uuid, string $sTokenId): bool
    {
        // API
        $oMensaje = new Mensaje();
        $oMensajeRespuesta = $oMensaje->envia('api', '/admin/comercio/' . $comercio_uuid . '/token/' . $sTokenId . '/revoke', 'DELETE');
        if ($oMensajeRespuesta->status == 'success') {
            $oMensajeData = json_decode($oMensajeRespuesta->response);
            if (isset($oMensajeData->data->token)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Obtiene lista de permisos disponibles con labels
     *
     * @return object Collection con permisos disponibles
     */
    public function getPermisos(): Collection
    {
        return collect([
            'cliente-tarjetas' => (object) [
                'id' => 'cliente-tarjetas',
                'label' => 'Tarjetas',
                'descripcion' => 'Tarjetas (Operaciones sobre tarjetas)',
            ],
            'cliente-transacciones' => (object) [
                'id' => 'cliente-transacciones',
                'label' => 'Transaciones',
                'descripcion' => 'Transacciones y cargos',
            ],
            'cliente-suscripciones' => (object) [
                'id' => 'cliente-suscripciones',
                'label' => 'Suscripciones',
                'descripcion' => 'Suscripciones y planes',
            ],
        ]);
    }
}
