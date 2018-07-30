<?php

use Illuminate\Http\Request;

Route::group(['guard' => 'api', 'middleware' => ['client.credentials'], 'as' => 'api.'], function () {

    // API Admin
    require base_path('routes/api/admin/admin.php');

});
