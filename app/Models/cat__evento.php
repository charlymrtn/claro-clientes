<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class cat__evento extends Model
{
    //
    use SoftDeletes;

    // Nombre de la tabla
    protected $table = 'cat__eventos';

    // Atributos
    protected $fillable = [
        'nombre', 'tipo_evento', 'metodo'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
}
