<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SePayWebhookController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $apiKey = (string) config('services.sepay.webhook_api_key');
        abort_if($apiKey === '', 503, 'SePay webhook is not configured.');
        abort_unless(hash_equals('Apikey '.$apiKey, (string) $request->header('Authorization')), 401);

        $data = $request->validate([
            'id' => ['required', 'integer'],
            'transferType' => ['required', 'string'],
            'transferAmount' => ['required', 'numeric'],
            'content' => ['nullable', 'string'],
            'code' => ['nullable', 'string'],
            'accountNumber' => ['nullable', 'string'],
        ]);

        if (strtolower($data['transferType']) !== 'in') {
            return response()->json(['success' => true, 'message' => 'Outgoing transaction ignored.']);
        }

        $accountNumber = (string) config('services.sepay.account_number');
        $normalizedConfiguredAccount = ltrim(preg_replace('/\D/', '', $accountNumber), '0');
        $normalizedIncomingAccount = ltrim(preg_replace('/\D/', '', (string) ($data['accountNumber'] ?? '')), '0');
        if ($normalizedConfiguredAccount !== '' && $normalizedIncomingAccount !== '' && $normalizedIncomingAccount !== $normalizedConfiguredAccount) {
            Log::warning('SePay webhook ignored: bank account does not match.', ['transaction_id' => $data['id']]);
            return response()->json(['success' => true, 'message' => 'Bank account ignored.']);
        }

        $reference = trim(($data['code'] ?? '').' '.($data['content'] ?? ''));

        $matched = DB::transaction(function () use ($data, $reference) {
            if (Order::where('sepay_transaction_id', $data['id'])->exists()) {
                return true;
            }

            $order = Order::where('payment_method', 'bank_transfer')
                ->where('status', 'pending')
                ->where('order_number', '!=', '')
                ->lockForUpdate()
                ->get()
                ->first(function (Order $order) use ($reference) {
                    $normalizedReference = preg_replace('/[^A-Z0-9]/', '', Str::upper($reference));
                    $normalizedOrderNumber = preg_replace('/[^A-Z0-9]/', '', Str::upper($order->order_number));

                    return Str::contains($normalizedReference, $normalizedOrderNumber);
                });

            if (! $order || (float) $data['transferAmount'] < (float) $order->total) {
                return false;
            }

            $order->update([
                'status' => 'confirmed',
                'sepay_transaction_id' => $data['id'],
                'paid_at' => now(),
            ]);

            return true;
        });

        if (! $matched) {
            Log::warning('SePay webhook ignored: no pending order matched the transfer reference or amount.', [
                'transaction_id' => $data['id'],
                'reference' => $reference,
                'amount' => $data['transferAmount'],
            ]);
        }

        return response()->json(['success' => true, 'matched' => $matched]);
    }
}
