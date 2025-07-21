@extends('layouts.dashboard')

@section('title')
    Transaction Details #{{ $transaction->transaction->code }}
@endsection

@section('content')
<div class="section-content section-dashboard-home" data-aos="fade-up">
    <div class="container-fluid">
        <div class="dashboard-heading">
            <h2 class="dashboard-title">Transaction #{{ $transaction->transaction->code }}</h2>
            <p class="dashboard-subtitle">
                Transaction Details
            </p>
        </div>
        <div class="dashboard-content">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    @if ($transaction->product && $transaction->product->galleries->isNotEmpty())
                                        <img src="{{ Storage::url($transaction->product->galleries->first()->photos) }}"
                                            alt="{{ $transaction->product->name }}" 
                                            class="w-100 mb-3 rounded" />
                                    @else
                                        <img src="/images/default-product.png" 
                                             alt="Default Product" 
                                             class="w-100 mb-3 rounded" />
                                    @endif
                                </div>
                                <div class="col-12 col-md-8">
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <div class="product-title">Product Name</div>
                                            <div class="product-subtitle">
                                                {{ $transaction->product->name ?? 'Product not available' }}
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="product-title">Transaction Date</div>
                                            <div class="product-subtitle">
                                                {{ $transaction->created_at->format('d M Y H:i') }}
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="product-title">Payment Status</div>
                                            <div class="product-subtitle {{ $transaction->transaction->status === 'SUCCESS' ? 'text-success' : 
                                                                         ($transaction->transaction->status === 'PENDING' ? 'text-warning' : 
                                                                         'text-danger') }}">
                                                {{ strtoupper($transaction->transaction->status) }}
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="product-title">Payment Method</div>
                                            <div class="product-subtitle">
                                                {{ $transaction->transaction->payment_method ? strtoupper($transaction->transaction->payment_method) : '-' }}
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="product-title">Total Amount</div>
                                            <div class="product-subtitle">
                                                @money($transaction->transaction->total_price)
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="product-title">Quantity</div>
                                            <div class="product-subtitle">
                                                {{ $transaction->quantity }} item(s)
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection