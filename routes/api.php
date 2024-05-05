<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;

Route::get('sale/bestsellers', [SaleController::class, 'bestSellers']);
Route::apiResource('category', CategoryController::class);
Route::apiResource('product', ProductController::class);
Route::apiResource('client', ClientController::class);
Route::apiResource('address', AddressController::class);
Route::apiResource('sale', SaleController::class)->except(['update', 'destroy']);
