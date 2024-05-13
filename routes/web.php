<?php

use App\Http\Controllers\AdminOrderController;
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

Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->name('verification.verify');

Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('forgot-password');
Route::post('/forgot-password', [AuthController::class, 'sendPasswordResetLink'])->name('forgot-password.post');
Route::get('/forgot-password/success', [AuthController::class, 'showForgotPasswordSuccess'])->name('forgot-password.success');

Route::get('/reset-password/{token}', [AuthController::class, 'forwardPasswordResetFromTemporaryLink'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'handlePasswordReset'])->name('password.reset.post');

Route::get('/login', [AuthController::class, 'showLoginForm']);
Route::post('/login', [AuthController::class, 'authenticate'])->name('login');

Route::get('/registration', [AuthController::class, 'showRegistrationForm']);
Route::post('/registration', [AuthController::class, 'register'])->name('register');

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/', [HomeController::class, 'index']);
Route::get('/home#menu', [HomeController::class, 'index'])->name('menu');

Route::get('/product/{id}', [ProductController::class, 'index'])->name('product');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/identity/update', [AuthController::class, 'update'])->name('identity.update');

    Route::get('/cart', [CartController::class, 'showCart'])->name('cart');
    Route::post('/cart-products-update-quantity', [CartController::class, 'updateQuantity'])->name('cart.update-quantity');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/checkout', [OrderController::class, 'index'])->name('checkout');
    Route::post('/checkout', [OrderController::class, 'order'])->name('checkout.order');
});

Route::middleware(['admin'])->prefix('/admin')->group(function() {
    Route::get('/orders', [AdminOrderController::class, 'showOrders'])->name('admin.orders');
    Route::get('/profile/{id}', [AdminOrderController::class, 'showProfile'])->name('admin.associated.profile');
    Route::get('/order/{id}', [AdminOrderController::class, 'showOrder'])->name('admin.associated.order');

    Route::post('/orders/complete', [AdminOrderController::class, 'complete'])->name('admin.orders.complete');
    Route::post('/orders/delete', [AdminOrderController::class, 'delete'])->name('admin.orders.delete');
});

// TODO move to api

Route::get('/api/cart-quantity', [CartController::class, 'acquireTotalQuantity'])->name('api.cart.total-quantity');
Route::get('/api/cart-total-price', [CartController::class, 'acquireTotalPrice'])->name('api.cart.total-price');

Route::get('/api/products/{category_id}', [ProductController::class, 'acquireProductViewsByCategory'])->name('api.products');
Route::get('/api/category/{category_id}', [ProductController::class, 'acquireCategory'])->name('api.category');
Route::get('/api/product/{product_id}', [ProductController::class, 'acquireProductView'])->name('api.product');

Route::get('/api/check-login-credential', [AuthController::class, 'checkLoginPresence'])->name('api.login.check');

