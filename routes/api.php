<?php

use App\Http\Controllers\v1\CategoryController;
use App\Http\Controllers\v1\LoginController;
use App\Http\Controllers\v1\LogoutController;
use App\Http\Controllers\v1\ProductController;
use App\Http\Controllers\v1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->missing(function () {
    return response()->json([
        'status'  => false,
        'message' => 'Data not found!',
    ])->setStatusCode(400);
})->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/login', [LoginController::class, 'verify']);
    });

    Route::middleware(['auth:sanctum', 'log'])->group(function () {
        Route::prefix('auth')->group(function () {
            Route::post('/logout', LogoutController::class);
        });

        Route::apiResource('products', ProductController::class);
        Route::apiResource('categories', CategoryController::class);
        Route::apiResource('users', UserController::class);
    });
});
