<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Auth;

class DashboardTransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Menampilkan semua transaksi pembelian pelanggan
    public function index()
    {
        $buyTransactions = TransactionDetail::with(['transaction.user', 'product.galleries'])
            ->whereHas('transaction', function ($transaction) {
                $transaction->where('users_id', Auth::id());
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.dashboard-transactions', [
            'buyTransactions' => $buyTransactions
        ]);
    }

    // Menampilkan detail transaksi pelanggan
    public function detailsBuy($id)
    {
        $transaction = TransactionDetail::with(['transaction.user', 'product.galleries'])
            ->whereHas('transaction', function($query) {
                $query->where('users_id', Auth::id());
            })
            ->findOrFail($id);

        return view('pages.dashboard-transactions-details-buy', [
            'transaction' => $transaction
        ]);
    }

    // Update status transaksi (opsional)
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'delivery_status' => 'required|in:PENDING,DELIVERY,SUCCESS'
        ]);

        $item = TransactionDetail::whereHas('transaction', function($query) {
                $query->where('users_id', Auth::id());
            })
            ->findOrFail($id);

        $item->update($validated);

        return redirect()->route('dashboard-transaction-details-buy', $id)
               ->with('success', 'Transaction status updated successfully');
    }
}