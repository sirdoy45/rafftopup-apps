<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Transaction as MidtransTransaction;

class MidtransCallbackController extends Controller
{
    public function handle(Request $request)
    {
        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.serverKey');
        Config::$isProduction = config('midtrans.isProduction');

        $payload = $request->all();
        $orderId = $payload['order_id'] ?? null;

        if (!$orderId) {
            return response()->json(['message' => 'Order ID tidak ditemukan'], 400);
        }

        // Validasi Signature
        $expectedSignature = hash('sha512', $payload['order_id'] . $payload['status_code'] . $payload['gross_amount'] . Config::$serverKey);
        if (($payload['signature_key'] ?? '') !== $expectedSignature) {
            Log::warning('❌ Signature tidak valid', ['order_id' => $orderId]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Cari transaksi
        $transaction = Transaction::where('code', $orderId)->first();
        if (!$transaction) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        // Ambil status dari Midtrans
        $status = MidtransTransaction::status($orderId);
        $transactionStatus = $status->transaction_status ?? '';
        $paymentType = $status->payment_type ?? null;

        // Update metode pembayaran
        $transaction->payment_method = $paymentType;

        if (in_array($transactionStatus, ['settlement', 'capture'])) {
            // Hanya kirim jika belum dikirim sebelumnya
            $detail = $transaction->details()->first();
            if ($detail && $detail->delivery_status !== 'DELIVERED') {
                Log::info('📦 Kirim ke VIP Reseller untuk transaksi: ' . $orderId);
                app(\App\Http\Controllers\CheckoutController::class)->sendToVipReseller($transaction, $detail);
            }

            $transaction->status = 'SUCCESS';
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $transaction->status = 'FAILED';
        }

        $transaction->save();

        return response()->json(['message' => 'Callback handled'], 200);
    }
}
