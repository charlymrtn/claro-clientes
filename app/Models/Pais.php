<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Pais extends Model
{
    // Traits
    use Notifiable, SoftDeletes, LogsActivity;

    // Nombre de la tabla
    protected $table = 'pais';

    // Atributos
    protected $fillable = [
        'nombre', 'iso_a2', 'iso_a3', 'iso_n3'
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
    protected static $logOnlyDirty = true;

    /**
     * Estados del país
     */
    public function estados()
    {
        return $this->hasMany('App\Models\Estado');
    }

    public function monedas()
    {
        return $this->hasOne('App\Models\Moneda');
    }
}
