<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Payment;
use App\Services\WompiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function checkout(Business $business, WompiService $wompi): View
    {
        $paymentData = $wompi->createPaymentLink($business);

        return view('payment.checkout', [
            'business' => $business,
            'payment' => $paymentData,
            'used' => $business->getMonthlyAppointmentCount(),
            'limit' => $business->monthly_appointment_limit,
            'price' => config('services.wompi.unlock_price'),
        ]);
    }

    public function result(Request $request, Business $business, WompiService $wompi): View
    {
        $transactionId = $request->query('id');
        $status = 'pending';

        if ($transactionId) {
            $transaction = $wompi->verifyTransaction($transactionId);

            if ($transaction) {
                $reference = $transaction['reference'] ?? '';
                $payment = Payment::where('reference', $reference)->first();

                if ($payment && $payment->status === 'pending') {
                    $mappedStatus = match ($transaction['status'] ?? '') {
                        'APPROVED' => 'approved',
                        'DECLINED' => 'declined',
                        default => 'pending',
                    };

                    $payment->update([
                        'status' => $mappedStatus,
                        'provider_transaction_id' => $transaction['id'] ?? null,
                        'provider_data' => $transaction,
                        'paid_at' => $mappedStatus === 'approved' ? now() : null,
                    ]);
                }

                $status = $payment?->status ?? 'pending';
            }
        }

        return view('payment.result', [
            'business' => $business,
            'status' => $status,
        ]);
    }

    public function webhook(Request $request, WompiService $wompi): Response
    {
        $signature = $request->header('X-Event-Checksum');
        $body = $request->all();

        $event = $body['event'] ?? '';
        $transaction = $body['data']['transaction'] ?? [];
        $timestamp = $body['timestamp'] ?? '';

        $properties = implode('', [
            $transaction['id'] ?? '',
            $transaction['status'] ?? '',
            $transaction['amount_in_cents'] ?? '',
            $timestamp,
        ]);
        $expectedSignature = hash('sha256', $properties.config('services.wompi.events_secret'));

        if ($signature !== $expectedSignature) {
            Log::warning('Wompi webhook: invalid signature');

            return response('Invalid signature', 401);
        }

        Log::info('Wompi webhook', ['event' => $event, 'reference' => $transaction['reference'] ?? '']);

        $wompi->handleWebhook($body);

        return response('OK', 200);
    }
}
