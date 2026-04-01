<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CustomerAppointmentController;
use App\Http\Controllers\DeployController;
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

Route::get('auth/google/redirect', [GoogleController::class, 'redirect'])->name('auth.google.redirect')->middleware('throttle:10,1');
Route::get('auth/google/callback', [GoogleController::class, 'callback'])->name('auth.google.callback')->middleware('throttle:10,1');

// Webhooks (no CSRF, rate limited)
Route::post('webhook/whatsapp', [WhatsAppWebhookController::class, 'handle'])
    ->name('webhook.whatsapp')
    ->withoutMiddleware(PreventRequestForgery::class)
    ->middleware('throttle:120,1');

Route::post('webhook/wompi', [PaymentController::class, 'webhook'])
    ->name('webhook.wompi')
    ->withoutMiddleware(PreventRequestForgery::class)
    ->middleware('throttle:60,1');

Route::post('webhook/deploy', [DeployController::class, 'handle'])
    ->name('webhook.deploy')
    ->withoutMiddleware(PreventRequestForgery::class)
    ->middleware('throttle:5,1');

// Customer appointments
Route::middleware('auth')->group(function () {
    Route::get('mis-citas', [CustomerAppointmentController::class, 'index'])->name('customer.appointments');
    Route::post('mis-citas/{appointment}/cancelar', [CustomerAppointmentController::class, 'cancel'])->name('customer.cancel');
    Route::get('mis-citas/{appointment}/reprogramar', [CustomerAppointmentController::class, 'rescheduleForm'])->name('customer.reschedule');
    Route::get('mis-citas/{appointment}/slots', [CustomerAppointmentController::class, 'rescheduleSlots'])->name('customer.reschedule.slots');
    Route::post('mis-citas/{appointment}/reprogramar', [CustomerAppointmentController::class, 'reschedule'])->name('customer.reschedule.save');
});

// Payments
Route::get('payment/{business:slug}/checkout', [PaymentController::class, 'checkout'])
    ->name('payment.checkout')
    ->middleware('auth');

Route::get('payment/{business:slug}/result', [PaymentController::class, 'result'])
    ->name('payment.result');

// Public booking (keep at bottom - catches slugs)
Route::get('{business:slug}', [BookingController::class, 'show'])->name('booking.show');
Route::get('{business:slug}/slots', [BookingController::class, 'slots'])->name('booking.slots')->middleware('throttle:60,1');
Route::post('{business:slug}/book', [BookingController::class, 'store'])->name('booking.store')->middleware(['auth', 'throttle:10,1']);
