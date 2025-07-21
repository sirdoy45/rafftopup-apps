<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Events\NewTransactionEvent;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Transaction::with(['user'])->latest();

            return datatables()->of($query)
                ->addColumn('invoice', fn($item) => $item->code)
                ->addColumn('status', function ($item) {
                    $color = match ($item->status) {
                        'SUCCESS' => 'success',
                        'PENDING' => 'warning',
                        'CANCELLED' => 'danger',
                        default => 'secondary'
                    };

                    return '<span class="badge badge-' . $color . '">' . ucfirst(strtolower($item->status)) . '</span>';
                })
                ->addColumn('payment_method', fn($item) => $item->payment_method ?? '-')
                ->addColumn('date', fn($item) => $item->created_at->timezone('Asia/Jakarta')->format('d-m-Y H:i'))
                ->editColumn('total_price', fn($item) => 'Rp ' . number_format($item->total_price, 0, ',', '.'))
                ->addColumn('action', function ($item) {
                    return '<a class="btn btn-primary" href="' . route('admin-transaction-detail', $item->id) . '">Lihat</a>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        // PENTING: Tandai semua notifikasi admin sebagai sudah dibaca
        auth()->user()->unreadNotifications->markAsRead();

        $transactions = Transaction::latest()->get();

        return view('pages.admin.transaction.index', compact('transactions'));
    }

    public function show($id)
    {
        $transaction = Transaction::with(['user', 'details.product'])->findOrFail($id);

        if (!$transaction->is_read) {
            $transaction->is_read = true;
            $transaction->save();
        }

        return view('pages.admin.transaction.show', compact('transaction'));
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        $oldStatus = $transaction->status;
        $transaction->update($request->only('status'));

        return redirect()->back()->with('success', 'Status updated!');
    }

    public function notification()
    {
        $count = Transaction::where('status', 'PENDING')->count();

        return response()->json(['count' => $count]);
    }

    public function cetakLaporan(Request $request)
    {
        $filter = $request->filter;
        $status = $request->status;
        $judul = 'Laporan Transaksi';

        $query = Transaction::with('user');

        // Filter status jika dipilih
        if ($status) {
            $query->where('status', $status);
            $judul .= ' - Status: ' . ucfirst(strtolower($status));
        }

        // Filter berdasarkan jenis laporan
        if ($filter === 'harian' && $request->tanggal) {
            $tanggal = Carbon::parse($request->tanggal)->format('Y-m-d');
            $query->whereDate('created_at', $tanggal);
            $judul .= ' - Tanggal: ' . Carbon::parse($tanggal)->format('d M Y');
        } elseif ($filter === 'bulanan' && $request->bulan) {
            [$tahun, $bulan] = explode('-', $request->bulan);
            $query->whereYear('created_at', $tahun)->whereMonth('created_at', $bulan);
            $judul .= ' - Bulan: ' . $request->bulan;
        } elseif ($filter === 'tahunan' && $request->tahun) {
            $query->whereYear('created_at', $request->tahun);
            $judul .= ' - Tahun: ' . $request->tahun;
        }

        $transaksi = $query->get();

        $pdf = Pdf::loadView('pages.admin.transaction.laporan-pdf', [
            'transaksi' => $transaksi,
            'judul' => $judul
        ]);

        return $pdf->download('laporan_transaksi.pdf');
    }

}

