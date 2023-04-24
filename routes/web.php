<?php

use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::prefix('admin')->as('admin.')->group(function () {
    Route::view( 'home','admin.pages.home')->name('home');

    Route::get('user/delete/image/{image}', [UserController::class, 'destroyImage'])->name('user.destroy.image');
    Route::resource('user', UserController::class);

    Route::resource('product', ProductController::class);
    Route::get('product/delete/image/{image}', [ProductController::class, 'destroyImage'])->name('product.destroy.image');

    Route::get('product/{product}/review', [ReviewController::class, 'index'])->name('product.review.index');
    Route::delete('product/{product}/review/{review}', [ReviewController::class, 'destroy'])->name('product.review.destroy');

    Route::resource('brand', BrandController::class);
    Route::resource('category', CategoryController::class);
    Route::resource('banner', BannerController::class);
    Route::resource('order', OrderController::class);
    Route::get('order/{order}/product', [OrderController::class, 'products'])->name('order_product.index');
    Route::delete('order/product/{order_product}', [OrderController::class, 'destroyProduct'])->name('order_product.destroy');
});
