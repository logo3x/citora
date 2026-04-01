<?php

namespace App\Services;

use App\Models\Business;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class WompiService
{
    public function createPaymentLink(Business $business): array
    {
        $period = now()->format('Y-m');
        $reference = 'CITORA-'.Str::upper(Str::random(8)).'-'.$business->id;
        $amountCop = config('services.wompi.unlock_price');
        $amountInCents = $amountCop * 100;
        $currency = config('services.wompi.currency');

        $payment = $business->payments()->create([
            'reference' => $reference,
            'provider' => 'wompi',
            'amount' => $amountCop,
            'currency' => $currency,
            'status' => 'pending',
            'period' => $period,
        ]);

        $integritySecret = config('services.wompi.integrity_secret');
        $signatureString = "{$reference}{$amountInCents}{$currency}{$integritySecret}";
        $signature = hash('sha256', $signatureString);

        return [
            'payment_id' => $payment->id,
            'public_key' => config('services.wompi.public_key'),
            'reference' => $reference,
            'amount_in_cents' => $amountInCents,
            'currency' => $currency,
            'signature' => $signature,
            'redirect_url' => $this->getRedirectUrl($business),
        ];
    }

    private function getRedirectUrl(Business $business): string
    {
        $base = config('services.wompi.redirect_base');

        if ($base) {
            return rtrim($base, '/').'/payment/'.$business->slug.'/result';
        }

        return route('payment.result', $business);
    }

    public function handleWebhook(array $data): void
    {
        $transaction = $data['data']['transaction'] ?? null;

        if (! $transaction) {
            return;
        }

        $reference = $transaction['reference'] ?? '';
        $status = $transaction['status'] ?? '';

        $payment = Payment::where('reference', $reference)->first();

        if (! $payment) {
            return;
        }

        $mappedStatus = match ($status) {
            'APPROVED' => 'approved',
            'DECLINED' => 'declined',
            'VOIDED' => 'voided',
            'ERROR' => 'error',
            default => 'pending',
        };

        $payment->update([
            'status' => $mappedStatus,
            'provider_transaction_id' => $transaction['id'] ?? null,
            'provider_data' => $transaction,
            'paid_at' => $mappedStatus === 'approved' ? now() : null,
        ]);
    }

    public function verifyTransaction(string $transactionId): ?array
    {
        $response = Http::timeout(10)->retry(2, 200)->get(config('services.wompi.base_url')."/v1/transactions/{$transactionId}");

        if ($response->successful()) {
            return $response->json('data');
        }

        return null;
    }
}
