<?php
//Tokens
Route::group(['middleware' => ['permission:listar tokens clientes']], function () {
    Route::get('/token', 'TokenController@index')->name('token.index');
    Route::post('/token', 'TokenController@store')->name('token.store');
    Route::get('/token/delete/{id}', 'TokenController@deleteClient')->name('token.delete.client');
    Route::get('/token/nuevo', 'TokenController@create')->name('token.create');
    Route::get('/token/delete_token/{id}', 'TokenController@deleteToken')->name('token.delete.token');
    Route::get('/token/{id}', 'TokenController@show')->name('token.show');
});
