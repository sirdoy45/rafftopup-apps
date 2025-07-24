@extends('layouts.dashboard')

@section('title')
    My Dashboard
@endsection

@section('content')
<br>
<br>
    <div class="section-content section-dashboard-home" data-aos="fade-up">
        <div class="container-fluid">
            <div class="dashboard-heading">
                <h2 class="dashboard-title">My Dashboard</h2>
                <p class="dashboard-subtitle">
                    Your Purchase Activity Summary
                </p>
            </div>
            <div class="dashboard-content">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="dashboard-card-title">
                                    Total Expenditure
                                </div>
                                <div class="dashboard-card-subtitle">
                                    Rp {{ number_format($expenditure ?? 0) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="dashboard-card-title">
                                    Transaction Count
                                </div>
                                <div class="dashboard-card-subtitle">
                                    {{ $transaction_count }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12 mt-2">
                        <h5 class="mb-3">Recent Transactions</h5>

                        @forelse ($transactions as $transaction)
                            @php
                                // Ambil produk pertama dari detail transaksi
                                $product = $transaction->details->first()->product ?? null;
                            @endphp
                            <a class="card card-list d-block" href="{{ route('dashboard-transaction-details', $transaction->id) }}">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-1">
                                            @if($product && $product->galleries->first())
                                                <img src="{{ url('public/assets/product/' . $product->galleries->first()->photos) }}" 
                                                     class="w-75" 
                                                     alt="{{ $product->name }}" />
                                            @else
                                                <div class="w-75 bg-light p-2 text-center">No Image</div>
                                            @endif
                                        </div>
                                        <div class="col-md-2">
                                            {{ $product->name ?? 'Diamonds' }}
                                        </div>
                                        <div class="col-md-2 {{ $transaction->status === 'SUCCESS' ? 'text-success' : 
                                                             ($transaction->status === 'PENDING' ? 'text-warning' : 
                                                             'text-danger') }}">
                                            {{ $transaction->status }}
                                        </div>
                                        <div class="col-md-2">
                                            {{ $transaction->code }}
                                        </div>
                                        <div class="col-md-3">
                                            {{ $transaction->created_at->format('d M Y H:i') }}
                                        </div>
                                        <div class="col-md-2 text-end">
                                            Rp {{ number_format($transaction->total_price) }}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="card card-list d-block">
                                <div class="card-body text-center py-5">
                                    <p class="text-muted">No transactions yet</p>
                                    <a href="{{ route('home') }}" class="btn btn-success mt-3">Start Shopping</a>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection