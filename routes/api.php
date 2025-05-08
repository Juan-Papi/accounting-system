<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProviderController;
use App\Http\Controllers\Api\CategoryController;
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

Route::post('/login', [AuthController::class, 'login']);

//TODO: RUTAS PROTEGIDAS POR SANCTUM
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post("/logout", [AuthController::class, "logout"]);
    Route::get("/check-status", [AuthController::class, "checkAuthStatus"]);

});
Route::apiResource('/product', ProductController::class);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/providers', [ProviderController::class, 'index']);
