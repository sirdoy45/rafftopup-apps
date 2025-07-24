@extends('layouts.auth')

@section('content')

<div class="page-content page-auth">
  <div class="section-store-auth" style="min-height: 100vh;">
    <div class="container h-100">
      <div class="row h-100 align-items-center justify-content-center">
        <div class="col-lg-10 col-12">
          <div class="row align-items-center">

            <!-- LOGO -->
            <div class="col-lg-6 text-center mb-4 mb-lg-0">
              <img src="{{ asset('images/RAFFSTORE-Icon.png') }}" alt="Raff Store"
                class="img-fluid"
                style="max-width: 100%; height: auto; max-height: 300px;">
            </div>

            <!-- FORM LOGIN -->
            <div class="col-lg-6">
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
  </div>
</div>

@endsection
