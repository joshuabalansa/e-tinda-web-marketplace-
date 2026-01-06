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
    .cooperative-banner {
      height: 300px;
      object-fit: cover;
      width: 100%;
    }
    .cooperative-header {
      background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
      color: white;
      padding: 3rem 0;
    }
  </style>

  <!-- Cooperative Header -->
  <div class="cooperative-header">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-3 text-center mb-3 mb-md-0">
          @if($cooperative->logo_url)
            <img src="{{ $cooperative->getLogoUrl() }}" alt="{{ $cooperative->name }}" class="img-fluid rounded" style="max-height: 150px; background: white; padding: 10px;">
          @else
            <div class="bg-white text-success rounded p-4" style="width: 150px; height: 150px; margin: 0 auto; display: flex; align-items: center; justify-content: center;">
              <i class="fas fa-users fa-4x"></i>
            </div>
          @endif
        </div>
        <div class="col-md-9">
          <h1 class="display-4 fw-bold mb-2">{{ $cooperative->name }}</h1>
          @if($cooperative->description)
            <p class="lead mb-3">{{ $cooperative->description }}</p>
          @endif
          <div class="row g-3">
            <div class="col-auto">
              <i class="fas fa-map-marker-alt"></i> <strong>{{ $cooperative->location }}</strong>
            </div>
            <div class="col-auto">
              <i class="fas fa-users"></i> <strong>{{ $farmerCount }} {{ $farmerCount == 1 ? 'Farmer' : 'Farmers' }}</strong>
            </div>
            @if($cooperative->date_established)
              <div class="col-auto">
                <i class="fas fa-calendar"></i> Established {{ $cooperative->date_established->format('Y') }}
              </div>
            @endif
          </div>
          @if($cooperative->contact_person)
            <div class="mt-2">
              <i class="fas fa-user"></i> Contact: {{ $cooperative->contact_person }}
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  @if($cooperative->banner_url)
    <img src="{{ $cooperative->getBannerUrl() }}" alt="{{ $cooperative->name }}" class="cooperative-banner">
  @endif

  <!-- Main Content -->
  <div class="container py-5">
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
      <div class="col-md-10 mx-auto">
        <form method="GET" action="{{ route('cooperatives.show', $cooperative->slug ?? $cooperative->association_id) }}" class="d-flex" id="searchForm" onsubmit="event.preventDefault(); performLiveSearch();">
          <input type="text" name="query" id="searchInput" class="form-control me-2 rounded-pill" placeholder="Search products..." value="{{ request('query') }}" style="padding: 12px 20px; font-size: 16px;">
          <button type="submit" class="btn btn-success rounded-pill" style="padding: 12px 24px;">
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
            <form id="filterForm" method="GET" action="{{ route('cooperatives.show', $cooperative->slug ?? $cooperative->association_id) }}">
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

      <!-- Products -->
      <div class="col-lg-9">
        <div id="products-container">
          @include('shop.partials.products', ['products' => $products])
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
          <div class="mt-4">
            {{ $products->appends(request()->query())->links('pagination::bootstrap-4') }}
          </div>
        @endif
      </div>
    </div>
  </div>

  <script>
    function setCategory(category) {
      document.getElementById('categoryInput').value = category;
      applyFilters();
    }

    function applyFilters() {
      document.getElementById('filterForm').submit();
    }

    function clearFilters() {
      window.location.href = "{{ route('cooperatives.show', $cooperative->slug ?? $cooperative->association_id) }}";
    }

    function performLiveSearch() {
      const query = document.getElementById('searchInput').value;
      const form = document.getElementById('searchForm');
      const formData = new FormData(form);

      fetch("{{ route('cooperatives.show', $cooperative->slug ?? $cooperative->association_id) }}", {
        method: 'GET',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
        },
        body: new URLSearchParams(formData)
      })
      .then(response => response.json())
      .then(data => {
        if (data.products_html) {
          document.getElementById('products-container').innerHTML = data.products_html;
        }
      })
      .catch(error => {
        console.error('Error:', error);
        document.getElementById('searchForm').submit();
      });
    }
  </script>
@endsection

