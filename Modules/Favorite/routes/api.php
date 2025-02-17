<?php

use Illuminate\Support\Facades\Route;
use Modules\Favorite\Http\Controllers\FavoriteController;


Route::prefix('/v1/favorite/')->middleware('auth:api')->group(function () {
    Route::get('/index', [FavoriteController::class , 'index']);
    Route::post('/store', [FavoriteController::class , 'store']);
    Route::delete('/{favorite}/remove', [FavoriteController::class , 'remove']);
});

