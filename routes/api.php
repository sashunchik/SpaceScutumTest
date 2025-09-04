<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\OrderController;


Route::apiResource('products', ProductController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/products/{product}/comments', [CommentController::class, 'index']);
    Route::post('/products/{product}/comments', [CommentController::class, 'store']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/orders', [OrderController::class, 'index']); // історія
    Route::post('/orders', [OrderController::class, 'store']); // створення замовлення
});

