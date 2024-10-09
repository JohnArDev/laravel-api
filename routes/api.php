<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\productController;

// Route de user
// Route::get('/user', [productController::class, 'index']);
// Route::post('/register', [productController::class, 'index']);
// Route::post('/login', [productController::class, 'index']);

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
use App\Http\Controllers\AuthController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
Route::get('user', [AuthController::class, 'getAuthenticatedUser'])->middleware('auth:api');

// Route::middleware(['auth:api'])->group(function () {
//     Route::get('products', [ProductController::class, 'index']);
//     // Otras rutas de productos
// });

Route::get('/product', [productController::class, 'index']);

Route::get('/product/{id}', [productController::class, 'show']);

Route::post('/product', [productController::class, 'store']);

Route::put('/product/{id}', [productController::class, 'updatePartial']);

Route::delete('/product/{id}', [productController::class, 'delete']);

// Route::middleware(['auth:api'])->group(function () {
//     Route::get('products', [ProductController::class, 'index']);
//     // Otras rutas de productos
// });

