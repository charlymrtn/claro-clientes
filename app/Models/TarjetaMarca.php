<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class TarjetaMarca extends Model
{
    // Traits
    use Notifiable, SoftDeletes, LogsActivity;

    // Nombre de la tabla
    protected $table = 'tarjeta_marca';

    // Atributos
    protected $fillable = [
        'id', 'nombre', 'rango', 'tamano'
    ];

    /**
     * Atributos a ignorar en bitácora de actividad. spatie/laravel-activity
     *
     */
    protected static $ignoreChangedAttributes = [];
    protected static $logOnlyDirty = true;

    // Validaciones de entrada
    public $rules = [
        'nombre' => 'required|min:3|max:30',
        'rango' => 'required|min:2|max:30',
        'tamano' => 'required|min:1|max:30',
    ];

}
