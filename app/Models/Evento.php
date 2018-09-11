<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evento extends Model
{
    //
    use SoftDeletes;

    // Nombre de la tabla
    protected $table = 'eventos';

    // Atributos
    protected $fillable = [
        'tipo_evento', 'prueba', 'estatus'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    //relaciones
    public function endpoint()
    {
        return $this->belongsTo('App\Models\Endpoint');
    }
}
