<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::apiResource('category', CategoryController::class);
Route::apiResource('product', ProductController::class);
Route::apiResource('client', ClientController::class);
Route::apiResource('address', AddressController::class);
