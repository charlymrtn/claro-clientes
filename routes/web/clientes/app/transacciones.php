<?php
//Transacciones
Route::group(['middleware' => ['permission:listar transacciones']], function () {
    Route::get('/transaccion', 'TransaccionController@index')->name('transaccion.index');
    Route::get('/transaccion/{id}', 'TransaccionController@show')->name('transaccion.show');
});

Route::group(['middleware' => ['permission:editar transacciones']], function () {
    Route::get('/transaccion/{id}/edita', 'TransaccionController@edit')->name('transaccion.edit');
    Route::post('/transaccion/{id}/actualiza', 'TransaccionController@update')->name('transaccion.update');
});