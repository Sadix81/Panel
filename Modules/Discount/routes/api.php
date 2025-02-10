<?php

use Illuminate\Support\Facades\Route;
use Modules\Discount\Http\Controllers\DiscountController;


Route::prefix('/v1/discount')->middleware('auth:api')->group(function () {
    Route::get('/index', [DiscountController::class, 'index']);
    Route::post('/store', [DiscountController::class, 'store']);
    Route::get('/{discount}/show', [DiscountController::class, 'show']);
    Route::put('/{discount}/update', [DiscountController::class, 'update']);
    Route::put('/all/products/price/update', [DiscountController::class, 'allprductsdiscount']);
    Route::delete('/{discount}/delete', [DiscountController::class, 'destroy']);
});