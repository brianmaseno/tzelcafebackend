<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\GroqChatController;
use App\Http\Controllers\Api\MenuItemController;
use App\Http\Controllers\Api\NewsletterController;
use App\Http\Controllers\Api\PaystackWebhookController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\OrderTrackingController;
use App\Http\Controllers\Api\PromotionController;
use Illuminate\Support\Facades\Route;

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/menu-items', [MenuItemController::class, 'index']);
Route::get('/promotions', [PromotionController::class, 'index']);
Route::post('/promotions/validate', [PromotionController::class, 'validateCode']);
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe']);
Route::post('/contact', [ContactController::class, 'store']);

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/password/otp', [AuthController::class, 'sendPasswordOtp']);
Route::post('/auth/password/reset', [AuthController::class, 'resetPasswordWithOtp']);

Route::post('/chat', GroqChatController::class);

Route::post('/orders/track', [OrderTrackingController::class, 'track']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', fn () => response()->json([
        'data' => AuthController::userPayload(request()->user()),
    ]));
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::patch('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/profile/password/otp', [AuthController::class, 'sendProfileOtp']);
    Route::put('/profile/password', [AuthController::class, 'updatePassword']);

    Route::post('/checkout/initialize', [CheckoutController::class, 'initialize']);
    Route::post('/checkout/verify', [CheckoutController::class, 'verify']);
    Route::get('/orders', [CheckoutController::class, 'orders']);
    Route::get('/orders/{order}', [OrderTrackingController::class, 'show']);
});

Route::post('/paystack/webhook', PaystackWebhookController::class)->name('paystack.webhook');
