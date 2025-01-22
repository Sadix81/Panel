<?php

use Illuminate\Support\Facades\Route;
use Modules\Size\Http\Controllers\SizeController;


Route::prefix('/v1/size/')->middleware('auth:api')->group(function () {
    Route::resource('/', SizeController::class)->parameters(['' => 'size']);
});