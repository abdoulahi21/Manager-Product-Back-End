<?php

use App\Http\Controllers\CartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/cart/{id}', [CartController::class, 'addToCart']);
Route::get('/cart', [CartController::class, 'getCart']);
Route::put('/cart/{productId}', [CartController::class, 'updateCartItem']);
Route::delete('/cart/{productId}', [CartController::class, 'removeCartItem']);

//Route::post('/order', [\App\Http\Controllers\CommandesController::class, 'createOrder']);
Route::apiResource('produit',\App\Http\Controllers\Admin\ProduitsController::class);
Route::apiResource('categorie',\App\Http\Controllers\Admin\CategoriesController::class);
Route::post('/login',[\App\Http\Controllers\Admin\UserController::class,'login']);
Route::post('/user',[\App\Http\Controllers\Admin\UserController::class,'store']);
Route::post('/order',[\App\Http\Controllers\CommandesController::class,'store']);
