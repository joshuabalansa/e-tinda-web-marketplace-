@extends('layouts.dashboard')

@section('content')
<div class="main-content">
    <div class="row">
        <div class="col-md-6 col-sm-8 clearfix">
            <h2><i class="entypo-basket"></i> Order Details</h2>
            <p class="text-muted">Order #{{ $order->id }}</p>
        </div>

        <div class="col-md-6 col-sm-4 clearfix">
            <div class="pull-right">
                <a href="{{ route('buyer.orders') }}" class="btn btn-default">
                    <i class="entypo-left"></i> Back to Orders
                </a>
            </div>
        </div>
    </div>

    <hr />

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Order Information -->
        <div class="col-md-8">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="panel-title">Order Information</div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Order Details</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Order ID:</strong></td>
                                    <td>#{{ $order->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Order Date:</strong></td>
                                    <td>{{ $order->created_at->format('M d, Y \a\t g:i A') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="label label-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'info') }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Shipping Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $order->first_name }} {{ $order->last_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $order->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Phone:</strong></td>
                                    <td>{{ $order->phone }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Address:</strong></td>
                                    <td>{{ $order->address }}, {{ $order->city }}, {{ $order->state }} {{ $order->zip }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="panel-title">Order Items</div>
                </div>
                <div class="panel-body">
                    @foreach($order->items as $item)
                    <div class="row mb-3 pb-3 border-bottom">
                        <div class="col-md-2">
                            @if($item->product && $item->product->image_url)
                                <img src="{{ asset('storage/' . $item->product->image_url) }}"
                                     alt="{{ $item->product->name }}"
                                     class="img-responsive"
                                     style="max-width: 80px; height: auto;">
                            @else
                                <div class="bg-light text-center py-3" style="width: 80px; height: 60px;">
                                    <i class="entypo-image text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h5>{{ $item->product->name ?? 'Product Unavailable' }}</h5>
                            <p class="text-muted small">
                                @if($item->product)
                                    Sold by: {{ $item->product->user->name ?? 'Unknown Seller' }}
                                @endif
                            </p>
                        </div>
                        <div class="col-md-2 text-center">
                            <span class="badge">{{ $item->quantity }}x</span>
                        </div>
                        <div class="col-md-2 text-right">
                            <strong>₱{{ number_format($item->price, 2) }}</strong>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-md-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="panel-title">Order Summary</div>
                </div>
                <div class="panel-body">
                    <table class="table table-borderless">
                        <tr>
                            <td>Subtotal:</td>
                            <td class="text-right">₱{{ number_format($order->subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Shipping:</td>
                            <td class="text-right">₱{{ number_format($order->shipping, 2) }}</td>
                        </tr>
                        <tr class="border-top">
                            <td><strong>Total:</strong></td>
                            <td class="text-right"><strong>₱{{ number_format($order->total, 2) }}</strong></td>
                        </tr>
                    </table>

                    <div class="text-center mt-3">
                        <a href="{{ route('shop.index') }}" class="btn btn-success btn-block">
                            <i class="entypo-shop"></i> Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
