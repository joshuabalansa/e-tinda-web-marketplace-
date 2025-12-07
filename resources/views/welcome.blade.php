@extends('layouts.shop')

@section('content')

<style>
  /* Homepage Responsive Styles */
  
  /* Hero Section Responsive Text */
  .hero-content h1 {
    font-size: clamp(1.75rem, 5vw, 3.5rem) !important;
    line-height: 1.2 !important;
    word-wrap: break-word;
    overflow-wrap: break-word;
  }

  .hero-content h1 .d-block {
    font-size: clamp(1rem, 2.5vw, 1.5rem) !important;
    line-height: 1.4 !important;
    margin-top: 0.5rem !important;
  }

  .hero-content p.lead {
    font-size: clamp(0.9rem, 2vw, 1.25rem) !important;
    line-height: 1.6 !important;
    word-wrap: break-word;
    overflow-wrap: break-word;
  }

  /* Features Section */
  .feature-item h5 {
    font-size: clamp(1rem, 2vw, 1.25rem);
  }

  .feature-item p {
    font-size: clamp(0.875rem, 1.5vw, 1rem);
  }

  .feature-icon i {
    font-size: clamp(2rem, 4vw, 3rem) !important;
  }

  /* Section Titles */
  .section-title {
    font-size: clamp(1.5rem, 4vw, 2.5rem) !important;
  }

  .section-title + p.lead {
    font-size: clamp(0.9rem, 2vw, 1.125rem) !important;
  }

  /* Category Cards */
  .category-card .card-title {
    font-size: clamp(1.125rem, 2.5vw, 1.5rem);
  }

  .category-card .card-text {
    font-size: clamp(0.875rem, 1.5vw, 1rem);
  }

  .category-card .badge {
    font-size: clamp(0.75rem, 1.5vw, 1rem) !important;
  }

  /* Product Cards */
  .product-card .card-title {
    font-size: clamp(1rem, 2vw, 1.25rem);
  }

  .product-card .card-text {
    font-size: clamp(0.8rem, 1.5vw, 0.875rem);
  }

  .product-card .h5 {
    font-size: clamp(1.125rem, 2vw, 1.5rem) !important;
  }

  /* Buttons Responsive */
  .btn-lg {
    font-size: clamp(0.9rem, 1.5vw, 1.125rem) !important;
    padding: clamp(0.625rem, 2vw, 0.75rem) clamp(1.25rem, 3vw, 1.5rem) !important;
  }

  /* Tablet Styles */
  @media (max-width: 992px) {
    .hero {
      padding: 2rem 0 !important;
    }

    .hero-content {
      padding: 1rem;
    }

    .feature-item {
      margin-bottom: 1rem;
    }

    .category-card .card-body,
    .product-card .card-body {
      padding: 1.25rem !important;
    }
  }

  /* Mobile Styles */
  @media (max-width: 768px) {
    .hero {
      padding: 1.5rem 0 !important;
      min-height: 350px !important;
    }

    .hero-content {
      text-align: center;
      padding: 0.5rem;
    }

    .hero-content h1 {
      margin-bottom: 1rem !important;
    }

    .hero-content p.lead {
      margin-bottom: 1rem !important;
    }

    .hero-content .d-flex {
      justify-content: center;
      flex-direction: column;
      gap: 0.75rem !important;
    }

    .hero-content .btn {
      width: 100%;
    }

    .feature-item {
      padding: 1.5rem 0.5rem !important;
    }

    .category-card .card-body,
    .product-card .card-body {
      padding: 1rem !important;
    }

    .category-card img,
    .product-card img {
      height: 200px !important;
    }

    section.py-5 {
      padding: 2rem 0 !important;
    }
  }

  /* Small Mobile Styles */
  @media (max-width: 576px) {
    .hero {
      min-height: 300px !important;
      padding: 1.5rem 0 !important;
    }

    .hero-content {
      padding: 0.5rem;
    }

    .hero-content h1 {
      font-size: 1.75rem !important;
      margin-bottom: 0.75rem !important;
    }

    .hero-content h1 .d-block {
      font-size: 1rem !important;
    }

    .hero-content p.lead {
      font-size: 0.95rem !important;
      margin-bottom: 1rem !important;
    }

    .hero-content .btn {
      padding: 0.625rem 1rem !important;
      font-size: 0.9rem !important;
    }

    .feature-icon i {
      font-size: 2rem !important;
    }

    .feature-item h5 {
      font-size: 1rem !important;
    }

    .feature-item p {
      font-size: 0.875rem !important;
    }

    .section-title {
      font-size: 1.5rem !important;
      margin-bottom: 1rem !important;
    }

    .section-title + p.lead {
      font-size: 0.9rem !important;
    }

    .category-card .card-title,
    .product-card .card-title {
      font-size: 1.125rem !important;
    }

    .category-card img,
    .product-card img {
      height: 180px !important;
    }

    .btn-lg {
      font-size: 0.9rem !important;
      padding: 0.625rem 1rem !important;
    }

    section.py-5 {
      padding: 1.5rem 0 !important;
    }

    .text-center.mb-5 {
      margin-bottom: 2rem !important;
    }

    .text-center.mt-5 {
      margin-top: 2rem !important;
    }
  }

  /* Extra Small Mobile */
  @media (max-width: 375px) {
    .hero {
      min-height: 280px !important;
    }

    .hero-content h1 {
      font-size: 1.5rem !important;
    }

    .hero-content h1 .d-block {
      font-size: 0.9rem !important;
    }

    .hero-content p.lead {
      font-size: 0.85rem !important;
    }

    .hero-content .btn {
      padding: 0.5rem 0.875rem !important;
      font-size: 0.85rem !important;
    }

    .feature-item {
      padding: 1rem 0.25rem !important;
    }

    .category-card .card-body,
    .product-card .card-body {
      padding: 0.75rem !important;
    }

    .category-card .badge,
    .product-card .badge {
      font-size: 0.7rem !important;
      padding: 0.25rem 0.5rem !important;
    }
  }

  /* Landscape Mobile */
  @media (max-width: 768px) and (orientation: landscape) {
    .hero {
      min-height: 250px !important;
      padding: 1rem 0 !important;
    }

    .hero-content h1 {
      font-size: 1.5rem !important;
      margin-bottom: 0.5rem !important;
    }

    .hero-content p.lead {
      font-size: 0.9rem !important;
      margin-bottom: 0.75rem !important;
    }

    .hero-content .d-flex {
      gap: 0.5rem !important;
    }
  }
