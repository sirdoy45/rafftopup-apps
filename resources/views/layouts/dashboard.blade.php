<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>@yield('title')</title>

    @stack('prepend-style')
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet" />
    <link href="/style/main.css" rel="stylesheet" />
    <link href="/style/account-profile.css" rel="stylesheet" />
    <link href="/style/trix.css" rel="stylesheet" />
    @include('includes.style')
    @stack('addon-style')
    
    <!-- Simple inline styles for dropdown fixes -->
    <style>
      .profile-picture {
        width: 40px;
        height: 40px;
        object-fit: cover;
      }
      .dropdown-menu {
        right: 0;
        left: auto;
      }
    </style>
  </head>

  <body>
    <div class="page-dashboard">
      <div class="d-flex" id="wrapper" data-aos="fade-right">
        <!-- Sidebar -->
        <div class="border-right" id="sidebar-wrapper">
          <div class="sidebar-heading text-center">
            <a href="/"><img src="{{ asset('images/RAFFSTORE-regis.png') }}" alt="Raff Store" style="max-width: 250px;"></a>
          </div>
          <div class="list-group list-group-flush">
            <a href="/dashboard" class="list-group-item list-group-item-action {{ request()->is('dashboard') ? 'active' : '' }}">
              Dashboard
            </a>
            <a href="/dashboard/transactions" class="list-group-item list-group-item-action {{ request()->is('dashboard/transactions*') ? 'active' : '' }}">
              Transactions
            </a>
            <a href="/dashboard/account" class="list-group-item list-group-item-action {{ request()->is('dashboard/account*') ? 'active' : '' }}">
              My Account
            </a>
          </div>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
          <nav class="navbar navbar-store navbar-expand-lg navbar-light fixed-top" data-aos="fade-down">
            <button class="btn btn-secondary d-md-none mr-auto mr-2" id="menu-toggle">&laquo; Menu</button>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
              aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <!-- Desktop Menu -->
              <ul class="navbar-nav ml-auto d-none d-lg-flex">
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" 
                     data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                     style="display: flex; align-items: center;">
                    @if (Auth::user()->img_profile)
                      <img src="{{ asset('storage/' . Auth::user()->img_profile) }}" 
                           class="rounded-circle mr-2 profile-picture">
                    @else
                      <img src="/images/raffstore-profile.jpg" 
                           alt="Profile" class="rounded-circle mr-2 profile-picture">
                    @endif
                    {{ Auth::user()->name }}
                  </a>
                  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="/">Back to Store</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                      onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                      @csrf
                    </form>
                  </div>
                </li>
              </ul>

              <!-- Mobile Menu -->
              <ul class="navbar-nav d-block d-lg-none mt-3">
                <li class="nav-item">
                  <a class="nav-link" href="/">Back to Store</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">Logout</a>
                  <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                  </form>
                </li>
              </ul>
            </div>
          </nav>

          <!-- Content -->
          @yield('content')
        </div>
        <!-- /#page-content-wrapper -->
      </div>
    </div>

    <!-- JavaScript -->
    @stack('prepend-script')
    <!-- Load jQuery first -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Then Popper.js, then Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="/script/trix.js"></script>
    <script>
      // Initialize AOS animations
      AOS.init();
      
      // Initialize dropdowns
      $(function () {
        $('.dropdown-toggle').dropdown();
      });
      
      // Menu toggle
      $("#menu-toggle").click(function (e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
      });
    </script>
    @stack('addon-script')
  </body>
</html>