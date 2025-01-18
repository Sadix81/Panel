<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\ProductController;


Route::prefix('/v1/product/')->middleware('auth:api')->group(function () {
    Route::resource('/', ProductController::class)->parameters(['' => 'product']);