</style>

<!-- Enhanced Hero Section -->
<section class="hero d-flex align-items-center position-relative">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6">
        <div class="hero-content">
          <h1 class="display-3 fw-bold mb-4 text-white">
            {{ __('welcome.hero_title') }}
            <span class="d-block fs-4 fw-normal mt-2">{{ __('welcome.hero_subtitle') }}</span>
          </h1>
          <p class="lead mb-4 text-white-50">{{ __('welcome.hero_description') }}</p>
          <div class="d-flex flex-wrap gap-3">
            <a href="/shop" class="btn btn-success btn-lg px-4 py-3">
              <i class="fas fa-shopping-basket me-2"></i>{{ __('welcome.shop_now') }}
            </a>
            <a href="/categories" class="btn btn-outline-light btn-lg px-4 py-3">
              <i class="fas fa-th-large me-2"></i>{{ __('welcome.browse_categories') }}
            </a>
          </div>
        </div>
      </div>
      <div class="col-lg-6 d-none d-lg-block">
        <div class="hero-image text-center">
          <img src="https://images.unsplash.com/photo-1706784694581-4c6e001c3c37?q=80&w=1932&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
               alt="{{ __('welcome.hero_image_alt') }}"
               class="img-fluid rounded-3 shadow-lg"
               style="max-height: 400px; object-fit: cover;">
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-white">
  <div class="container">
    <div class="row g-4 justify-content-center">
      <div class="col-lg-3 col-md-4 col-sm-6 text-center">
        <div class="feature-item p-3">
          <div class="feature-icon mb-3">
            <i class="fas fa-leaf fa-3x text-success"></i>
          </div>
          <h5 class="fw-bold">{{ __('welcome.fresh_organic') }}</h5>
          <p class="text-muted mb-0">{{ __('welcome.fresh_organic_desc') }}</p>
        </div>
      </div>
      <div class="col-lg-3 col-md-4 col-sm-6 text-center">
        <div class="feature-item p-3">
          <div class="feature-icon mb-3">
            <i class="fas fa-handshake fa-3x text-success"></i>
          </div>
          <h5 class="fw-bold">{{ __('welcome.support_local') }}</h5>
          <p class="text-muted mb-0">{{ __('welcome.support_local_desc') }}</p>
        </div>
      </div>
      <div class="col-lg-3 col-md-4 col-sm-6 text-center">
        <div class="feature-item p-3">
          <div class="feature-icon mb-3">
            <i class="fas fa-shield-alt fa-3x text-success"></i>
          </div>
          <h5 class="fw-bold">{{ __('welcome.quality_assured') }}</h5>
          <p class="text-muted mb-0">{{ __('welcome.quality_assured_desc') }}</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Enhanced Categories Section -->
