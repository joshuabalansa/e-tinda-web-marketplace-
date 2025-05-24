@extends('layouts.shop')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle text-success fa-4x mb-4"></i>
                    <h2 class="mb-4">Order Placed Successfully!</h2>
                    <p class="lead mb-4">Thank you for your purchase. Your order has been received.</p>

                    <div class="order-details text-start mb-4">
                        <h4 class="mb-3">Order Details</h4>
                        <p><strong>Order Number:</strong> #{{ $order->id }}</p>
                        <p><strong>Date:</strong> {{ $order->created_at->format('F j, Y') }}</p>
                        <p><strong>Total Amount:</strong> ₱{{ number_format($order->total, 2) }}</p>

                        <h5 class="mt-4 mb-3">Shipping Information</h5>
                        <p>{{ $order->first_name }} {{ $order->last_name }}</p>
                        <p>{{ $order->address }}</p>
                        <p>{{ $order->city }}, {{ $order->state }} {{ $order->zip }}</p>
                        <p>{{ $order->email }}</p>
                        <p>{{ $order->phone }}</p>

                        <h5 class="mt-4 mb-3">Order Items</h5>
                        @foreach($order->items as $item)
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ $item->product->name }} x {{ $item->quantity }}</span>
                                <span>₱{{ number_format($item->price * $item->quantity, 2) }}</span>
                            </div>
                        @endforeach

                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>₱{{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping</span>
                            <span>₱{{ number_format($order->shipping, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Total</strong>
                            <strong class="text-success">₱{{ number_format($order->total, 2) }}</strong>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('shop.index') }}" class="btn btn-success me-2">Continue Shopping</a>
                        <a href="{{ route('buyer.dashboard') }}" class="btn btn-outline-success">View Orders</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection