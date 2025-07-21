@extends('layouts.app')

@section('title')
    Select Product Type
@endsection

@section('content')
<div class="page-content page-home">
    <section class="product-type-selection py-5">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-12 text-center" data-aos="fade-up">
                    <h2 class="mb-3">Choose Your Product Type</h2>
                    <p class="text-muted">Select from our available product categories</p>
                </div>
            </div>
            <div class="row justify-content-center">
                @foreach ($types as $type)
                    <div class="col-6 col-md-4 col-lg-3 mb-4" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                        <div class="card type-card h-100 border-0 shadow-sm rounded-lg overflow-hidden">
                            <a href="{{ route('category.byType', $type->slug) }}" class="text-decoration-none">
                                <div class="type-image-container bg-light d-flex align-items-center justify-content-center" style="height: 120px;">
                                    @if(strtolower($type->name) == 'game')
                                        <i class="fas fa-gamepad fa-3x text-primary"></i>
                                    @elseif(strtolower($type->name) == 'pulsa')
                                        <i class="fas fa-mobile-alt fa-3x text-primary"></i>
                                    @else
                                        <i class="fas fa-shopping-bag fa-3x text-primary"></i>
                                    @endif
                                </div>
                                <div class="card-body text-center p-3">
                                    <h5 class="card-title mb-1 text-dark">{{ $type->name }}</h5>
                                    <span class="text-primary small">Browse products →</span>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</div>
@endsection

@push('style')
<style>
    .type-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid rgba(0,0,0,0.1);
    }
    
    .type-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        border-color: rgba(0,0,0,0.2);
    }
    
    .type-image-container {
        transition: all 0.3s ease;
    }
    
    .type-card:hover .type-image-container {
        background-color: #f8f9fa !important;
    }
</style>
@endpush