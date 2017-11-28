<?php
//Perfiles
Route::group(['middleware' => ['permission:ver perfil clientes']], function () {
    Route::get('/perfil', 'PerfilController@index')->name('perfil.index');
});

Route::group(['middleware' => ['permission:editar perfil clientes']], function () {
    Route::get('/perfil', 'PerfilController@index')->name('perfil.index');
    Route::get('/perfil/edita', 'PerfilController@edit')->name('perfil.edit');
    Route::post('/perfil/actualiza', 'PerfilController@update')->name('perfil.update');
    Route::get('/perfil/contrasena', 'PerfilController@password')->name('perfil.password');
    Route::get('/perfil/avatar', 'PerfilController@avatar')->name('perfil.avatar');
    Route::post('/perfil/edita-avatar', 'PerfilController@editAvatar')->name('perfil.edita_avatar');
});
