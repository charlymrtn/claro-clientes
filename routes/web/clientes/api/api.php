<?php
// Admin APIs
Route::group(['middleware' => ['permission:listar transacciones']], function () {
//    Route::resource('/api/transaccion', 'API\TransaccionController', ['as' => 'api']);
});
