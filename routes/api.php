<?php

use App\Http\Controllers\AuthController;

use App\Http\Controllers\DateController;
use App\Http\Controllers\DollarController;

use App\Http\Controllers\CatalogItemController;
use App\Http\Controllers\CatalogTypeController;
use App\Http\Controllers\CatalogBoxController;
use App\Http\Controllers\CatalogCategoryController;
use App\Http\Controllers\CatalogSborkaController;

use App\Http\Controllers\CalculationController;
use App\Http\Controllers\CalculationDeliveryController;

use App\Http\Controllers\ProjectController;

use App\Http\Controllers\ProductionController;

use App\Http\Controllers\OfferController;

use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskCommentController;
use App\Http\Controllers\TaskBoardController;
use App\Http\Controllers\TaskBoardColumnController;

use App\Http\Controllers\ClientController;

use App\Http\Controllers\UserController;

use App\Http\Controllers\FileController;

use App\Http\Controllers\StockCategoryController;
use App\Http\Controllers\StockItemController;
use App\Http\Controllers\StockBalanceController;

use App\Http\Controllers\StockNeedController;

use App\Http\Controllers\ReserveController;

use App\Http\Controllers\NotificationController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



// SERVER DATE AND TIME
Route::get('/date', [DateController::class, 'date']);



// AUTH
Route::post('/login', [AuthController::class, 'login']);
Route::post('/me', [AuthController::class, 'me']);



// CATALOG
Route::get('catalog/items', [CatalogItemController::class, 'index']);
Route::get('catalog/items/box/{id}', [CatalogItemController::class, 'indexByBox']);
Route::get('catalog/item/{id}', [CatalogItemController::class, 'item']);
Route::put('catalog/item/{id}/update', [CatalogItemController::class, 'update']);
Route::delete('catalog/item/{id}/delete', [CatalogItemController::class, 'delete']);
Route::post('catalog/items', [CatalogItemController::class, 'store']);
Route::get('catalog/items-prices-update', [CatalogItemController::class, 'updatePrices']);

Route::get('catalog/sborka', [CatalogSborkaController::class, 'index']);

Route::get('catalog/boxes', [CatalogBoxController::class, 'index']);
Route::post('catalog/boxes', [CatalogBoxController::class, 'store']);
Route::get('catalog/boxes/type/{type_id}', [CatalogBoxController::class, 'indexByType']);
Route::get('catalog/box/{id}', [CatalogBoxController::class, 'box']);
Route::put('catalog/box/{id}/update', [CatalogBoxController::class, 'update']);
Route::delete('catalog/box/{id}/delete', [CatalogBoxController::class, 'delete']);
Route::get('catalog/boxes-prices-update', [CatalogBoxController::class, 'updatePrices']);

Route::get('catalog/categories', [CatalogCategoryController::class, 'index']);
Route::get('catalog/{box_id}/categories', [CatalogCategoryController::class, 'indexByBox']);
Route::post('catalog/categories', [CatalogCategoryController::class, 'store']);
Route::get('catalog/category/{id}', [CatalogCategoryController::class, 'category']);
Route::put('catalog/category/{id}/update', [CatalogCategoryController::class, 'update']);
Route::delete('catalog/category/{id}/delete', [CatalogCategoryController::class, 'delete']);

Route::get('catalog/types', [CatalogTypeController::class, 'index']);
Route::post('catalog/types', [CatalogTypeController::class, 'store']);
Route::get('catalog/type/{id}', [CatalogTypeController::class, 'type']);
Route::put('catalog/type/{id}/update', [CatalogTypeController::class, 'update']);
Route::delete('catalog/type/{id}/delete', [CatalogTypeController::class, 'delete']);

Route::get('calculation/deliveries', [CalculationDeliveryController::class, 'index']);



Route::get('calculations', [CalculationController::class, 'index']);
Route::get('calculation/{id}', [CalculationController::class, 'calculation']);
Route::post('calculations', [CalculationController::class, 'store']);



Route::get('offers', [OfferController::class, 'index']);
Route::post('offers', [OfferController::class, 'store']);
Route::get('offer/{id}', [OfferController::class, 'offer']);
Route::get('offer/{id}/pdf', [OfferController::class, 'pdf']);



Route::get('projects', [ProjectController::class, 'index']);
Route::post('projects', [ProjectController::class, 'store']);
Route::get('project/{id}', [ProjectController::class, 'project']);



