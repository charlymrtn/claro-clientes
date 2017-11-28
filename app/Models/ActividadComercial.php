<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class ActividadComercial extends Model
{
    // Traits
    use Notifiable, SoftDeletes, LogsActivity;

    // Nombre de la tabla
    protected $table = 'actividad_comercial';

    // Atributos
    protected $fillable = [
         'nombre'
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
     * Atributos a ignorar en bitácora de actividad. spatie/laravel-activity
     */
    protected static $ignoreChangedAttributes = [];
    protected static $logAttributes =['nombre'];
    protected static $logOnlyDirty = true;

}
