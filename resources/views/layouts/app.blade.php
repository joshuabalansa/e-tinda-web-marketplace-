<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
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
      background: url('https://images.unsplash.com/photo-1605000797499-95a51c5269ae?q=80&w=2942&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D') center/cover no-repeat;
      min-height: 300px;
      height: 60vh;
      position: relative;
      color: white;
    }

    .hero::before {
      content: "";
      position: absolute;
      background-color: rgba(0, 0, 0, 0.4);
      width: 100%;
      height: 100%;
      top: 0; left: 0;
    }

    .hero-content {
      position: relative;
      z-index: 1;
    }

    .category-card, .product-card {
      transition: transform 0.3s ease;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .category-card:hover, .product-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
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
    }

    .nav-link:hover {
      opacity: 0.8;
    }

    .auth-links .btn {
      margin-left: 0.5rem;
    }

    @media (max-width: 768px) {
      .hero {
        height: 50vh;
      }

      .hero-content h1 {
        font-size: 2rem !important;
      }

      .hero-content p {
        font-size: 1rem !important;
      }

      .section-title {
        font-size: 1.5rem !important;
      }

      .navbar-nav {
        text-align: center;
        padding-top: 1rem;
      }

      .auth-links {
        justify-content: center !important;
        padding-bottom: 1rem;
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
              <a class="nav-link" href="/">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/categories">Categories</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/shop">Shop</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/forums">forums</a>
            </li>
          </ul>
          <div class="d-flex">
            <a href="/cart" class="btn btn-outline-light me-2">
              <i class="fas fa-shopping-cart"></i>
              <span class="badge bg-danger ms-1">3</span>
            </a>
            <a href="/login" class="btn btn-light text-success">Login</a>
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
          </ul>
        </div>
        <div class="col-md-2 mb-4 mb-md-0">
          <h5>About</h5>
          <ul class="list-unstyled">
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

  <script>
    // Add to cart simulation
    document.addEventListener('DOMContentLoaded', function() {
      const buttons = document.querySelectorAll('.product-card button, .category-card button');
      buttons.forEach(button => {
        button.addEventListener('click', () => {
          // Create and show Bootstrap toast notification
          const toastHTML = `
            <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
              <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header bg-success text-white">
                  <strong class="me-auto">Success</strong>
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                  Product added to your cart!
                </div>
              </div>
            </div>
          `;

          // Insert toast into DOM
          document.body.insertAdjacentHTML('beforeend', toastHTML);

          // Remove toast after 3 seconds
          setTimeout(() => {
            const toast = document.querySelector('.toast');
            if (toast) {
              toast.classList.remove('show');
              setTimeout(() => toast.parentElement.remove(), 150);
            }
          }, 3000);
        });
      });
    });
  </script>

</body>
</html>
