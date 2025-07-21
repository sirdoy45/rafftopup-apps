<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->roles === 'ADMIN') {
            return redirect()->route('admin-dashboard');
        }

        // Khusus untuk user
        $transactions = Transaction::with(['details.product.galleries'])
            ->where('users_id', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $expenditure = $transactions->where('status', 'SUCCESS')->sum('total_price');

        return view('pages.dashboard', [
            'transaction_count' => $transactions->count(),
            'transactions' => $transactions,
            'expenditure' => $expenditure,
            'customer' => 0
        ]);
    }

}