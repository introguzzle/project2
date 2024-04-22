<?php

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

Route::get("/home", function() {
    return "home";
});

Route::get("/1337", function () {
    return view('test');
});

Route::get("/1338", function() {
    return view('test2');
});

Route::get("/1339", function() {
    return view('admin');
});

Route::get("/1340", function() {
    return view('test3');
});
