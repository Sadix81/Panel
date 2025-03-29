<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\ProductController;

Route::prefix('/v1/product/')->middleware('auth:api')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{product}', [ProductController::class, 'show']);
    Route::post('/', [ProductController::class, 'store']);
    Route::put('/{product}', [ProductController::class, 'update']);
    Route::patch('/thumbnail/{product}/delete', [ProductController::class, 'thumbnail']);
    Route::patch('/image/{product}/delete', [ProductController::class, 'product_iamge']);
    Route::delete('/{product}', [ProductController::class, 'destroy']);
});
