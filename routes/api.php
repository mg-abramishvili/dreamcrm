<?php

use App\Http\Controllers\ElementController;
use App\Http\Controllers\BoxController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\CalculationController;
use App\Http\Controllers\OfferController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('elements', [ElementController::class, 'index']);
Route::get('element/{id}', [ElementController::class, 'element']);
Route::post('elements', [ElementController::class, 'store']);

Route::get('boxes', [BoxController::class, 'index']);
Route::get('box/{id}', [BoxController::class, 'box']);

Route::get('categories', [CategoryController::class, 'index']);
Route::get('category/{id}', [CategoryController::class, 'category']);
Route::get('category/{id}/elements', [CategoryController::class, 'categoryElements']);

Route::get('deliveries', [DeliveryController::class, 'index']);

Route::get('calculations', [CalculationController::class, 'index']);
Route::get('calculation/{id}', [CalculationController::class, 'calculation']);
Route::post('calculations', [CalculationController::class, 'store']);

Route::get('offers', [OfferController::class, 'index']);