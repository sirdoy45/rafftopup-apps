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
        Config::$serverKey = config('midtrans.serverKey');
        Config::$isProduction = config('midtrans.isProduction');

        $payload = $request->all();
        $orderId = $payload['order_id'] ?? null;

        if (!$orderId) {
            return response()->json(['message' => 'Order ID tidak ditemukan'], 400);
        }

        $transaction = Transaction::where('code', $orderId)->first();
        if (!$transaction) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        $status = MidtransTransaction::status($orderId);

        // Kalau sukses, kirim ke VIP
        if (in_array($status->transaction_status ?? '', ['settlement', 'capture'])) {
            $detail = $transaction->details()->first();

            if ($detail && $detail->delivery_status !== 'DELIVERED') {
                app(\App\Http\Controllers\CheckoutController::class)->sendToVipReseller($transaction, $detail);
            }

            $transaction->update(['status' => 'SUCCESS']);
        } elseif (in_array($status->transaction_status ?? '', ['cancel', 'deny', 'expire'])) {
            $transaction->update(['status' => 'FAILED']);
        }

        return response()->json(['message' => 'Callback handled'], 200);
    }
}