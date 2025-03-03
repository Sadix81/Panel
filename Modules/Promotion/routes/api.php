<?php

use Illuminate\Support\Facades\Route;
use Modules\Promotion\Http\Controllers\PromotionController;

Route::prefix('/v1/promotion')->middleware('auth:api')->group(function () {
    Route::put('/discount/all/products/price', [PromotionController::class, 'allprductsdiscount']);
});
