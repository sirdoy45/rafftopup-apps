@extends('layouts.admin')

@section('title')
    Category
@endsection

@section('content')
<br>
<br>
    <!-- section content-->
          <div
            class="section-content section-dashboard-home"
            data-aos="fade-up"
          >
            <div class="container-fluid">
              <div class="dashboard-heading">
                <h2 class="dashboard-title">Category</h2>
                <p class="dashboard-subtitle">
                  Edit Category
                </p>
              </div>
              <div class="dashboard-content">
                <div class="row">
                    <div class="col-md-12">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('category.update', $item->id) }}
                                " method="POST" enctype="multipart/form-data">
                                @method('PUT')
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>
                                                Category Name
                                            </label>
                                            <input type="text" name="name" class="form-control" value="{{ $item->name }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="type_id">Category Type</label>
                                            <select name="type_id" class="form-control" required>
                                                <option value="">-- Select Type --</option>
                                                @foreach ($types as $type)
                                                    <option value="{{ $type->id }}" {{ $type->id == $item->type_id ? 'selected' : '' }}>
                                                        {{ $type->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>    
                                    </div>                                    
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>
                                                Image
                                            </label>
                                            <input type="file" name="photo" id="" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col text-right">
                                        <button type="submit" class="btn btn-success px-5"> Save</button>
                                    </div>
                                </div>
                            </form>
                            </div>
                        </div> 
                    </div>
                </div>
              </div>
            </div>
          </div>
@endsection
