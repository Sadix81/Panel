<?php

use Illuminate\Support\Facades\Route;
use Modules\Rating\Http\Controllers\RatingController;



Route::prefix('/v1/rating')->middleware('auth:api')->group(function (){
    Route::get('/index', [RatingController::class, 'index']);
    Route::post('/store', [RatingController::class, 'store']);
    Route::get('/show/detailes/{rate}', [RatingController::class, 'show']);
    Route::put('/update/detail', [RatingController::class, 'update']);
});