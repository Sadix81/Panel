<?php

use Illuminate\Support\Facades\Route;
use Modules\Comment\Http\Controllers\CommentController;

Route::prefix('/v1/comment/')->middleware('auth:api')->group(function () {
    Route::get('/', [CommentController::class, 'index']);
    Route::get('/{comment}', [CommentController::class, 'show']);
    Route::post('/', [CommentController::class, 'store']);
    Route::post('/replay/{comment}', [CommentController::class, 'replay']);
    Route::put('/{comment}', [CommentController::class, 'update']);
    Route::delete('/{comment}', [CommentController::class, 'destroy']);

});
