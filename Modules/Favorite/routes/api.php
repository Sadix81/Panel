<?php

use Illuminate\Support\Facades\Route;
use Modules\Favorite\Http\Controllers\FavoriteController;

Route::prefix('/v1/favorite/')->middleware('auth:api')->group(function () {
    Route::get('/', [FavoriteController::class, 'index']);
    Route::post('/', [FavoriteController::class, 'store']);
    Route::delete('/{favorite}', [FavoriteController::class, 'remove']);
});
