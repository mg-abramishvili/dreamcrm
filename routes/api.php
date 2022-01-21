<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ElementController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\BoxController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\CalculationController;
use App\Http\Controllers\OfferController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/me', [AuthController::class, 'me']);

Route::get('elements', [ElementController::class, 'index']);
Route::get('elements/box/{id}', [ElementController::class, 'indexByBox']);
Route::get('element/{id}', [ElementController::class, 'element']);
Route::post('elements', [ElementController::class, 'store']);

Route::get('types', [TypeController::class, 'index']);

Route::get('boxes', [BoxController::class, 'index']);
Route::get('boxes/type/{id}', [BoxController::class, 'indexByType']);
Route::get('box/{id}', [BoxController::class, 'box']);

Route::get('categories', [CategoryController::class, 'index']);
Route::get('category/{id}', [CategoryController::class, 'category']);
Route::get('category/{id}/elements', [CategoryController::class, 'categoryElements']);

Route::get('deliveries', [DeliveryController::class, 'index']);

Route::get('calculations', [CalculationController::class, 'index']);
Route::get('calculation/{id}', [CalculationController::class, 'calculation']);
Route::post('calculations', [CalculationController::class, 'store']);

Route::get('offers', [OfferController::class, 'index']);
Route::get('offer/{id}', [OfferController::class, 'offer']);
Route::get('offer/{id}/pdf', [OfferController::class, 'offerPDF']);