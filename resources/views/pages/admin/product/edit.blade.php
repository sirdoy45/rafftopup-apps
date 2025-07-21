@extends('layouts.admin')

@section('title')
  Edit Produk
@endsection

@section('content')
<div class="section-content section-dashboard-home" data-aos="fade-up">
  <div class="container-fluid">
    <div class="dashboard-heading">
      <h2 class="dashboard-title">Edit Produk</h2>
      <p class="dashboard-subtitle">Change product information</p>
    </div>
    <div class="dashboard-content">
      <div class="row">
        <div class="col-md-12">
          <form action="{{ route('product.update', $item->id) }}" method="POST">
            @method('PUT')
            @csrf
            <div class="card">
              <div class="card-body">

                <div class="form-group">
                  <label>Product Name</label>
                  <input type="text" class="form-control" name="name" value="{{ old('name', $item->name) }}" required>
                </div>

                <div class="form-group">
                  <label>Provider</label>
                  <input type="text" class="form-control" name="provider" value="{{ old('provider', $item->provider) }}" readonly>
                </div>

                <div class="form-group">
                  <label>Product Category</label>
                  <select name="categories_id" class="form-control" required>
                    @foreach ($categories as $category)
                      <option value="{{ $category->id }}" {{ $category->id == $item->categories_id ? 'selected' : '' }}>
                        {{ $category->name }}
                      </option>
                    @endforeach
                  </select>
                </div>

                <div class="form-group">
                  <label>Product Code</label>
                  <input type="text" class="form-control" name="kode_produk" value="{{ old('kode_produk', $item->kode_produk) }}" required>
                </div>

                <div class="form-group">
                  <label>Price</label>
                  <input type="number" class="form-control" name="price" value="{{ old('price', $item->price) }}" required>
                </div>

                <div class="form-group">
                  <label>Status</label>
                  <select name="status" class="form-control" required>
                    <option value="aktif" {{ $item->status == 'aktif' ? 'selected' : '' }}>Active</option>
                    <option value="nonaktif" {{ $item->status == 'nonaktif' ? 'selected' : '' }}>Non-active</option>
                  </select>
                </div>

                <div class="form-group">
                  <label>Input Type</label>
                  <select name="input_type" class="form-control" required>
                    <option value="user_id" {{ $item->input_type == 'user_id' ? 'selected' : '' }}>User ID</option>
                    <option value="id_server" {{ $item->input_type == 'id_server' ? 'selected' : '' }}>ID Server</option>
                    <option value="no_hp" {{ $item->input_type == 'no_hp' ? 'selected' : '' }}>No. HP</option>
                  </select>
                </div>

                <div class="form-group">
                  <label>Deskripsi</label>
                  <textarea name="description" id="editor">{{ old('description', $item->description) }}</textarea>
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
