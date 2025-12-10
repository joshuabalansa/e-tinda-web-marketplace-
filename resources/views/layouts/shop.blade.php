<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>E-Tinda</title>
  <!-- Bootstrap CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome CDN for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* Custom styles that override or complement Bootstrap */
    html, body {
      height: 100%;
      font-family: 'Segoe UI', sans-serif;
      background-color: #f9f9f9;
    }

    body {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    /* Main content wrapper - grows to fill available space */
    .main-content-wrapper {
      flex: 1 0 auto;
    }

    /* Footer stays at bottom */
    footer {
      flex-shrink: 0;
      margin-top: auto;
    }

    /* Mobile-specific footer positioning */
    @media (max-width: 768px) {
      body {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
      }

      .main-content-wrapper {
        flex: 1;
        min-height: 0;
      }

      footer {
        margin-top: auto;
      }
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

    /* Hero text responsive sizing */
    .hero-content h1 {
      font-size: clamp(1.75rem, 5vw, 3.5rem);
      line-height: 1.2;
      word-wrap: break-word;
      overflow-wrap: break-word;
    }

    .hero-content h1 .d-block {
      font-size: clamp(1rem, 2.5vw, 1.5rem);
      line-height: 1.4;
    }

    .hero-content p {
      font-size: clamp(0.9rem, 2vw, 1.25rem);
      line-height: 1.6;
      word-wrap: break-word;
      overflow-wrap: break-word;
    }

    .hero-content .btn {
      font-size: clamp(0.875rem, 1.5vw, 1rem);
      padding: clamp(0.5rem, 2vw, 0.75rem) clamp(1rem, 3vw, 1.5rem);
    }

    /* Responsive improvements */
    @media (max-width: 992px) {
      .hero {
        height: 70vh;
        min-height: 400px;
        padding: 2rem 0;
      }

      .hero-content {
        padding: 1rem;
      }

      .hero-content h1 {
        margin-bottom: 1.5rem !important;
      }

      .hero-content p {
        margin-bottom: 1.5rem !important;
      }
    }

    @media (max-width: 768px) {
      .hero {
        height: 60vh;
        min-height: 350px;
        padding: 1.5rem 0;
      }

      .hero-content {
        text-align: center;
        padding: 0.5rem;
      }

      .hero-content h1 {
        margin-bottom: 1rem !important;
      }

      .hero-content p {
        margin-bottom: 1rem !important;
      }

      .hero-content .d-flex {
        justify-content: center;
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

      /* Header buttons mobile responsive */
      .navbar .d-flex.align-items-center {
        flex-direction: column;
        width: 100%;
        gap: 0.5rem;
        padding-top: 1rem;
        padding-bottom: 1rem;
        align-items: stretch !important;
      }

      .navbar .d-flex.align-items-center > * {
        width: 100%;
        margin: 0 !important;
      }

      .navbar .d-flex.align-items-center .btn {
        width: 100%;
        justify-content: center;
        padding: 0.625rem 1rem;
        font-size: 0.9rem;
        min-height: 44px; /* Touch-friendly minimum height */
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .navbar .d-flex.align-items-center .language-switcher {
        width: 100%;
      }

      .navbar .d-flex.align-items-center .language-switcher .btn {
        width: 100%;
        min-height: 44px;
      }

      .navbar .d-flex.align-items-center a.btn {
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
      }

      .navbar .d-flex.align-items-center .badge {
        margin-left: 0.5rem;
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
        height: auto;
        min-height: 300px;
        padding: 2rem 0;
      }

      .hero-content {
        padding: 0.5rem;
      }

      .hero-content h1 {
        margin-bottom: 1rem !important;
      }

      .hero-content p {
        margin-bottom: 1rem !important;
        font-size: 0.95rem !important;
      }

      .hero-content .btn {
        padding: 0.625rem 1.25rem !important;
        font-size: 0.9rem !important;
      }

      .hero-content .d-flex {
        flex-direction: column;
        gap: 0.75rem !important;
      }

      .hero-content .d-flex .btn {
        width: 100%;
      }

      .hero .d-flex.align-items-center {
        flex-direction: column;
      }

      .hero .me-4 {
        margin-right: 0.5rem !important;
        margin-bottom: 1rem;
      }

      /* Smaller screens - compact header buttons */
      .navbar .d-flex.align-items-center {
        gap: 0.375rem;
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
      }

      .navbar .d-flex.align-items-center .btn {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
      }

      .navbar .d-flex.align-items-center .btn i {
        font-size: 0.875rem;
      }

      .navbar .d-flex.align-items-center .badge {
        font-size: 0.75rem;
        padding: 0.25em 0.5em;
      }
    }

    @media (max-width: 375px) {
      .hero {
        min-height: 280px;
        padding: 1.5rem 0;
      }

      .hero-content h1 {
        font-size: 1.5rem !important;
      }

      .hero-content h1 .d-block {
        font-size: 0.9rem !important;
      }

      .hero-content p {
        font-size: 0.85rem !important;
      }

      .hero-content .btn {
        padding: 0.5rem 1rem !important;
        font-size: 0.85rem !important;
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
          <i class="fas fa-leaf me-2"></i>E-tinda
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
            <li class="nav-item">
              <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#instructionModal">
                <i class="fas fa-play-circle me-1"></i>Instruction
              </a>
            </li>
          </ul>
          <div class="d-flex align-items-center">
            <!-- Language Switcher -->
            <div class="me-3">
                @include('components.language-switcher')
            </div>

            <a href="/cart" class="btn btn-outline-light me-2">
              <i class="fas fa-shopping-cart"></i>
              <span class="badge bg-danger ms-1">
                @auth
                  {{ auth()->user()->cartItems()->count() }}
                @else
                  {{ count(session('cart', [])) }}
                @endauth
              </span>
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

  <div class="main-content-wrapper">
    @yield('content')
  </div>

  <!-- Footer Section -->
  <footer class="bg-success text-white py-4">
    <div class="container">
      <div class="row">
        <div class="col-12 text-center">
          <h5><i class="fas fa-leaf me-2"></i>E-tinda</h5>
          <p>Connecting local farmers with the community.</p>
        </div>
      </div>
    </div>
  </footer>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

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

  <!-- Instruction Video Modal -->
  <div class="modal fade" id="instructionModal" tabindex="-1" aria-labelledby="instructionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="instructionModalLabel">
            <i class="fas fa-video me-2"></i>Instruction Video
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-0">
          <div class="ratio ratio-16x9">
            <video id="instructionVideo" controls style="width: 100%; height: 100%;">
              <source src="{{ asset('uploads/video/Messenger_creation_A0C63F95-0817-4188-BC07-46E7280CD777.mp4') }}" type="video/mp4">
              Your browser does not support the video tag.
            </video>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
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

      // Pause video when modal is closed
      const instructionModal = document.getElementById('instructionModal');
      const instructionVideo = document.getElementById('instructionVideo');
      if (instructionModal && instructionVideo) {
        instructionModal.addEventListener('hidden.bs.modal', function () {
          instructionVideo.pause();
          instructionVideo.currentTime = 0;
        });
      }
    });
  </script>
</body>
</html>