<section class="py-5 bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="section-title display-6 fw-bold">{{ __('welcome.shop_by_category') }}</h2>
      <p class="lead text-muted">{{ __('welcome.categories_description') }}</p>
    </div>
    <div class="row g-4 justify-content-center">
      @if($categories->count() > 0)
        @foreach($categories as $category)
        <div class="col-lg-4 col-md-6 col-sm-12">
          <div class="category-card card h-100 border-0 shadow-sm">
            <div class="position-relative">
              <img src="{{ $category['image'] }}" class="card-img-top" alt="{{ $category['name'] }}" style="height: 250px; object-fit: cover;">
              <div class="position-absolute top-0 end-0 m-3">
                <span class="badge bg-success fs-6 px-3 py-2">{{ $category['product_count'] }} {{ __('welcome.products_count') }}</span>
              </div>
            </div>
            <div class="card-body d-flex flex-column p-4">
              <h4 class="card-title fw-bold mb-3">{{ $category['name'] }}</h4>
              <p class="card-text text-muted flex-grow-1">{{ $category['description'] }}</p>
              <div class="mt-auto">
                @if(!empty($category['name']))
                <a href="{{ route('categories.show', $category['name']) }}" class="btn btn-outline-success w-100 py-2">
                  <i class="fas fa-arrow-right me-2"></i>{{ __('welcome.browse_category') }} {{ $category['name'] }}
                </a>
                @else
                <button class="btn btn-outline-secondary w-100 py-2" disabled>
                  <i class="fas fa-exclamation-triangle me-2"></i>Category Unavailable
                </button>
                @endif
              </div>
            </div>
          </div>
        </div>
        @endforeach
      @else
        <!-- Fallback if no categories exist -->
        <div class="col-12 text-center">
          <div class="py-5">
            <i class="fas fa-seedling fa-4x text-muted mb-3"></i>
            <p class="text-muted fs-5">{{ __('welcome.no_categories') }}</p>
            <a href="/shop" class="btn btn-success btn-lg">{{ __('welcome.browse_all_products') }}</a>
          </div>
        </div>
      @endif
    </div>

    @if($categories->count() > 0)
    <div class="text-center mt-5">
      <a href="{{ route('categories.index') }}" class="btn btn-success btn-lg px-5">
        <i class="fas fa-th-large me-2"></i>{{ __('welcome.view_all_categories') }}
      </a>
    </div>
    @endif
  </div>
</section>

<!-- Enhanced Featured Products Section -->
<section class="py-5 bg-white">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="section-title display-6 fw-bold">{{ __('welcome.featured_products') }}</h2>
      <p class="lead text-muted">{{ __('welcome.featured_description') }}</p>
    </div>
    <div class="row g-4 justify-content-center">
      @if($featuredProducts->count() > 0)
        @foreach($featuredProducts as $product)
        <div class="col-lg-3 col-md-6 col-sm-12">
          <div class="product-card card h-100 border-0 shadow-sm">
            <div class="position-relative">
              <img src="{{ $product['image'] }}" class="card-img-top" alt="{{ $product['name'] }}" style="height: 220px; object-fit: cover;">
              @if($product['stock'] > 0)
                <div class="position-absolute top-0 start-0 m-2">
                  <span class="badge bg-success">{{ __('welcome.in_stock') }}</span>
                </div>
              @else
                <div class="position-absolute top-0 start-0 m-2">
                  <span class="badge bg-danger">{{ __('welcome.out_of_stock') }}</span>
                </div>
              @endif
              <div class="position-absolute top-0 end-0 m-2">
                <span class="badge bg-warning text-dark">{{ $product['category'] }}</span>
              </div>
            </div>
            <div class="card-body d-flex flex-column p-4">
              <h5 class="card-title fw-bold mb-2">{{ $product['name'] }}</h5>
              <p class="card-text text-muted small flex-grow-1">{{ Str::limit($product['description'], 60) }}</p>
              <div class="mb-3">
                <span class="h5 text-success fw-bold mb-0">${{ number_format($product['price'], 2) }}</span>
                <small class="text-muted">/{{ $product['unit'] }}</small>
              </div>
              <div class="mb-3">
                <small class="text-muted">
                  <i class="fas fa-user me-1"></i>{{ $product['vendor'] }}
                </small>
              </div>
              <div class="mt-auto">
                <div class="d-grid gap-2">
                  <a href="{{ route('shop.product.show', $product['id']) }}" class="btn btn-outline-success">
                    <i class="fas fa-eye me-2"></i>{{ __('welcome.view_details') }}
                  </a>
                  @if($product['stock'] > 0)
                    <form action="{{ route('cart.add') }}" method="POST">
                      @csrf
                      <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                      <input type="hidden" name="quantity" value="1">
                      <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-cart-plus me-2"></i>{{ __('welcome.add_to_cart') }}
                      </button>
                    </form>
                  @else
                    <button class="btn btn-secondary w-100" disabled>
                      <i class="fas fa-times me-2"></i>{{ __('welcome.out_of_stock') }}
                    </button>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
        @endforeach
      @else
        <!-- Fallback if no products exist -->
        <div class="col-12 text-center">
          <div class="py-5">
            <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
            <p class="text-muted fs-5">{{ __('welcome.no_products') }}</p>
            <a href="/shop" class="btn btn-success btn-lg">{{ __('welcome.check_back_later') }}</a>
          </div>
        </div>
      @endif
    </div>

    @if($featuredProducts->count() > 0)
    <div class="text-center mt-5">
      <a href="{{ route('shop.index') }}" class="btn btn-success btn-lg px-5">
        <i class="fas fa-store me-2"></i>{{ __('welcome.view_all_products') }}
      </a>
    </div>
    @endif
  </div>
</section>
@endsection
