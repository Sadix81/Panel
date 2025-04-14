<?php

use Illuminate\Support\Facades\Route;
use Modules\Slider\Http\Controllers\SliderController;


Route::prefix('v1/slider')->middleware('auth:api')->group(function () {
    Route::get('/', [SliderController::class, 'index']);
    Route::post('/', [SliderController::class, 'store']);
    Route::get('/{slider}', [SliderController::class, 'show']);
    Route::put('/{slider}', [SliderController::class, 'update']);
    Route::delete('/{slider}', [SliderController::class, 'destroy']);
});
