<?php
// Tokens
Route::group(['middleware' => ['permission:listar vpos clientes']], function () {
    Route::get('/vpos', 'VposController@index')->name('vpos.index');
    Route::post('/vpos', 'VposController@cargo')->name('vpos.cargo');
    Route::get('/vpos/devolucion/{uuid}', 'VposController@devolucion')->name('vpos.devolucion');
    Route::get('/vpos/cancelacion/{uuid}', 'VposController@cancelacion')->name('vpos.cancelacion');
});