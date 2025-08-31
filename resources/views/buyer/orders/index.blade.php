@extends('layouts.shop')

@section('content')
<!-- Orders Header Section -->
<div class="container-fluid bg-light py-5">
    <div class="container">
        <h1 class="display-4 text-success fw-bold">My Orders</h1>
        <p class="lead">View and track your order history</p>
    </div>
</div>

<!-- Orders Content Section -->
<div class="container my-5">
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

    <div class="row">
        <div class="col-md-12">
            @if($orders->count() > 0)
                <div class="row">
                    @foreach($orders as $order)
                    <div class="col-lg-6 col-md-12 mb-4">
                        <div class="card h-100 border-success">
                            <div class="card-header bg-success text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        <i class="fas fa-shopping-bag"></i> Order #{{ $order->id }}
                                    </h5>
                                    <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'info') }} rounded-pill">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                            </div>

                            <div class="card-body d-flex flex-column">
                                <div class="mb-3">
                                    <p class="text-muted mb-1">
                                        <i class="fas fa-calendar text-success"></i>
                                        <strong>Order Date:</strong> {{ $order->created_at->format('M d, Y') }}
                                    </p>
                                    <p class="text-muted mb-1">
                                        <i class="fas fa-clock text-success"></i>
                                        <strong>Order Time:</strong> {{ $order->created_at->format('h:i A') }}
                                    </p>
                                </div>

                                <div class="mb-3">
                                    <h6 class="text-success fw-bold">Products:</h6>
                                    @foreach($order->items->take(3) as $item)
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="small">
                                                {{ $item->product->name ?? 'N/A' }}
                                            </span>
                                            <span class="badge bg-light text-dark">
                                                {{ $item->quantity }}x
                                            </span>
                                        </div>
                                    @endforeach
                                    @if($order->items->count() > 3)
                                        <div class="text-muted small">
                                            +{{ $order->items->count() - 3 }} more items
                                        </div>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="h5 text-success fw-bold mb-0">Total Amount:</span>
                                        <span class="h4 text-success fw-bold">₱{{ number_format($order->total, 2) }}</span>
                                    </div>
                                </div>

                                <div class="mt-auto">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <a href="{{ route('buyer.orders.show', $order->id) }}" class="btn btn-success w-100">
                                                <i class="fas fa-eye"></i> View Details
                                            </a>
                                        </div>
                                        <div class="col-6">
                                            <a href="{{ route('shop.index') }}" class="btn btn-outline-success w-100">
                                                <i class="fas fa-shopping-cart"></i> Reorder
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-shopping-bag text-muted" style="font-size: 4rem;"></i>
                    </div>
                    <h3 class="text-muted mb-3">No Orders Yet</h3>
                    <p class="text-muted mb-4">You haven't placed any orders yet. Start shopping to see your order history here.</p>
                    <a href="{{ route('shop.index') }}" class="btn btn-success btn-lg">
                        <i class="fas fa-shopping-bag"></i> Start Shopping
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
