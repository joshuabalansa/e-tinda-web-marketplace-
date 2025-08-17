@extends('layouts.farmer')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4>Order Details #{{ $order->id }}</h4>
                </div>
                <div class="panel-options">
                    <span class="badge badge-{{ getStatusBadgeClass($order->status) }}">
                        {{ ucfirst($order->status) }}
                    </span>
                    <a href="{{ route('farmer.orders.index') }}">
                        <i class="entypo-left-open"></i> Back to Orders
                    </a>
                </div>
            </div>
            <div class="panel-body">
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

                <div class="row">
                    <!-- Order Information -->
                    <div class="col-lg-8">
                        <div class="panel panel-default" data-collapsed="0">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <h5>Your Products in This Order</h5>
                                </div>
                            </div>
                            <div class="panel-body">
                                @foreach($farmerOrderItems as $item)
                                    <div class="well" style="margin-bottom: 15px;">
                                        <div class="row">
                                            <div class="col-md-2">
                                                @if($item->product->image_url)
                                                    <img src="{{ asset('storage/' . $item->product->image_url) }}"
                                                         alt="{{ $item->product->name }}"
                                                         class="img-responsive img-rounded">
                                                @else
                                                    <div class="text-center" style="height: 80px; background: #f5f5f5; border-radius: 5px; display: flex; align-items: center; justify-content: center;">
                                                        <i class="entypo-image" style="font-size: 24px; color: #ccc;"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <h5 style="margin-top: 0;">{{ $item->product->name }}</h5>
                                                <p class="text-muted">{{ $item->product->description }}</p>
                                                <small class="text-muted">Category: {{ $item->product->category }}</small>
                                            </div>
                                            <div class="col-md-2 text-center">
                                                <span class="badge badge-info">{{ $item->quantity }}</span>
                                                <div class="text-muted">
                                                    <small>{{ $item->product->unit_type }}</small>
                                                </div>
                                            </div>
                                            <div class="col-md-2 text-right">
                                                <strong>₱{{ number_format($item->price, 2) }}</strong>
                                                <div class="text-muted">
                                                    <small>Total: ₱{{ number_format($item->price * $item->quantity, 2) }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Order Summary -->
                                <div class="row" style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee;">
                                    <div class="col-md-6">
                                        <h6><strong>Order Summary</strong></h6>
                                        <table class="table table-condensed">
                                            <tr>
                                                <td>Subtotal (Your Products):</td>
                                                <td class="text-right">₱{{ number_format($farmerOrderItems->sum(function($item) { return $item->price * $item->quantity; }), 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Shipping:</td>
                                                <td class="text-right">₱{{ number_format($order->shipping, 2) }}</td>
                                            </tr>
                                            <tr class="active">
                                                <td><strong>Order Total:</strong></td>
                                                <td class="text-right"><strong>₱{{ number_format($order->total, 2) }}</strong></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <h6><strong>Payment Information</strong></h6>
                                        <p class="text-muted">Payment Method: <span class="text-dark">Cash on Delivery</span></p>
                                        <p class="text-muted">Payment Status: <span class="badge badge-warning">Pending</span></p>
                                        <p class="text-muted">Order Date: {{ $order->created_at->format('M d, Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Information & Actions -->
                    <div class="col-lg-4">
                        <!-- Customer Information -->
                        <div class="panel panel-default" data-collapsed="0">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <h5>Customer Information</h5>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="control-label"><strong>Name:</strong></label>
                                    <p>{{ $order->first_name }} {{ $order->last_name }}</p>
                                </div>
                                <div class="form-group">
                                    <label class="control-label"><strong>Email:</strong></label>
                                    <p>{{ $order->email }}</p>
                                </div>
                                <div class="form-group">
                                    <label class="control-label"><strong>Phone:</strong></label>
                                    <p>{{ $order->phone }}</p>
                                </div>
                                <div class="form-group">
                                    <label class="control-label"><strong>Shipping Address:</strong></label>
                                    <p>
                                        {{ $order->address }}<br>
                                        {{ $order->city }}, {{ $order->state }} {{ $order->zip }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Order Actions -->
                        <div class="panel panel-default" data-collapsed="0">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <h5>Order Actions</h5>
                                </div>
                            </div>
                            <div class="panel-body">
                                <form action="{{ route('farmer.orders.updateStatus', $order) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="form-group">
                                        <label for="status" class="control-label"><strong>Update Status:</strong></label>
                                        <select name="status" id="status" class="form-control" onchange="this.form.submit()">
                                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </div>
                                </form>

                                <hr>

                                <!-- Quick Action Buttons -->
                                <div class="form-group">
                                    @if($order->status === 'pending')
                                        <button type="button" class="btn btn-warning btn-block"
                                                onclick="updateOrderStatus('processing')">
                                            <i class="entypo-cog"></i> Start Processing
                                        </button>
                                    @elseif($order->status === 'processing')
                                        <button type="button" class="btn btn-primary btn-block"
                                                onclick="updateOrderStatus('shipped')">
                                            <i class="entypo-paper-plane"></i> Mark as Shipped
                                        </button>
                                    @elseif($order->status === 'shipped')
                                        <button type="button" class="btn btn-success btn-block"
                                                onclick="updateOrderStatus('delivered')">
                                            <i class="entypo-check"></i> Mark as Delivered
                                        </button>
                                    @endif

                                    @if($order->status === 'pending')
                                        <button type="button" class="btn btn-danger btn-block"
                                                onclick="updateOrderStatus('cancelled')">
                                            <i class="entypo-cancel"></i> Cancel Order
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Order Timeline -->
                        <div class="panel panel-default" data-collapsed="0">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <h5>Order Timeline</h5>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="timeline">
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-success"></div>
                                        <div class="timeline-content">
                                            <h6 class="timeline-title">Order Placed</h6>
                                            <p class="timeline-text">{{ $order->created_at->format('M d, Y H:i') }}</p>
                                        </div>
                                    </div>

                                    @if($order->status !== 'pending')
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-info"></div>
                                            <div class="timeline-content">
                                                <h6 class="timeline-title">Order Processing</h6>
                                                <p class="timeline-text">Order is being prepared</p>
                                            </div>
                                        </div>
                                    @endif

                                    @if(in_array($order->status, ['shipped', 'delivered']))
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-primary"></div>
                                            <div class="timeline-content">
                                                <h6 class="timeline-title">Order Shipped</h6>
                                                <p class="timeline-text">Order has been shipped</p>
                                            </div>
                                        </div>
                                    @endif

                                    @if($order->status === 'delivered')
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-success"></div>
                                            <div class="timeline-content">
                                                <h6 class="timeline-title">Order Delivered</h6>
                                                <p class="timeline-text">Order has been delivered</p>
                                            </div>
                                        </div>
                                    @endif

                                    @if($order->status === 'cancelled')
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-danger"></div>
                                            <div class="timeline-content">
                                                <h6 class="timeline-title">Order Cancelled</h6>
                                                <p class="timeline-text">Order has been cancelled</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 20px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -30px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.timeline-content {
    margin-left: 10px;
}

.timeline-title {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 5px;
}

.timeline-text {
    font-size: 12px;
    color: #6c757d;
    margin-bottom: 0;
}

.bg-success { background-color: #00a651 !important; }
.bg-info { background-color: #359ade !important; }
.bg-primary { background-color: #2b303a !important; }
.bg-danger { background-color: #ee4749 !important; }
</style>
@endpush

@push('scripts')
<script>
function updateOrderStatus(status) {
    document.getElementById('status').value = status;
    document.getElementById('status').form.submit();
}

// Auto-hide alerts after 5 seconds
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        if (alert.classList.contains('alert-success') || alert.classList.contains('alert-danger')) {
            alert.style.display = 'none';
        }
    });
}, 5000);
</script>
@endpush

@php
function getStatusBadgeClass($status) {
    switch ($status) {
        case 'pending':
            return 'warning';
        case 'processing':
            return 'info';
        case 'shipped':
            return 'primary';
        case 'delivered':
            return 'success';
        case 'cancelled':
            return 'danger';
        default:
            return 'secondary';
    }
}
@endphp