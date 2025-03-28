<?php

use Illuminate\Support\Facades\Route;
use Modules\Rating\Http\Controllers\RatingController;



Route::prefix('/v1/rating')->middleware('auth:api')->group(function (){
    Route::get('/', [RatingController::class, 'index']);
    Route::post('/', [RatingController::class, 'store']);
    Route::get('/detailes/{product}', [RatingController::class, 'show']);
    Route::put('/update/detail', [RatingController::class, 'update']);
    Route::get('/product/percent', [RatingController::class, 'rateCalculate']);
});