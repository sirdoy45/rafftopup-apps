  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-light navbar-store fixed-top navbar-fixed-top shadow border-bottom-primary-navbar"
      data-aos="fade-down">
      <div class="container mb-1">
          <a class="navbar-brand" href="{{ route('home') }}">
            <img  src="{{ asset('images/RAFFSTORE-regis.png') }}" alt="Raff Store" style="width: 250px; margin-top: -40px; margin-bottom: -30px; margin-left: -21px; padding: 20px;">
          </a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive"
              aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarResponsive">
              <ul class="navbar-nav ml-auto">
                  <li class="nav-item">
                      <a class="nav-link" href="{{ route('home') }}">Home </a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="{{ route('category.type') }}">Products</a>
                  </li>

                  @guest

                      <li class="nav-item">
                          <a class="nav-link" href="{{ route('register') }}">Sign Up</a>
                      </li>
                      <li class="nav-item">
                          <a class="btn btn-primary nav-link px-4 text-white" href="{{ route('login') }}">Sign In</a>
                      </li>

                  @endguest

              </ul>

              @auth
                  <!-- Desktop Menu -->
                  <ul class="navbar-nav d-none d-lg-flex">
                      <li class="nav-item dropdown">
                          <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                              aria-haspopup="true" aria-expanded="false">
                              @if (Auth::user()->img_profile)
                                  <img id="profilePic" class="rounded-circle mr-2 profile-picture"
                                      src="{{ asset('storage/' . Auth::user()->img_profile) }}" style="object-fit:cover;">
                              @else
                                  <img src="{{ asset('images/raffstore-profile.jpg') }}" alt=""
                                      class="rounded-circle mr-2 profile-picture" />
                              @endif
                              Hi, {{ Auth::user()->name }}
                          </a>
                          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                              <a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a>
                              <a class="dropdown-item" href="{{ route('dashboard-settings-account') }}">Settings</a>
                              <div class="dropdown-divider"></div>
                              <a class="dropdown-item" href="{{ route('logout') }}"
                                  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                  @csrf
                              </form>
                          </div>
                      </li>
                      {{-- <li class="nav-item">
                          <a class="nav-link d-inline-block mt-2" href="{{ route('cart') }}">
                              @php
                                  $carts = \App\Models\Cart::where('users_id', Auth::user()->id)->count();
                              @endphp

                              @if ($carts > 0)
                                  <img src="/images/icon-cart-filled.svg" alt="" />
                                  <div class="cart-badge">{{ $carts }}</div>
                              @else
                                  <img src="/images/icon-cart-empty.svg" alt="" />
                              @endif
                          </a>
                      </li> --}}
                  </ul>

                  <!-- Mobile Menu -->
                  <ul class="navbar-nav d-block d-lg-none">
                      <li class="nav-item">
                          <a class="nav-link" href="#">
                              Hi, {{ Auth::user()->name }}
                          </a>
                      </li>
                  </ul>
              @endauth
          </div>
      </div>
  </nav>
