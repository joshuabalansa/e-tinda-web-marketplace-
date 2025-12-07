<div class="text-center">
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
</div>

