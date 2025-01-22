<?php

use Illuminate\Support\Facades\Route;
use Modules\Color\Http\Controllers\ColorController;



Route::prefix('/v1/color/')->middleware('auth:api')->group(function () {
    Route::resource('/', ColorController::class)->parameters(['' => 'color']);
});
