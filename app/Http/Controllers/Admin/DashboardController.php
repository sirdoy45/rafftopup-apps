<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {   
        // Hitung hanya user dengan role "USER"
        $customer = User::where('roles', 'USER')->count();

        // Hitung revenue dan transaksi hanya yang berstatus "SUCCESS"
        $revenue = Transaction::where('status', 'SUCCESS')->sum('total_price');
        $transaction = Transaction::where('status', 'SUCCESS')->count();
        
        return view('pages.admin.dashboard', [
            'customer' => $customer,
            'revenue' => $revenue,
            'transaction' => $transaction
        ]);
    }

}
