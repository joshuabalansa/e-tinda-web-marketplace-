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
    #searchInput {
      border: 2px solid #e0e0e0;
      transition: all 0.3s ease;
    }
    #searchInput:focus {
      border-color: #198754;
      box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
      outline: none;
    }
  </style>

  <header class="bg-light py-5">
    <div class="container text-center">
      <h1 class="display-4 fw-bold text-success">{{ __('shop.fresh_from_farms') }}</h1>
      <p class="lead">{{ __('shop.organic_description') }}</p>
    </div>
  </header>

  <!-- Main Content -->
  <div class="container py-5">
    <!-- Page Header -->
    @if(isset($currentCategory))
      <div class="mb-4">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">{{ __('shop.categories') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $currentCategory }}</li>
          </ol>
        </nav>
        <h1 class="section-title">{{ $currentCategory }} {{ __('shop.products') }}</h1>
        <p class="text-muted">{{ __('shop.browse_category_products', ['category' => strtolower($currentCategory)]) }}</p>
      </div>
    @else
      <h1 class="section-title text-center">{{ __('shop.shop_all_products') }}</h1>
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
      <div class="col-md-10 mx-auto">
        <form method="GET" action="{{ route('shop.index') }}" class="d-flex" id="searchForm" onsubmit="event.preventDefault(); performLiveSearch();">
          <input type="text" name="query" id="searchInput" class="form-control me-2 rounded-pill" placeholder="{{ __('shop.search_products') }}" value="{{ request('query') }}" style="padding: 12px 20px; font-size: 16px;">
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
            <h5 class="mb-0">{{ __('shop.filters') }}</h5>
          </div>
          <div class="card-body">
            <form id="filterForm" method="GET" action="{{ route('shop.index') }}">
              <!-- Categories -->
              <h6 class="mb-3">{{ __('shop.categories') }}</h6>
              <div class="d-flex flex-wrap gap-2 mb-4">
                <button type="button" class="btn btn-sm btn-outline-success category-btn {{ !request('category') || request('category') == 'all' ? 'active-filter' : '' }}"
                        onclick="setCategory('all')">{{ __('shop.all') }}</button>
                @foreach($categories as $category)
                  <button type="button" class="btn btn-sm btn-outline-success category-btn {{ request('category') == $category ? 'active-filter' : '' }}"
                          onclick="setCategory('{{ $category }}')">{{ $category }}</button>
                @endforeach
              </div>
              <input type="hidden" name="category" id="categoryInput" value="{{ request('category', 'all') }}">

              <!-- Price Range -->
              <h6 class="mb-3">{{ __('shop.price_range') }}</h6>
              <div class="row g-2 mb-4">
                <div class="col-6">
                  <input type="number" name="min_price" id="min_price" class="form-control form-control-sm" placeholder="{{ __('shop.min') }}" value="{{ request('min_price') }}" min="0" step="0.01">
                </div>
                <div class="col-6">
                  <input type="number" name="max_price" id="max_price" class="form-control form-control-sm" placeholder="{{ __('shop.max') }}" value="{{ request('max_price') }}" min="0" step="0.01">
                </div>
              </div>

              <!-- Stock Filter -->
              <div class="mb-4">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="in_stock" id="in_stock"
                         {{ request('in_stock') ? 'checked' : '' }} onchange="applyFilters()">
                  <label class="form-check-label" for="in_stock">
                    {{ __('shop.in_stock_only') }}
                  </label>
                </div>
              </div>

              <!-- Clear Filters -->
              <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="clearFilters()">
                {{ __('shop.clear_all_filters') }}
              </button>
            </form>
          </div>
        </div>
      </div>

      <!-- Products Grid -->
      <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h4 class="mb-0">{{ __('shop.products') }}
            <small class="text-muted" id="resultsCount">
              @if(request()->hasAny(['category', 'min_price', 'max_price', 'query', 'in_stock']))
                ({{ $products->total() }} {{ __('shop.results') }})
              @endif
            </small>
          </h4>
          <div class="d-flex">
            <form method="GET" action="{{ route('shop.index') }}" class="d-flex">
              <!-- Preserve other filters -->
              @foreach(request()->except(['sort', 'page']) as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
              @endforeach

              <select name="sort" class="form-select me-2" style="width: auto;" onchange="this.form.submit()">
                <option value="">{{ __('shop.sort_by') }}</option>
                <option value="price_low_high" {{ request('sort') == 'price_low_high' ? 'selected' : '' }}>{{ __('shop.price_low_high') }}</option>
                <option value="price_high_low" {{ request('sort') == 'price_high_low' ? 'selected' : '' }}>{{ __('shop.price_high_low') }}</option>
                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>{{ __('shop.name_asc') }}</option>
                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>{{ __('shop.name_desc') }}</option>
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ __('shop.newest_first') }}</option>
              </select>
            </form>
          </div>
        </div>

        <!-- Active Filters Display -->
        <div id="activeFilters" class="mb-3 text-center">
          @if(request()->hasAny(['category', 'min_price', 'max_price', 'query', 'in_stock']))
            <small class="text-muted">{{ __('shop.active_filters') }}</small>
            @if(request('category') && request('category') != 'all')
              <span class="badge bg-success me-1">{{ request('category') }}</span>
            @endif
            @if(request('min_price'))
              <span class="badge bg-success me-1">{{ __('shop.min_price', ['price' => request('min_price')]) }}</span>
            @endif
            @if(request('max_price'))
              <span class="badge bg-success me-1">{{ __('shop.max_price', ['price' => request('max_price')]) }}</span>
            @endif
            @if(request('query'))
              <span class="badge bg-success me-1">{{ __('shop.search_query', ['query' => request('query')]) }}</span>
            @endif
            @if(request('in_stock'))
              <span class="badge bg-success me-1">{{ __('shop.in_stock_only') }}</span>
            @endif
          @endif
        </div>

        <div id="productsContainer">
          @include('shop.partials.products', ['products' => $products])
        </div>

        <!-- Pagination -->
        <div id="paginationContainer" class="mt-5">
          @if($products->hasPages())
            <nav aria-label="{{ __('shop.page_navigation') }}">
              {{ $products->appends(request()->query())->links('pagination::bootstrap-4') }}
            </nav>
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Live search functionality with AJAX
    let searchTimeout;
    let isLoading = false;
    const searchInput = document.getElementById('searchInput');
    const searchForm = document.getElementById('searchForm');
    const filterForm = document.getElementById('filterForm');
    const productsContainer = document.getElementById('productsContainer');
    const paginationContainer = document.getElementById('paginationContainer');
    const resultsCount = document.getElementById('resultsCount');

    if (searchInput) {
      searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);

        // Debounce the search - wait 500ms after user stops typing
        searchTimeout = setTimeout(function() {
          performLiveSearch();
        }, 500);
      });

      // Also trigger search on Enter key
      searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
          e.preventDefault();
          clearTimeout(searchTimeout);
          performLiveSearch();
        }
      });
    }

    function performLiveSearch() {
      if (isLoading) return;

      const query = searchInput.value.trim();

      // Get current filter values
      const category = document.getElementById('categoryInput')?.value || '';
      const minPrice = document.getElementById('min_price')?.value || '';
      const maxPrice = document.getElementById('max_price')?.value || '';
      const inStock = document.getElementById('in_stock')?.checked ? '1' : '';
      const sort = new URLSearchParams(window.location.search).get('sort') || '';

      // Build query parameters
      const params = new URLSearchParams();
      if (query) params.set('query', query);
      if (category && category !== 'all') params.set('category', category);
      if (minPrice) params.set('min_price', minPrice);
      if (maxPrice) params.set('max_price', maxPrice);
      if (inStock) params.set('in_stock', inStock);
      if (sort) params.set('sort', sort);

      // Update URL without reload
      const newUrl = '{{ route("shop.index") }}' + (params.toString() ? '?' + params.toString() : '');
      window.history.pushState({}, '', newUrl);

      // Show loading state
      isLoading = true;
      productsContainer.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-success" role="status"><span class="visually-hidden">Loading...</span></div></div>';

      // Make AJAX request
      fetch(newUrl, {
        method: 'GET',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
        }
      })
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        isLoading = false;

        // Update products
        productsContainer.innerHTML = data.products_html;

        // Update pagination
        paginationContainer.innerHTML = data.pagination_html || '';

        // Update active filters
        const activeFiltersEl = document.getElementById('activeFilters');
        if (activeFiltersEl) {
          if (data.active_filters_html) {
            activeFiltersEl.innerHTML = data.active_filters_html;
            activeFiltersEl.style.display = 'block';
          } else {
            activeFiltersEl.innerHTML = '';
            activeFiltersEl.style.display = 'none';
          }
        }

        // Update results count
        if (data.has_filters && data.results_count !== undefined) {
          resultsCount.textContent = `(${data.results_count} {{ __('shop.results') }})`;
        } else {
          resultsCount.textContent = '';
        }

        // Scroll to top of products section smoothly
        productsContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
      })
      .catch(error => {
        isLoading = false;
        console.error('Error:', error);
        productsContainer.innerHTML = '<div class="col-12 text-center py-5"><p class="text-danger">Error loading products. Please try again.</p></div>';
      });
    }

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
      if (searchInput) searchInput.value = '';

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
