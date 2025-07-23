@extends('layouts.app')

@section('title')
    Store Category Page
@endsection

@section('content')
    <style>
        .starrating-profile > label:before {
            content: "\f005";
            margin: 2px;
            font-size: 15px;
            font-family: FontAwesome;
            display: inline-block;
        }

        .starrating-profile > label {
            color: #007BFF;
        }

        .starOnProfile {
            color: #007BFF !important;
        }

        .starrating-profile {
            pointer-events: none;
        }
    </style>

    <div class="page-content page-categories">
        <div class="container">
            <div class="row">
                {{-- Sidebar Kategori --}}
                <div class="col-md-4">
                    <section class="store-trend-categories">
                        <div class="container">
                            <div class="row">
                                <div class="col-12" data-aos="fade-up">
                                    {{-- Menampilkan nama tipe kategori --}}
                                    <h5>{{ ucfirst($typeName ?? 'Category') }}</h5>
                                </div>
                            </div>

                            {{-- Kategori lainnya tidak ditampilkan --}}
                        </div>
                    </section>
                </div>

                {{-- Produk --}}
                <div class="col-lg">
                    <section class="store-new-products">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 mt-1" data-aos="fade-up">
                                    <form action="/categories">
                                        <h5>{{ $title }}</h5>

                                        @if (request('category'))
                                            <input type="hidden" name="category" value="{{ request('category') }}">
                                        @endif

                                        <div class="input-group mb-3 shadow-sm">
                                            <input type="text" class="form-control" placeholder="Search..."
                                                   name="search" value="{{ request('search') }}">
                                            <button class="btn btn-outline-primary" type="submit">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="row">
                                @php $incrementProduct = 0 @endphp
                                @forelse ($products as $product)
                                <div class="col-6 col-md-4 col-lg-3" data-aos="fade-up" data-aos-delay="{{ $incrementProduct += 100 }}">
                                    <a class="component-products d-block" href="{{ route('buy.form', $product->slug) }}">
                                        <div class="products-thumbnail shadow-sm">
                                            <div class="products-image" style="height: 200px; overflow: hidden;">
                                                @if ($product->galleries->count())
                                                    <img src="{{ asset('assets/product/' . $product->galleries->first()->photos) }}" 
                                                        style="width: 100%; height: 100%; object-fit: cover;" 
                                                        alt="{{ $product->name }}"
                                                        onerror="console.log('Image failed to load:', this.src)">
                                                @else
                                                    <img src="{{ asset('images/bgemptyproduct.png') }}" 
                                                        style="width: 100%; height: 100%; object-fit: cover;" 
                                                        alt="No Image">
                                                @endif
                                            </div>
                                        </div>
                                        <div class="products-text">{{ $product->name }}</div>
                                        <div class="products-price">@money($product->price)</div>
                                    </a>
                                </div>
                                @empty
                                <div class="col-12 text-center py-5" data-aos="fade-up" data-aos-delay="100">
                                    No Products Found
                                </div>
                                @endforelse
                            </div>
                            <div class="d-flex justify-content-end">
                                {{ $products->links() }}
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection
