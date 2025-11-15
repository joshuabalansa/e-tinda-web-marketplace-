@extends('layouts.shop')
@section('content')
<div class="container py-5">
    <h1 class="mb-4">{{ __('shop.shopping_cart') }}</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(count($items) > 0)
        <div class="row">
            <div class="col-md-8">
                <!-- Cart Items -->
                <div class="card mb-4">
                    <div class="card-body">
                        @foreach($items as $item)
                            <div class="row mb-4">
                                <div class="col-md-2">
                                    <img src="{{ $item['image'] }}" class="img-fluid rounded" alt="{{ $item['name'] }}">
                                </div>
                                <div class="col-md-6">
                                    <h5 class="mb-1">{{ $item['name'] }}</h5>
                                    <p class="text-muted mb-0">₱{{ number_format($item['price'], 2) }}/{{ $item['unit'] }}</p>
                                </div>
                                <div class="col-md-2">
                                    <form action="{{ route('cart.update') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                        <div class="input-group">
                                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="0" max="99" class="form-control form-control-sm" onchange="this.form.submit()">
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-2 text-end">
                                    <p class="mb-0">₱{{ number_format($item['subtotal'], 2) }}</p>
                                    <form action="{{ route('cart.remove', $item['id']) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-link text-danger p-0">{{ __('shop.remove') }}</button>
                                    </form>
                                </div>
                            </div>
                            @if(!$loop->last)
                                <hr>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Continue Shopping -->
                <a href="{{ route('shop.index') }}" class="btn btn-success">
                    <i class="fas fa-arrow-left me-2"></i>{{ __('shop.continue_shopping') }}
                </a>
            </div>

            <div class="col-md-4">
                <!-- Order Summary -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('shop.order_summary') }}</h5>
                        <div class="d-flex justify-content-between mb-3">
                            <span>{{ __('shop.subtotal') }}</span>
                            <span>₱{{ number_format($total, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>{{ __('shop.shipping') }}</span>
                            <span>{{ __('shop.free') }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>{{ __('shop.total') }}</strong>
                            <strong>₱{{ number_format($total, 2) }}</strong>
                        </div>
                        <a href="{{ route('checkout.index') }}" class="btn btn-success w-100">
                            {{ __('shop.proceed_to_checkout') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
            <h3>{{ __('shop.your_cart_empty') }}</h3>
            <p class="text-muted">{{ __('shop.add_products_cart') }}</p>
            <a href="{{ route('shop.index') }}" class="btn btn-success">
                {{ __('shop.continue_shopping') }}
            </a>
        </div>
    @endif
</div>
@endsection