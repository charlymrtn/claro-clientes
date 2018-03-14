<?php

/**
 *  @todo: Cambiar a scope:blablabla . Verificar el fallo de no usuario en  app\Http\Middleware\CheckClientCredentials.php
 */
//Route::group(['guard' => 'api', 'namespace' => 'API\admin', 'prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['scope:superadmin']], function () {
Route::group(['guard' => 'api', 'namespace' => 'API\Admin', 'prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['client.credentials:superadmin']], function () {

    // Usuarios
    Route::apiResource('/usuario', 'UsuarioController');
    // Comercios
    Route::apiResource('/comercio', 'ComercioController');
    // Transacciones
    Route::apiResource('/transaccion', 'TransaccionController');

});
