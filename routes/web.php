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

Route::get('/test', static function() {
    return view('test');
});

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
    ->name('forgot-password');

Route::post('/forgot-password', [AuthController::class, 'requestPasswordReset'])
    ->name('forgot-password.post');

Route::get('/forgot-password/success', [AuthController::class, 'showForgotPasswordSuccess'])
    ->name('forgot-password.success');

Route::get('/reset-password/{token}', [AuthController::class, 'showPasswordResetForm'])
    ->name('password.reset')
    ->middleware('not.expired');

Route::post('/reset-password', [AuthController::class, 'resetPassword'])
    ->name('password.reset.post');

Route::get('/login', [AuthController::class, 'showLoginForm']);
Route::post('/login', [AuthController::class, 'authenticate'])
    ->name('login');

Route::get('/registration', [AuthController::class, 'showRegistrationForm']);
Route::post('/registration', [AuthController::class, 'register'])
    ->name('register');

//
//
// HOME
//
//

Route::get('/home', [HomeController::class, 'index'])
    ->name('home');
Route::get('/', [HomeController::class, 'index']);
Route::get('/home#menu', [HomeController::class, 'index'])
    ->name('menu');

//
//
// PRODUCT
//
//

Route::get('/product/{id}', [ProductController::class, 'index'])
    ->name('product');

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
        ->name('cart');

    Route::get('/profile', [ProfileController::class, 'index'])
        ->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::get('/checkout', [OrderController::class, 'index'])
        ->name('checkout');
    Route::post('/checkout', [OrderController::class, 'order'])
        ->name('checkout.order');
});



//
//
// FRONTEND CALLS
//
//


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
