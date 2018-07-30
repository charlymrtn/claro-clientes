<?php

namespace App\Models;

use Log;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Transaccion extends Model
{
    // @todo: Programar soporte de uuid como llave de LogsActivity;
    // use LogsActivity;

    //Nombre de la tabla
    protected $table = 'transaccion';

    //Atributos
    protected $fillable = [
        'uuid', // Id de la transacción en Claro Pagos
        'comercio_uuid', // Id del comercio
        'prueba',  // Booleano. Es prueba la transacción
        'operacion',
        'monto',
        'forma_pago',
        // Catálogos (pseudo por velocidad)
        'transaccion_estatus_id',
        'pais_id',
        'moneda_id',
        // Objetos JSON
        'datos_pago',
        'datos_comercio',
        'datos_destino',
        // Eventos JSON
        'datos_antifraude',
        'datos_procesador',
        'datos_claropagos',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'transaccion_estatus_id', 'pais_id', 'moneda_id'
    ];

    /**
     * Se elimina autoincrementable
     * @var string
     */
    public $incrementing = 'false';
    protected $primaryKey = 'uuid';
    protected $keyType = 'uuid';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    /**
     * Atributos mutables.
     *
     * @var array
     */
    protected $casts = [
        'datos_pago' => 'array',
        'datos_comercio' => 'array',
        'datos_claropagos' => 'array',
        'datos_destino' => 'array',
        'datos_antifraude' => 'array',
        'datos_procesador' => 'array',
    ];

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

    /**
     * Estatus de transacción
     */
    public function estatus()
    {
        return $this->belongsTo('App\Models\TransaccionEstatus', 'transaccion_estatus_id')->withDefault([
            'name' => 'Desconocido', 'descripcion' => 'Estatus desconocido', 'color' => '#dd4b39', 'indice' => 'desconocido'
        ]);
    }

    /**
     * Pais de la transacción
     */
    public function pais()
    {
        return $this->belongsTo('App\Models\Pais', 'pais_id', 'id');
    }

    /**
     * Moneda de la transacción
     */
    public function moneda()
    {
        return $this->belongsTo('App\Models\Moneda');
    }

}
