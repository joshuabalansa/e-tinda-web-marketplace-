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
            <h6 class="mt-3">Categories</h6>
            <div class="d-flex flex-wrap gap-2">
              <button class="btn btn-sm btn-outline-success category-btn active">All</button>
              <button class="btn btn-sm btn-outline-success category-btn">Vegetables</button>
              <button class="btn btn-sm btn-outline-success category-btn">Fruits</button>
              <button class="btn btn-sm btn-outline-success category-btn">Dairy</button>
              <button class="btn btn-sm btn-outline-success category-btn">Grains</button>
            </div>

            <h6 class="mt-4">Price Range</h6>
            <input type="range" class="form-range" min="0" max="100" id="priceRange">
            <div class="d-flex justify-content-between">
              <small>$0</small>
              <small>$100</small>
            </div>

            <h6 class="mt-4">Farm Location</h6>
            <select class="form-select">
              <option selected>All Locations</option>
              <option>Local Farms</option>
              <option>Organic Certified</option>
              <option>Family Owned</option>
            </select>

            <h6 class="mt-4">Vendor</h6>
            <select class="form-select">
              <option selected>All Vendors</option>
              <option>Green Harvest Farms</option>
              <option>Fresh Valley Produce</option>
              <option>Organic Life Co.</option>
              <option>Nature's Best</option>
            </select>

            <button class="btn btn-success w-100 mt-4">Apply Filters</button>
          </div>
        </div>
      </div>

      <!-- Product Listings -->
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
          <!-- Product Card 1 -->
          <div class="col">
            <a href="{{ route('shop.product', ['id' => $products[0]['id']]) }}" class="product-link">
              <div class="card h-100 product-card">
                <div class="badge bg-success position-absolute" style="top: 10px; right: 10px;">Organic</div>
                <img src="{{ $products[0]['image'] }}" class="card-img-top" alt="{{ $products[0]['name'] }}">
                <div class="card-body d-flex flex-column">
                  <h5 class="card-title">{{ $products[0]['name'] }}</h5>
                  <div class="rating mb-2">
                    @for($i = 0; $i < 5; $i++)
                      @if($i < floor($products[0]['rating']))
                        <i class="fas fa-star"></i>
                      @elseif($i < ceil($products[0]['rating']) && $products[0]['rating'] - floor($products[0]['rating']) >= 0.5)
                        <i class="fas fa-star-half-alt"></i>
                      @else
                        <i class="far fa-star"></i>
                      @endif
                    @endfor
                    <small class="text-muted ms-1">({{ $products[0]['review_count'] }})</small>
                  </div>
                  <p class="card-text flex-grow-1">{{ $products[0]['description'] }}</p>
                  <div class="d-flex justify-content-between align-items-center mt-auto">
                    <h5 class="mb-0 text-success">₱{{ number_format($products[0]['price'], 2) }}/{{ $products[0]['unit'] }}</h5>
                    <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                      @csrf
                      <input type="hidden" name="product_id" value="{{ $products[0]['id'] }}">
                      <input type="hidden" name="name" value="{{ $products[0]['name'] }}">
                      <input type="hidden" name="price" value="{{ $products[0]['price'] }}">
                      <input type="hidden" name="image" value="{{ $products[0]['image'] }}">
                      <input type="hidden" name="unit" value="{{ $products[0]['unit'] }}">
                      <button type="submit" class="btn btn-sm btn-success">
                        <i class="fas fa-cart-plus"></i> Add
                      </button>
                    </form>
                  </div>
                </div>
              </div>
            </a>
          </div>

          <!-- Product Card 2 -->
          <div class="col">
            <a href="{{ route('shop.product', ['id' => $products[1]['id']]) }}" class="product-link">
              <div class="card h-100 product-card">
                <img src="{{ $products[1]['image'] }}" class="card-img-top" alt="{{ $products[1]['name'] }}">
                <div class="card-body d-flex flex-column">
                  <h5 class="card-title">{{ $products[1]['name'] }}</h5>
                  <div class="rating mb-2">
                    @for($i = 0; $i < 5; $i++)
                      @if($i < floor($products[1]['rating']))
                        <i class="fas fa-star"></i>
                      @elseif($i < ceil($products[1]['rating']) && $products[1]['rating'] - floor($products[1]['rating']) >= 0.5)
                        <i class="fas fa-star-half-alt"></i>
                      @else
                        <i class="far fa-star"></i>
                      @endif
                    @endfor
                    <small class="text-muted ms-1">({{ $products[1]['review_count'] }})</small>
                  </div>
                  <p class="card-text flex-grow-1">{{ $products[1]['description'] }}</p>
                  <div class="d-flex justify-content-between align-items-center mt-auto">
                    <h5 class="mb-0 text-success">₱{{ number_format($products[1]['price'], 2) }}/{{ $products[1]['unit'] }}</h5>
                    <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                      @csrf
                      <input type="hidden" name="product_id" value="{{ $products[1]['id'] }}">
                      <input type="hidden" name="name" value="{{ $products[1]['name'] }}">
                      <input type="hidden" name="price" value="{{ $products[1]['price'] }}">
                      <input type="hidden" name="image" value="{{ $products[1]['image'] }}">
                      <input type="hidden" name="unit" value="{{ $products[1]['unit'] }}">
                      <button type="submit" class="btn btn-sm btn-success">
                        <i class="fas fa-cart-plus"></i> Add
                      </button>
                    </form>
                  </div>
                </div>
              </div>
            </a>
          </div>

          <!-- Product Card 3 -->
          <div class="col">
            <a href="{{ route('shop.index') }}" class="product-link">
              <div class="card h-100 product-card">
                <div class="badge bg-success position-absolute" style="top: 10px; right: 10px;">Organic</div>
                <img src="https://images.unsplash.com/photo-1603569283847-aa295f0d016a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" class="card-img-top" alt="Organic Apples">
                <div class="card-body d-flex flex-column">
                  <h5 class="card-title">Organic Apples</h5>
                  <div class="rating mb-2">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="far fa-star"></i>
                    <small class="text-muted ms-1">(29)</small>
                  </div>
                  <p class="card-text flex-grow-1">Crisp and sweet, perfect for eating fresh or baking.</p>
                  <div class="d-flex justify-content-between align-items-center mt-auto">
                    <h5 class="mb-0 text-success">$1.99/lb</h5>
                    <button class="btn btn-sm btn-success"><i class="fas fa-cart-plus"></i> Add</button>
                  </div>
                </div>
              </div>
            </a>
          </div>

          <!-- Product Card 4 -->
          <div class="col">
            <a href="{{ route('shop.index') }}" class="product-link">
              <div class="card h-100 product-card">
                <img src="https://images.unsplash.com/photo-1550258987-190a2d41a8ba?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" class="card-img-top" alt="Farm Fresh Eggs">
                <div class="card-body d-flex flex-column">
                  <h5 class="card-title">Farm Fresh Eggs</h5>
                  <div class="rating mb-2">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <small class="text-muted ms-1">(58)</small>
                  </div>
                  <p class="card-text flex-grow-1">Free-range eggs from happy, pasture-raised chickens.</p>
                  <div class="d-flex justify-content-between align-items-center mt-auto">
                    <h5 class="mb-0 text-success">$4.99/dozen</h5>
                    <button class="btn btn-sm btn-success"><i class="fas fa-cart-plus"></i> Add</button>
                  </div>
                </div>
              </div>
            </a>
          </div>

          <!-- Product Card 5 -->
          <div class="col">
            <a href="{{ route('shop.index') }}" class="product-link">
              <div class="card h-100 product-card">
                <div class="badge bg-success position-absolute" style="top: 10px; right: 10px;">Organic</div>
                <img src="https://images.unsplash.com/photo-1573246123716-6b1782bfc499?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" class="card-img-top" alt="Fresh Strawberries">
                <div class="card-body d-flex flex-column">
                  <h5 class="card-title">Fresh Strawberries</h5>
                  <div class="rating mb-2">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                    <i class="far fa-star"></i>
                    <small class="text-muted ms-1">(47)</small>
                  </div>
                  <p class="card-text flex-grow-1">Sweet and juicy strawberries picked at peak ripeness.</p>
                  <div class="d-flex justify-content-between align-items-center mt-auto">
                    <h5 class="mb-0 text-success">$4.49/lb</h5>
                    <button class="btn btn-sm btn-success"><i class="fas fa-cart-plus"></i> Add</button>
                  </div>
                </div>
              </div>
            </a>
          </div>

          <!-- Product Card 6 -->
          <div class="col">
            <a href="{{ route('shop.index') }}" class="product-link">
              <div class="card h-100 product-card">
                <img src="https://images.unsplash.com/photo-1601493700631-2b16ec4b4716?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" class="card-img-top" alt="Carrots">
                <div class="card-body d-flex flex-column">
                  <h5 class="card-title">Organic Carrots</h5>
                  <div class="rating mb-2">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="far fa-star"></i>
                    <small class="text-muted ms-1">(36)</small>
                  </div>
                  <p class="card-text flex-grow-1">Sweet and crunchy carrots, perfect for snacking or cooking.</p>
                  <div class="d-flex justify-content-between align-items-center mt-auto">
                    <h5 class="mb-0 text-success">$2.49/lb</h5>
                    <button class="btn btn-sm btn-success"><i class="fas fa-cart-plus"></i> Add</button>
                  </div>
                </div>
              </div>
            </a>
          </div>
        </div>

        <!-- Pagination -->
        <nav aria-label="Page navigation" class="mt-5">
          <ul class="pagination justify-content-center">
            <li class="page-item disabled">
              <a class="page-link" href="#" tabindex="-1">Previous</a>
            </li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item">
              <a class="page-link" href="#">Next</a>
            </li>
          </ul>
        </nav>
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
