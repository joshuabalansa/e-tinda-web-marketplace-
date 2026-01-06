@extends('layouts.admin')
@section('content')

<!-- Welcome Section -->
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4>Admin Dashboard - System Overview</h4>
                </div>
                <div class="panel-options">
                    <span class="text-muted">{{ now()->format('F j, Y') }}</span>
                </div>
            </div>
            <div class="panel-body">
                <div class="well">
                    <h3>Welcome <strong>{{ ucwords(Auth::user()->name) }}</strong></h3>
                    <p class="text-muted">Monitor and manage the E-Tinda marketplace system from this comprehensive admin dashboard.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row">
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="panel panel-default">
            <div class="panel-body text-center">
                <div class="tile-progress">
                    <div class="tile-progressbar">
                        <span data-fill="85%" style="width: 85%;"></span>
                    </div>
                </div>
                <h3 class="text-primary">{{ $stats['total_users'] }}</h3>
                <p class="text-muted">Total Users</p>
                <small class="text-success">
                    <i class="entypo-up"></i> {{ $stats['total_farmers'] }} Farmers, {{ $stats['total_buyers'] }} Buyers
                </small>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="panel panel-default">
            <div class="panel-body text-center">
                <div class="tile-progress">
                    <div class="tile-progressbar">
                        <span data-fill="70%" style="width: 70%;"></span>
                    </div>
                </div>
                <h3 class="text-success">{{ $stats['total_products'] }}</h3>
                <p class="text-muted">Total Products</p>
                <small class="text-info">
                    <i class="entypo-newspaper"></i> Listed by farmers
                </small>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="panel panel-default">
            <div class="panel-body text-center">
                <div class="tile-progress">
                    <div class="tile-progressbar">
                        <span data-fill="60%" style="width: 60%;"></span>
                    </div>
                </div>
                <h3 class="text-info">{{ $stats['total_orders'] }}</h3>
                <p class="text-muted">Total Orders</p>
                <small class="text-warning">
                    <i class="entypo-mail"></i> {{ $stats['pending_orders'] }} Pending
                </small>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="panel panel-default">
            <div class="panel-body text-center">
                <div class="tile-progress">
                    <div class="tile-progressbar">
                        <span data-fill="45%" style="width: 45%;"></span>
                    </div>
                </div>
                <h3 class="text-warning">{{ $stats['total_forums'] }}</h3>
                <p class="text-muted">Forum Topics</p>
                <small class="text-primary">
                    <i class="entypo-chat"></i> Community discussions
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row">
    <div class="col-sm-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">Recent Orders</div>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Type</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recent_orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->user->name ?? 'Guest' }}</td>
                                <td>${{ number_format($order->total, 2) }}</td>
                                <td>
                                    <span class="label label-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : ($order->status === 'cancelled' ? 'danger' : 'info')) }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($order->is_negotiation)
                                        <span class="label label-warning">
                                            <i class="entypo-hand"></i> Negotiation
                                        </span>
                                    @else
                                        <span class="label label-default">Regular</span>
                                    @endif
                                </td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">System Status</div>
            </div>
            <div class="panel-body">
                <div class="list-group">
                    <div class="list-group-item">
                        <span class="badge badge-success">{{ $stats['completed_orders'] }}</span>
                        Completed Orders
                    </div>
                    <div class="list-group-item">
                        <span class="badge badge-warning">{{ $stats['pending_orders'] }}</span>
                        Pending Orders
                    </div>
                    <div class="list-group-item">
                        <span class="badge badge-info">{{ $stats['total_farmers'] }}</span>
                        Active Farmers
                    </div>
                    <div class="list-group-item">
                        <span class="badge badge-primary">{{ $stats['total_buyers'] }}</span>
                        Active Buyers
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .panel {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    .tile-progress {
        margin-bottom: 20px;
    }
    .btn-lg {
        padding: 20px;
        font-size: 1rem;
        border-radius: 8px;
    }
    .well {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 20px;
    }
    .badge {
        font-size: 0.75rem;
        font-weight: 500;
    }
    .table {
        margin-bottom: 0;
    }
    .table th {
        border-top: none;
        font-weight: 600;
    }
    .tile-progressbar {
        height: 4px;
        background: #e9ecef;
        border-radius: 2px;
        overflow: hidden;
        margin-bottom: 10px;
    }
    .tile-progressbar span {
        display: block;
        height: 100%;
        background: linear-gradient(90deg, #28a745, #20c997);
        border-radius: 2px;
        transition: width 0.3s ease;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize tile progress bars
    $('.tile-progressbar span').each(function() {
        var fill = $(this).data('fill');
        $(this).css('width', fill);
    });
});
</script>
@endpush
