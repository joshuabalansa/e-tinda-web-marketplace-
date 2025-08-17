@extends('layouts.farmer')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4>Orders Management</h4>
                </div>
                <div class="panel-options">
                    <a href="{{ route('farmer.dashboard') }}" data-rel="collapse">
                        <i class="entypo-down-open"></i>
                    </a>
                </div>
            </div>
            <div class="panel-body">
                <!-- Statistics Cards -->
                <div class="row">
                    <div class="col-sm-3">
                        <div class="tile-progress tile-primary">
                            <div class="tile-header">
                                <h3>Total Orders</h3>
                                <span>All time orders</span>
                            </div>
                            <div class="tile-progressbar">
                                <span data-fill="{{ $stats['total_orders'] > 0 ? '100' : '0' }}%"></span>
                            </div>
                            <div class="tile-footer">
                                <h4>{{ $stats['total_orders'] }}</h4>
                                <span>orders received</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="tile-progress tile-warning">
                            <div class="tile-header">
                                <h3>Pending Orders</h3>
                                <span>Awaiting processing</span>
                            </div>
                            <div class="tile-progressbar">
                                <span data-fill="{{ $stats['total_orders'] > 0 ? ($stats['pending_orders'] / $stats['total_orders'] * 100) : '0' }}%"></span>
                            </div>
                            <div class="tile-footer">
                                <h4>{{ $stats['pending_orders'] }}</h4>
                                <span>need attention</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="tile-progress tile-info">
                            <div class="tile-header">
                                <h3>Processing</h3>
                                <span>Currently processing</span>
                            </div>
                            <div class="tile-progressbar">
                                <span data-fill="{{ $stats['total_orders'] > 0 ? ($stats['processing_orders'] / $stats['total_orders'] * 100) : '0' }}%"></span>
                            </div>
                            <div class="tile-footer">
                                <h4>{{ $stats['processing_orders'] }}</h4>
                                <span>in progress</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="tile-progress tile-success">
                            <div class="tile-header">
                                <h3>Total Revenue</h3>
                                <span>From your products</span>
                            </div>
                            <div class="tile-progressbar">
                                <span data-fill="100%"></span>
                            </div>
                            <div class="tile-footer">
                                <h4>₱{{ number_format($stats['total_revenue'], 2) }}</h4>
                                <span>earned</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4>Orders List</h4>
                </div>
                <div class="panel-options">
                    <select id="statusFilter" class="form-control input-sm">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="shipped">Shipped</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>
            <div class="panel-body no-padding">
                @if($orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-responsive">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Products</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                    <th>Order Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>
                                            <strong>#{{ $order->id }}</strong>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $order->first_name }} {{ $order->last_name }}</strong><br>
                                                <small class="text-muted">{{ $order->email }}</small><br>
                                                <small class="text-muted">{{ $order->phone }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $farmerItems = $order->items->filter(function($item) {
                                                    return $item->product->user_id === auth()->id();
                                                });
                                            @endphp
                                            @foreach($farmerItems as $item)
                                                <div class="mb-1">
                                                    <span class="badge badge-info">{{ $item->product->name }}</span>
                                                    <small class="text-muted">x{{ $item->quantity }} @ ₱{{ number_format($item->price, 2) }}</small>
                                                </div>
                                            @endforeach
                                        </td>
                                        <td>
                                            @php
                                                $farmerTotal = $farmerItems->sum(function($item) {
                                                    return $item->price * $item->quantity;
                                                });
                                            @endphp
                                            <strong>₱{{ number_format($farmerTotal, 2) }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ getStatusBadgeClass($order->status) }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $order->created_at->format('M d, Y H:i') }}
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('farmer.orders.show', $order) }}"
                                                   class="btn btn-sm btn-info">
                                                    <i class="entypo-eye"></i> View
                                                </a>
                                                @if($order->status === 'pending')
                                                    <button type="button"
                                                            class="btn btn-sm btn-warning"
                                                            onclick="updateOrderStatus({{ $order->id }}, 'processing')">
                                                        <i class="entypo-cog"></i> Process
                                                    </button>
                                                @elseif($order->status === 'processing')
                                                    <button type="button"
                                                            class="btn btn-sm btn-primary"
                                                            onclick="updateOrderStatus({{ $order->id }}, 'shipped')">
                                                        <i class="entypo-paper-plane"></i> Ship
                                                    </button>
                                                @elseif($order->status === 'shipped')
                                                    <button type="button"
                                                            class="btn btn-sm btn-success"
                                                            onclick="updateOrderStatus({{ $order->id }}, 'delivered')">
                                                        <i class="entypo-check"></i> Deliver
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="text-center" style="padding: 20px;">
                        {{ $orders->links() }}
                    </div>
                @else
                    <div class="text-center" style="padding: 40px;">
                        <i class="entypo-mail" style="font-size: 48px; color: #ccc;"></i>
                        <h5 style="color: #666;">No orders found</h5>
                        <p style="color: #999;">Orders containing your products will appear here.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusUpdateModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Order Status</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="statusUpdateForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <p>Are you sure you want to update the order status?</p>
                    <input type="hidden" id="newStatus" name="status">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateOrderStatus(orderId, status) {
    document.getElementById('newStatus').value = status;
    document.getElementById('statusUpdateForm').action = `/farmer/orders/${orderId}/status`;
    $('#statusUpdateModal').modal('show');
}

// Status filtering
document.getElementById('statusFilter').addEventListener('change', function() {
    const status = this.value;
    const rows = document.querySelectorAll('tbody tr');

    rows.forEach(row => {
        const statusCell = row.querySelector('td:nth-child(5) .badge');
        if (status === '' || statusCell.textContent.toLowerCase().includes(status.toLowerCase())) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Initialize tile progress bars
$(document).ready(function() {
    $('.tile-progressbar span').each(function() {
        var fill = $(this).data('fill');
        $(this).css('width', fill);
    });
});
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
