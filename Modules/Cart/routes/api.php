<?php

use Illuminate\Support\Facades\Route;
use Modules\Cart\Http\Controllers\CartController;

// ->middleware('auth:api')
Route::prefix('/v1/cart/')->group(function () {
    Route::post('/create', [CartController::class, 'create_cart']);
    Route::get('/index', [CartController::class, 'index']);
    Route::post('/add/product', [CartController::class, 'addToCart']);
    Route::put('/update/quantity', [CartController::class, 'updateCartQuantity']);
});
