@extends('layouts.shop')
@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Product Image -->
        <div class="col-md-6">
            <div class="card border-0">
                <img src="{{ $productData['image'] }}" class="img-fluid rounded" alt="{{ $productData['name'] }}">
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-md-6">
            <h1 class="mb-3">{{ $productData['name'] }}</h1>

            <!-- Price -->
            <h2 class="text-success mb-4">₱{{ number_format($productData['price'], 2) }}/{{ $productData['unit'] }}</h2>

            <!-- Description -->
            <p class="mb-4">{{ $productData['description'] }}</p>

            <!-- Vendor Info -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Vendor Information</h5>
                    <p class="mb-1"><strong>Farm:</strong> {{ $productData['vendor'] }}</p>
                    <p class="mb-1"><strong>Location:</strong> {{ $productData['location'] }}</p>
                </div>
            </div>

            <!-- Add to Cart -->
            <form action="{{ route('cart.add') }}" method="POST" class="mb-4">
                @csrf
                <input type="hidden" name="product_id" value="{{ $productData['id'] }}">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="input-group" style="width: 130px;">
                            <button type="button" class="btn btn-outline-secondary" onclick="decrementQuantity()">-</button>
                            <input type="number" class="form-control text-center" id="quantity" name="quantity" value="1" min="1" max="{{ $productData['stock'] }}">
                            <button type="button" class="btn btn-outline-secondary" onclick="incrementQuantity()">+</button>
                        </div>
                    </div>
                    <div class="col">
                        <button type="submit" class="btn btn-success btn-lg" {{ $productData['stock'] <= 0 ? 'disabled' : '' }}>
                            <i class="fas fa-cart-plus me-2"></i>{{ $productData['stock'] <= 0 ? 'Out of Stock' : 'Add to Cart' }}
                        </button>
                    </div>
                </div>
            </form>

            <!-- Additional Info -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Product Details</h5>
                    <ul class="list-unstyled mb-0">
                        <li><strong>Category:</strong> {{ $productData['category'] }}</li>
                        <li><strong>Stock:</strong> {{ $productData['stock'] }} {{ $productData['unit'] }}s available</li>
                        <li><strong>Harvest Date:</strong> {{ $productData['harvest_date'] }}</li>
                        <li><strong>Storage:</strong> {{ $productData['storage'] }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="mt-5">
        <h3 class="mb-4">Related Products</h3>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            @foreach($relatedProducts as $relatedProduct)
            <div class="col">
                <a href="{{ route('shop.product', ['id' => $relatedProduct['id']]) }}" class="product-link">
                    <div class="card h-100 product-card">
                        <img src="{{ $relatedProduct['image'] }}" class="card-img-top" alt="{{ $relatedProduct['name'] }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $relatedProduct['name'] }}</h5>
                            <p class="card-text text-success mb-0">₱{{ number_format($relatedProduct['price'], 2) }}/{{ $relatedProduct['unit'] }}</p>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
function incrementQuantity() {
    const input = document.getElementById('quantity');
    const max = parseInt(input.getAttribute('max'));
    const currentValue = parseInt(input.value);
    if (currentValue < max) {
        input.value = currentValue + 1;
    }
}

function decrementQuantity() {
    const input = document.getElementById('quantity');
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
    }
}
</script>
@endsection