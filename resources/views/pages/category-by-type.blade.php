@extends('layouts.app')

@section('title')
    {{ $typeName }} Products
@endsection

@section('content')
<div class="page-content page-home">
    <section class="category-products py-4">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12" data-aos="fade-up">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent p-0 mb-2">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('category.type') }}" class="text-decoration-none">Product Type</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $typeName }}</li>
                        </ol>
                    </nav>
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">{{ $typeName }} Products</h2>
                        <a href="{{ route('category.type') }}" class="btn btn-outline-primary btn-sm d-md-none">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
            </div>
            <div class="row">
                {{-- Sidebar - Desktop --}}
                <div class="col-md-3 mb-4 d-none d-md-block">
                    <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
                        <div class="card-header bg-primary text-white py-3">
                            <h5 class="mb-0">Categories</h5>
                        </div>
                        <div class="list-group list-group-flush">
                            @foreach ($categories as $category)
                                <a href="{{ route('categories-detail', $category->slug) }}"
                                    class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 px-4 {{ request()->is('categories/' . $category->slug) ? 'active bg-primary text-white' : '' }}">
                                    <span>{{ $category->name }}</span>
                                    <span class="badge rounded-pill {{ request()->is('categories/' . $category->slug) ? 'bg-white text-primary' : 'bg-primary' }}">{{ $category->products_count }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Mobile Category Dropdown --}}
                <div class="col-12 mb-3 d-md-none">
                    <select class="form-select" onchange="window.location.href=this.value">
                        <option value="{{ route('category.type') }}">Select Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ route('categories-detail', $category->slug) }}" 
                                {{ request()->is('categories/' . $category->slug) ? 'selected' : '' }}>
                                {{ $category->name }} ({{ $category->products_count }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Products --}}
                <div class="col-md-9 col-12">
                    @if(count($products) > 0)
                        <div class="row">
                            @foreach ($products as $product)
                                <div class="col-6 col-md-4 col-lg-3 mb-4">
                                    <div class="card product-card h-100 border-0 shadow-sm rounded-lg overflow-hidden">
                                        <a href="{{ route('buy.form', $product->slug) }}" class="text-decoration-none">
                                            <div class="product-image-container bg-light d-flex align-items-center justify-content-center" style="height: 140px;">
                                                @if($product->galleries->count() > 0 && $product->galleries->first()->photos)
                                                    <img src="{{ url('public/assets/product/' . $product->galleries->first()->photos) }}" 
                                                         alt="{{ $product->name }}" 
                                                         class="img-fluid" 
                                                         style="max-height: 100%; max-width: 100%; object-fit: contain;"
                                                         onerror="this.onerror=null; this.style.display='none'; this.parentElement.innerHTML='<i class=\'fas fa-shopping-bag fa-3x text-muted\'></i>';">
                                                @else
                                                    <i class="fas fa-shopping-bag fa-3x text-muted"></i>
                                                @endif
                                            </div>
                                            <div class="card-body p-3">
                                                <h6 class="card-title text-dark mb-1">{{ $product->name }}</h6>
                                                <div class="d-flex justify-content-between align-items-center mt-3">
                                                    <span class="text-primary fw-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                                    <button class="btn btn-sm btn-primary rounded-pill px-3">Buy Now</button>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="card border-0 shadow-sm rounded-lg">
                            <div class="card-body text-center py-5">
                                <i class="fas fa-box-open fa-4x text-muted mb-4"></i>
                                <h5 class="text-muted">No products available in this category</h5>
                                <p class="text-muted">Please check back later or browse other categories</p>
                                <a href="{{ route('category.type') }}" class="btn btn-primary mt-3">Back to Product Types</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>
@endsection