@extends('layouts.admin')

@section('title')
    Transactions
@endsection

@section('content')
<br>
<br>
<!-- section content -->
<div class="section-content section-dashboard-home" data-aos="fade-up">
  <div class="container-fluid">
    <div class="dashboard-heading">
      <h2 class="dashboard-title">Transactions</h2>
      <p class="dashboard-subtitle">Transaction List</p>
    </div>

    <!-- FORM CETAK -->
    <form action="{{ route('admin-transaction-print') }}" method="GET" target="_blank" class="mb-3">
      <div class="row">
        <div class="col-md-3">
          <label>Jenis Laporan</label>
          <select name="filter" class="form-control" required>
            <option value="">-- Pilih --</option>
            <option value="harian">Per Hari</option>
            <option value="bulanan">Per Bulan</option>
            <option value="tahunan">Per Tahun</option>
          </select>
        </div>
        <div class="col-md-3">
          <label>Tanggal / Bulan / Tahun</label>
          <input type="date" name="tanggal" class="form-control filter-input">
          <input type="month" name="bulan" class="form-control filter-input d-none">
          <input type="number" name="tahun" class="form-control filter-input d-none" placeholder="Contoh: 2025">
        </div>
        <div class="col-md-3">
          <label>Status Transaksi</label>
          <select name="status" class="form-control">
            <option value="">Semua</option>
            <option value="SUCCESS">Sukses</option>
            <option value="PENDING">Pending</option>
            <option value="CANCELLED">Dibatalkan</option>
          </select>
        </div>
        <div class="col-md-3">
          <label>Jenis Produk</label>
          <select name="jenis_produk" class="form-control">
            <option value="">Semua</option>
            <option value="game">Game</option>
            <option value="pulsa">Pulsa</option>
          </select>
        </div>
        <div class="col-md-2 align-self-end">
          <button type="submit" class="btn btn-danger btn-block">
            <i class="fas fa-file-pdf"></i> Cetak Laporan
          </button>
        </div>
      </div>
    </form>

    <div class="dashboard-content">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <div class="table table-responsive">
                <table class="table-hover scroll-horizontal-vertical w-100" id="crudTable">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Transaction Code</th>
                      <th>Customer</th>
                      <th>Method</th>
                      <th>Total</th>
                      <th>Status</th>
                      <th>Date</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div> 
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('addon-script')
<script>
    var datatable = $('#crudTable').DataTable({
        processing: true,
        serverSide: true,
        ordering: true,
        ajax: {
            url: '{!! url()->current() !!}',
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'invoice', name: 'invoice' },
            { data: 'user.name', name: 'user.name' },
            { data: 'payment_method', name: 'payment_method' },
            { data: 'total_price', name: 'total_price' },
            { data: 'status', name: 'status' },
            { data: 'date', name: 'date' },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false,
                width: '15%'
            },
        ]
    });

    // Filter input berdasarkan jenis laporan
    $('select[name="filter"]').on('change', function () {
      $('.filter-input').addClass('d-none');
      if (this.value === 'harian') {
        $('input[name="tanggal"]').removeClass('d-none');
      } else if (this.value === 'bulanan') {
        $('input[name="bulan"]').removeClass('d-none');
      } else if (this.value === 'tahunan') {
        $('input[name="tahun"]').removeClass('d-none');
      }
    });
</script>
@endpush
