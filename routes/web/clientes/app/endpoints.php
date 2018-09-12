<?php

use App\Http\Controllers\Clientes\EndpointController;

Route::group(['middleware' => ['permission:accesar backend clientes']], function () {
    
    Route::resource('endpoint', 'EndpointController')->except('destroy');

    Route::get('endpoint/delete/{id}','EndpointController@destroy')->name('endpoint.destroy');

    Route::get('list','EndpointController@list')->name('list');
    
});