<?php

use Illuminate\Support\Facades\Route;
use Modules\Cart\Http\Controllers\CartController;

// ->middleware('auth:api')
Route::prefix('/v1/cart/')->group(function () {
    Route::post('/create', [CartController::class, 'create_cart']);
    Route::get('/index', [CartController::class, 'index']);
    // Route::get('/{cart}/show', [CartController::class, 'show']);
    Route::post('/add/product', [CartController::class, 'addToCart']);
    // Route::put('/{cart}/update', [CartController::class, 'update']);
    // Route::patch('/thumbnail/{product}/delete', [CartController::class, 'thumbnail']);
    // Route::patch('/image/{product}/delete', [CartController::class, 'product_iamge']);
    // Route::delete('/{product}/delete', [CartController::class, 'destroy']);
});