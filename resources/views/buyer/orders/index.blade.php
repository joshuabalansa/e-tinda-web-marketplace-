@extends('layouts.dashboard')

@section('content')
<div class="main-content">
    <div class="row">
        <div class="col-md-6 col-sm-8 clearfix">
            <h2><i class="entypo-basket"></i> My Orders</h2>
            <p class="text-muted">View and track your order history</p>
        </div>

        <div class="col-md-6 col-sm-4 clearfix">
            <div class="pull-right">
                <a href="{{ route('shop.index') }}" class="btn btn-success">
                    <i class="entypo-shop"></i> Continue Shopping
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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="panel-title">Order History</div>
                </div>
                <div class="panel-body">
                    @if($orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Date</th>
                                        <th>Products</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                    <tr>
                                        <td>
                                            <strong>#{{ $order->id }}</strong>
                                        </td>
                                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                                        <td>
                                            @foreach($order->items->take(2) as $item)
                                                <div class="small">
                                                    {{ $item->product->name ?? 'N/A' }}
                                                    <span class="text-muted">({{ $item->quantity }}x)</span>
                                                </div>
                                            @endforeach
                                            @if($order->items->count() > 2)
                                                <div class="small text-muted">
                                                    +{{ $order->items->count() - 2 }} more items
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>₱{{ number_format($order->total, 2) }}</strong>
                                        </td>
                                        <td>
                                            <span class="label label-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'info') }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('buyer.orders.show', $order->id) }}" class="btn btn-sm btn-primary">
                                                <i class="entypo-eye"></i> View Details
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="text-center">
                            {{ $orders->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="entypo-basket" style="font-size: 4rem; color: #ccc;"></i>
                            <h4 class="text-muted mt-3">No Orders Yet</h4>
                            <p class="text-muted">You haven't placed any orders yet.</p>
                            <a href="{{ route('shop.index') }}" class="btn btn-success btn-lg">
                                <i class="entypo-shop"></i> Start Shopping
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
