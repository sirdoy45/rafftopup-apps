@extends('layouts.auth')

@section('content')

<div class="page-content page-auth">
  <div class="section-store-auth" style="min-height: 100vh;">
    <div class="container h-100">

      <!-- DESKTOP VIEW (lg ke atas) -->
      <div class="d-none d-lg-flex row h-100 align-items-center justify-content-center">
        <div class="col-lg-10">
          <div class="row align-items-center">
            <div class="col-lg-6 text-center">
              <img src="{{ asset('images/RAFFSTORE-Icon.png') }}" alt="Raff Store"
                class="img-fluid"
                style="max-height: 300px;">
            </div>
            <div class="col-lg-6">
              <h2 class="mb-4">
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
                  @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <div class="form-group mt-3">
                  <label for="password">Password</label>
                  <input id="password" type="password"
                    class="form-control @error('password') is-invalid @enderror"
                    name="password" required autocomplete="current-password">
                  @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <button type="submit" class="btn btn-primary btn-block mt-4">
                  Login
                </button>
                <a class="btn btn-light btn-block mt-2" href="{{ route('register') }}">
                  Sign Up Now
                </a>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- MOBILE VIEW (lg ke bawah) -->
      <div class="d-block d-lg-none pt-5">
        <div class="text-center mb-4">
          <img src="{{ asset('images/RAFFSTORE-Icon.png') }}" alt="Raff Store"
            class="img-fluid"
            style="max-width: 220px; width: 80%;">
        </div>
        <h4 class="text-center mb-3">
          Belanja kebutuhan digital,<br />jadi lebih mudah
        </h4>
        <form method="POST" action="{{ route('login') }}" class="px-3">
          @csrf
          <div class="form-group">
            <label for="email_mobile">Email Address</label>
            <input id="email_mobile" type="email"
              class="form-control @error('email') is-invalid @enderror"
              name="email" value="{{ old('email') }}" required>
            @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
          </div>

          <div class="form-group mt-3">
            <label for="password_mobile">Password</label>
            <input id="password_mobile" type="password"
              class="form-control @error('password') is-invalid @enderror"
              name="password" required>
            @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
          </div>

          <button type="submit" class="btn btn-primary btn-block mt-4 w-100">
            Login
          </button>
          <a class="btn btn-light btn-block mt-2 w-100" href="{{ route('register') }}">
            Sign Up Now
          </a>
        </form>
      </div>

    </div>
  </div>
</div>

@endsection
