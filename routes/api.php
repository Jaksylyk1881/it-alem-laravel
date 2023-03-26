<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserAddressController;
use App\Http\Controllers\Api\UserBasketController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserOrderController;
use App\Http\Controllers\Api\UserReviewController;
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

Route::prefix('auth')->group(function() {
    Route::post('/', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::prefix('code')->group(function () {
        Route::get('send', [AuthController::class, 'sendCode']);
        Route::post('check', [AuthController::class, 'checkCode']);
    });
});

Route::prefix('product')->group(function () {
    Route::get('form-values', [ProductController::class, 'searchFormValues']);
    Route::get('company/{company}', [CompanyController::class, 'show']);
});
Route::apiResource('product', ProductController::class)->only(['index', 'show']);

Route::get('banner', [BannerController::class, 'index']);
Route::get('category', [CategoryController::class, 'index']);

Route::prefix('user')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::put('/', [UserController::class, 'update']);
    Route::get('/product', [UserController::class, 'products']);
    Route::delete('products/image/{image}', [ProductController::class, 'deleteImage']);
    Route::apiResource('product', ProductController::class)->only(['store', 'update', 'destroy']);

    Route::delete('basket/clear', [UserBasketController::class, 'clear']);
    Route::apiResource('basket', UserBasketController::class)->only(['index', 'store', 'update','destroy']);
    Route::apiResource('order', UserOrderController::class)->only(['index', 'show', 'store']);
    Route::apiResource('address', UserAddressController::class)->only(['index', 'store', 'destroy']);

    Route::post('review', [UserReviewController::class, 'store']);
});

