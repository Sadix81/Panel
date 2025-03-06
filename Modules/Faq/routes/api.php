<?php

use Illuminate\Support\Facades\Route;
use Modules\Faq\Http\Controllers\FaqController;

Route::prefix('/v1/faq/')->middleware('auth:api')->group(function () {
    Route::get('/index', [FaqController::class , 'index']);
    Route::post('/store', [FaqController::class , 'store']);
    Route::get('/show/{faq}', [FaqController::class , 'show']);
    Route::put('/update/{faq}', [FaqController::class , 'update']);
    Route::delete('/destroy/{faq}', [FaqController::class , 'destroy']);
});
