@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mt-5 mb-4">Purchase Form - {{ $product->name }}</h2>

    {{-- Info produk --}}
    <p><strong>Category:</strong> {{ $product->category->name }}</p>
    <p><strong>Price:</strong> Rp{{ number_format($product->price) }}</p>

    {{-- Alert sukses / error --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Tampilkan error validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger mt-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Pembelian --}}
    <form action="{{ route('buy.process', $product->slug) }}" method="POST">
        @csrf

        {{-- Nomor HP pelanggan --}}
        <div class="form-group">
            <label for="customer_phone">Your WhatsApp number</label>
            <input type="text" name="customer_phone" id="customer_phone" class="form-control" required
                   placeholder="Enter your active WhatsApp number"
                   value="{{ old('customer_phone') }}">
        </div>

        {{-- Input berdasarkan tipe produk --}}
        @if($product->input_type === 'id_game')
            {{-- ML: ID dan Server --}}
            <div class="form-group mt-3">
                <label for="game_id">ID Game</label>
                <input type="text" name="game_id" id="game_id" class="form-control" required
                       placeholder="Enter Game ID"
                       value="{{ old('game_id') }}">
            </div>
            <div class="form-group">
                <label for="server">Server</label>
                <input type="text" name="server" id="server" class="form-control" required
                       placeholder="Enter Server"
                       value="{{ old('server') }}">
            </div>

        @elseif($product->input_type === 'user_id')
            {{-- Game lain: User ID saja --}}
            <div class="form-group mt-3">
                <label for="user_id">User ID</label>
                <input type="text" name="user_id" id="user_id" class="form-control" required
                       placeholder="Enter User ID"
                       value="{{ old('user_id') }}">
            </div>

        @elseif($product->input_type === 'no_hp')
            {{-- Pulsa / digital: Nomor HP tujuan --}}
            <div class="form-group mt-3">
                <label for="phone_number">Destination cellphone number</label>
                <input type="text" name="phone_number" id="phone_number" class="form-control" required
                       placeholder="Enter the destination number"
                       value="{{ old('phone_number') }}">
            </div>
        @endif

        {{-- Tombol submit --}}
        @guest
            <a href="{{ route('login') }}" class="btn btn-warning mt-4">
                Login terlebih dahulu untuk membeli
            </a>
        @else
            <button type="submit" class="btn btn-success mt-4">
                Buy Now (Rp{{ number_format($product->price) }})
            </button>
        @endguest

    </form>
</div>
@endsection
