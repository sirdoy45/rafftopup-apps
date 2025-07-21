<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <title>@yield('title')</title>

  @stack('prepend-style')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
  <link href="/style/main.css" rel="stylesheet" />
  <link href="/style/trix.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.datatables.net/v/bs4/dt-1.13.1/datatables.min.css" />
  @stack('addon-style')
</head>
<body>
  <div class="page-dashboard">
    <div class="d-flex" id="wrapper" data-aos="fade-right">
      <!-- Sidebar -->
      <div class="border-right" id="sidebar-wrapper">
        <div class="sidebar-heading text-center">
          <img src="{{ asset('public/images/RAFFSTORE-regis.png') }}" alt="Raff Store"
            style="width: 250px; margin: -40px -21px -30px; padding: 20px;" />
        </div>
        <div class="list-group list-group-flush">
          <a href="{{ route('admin-dashboard') }}"
            class="list-group-item list-group-item-action {{ request()->is('admin') ? 'active' : '' }}">
            Dashboard
          </a>
          <a href="{{ route('category.index') }}"
            class="list-group-item list-group-item-action {{ request()->is('admin/category*') ? 'active' : '' }}">
            Categories
          </a>
          <a href="{{ route('product.index') }}"
            class="list-group-item list-group-item-action {{ request()->is('admin/product') ? 'active' : '' }}">
            Products
          </a>
          <a href="{{ route('product-gallery.index') }}"
            class="list-group-item list-group-item-action {{ request()->is('admin/product-gallery*') ? 'active' : '' }}">
            Galleries
          </a>
          <a href="{{ route('admin-transaction') }}"
            class="list-group-item list-group-item-action {{ request()->is('admin/transaction') ? 'active' : '' }}">
            Transactions
          </a>
          <a href="{{ route('user.index') }}"
            class="list-group-item list-group-item-action {{ request()->is('admin/user*') ? 'active' : '' }}">
            Users
          </a>
          <a href="/" class="list-group-item list-group-item-action">Sign Out</a>
        </div>
      </div>

      <!-- Page Content -->
      <div id="page-content-wrapper">
        <nav class="navbar navbar-store navbar-expand-lg navbar-light fixed-top" data-aos="fade-down">
          <button class="btn btn-secondary d-md-none mr-auto mr-2" id="menu-toggle">&laquo; Menu</button>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto d-none d-lg-flex">

              {{-- Notifikasi AJAX --}}
              <li class="nav-item dropdown" id="notif-section">
                {{-- Notifikasi akan dimuat otomatis lewat AJAX --}}
              </li>

              {{-- Profil Admin --}}
              <li class="nav-item dropdown">
                <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                  aria-haspopup="true" aria-expanded="false">
                  <img src="/images/raffstore-profile.jpg" alt="Profile" class="rounded-circle mr-2 profile-picture" />
                  Admin
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <a class="dropdown-item" href="/">Logout</a>
                </div>
              </li>
            </ul>

            <!-- Mobile Menu -->
            <ul class="navbar-nav d-block d-lg-none mt-3">
              <li class="nav-item"><a class="nav-link" href="#">Hi, Admin</a></li>
              <li class="nav-item"><a class="nav-link d-inline-block" href="#">Cart</a></li>
            </ul>
          </div>
        </nav>

        <!-- Main Content -->
        @yield('content')
      </div>
    </div>
  </div>

  <!-- Scripts -->
  @stack('prepend-script')
  <script src="/vendor/jquery/jquery.min.js"></script>
  <script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/v/bs4/dt-1.13.1/datatables.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script src="/script/trix.js"></script>
  <script>
    AOS.init();
    $("#menu-toggle").click(function (e) {
      e.preventDefault();
      $("#wrapper").toggleClass("toggled");
    });

    // Notifikasi AJAX
    function loadNotifications() {
      $.ajax({
        url: '{{ url("/admin/notifikasi/check") }}',
        method: 'GET',
        success: function (response) {
          $('#notif-section').html(response.html);
        }
      });
    }

    // Jalankan saat halaman dibuka
    loadNotifications();

    // Refresh otomatis setiap 10 detik
    setInterval(loadNotifications, 10000);
  </script>
  @stack('addon-script')
</body>
</html>
