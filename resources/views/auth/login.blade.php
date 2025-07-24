@extends('layouts.auth')

@section('content')

<div class="page-content page-auth">
  <div class="section-store-auth" data-aos="fade-up">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8 col-sm-10 text-center mb-4">
          <img src="{{ asset('images/RAFFSTORE-Icon.png') }}" alt="Raff Store" class="img-fluid"
            style="max-width: 100%; height: auto; padding: 20px;">
        </div>
        <div class="col-lg-5 col-md-8 col-sm-10">
          <h2 class="text-center mb-4">
            Belanja kebutuhan digital, <br />
            menjadi lebih mudah
          </h2>
          <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
              <label for="email">Email Address</label>
              <input id="email" type="email" 
                class="form-control @error('email') is-invalid @enderror"
                name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
              @error('email')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>

            <div class="form-group mt-3">
              <label for="password">Password</label>
              <input id="password" type="password" 
                class="form-control @error('password') is-invalid @enderror" 
                name="password" required autocomplete="current-password">
              @error('password')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>

            <button type="submit" class="btn btn-primary btn-block mt-4">
              Login
            </button>

            <a class="btn btn-signup btn-block mt-2 text-center" href="{{ route('register') }}">
              Sign Up Now
            </a>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
