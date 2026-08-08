<?php

use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;

// Tambahkan baris-baris ini ke routes/web.php yang sudah ada di project Anda

Route::get('/checkout', [CheckoutController::class, 'index']);
Route::post('/checkout/pay', [CheckoutController::class, 'pay']);
Route::post('/checkout/notification', [CheckoutController::class, 'notification']);
