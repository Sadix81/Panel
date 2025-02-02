<?php

use Illuminate\Support\Facades\Route;
use Modules\Category\Http\Controllers\CategoryController;

Route::prefix('/v1/category')->middleware('auth:api')->group(function () {
    Route::get('/index', [CategoryController::class, 'index']);
    Route::post('/store', [CategoryController::class, 'store']);
    Route::get('/{category}/show', [CategoryController::class, 'show']);
    Route::put('/{category}/update', [CategoryController::class, 'update']);
    Route::patch('/delete/{category}/image', [CategoryController::class, 'remove_category_image']);
    Route::delete('/{category}/delete', [CategoryController::class, 'destroy']);
});
