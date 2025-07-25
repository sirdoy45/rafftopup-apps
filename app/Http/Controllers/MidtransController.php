<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\CheckoutController;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        // Konfigurasi Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('midtrans.isProduction');

        // Notifikasi dari Midtrans
        $notif = new \Midtrans\Notification();

        // Ambil data notifikasi
        $transactionStatus = $notif->transaction_status;
        $paymentType = $notif->payment_type;
        $fraudStatus = $notif->fraud_status;
        $orderId = $notif->order_id;

        // Ambil transaksi dengan relasi
        $transaction = Transaction::with('details.product')->where('code', $orderId)->first();

        if (!$transaction) {
            Log::error('❌ Transaksi tidak ditemukan untuk kode: ' . $orderId);
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

        // Jalankan pengiriman ke VIP hanya jika sukses
        if ($transaction->status === 'SUCCESS') {
            $detail = $transaction->details->first(); // Sudah eager loaded

            if (!$detail) {
                Log::error('❌ Tidak ada detail transaksi ditemukan.', [
                    'order_id' => $transaction->code
                ]);
                return response()->json(['message' => 'Transaction detail not found'], 200);
            }

            if ($detail->delivery_status === 'DELIVERED') {
                Log::info('✅ Order sudah dikirim sebelumnya ke VIP. Tidak dikirim ulang.', [
                    'order_id' => $transaction->code
                ]);
                return response()->json(['message' => 'Already delivered'], 200);
            }

            // CEK LINGKUNGAN SEBELUM KIRIM
            if (app()->environment() !== 'production') {
                Log::info('🔒 Callback berhasil, tapi tidak dikirim ke VIP karena bukan di production.', [
                    'order_id' => $transaction->code
                ]);
                return response()->json(['message' => 'Callback handled in non-production, VIP not called'], 200);
            }

            // Kirim ke VIP Reseller
            $checkout = new CheckoutController();
            $result = $checkout->sendToVipReseller($transaction, $detail);

            if ($result) {
                Log::info('✅ Order berhasil dikirim ke VIP Reseller via callback', [
                    'order_id' => $transaction->code
                ]);
            } else {
                Log::warning('⚠️ Gagal mengirim ke VIP Reseller via callback', [
                    'order_id' => $transaction->code
                ]);
            }
        }

        Log::info('📥 Midtrans callback handled', [
            'order_id' => $orderId,
            'status' => $transactionStatus,
            'payment' => $paymentType
        ]);

        return response()->json(['message' => 'Callback handled'], 200);
    }
}
