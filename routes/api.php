<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\productController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/product', [productController::class, 'index']);

Route::get('/product/{id}', [productController::class, 'show']);

Route::post('/product', [productController::class, 'store']);

Route::put('/product/{id}', [productController::class, 'updatePartial']);

Route::delete('/product/{id}', [productController::class, 'delete']);
