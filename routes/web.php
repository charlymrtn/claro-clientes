<?php

/*
|--------------------------------------------------------------------------
| Rutas
|--------------------------------------------------------------------------
*/

// Inicio
Route::get('/', 'HomeController@index');

// Auth
Auth::routes();
Route::get('/logout', 'Auth\LoginController@logout')->name('logout'); // Get logout hack

// Clientes
Route::group(['namespace' => 'Clientes', 'prefix' => 'clientes', 'middleware' => ['auth', 'permission:accesar backend clientes']], function () {
    require base_path('routes/web/clientes/clientes.php');
});

// Que es esto?
//Route::get('/api/usuario', 'Admin\API\UsuarioController@index');
//Route::get('/api/usuario/{id}', 'Admin\API\UsuarioController@show');
//Route::post('/api/usuario/{id}', 'Admin\API\UsuarioController@update');

