<?php
//Tokens
Route::group(['middleware' => ['permission:listar tokens clientes']], function () {
    Route::get('/token', 'TokenController@index')->name('token.index');
    Route::get('/token/{id}', 'TokenController@show')->name('token.show');
    Route::get('/token/genera', 'TokenController@genera')->name('token.genera');
    Route::get('/token/delete/{id}', 'TokenController@deleteClient')->name('token.delete.client');
    Route::get('/token/nuevo', 'TokenController@nuevoToken')->name('token.nuevo.token');
    Route::get('/token/delete_token/{id}', 'TokenController@deleteToken')->name('token.delete.token');
});
