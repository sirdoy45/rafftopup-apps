@extends('layouts.auth')

@section('content')

<div class="page-content page-auth">
  <div class="section-store-auth py-5" data-aos="fade-up">
    <div class="container">
      <div class="d-flex flex-column flex-lg-row align-items-center justify-content-center min-vh-100">

        <!-- LOGO -->
        <div class="text-center mb-4 mb-lg-0 me-lg-5">
          <img src="{{ asset('images/RAFFSTORE-Icon.png') }}" alt="Raff Store"
            class="img-fluid"
            style="max-width: 300px; height: auto;">
        </div>

        <!-- FORM LOGIN -->
        <div style="max-width: 400px; width: 100%;">
          <h2 class="text-center mb-4">
            Belanja kebutuhan digital,<br />
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

            <button type="submit" class="btn btn-primary btn-block mt-4 w-100">
              Login
            </button>

            <a class="btn btn-light btn-block mt-2 w-100 text-center" href="{{ route('register') }}">
              Sign Up Now
            </a>
          </form>
        </div>

      </div>
    </div>
  </div>
</div>

@endsection
