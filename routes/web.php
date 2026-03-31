<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WhatsAppWebhookController;
use App\Models\Business;
use App\Models\Service;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $businesses = Business::where('is_active', true)
        ->withCount(['services', 'employees'])
        ->with('media')
        ->latest()
        ->limit(12)
        ->get();

    $services = Service::where('is_active', true)
        ->whereHas('business', fn ($q) => $q->where('is_active', true))
        ->with(['business', 'media'])
        ->inRandomOrder()
        ->limit(12)
        ->get();

    return view('welcome', compact('businesses', 'services'));
});

Route::get('auth/google/redirect', [GoogleController::class, 'redirect'])->name('auth.google.redirect');
Route::get('auth/google/callback', [GoogleController::class, 'callback'])->name('auth.google.callback');

// Webhooks (no CSRF)
Route::post('webhook/whatsapp', [WhatsAppWebhookController::class, 'handle'])
    ->name('webhook.whatsapp')
    ->withoutMiddleware(PreventRequestForgery::class);

Route::post('webhook/wompi', [PaymentController::class, 'webhook'])
    ->name('webhook.wompi')
    ->withoutMiddleware(PreventRequestForgery::class);

// Payments
Route::get('payment/{business:slug}/checkout', [PaymentController::class, 'checkout'])
    ->name('payment.checkout')
    ->middleware('auth');

Route::get('payment/{business:slug}/result', [PaymentController::class, 'result'])
    ->name('payment.result');

// Public booking (keep at bottom - catches slugs)
Route::get('{business:slug}', [BookingController::class, 'show'])->name('booking.show');
Route::get('{business:slug}/slots', [BookingController::class, 'slots'])->name('booking.slots');
Route::post('{business:slug}/book', [BookingController::class, 'store'])->name('booking.store')->middleware('auth');
