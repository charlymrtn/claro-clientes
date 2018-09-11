<?php

Route::group(['middleware' => ['permission:accesar backend clientes']], function () {
    
    Route::resource('endpoint', 'EndpointController');
    Route::resource('evento', 'EventoController');

    Route::get('list','EndpointController@list')->name('list');
    
});