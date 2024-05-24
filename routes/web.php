<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;


Route::middleware('auth')->group(function () {
    Route::resource('products', ProductController::class);
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});

Route::get('/login', [LoginController::class,'index'])->name('login');
Route::post('/login', [LoginController::class,'login']);

