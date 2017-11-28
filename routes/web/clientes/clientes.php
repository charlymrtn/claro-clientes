<?php

// Admin principal
Route::get('/', 'ClientesController@index')->name('clientes');

/**
 * Modelos base
 */
    // Perfiles
    require base_path('routes/web/clientes/base/perfiles.php');

/**
 * Modelos exclusivos de la app
 */
    // Transacciones
    require base_path('routes/web/clientes/app/transacciones.php');

/**
 * Clientes APIs
 */
    // Actividad Comercial
    require base_path('routes/web/clientes/api/api.php');