Route::get('productions', [ProductionController::class, 'index']);
Route::get('production/{id}', [ProductionController::class, 'production']);
Route::post('productions', [ProductionController::class, 'store']);
Route::get('productions/redmine-import/{offset}/{limit}', [ProductionController::class, 'importRedmineIssues']);



Route::get('usd', [DollarController::class, 'index']);
Route::get('usd/update', [DollarController::class, 'update']);



Route::get('users', [UserController::class, 'index'])->middleware('auth:sanctum');
Route::get('user/{uid}', [UserController::class, 'user'])->middleware('auth:sanctum');
Route::put('user/{uid}/update', [UserController::class, 'update'])->middleware('auth:sanctum');



Route::get('clients/{inn?}', [ClientController::class, 'index'])->middleware('auth:sanctum');
Route::get('client/{id}', [ClientController::class, 'client'])->middleware('auth:sanctum');
Route::post('clients', [ClientController::class, 'store'])->middleware('auth:sanctum');
Route::put('client/{id}/update', [ClientController::class, 'update'])->middleware('auth:sanctum');



Route::get('stock/categories', [StockCategoryController::class, 'index'])->middleware('auth:sanctum');
Route::post('stock/categories', [StockCategoryController::class, 'store'])->middleware('auth:sanctum');
Route::get('stock/category/{id}', [StockCategoryController::class, 'category'])->middleware('auth:sanctum');
Route::put('stock/category/{id}/update', [StockCategoryController::class, 'update'])->middleware('auth:sanctum');
Route::delete('stock/category/{id}/delete', [StockCategoryController::class, 'delete'])->middleware('auth:sanctum');
Route::get('stock/items', [StockItemController::class, 'index'])->middleware('auth:sanctum');
Route::post('stock/items', [StockItemController::class, 'store'])->middleware('auth:sanctum');
Route::get('stock/item/{id}', [StockItemController::class, 'item'])->middleware('auth:sanctum');
Route::put('stock/item/{id}/update', [StockItemController::class, 'update'])->middleware('auth:sanctum');
Route::delete('stock/item/{id}/delete', [StockItemController::class, 'delete'])->middleware('auth:sanctum');
Route::post('stock/item/{id}/balance', [StockBalanceController::class, 'store'])->middleware('auth:sanctum');
Route::put('stock/balance/{id}/update', [StockBalanceController::class, 'update'])->middleware('auth:sanctum');
Route::delete('stock/balance/{id}/delete', [StockBalanceController::class, 'delete'])->middleware('auth:sanctum');



// STOCK NEEDS
Route::get('stock/needs', [StockNeedController::class, 'index'])->middleware('auth:sanctum');



// RESERVES
Route::get('reserves', [ReserveController::class, 'index'])->middleware('auth:sanctum');



// TASKS
Route::get('tasks/boards', [TaskBoardController::class, 'index']);
Route::post('tasks/boards', [TaskBoardController::class, 'store']);
Route::get('tasks/board/{id}', [TaskBoardController::class, 'board']);
Route::put('tasks/board/{id}/update', [TaskBoardController::class, 'update']);
Route::delete('tasks/board/{id}/delete', [TaskBoardController::class, 'delete']);
Route::post('tasks/columns', [TaskBoardColumnController::class, 'store']);
Route::put('tasks/columns/reorder', [TaskBoardColumnController::class, 'reorder']);
Route::put('tasks/reorder', [TaskController::class, 'reorder']);
Route::put('tasks/column/{id}/rename', [TaskBoardColumnController::class, 'rename']);
Route::delete('tasks/column/{id}/delete', [TaskBoardColumnController::class, 'delete']);
Route::post('tasks', [TaskController::class, 'store']);
Route::get('task/{id}/comments', [TaskCommentController::class, 'index']);
Route::get('task/{id}', [TaskController::class, 'task']);
Route::post('task/{id}/comments', [TaskCommentController::class, 'store']);
Route::put('task/{id}/update', [TaskController::class, 'update']);
Route::delete('task/{id}/delete', [TaskController::class, 'delete']);



// NOTIFICATIONS
Route::get('notifications/{user_id}', [NotificationController::class, 'index']);
Route::put('notification/{id}/update', [NotificationController::class, 'update']);



Route::post('file/upload', [FileController::class, 'store']);