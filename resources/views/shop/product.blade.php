@extends('layouts.shop')
@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Product Image -->
        <div class="col-md-6">
            <div class="card border-0">
                <img src="{{ $product['image'] }}" class="img-fluid rounded" alt="{{ $product['name'] }}">
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-md-6">
            <h1 class="mb-3">{{ $product['name'] }}</h1>

            <!-- Rating -->
            <div class="rating mb-3">
                @for($i = 0; $i < 5; $i++)
                    @if($i < floor($product['rating']))
                        <i class="fas fa-star text-warning"></i>
                    @elseif($i < ceil($product['rating']) && $product['rating'] - floor($product['rating']) >= 0.5)
                        <i class="fas fa-star-half-alt text-warning"></i>
                    @else
                        <i class="far fa-star text-warning"></i>
                    @endif
                @endfor
                <span class="ms-2 text-muted">({{ $product['review_count'] }} reviews)</span>
            </div>

            <!-- Price -->
            <h2 class="text-success mb-4">₱{{ number_format($product['price'], 2) }}/{{ $product['unit'] }}</h2>

            <!-- Description -->
            <p class="mb-4">{{ $product['description'] }}</p>

            <!-- Vendor Info -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Vendor Information</h5>
                    <p class="mb-1"><strong>Farm:</strong> {{ $product['vendor'] }}</p>
                    <p class="mb-1"><strong>Location:</strong> {{ $product['location'] }}</p>
                    <p class="mb-0"><strong>Certification:</strong> {{ $product['certification'] }}</p>
                </div>
            </div>

            <!-- Add to Cart -->
            <form action="{{ route('cart.add') }}" method="POST" class="mb-4">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="input-group" style="width: 130px;">
                            <button type="button" class="btn btn-outline-secondary" onclick="decrementQuantity()">-</button>
                            <input type="number" class="form-control text-center" id="quantity" name="quantity" value="1" min="1">
                            <button type="button" class="btn btn-outline-secondary" onclick="incrementQuantity()">+</button>
                        </div>
                    </div>
                    <div class="col">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-cart-plus me-2"></i>Add to Cart
                        </button>
                    </div>
                </div>
            </form>

            <!-- Additional Info -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Product Details</h5>
                    <ul class="list-unstyled mb-0">
                        <li><strong>Category:</strong> {{ $product['category'] }}</li>
                        <li><strong>Stock:</strong> {{ $product['stock'] }} {{ $product['unit'] }}s available</li>
                        <li><strong>Harvest Date:</strong> {{ $product['harvest_date'] }}</li>
                        <li><strong>Storage:</strong> {{ $product['storage'] }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function incrementQuantity() {
    const input = document.getElementById('quantity');
    input.value = parseInt(input.value) + 1;
}

function decrementQuantity() {
    const input = document.getElementById('quantity');
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
    }
}
</script>
@endsection