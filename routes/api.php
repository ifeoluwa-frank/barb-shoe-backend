<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/hello', function () {
    return "Hello World!";
});

//Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//Product Operations
Route::get('/inventory', [InventoryController::class, 'index']);
Route::post('/addProduct', [InventoryController::class, 'addInventory']);
Route::get('/getProduct/{id}', [InventoryController::class, 'getProductById']);
Route::post('/updateProduct/{id}', [InventoryController::class, 'updateInventory']);
Route::delete('/deleteProduct/{id}', [InventoryController::class, 'deleteInventory']);

Route::get('/cartItems/{user_id}', [CartController::class, 'index']);
Route::post('/addCartItems', [CartController::class, 'store']);
