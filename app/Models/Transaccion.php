<?php

namespace App\Models;

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
     * Se elimina autoincrementable
     * @var string
     */
    public $incrementing = 'false';
    protected $primaryKey = 'uuid';

    /**
     * Relaciones
     */

    /**
     * Comercio de la transaccion
     */
    public function comercio()
    {
        return $this->belongsTo('App\Models\Comercio', 'uuid', 'comercio_uuid');
    }
    /**
     * Estatus de transacción
     */
    public function transaccion_estatus()
    {
        return $this->belongsTo('App\Models\TransaccionEstatus');
    }

    /**
     * Pais de la transacción
     */
    public function pais()
    {
        return $this->belongsTo('App\Models\Pais');
    }

    /**
     * Moneda de la transacción
     */
    public function moneda()
    {
        return $this->belongsTo('App\Models\Moneda');
    }

}
