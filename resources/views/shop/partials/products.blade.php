<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
  @forelse($products as $product)
    <div class="col">
      <a href="{{ route('shop.product.show', $product['id']) }}" class="product-link">
        <div class="card h-100 product-card">
          @if(isset($product['certification']) && $product['certification'] === 'Organic Certified')
            <div class="badge bg-success position-absolute" style="top: 10px; right: 10px;">{{ __('shop.organic') }}</div>
          @endif
          @if($product['stock'] <= 0)
            <div class="badge bg-danger position-absolute" style="top: 10px; left: 10px;">{{ __('shop.out_of_stock') }}</div>
          @elseif($product['stock'] <= 5)
            <div class="badge bg-warning position-absolute" style="top: 10px; left: 10px;">{{ __('shop.low_stock') }}</div>
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
                  <i class="fas fa-cart-plus"></i> {{ $product['stock'] <= 0 ? __('shop.out_of_stock') : __('shop.add') }}
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
      <h3>{{ __('shop.no_products_found') }}</h3>
      <p class="text-muted">
        @if(request()->hasAny(['category', 'min_price', 'max_price', 'query', 'in_stock']))
          {{ __('shop.try_adjusting_filters') }}
        @else
          {{ __('shop.check_back_later') }}
        @endif
      </p>
      @if(request()->hasAny(['category', 'min_price', 'max_price', 'query', 'in_stock']))
        <button class="btn btn-outline-success" onclick="clearFilters()">{{ __('shop.clear_all_filters_btn') }}</button>
      @endif
    </div>
  @endforelse
</div>

