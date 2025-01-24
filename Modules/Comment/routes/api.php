<?php

use Illuminate\Support\Facades\Route;
use Modules\Comment\Http\Controllers\CommentController;

Route::prefix('/v1/comment/')->middleware('auth:api')->group(function () {
    Route::get('/index', [CommentController::class , 'index']);
    Route::get('/show/{comment}', [CommentController::class , 'show']);
    Route::post('/store/{product}', [CommentController::class , 'store']);
    Route::post('/replay/{comment}', [CommentController::class , 'replay']);
    Route::put('/update/{comment}', [CommentController::class , 'update']);
    Route::delete('/delete/{comment}', [CommentController::class , 'destroy']);

});
