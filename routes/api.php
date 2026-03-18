<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me',      [AuthController::class, 'me']);
    });
});



Route::middleware(['auth:api', 'role:client'])->group(function () {
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('orders/{id}',          [OrderController::class, 'show']);
    Route::patch('orders/{id}/cancel', [OrderController::class, 'cancel']);
    Route::get('mes-commandes', [OrderController::class, 'mesCommandes']);
});



Route::get('products',        [ProductController::class, 'index']);
Route::get('products/{slug}', [ProductController::class, 'show']);


Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::post('products',           [ProductController::class, 'store']);
    Route::put('products/{product}',  [ProductController::class, 'update']);
    Route::delete('products/{product}', [ProductController::class, 'destroy']);
});





Route::get('categories', [CategoryController::class, 'index']);

Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::post('categories',             [CategoryController::class, 'store']);
    Route::put('categories/{category}',   [CategoryController::class, 'update']);
    Route::delete('categories/{category}', [CategoryController::class, 'destroy']);
});
