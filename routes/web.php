<?php

use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
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

Route::match(['get'],'/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->name('verification.verify');

Route::match(['get'], '/forgot', [AuthController::class, 'showForgotPasswordForm'])->name('forgot');
Route::match(['post'], '/forgot', [AuthController::class, 'sendPasswordResetLink'])->name('forgot.post');
Route::match(['get'], '/forgot/success', [AuthController::class, 'showForgotPasswordSuccess'])->name('forgot.success');

Route::match(['get'], '/reset/{token}', [AuthController::class, 'forwardPasswordResetFromTemporaryLink'])->name('password.reset');
Route::match(['post'], '/reset', [AuthController::class, 'handlePasswordReset'])->name('password.reset.post');

Route::match(['get'], '/login', [AuthController::class, 'showLoginForm']);
Route::match(['post'], '/login', [AuthController::class, 'authenticate'])->name('login');

Route::match(['get'], 'registration', [AuthController::class, 'showRegistrationForm']);
Route::match(['post'], 'registration', [AuthController::class, 'register'])->name('register');
Route::match(['get'], '/auth/check-login-credential', [AuthController::class, 'checkLoginPresence']);

Route::match(['get'], '/home', [HomeController::class, 'index'])->name('home');
Route::match(['get'], '/', [HomeController::class, 'index']);
Route::match(['get'], '/home#menu', [HomeController::class, 'index'])->name('menu');

Route::match(['get'], '/product/{id}', [ProductController::class, 'index'])->name('product');
Route::match(['get'], '/get/products-by-category/{category_id}', [ProductController::class, 'acquireProductViewsByCategory']);
Route::match(['get'], '/get/category-name/{category_id}', [ProductController::class, 'acquireCategoryName']);
Route::match(['get'], '/get/product/{product_id}', [ProductController::class, 'acquireProductView']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::match(['post'], '/logout', [AuthController::class, 'logout'])->name('logout');
    Route::match(['post'], '/profile/update', [AuthController::class, 'update'])->name('identity.password.update');

    Route::match(['get'], '/cart', [CartController::class, 'index'])->name('cart');
    Route::match(['post'], '/cart', [CartController::class, 'update'])->name('cart.add');
    Route::match(['get'], '/get/cart-quantity', [CartController::class, 'acquireTotalQuantity'])->name('cart.total-quantity');

    Route::match(['get'], '/profile', [ProfileController::class, 'index'])->name('profile');
    Route::match(['post'], '/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::match(['get'], '/checkout', [OrderController::class, 'index'])->name('checkout');
    Route::match(['post'], '/checkout', [OrderController::class, 'order'])->name('checkout.order');
});

Route::middleware(['admin'])->group(function() {
    Route::match(['get'], '/admin/orders', [AdminOrderController::class, 'index'])->name('admin.orders');
    Route::match(['get'], '/admin/profile/{id}', [AdminOrderController::class, 'profile'])->name('admin.associated.profile');
    Route::match(['get'], '/admin/details/{id}', [AdminOrderController::class, 'details'])->name('admin.associated.details');

    Route::match(['post'], '/admin/orders/finalize', [AdminOrderController::class, 'finalize'])->name('admin.orders.finalize');
    Route::match(['post'], '/admin/orders/delete', [AdminOrderController::class, 'delete'])->name('admin.orders.delete');
});
