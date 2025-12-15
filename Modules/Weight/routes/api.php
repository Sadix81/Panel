<?php

use Illuminate\Support\Facades\Route;
use Modules\Weight\Http\Controllers\WeightController;

Route::prefix('/v1/weight/')->middleware('auth:api')->group(function () {
    Route::resource('/', WeightController::class)->parameters(['' => 'weight']);
});
