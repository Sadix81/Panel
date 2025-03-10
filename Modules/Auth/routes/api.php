<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AuthController;

Route::prefix('auth/user/')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('/verify/code', [AuthController::class, 'verify_otp_code']);
    Route::post('/resend/code', [AuthController::class, 'ResendCode']);
})->middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
});
