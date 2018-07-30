<?php
//Tokens
Route::group(['middleware' => ['permission:listar tokens clientes']], function () {
    Route::resource('/token', 'TokenController');
    Route::get('/token/revoke/{id}', 'TokenController@revoke')->name('token.revoke');
});
