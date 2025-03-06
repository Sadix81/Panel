<?php

use Illuminate\Support\Facades\Route;
use Modules\ForgotPassword\Http\Controllers\ForgotPasswordController;



Route::prefix('/v1')->group(function () {
    Route::post('/forgot/password', [ForgotPasswordController::class, 'forgotpassword']);
    Route::post('password/verify/code', [ForgotPasswordController::class, 'verify_password_otp_code']);
    Route::patch('/change/forgot/password', [ForgotPasswordController::class, 'ChangePassword']);
});
