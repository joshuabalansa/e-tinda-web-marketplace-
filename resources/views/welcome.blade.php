@extends('layouts.shop')

@section('content')
<!-- Hero Section -->
<section class="hero d-flex align-items-center">
  <div class="container">
    <div class="hero-content text-center">
      <h1 class="display-4 fw-bold mb-4">Fresh from Local Farmers</h1>
      <p class="lead mb-4">Support your local farmers and get fresh, organic produce delivered to your doorstep.</p>
      <a href="/shop" class="btn btn-success btn-lg">Shop Now</a>
    </div>
  </div>
</section>

<!-- Categories Section -->
<section class="py-5">
  <div class="container">
    <h2 class="section-title text-center">Shop by Category</h2>
    <div class="row g-4">
      <!-- Category Cards -->
      <div class="col-md-4">
        <div class="category-card card h-100">
          <img src="https://images.unsplash.com/photo-1597362925123-77861d3fbac7" class="card-img-top" alt="Vegetables">
          <div class="card-body text-center">
            <h5 class="card-title">Vegetables</h5>
            <p class="card-text">Fresh, locally grown vegetables.</p>
            <a href="/shop/vegetables" class="btn btn-outline-success">Browse Vegetables</a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="category-card card h-100">
          <img src="https://images.unsplash.com/photo-1592558480962-903b06077406?q=80&w=2062&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="card-img-top" alt="Fruits">
          <div class="card-body text-center">
            <h5 class="card-title">Fruits</h5>
            <p class="card-text">Seasonal and exotic fruits.</p>
            <a href="/shop/fruits" class="btn btn-outline-success">Browse Fruits</a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="category-card card h-100">
          <img src="https://images.unsplash.com/photo-1633179963862-72c64dc6d30d?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="card-img-top" alt="Dairy">
          <div class="card-body text-center">
            <h5 class="card-title">Dairy</h5>
            <p class="card-text">Farm-fresh dairy products.</p>
            <a href="/shop/dairy" class="btn btn-outline-success">Browse Dairy</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Featured Products Section -->
<section class="py-5 bg-light">
  <div class="container">
    <h2 class="section-title text-center">Featured Products</h2>
    <div class="row g-4">
      <!-- Product Cards -->
      <div class="col-md-3">
        <div class="product-card card h-100">
          <div class="card-img-wrapper" style="height: 200px; overflow: hidden;">
            <img src="https://images.unsplash.com/photo-1723477036930-ab1ebb3953e9?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="card-img-top h-100 w-100" alt="Organic Tomatoes" style="object-fit: cover;">
          </div>
          <div class="card-body">
            <h5 class="card-title">Organic Tomatoes</h5>
            <p class="card-text">Fresh, vine-ripened tomatoes</p>
            <p class="text-success fw-bold">$4.99/lb</p>
            <button class="btn btn-success w-100">Add to Cart</button>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="product-card card h-100">
          <div class="card-img-wrapper" style="height: 200px; overflow: hidden;">
            <img src="https://plus.unsplash.com/premium_photo-1661376910798-c74ed8f65819?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="card-img-top h-100 w-100" alt="Fresh Carrots" style="object-fit: cover;">
          </div>
          <div class="card-body">
            <h5 class="card-title">Fresh Carrots</h5>
            <p class="card-text">Locally grown organic carrots</p>
            <p class="text-success fw-bold">$3.49/lb</p>
            <button class="btn btn-success w-100">Add to Cart</button>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="product-card card h-100">
          <div class="card-img-wrapper" style="height: 200px; overflow: hidden;">
            <img src="https://images.unsplash.com/photo-1662318183333-971ae1658e44?q=80&w=1932&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="card-img-top h-100 w-100" alt="Fresh Lettuce" style="object-fit: cover;">
          </div>
          <div class="card-body">
            <h5 class="card-title">Fresh Lettuce</h5>
            <p class="card-text">Crispy organic lettuce</p>
            <p class="text-success fw-bold">$2.99/head</p>
            <button class="btn btn-success w-100">Add to Cart</button>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="product-card card h-100">
          <div class="card-img-wrapper" style="height: 200px; overflow: hidden;">
            <img src="https://images.unsplash.com/photo-1741518165791-df31747b8d8b?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="card-img-top h-100 w-100" alt="Bell Peppers" style="object-fit: cover;">
          </div>
          <div class="card-body">
            <h5 class="card-title">Bell Peppers</h5>
            <p class="card-text">Colorful organic peppers</p>
            <p class="text-success fw-bold">$5.99/lb</p>
            <button class="btn btn-success w-100">Add to Cart</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
