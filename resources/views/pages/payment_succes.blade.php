@extends('layouts.app') {{-- atau sesuaikan dengan layout kamu --}}

@section('content')
<div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="text-center">
        <h1 class="text-success">✅ Pembayaran Berhasil!</h1>
        <p>Terima kasih, pembayaran Anda telah berhasil diproses.</p>
        <a href="{{ url('/') }}" class="btn btn-primary mt-3">Kembali ke Toko</a>
    </div>
</div>
@endsection