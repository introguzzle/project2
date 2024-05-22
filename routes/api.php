<?php

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

use App\Http\Controllers\API\TelegramController;
use App\Http\Controllers\API\VKController;
use Illuminate\Support\Facades\Route;

Route::post('/telegram-webhook', [TelegramController::class, 'webhook'])
    ->name('telegram.webhook')
    ->withoutMiddleware(['web', 'csrf']);
