<?php
// Admin APIs
Route::group(['middleware' => ['permission:listar transacciones clientes']], function () {
    Route::resource('/api/transaccion', 'API\TransaccionController', ['as' => 'api']);
});
