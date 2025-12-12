<?php

use Illuminate\Support\Facades\Route;
use Modules\Shop\Http\Controllers\ShopController;



Route::prefix('/v1/shop/info/')->middleware('auth:api')->group(function(){
    Route::get('index/' , [ShopController::class , 'index']);
    Route::put('update/{shop}' , [ShopController::class , 'update']);
});
