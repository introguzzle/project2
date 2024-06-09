<?php

use App\Http\Controllers\API\Auth\GoogleController;
use App\Http\Controllers\API\Auth\VKController;
use App\Http\Controllers\Client\AuthController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\ImageController;
use App\Http\Controllers\Client\OrderController;
use App\Http\Controllers\Client\ProductController;
use App\Http\Controllers\Client\ProfileController;
use App\Models\Category;
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

Route::get('/images/{name}', [ImageController::class, 'show'])
    ->name('images.show');

//
//
// AUTH
//
//

Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->middleware('not.expired')
    ->name('verification.verify');

Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])
    ->name('forgot-password.index');

Route::post('/forgot-password', [AuthController::class, 'requestPasswordReset'])
    ->name('forgot-password');

Route::get('/forgot-password/success', [AuthController::class, 'showForgotPasswordSuccess'])
    ->name('forgot-password.success.index');

Route::get('/reset-password/{token}', [AuthController::class, 'showPasswordResetForm'])
    ->name('password.reset.index')
    ->middleware('not.expired');

Route::post('/reset-password', [AuthController::class, 'resetPassword'])
    ->name('password.reset');

Route::get('/login', [AuthController::class, 'showLoginForm'])
    ->name('login.index');
Route::post('/login', [AuthController::class, 'authenticate'])
    ->name('login');

Route::get('/registration', [AuthController::class, 'showRegistrationForm'])
    ->name('register.index');
Route::post('/registration', [AuthController::class, 'register'])
    ->name('register');

//
//
// HOME
//
//

Route::get('/home', [HomeController::class, 'index'])
    ->name('home.index');
Route::get('/', [HomeController::class, 'index']);
Route::get('/home#menu', [HomeController::class, 'index'])
    ->name('menu.index');

//
//
// PRODUCT
//
//

Route::get('/product/{id}', [ProductController::class, 'index'])
    ->name('product.index');

//
//
// AUTHENTICATED
//
//

Route::middleware(['auth', 'email.verified'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
    Route::post('/identity/update', [AuthController::class, 'update'])
        ->name('identity.update');

    Route::get('/cart', [CartController::class, 'showCart'])
        ->name('cart.index');

    Route::get('/profile', [ProfileController::class, 'index'])
        ->name('profile.index');
    Route::post('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::get('/checkout', [OrderController::class, 'index'])
        ->name('order.index');
    Route::post('/checkout', [OrderController::class, 'order'])
        ->name('order');
});



//
//
// FRONTEND CALLS
//
//


Route::get('/api/payment-methods', [OrderController::class, 'getPaymentMethods'])
    ->name('api.payment-methods');


Route::get('/api/cart-quantity', [CartController::class, 'getTotalQuantity'])
    ->name('api.cart.total-quantity');

Route::get('/api/cart-total-price', [CartController::class, 'getTotalPrice'])
    ->name('api.cart.total-price');

Route::get('/api/products/{category_id}', [ProductController::class, 'getProductResourceCollection'])
    ->name('api.products');

Route::get('/api/category/{category_id}', [ProductController::class, 'getCategoryResource'])
    ->name('api.category');

Route::get('/api/product/{product_id}', [ProductController::class, 'getProductResource'])
    ->name('api.product');

Route::post('/cart-products-update-quantity', [CartController::class, 'updateQuantity'])
    ->name('cart.update-quantity');

//
//
// AUTH
//
//

Route::get('/api/check-login-credential', [AuthController::class, 'checkLoginPresence'])
    ->name('api.login.check');

Route::get('/auth/vk', [VKController::class, 'redirectToProvider'])
    ->name('vk.auth');

Route::get(env('VKONTAKTE_REDIRECT_URI'), [VKController::class, 'handleProviderCallback'])
    ->name('vk.auth.redirect');

Route::get('/auth/google', [GoogleController::class, 'redirectToProvider'])
    ->name('google.auth');

Route::get(env('GOOGLE_REDIRECT_URI'), [GoogleController::class, 'handleProviderCallback'])
    ->name('google.auth.redirect');
