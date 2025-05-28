@extends('layouts.shop')
@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <i class="fas fa-check-circle text-success fa-4x mb-3"></i>
        <h1>Order Placed Successfully!</h1>
        <p class="lead">Thank you for your order. Your order number is #{{ $order->id }}</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Order Details</h5>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Shipping Information</h6>
                            <p class="mb-1">{{ $order->shipping_address }}</p>
                            <p class="mb-1">Contact: {{ $order->contact_number }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Payment Information</h6>
                            <p class="mb-1">Payment Method: {{ strtoupper($order->payment_method) }}</p>
                            <p class="mb-1">Order Status: {{ ucfirst($order->status) }}</p>
                        </div>
                    </div>

                    <h6 class="mb-3">Order Items</h6>
                    @foreach($order->items as $item)
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <h6 class="mb-1">{{ $item->product->name }}</h6>
                                <p class="text-muted mb-0">
                                    {{ $item->quantity }} {{ $item->product->unit_type }} x ₱{{ number_format($item->price, 2) }}
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <p class="mb-0">₱{{ number_format($item->subtotal, 2) }}</p>
                            </div>
                        </div>
                        @if(!$loop->last)
                            <hr>
                        @endif
                    @endforeach

                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Total Amount</strong>
                        <strong>₱{{ number_format($order->total_amount, 2) }}</strong>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('shop.index') }}" class="btn btn-primary">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
</div>
@endsection