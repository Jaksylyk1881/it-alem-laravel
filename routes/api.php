<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\PrivacyPolicyController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserAddressController;
use App\Http\Controllers\Api\UserBasketController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserOrderController;
use App\Http\Controllers\Api\UserReviewController;
use App\Http\Controllers\Api\UserCompanyController;
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

    Route::post('reset/password', [AuthController::class, 'resetPassword']);
    Route::prefix('code')->group(function () {
        Route::get('send', [AuthController::class, 'sendCode']);
        Route::post('check', [AuthController::class, 'checkCode']);
    });
});

Route::prefix('product')->group(function () {
    Route::get('form-values', [ProductController::class, 'searchFormValues']);
    Route::get('company/{company}', [CompanyController::class, 'show']);
    Route::post('company/{company}', [UserReviewController::class, 'companyStore'])->middleware('auth:sanctum');
    Route::get('company/product/{company}', [ProductController::class, 'company']);

    Route::get('{product}/review', [UserReviewController::class, 'index']);
    Route::post('{product}/review', [UserReviewController::class, 'store'])->middleware('auth:sanctum');
});
Route::apiResource('product', ProductController::class)->only(['index', 'show']);

Route::get('company', [CompanyController::class, 'index']);
Route::get('banner', [BannerController::class, 'index']);
Route::get('category', [CategoryController::class, 'index']);
Route::apiResource('chat', ChatController::class)->only(['index', 'show'])->middleware('auth:sanctum');
Route::get('privacy-policy', [PrivacyPolicyController::class, 'index']);

Route::prefix('user')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::put('/', [UserController::class, 'update']);

    Route::get('company/order', [UserCompanyController::class, 'index']);
    Route::get('company/order/{order}', [UserCompanyController::class, 'show']);
    Route::put('company/order/{order}', [UserCompanyController::class, 'update']);

    Route::get('/product', [UserController::class, 'products']);
    Route::get('/product/placeholders', [ProductController::class, 'placeholders']);
    Route::delete('products/{product}/image/{image}', [ProductController::class, 'deleteImage']);
    Route::delete('products/{product}/gift/{gift}', [ProductController::class, 'deleteGift']);
    Route::apiResource('product', ProductController::class)->only(['store', 'update', 'destroy']);
    Route::delete('favorite/{product_id}', [FavoriteController::class, 'destroy']);

    Route::delete('basket/clear', [UserBasketController::class, 'clear']);
    Route::apiResource('basket', UserBasketController::class)->only(['index', 'store', 'update','destroy']);
    Route::apiResource('order', UserOrderController::class)->only(['index', 'show', 'store', 'update']);
    Route::apiResource('address', UserAddressController::class)->only(['index', 'store', 'destroy']);
    Route::apiResource('company', UserCompanyController::class)->only(['index', 'show']);
    Route::apiResource('favorite', FavoriteController::class)->only('index', 'store');
});

