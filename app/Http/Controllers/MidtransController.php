<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Events\NewTransactionEvent;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        // Konfigurasi Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('midtrans.isProduction');

        // Ambil notifikasi dari Midtrans
        $notif = new \Midtrans\Notification();

        // Ambil data dari notifikasi
        $transactionStatus = $notif->transaction_status;
        $paymentType = $notif->payment_type;
        $fraudStatus = $notif->fraud_status;
        $orderId = $notif->order_id;

        // Ambil transaksi berdasarkan kode
        $transaction = Transaction::where('code', $orderId)->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Simpan metode pembayaran
        $transaction->payment_method = $paymentType;

        // Update status
        if ($transactionStatus == 'capture') {
            if ($paymentType == 'credit_card' && $fraudStatus == 'challenge') {
                $transaction->status = 'PENDING';
            } else {
                $transaction->status = 'SUCCESS';
            }
        } elseif ($transactionStatus == 'settlement') {
            $transaction->status = 'SUCCESS';
        } elseif ($transactionStatus == 'pending') {
            $transaction->status = 'PENDING';
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $transaction->status = 'CANCELLED';
        }

        $transaction->save();

        if ($transaction->status === 'SUCCESS') {
            $detail = $transaction->details()->first();

            // Validasi ganda: delivery_status dan apakah sudah dikirim (safety)
            if (!$detail || $detail->delivery_status === 'DELIVERED') {
                Log::info('✅ Order sudah dikirim sebelumnya ke VIP. Tidak dikirim ulang.', [
                    'order_id' => $transaction->code
                ]);
                return response()->json(['message' => 'Already delivered'], 200);
            }

            // Kirim ke VIP Reseller
            $checkout = new \App\Http\Controllers\CheckoutController();
            $result = $checkout->sendToVipReseller($transaction, $detail);

            if ($result) {
                Log::info('✅ Order berhasil dikirim ke VIP Reseller via callback', [
                    'order_id' => $transaction->code
                ]);
            }
        }

        // (Opsional) Log aktivitas callback
        Log::info('Midtrans callback handled', [
            'order_id' => $orderId,
            'status' => $transactionStatus,
            'payment' => $paymentType
        ]);

        return response()->json(['message' => 'Callback handled'], 200);
    }
}
