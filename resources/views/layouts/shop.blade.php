<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Etinda - Local Farmers' Market</title>
  <!-- Bootstrap CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome CDN for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* Custom styles that override or complement Bootstrap */
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f9f9f9;
    }

    header {
      background-color: #28a745;
    }

    .hero {
      background: linear-gradient(135deg, rgba(40, 167, 69, 0.9), rgba(40, 167, 69, 0.7)),
                  url('https://images.unsplash.com/photo-1605000797499-95a51c5269ae?q=80&w=2942&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D') center/cover no-repeat;
      min-height: 400px;
      height: 80vh;
      position: relative;
      color: white;
      display: flex;
      align-items: center;
    }

    .hero::before {
      content: "";
      position: absolute;
      background: linear-gradient(45deg, rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.1));
      width: 100%;
      height: 100%;
      top: 0; left: 0;
    }

    .hero-content {
      position: relative;
      z-index: 1;
    }

    .hero-image img {
      transition: transform 0.3s ease;
    }

    .hero-image:hover img {
      transform: scale(1.05);
    }

    .feature-item {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      border-radius: 10px;
    }

    .feature-item:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .feature-icon {
      transition: transform 0.3s ease;
    }

    .feature-item:hover .feature-icon {
      transform: scale(1.1);
    }

    .category-card, .product-card {
      transition: all 0.3s ease;
      border-radius: 15px;
      overflow: hidden;
    }

    .category-card:hover, .product-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15) !important;
    }

    .category-card .card-img-top,
    .product-card .card-img-top {
      transition: transform 0.3s ease;
    }

    .category-card:hover .card-img-top,
    .product-card:hover .card-img-top {
      transform: scale(1.05);
    }

    .product-link {
      text-decoration: none;
      color: inherit;
    }

    .product-link:hover {
      color: #28a745;
    }

    /* Language Toggle Styles */
    .navbar .dropdown-menu {
      border: none;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
      border-radius: 12px;
      overflow: hidden;
      margin-top: 8px;
    }

    .navbar .dropdown-item {
      padding: 12px 20px;
      transition: all 0.2s ease;
      color: #333;
    }

    .navbar .dropdown-item:hover {
      background: linear-gradient(135deg, #28a745, #20c997);
      color: white;
      transform: translateX(5px);
    }

    .navbar .dropdown-item i {
      width: 20px;
      text-align: center;
    }

    /* Mobile Responsiveness for Language Toggle */
    @media (max-width: 768px) {
      .navbar .dropdown-menu {
        min-width: 180px;
      }

      .navbar .dropdown {
        margin-bottom: 1rem;
      }
    }
      text-decoration: none;
      color: inherit;
    }

    .active-filter {
      background-color: #28a745 !important;
      color: white !important;
      border-color: #28a745 !important;
    }

    .section-title {
      color: #28a745;
      font-weight: 700;
      margin-bottom: 2rem;
      position: relative;
    }

    .section-title::after {
      content: "";
      display: block;
      width: 80px;
      height: 3px;
      background: #28a745;
      margin: 0.5rem auto 0;
    }

    .nav-link {
      color: white !important;
      font-weight: 500;
      padding: 0.5rem 1rem !important;
      transition: all 0.3s ease;
    }

    .nav-link:hover {
      opacity: 0.8;
      transform: translateY(-2px);
    }

    .nav-link.active {
      background-color: rgba(255, 255, 255, 0.2) !important;
      border-radius: 8px;
      font-weight: 600;
    }

    .auth-links .btn {
      margin-left: 0.5rem;
      transition: all 0.3s ease;
    }

    .auth-links .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    /* Enhanced button styles */
    .btn {
      transition: all 0.3s ease;
      border-radius: 8px;
    }

    .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .btn-lg {
      border-radius: 10px;
    }

    /* Card enhancements */
    .card {
      border-radius: 15px;
      transition: all 0.3s ease;
    }

    .card-body {
      padding: 1.5rem;
    }

    /* Badge enhancements */
    .badge {
      border-radius: 6px;
      font-weight: 500;
    }

    /* Enhanced shadows */
    .shadow-sm {
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08) !important;
    }

    /* Stats section styling */
    .hero .h4 {
      font-weight: 700;
      text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .hero small {
      font-weight: 500;
      text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
    }

    /* Responsive improvements */
    @media (max-width: 768px) {
      .hero {
        height: 60vh;
        min-height: 300px;
      }

      .hero-content h1 {
        font-size: 2.5rem !important;
      }

      .hero-content p {
        font-size: 1rem !important;
      }

      .section-title {
        font-size: 2rem !important;
      }

      .navbar-nav {
        text-align: center;
        padding-top: 1rem;
      }

      .auth-links {
        justify-content: center !important;
        padding-bottom: 1rem;
      }

      .hero .d-flex.align-items-center {
        flex-direction: column;
        text-align: center;
      }

      .hero .me-4 {
        margin-right: 1rem !important;
      }
    }

    @media (max-width: 576px) {
      .hero {
        height: 50vh;
        min-height: 250px;
      }

      .hero-content h1 {
        font-size: 2rem !important;
      }

      .hero .d-flex.align-items-center {
        flex-direction: column;
      }

      .hero .me-4 {
        margin-right: 0.5rem !important;
        margin-bottom: 1rem;
      }
    }
  </style>
</head>
<body>

  <!-- Header Section -->
  <header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-success sticky-top">
      <div class="container">
        <a class="navbar-brand" href="/">
          <i class="fas fa-leaf me-2"></i>Etinda
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav me-auto">
            <li class="nav-item">
              <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">{{ __('common.home') }}</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ request()->is('categories*') ? 'active' : '' }}" href="/categories">{{ __('common.categories') }}</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ request()->is('shop*') ? 'active' : '' }}" href="/shop">{{ __('common.shop') }}</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ request()->is('forums*') ? 'active' : '' }}" href="/forums">{{ __('common.forums') }}</a>
            </li>
          </ul>
          <div class="d-flex align-items-center">
            <!-- Language Toggle -->
            <div class="dropdown me-3">
              <button class="btn btn-outline-light dropdown-toggle" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-globe me-2"></i>{{ app()->getLocale() == 'en' ? __('common.english') : __('common.hiligaynon') }}
              </button>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                <li><a class="dropdown-item" href="{{ route('language.switch', 'en') }}">
                  <i class="fas fa-flag me-2"></i>{{ __('common.english') }}
                </a></li>
                <li><a class="dropdown-item" href="{{ route('language.switch', 'hil') }}">
                  <i class="fas fa-flag me-2"></i>{{ __('common.hiligaynon') }}
                </a></li>
              </ul>
            </div>

            <a href="/cart" class="btn btn-outline-light me-2">
              <i class="fas fa-shopping-cart"></i>
              <span class="badge bg-danger ms-1">{{ count(session('cart', [])) }}</span>
            </a>
            @auth
                <a href="/dashboard" class="btn btn-light text-success">{{ __('common.my_account') }}</a>
            @else
                <a href="/login" class="btn btn-light text-success">{{ __('common.login') }}</a>
            @endauth
          </div>
        </div>
      </div>
    </nav>
  </header>

  @yield('content')
  <!-- Footer Section -->
  <footer class="bg-success text-white py-4">
    <div class="container">
      <div class="row">
        <div class="col-md-4 mb-4 mb-md-0">
          <h5><i class="fas fa-leaf me-2"></i>Etinda</h5>
          <p>Connecting local farmers with the community.</p>
          <div class="social-icons">
            <a href="#" class="text-white me-2"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="text-white me-2"><i class="fab fa-twitter"></i></a>
            <a href="#" class="text-white me-2"><i class="fab fa-instagram"></i></a>
          </div>
        </div>
        <div class="col-md-2 mb-4 mb-md-0">
          <h5>Shop</h5>
          <ul class="list-unstyled">
            <li><a href="#" class="text-white">All Products</a></li>
            {{-- <li><a href="#" class="text-white">Seasonal</a></li>
            <li><a href="#" class="text-white">Organic</a></li>
            <li><a href="#" class="text-white">Bundles</a></li> --}}
          </ul>
        </div>
        <div class="col-md-2 mb-4 mb-md-0">
          <h5>About</h5>
          <ul class="list-unstyled">
            {{-- <li><a href="#" class="text-white">Our Story</a></li>
            <li><a href="#" class="text-white">Farmers</a></li>
            <li><a href="#" class="text-white">Sustainability</a></li> --}}
            <li><a href="#" class="text-white">Blog</a></li>
          </ul>
        </div>
        <div class="col-md-4">
          <h5>Newsletter</h5>
          <p>Subscribe for updates and special offers</p>
          <div class="input-group mb-3">
            <input type="email" class="form-control" placeholder="Your email">
            <button class="btn btn-light text-success" type="button">Subscribe</button>
          </div>
        </div>
      </div>
      <hr class="my-4 bg-light">
      <div class="text-center">
        <p class="mb-0">&copy; 2025 Etinda Farmers' Market. All rights reserved.</p>
      </div>
    </div>
  </footer>

  <!-- Bootstrap JS Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Notification Container -->
  <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    @if(session('success'))
      <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-success text-white">
          <strong class="me-auto">Success</strong>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
          {{ session('success') }}
        </div>
      </div>
    @endif
  </div>

  <script>
    // Auto-hide notifications after 3 seconds
    document.addEventListener('DOMContentLoaded', function() {
      const toasts = document.querySelectorAll('.toast');
      toasts.forEach(toast => {
        setTimeout(() => {
          toast.classList.remove('show');
          setTimeout(() => toast.parentElement.remove(), 150);
        }, 3000);
      });
    });
  </script>
</body>
</html>
