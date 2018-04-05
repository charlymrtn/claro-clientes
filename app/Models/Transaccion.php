<?php

namespace App\Models;

use Log;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Transaccion extends Model
{
    use LogsActivity;

    //Nombre de la tabla
    protected $table = 'transaccion';

    //Atributos
    protected $fillable = [
        'uuid', 'comercio_uuid', 'prueba', 'operacion', 'transaccion_estatus_id', 'pais_id', 'moneda_id', 'monto',
        'forma_pago', 'datos_pago', 'datos_antifraude', 'datos_comercio', 'datos_claropagos', 'datos_procesador',
        'datos_destino'
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
    //protected $primaryKey = 'uuid';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

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
