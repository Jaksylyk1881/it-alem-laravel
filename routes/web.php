<?php

use App\Http\Controllers\Admin\ProductController;
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

    Route::get('user/delete/image/{image}', [UserController::class, 'destroyImage'])->name('user.destroy.image');
    Route::resource('user', UserController::class);
    Route::resource('product', ProductController::class);
    Route::get('product/delete/image/{image}', [ProductController::class, 'destroyImage'])->name('product.destroy.image');
    Route::view( 'home','admin.pages.home')->name('home');
});
