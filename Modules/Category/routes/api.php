<?php

use Illuminate\Support\Facades\Route;
use Modules\Category\Http\Controllers\CategoryController;

Route::prefix('/v1/category')->middleware('auth:api')->group(function () {
    Route::resource('/', CategoryController::class)->parameters(['' => 'category']);
    Route::patch('/delete/{category}/image', [CategoryController::class, 'remove_category_image']);
    Route::delete('/{category}', [CategoryController::class, 'destroy']);
});
