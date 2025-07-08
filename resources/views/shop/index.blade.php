@extends('layouts.shop')
@section('content')
  <style>
    .product-card .card-img-top {
      height: 200px;
      object-fit: cover;
    }
    .product-card {
      transition: transform 0.2s;
    }
    .product-card:hover {
      transform: translateY(-5px);
    }
    .product-link {
      text-decoration: none;
      color: inherit;
    }
    .filter-form {
      background: none;
      border: none;
    }
    .active-filter {
      background-color: #198754 !important;
      color: white !important;
    }
  </style>

  <header class="bg-light py-5">
    <div class="container text-center">
      <h1 class="display-4 fw-bold text-success">Fresh From Our Farms</h1>
      <p class="lead">Organic, locally-sourced produce delivered to your doorstep</p>
    </div>
  </header>

  <!-- Main Content -->
  <div class="container py-5">
    <!-- Page Header -->
    @if(isset($currentCategory))
      <div class="mb-4">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categories</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $currentCategory }}</li>
          </ol>
        </nav>
        <h1 class="section-title">{{ $currentCategory }} Products</h1>
        <p class="text-muted">Browse our selection of {{ strtolower($currentCategory) }} products from local farmers.</p>
      </div>
    @else
      <h1 class="section-title text-center">Shop All Products</h1>
    @endif

    <!-- Flash Messages -->
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <!-- Search Bar -->
    <div class="row mb-4">
      <div class="col-md-8 mx-auto">
        <form method="GET" action="{{ route('shop.index') }}" class="d-flex">
          <input type="text" name="query" class="form-control me-2" placeholder="Search products..." value="{{ request('query') }}">
          <button type="submit" class="btn btn-success">
            <i class="fas fa-search"></i>
          </button>
        </form>
      </div>
    </div>

    <div class="row">
      <!-- Sidebar Filters -->
      <div class="col-lg-3 mb-4">
        <div class="card shadow-sm">
          <div class="card-header bg-success text-white">
            <h5 class="mb-0">Filters</h5>
          </div>
          <div class="card-body">
            <form id="filterForm" method="GET" action="{{ route('shop.index') }}">
              <!-- Categories -->
              <h6 class="mb-3">Categories</h6>
              <div class="d-flex flex-wrap gap-2 mb-4">
                <button type="button" class="btn btn-sm btn-outline-success category-btn {{ !request('category') || request('category') == 'all' ? 'active-filter' : '' }}"
                        onclick="setCategory('all')">All</button>
                @foreach($categories as $category)
                  <button type="button" class="btn btn-sm btn-outline-success category-btn {{ request('category') == $category ? 'active-filter' : '' }}"
                          onclick="setCategory('{{ $category }}')">{{ $category }}</button>
                @endforeach
              </div>
              <input type="hidden" name="category" id="categoryInput" value="{{ request('category', 'all') }}">

              <!-- Price Range -->
              <h6 class="mb-3">Price Range</h6>
              <div class="row g-2 mb-4">
                <div class="col-6">
                  <input type="number" name="min_price" id="min_price" class="form-control form-control-sm" placeholder="Min" value="{{ request('min_price') }}" min="0" step="0.01">
                </div>
                <div class="col-6">
                  <input type="number" name="max_price" id="max_price" class="form-control form-control-sm" placeholder="Max" value="{{ request('max_price') }}" min="0" step="0.01">
                </div>
              </div>

              <!-- Stock Filter -->
              <div class="mb-4">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="in_stock" id="in_stock"
                         {{ request('in_stock') ? 'checked' : '' }} onchange="applyFilters()">
                  <label class="form-check-label" for="in_stock">
                    In Stock Only
                  </label>
                </div>
              </div>

              <!-- Clear Filters -->
              <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="clearFilters()">
                Clear All Filters
              </button>
            </form>
          </div>
        </div>
      </div>

      <!-- Products Grid -->
      <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h4 class="mb-0">Products
            @if(request()->hasAny(['category', 'min_price', 'max_price', 'query', 'in_stock']))
              <small class="text-muted">({{ $products->total() }} results)</small>
            @endif
          </h4>
          <div class="d-flex">
            <form method="GET" action="{{ route('shop.index') }}" class="d-flex">
              <!-- Preserve other filters -->
              @foreach(request()->except(['sort', 'page']) as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
              @endforeach

              <select name="sort" class="form-select me-2" style="width: auto;" onchange="this.form.submit()">
                <option value="">Sort by</option>
                <option value="price_low_high" {{ request('sort') == 'price_low_high' ? 'selected' : '' }}>Price: Low to High</option>
                <option value="price_high_low" {{ request('sort') == 'price_high_low' ? 'selected' : '' }}>Price: High to Low</option>
                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name: Z to A</option>
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
              </select>
            </form>
          </div>
        </div>

        <!-- Active Filters Display -->
        @if(request()->hasAny(['category', 'min_price', 'max_price', 'query', 'in_stock']))
          <div class="mb-3">
            <small class="text-muted">Active filters:</small>
            @if(request('category') && request('category') != 'all')
              <span class="badge bg-success me-1">{{ request('category') }}</span>
            @endif
            @if(request('min_price'))
              <span class="badge bg-success me-1">Min: ₱{{ request('min_price') }}</span>
            @endif
            @if(request('max_price'))
              <span class="badge bg-success me-1">Max: ₱{{ request('max_price') }}</span>
            @endif
            @if(request('query'))
              <span class="badge bg-success me-1">Search: "{{ request('query') }}"</span>
            @endif
            @if(request('in_stock'))
              <span class="badge bg-success me-1">In Stock Only</span>
            @endif
          </div>
        @endif

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
          @forelse($products as $product)
            <div class="col">
              <a href="{{ route('shop.product', ['id' => $product['id']]) }}" class="product-link">
                <div class="card h-100 product-card">
                  @if(isset($product['certification']) && $product['certification'] === 'Organic Certified')
                    <div class="badge bg-success position-absolute" style="top: 10px; right: 10px;">Organic</div>
                  @endif
                  @if($product['stock'] <= 0)
                    <div class="badge bg-danger position-absolute" style="top: 10px; left: 10px;">Out of Stock</div>
                  @elseif($product['stock'] <= 5)
                    <div class="badge bg-warning position-absolute" style="top: 10px; left: 10px;">Low Stock</div>
                  @endif
                  <img src="{{ $product['image'] }}" class="card-img-top" alt="{{ $product['name'] }}">
                  <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $product['name'] }}</h5>
                    <p class="card-text text-muted small">{{ $product['category'] }}</p>
                    @if(isset($product['rating']))
                      <div class="rating mb-2">
                        @for($i = 0; $i < 5; $i++)
                          @if($i < floor($product['rating']))
                            <i class="fas fa-star"></i>
                          @elseif($i < ceil($product['rating']) && $product['rating'] - floor($product['rating']) >= 0.5)
                            <i class="fas fa-star-half-alt"></i>
                          @else
                            <i class="far fa-star"></i>
                          @endif
                        @endfor
                        @if(isset($product['review_count']))
                          <small class="text-muted ms-1">({{ $product['review_count'] }})</small>
                        @endif
                      </div>
                    @endif
                    <p class="card-text flex-grow-1">{{ Str::limit($product['description'], 100) }}</p>
                    <div class="d-flex justify-content-between align-items-center mt-auto">
                      <h5 class="mb-0 text-success">₱{{ number_format($product['price'], 2) }}/{{ $product['unit'] }}</h5>
                      <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                        <input type="hidden" name="name" value="{{ $product['name'] }}">
                        <input type="hidden" name="price" value="{{ $product['price'] }}">
                        <input type="hidden" name="image" value="{{ $product['image'] }}">
                        <input type="hidden" name="unit" value="{{ $product['unit'] }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn btn-sm btn-success" {{ $product['stock'] <= 0 ? 'disabled' : '' }}>
                          <i class="fas fa-cart-plus"></i> {{ $product['stock'] <= 0 ? 'Out of Stock' : 'Add' }}
                        </button>
                      </form>
                    </div>
                  </div>
                </div>
              </a>
            </div>
          @empty
            <div class="col-12 text-center py-5">
              <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
              <h3>No Products Found</h3>
              <p class="text-muted">
                @if(request()->hasAny(['category', 'min_price', 'max_price', 'query', 'in_stock']))
                  Try adjusting your filters or search terms
                @else
                  Check back later for new products
                @endif
              </p>
              @if(request()->hasAny(['category', 'min_price', 'max_price', 'query', 'in_stock']))
                <button class="btn btn-outline-success" onclick="clearFilters()">Clear All Filters</button>
              @endif
            </div>
          @endforelse
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
          <nav aria-label="Page navigation" class="mt-5">
            {{ $products->appends(request()->query())->links('pagination::bootstrap-4') }}
          </nav>
        @endif
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function setCategory(category) {
      document.getElementById('categoryInput').value = category;
      // Update button states
      document.querySelectorAll('.category-btn').forEach(btn => {
        btn.classList.remove('active-filter');
        btn.classList.add('btn-outline-success');
      });
      event.target.classList.add('active-filter');
      event.target.classList.remove('btn-outline-success');

      applyFilters();
    }

    function applyFilters() {
      document.getElementById('filterForm').submit();
    }

    function clearFilters() {
      // Clear all form inputs
      document.getElementById('categoryInput').value = 'all';
      document.getElementById('min_price').value = '';
      document.getElementById('max_price').value = '';
      document.getElementById('in_stock').checked = false;

      // Reset category buttons
      document.querySelectorAll('.category-btn').forEach(btn => {
        btn.classList.remove('active-filter');
        btn.classList.add('btn-outline-success');
      });
      document.querySelector('.category-btn').classList.add('active-filter');

      // Redirect to clean URL
      window.location.href = '{{ route("shop.index") }}';
    }

    // Initialize category buttons on page load
    document.addEventListener('DOMContentLoaded', function() {
      const activeCategory = '{{ request("category", "all") }}';
      document.querySelectorAll('.category-btn').forEach(btn => {
        const btnCategory = btn.textContent.trim();
        if ((activeCategory === 'all' && btnCategory === 'All') ||
            (activeCategory === btnCategory)) {
          btn.classList.add('active-filter');
          btn.classList.remove('btn-outline-success');
        }
      });
    });
  </script>
@endsection
