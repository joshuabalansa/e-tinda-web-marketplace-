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
  </style>

  <header class="bg-light py-5">
    <div class="container text-center">
      <h1 class="display-4 fw-bold text-success">Fresh From Our Farms</h1>
      <p class="lead">Organic, locally-sourced produce delivered to your doorstep</p>
    </div>
  </header>

  <!-- Main Content -->
  <div class="container py-5">
    <div class="row">
      <!-- Sidebar Filters -->
      <div class="col-lg-3 mb-4">
        <div class="card shadow-sm">
          <div class="card-header bg-success text-white">
            <h5 class="mb-0">Filters</h5>
          </div>
          <div class="card-body">
            <!-- Categories -->
            <h6 class="mb-3">Categories</h6>
            <div class="d-flex flex-wrap gap-2 mb-4">
              <button class="btn btn-sm btn-outline-success category-btn active">All</button>
              <button class="btn btn-sm btn-outline-success category-btn">Vegetables</button>
              <button class="btn btn-sm btn-outline-success category-btn">Fruits</button>
              <button class="btn btn-sm btn-outline-success category-btn">Dairy</button>
              <button class="btn btn-sm btn-outline-success category-btn">Meat</button>
            </div>

            <!-- Price Range -->
            <h6 class="mb-3">Price Range</h6>
            <div class="mb-4">
              <input type="range" class="form-range" min="0" max="1000" step="10" id="priceRange">
              <div class="d-flex justify-content-between">
                <span>₱0</span>
                <span>₱1000</span>
              </div>
            </div>

            <!-- Rating -->
            <h6 class="mb-3">Rating</h6>
            <div class="mb-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="rating5">
                <label class="form-check-label" for="rating5">
                  <i class="fas fa-star text-warning"></i>
                  <i class="fas fa-star text-warning"></i>
                  <i class="fas fa-star text-warning"></i>
                  <i class="fas fa-star text-warning"></i>
                  <i class="fas fa-star text-warning"></i>
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="rating4">
                <label class="form-check-label" for="rating4">
                  <i class="fas fa-star text-warning"></i>
                  <i class="fas fa-star text-warning"></i>
                  <i class="fas fa-star text-warning"></i>
                  <i class="fas fa-star text-warning"></i>
                  <i class="far fa-star text-warning"></i>
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="rating3">
                <label class="form-check-label" for="rating3">
                  <i class="fas fa-star text-warning"></i>
                  <i class="fas fa-star text-warning"></i>
                  <i class="fas fa-star text-warning"></i>
                  <i class="far fa-star text-warning"></i>
                  <i class="far fa-star text-warning"></i>
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Products Grid -->
      <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h4 class="mb-0">Products</h4>
          <div class="d-flex">
            <select class="form-select me-2" style="width: auto;">
              <option selected>Sort by</option>
              <option>Price: Low to High</option>
              <option>Price: High to Low</option>
              <option>Most Popular</option>
              <option>Newest</option>
            </select>
            <div class="btn-group" role="group">
              <button type="button" class="btn btn-outline-success"><i class="fas fa-th"></i></button>
              <button type="button" class="btn btn-outline-success active"><i class="fas fa-list"></i></button>
            </div>
          </div>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
          @forelse($products as $product)
            <div class="col">
              <a href="{{ route('shop.product', ['id' => $product['id']]) }}" class="product-link">
                <div class="card h-100 product-card">
                  @if(isset($product['certification']) && $product['certification'] === 'Organic Certified')
                    <div class="badge bg-success position-absolute" style="top: 10px; right: 10px;">Organic</div>
                  @endif
                  <img src="{{ $product['image'] }}" class="card-img-top" alt="{{ $product['name'] }}">
                  <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $product['name'] }}</h5>
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
                    <p class="card-text flex-grow-1">{{ $product['description'] }}</p>
                    <div class="d-flex justify-content-between align-items-center mt-auto">
                      <h5 class="mb-0 text-success">₱{{ number_format($product['price'], 2) }}/{{ $product['unit'] }}</h5>
                      <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                        <input type="hidden" name="name" value="{{ $product['name'] }}">
                        <input type="hidden" name="price" value="{{ $product['price'] }}">
                        <input type="hidden" name="image" value="{{ $product['image'] }}">
                        <input type="hidden" name="unit" value="{{ $product['unit'] }}">
                        <button type="submit" class="btn btn-sm btn-success">
                          <i class="fas fa-cart-plus"></i> Add
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
              <h3>No Products Available</h3>
              <p class="text-muted">Check back later for new products</p>
            </div>
          @endforelse
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
          <nav aria-label="Page navigation" class="mt-5">
            <ul class="pagination justify-content-center">
              @if($products->onFirstPage())
                <li class="page-item disabled">
                  <a class="page-link text-success" href="#" tabindex="-1">Previous</a>
                </li>
              @else
                <li class="page-item">
                  <a class="page-link text-success" href="{{ $products->previousPageUrl() }}">Previous</a>
                </li>
              @endif

              @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                <li class="page-item {{ $page == $products->currentPage() ? 'active' : '' }}">
                  <a class="page-link {{ $page == $products->currentPage() ? 'bg-success border-success' : 'text-success' }}" href="{{ $url }}">{{ $page }}</a>
                </li>
              @endforeach

              @if($products->hasMorePages())
                <li class="page-item">
                  <a class="page-link text-success" href="{{ $products->nextPageUrl() }}">Next</a>
                </li>
              @else
                <li class="page-item disabled">
                  <a class="page-link text-success" href="#">Next</a>
                </li>
              @endif
            </ul>
          </nav>
        @endif
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Category filter buttons
    document.querySelectorAll('.category-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
      });
    });
  </script>
@endsection
