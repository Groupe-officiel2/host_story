<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\paypalEvent\SubscriptionController;
use App\Http\Controllers\paypalEvent\WebhookController;


Route::get('/plans', [SubscriptionController::class, 'index'])->name('plans.index');

// Routes protégées par auth
Route::middleware('auth')->group(function () {
    // Subscription and PayPal return handling
    Route::get('/subscribe/{plan}',      [SubscriptionController::class, 'subscribe'])->name('subscribe');
    Route::get('/subscription/success',  [SubscriptionController::class, 'success'])->name('subscription.success');
    Route::get('/subscription/cancel',   [SubscriptionController::class, 'cancel'])->name('subscription.cancel');
}); 

// PayPal Webhook endpoint
Route::post('/subscription/webhook', [WebhookController::class, 'webhook'])->name('subscription.webhook');
