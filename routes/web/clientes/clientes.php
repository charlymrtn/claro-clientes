<?php

// Clientes principal
Route::get('/', 'ClientesController@index')->name('inicio');

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
// Tokens
require base_path('routes/web/clientes/token/token.php');
// Vpos
require base_path('routes/web/clientes/app/vpos.php');
//Endpoint
require base_path('routes/web/clientes/app/endpoints.php');

/**
 * Clientes APIs
 */

// Web Clientes API
require base_path('routes/web/clientes/api/api.php');
