<?php

use App\Http\Controllers\AppointmentShareController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CronController;
use App\Http\Controllers\CustomerAppointmentController;
use App\Http\Controllers\DeployController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WhatsAppWebhookController;
use App\Models\Business;
use App\Models\Service;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Http\Request;
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

Route::post('webhook/sms', [WhatsAppWebhookController::class, 'handle'])
    ->name('webhook.sms')
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

// Cron-job.org endpoint for scheduled tasks (bypass proc_open limitations)
Route::get('cron/reminders', [CronController::class, 'reminders'])
    ->name('cron.reminders')
    ->middleware('throttle:10,1');

// Search
Route::get('buscar', function (Request $request) {
    $q = $request->input('q', '');
    if (strlen($q) < 2) {
        return response()->json(['businesses' => [], 'services' => []]);
    }

    $businesses = Business::where('is_active', true)
        ->where(fn ($query) => $query->where('name', 'like', "%{$q}%")->orWhere('address', 'like', "%{$q}%"))
        ->with('media')
        ->limit(5)
        ->get()
        ->map(fn ($b) => ['name' => $b->name, 'slug' => $b->slug, 'address' => $b->address, 'logo' => $b->getFirstMediaUrl('logo')]);

    $services = Service::where('is_active', true)
        ->where('name', 'like', "%{$q}%")
        ->whereHas('business', fn ($query) => $query->where('is_active', true))
        ->with(['business', 'media'])
        ->limit(5)
        ->get()
        ->map(fn ($s) => ['name' => $s->name, 'price' => $s->price, 'duration' => $s->duration_minutes, 'slug' => $s->business->slug, 'business' => $s->business->name, 'image' => $s->getFirstMediaUrl('image')]);

    return response()->json(['businesses' => $businesses, 'services' => $services]);
})->name('search');

// Legal pages
Route::get('privacidad', [LegalController::class, 'privacy'])->name('legal.privacy');
Route::get('terminos', [LegalController::class, 'terms'])->name('legal.terms');

// Shareable appointment page (short link for SMS notifications)
Route::get('c/{token}', [AppointmentShareController::class, 'show'])
    ->where('token', '[a-z0-9]{8}')
    ->name('appointment.share');

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
