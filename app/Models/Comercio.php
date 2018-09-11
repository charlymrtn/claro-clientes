<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Webpatser\Uuid\Uuid;

class Comercio extends Model
{

    // Traits
    use Notifiable, SoftDeletes;
    //use Notifiable, SoftDeletes, LogsActivity; // LogsActivity no funciona con columnas con id de diferente tipo

    // Nombre de la tabla
    protected $table = 'comercio';

    // Atributos
    protected $primaryKey = 'uuid';
    public $incrementing = false;

    /**
     * Atributos modificables
     *
     * @var array
     */
    protected $fillable = [
        'uuid', 'comercio_nombre', 'comercio_correo', 'comercio_contrasena', 'contacto_nombre',
        'contacto_telefono_empresa', 'contacto_correo', 'contacto_telefono_comercial',
        'facturacion_razon_social', 'facturacion_responsable_legal', 'facturacion_rfc',
        'facturacion_fecha_alta_legal', 'facturacion_direccion', 'facturacion_codigo_postal',
        'estatus','activo',
        // Catalogos
        'actividad_comercial_id', 'pais_id', 'estado_id',
        // @todo: Cambiar a referencias de catálogos cuando estos esten disponibles.
        'facturacion_colonia', 'facturacion_municipio', 'facturacion_ciudad',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'comercio_contrasena',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at'
    ];

    /**
     *  Setup model event hooks
     */
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Uuid::generate(4);
            }
        });
    }
    /**
     * Atributos a ignorar en bitácora de actividad. spatie/laravel-activity
     */
    protected static $ignoreChangedAttributes = [];
    protected static $logOnlyDirty = true;

    /**
     * Relaciones ===========================================
     */

    /**
     * País del comercio (pais_id).
     */
    public function pais()
    {
        return $this->belongsTo('App\Models\Pais');
    }

    /**
     * Estado del comercio (estado_id).
     */
    public function estado()
    {
        return $this->belongsTo('App\Models\Estado');
    }

    /**
     * Actividad Comercial del comercio (actividad_comercial_id).
     */
    public function actividad_comercial()
    {
        return $this->belongsTo('App\Models\ActividadComercial');
    }

    /**
     * Usuarios del comercio
     */
    public function usuarios()
    {
        return $this->hasMany('App\Models\User', 'comercio_uuid', 'uuid');
    }

    /**
     * Tokens del comercio
     */
    public function tokens()
    {
        return $this->hasMany('App\Models\Token', 'comercio_uuid', 'uuid');
    }

    /**
     * Endpoint del comercio
     */
    public function endpoints()
    {
        return $this->hasMany('App\Models\Endpoint', 'comercio_uuid', 'uuid');
    }


}
