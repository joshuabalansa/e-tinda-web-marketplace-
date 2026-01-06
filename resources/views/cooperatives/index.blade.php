@extends('layouts.shop')
@section('content')
  <style>
    .cooperative-card {
      transition: transform 0.2s;
      height: 100%;
    }
    .cooperative-card:hover {
      transform: translateY(-5px);
    }
    .cooperative-link {
      text-decoration: none;
      color: inherit;
    }
    .cooperative-logo {
      height: 150px;
      object-fit: cover;
      background-color: #f8f9fa;
    }
  </style>

  <header class="bg-light py-5">
    <div class="container text-center">
      <h1 class="display-4 fw-bold text-success">Cooperative Storefronts</h1>
      <p class="lead">Shop from local farmer cooperatives and associations</p>
    </div>
  </header>

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

    @if(isset($error))
      <div class="alert alert-warning alert-dismissible fade show" role="alert">
        {{ $error }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <!-- Cooperatives Grid -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
      @forelse($cooperatives as $cooperative)
        <div class="col">
          <a href="{{ route('cooperatives.show', $cooperative->slug ?? $cooperative->association_id) }}" class="cooperative-link">
            <div class="card h-100 cooperative-card shadow-sm">
              @if($cooperative->banner_url)
                <img src="{{ $cooperative->getBannerUrl() }}" class="card-img-top cooperative-logo" alt="{{ $cooperative->name }}">
              @elseif($cooperative->logo_url)
                <div class="text-center p-3">
                  <img src="{{ $cooperative->getLogoUrl() }}" class="cooperative-logo" alt="{{ $cooperative->name }}" style="max-width: 100%; height: auto;">
                </div>
              @else
                <div class="cooperative-logo d-flex align-items-center justify-content-center bg-success text-white">
                  <i class="fas fa-users fa-3x"></i>
                </div>
              @endif
              <div class="card-body d-flex flex-column">
                <h5 class="card-title">{{ $cooperative->name }}</h5>
                @if($cooperative->description)
                  <p class="card-text text-muted small flex-grow-1">{{ Str::limit($cooperative->description, 100) }}</p>
                @endif
                <div class="mt-auto">
                  <p class="card-text mb-1">
                    <small class="text-muted">
                      <i class="fas fa-map-marker-alt"></i> {{ Str::limit($cooperative->location, 50) }}
                    </small>
                  </p>
                  <p class="card-text mb-0">
                    <small class="text-muted">
                      <i class="fas fa-users"></i> {{ $cooperative->farmers_count ?? $cooperative->total_members }} {{ $cooperative->farmers_count == 1 ? 'Farmer' : 'Farmers' }}
                    </small>
                  </p>
                  @if($cooperative->featured)
                    <span class="badge bg-warning text-dark mt-2">
                      <i class="fas fa-star"></i> Featured
                    </span>
                  @endif
                </div>
              </div>
            </div>
          </a>
        </div>
      @empty
        <div class="col-12 text-center py-5">
          <i class="fas fa-store fa-3x text-muted mb-3"></i>
          <h3>No Cooperatives Found</h3>
          <p class="text-muted">Check back later for cooperative storefronts.</p>
        </div>
      @endforelse
    </div>

    <!-- Pagination -->
    @if($cooperatives->hasPages())
      <div class="mt-4">
        {{ $cooperatives->links('pagination::bootstrap-4') }}
      </div>
    @endif
  </div>
@endsection


