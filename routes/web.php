<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});


//['middleware' => ['client']

/*
Route::group(['middleware' => ['auth']], function () {
    Route::resource('/api/usuario', 'Admin\API\UsuarioController', ['as' => 'api']);
});

*/

/*
Route::group(array('before' => 'auth'), function()
{

    Route::get('/api/usuario', 'Admin\API\UsuarioController', ['as' => 'api']);
});
*/

Route::get('/api/usuario', 'Admin\API\UsuarioController@index');
Route::get('/api/usuario/{id}', 'Admin\API\UsuarioController@show');
Route::post('/api/{id}', 'Admin\API\UsuarioController@update');

