<?php

/**
 *  @todo: Cambiar a scope:blablabla . Verificar el fallo de no usuario en  app\Http\Middleware\CheckClientCredentials.php
 */
//Route::group(['guard' => 'api', 'namespace' => 'API\admin', 'prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['scope:superadmin']], function () {
Route::group(['guard' => 'api', 'namespace' => 'API\admin', 'prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['client.credentials:superadmin']], function () {

    // Usuarios
    Route::apiResource('/usuario', 'UsuarioController');
    Route::name('usuario.')->group(function () {
        Route::delete('/usuario/{uid}/token/{token}/revoke', 'UsuarioTokenController@revoke')->name('token.revoke');
        Route::apiResource('/usuario/{uid}/token', 'UsuarioTokenController');
    });
    // Comercios
    Route::apiResource('/comercio', 'ComercioController');
    Route::name('comercio.')->group(function () {
        Route::delete('/comercio/{uuid}/token/{token}/revoke', 'ComercioTokenController@revoke')->name('token.revoke');
        Route::apiResource('/comercio/{uuid}/token', 'ComercioTokenController');
    });

});
