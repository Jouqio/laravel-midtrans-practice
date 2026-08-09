<?php

use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CheckoutController::class, 'index']);
Route::get('/checkout', [CheckoutController::class, 'index']);
Route::post('/checkout/pay', [CheckoutController::class, 'pay']);
Route::post('/checkout/notification', [CheckoutController::class, 'notification']);
