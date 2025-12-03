<?php

use Illuminate\Support\Facades\Route;
use Modules\Discount\Http\Controllers\DiscountController;

Route::prefix('/v1/discount')->middleware('auth:api')->group(function () {
    // Route::resource('/' , DiscountController::class)->parameters([''=>'discount']);
    Route::get('/', [DiscountController::class, 'index']);
    Route::post('/', [DiscountController::class, 'store']);
    Route::get('/{discount}', [DiscountController::class, 'show']);
    Route::put('/{discount}', [DiscountController::class, 'update']);
    Route::delete('/{discount}', [DiscountController::class, 'destroy']);
});
