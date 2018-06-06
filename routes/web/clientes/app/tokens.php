<?php
// Tokens
Route::group(['middleware' => ['permission:listar tokens clientes']], function () {
    Route::get('/token/{id}/edita', 'TokenController@edit')->name('token.edit');
    Route::post('/token/{id}/actualiza', 'TokenController@update')->name('token.update');
});