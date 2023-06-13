<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\CategoryController;
use App\Http\Controllers\Api\v1\ProductController;
use App\Http\Controllers\Api\v1\UserController;
use App\Http\Controllers\Api\v1\TransactionController;
use App\Http\Controllers\Api\v1\AuthenticationController;

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


Route::group(['prefix' => 'v1'], function () {    
    Route::group(['middleware' => ['api']], function () {
        Route::post('/login', [AuthenticationController::class, 'login']);
        Route::group(['middleware' => ['auth:sanctum']], function () {
            Route::resource('category', CategoryController::class);
            Route::resource('product', ProductController::class);
            Route::resource('user', UserController::class);
            Route::get('/transactions', [TransactionController::class, 'index']);
            Route::post('/transactions', [TransactionController::class, 'store']);
        });
    });
});
