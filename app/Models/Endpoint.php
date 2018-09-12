<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Evento;

class Endpoint extends Model
{
    //
    use SoftDeletes;

    // Nombre de la tabla
    protected $table = 'endpoints';

    // Atributos
    protected $fillable = [
        'url', 'es_activo', 'es_valido', 'max_intentos', 'comercio_uuid'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    protected $hidden = ['deleted_at'];

    //relaciones
    public function eventos()
    {
        return $this->hasMany('App\Models\Evento');
    }

    public function comercio()
    {
        return $this->belongsTo('App\Models\Comercio');
    }
}
