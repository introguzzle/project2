<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
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

Route::match(['get'], '/login', [AuthController::class, 'viewLogin']);
Route::match(['post'], '/login', [AuthController::class, 'login'])->name('login');

Route::match(['get'], 'registration', [AuthController::class, 'viewRegistration']);
Route::match(['post'], 'registration', [AuthController::class, 'register'])->name('register');
Route::match(['get'], '/auth/check-login-credential', [AuthController::class, 'isLoginPresent']);

Route::match(['get'], '/home', [HomeController::class, 'index'])->name('home');
Route::match(['get'], '/', [HomeController::class, 'index']);

Route::match(['get'], '/product/{id}', [ProductController::class, 'index']);
Route::match(['get'], '/get/products-by-category/{category_id}', [ProductController::class, 'acquireProductViewsByCategory']);
Route::match(['get'], '/get/category-name/{category_id}', [ProductController::class, 'acquireCategoryName']);
Route::match(['get'], '/get/product/{product_id}', [ProductController::class, 'acquireProductView']);

Route::middleware(['auth'])->group(function () {
    Route::match(['get'], '/cart', [CartController::class, 'index'])->name('cart');
    Route::match(['post'], '/cart', [CartController::class, 'update'])->name('cart.add');
    Route::match(['get'], '/get/cart-quantity', [CartController::class, 'acquireTotalQuantity'])->name('cart.total-quantity');

    Route::match(['get'], '/profile', [ProfileController::class, 'index'])->name('profile');
    Route::match(['post'], '/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::match(['get'], '/checkout', [OrderController::class, 'index'])->name('checkout');
});
