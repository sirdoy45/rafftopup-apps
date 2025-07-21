@extends('layouts.dashboard')

@section('title')
    My Transactions
@endsection

@section('content')
<div class="section-content section-dashboard-home" data-aos="fade-up">
    <div class="container-fluid">
        <div class="dashboard-heading">
            <h2 class="dashboard-title">My Transactions</h2>
            <p class="dashboard-subtitle">Your purchase history</p>
        </div>
        <div class="dashboard-content">
            <div class="row mt-3">
                <div class="col-12 mt-2">
                    @forelse ($buyTransactions as $transaction)
                        <a class="card card-list d-block" href="{{ route('dashboard-transaction-details', $transaction->id) }}">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        {{ $transaction->transaction->code }}
                                    </div>
                                    <div class="col-md-1">
                                        @if ($transaction->product && $transaction->product->galleries->isNotEmpty())
                                            <img src="{{ Storage::url($transaction->product->galleries->first()->photos) }}" 
                                                 class="w-75 rounded" 
                                                 alt="{{ $transaction->product->name }}">
                                        @else
                                            <img src="/images/default-product.png" 
                                                 class="w-75 rounded" 
                                                 alt="Default Product">
                                        @endif
                                    </div>
                                    <div class="col-md-3">
                                        {{ $transaction->product->name ?? 'Product not available' }}
                                    </div>
                                    <div class="col-md-2 {{ $transaction->transaction->status === 'SUCCESS' ? 'text-success' : 
                                                          ($transaction->transaction->status === 'PENDING' ? 'text-warning' : 
                                                          'text-danger') }}">
                                        {{ strtoupper($transaction->transaction->status) }}
                                    </div>
                                    <div class="col-md-2">
                                        @money($transaction->transaction->total_price)
                                    </div>
                                    <div class="col-md-2">
                                        {{ $transaction->created_at->format('d M Y') }}
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="card card-list d-block">
                            <div class="card-body text-center py-5">
                                <p class="text-muted">You haven't made any transactions yet</p>
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