<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class TransaccionEstatus extends Model
{
    // Traits
    use Notifiable, SoftDeletes, LogsActivity;

    // Nombre de la tabla
    protected $table = 'transaccion_estatus';

    // Atributos
    protected $fillable = [
        'nombre', 'descripcion', 'color', 'indice'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
}
