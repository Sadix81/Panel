<?php

use Illuminate\Support\Facades\Route;
use Modules\Faq\Http\Controllers\FaqController;

Route::prefix('/v1/faq/')->middleware('auth:api')->group(function () {
    Route::get('/', [FaqController::class, 'index']);
    Route::post('/', [FaqController::class, 'store']);
    Route::get('/{faq}', [FaqController::class, 'show']);
    Route::put('/{faq}', [FaqController::class, 'update']);
    Route::delete('/{faq}', [FaqController::class, 'destroy']);
});
