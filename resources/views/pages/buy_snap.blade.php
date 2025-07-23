@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="text-center">
        <h2>Mohon Tunggu, Memproses Pembayaran...</h2>
    </div>
</div>

<script src="{{ config('midtrans.isProduction') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" 
        data-client-key="{{ config('midtrans.clientKey') }}">
</script>
<script type="text/javascript">
  snap.pay('{{ $snapToken }}', {
    onSuccess: function(result){
      window.location.href = '{{ route("payment.success") }}' + '?order_id=' + result.order_id;
    },
    onPending: function(result){
      window.location.href = '{{ route("payment.success") }}' + '?order_id=' + result.order_id;
    },
    onError: function(result){
      window.location.href = '{{ route("payment.failed") }}';
    },
    onClose: function(){
      window.location.href = '{{ route("payment.failed") }}';
    }
  });
</script>
@endsection
