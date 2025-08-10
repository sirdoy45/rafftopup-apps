@extends('layouts.admin')

@section('title')
  Product
@endsection

@section('content')
<div class="section-content section-dashboard-home" data-aos="fade-up">
  <div class="container-fluid">
    <div class="dashboard-heading">
      <h2 class="dashboard-title">Products</h2>
      <p class="dashboard-subtitle">Product List</p>
    </div>
    <div class="dashboard-content">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <a href="{{ route('product.create') }}" class="btn btn-primary mb-3">+ Add Product</a>
              <div class="table-responsive">
                <table class="table table-hover w-100" id="crudTable">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Provider</th>
                      <th>Category</th>
                      <th>Vendor Price</th>
                      <th>Price</th>
                      <th>Product Code</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- DataTable dihandle oleh server -->
                  </tbody>
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
      { data: 'name', name: 'name' },
      { data: 'provider', name: 'provider' },
      { data: 'category.name', name: 'category.name' },
      { data: 'vendor_price', name: 'vendor_price' },
      { data: 'price', name: 'price' },
      { data: 'kode_produk', name: 'kode_produk' },
      { 
        data: 'status', 
        name: 'status',
        render: function(data) {
          return data === 'aktif' 
            ? '<span class="badge badge-success">Aktif</span>' 
            : '<span class="badge badge-danger">Nonaktif</span>';
        }
      },
      {
        data: 'action',
        name: 'action',
        orderable: false,
        searchable: false,
        width: '15%'
      },
    ]
  })
</script>
@endpush
