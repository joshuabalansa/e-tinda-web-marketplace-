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
                    @if($order->is_negotiation)
                        <span class="badge badge-warning">
                            <i class="entypo-hand"></i> Price Negotiation
                        </span>
                    @endif
                    <a href="{{ route('farmer.orders') }}">
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

                @if($order->is_negotiation)
                    <div class="alert alert-{{ $order->status === 'pending' ? 'warning' : 'info' }}">
                        <h5><i class="entypo-hand"></i> Price Negotiation Request</h5>
                        @if($order->status === 'pending')
                            <p><strong>Action Required:</strong> The buyer has requested a price negotiation for this order. Please review the proposed prices below and accept or reject the offer.</p>
                        @else
                            <p><strong>Negotiation Status:</strong> This order was created with a price negotiation request.
                            @if($order->status === 'cancelled')
                                The negotiation was rejected and the order was cancelled.
                            @else
                                The negotiation has been processed and the order is now {{ $order->status }}.
                            @endif
                            </p>
                        @endif
                        @if($order->negotiation_notes)
                            <div class="well well-sm mt-2" style="background-color: #fff3cd;">
                                <strong>Buyer's Message:</strong>
                                <p class="mb-0">{{ $order->negotiation_notes }}</p>
                            </div>
                        @endif
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
                                                <img src="{{ $item->product->getImageUrl() }}"
                                                     alt="{{ $item->product->name }}"
                                                     class="img-responsive img-rounded"
                                                     style="height: 80px; object-fit: cover;">
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
                                                @if($item->negotiated_price !== null)
                                                    <div>
                                                        <small class="text-muted text-decoration-line-through">₱{{ number_format($item->price, 2) }}</small>
                                                        <br>
                                                        <strong class="text-success">₱{{ number_format($item->negotiated_price, 2) }}</strong>
                                                        <br>
                                                        <small class="text-info">Negotiated</small>
                                                    </div>
                                                    <div class="text-muted mt-1">
                                                        <small>Total: ₱{{ number_format($item->negotiated_price * $item->quantity, 2) }}</small>
                                                        <br>
                                                        <small class="text-danger">Savings: ₱{{ number_format(($item->price - $item->negotiated_price) * $item->quantity, 2) }}</small>
                                                    </div>
                                                @else
                                                    <strong>₱{{ number_format($item->price, 2) }}</strong>
                                                    <div class="text-muted">
                                                        <small>Total: ₱{{ number_format($item->price * $item->quantity, 2) }}</small>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Order Summary -->
                                <div class="row" style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee;">
                                    <div class="col-md-6">
                                        <h6><strong>Your Order Summary</strong></h6>
                                        <table class="table table-condensed">
                                            <tr>
                                                <td>Your Products Subtotal:</td>
                                                <td class="text-right">
                                                    @php
                                                        $originalSubtotal = $farmerOrderItems->sum(function($item) { return $item->price * $item->quantity; });
                                                        $negotiatedSubtotal = $farmerOrderItems->sum(function($item) {
                                                            return ($item->negotiated_price ?? $item->price) * $item->quantity;
                                                        });
                                                    @endphp
                                                    @if($negotiatedSubtotal < $originalSubtotal)
                                                        <small class="text-muted text-decoration-line-through">₱{{ number_format($originalSubtotal, 2) }}</small><br>
                                                        <strong class="text-success">₱{{ number_format($negotiatedSubtotal, 2) }}</strong>
                                                    @else
                                                        ₱{{ number_format($originalSubtotal, 2) }}
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Shipping:</td>
                                                <td class="text-right">
                                                    @if($order->delivery_option === 'pickup')
                                                        <span class="text-success">Free (Pickup)</span>
                                                    @else
                                                        <span class="text-muted">Included in total order</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr class="active">
                                                <td><strong>Your Revenue:</strong></td>
                                                <td class="text-right">
                                                    <strong>₱{{ number_format($negotiatedSubtotal, 2) }}</strong>
                                                    @if($negotiatedSubtotal < $originalSubtotal)
                                                        <br><small class="text-danger">Reduced by ₱{{ number_format($originalSubtotal - $negotiatedSubtotal, 2) }}</small>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>

                                        @php
                                            $totalOrderItems = $order->items->count();
                                            $yourItems = $farmerOrderItems->count();
                                            $isMultiVendor = $totalOrderItems > $yourItems;
                                        @endphp

                                    </div>
                                    <div class="col-md-6">
                                        <h6><strong>Payment Information</strong></h6>
                                        <p class="text-muted">Payment Method: <span class="text-dark">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span></p>
                                        <p class="text-muted">Delivery Option: <span class="text-dark">{{ ucfirst($order->delivery_option) }}</span></p>
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
                                        @if($order->delivery_option === 'pickup')
                                            <span class="text-info"><i class="entypo-home"></i> Farm Pickup</span><br>
                                            <small class="text-muted">Customer will pick up from farm location</small>
                                        @else
                                            {{ $order->address }}<br>
                                            {{ $order->city }}, {{ $order->state }} {{ $order->zip }}
                                        @endif
                                    </p>
                                </div>

                                @if($order->special_instructions)
                                <div class="form-group">
                                    <label class="control-label"><strong>Special Instructions:</strong></label>
                                    <div class="well well-sm" style="background-color: #f8f9fa; border-left: 3px solid #28a745;">
                                        <p class="mb-0"><i class="entypo-comment"></i> {{ $order->special_instructions }}</p>
                                    </div>
                                </div>
                                @endif
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
                                @php
                                    // Check if any farmer order items have negotiated prices
                                    // Be more permissive - just check if negotiated_price exists and is > 0
                                    $hasNegotiatedPrices = false;
                                    foreach ($farmerOrderItems as $item) {
                                        // Check if negotiated_price exists and is greater than 0
                                        // Don't require it to be different from price, just that it exists
                                        if ($item->negotiated_price !== null
                                            && $item->negotiated_price !== ''
                                            && floatval($item->negotiated_price) > 0) {
                                            $hasNegotiatedPrices = true;
                                            break;
                                        }
                                    }

                                    // Also check all order items (in case negotiation flag is set but prices aren't on farmer items yet)
                                    $orderHasAnyNegotiation = false;
                                    if ($order->items && $order->items->count() > 0) {
                                        foreach ($order->items as $item) {
                                            // Check if negotiated_price exists and is greater than 0
                                            if ($item->negotiated_price !== null
                                                && $item->negotiated_price !== ''
                                                && floatval($item->negotiated_price) > 0) {
                                                $orderHasAnyNegotiation = true;
                                                break;
                                            }
                                        }
                                    }

                                    // Check is_negotiation flag - handle both boolean and integer (0/1) values
                                    $orderIsNegotiation = false;
                                    if ($order->is_negotiation === true
                                        || $order->is_negotiation === 1
                                        || $order->is_negotiation === '1'
                                        || (is_bool($order->is_negotiation) && $order->is_negotiation)) {
                                        $orderIsNegotiation = true;
                                    }

                                    // Show buttons if order is pending and (has negotiation flag OR farmer items have negotiated prices OR any items have negotiated prices)
                                    // Also check if any items show negotiated prices in the display (as a fallback)
                                    $hasDisplayNegotiation = false;
                                    foreach ($farmerOrderItems as $item) {
                                        // Check the actual database value
                                        $np = $item->getOriginal('negotiated_price') ?? $item->negotiated_price;
                                        if ($np !== null && $np !== '' && floatval($np) > 0) {
                                            $hasDisplayNegotiation = true;
                                            break;
                                        }
                                    }

                                    $showNegotiationButtons = $order->status === 'pending' && (
                                        $orderIsNegotiation
                                        || $hasNegotiatedPrices
                                        || $orderHasAnyNegotiation
                                        || $hasDisplayNegotiation
                                    );

                                    // Debug output (remove in production)
                                    if (config('app.debug')) {
                                        \Log::info('View Negotiation Check', [
                                            'order_id' => $order->id,
                                            'order_status' => $order->status,
                                            'is_negotiation_raw' => $order->is_negotiation,
                                            'is_negotiation_type' => gettype($order->is_negotiation),
                                            'orderIsNegotiation' => $orderIsNegotiation,
                                            'hasNegotiatedPrices' => $hasNegotiatedPrices,
                                            'orderHasAnyNegotiation' => $orderHasAnyNegotiation,
                                            'showNegotiationButtons' => $showNegotiationButtons,
                                            'farmer_items_count' => $farmerOrderItems->count(),
                                            'item_details' => $farmerOrderItems->map(function($item) {
                                                return [
                                                    'id' => $item->id,
                                                    'price' => $item->price,
                                                    'negotiated_price' => $item->negotiated_price,
                                                    'negotiated_price_type' => gettype($item->negotiated_price)
                                                ];
                                            })->toArray()
                                        ]);
                                    }
                                @endphp

                                {{-- Show negotiation buttons if order is pending and has negotiation indicators --}}
                                @if($showNegotiationButtons)
                                    <!-- Negotiation Actions -->
                                    <div class="form-group" style="background-color: #fff3cd; padding: 15px; border-radius: 5px; border: 2px solid #ffc107; margin-bottom: 20px;">
                                        <h5 class="text-warning" style="margin-top: 0;">
                                            <i class="entypo-hand"></i> <strong>Price Negotiation - Action Required</strong>
                                        </h5>
                                        <p class="text-muted">The buyer has proposed negotiated prices. Review the prices above and decide:</p>

                                        <form action="{{ route('farmer.orders.negotiation.accept', $order) }}" method="POST" class="mb-2" style="display: block;">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-lg btn-block" style="display: block; width: 100%;" onclick="return confirm('Accept this price negotiation? Stock will be reserved and order will be confirmed.')">
                                                <i class="entypo-check"></i> Accept Negotiation
                                            </button>
                                        </form>

                                        <button type="button" class="btn btn-danger btn-lg btn-block" style="display: block; width: 100%; margin-top: 10px;" data-toggle="modal" data-target="#rejectNegotiationModal">
                                            <i class="entypo-cancel"></i> Reject Negotiation
                                        </button>

                                        <div class="alert alert-info mt-3 mb-0" style="font-size: 0.9em;">
                                            <i class="entypo-info"></i> <strong>Note:</strong> Once you accept, the order will be confirmed with the negotiated prices and stock will be deducted.
                                        </div>
                                    </div>
                                    <hr>
                                @endif

                                <form action="{{ route('farmer.orders.update-status', $order) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="status" class="control-label"><strong>Update Status:</strong></label>
                                        <select name="status" id="status" class="form-control" onchange="this.form.submit()" {{ $showNegotiationButtons ? 'disabled' : '' }}>
                                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                        @if($showNegotiationButtons)
                                            <small class="text-muted">Please accept or reject the negotiation first.</small>
                                        @endif
                                    </div>
                                </form>

                                <hr>

                                <!-- Quick Action Buttons -->
                                <div class="form-group">
                                    @if(!$showNegotiationButtons)
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

                                        @if($order->status === 'pending' && !$showNegotiationButtons)
                                            <button type="button" class="btn btn-danger btn-block"
                                                    onclick="updateOrderStatus('cancelled')">
                                                <i class="entypo-cancel"></i> Cancel Order
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Reject Negotiation Modal -->
                        @if($showNegotiationButtons)
                        <div class="modal fade" id="rejectNegotiationModal" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Reject Price Negotiation</h5>
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('farmer.orders.negotiation.reject', $order) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <p>Are you sure you want to reject this price negotiation? The order will be cancelled.</p>
                                            <div class="form-group">
                                                <label for="rejection_reason">Reason (Optional):</label>
                                                <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" placeholder="Explain why you're rejecting this offer..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger">Reject Negotiation</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif

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