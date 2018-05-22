<?php
//Tokens
Route::group(['middleware' => ['permission:ver perfil clientes']], function () {
    Route::get('/tokens', 'TokenController@index')->name('tokens.index');
    Route::get('/tokens/genera', 'TokenController@genera')->name('tokens.genera');
    Route::get('/tokens/delete/{id}', 'TokenController@deleteClient')->name('tokens.delete.client');
    Route::get('/tokens/nuevo', 'TokenController@nuevoToken')->name('tokens.nuevo.token');
    Route::get('/tokens/delete_token/{id}', 'TokenController@deleteToken')->name('tokens.delete.token');

});
