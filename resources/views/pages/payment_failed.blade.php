@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="text-center">
        <h1 class="text-danger">❌ Pembayaran Gagal</h1>
        <p>Maaf, terjadi kesalahan saat memproses pembayaran Anda.</p>
        <a href="{{ url('/') }}" class="btn btn-warning mt-3">Coba Lagi</a>
    </div>
</div>
@endsection
