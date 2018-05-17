<?php

/**
 *  @todo: Cambiar a scope:blablabla . Verificar el fallo de no usuario en  app\Http\Middleware\CheckClientCredentials.php
 *          'middleware' => ['scope:superadmin']
 *          'middleware' => ['client.credentials:superadmin']
 *          'middleware' => ['client.credentials', 'scope:superadmin']
 */
Route::group(['namespace' => 'API\Admin', 'prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['client.credentials:superadmin']], function () {

    // Usuarios
    Route::apiResource('/usuario', 'UsuarioController');
    // Comercios
    Route::apiResource('/comercio', 'ComercioController');
    // Transacciones
    Route::apiResource('/transaccion', 'TransaccionController');
    //Ping
    Route::get('/ping', 'PingController@index');

});
