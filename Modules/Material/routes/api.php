<?php

use Illuminate\Support\Facades\Route;
use Modules\Material\Http\Controllers\MaterialController;

Route::prefix('/v1/material/')->middleware('auth:api')->group(function () {
    Route::resource('/', MaterialController::class)->parameters(['' => 'material']);
});
