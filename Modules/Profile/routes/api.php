<?php

use Illuminate\Support\Facades\Route;
use Modules\Profile\Http\Controllers\ProfileController;


Route::prefix('/v1/profile/')->middleware('auth:api')->group(function () {
    Route::post('/update/{user}/profile' , [ProfileController::class , 'update']);
    Route::patch('/change/{user}/password' , [ProfileController::class , 'change_password']);
});
