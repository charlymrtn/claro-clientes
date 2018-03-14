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

// Admin
Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => ['auth', 'permission:accesar backend']], function () {
    require base_path('routes/web/admin/admin.php');
});
