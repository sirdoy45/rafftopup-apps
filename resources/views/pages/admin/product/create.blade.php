@extends('layouts.admin')

@section('title')
  Products
@endsection

@section('content')
<div class="section-content section-dashboard-home" data-aos="fade-up">
  <div class="container-fluid">
    <div class="dashboard-heading">
        <h2 class="dashboard-title">Products</h2>
        <p class="dashboard-subtitle">Create New Product</p>
    </div>
    <div class="dashboard-content">
      <div class="row">
        <div class="col-12">
          @if ($errors->any())
              <div class="alert alert-danger">
                  <ul>
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
          @endif
          <form action="{{ route('product.store') }}" method="POST">
            @csrf
            <div class="card">
              <div class="card-body">

                <div class="form-group">
                  <label>Product Name</label>
                  <input type="text" class="form-control" name="name" required>
                </div>

                <div class="form-group">
                  <label>Provider</label>
                  <input type="text" name="provider" class="form-control" value="vip-reseller" readonly>
                </div>

                <div class="form-group">
                  <label>Product Category</label>
                  <select name="categories_id" class="form-control" required>
                    @foreach ($categories as $category)
                      <option value="{{ $category->id }}">
                        {{ $category->name }}
                      </option>
                    @endforeach
                  </select>
                </div>

                <div class="form-group">
                  <label>Product Code</label>
                  <input type="text" class="form-control" name="kode_produk" required>
                </div>

                <div class="form-group">
                  <label>Vendor Price</label>
                  <input type="number" class="form-control" name="vendor_price" required>
                </div>

                <div class="form-group">
                  <label>Price</label>
                  <input type="number" class="form-control" name="price" required>
                </div>

                <div class="form-group">
                  <label>Status</label>
                  <select name="status" class="form-control" required>
                    <option value="aktif">Active</option>
                    <option value="nonaktif">Non-active</option>
                  </select>
                </div>

                <div class="form-group">
                  <label>Input Type</label>
                  <select name="input_type" class="form-control" required>
                    <option value="id_game">ID Game + Server</option>
                    <option value="user_id">User ID</option>
                    <option value="no_hp">No. HP</option>
                  </select>
                </div>

                <div class="form-group">
                  <label>Description</label>
                  <textarea name="description" id="editor"></textarea>
                </div>

                <div class="text-right">
                  <button type="submit" class="btn btn-success px-5">Save</button>
                </div>

              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('addon-script')
<script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<script>
  CKEDITOR.replace('editor');
</script>
@endpush
