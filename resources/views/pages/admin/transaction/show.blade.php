@extends('layouts.admin')

@section('title')
    Detail Transaksi #{{ $transaction->code }}
@endsection

@section('content')
<div class="container-xl px-md-5 py-4">
    <h4 class="mb-4">
        <i class="fas fa-receipt"></i> Detail Transaksi <strong>#{{ $transaction->code }}</strong>
    </h4>

    {{-- Informasi Pengguna --}}
    <div class="card mb-4 shadow rounded-3">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-user"></i> Informasi Pengguna
        </div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Nama:</dt>
                <dd class="col-sm-9">{{ $transaction->user->name }}</dd>

                <dt class="col-sm-3">Email:</dt>
                <dd class="col-sm-9">{{ $transaction->user->email }}</dd>
            </dl>
        </div>
    </div>

    {{-- Detail Transaksi --}}
    <div class="card mb-4 shadow rounded-3">
        <div class="card-header bg-info text-white">
            <i class="fas fa-info-circle"></i> Detail Transaksi
        </div>
        <div class="card-body">
            @php
                $statusColor = match($transaction->status) {
                    'SUCCESS' => 'success',
                    'PENDING' => 'warning',
                    'CANCELLED' => 'danger',
                    default => 'secondary'
                };
            @endphp

            <dl class="row mb-0">
                <dt class="col-sm-3">Status:</dt>
                <dd class="col-sm-9">
                    <span class="badge badge-{{ $statusColor }}">
                        {{ ucfirst(strtolower($transaction->status)) }}
                    </span>
                </dd>

                <dt class="col-sm-3">Metode Pembayaran:</dt>
                <dd class="col-sm-9">{{ $transaction->payment_method ?? '-' }}</dd>

                <dt class="col-sm-3">Tanggal Pembelian:</dt>
                <dd class="col-sm-9">
                    {{ $transaction->created_at->timezone('Asia/Jakarta')->format('d-m-Y H:i') }}
                </dd>

                <dt class="col-sm-3">Total Harga:</dt>
                <dd class="col-sm-9">Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</dd>

                <dt class="col-sm-3">Pajak:</dt>
                <dd class="col-sm-9">Rp{{ number_format($transaction->tax_price, 0, ',', '.') }}</dd>
            </dl>
        </div>
    </div>

    {{-- Produk dalam Transaksi --}}
    <div class="card mb-4 shadow rounded-3">
        <div class="card-header bg-success text-white">
            <i class="fas fa-box"></i> Produk dalam Transaksi
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Nama Produk</th>
                            <th>Kuantitas</th>
                            <th>Harga Satuan</th>
                            <th>Subtotal</th>
                            <th>Status Pengiriman</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaction->details as $detail)
                            @php
                                $deliveryColor = match($detail->delivery_status) {
                                    'SHIPPING' => 'info',
                                    'SUCCESS' => 'success',
                                    'CANCELLED' => 'danger',
                                    default => 'secondary'
                                };
                            @endphp
                            <tr>
                                <td>{{ $detail->product->name ?? 'Produk tidak ditemukan' }}</td>
                                <td>{{ $detail->quantity }}</td>
                                <td>Rp{{ number_format($detail->price, 0, ',', '.') }}</td>
                                <td>Rp{{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge badge-{{ $deliveryColor }}">
                                        {{ ucfirst(strtolower($detail->delivery_status)) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Tombol Kembali --}}
    <a href="{{ route('admin-transaction') }}" class="btn btn-secondary mt-3">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>
@endsection
