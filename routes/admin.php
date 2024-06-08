<?php

use App\Http\Controllers\Admin\CafeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\Controller;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Admin\FlowController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;

use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\StatusController;
use Illuminate\Support\Facades\Route;

Route::get('/', [Controller::class, 'showDashboard'])
    ->name('admin.dashboard');

Route::match(['POST', 'PUT'], '/', [Controller::class, 'updateProfile'])
    ->name('admin.dashboard.update');

Route::get('/update-password', [Controller::class, 'showUpdatePassword'])
    ->name('admin.dashboard.update-password.index');

Route::match(['POST', 'PUT'], '/update-password', [Controller::class, 'updatePassword'])
    ->name('admin.dashboard.update-password');

Route::post('/logout', [Controller::class, 'logout'])
    ->name('admin.dashboard.logout');

//
//

Route::get('/promotions', [PromotionController::class, 'showPromotions'])
    ->name('admin.promotions.index');

Route::get('/promotions/create', [PromotionController::class, 'showCreate'])
    ->name('admin.promotions.create.index');

Route::match(['POST', 'PUT'], '/promotions/create', [PromotionController::class, 'create'])
    ->name('admin.promotions.create');

Route::match(['POST', 'PUT'], '/promotions/delete', [PromotionController::class, 'delete'])
    ->name('admin.promotions.delete');

Route::match(['POST', 'PUT'], '/promotions/update', [PromotionController::class, 'update'])
    ->name('admin.promotions.update');

//
//
// FAVICON
//
//

Route::get('/favicon/update', [Controller::class, 'showFaviconUpdate'])
    ->name('admin.dashboard.update-favicon.index');

Route::match(['POST', 'PUT'], '/update-favicon', [Controller::class, 'updateFavicon'])
    ->name('admin.dashboard.update-favicon');

//
//
// TOKEN
//
//

Route::get('/token', [Controller::class, 'showToken'])
    ->name('admin.dashboard.token');

Route::get('/token/reset', [Controller::class, 'resetToken'])
    ->name('admin.dashboard.token.reset');

Route::get('/token/reset-all', [Controller::class, 'resetAllTokens'])
    ->name('admin.dashboard.token.reset-all');

//
//
// METHODS
//
//

Route::get('/flows', [FlowController::class, 'show'])
    ->name('admin.flows.index');

Route::get('/flows/delete', [FlowController::class, 'delete'])
    ->name('admin.flows.delete');

Route::match(['POST', 'PUT'], '/flows', [FlowController::class, 'update'])
    ->name('admin.flows.update');

Route::get('/flows/receipt-method/create', [FlowController::class, 'showReceiptMethodCreate'])
    ->name('admin.flows.receipts.create.index');

Route::match(['POST', 'PUT'], '/flows/receipt-method/create', [FlowController::class, 'createReceiptMethod'])
    ->name('admin.flows.receipts.create');

Route::match(['delete', 'post'], '/flows/receipt-method/delete', [FlowController::class, 'deleteReceiptMethod'])
    ->name('admin.flows.receipts.delete');


Route::get('/flows/payment-method/create', [FlowController::class, 'showPaymentMethodCreate'])
    ->name('admin.flows.payments.create.index');

Route::match(['POST', 'PUT'], '/flows/payment-method/create', [FlowController::class, 'createPaymentMethod'])
    ->name('admin.flows.payments.create');

Route::match(['delete', 'post'], '/flows/payment-method/delete', [FlowController::class, 'deletePaymentMethod'])
    ->name('admin.flows.payments.delete');


//
//
// IMAGES
//
//

Route::get('/images', [ImageController::class, 'showImages'])
    ->name('admin.images.index');

//
//
//
//
//

Route::get('/cafe', [CafeController::class, 'showCafe'])
    ->name('admin.cafe.index');

Route::match(['POST', 'PUT'], '/cafe', [CafeController::class, 'update'])
    ->name('admin.cafe.update');

//
//
// CATEGORIES
//
//

Route::get('/categories', [CategoryController::class, 'showCategories'])
    ->name('admin.categories.index');

Route::get('/categories/create', [CategoryController::class, 'showCreate'])
    ->name('admin.categories.create.index');

Route::post('/categories/create', [CategoryController::class, 'create'])
    ->name('admin.categories.create');

Route::get('/categories/update/{id}', [CategoryController::class, 'showUpdate'])
    ->name('admin.categories.update.index');

Route::post('/categories/update', [CategoryController::class, 'update'])
    ->name('admin.categories.update');

Route::get('/categories/delete/{id}', [CategoryController::class, 'showDelete'])
    ->name('admin.categories.delete.index');

Route::post('/categories/delete', [CategoryController::class, 'delete'])
    ->name('admin.categories.delete');


//
//
// PRODUCTS
//
//



Route::get('/products/update/{id}', [ProductController::class, 'showUpdate'])
    ->name('admin.products.update.index');

Route::match(['PUT', 'POST'], '/products/update', [ProductController::class, 'update'])
    ->name('admin.products.update');

Route::get('/products', [ProductController::class, 'showProducts'])
    ->name('admin.products.index');

Route::get('/products/delete/{id}', [ProductController::class, 'showDelete'])
    ->name('admin.products.delete.index');

Route::post('/products/delete', [ProductController::class, 'delete'])
    ->name('admin.products.delete');

Route::get('/products/create', [ProductController::class, 'showCreate'])
    ->name('admin.products.create.index');

Route::post('/products/create', [ProductController::class, 'create'])
    ->name('admin.products.create');

//
//
// STATUSES
//
//


Route::get('/statuses/update/{id}', [StatusController::class, 'showUpdate'])
    ->name('admin.statuses.update.index');

Route::match(['PUT', 'POST'], '/statuses/update', [StatusController::class, 'update'])
    ->name('admin.statuses.update');

Route::get('/statuses', [StatusController::class, 'showStatuses'])
    ->name('admin.statuses.index');

Route::get('/statuses/delete/{id}', [StatusController::class, 'showDelete'])
    ->name('admin.statuses.delete.index');

Route::post('/statuses/delete', [StatusController::class, 'delete'])
    ->name('admin.statuses.delete');

Route::get('/statuses/create', [StatusController::class, 'showCreate'])
    ->name('admin.statuses.create.index');

Route::post('/statuses/create', [StatusController::class, 'create'])
    ->name('admin.statuses.create');


//
//
// ORDERS
//
//


Route::get('/orders/active', [OrderController::class, 'showActiveOrders'])
    ->name('admin.orders.active.index');

Route::get('/orders/complete', [OrderController::class, 'showCompletedOrders'])
    ->name('admin.orders.completed.index');

Route::get('/orders/update/{id}', [OrderController::class, 'showUpdate'])
    ->name('admin.orders.update.index');

Route::get('/orders/order/profile/{id}', [OrderController::class, 'showProfile'])
    ->name('admin.orders.profile.index');

Route::get('/orders/{id}', [OrderController::class, 'showOrder'])
    ->name('admin.orders.order.index');

Route::post('/orders/complete', [OrderController::class, 'complete'])
    ->name('admin.orders.complete');

Route::post('/orders/update', [OrderController::class, 'update'])
    ->name('admin.orders.update');
