<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Estado extends Model
{
    // Traits
    use Notifiable, SoftDeletes, LogsActivity;

    // Nombre de la tabla
    protected $table = 'estado';

    // Atributos
    protected $fillable = [
        'pais_id', 'nombre', 'iso_a3'
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
     * País del estado.
     */
    public function pais()
    {
        return $this->belongsTo('App\Models\Pais');
    }
}
